<?php

namespace App\Http\ApiControllers;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Criteria\FilterManager;
use App\Transformers\TopicTransformer;
use Phphub\Core\CreatorListener;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
use App\Models\Vote;
use Gate;
use Auth;

class TopicsController extends Controller implements CreatorListener
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
        if (!Auth::user()->verified) {
            throw new StoreResourceFailedException('创建话题失败，请验证用户邮箱');
        }
        $data = array_merge($request->except('_token'), ['category_id' => $request->node_id]);
        return app('Phphub\Creators\TopicCreator')->create($this, $data);
    }

    public function show($id)
    {
        $topic = Topic::with('user')->find($id);

        $topic_id = $topic->id;
        $user_id = Auth::id();

        if (Auth::check()) {
            $upvoted = Vote::where([
                               'user_id'      => $user_id,
                               'votable_id'   => $topic_id,
                               'votable_type' => 'App\Models\Topic',
                               'is'           => 'upvote',
                           ])->exists();
            $downvoted = Vote::where([
                               'user_id'      => $user_id,
                               'votable_id'   => $topic_id,
                               'votable_type' => 'App\Models\Topic',
                               'is'           => 'downvote',
                           ])->exists();

            $topic->favorite = $upvoted;
            $topic->attention = $upvoted;
            $topic->vote_up = $upvoted;
            $topic->vote_down = $downvoted;
        }

        $topic->increment('view_count', 1);

        return $this->response()->item($topic, new TopicTransformer());
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        if (Gate::denies('delete', $topic)) {
            throw new AccessDeniedHttpException();
        }

        $topic->delete();
        return ['status' => 'ok'];
    }

    public function voteUp($id)
    {
        $topic = Topic::find($id);
        app('Phphub\Vote\Voter')->topicUpVote($topic);

        return response([
            'vote-up'    => true,
            'vote_count' => $topic->vote_count,
        ]);
    }

    public function voteDown($id)
    {
        $topic = Topic::find($id);
        app('Phphub\Vote\Voter')->topicDownVote($topic);

        return response([
            'vote-down'  => true,
            'vote_count' => $topic->vote_count,
        ]);
    }

    public function showWebView($id)
    {
        $topic = Topic::find($id);
        return view('api.topics.show', compact('topic'));
    }

    // 收藏和关注已被删除，这里只做客户端兼容处理
    // https://phphub.org/topics/2545
    public function favorite($topic_id)
    {
        $this->voteUp($topic_id);
        return response([ 'status' => true]);
    }
    public function unFavorite($topic_id)
    {
        $this->voteDown($topic_id);
        return response([ 'status' => true]);
    }
    public function attention($topic_id)
    {
        $this->voteUp($topic_id);
        return response([ 'status' => true]);
    }
    public function unAttention($topic_id)
    {
        $this->voteDown($topic_id);
        return response([ 'status' => true]);
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */

    public function creatorFailed($errors)
    {
        throw new StoreResourceFailedException('创建话题失败：'. output_msb($errors->getMessageBag()) );
    }

    public function creatorSucceed($topic)
    {
        return $this->response()->item($topic, new TopicTransformer());
    }
}
