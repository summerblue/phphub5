<?php

namespace App\Http\ApiControllers;

use Auth;
use App\Transformers\NotificationTransformer;

class NotificationController extends Controller
{
    public function index()
    {
        $this->notifications->addAvailableInclude('from_user', ['name', 'avatar']);
        $this->notifications->addAvailableInclude('reply', ['created_at']);
        $this->notifications->addAvailableInclude('topic', ['title']);

        $data = $this->notifications
            ->userRecent(Auth::id())
            ->autoWith()
            ->autoWithRootColumns(['id', 'type', 'body', 'topic_id', 'reply_id', 'created_at'])
            ->paginate(per_page());

        $this->users->setUnreadMessagesCount(Auth::id(), 0);

        return $this->response()->paginator($data, new NotificationTransformer());
    }

    public function unreadMessagesCount()
    {
        $count = $this->users->getUnreadMessagesCount();

        return response(compact('count'));
    }
}
