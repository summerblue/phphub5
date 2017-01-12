<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'showPost']]);
    }

	public function index()
	{
		$blogs = Blog::orderBy('id', 'desc')->paginate(10);

		return view('blogs.index', compact('blogs'));
	}

	public function create()
	{
		return view('blogs.create');
	}

	public function store(Request $request)
	{
		$blog = new Blog();

		$blog->name = $request->input("name");
        $blog->slug = $request->input("slug");
        $blog->description = $request->input("description");
        $blog->cover = $request->input("cover");
        $blog->user_id = $request->input("user_id");
        $blog->article_count = $request->input("article_count");
        $blog->subscriber_count = $request->input("subscriber_count");
        $blog->is_recommended = $request->input("is_recommended");
        $blog->is_blocked = $request->input("is_blocked");

		$blog->save();

		return redirect()->route('blogs.index')->with('message', 'Item created successfully.');
	}

	public function show($id)
	{
		$blog = Blog::findOrFail($id);

		return view('blogs.show', compact('blog'));
	}

	public function edit($id)
	{
		$blog = Blog::findOrFail($id);

		return view('blogs.edit', compact('blog'));
	}

	public function update(Request $request, $id)
	{
		$blog = Blog::findOrFail($id);

		$blog->name = $request->input("name");
        $blog->slug = $request->input("slug");
        $blog->description = $request->input("description");
        $blog->cover = $request->input("cover");
        $blog->user_id = $request->input("user_id");
        $blog->article_count = $request->input("article_count");
        $blog->subscriber_count = $request->input("subscriber_count");
        $blog->is_recommended = $request->input("is_recommended");
        $blog->is_blocked = $request->input("is_blocked");

		$blog->save();

		return redirect()->route('blogs.index')->with('message', 'Item updated successfully.');
	}

	public function createPost()
	{
        $user = User::findOrFail(Auth::id());
		return view('blogs.create_edit_post', compact('user'));
	}
}
