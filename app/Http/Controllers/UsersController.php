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

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index()
    {
        $users = User::recent()->take(48)->get();

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user    = User::findOrFail($id);
        $topics  = Topic::whose($user->id)->recent()->limit(10)->get();
        $replies = Reply::whose($user->id)->recent()->limit(10)->get();
        return view('users.show', compact('user', 'topics', 'replies'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user', 'topics', 'replies'));
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = $request->only('github_name', 'real_name', 'city', 'company', 'twitter_account', 'personal_website', 'introduction');

        $user->update($data);

        Flash::success(lang('Operation succeeded.'));

        return redirect(route('users.show', $id));
    }

    public function destroy($id)
    {
        $this->authorize('update', $topic->user);
    }

    public function replies($id)
    {
        $user = User::findOrFail($id);
        $replies = Reply::whose($user->id)->recent()->paginate(15);

        return view('users.replies', compact('user', 'replies'));
    }

    public function topics($id)
    {
        $user = User::findOrFail($id);
        $topics = Topic::whose($user->id)->recent()->paginate(15);

        return view('users.topics', compact('user', 'topics'));
    }

    public function favorites($id)
    {
        $user = User::findOrFail($id);
        $topics = $user->favoriteTopics()->paginate(15);

        return view('users.favorites', compact('user', 'topics'));
    }

    public function following($id)
    {
        $user = User::findOrFail($id);
        $followings = $user->followings()->get();
        // TODO @by summer Add followings view
    }

    public function accessTokens($id)
    {
        if (!Auth::check() || Auth::id() != $id) {
            return redirect(route('users.show', $id));
        }
        $user = User::findOrFail($id);
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
        $user = User::findOrFail($id);
        $user->is_banned = $user->is_banned == 'yes' ? 'no' : 'yes';
        $user->save();

        return redirect(route('users.show', $id));
    }

    public function githubApiProxy($username)
    {
        $cache_name = 'github_api_proxy_user_'.$username;
        return Cache::remember($cache_name, 1440, function () use ($username) {
            $result = (new GithubUserDataReader())->getDataFromUserName($username);
            return response()->json($result);
        });
    }

    public function githubCard()
    {
        return view('users.github-card');
    }

    public function refreshCache($id)
    {
        $user =  User::findOrFail($id);

        $user_info = (new GithubUserDataReader())->getDataFromUserName($user->github_name);

        // Refresh the GitHub card proxy cache.
        $cache_name = 'github_api_proxy_user_'.$user->github_name;
        Cache::put($cache_name, $user_info, 1440);

        // Refresh the avatar cache.
        $user->image_url = $user_info['avatar_url'];
        $user->cacheAvatar();
        $user->save();

        Flash::message(lang('Refresh cache success'));

        return redirect(route('users.edit', $id));
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
}
