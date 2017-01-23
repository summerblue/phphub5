<?php

namespace Phphub\OAuth;

use App\Models\User;

class LoginTokenVerifier
{
    public function verify($user_id, $login_token)
    {
        $user = User::query()
            ->where(['id' => $user_id])
            ->first(['id', 'login_token']);

        if (count($user) && $user->login_token === $login_token) {
            return $user->id;
        }

        return false;
    }
}
