<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Link;

class CategoriesController extends Controller
{

    protected $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        $filter   = $this->topic->present()->getTopicFilter();
        $topics   = $this->topic->getCategoryTopicsWithFilter($filter, $id);
        $links    = Link::allFromCache();
        
        return view('topics.index', compact('topics', 'category', 'links'));
    }
}
