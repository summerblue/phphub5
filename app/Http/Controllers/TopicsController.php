<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Phphub\Core\CreatorListener;
use App\Models\Topic;
use App\Models\SiteStatus;
use App\Models\Link;
use App\Models\Notification;
use App\Models\Append;
use App\Models\Category;
use App\Models\Banner;
use App\Models\ActiveUser;
use App\Models\HotTopic;
use Phphub\Handler\Exception\ImageUploadException;
use Phphub\Markdown\Markdown;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTopicRequest;
use Auth;
use Flash;
use Image;
use Request as UserRequest;
use Phphub\Notification\Mention;
use App\Activities\UserPublishedNewTopic;
use App\Activities\BlogHasNewArticle;
use App\Activities\UserAddedAppend;

class TopicsController extends Controller implements CreatorListener
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic)
    {
        $topics = $topic->getTopicsWithFilter($request->get('filter', 'index'), 40);
        $links  = Link::allFromCache();
        $banners = Banner::allByPosition();

        $active_users = ActiveUser::fetchAll();
        $hot_topics = HotTopic::fetchAll();

        return view('topics.index', compact('topics', 'links', 'banners', 'active_users', 'hot_topics'));
    }

    public function create(Request $request)
    {
        $category = Category::find($request->input('category_id'));
        $categories = Category::where('id', '!=', config('phphub.blog_category_id'))
                                ->where('id', '!=', config('phphub.hunt_category_id'))
                                ->get();

        return view('topics.create_edit', compact('categories', 'category'));
    }

    public function store(StoreTopicRequest $request)
    {
        return app('Phphub\Creators\TopicCreator')->create($this, $request->except('_token'));
    }

    public function show($id, Request $request, $fromCode = false)
    {
        $topic = Topic::where('id', $id)->with('user', 'lastReplyUser')->firstOrFail();
        if ($topic->isArticle() && $topic->is_draft == 'yes') {
            $this->authorize('show_draft', $topic);
        }

        // URL 矫正
        $slug = $request->route('slug');
        if (!empty($topic->slug) && $topic->slug != $slug && ! $fromCode) {
            return redirect($topic->link(), 301);
        }

        if ($topic->user->is_banned == 'yes') {
            // 未登录，或者已登录但是没有管理员权限
            if (!Auth::check() || (Auth::check() && !Auth::user()->may('manage_topics'))) {
                Flash::error('你访问的文章已被屏蔽，有疑问请发邮件：all@estgroupe.com');
                return redirect(route('topics.index'));
            }
            Flash::error('当前文章的作者已被屏蔽，游客与用户将看不到此文章。');
        }

        if (
            config('phphub.admin_board_cid')
            && $topic->id == config('phphub.admin_board_cid')
            && (!Auth::check() || !Auth::user()->can('access_board'))
        ) {
            Flash::error('您没有权限访问该文章，有疑问请发邮件：all@estgroupe.com');
            return redirect()->route('topics.index');
        }

        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'), $request->order_by);
        $categoryTopics = $topic->getSameCategoryTopics();

        $votedUsers = $topic->votes()->orderBy('id', 'desc')->with('user')->get()->pluck('user');
        $revisionHistory = $topic->revisionHistory()->orderBy('created_at', 'DESC')->first();
        $topic->increment('view_count', 1);

        $banners  = Banner::allByPosition();

        $cover = $topic->cover();

        if ($topic->isArticle()) {

            if (UserRequest::is('topics*')) {
                return redirect()->to($topic->link());
            }

            $user = $topic->user;
            $blog = $topic->blogs()->first();
            $userTopics = $blog->topics()->withoutDraft()->onlyArticle()->orderBy('vote_count', 'desc')->limit(5)->get();

            return view('articles.show', compact(
                                'blog', 'user','topic', 'replies', 'categoryTopics',
                                'category', 'banners', 'cover',
                                'votedUsers', 'userTopics', 'revisionHistory'));
        } else {
            $userTopics = $topic->byWhom($topic->user_id)->withoutDraft()->withoutBoardTopics()->recent()->limit(3)->get();

            return view('topics.show', compact(
                                'topic', 'replies', 'categoryTopics',
                                'category', 'banners', 'cover',
                                'votedUsers', 'userTopics', 'revisionHistory'));
        }
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);
        $categories = Category::where('id', '!=', config('phphub.blog_category_id'))->get();
        $category = $topic->category;

        $topic->body = $topic->body_original;

        return view('topics.create_edit', compact('topic', 'categories', 'category'));
    }

    public function append($id, Request $request)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('append', $topic);

        $markdown = new Markdown;
        $content = $markdown->convertMarkdownToHtml($request->input('content'));

        $append = Append::create(['topic_id' => $topic->id, 'content' => $content]);

        app('Phphub\Notification\Notifier')->newAppendNotify(Auth::user(), $topic, $append);
        app(UserAddedAppend::class)->generate(Auth::user(), $topic, $append);

        return response([
                    'status'  => 200,
                    'message' => lang('Operation succeeded.'),
                    'append'  => $append
                ]);
    }

    public function update($id, StoreTopicRequest $request, Mention $mentionParser)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);

        $data = $request->only('title', 'body', 'category_id', 'link');

        $data['body'] = $mentionParser->parse($data['body']);

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        if ($topic->isArticle() && $request->subject == 'publish' && $topic->is_draft == 'yes') {
            $data['is_draft'] = 'no';

            // Topic Published
            app('Phphub\Notification\Notifier')->newTopicNotify(Auth::user(), $mentionParser, $topic);

            // User activity
            app(UserPublishedNewTopic::class)->generate(Auth::user(), $topic);
            app(BlogHasNewArticle::class)->generate(Auth::user(), $topic, $topic->blogs()->first());

            Auth::user()->decrement('draft_count', 1);
            Auth::user()->increment('article_count', 1);
        }

        if ($topic->isShareLink()) {
            $topic->share_link->link = $data['link'];
            $topic->share_link->site = domain_from_url($data['link']);
            $topic->share_link->save();
        }

        $topic->update($data);

        Flash::success(lang('Operation succeeded.'));

        $topic->collectImages();

        return redirect()->to($topic->link());
    }

    /**
     * ----------------------------------------
     * User Topic Vote function
     * ----------------------------------------
     */

    public function upvote($id)
    {
        $topic = Topic::find($id);
        $topic = app('Phphub\Vote\Voter')->topicUpVote($topic);

        return response(['status' => 200, 'count' => $topic->vote_count]);
    }

    public function downvote($id)
    {
        $topic = Topic::find($id);
        app('Phphub\Vote\Voter')->topicDownVote($topic);

        return response(['status' => 200]);
    }

    /**
     * ----------------------------------------
     * Admin Topic Management
     * ----------------------------------------
     */

    public function recommend($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('recommend', $topic);
        $topic->is_excellent = $topic->is_excellent == 'yes' ? 'no' : 'yes';
        $topic->save();
        Notification::notify('topic_mark_excellent', Auth::user(), $topic->user, $topic);

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    public function pin($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('pin', $topic);

        $topic->order = $topic->order > 0 ? 0 : 999;
        $topic->save();

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    public function sink($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('sink', $topic);

        $topic->order = $topic->order >= 0 ? -1 : 0;
        $topic->save();

        app(UserPublishedNewTopic::class)->remove(Auth::user(), $topic);
        app(BlogHasNewArticle::class)->remove(Auth::user(), $topic, $topic->user->blogs()->first());

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('delete', $topic);
        $topic->delete();
        Flash::success(lang('Operation succeeded.'));

        $blog = $topic->blogs()->first();

        if ($topic->isArticle() && $topic->is_draft == 'yes') {
            $topic->user()->decrement('draft_count', 1);
        } elseif ($topic->isArticle()) {
            $topic->user()->decrement('article_count', 1);
            $blog->decrement('article_count', 1);
        } else {
            $topic->user()->decrement('topic_count', 1);
        }
        app(UserPublishedNewTopic::class)->remove($topic->user, $topic);
        app(BlogHasNewArticle::class)->remove($topic->user, $topic, $blog);

        return redirect()->route('topics.index');
    }

    public function uploadImage(Request $request)
    {
        if ($file = $request->file('file')) {
            try {
                $upload_status = app('Phphub\Handler\ImageUploadHandler')->uploadImage($file);
            } catch (ImageUploadException $exception) {
                return ['error' => $exception->getMessage()];
            }
            $data['filename'] = $upload_status['filename'];

            SiteStatus::newImage();
        } else {
            $data['error'] = 'Error while uploading file';
        }
        return $data;
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */

    public function creatorFailed($error)
    {
        Flash::error('发布失败：' . $error);
        return redirect('/');
    }

    public function creatorSucceed($topic)
    {
        Flash::success(lang('Operation succeeded.'));
        return redirect()->to($topic->link());
    }
}
