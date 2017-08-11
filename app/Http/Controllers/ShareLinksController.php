<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Link;
use App\Models\Banner;

class ShareLinksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function createLink()
    {
        return view('share_links.create_edit');
    }

    public function edit($id, Request $request, Topic $topic)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);
        $categories = Category::where('id', '!=', config('phphub.blog_category_id'))->get();
        $category = $topic->category;

        $topic->body = $topic->body_original;

        return view('share_links.create_edit', compact('topic', 'categories', 'category'));
    }
}
