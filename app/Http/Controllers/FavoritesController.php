<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Favorite;
use App\Models\Notification;
use Auth;
use Flash;

class FavoritesController extends Controller
{
    public function createOrDelete($id)
    {
        $topic = Topic::find($id);

        if (Favorite::isUserFavoritedTopic(Auth::user(), $topic)) {
            Auth::user()->favoriteTopics()->detach($topic->id);
        } else {
            Auth::user()->favoriteTopics()->attach($topic->id);
            Notification::notify('topic_favorite', Auth::user(), $topic->user, $topic);
        }

        return response(['status' => 200, 'message' => lang('Operation succeeded.')]);
    }
}
