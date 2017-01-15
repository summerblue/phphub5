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
        $this->middleware('auth', ['except' => ['index', 'show', 'showPost']]);
    }

	// public function index()
	// {
	// 	$articles = Blog::orderBy('id', 'desc')->paginate(10);
    //
	// 	return view('articles.index', compact('articles'));
	// }

	public function create()
	{
        $user = Auth::user();
        if ($user->blogs()->count() <= 0) {
            Flash::info('请先才创建专栏，专栏创建成功后才能发布文章。');
            return redirect()->route('blogs.create');
        }
        $topic = new Topic;
		return view('articles.create_edit', compact('topic', 'user'));
	}

	public function store(StoreTopicRequest $request)
	{
        return app('Phphub\Creators\TopicCreator')->create($this, $request->except('_token'));
	}

	public function show($id)
	{
        $topic = Topic::where('id', $id)->with('user', 'lastReplyUser')->firstOrFail();

        if ($topic->user->is_banned == 'yes') {
            Flash::error('你访问的文章已被屏蔽，有疑问请发邮件：all@estgroupe.com');
            return redirect(route('topics.index'));
        }

        if (
            config('app.admin_board_cid')
            && $topic->id == config('app.admin_board_cid')
            && (!Auth::check() || !Auth::user()->can('access_board'))
        ) {
            Flash::error('您没有权限访问该文章，有疑问请发邮件：all@estgroupe.com');
            return redirect(route('topics.index'));
        }

        $randomExcellentTopics = $topic->getRandomExcellent();
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));
        $categoryTopics = $topic->getSameCategoryTopics();
        $userTopics = $topic->byWhom($topic->user_id)->with('user')->withoutBoardTopics()->recent()->limit(3)->get();
        $votedUsers = $topic->votes()->orderBy('id', 'desc')->with('user')->get()->pluck('user');
        $revisionHistory = $topic->revisionHistory()->orderBy('created_at', 'DESC')->first();
        $topic->increment('view_count', 1);

        $banners  = Banner::allByPosition();
        $user = $topic->user;
        $blog = $user->blogs()->first();
        return view('articles.show', compact(
                            'blog', 'user','topic', 'replies', 'categoryTopics',
                            'category', 'banners', 'randomExcellentTopics',
                            'votedUsers', 'userTopics', 'revisionHistory'));
	}

	public function edit($id)
	{
		$topic = Topic::findOrFail($id);
		return view('articles.create_edit', compact('topic'));
	}

	public function update($id, StoreTopicRequest $request)
	{
        $topic = Topic::findOrFail($id);
        // $this->authorize('update', $topic);

        $data = $request->only('title', 'body', 'category_id');

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $topic->update($data);

        Flash::success(lang('Operation succeeded.'));
        return redirect()->route('articles.show', array($topic->id));
	}

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */

    public function creatorFailed($errors)
    {
        Flash::error('发布失败，未知错误。');
        return redirect('/');
    }

    public function creatorSucceed($topic)
    {
        Flash::success(lang('Operation succeeded.'));
        return redirect()->route('articles.show', array($topic->id));
    }
}
