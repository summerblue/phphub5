<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use App\Models\Attention;
use App\Models\Notification;
use Auth;
use Flash;
use Redirect;
use App\Activities\UserAttendedTopic;

class AttentionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createOrDelete($id)
    {
        $topic = Topic::find($id);
        if (Attention::isUserAttentedTopic(Auth::user(), $topic)) {
            $message = lang('Successfully remove attention.');
            Auth::user()->attentTopics()->detach($topic->id);
            app(UserAttendedTopic::class)->remove(Auth::user(), $topic);
        } else {
            $message = lang('Successfully_attention');
            Auth::user()->attentTopics()->attach($topic->id);
            Notification::notify('topic_attent', Auth::user(), $topic->user, $topic);
            app(UserAttendedTopic::class)->generate(Auth::user(), $topic);
        }
        Flash::success($message);
        return Redirect::back();
    }
}