<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;
use Auth;
use Flash;
use App\Http\Requests\BlogStoreRequest;
use App\Activities\UserSubscribedBlog;

class BlogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function create()
    {
        $user = Auth::user();
        $blog = Blog::firstOrNew(['user_id' => Auth::id()]);
        return view('blogs.create_edit', compact('user', 'blog'));
    }

	public function show($name)
	{
        $blog = Blog::where('slug', $name)->firstOrFail();
        $user   = $blog->user;
        $topics = $blog->topics()->onlyArticle()->withoutDraft()->recent()->paginate(28);

        $authors = $blog->authors;

        $blog->update(['article_count' => $topics->total()]);

        return view('blogs.show', compact('user','blog', 'topics', 'authors'));
	}

	public function store(BlogStoreRequest $request)
	{
		$blog = new Blog();

        try {
            $request->performUpdate($blog);
            Flash::success(lang('Operation succeeded.'));
        } catch (ImageUploadException $exception) {
            Flash::error(lang($exception->getMessage()));
            return redirect()->back()->withInput($request->input());
        }

		return redirect()->route('blogs.edit', $blog->id);
	}

	public function edit($id)
	{
        $blog = Blog::findOrFail($id);
        $this->authorize('update', $blog);
        $user = Auth::user();
		return view('blogs.create_edit', compact('blog', 'user'));
	}

	public function update(BlogStoreRequest $request, $id)
	{
		$blog = Blog::findOrFail($id);
        $this->authorize('update', $blog);
        try {
            $request->performUpdate($blog);
            Flash::success(lang('Operation succeeded.'));
        } catch (ImageUploadException $exception) {
            Flash::error(lang($exception->getMessage()));
            return redirect()->back()->withInput($request->input());
        }

		return redirect()->route('blogs.edit', $blog->id);
	}

    public function subscribe($id)
    {
        $blog = Blog::findOrFail($id);
        Auth::user()->subscribes()->attach($blog->id);
        $blog->increment('subscriber_count', 1);
        Flash::success("订阅成功");
        app(UserSubscribedBlog::class)->generate(Auth::user(), $blog);
        return redirect()->back();
    }

    public function unsubscribe($id)
    {
        $blog = Blog::findOrFail($id);
        Auth::user()->subscribes()->detach($blog->id);
        $blog->decrement('subscriber_count', 1);
        Flash::success("成功取消订阅");
        app(UserSubscribedBlog::class)->remove(Auth::user(), $blog);
        return redirect()->back();
    }
}
