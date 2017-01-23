<?php

namespace Phphub\OAuth;

use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Server\Exception\InvalidRequestException;

class LoginTokenGrant extends BaseGrant
{

    protected $identifier = 'login_token';

    public function getUserId(Request $request, $verifier)
    {
        // get('username') 为客户端的兼容写法
        // 修改为 user id 会更加稳定
        $user_id = $this->server->getRequest()->request->get('username', null);
        if (is_null($user_id)) {
            throw new InvalidRequestException('user_id');
        }

        $login_token = $this->server->getRequest()->request->get('login_token', null);
        if (is_null($login_token)) {
            throw new InvalidRequestException('login_token');
        }

        return call_user_func($verifier, $user_id, $login_token);
    }
}
