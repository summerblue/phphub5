<?php

namespace App\Http\ApiControllers;

use Dingo\Api\Exception\StoreResourceFailedException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Transformers\ReplyTransformer;
use Phphub\Core\CreatorListener;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
use App\Models\Reply;
use Auth;

class RepliesController extends Controller implements CreatorListener
{
    public function indexByTopicId($topic_id)
    {
        $topic = Topic::find($topic_id);
        $replies = $topic->getRepliesWithLimit(config('phphub.replies_perpage'));
        return $this->response()->paginator($replies, new ReplyTransformer());
    }

    public function indexByUserId($user_id)
    {
        $user = User::findOrFail($user_id);
        $replies = Reply::whose($user->id)->with('user')->recent()->paginate(15);
        return $this->response()->paginator($replies, new ReplyTransformer());
    }

    public function store(Request $request)
    {
        if (!Auth::user()->verified) {
            throw new StoreResourceFailedException('创建评论失败，请验证用户邮箱');
        }

        return app('Phphub\Creators\ReplyCreator')->create($this, $request->except('_token'));
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

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */
    public function creatorFailed($errors)
    {
        throw new StoreResourceFailedException('创建评论失败：' . output_msb($errors->getMessageBag()));
    }

    public function creatorSucceed($reply)
    {
        return $this->response()->item($reply, new ReplyTransformer());
    }
}
