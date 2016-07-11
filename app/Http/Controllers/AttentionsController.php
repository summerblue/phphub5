<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Attention;
use App\Models\Notification;
use Auth;
use Flash;

class AttentionsController extends Controller
{
    public function createOrDelete($id)
    {
        $topic = Topic::find($id);

        if (Attention::isUserAttentedTopic(Auth::user(), $topic)) {
            $message = lang('Successfully remove attention.');
            Auth::user()->attentTopics()->detach($topic->id);
        } else {
            $message = lang('Successfully_attention');
            Auth::user()->attentTopics()->attach($topic->id);
            Notification::notify('topic_attent', Auth::user(), $topic->user, $topic);
        }
        Flash::success($message);

        return response(['status' => 200, 'message' => $message]);
    }
}
