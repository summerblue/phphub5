<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Session;
use Auth;
use Flash;
use App\Http\Requests\StoreUserRequest;
use Phphub\Listeners\UserCreatorListener;

class AuthController extends Controller implements UserCreatorListener
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(User $userModel)
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        // Redirect from Github
        if ($request->input('code')) {
            $githubUser = Socialite::driver('github')->user();
            $user = User::getByGithubId($githubUser->id);

            if ($user) {
                return $this->loginUser($user);
            }

            return $this->userNotFound($githubUser);
        }

        // redirect to the github authentication url
        return Socialite::driver('github')->redirect();
    }

    private function loginUser($user)
    {
        if ($user->is_banned == 'yes') {
            return $this->userIsBanned($user);
        }

        return $this->userFound($user);
    }

    public function logout()
    {
        Auth::logout();
        Flash::success(lang('Operation succeeded.'));
        return redirect(route('home'));
    }

    public function loginRequired()
    {
        return view('auth.loginrequired');
    }

    public function adminRequired()
    {
        return view('auth.adminrequired');
    }

    /**
     * Shows a user what their new account will look like.
     */
    public function create()
    {
        if (! Session::has('userGithubData')) {
            return redirect(route('login'));
        }

        $githubUser = array_merge(Session::get('userGithubData'), Session::get('_old_input', []));
        return view('auth.signupconfirm', compact('githubUser'));
    }

    /**
     * Actually creates the new user account
     */
    public function store(StoreUserRequest $request)
    {
        if (! Session::has('userGithubData')) {
            return redirect(route('login'));
        }
        $githubUser = array_merge(Session::get('userGithubData'), $request->only('github_id', 'name', 'github_name', 'email'));
        $githubUser = array_only($githubUser, array_keys($request->rules()));

        return app(\Phphub\Creators\UserCreator::class)->create($this, $githubUser);
    }

    public function userBanned()
    {
        if (Auth::check() && Auth::user()->is_banned == 'no') {
            return redirect(route('home'));
        }

        //force logout
        Auth::logout();
        return view('auth.userbanned');
    }

    /**
     * ----------------------------------------
     * UserCreatorListener Delegate
     * ----------------------------------------
     */

    public function userValidationError($errors)
    {
        return redirect('/');
    }

    public function userCreated($user)
    {
        Auth::login($user, true);
        Session::forget('userGithubData');

        Flash::success(lang('Congratulations and Welcome!'));

        return redirect()->intended('/');
    }

    /**
     * ----------------------------------------
     * GithubAuthenticatorListener Delegate
     * ----------------------------------------
     */

    // 数据库找不到用户, 执行新用户注册
    public function userNotFound($githubData)
    {
        $userGithubData = $githubData->user;
        $userGithubData['image_url'] = $githubData->user['avatar_url'];
        $userGithubData['github_id'] = $githubData->user['id'];
        $userGithubData['github_url'] = $githubData->user['url'];
        $userGithubData['github_name'] = $githubData->nickname;

        Session::put('userGithubData', $userGithubData);
        return redirect(route('signup'));
    }

    // 数据库有用户信息, 登录用户
    public function userFound($user)
    {
        Auth::loginUsingId($user->id);
        Session::forget('userGithubData');

        Flash::success(lang('Login Successfully.'));
        show_crx_hint();

        return redirect()->intended('/');
    }

    // 用户屏蔽
    public function userIsBanned($user)
    {
        return redirect(route('user-banned'));
    }
}
