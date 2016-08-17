<?php

namespace App\Http\ApiControllers;

use Auth;
use Dingo\Api\Exception\StoreResourceFailedException;
use Gate;
use App\Repositories\Criteria\FilterManager;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TopicsController extends Controller
{
    public function index(Request $request, Topic $topic)
    {
        $filter = $topic->correctApiFilter($request->get('filters'));
        $topics = $topic->getTopicsWithFilter($filter, per_page());
        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function indexByUserId($user_id)
    {
        $topics = Topic::whose($user_id)->recent()->paginate(15);
        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function indexByNodeId($node_id, Topic $topic)
    {
        $topics = $topic->getCategoryTopicsWithFilter('default', $node_id);
        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function indexByUserFavorite($user_id)
    {
        $user = User::findOrFail($user_id);
        $topics = $user->votedTopics()->orderBy('pivot_created_at', 'desc')->paginate(15);
        return $this->response()->paginator($topics, new TopicTransformer());
    }

    public function indexByUserAttention($user_id)
    {
        $user = User::findOrFail($user_id);
        $topics = $user->votedTopics()->orderBy('pivot_created_at', 'desc')->paginate(15);
        return $this->response()->paginator($topics, new TopicTransformer());
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
        $topic = Topic::with('user')->find($id);

        // if (Auth::check()) {
        //     $topic->favorite = $this->topics->userFavorite($topic->id, Auth::id());
        //     $topic->attention = $this->topics->userAttention($topic->id, Auth::id());
        //     $topic->vote_up = $this->topics->userUpVoted($topic->id, Auth::id());
        //     $topic->vote_down = $this->topics->userDownVoted($topic->id, Auth::id());
        // }

        return $this->response()->item($topic, new TopicTransformer());
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

    public function showWebView($id)
    {
        $topic = Topic::find($id);
        return view('api.topics.show', compact('topic'));
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
}
