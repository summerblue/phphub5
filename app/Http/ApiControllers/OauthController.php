<?php

namespace App\Http\ApiControllers;

use Response;
use Authorizer;

class OauthController extends Controller
{
    public function issueAccessToken()
    {
        return Response::json(Authorizer::issueAccessToken());
    }
}
