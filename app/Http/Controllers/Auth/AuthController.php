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
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Jrean\UserVerification\Exceptions\UserNotFoundException;
use Jrean\UserVerification\Exceptions\UserIsVerifiedException;
use Jrean\UserVerification\Exceptions\TokenMismatchException;

class AuthController extends Controller implements UserCreatorListener
{
    use VerifiesUsers;
    protected $oauthDriver = ['github', 'weixin', 'weibo'];

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(User $userModel)
    {
        $this->middleware('guest', ['except' => ['logout', 'getVerification', 'emailVerificationRequired']]);
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

    public function emailVerificationRequired()
    {
        return view('auth.emailverificationrequired');
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
        if (! Session::has('oauthData')) {
            return redirect(route('login'));
        }

        $oauthData = array_merge(Session::get('oauthData'), Session::get('_old_input', []));
        return view('auth.signupconfirm', compact('oauthData'));
    }

    /**
     * Actually creates the new user account
     */
    public function store(StoreUserRequest $request)
    {
        if (! Session::has('oauthData')) {
            return redirect(route('login'));
        }
        $oauthUser = array_merge(Session::get('oauthData'), $request->only('name', 'email'));
        $oauthUser = array_only($oauthUser, array_keys($request->rules()));

        return app(\Phphub\Creators\UserCreator::class)->create($this, $oauthUser);
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
        Session::forget('oauthData');

        Flash::success(lang('Congratulations and Welcome!'));

        return redirect(route('users.edit', Auth::user()->id));
    }

    /**
     * ----------------------------------------
     * GithubAuthenticatorListener Delegate
     * ----------------------------------------
     */

    // 数据库找不到用户, 执行新用户注册
    public function userNotFound($driver, $registerUserData)
    {
        if ($driver == 'github') {
            $oauthData = $registerUserData->user;
            $oauthData['image_url'] = $registerUserData->user['avatar_url'];
            $oauthData['github_id'] = $registerUserData->user['id'];
            $oauthData['github_url'] = $registerUserData->user['url'];
            $oauthData['github_name'] = $registerUserData->nickname;
        } elseif ($driver == 'weixin') {
            $oauthData['image_url'] = $registerUserData->avatar;
            $oauthData['wechat_openid'] = $registerUserData->id;
            $oauthData['name'] = $registerUserData->nickname;
            $oauthData['email'] = $registerUserData->email;
            $oauthData['wechat_unionid'] = $registerUserData->user['unionid'];
        }

        $oauthData['driver'] = $driver;
        Session::put('oauthData', $oauthData);

        return redirect(route('signup'));
    }

    // 数据库有用户信息, 登录用户
    public function userFound($user)
    {
        Auth::loginUsingId($user->id);
        Session::forget('oauthData');

        Flash::success(lang('Login Successfully.'));

        return redirect(route('users.edit', Auth::user()->id));
    }

    // 用户屏蔽
    public function userIsBanned($user)
    {
        return redirect(route('user-banned'));
    }

    /**
     * ----------------------------------------
     * Oauth Login Logic
     * ----------------------------------------
     */

    public function oauth(Request $request)
    {
        $driver = $request->input('driver');
        $driver = !in_array($driver, $this->oauthDriver)
                    ? $this->oauthDriver[0]
                    : $driver;
        return Socialite::driver($driver)->redirect();
    }

    public function callback(Request $request)
    {
        $driver = $request->input('driver');

        if (!in_array($driver, $this->oauthDriver)) {
            return redirect()->intended('/');
        }
        $oauthUser = Socialite::with($driver)->user();
        $user = User::getByDriver($driver, $oauthUser->id);
        if ($user) {
            return $this->loginUser($user);
        }

        return $this->userNotFound($driver, $oauthUser);
    }

    /**
     * ----------------------------------------
     * Email Validation
     * ----------------------------------------
     */
    public function getVerification(Request $request, $token)
    {
        $this->validateRequest($request);
        try {
            UserVerification::process($request->input('email'), $token, 'users');
            Flash::success(lang('Email validation successed.'));
            return redirect('/');
        } catch (UserNotFoundException $e) {
            Flash::error(lang('Email not found'));
            return redirect('/');
        } catch (UserIsVerifiedException $e) {
            Flash::success(lang('Email validation successed.'));
            return redirect('/');
        } catch (TokenMismatchException $e) {
            Flash::error(lang('Token mismatch'));
            return redirect('/');
        }

        return redirect('/');
    }
}
