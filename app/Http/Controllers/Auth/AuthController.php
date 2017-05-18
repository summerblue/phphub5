<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
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
use App\Http\Controllers\Traits\SocialiteHelper;

class AuthController extends Controller implements UserCreatorListener
{
    use VerifiesUsers,SocialiteHelper,AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/topics';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(User $userModel)
    {
        $this->middleware('guest', ['except' => ['logout', 'oauth', 'callback', 'getVerification', 'userBanned']]);
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

    public function signin()
    {
        return view('auth.signin');
    }

    public function signinStore()
    {
        return view('auth.signin');
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
            return redirect()->route('login');
        }

        $oauthData = array_merge(Session::get('oauthData'), Session::get('_old_input', []));
        return view('auth.signupconfirm', compact('oauthData'));
    }

    /**
     * Actually creates the new user account
     */
    public function createNewUser(StoreUserRequest $request)
    {
        if (! Session::has('oauthData')) {
            return redirect()->route('login');
        }
        $oauthUser = array_merge(Session::get('oauthData'), $request->only('name', 'email', 'password'));
        $userData = array_only($oauthUser, array_keys($request->rules()));
        $userData['register_source'] = $oauthUser['driver'];

        return app(\Phphub\Creators\UserCreator::class)->create($this, $userData);
    }

    public function userBanned()
    {
        if (Auth::check() && Auth::user()->is_banned == 'no') {
            return redirect(route('home'));
        }

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
    public function userNotFound($driver, $registerUserData)
    {
        if ($driver == 'github') {
            $oauthData['image_url'] = $registerUserData->user['avatar_url'];
            $oauthData['github_id'] = $registerUserData->user['id'];
            $oauthData['github_url'] = $registerUserData->user['url'];
            $oauthData['github_name'] = $registerUserData->nickname;
            $oauthData['name'] = $registerUserData->user['name'];
            $oauthData['email'] = $registerUserData->user['email'];
        } elseif ($driver == 'wechat') {
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
        Auth::login($user, true);
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
