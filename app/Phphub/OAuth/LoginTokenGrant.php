<?php

namespace Phphub\OAuth;

use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Server\Exception\InvalidRequestException;

class LoginTokenGrant extends BaseGrant
{

    protected $identifier = 'login_token';

    public function getUserId(Request $request, $verifier)
    {
        $username = $this->server->getRequest()->request->get('username', null);
        if (is_null($username)) {
            throw new InvalidRequestException('username');
        }

        $login_token = $this->server->getRequest()->request->get('login_token', null);
        if (is_null($login_token)) {
            throw new InvalidRequestException('login_token');
        }

        return call_user_func($verifier, $username, $login_token);
    }
}
