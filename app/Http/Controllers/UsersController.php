<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;
use Illuminate\Http\Request;
use Phphub\Github\GithubUserDataReader;
use Cache;
use Auth;
use Flash;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\SendActivateMail;
use Phphub\Handler\Exception\ImageUploadException;
use App\Activities\UserFollowedUser;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
                'index', 'show', 'replies',
                 'topics', 'articles', 'votes', 'following',
                 'followers', 'githubCard', 'githubApiProxy',
            ]]);
    }

    public function index()
    {
        $users = User::recent()->take(48)->get();

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user    = User::findOrFail($id);
        $topics  = Topic::whose($user->id)->withoutArticle()->withoutBoardTopics()->recent()->limit(20)->get();
        $articles  = Topic::whose($user->id)->onlyArticle()->withoutDraft()->recent()->with('blogs')->limit(20)->get();
        $blog  = $user->blogs()->first();
        $replies = Reply::whose($user->id)->recent()->limit(20)->get();
        return view('users.show', compact('user','blog', 'articles', 'topics', 'replies'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user', 'topics', 'replies'));
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        try {
            $request->performUpdate($user);
            Flash::success(lang('Operation succeeded.'));
        } catch (ImageUploadException $exception) {
            Flash::error(lang($exception->getMessage()));
        }

        return redirect(route('users.edit', $id));
    }

    public function replies($id)
    {
        $user    = User::findOrFail($id);
        $replies = Reply::whose($user->id)->recent()->paginate(15);

        return view('users.replies', compact('user', 'replies'));
    }

    public function topics($id)
    {
        $user   = User::findOrFail($id);
        $topics = Topic::whose($user->id)->withoutArticle()->withoutBoardTopics()->recent()->paginate(30);

        return view('users.topics', compact('user', 'topics'));
    }

    public function articles($id)
    {
        $user   = User::findOrFail($id);
        $topics = Topic::whose($user->id)->onlyArticle()->withoutDraft()->recent()->with('blogs')->paginate(30);
        $user->update(['article_count' => $topics->total()]);
        return view('users.articles', compact('user','blog', 'topics'));
    }

    public function drafts()
    {
        $user   = Auth::user();
        $topics = $user->topics()->onlyArticle()->draft()->recent()->paginate(30);
        $blog   = $user->blogs()->first();

        $user->draft_count = $user->topics()->onlyArticle()->draft()->count();
        $user->save();

        return view('users.articles', compact('user','blog', 'topics'));
    }

    public function votes($id)
    {
        $user   = User::findOrFail($id);
        $topics = $user->votedTopics()->orderBy('pivot_created_at', 'desc')->paginate(30);

        return view('users.votes', compact('user', 'topics'));
    }

    public function following($id)
    {
        $user  = User::findOrFail($id);
        $users = $user->followings()->orderBy('id', 'desc')->paginate(15);

        return view('users.following', compact('user', 'users'));
    }

    public function followers($id)
    {
        $user  = User::findOrFail($id);
        $users = $user->followers()->orderBy('id', 'desc')->paginate(15);

        return view('users.followers', compact('user', 'users'));
    }

    public function accessTokens($id)
    {
        if (!Auth::check() || Auth::id() != $id) {
            return redirect(route('users.show', $id));
        }
        $user     = User::findOrFail($id);
        $sessions = OAuthSession::where([
            'owner_type' => 'user',
            'owner_id'   => Auth::id(),
        ])
            ->with('token')
            ->lists('id') ?: [];

        $tokens = AccessToken::whereIn('session_id', $sessions)->get();

        return view('users.access_tokens', compact('user', 'tokens'));
    }

    public function revokeAccessToken($token)
    {
        $access_token = AccessToken::with('session')->find($token);

        if (!$access_token || !Auth::check() || $access_token->session->owner_id != Auth::id()) {
            Flash::error(lang('Revoke Failed'));
        } else {
            $access_token->delete();
            Flash::success(lang('Revoke success'));
        }

        return redirect(route('users.access_tokens', Auth::id()));
    }

    public function blocking($id)
    {
        $user            = User::findOrFail($id);
        $user->is_banned = $user->is_banned == 'yes' ? 'no' : 'yes';
        $user->save();

        // 用户被屏蔽后屏蔽用户所有内容，解封时解封所有内容
        $user->topics()->update(['is_blocked' => $user->is_banned]);
        $user->replies()->update(['is_blocked' => $user->is_banned]);

        return redirect(route('users.show', $id));
    }

    public function editEmailNotify($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_email_notify', compact('user'));
    }

    public function updateEmailNotify($id, Request $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $user->email_notify_enabled = $request->email_notify_enabled == 'on' ? 'yes' : 'no';
        $user->save();

        Flash::success(lang('Operation succeeded.'));

        return redirect(route('users.edit_email_notify', $id));
    }

    public function editPassword($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_password', compact('user'));
    }

    public function updatePassword($id, ResetPasswordRequest $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $user->password = bcrypt($request->password);
        $user->save();

        Flash::success(lang('Operation succeeded.'));

        return redirect(route('users.edit_password', $id));
    }

    public function githubApiProxy($username)
    {
        $cache_name = 'github_api_proxy_user_' . $username;

        return Cache::remember($cache_name, 1440, function () use ($username) {
            $result = (new GithubUserDataReader())->getDataFromUserName($username);

            return response()->json($result);
        });
    }

    public function regenerateLoginToken()
    {
        if (Auth::check()) {
            Auth::user()->login_token = str_random(rand(20, 32));
            Auth::user()->save();
            Flash::success(lang('Regenerate succeeded.'));
        } else {
            Flash::error(lang('Regenerate failed.'));
        }

        return redirect(route('users.show', Auth::id()));
    }

    public function doFollow($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->isFollowing($id)) {
            Auth::user()->unfollow($id);
            app(UserFollowedUser::class)->remove(Auth::user(), $user);
        } else {
            Auth::user()->follow($id);
            app('Phphub\Notification\Notifier')->newFollowNotify(Auth::user(), $user);
            app(UserFollowedUser::class)->generate(Auth::user(), $user);

        }

        $user->update(['follower_count' => $user->followers()->count()]);
        Flash::success(lang('Operation succeeded.'));

        return redirect()->back();
    }

    public function editAvatar($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_avatar', compact('user'));
    }

    public function updateAvatar($id, Request $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        if ($file = $request->file('avatar')) {
            try {
                $user->updateAvatar($file);
                Flash::success(lang('Update Avatar Success'));
            } catch (ImageUploadException $exception) {
                Flash::error(lang($exception->getMessage()));
            }
        } else {
            Flash::error(lang('Update Avatar Failed'));
        }

        return redirect(route('users.edit_avatar', $id));
    }

    public function sendVerificationMail()
    {
        $user      = Auth::user();
        $cache_key = 'send_activite_mail_' . $user->id;
        if (Cache::has($cache_key)) {
            Flash::error(lang('The mail send failed! Please try again in 60 seconds.', ['seconds' => (Cache::get($cache_key) - time())]));
        } else {
            if (!$user->email) {
                Flash::error(lang('The mail send failed! Please fill in your email address first.'));
            } else {
                if (!$user->verified) {
                    dispatch(new SendActivateMail($user));
                    Flash::success(lang('The mail sent successfully.'));
                    Cache::put($cache_key, time() + 60, 1);
                }
            }
        }

        return redirect()->intended('/');
    }

    public function editSocialBinding($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit_social_binding', compact('user'));
    }

    public function emailVerificationRequired()
    {
        if (\Auth::user()->verified) {
            return redirect()->intended('/');
        }

        return view('users.emailverificationrequired');
    }
}
