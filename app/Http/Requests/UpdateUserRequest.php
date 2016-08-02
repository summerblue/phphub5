<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request
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
            'email'           => 'email|required|unique:users,email,' . $this->id,
            'github_url'      => 'url',
            'image_url'       => 'url',
            'wechat_unionid'  => 'string',
            'linkedin'        => 'url',
            'payment_qrcode'  => 'image',
        ];
    }
}
