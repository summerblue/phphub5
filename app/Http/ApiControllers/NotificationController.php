<?php

namespace App\Http\ApiControllers;

use Auth;
use App\Transformers\NotificationTransformer;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications();
        Auth::user()->notification_count = 0;
        Auth::user()->save();

        return $this->response()->paginator($notifications, new NotificationTransformer());
    }

    public function unreadMessagesCount()
    {
        $count = $this->users->getUnreadMessagesCount();

        return response(compact('count'));
    }
}
