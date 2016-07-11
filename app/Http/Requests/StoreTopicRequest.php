<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreTopicRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'       => 'required|min:2',
            'body'        => 'required|min:2',
            'category_id' => 'required|numeric'
        ];
    }
}
