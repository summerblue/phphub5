<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Blog;
use App\Models\User;
use Auth;

class BlogStoreRequest extends Request
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
                    'slug'            => 'between:2,25|regex:/^[A-Za-z0-9\-\_]+$/|required|unique:blogs',
                    'name'            => 'between:2,20|required|unique:blogs',
                    'description'     => 'max:250',
                    'cover'           => 'required|image',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $blog = Blog::findOrFail($this->route('id'));
                return [
                    'slug'            => 'between:2,25|regex:/^[A-Za-z0-9\-\_]+$/|required|unique:blogs,slug,' . $blog->id,
                    'name'            => 'between:2,20|required|unique:blogs,name,' . $blog->id,
                    'description'     => 'max:250',
                    'cover'           => 'image',
                ];
            }
            default:break;
        }
    }

    public function performUpdate(Blog $blog)
    {
		$blog->name = $this->input("name");
        $blog->slug = $this->input("slug");
        $blog->user_id = $blog->user_id ?: Auth::id();
        $blog->description = $this->input("description");

        if ($file = $this->file('cover')) {
            $upload_status = app('Phphub\Handler\ImageUploadHandler')->uploadImage($file);
            $blog->cover = $upload_status['filename'];
        }

		return $blog->save();
    }
}
