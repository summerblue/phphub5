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
            'github_url'      => 'url',
            'image_url'       => 'url',
            'wechat_unionid'  => 'string',
            'password'        => 'required|confirmed|min:6',
        ];
    }
}
