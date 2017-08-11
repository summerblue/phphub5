<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Topic;

class StoreTopicRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            // Crate
            case 'POST':
            {
                return [
                    'title'       => 'required|min:2',
                    'body'        => 'min:2',
                    'category_id' => 'required|numeric',
                    'link'        => 'url|unique:share_links',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $topic = Topic::findOrFail($this->route('id'));
                if ($topic->isShareLink()) {
                    return [
                        'title'       => 'required|min:2',
                        'body'        => 'min:2',
                        'category_id' => 'required|numeric',
                        'link'        => 'url|unique:share_links,link,' . $topic->share_link->id,
                    ];
                } else {
                    return [
                        'title'       => 'required|min:2',
                        'body'        => 'min:2',
                        'category_id' => 'required|numeric',
                    ];
                }
            }
            default:break;
        }
    }
}
