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
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function create()
	{
        $user = Auth::user();
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

	public function edit()
	{
        $user = Auth::user();
        $blog = $user->blogs()->first();
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

		return redirect()->route('blogs.edit');
	}
}
