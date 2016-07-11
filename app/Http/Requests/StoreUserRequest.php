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
            'github_id'   => 'required|unique:users',
            'github_name' => 'required',
            'name'        => 'alpha_num|required|unique:users',
            'email'       => 'email',
            'github_url'  => 'active_url',
            'image_url'   => 'active_url',
        ];
    }
}
