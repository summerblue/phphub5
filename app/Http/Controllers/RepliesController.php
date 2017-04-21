<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Phphub\Core\CreatorListener;
use App\Http\Requests\StoreReplyRequest;
use App\Models\Reply;
use Flash;
use Auth;
use Redirect;
use Request;
use App\Activities\UserRepliedTopic;

class RepliesController extends Controller implements CreatorListener
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreReplyRequest $request)
    {
        return app('Phphub\Creators\ReplyCreator')->create($this, $request->except('_token'));
    }

    public function vote($id)
    {
        $reply = Reply::findOrFail($id);
        $type = app('Phphub\Vote\Voter')->replyUpVote($reply);

        return response([
            'status'  => 200,
            'message' => lang('Operation succeeded.'),
            'type'    => $type['action_type'],
        ]);
    }

    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);
        $this->authorize('delete', $reply);
        $reply->delete();

        $reply->topic->decrement('reply_count', 1);
        $reply->topic->generateLastReplyUserInfo();

        app(UserRepliedTopic::class)->remove($reply->user, $reply);

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */

    public function creatorFailed($errors)
    {
        if (Request::ajax()) {
            return response([
                        'status'  => 500,
                        'message' => lang('Operation failed.'),
                    ]);
        } else {
            Flash::error(lang('Operation failed.'));
            return Redirect::back();
        }
    }

    public function creatorSucceed($reply)
    {
        $reply->user->image_url = $reply->user->present()->gravatar;

        if (Request::ajax()) {
            return response([
                        'status'        => 200,
                        'message'       => lang('Operation succeeded.'),
                        'reply'         => $reply,
                        'manage_topics' => $reply->user->may('manage_topics') ? 'yes' : 'no',
                    ]);
        } else {
            Flash::success(lang('Operation succeeded.'));
            return Redirect::to($reply->topic->link(['#last-reply']));
        }
    }
}
