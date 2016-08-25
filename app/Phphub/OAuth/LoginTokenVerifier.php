<?php

namespace Phphub\OAuth;

use App\Models\User;

class LoginTokenVerifier
{
    public function verify($github_name, $login_token)
    {
        $user = User::query()
            ->where(['github_name' => $github_name])
            ->first(['id', 'login_token']);

        if (count($user) && $user->login_token === $login_token) {
            return $user->id;
        }

        return false;
    }
}
