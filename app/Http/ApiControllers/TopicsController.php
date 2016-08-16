<?php

namespace App\Http\ApiControllers;

use Auth;
use Dingo\Api\Exception\StoreResourceFailedException;
use Gate;
use App\Repositories\Criteria\FilterManager;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TopicsController extends Controller
{
    public function index(Request $request, Topic $topic)
    {
        $filter = $topic->correctApiFilter($request->get('filter'));
        $topics = $topic->getTopicsWithFilter($filter, per_page());
        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function indexByUserId($user_id)
    {
        $this->topics->byUserId($user_id);

        return $this->commonIndex();
    }

    public function indexByNodeId($node_id)
    {
        $this->topics->byNodeId($node_id);

        return $this->commonIndex();
    }

    public function indexByUserFavorite($user_id)
    {
        $this->registerListApiIncludes();

        $data = $this->topics
            ->favoriteTopicsWithPaginator($user_id,
                ['id', 'title', 'is_excellent', 'reply_count', 'updated_at', 'created_at']);

        return $this->response()->paginator($data, new TopicTransformer());
    }

    public function indexByUserAttention($user_id)
    {
        $this->registerListApiIncludes();

        $data = $this->topics
            ->attentionTopicsWithPaginator($user_id,
                ['id', 'title', 'is_excellent', 'reply_count', 'updated_at', 'created_at']);

        return $this->response()->paginator($data, new TopicTransformer());
    }

    public function store(Request $request)
    {
        try {
            $topic = $this->topics->create($request->all());

            return $this->response()->item($topic, new TopicTransformer());
        } catch (ValidatorException $e) {
            throw new StoreResourceFailedException('Could not create new topic.', $e->getMessageBag()->all());
        }
    }

    public function show($id)
    {
        $this->topics->addAvailableInclude('user', ['name', 'avatar']);

        $topic = $this->topics
            ->autoWith()
            ->autoWithRootColumns(array_diff(Topic::$includable, ['body', 'body_original', 'excerpt']))
            ->find($id);

        if (Auth::check()) {
            $topic->favorite = $this->topics->userFavorite($topic->id, Auth::id());
            $topic->attention = $this->topics->userAttention($topic->id, Auth::id());
            $topic->vote_up = $this->topics->userUpVoted($topic->id, Auth::id());
            $topic->vote_down = $this->topics->userDownVoted($topic->id, Auth::id());
        }

        return $this->response()->item($topic, new TopicTransformer());
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $topic = $this->topics->find($id);

        if (Gate::denies('delete', $topic)) {
            throw new AccessDeniedHttpException();
        }

        $this->topics->delete($id);
    }

    public function voteUp($id)
    {
        $topic = $this->topics->find($id);

        return response([
            'vote-up'    => $this->topics->voteUp($topic),
            'vote_count' => $topic->vote_count,
        ]);
    }

    public function voteDown($id)
    {
        $topic = $this->topics->find($id);

        return response([
            'vote-down'  => $this->topics->voteDown($topic),
            'vote_count' => $topic->vote_count,
        ]);
    }

    protected function commonIndex()
    {
        FilterManager::addFilter('newest');
        $this->registerListApiIncludes();

        $data = $this->topics
            ->autoWith()
            ->autoWithRootColumns([
                'id',
                'title',
                'is_excellent',
                'reply_count',
                'updated_at',
                'created_at',
                'vote_count',
            ])
            ->paginate(per_page());

        return $this->response()->paginator($data, new TopicTransformer());
    }

    public function showWebView($id)
    {
        $topic = $this->topics->find($id, ['title', 'body', 'created_at', 'vote_count']);

        return view('api_web_views.topic', compact('topic'));
    }

    public function favorite($topic_id)
    {
        try {
            $this->topics->favorite($topic_id, Auth::id());
        } catch (\Exception $e) {
            $filed = true;
        }

        return response([
            'status' => isset($filed) ? false : true,
        ]);
    }

    public function unFavorite($topic_id)
    {
        try {
            $this->topics->unFavorite($topic_id, Auth::id());
        } catch (\Exception $e) {
            $filed = true;
        }

        return response([
            'status' => isset($filed) ? false : true,
        ]);
    }

    public function attention($topic_id)
    {
        try {
            $this->topics->attention($topic_id, Auth::id());
        } catch (\Exception $e) {
            $filed = true;
        }

        return response([
            'status' => isset($filed) ? false : true,
        ]);
    }

    public function unAttention($topic_id)
    {
        try {
            $this->topics->unAttention($topic_id, Auth::id());
        } catch (\Exception $e) {
            $filed = true;
        }

        return response([
            'status' => isset($filed) ? false : true,
        ]);
    }

    protected function registerListApiIncludes()
    {
        $this->topics->addAvailableInclude('user', ['name', 'avatar']);
        $this->topics->addAvailableInclude('last_reply_user', ['name']);
        $this->topics->addAvailableInclude('node', ['name']);
    }
}
