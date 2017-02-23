<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Phphub\Core\CreatorListener;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Models\Topic;
use App\Models\Banner;
use Illuminate\Http\Request;
use Auth;
use Flash;
use Phphub\Markdown\Markdown;

use App\Http\Requests\StoreTopicRequest;

class ArticlesController extends Controller implements CreatorListener
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function create()
	{
        $user = Auth::user();
        if ($user->blogs()->count() <= 0) {
            Flash::info('请先创建专栏，专栏创建成功后才能发布文章。');
            return redirect()->route('blogs.create');
        }
        $topic = new Topic;
		return view('articles.create_edit', compact('topic', 'user'));
	}

	public function store(StoreTopicRequest $request)
	{
        $data = $request->except('_token');
        if ($request->subject == 'draft') {
            $data['is_draft'] = 'yes';
        }
        return app('Phphub\Creators\TopicCreator')->create($this, $data);
	}

	public function transform($id)
	{
        Auth::user()->decrement('topic_count', 1);
        Auth::user()->increment('article_count', 1);

        if (Auth::user()->blogs()->count() <= 0) {
            Flash::info('请先创建专栏，专栏创建成功后才能发布文章。');
            return redirect()->route('blogs.create');
        }
        $topic = Topic::find($id);
        $topic->update([
            'category_id' => config('phphub.blog_category_id')
        ]);
        Flash::success(lang('Operation succeeded.'));
        return redirect()->route('articles.show', [$topic->id]);
	}

	public function show($id)
	{
        // See: TopicsController->show
	}

	public function edit($id)
	{
		$topic = Topic::findOrFail($id);
		return view('articles.create_edit', compact('topic'));
	}

	public function update($id, StoreTopicRequest $request)
	{
        // See: TopicsController->update
	}

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */

    public function creatorFailed($error)
    {
        Flash::error('发布失败：' . $error);
        return redirect()->back();
    }

    public function creatorSucceed($topic)
    {
        Flash::success(lang('Operation succeeded.'));
        return redirect()->route('articles.show', [$topic->id]);
    }
}
