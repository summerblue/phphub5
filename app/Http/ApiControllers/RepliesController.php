<?php

namespace App\Http\ApiControllers;

use Dingo\Api\Exception\StoreResourceFailedException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
use App\Models\Reply;

class RepliesController extends Controller
{
    public function indexByTopicId($topic_id)
    {
        $topic = Topic::find($topic_id);
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));
        return $this->response()->paginator($replies, new ReplyTransformer());
    }

    public function indexByUserId($user_id)
    {
        $this->replies->addAvailableInclude('user', ['name', 'avatar']);

        $data = $this->replies
            ->byUserId($user_id)
            ->autoWith()
            ->autoWithRootColumns(['id', 'vote_count'])
            ->paginate(per_page());

        return $this->response()->paginator($data, new ReplyTransformer());
    }

    public function store(Request $request)
    {
        try {
            $reply = $this->replies->store($request->all());

            return $this->response()->item($reply, new ReplyTransformer());
        } catch (ValidatorException $e) {
            throw new StoreResourceFailedException('Could not create new topic.', $e->getMessageBag()->all());
        }
    }

    public function indexWebViewByTopic($topic_id)
    {
        $topic = Topic::find($topic_id);
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));

        return view('api.replies.index', compact('replies'));
    }

    public function indexWebViewByUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $replies = Reply::whose($user->id)->recent()->paginate(20);
        return view('api.users.users_replies_list', compact('replies'));
    }
}
