<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Flash;
use App\Http\Requests\BlogStoreRequest;

class BlogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'showPost']]);
    }

	// public function index()
	// {
	// 	$blogs = Blog::orderBy('id', 'desc')->paginate(10);
    //
	// 	return view('blogs.index', compact('blogs'));
	// }

	public function create()
	{
        $user = Auth::user();
        // if ($user->blogs()->count() > 0) {
        //     Flash::error('目前只允许创建一个专栏！');
        //     return redirect()->route('home');
        // }
        $blog = Blog::firstOrNew(['user_id' => Auth::id()]);
    	return view('blogs.create_edit', compact('user', 'blog'));
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

		return redirect()->route('blogs.edit');
	}

	// public function show($id)
	// {
	// 	$blog = Blog::findOrFail($id);
    //
	// 	return view('blogs.show', compact('blog'));
	// }
    //
	public function edit()
	{
		$blog = Blog::first();
        $user = Auth::user();
		return view('blogs.create_edit', compact('blog', 'user'));
	}

	public function update(BlogStoreRequest $request, $id)
	{
		$blog = Blog::findOrFail($id);

        try {
            $request->performUpdate($blog);
            Flash::success(lang('Operation succeeded.'));
        } catch (ImageUploadException $exception) {
            Flash::error(lang($exception->getMessage()));
            return redirect()->back()->withInput($request->input());
        }

		return redirect()->route('blogs.edit');
	}
    //
	// public function createPost()
	// {
    //     $user = User::findOrFail(Auth::id());
	// 	return view('blogs.create_edit_post', compact('user'));
	// }
}
