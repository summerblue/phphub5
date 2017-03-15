<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Banner;
use App\Models\Link;
use App\Models\ActiveUser;
use App\Models\HotTopic;
use Illuminate\Http\Request;
use Auth;

class ActivityController extends Controller
{
	public function index(Request $request)
	{
        // $activities = Auth::user()->subscribedActivityFeeds();
        $activities = Activity::recent()->paginate();
        $links  = Link::allFromCache();
        $banners = Banner::allByPosition();

        $active_users = ActiveUser::fetchAll();
        $hot_topics = HotTopic::fetchAll();

        return view('activities.index', compact('activities', 'links', 'banners', 'active_users', 'hot_topics'));
	}

}
