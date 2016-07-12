<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Phphub\Core\CreatorListener;
use App\Models\Topic;
use App\Models\SiteStatus;
use App\Models\Link;
use App\Models\Attention;
use App\Models\Notification;
use App\Models\Append;
use App\Models\Category;
use App\Models\Banner;
use Phphub\Markdown\Markdown;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTopicRequest;
use Auth;
use Flash;
use Image;

class TopicsController extends Controller implements CreatorListener
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Topic $topic)
    {
        $filter = $topic->present()->getTopicFilter();
        $topics = $topic->getTopicsWithFilter($filter, 40);
        $links  = Link::allFromCache();
        $banners = Banner::allByPosition();
        return view('topics.index', compact('topics', 'links', 'banners'));
    }

    public function create(Request $request)
    {
        $category = Category::find($request->input('category_id'));
        $categories = Category::all();

        return view('topics.create_edit', compact('categories', 'category'));
    }

    public function store(StoreTopicRequest $request)
    {
        return app('Phphub\Creators\TopicCreator')->create($this, $request->except('_token'));
    }

    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));
        $category = $topic->category;
        $categoryTopics = $topic->getSameCategoryTopics();

        $topic->increment('view_count', 1);

        $banners  = Banner::allByPosition();
        return view('topics.show', compact('topic', 'replies', 'categoryTopics', 'category', 'banners'));
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);
        $categories = Category::all();
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

        return response([
                    'status'  => 200,
                    'message' => lang('Operation succeeded.'),
                    'append'  => $append
                ]);
    }

    public function update($id, StoreTopicRequest $request)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic);

        $data = $request->only('title', 'body', 'category_id');

        $markdown = new Markdown;
        $data['body_original'] = $data['body'];
        $data['body'] = $markdown->convertMarkdownToHtml($data['body']);
        $data['excerpt'] = Topic::makeExcerpt($data['body']);

        $topic->update($data);

        Flash::success(lang('Operation succeeded.'));
        return redirect(route('topics.show', $topic->id));
    }

    /**
     * ----------------------------------------
     * User Topic Vote function
     * ----------------------------------------
     */

    public function upvote($id)
    {
        $topic = Topic::find($id);
        app('Phphub\Vote\Voter')->topicUpVote($topic);

        return response(['status' => 200]);
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

    public function wiki($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('wiki', $topic);
        $topic->is_wiki = $topic->is_wiki == 'yes' ? 'no' : 'yes';
        $topic->save();
        Notification::notify('topic_mark_wiki', Auth::user(), $topic->user, $topic);

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    public function pin($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('pin', $topic);
        ($topic->order > 0) ? $topic->decrement('order', 1) : $topic->increment('order', 1);

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    public function sink($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('sink', $topic);
        ($topic->order >= 0) ? $topic->decrement('order', 1) : $topic->increment('order', 1);

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('delete', $topic);
        $topic->delete();
        Flash::success(lang('Operation succeeded.'));

        return redirect(route('topics.index'));
    }

    public function uploadImage(Request $request)
    {
        if ($file = $request->file('file')) {
            $allowed_extensions = ["png", "jpg", "gif"];
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return ['error' => 'You may only upload png, jpg or gif.'];
            }

            $fileName        = $file->getClientOriginalName();
            $extension       = $file->getClientOriginalExtension() ?: 'png';
            $folderName      = 'uploads/images/' . date("Ym", time()) .'/'.date("d", time()) .'/'. Auth::user()->id;
            $destinationPath = public_path() . '/' . $folderName;
            $safeName        = str_random(10).'.'.$extension;
            $file->move($destinationPath, $safeName);

            // If is not gif file, we will try to reduse the file size
            if ($file->getClientOriginalExtension() != 'gif') {
                // open an image file
                $img = Image::make($destinationPath . '/' . $safeName);
                // prevent possible upsizing
                $img->resize(1440, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                // finally we save the image as a new file
                $img->save();
            }

            $data['filename'] = getUserStaticDomain() . $folderName .'/'. $safeName;

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

    public function creatorFailed($errors)
    {
        return redirect('/');
    }

    public function creatorSucceed($topic)
    {
        Flash::success(lang('Operation succeeded.'));
        show_crx_hint();

        return redirect(route('topics.show', array($topic->id)));
    }
}
