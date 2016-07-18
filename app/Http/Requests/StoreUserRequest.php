<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreUserRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'github_id'       => 'unique:users',
            'github_name'     => 'string',
            'wechat_openid'   => 'string',
            'name'            => 'alpha_num|required|unique:users',
            'email'           => 'email|required|unique:users',
            'github_url'      => 'active_url',
            'image_url'       => 'active_url',
        ];
    }
}
