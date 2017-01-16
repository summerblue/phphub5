<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Topic;
use App\Models\Banner;
use App\Models\Role;
use Illuminate\Http\Request;
use Rss;
use Purifier;
use Phphub\Handler\EmailHandler;
use Jrean\UserVerification\Facades\UserVerification;

class PagesController extends Controller
{
    public function home(Topic $topic)
    {
        $topics = $topic->getTopicsWithFilter('excellent');
        $banners = Banner::allByPosition();
        return view('pages.home', compact('topics', 'banners'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function wiki()
    {
        return app(TopicsController::class)->show(config('app.wiki_topic_id'));
    }

    public function search(Request $request)
    {
        $query = Purifier::clean($request->input('q'), 'search_q');
        $users = User::search($query, null, true)->orderBy('last_actived_at', 'desc')->limit(5)->get();

        if ($request->user_id) {
            $user = User::findOrFail($request->user_id);
            $topics = Topic::where('user_id', $user->id)
                                ->search($query, null, true)
                                ->withoutBlocked()
                                ->withoutBoardTopics()
                                ->paginate(30);

        } else {
            $user = new User;
            $topics = Topic::search($query, null, true)
                                ->withoutBlocked()
                                ->withoutBoardTopics()
                                ->paginate(30);
        }

        return view('pages.search', compact('users', 'user', 'query', 'topics'));
    }

    public function feed()
    {
        $topics = Topic::excellent()->recent()->limit(20)->get();

        $channel =[
            'title'       => 'PHPHub - PHP & Laravel的中文社区',
            'description' => 'PHPHub是 PHP 和 Laravel 的中文社区，在这里我们讨论技术, 分享技术。',
            'link'        => url(route('feed')),
        ];

        $feed = Rss::feed('2.0', 'UTF-8');

        $feed->channel($channel);

        foreach ($topics as $topic) {
            $feed->item([
                'title'             => $topic->title,
                'description|cdata' => str_limit($topic->body, 200),
                'link'              => url(route('topics.show', $topic->id)),
                'pubDate'           => date('Y-m-d', strtotime($topic->created_at)),
                ]);
        }

        return response($feed, 200, array('Content-Type' => 'text/xml'));
    }

    public function sitemap()
    {
        return app('Phphub\Sitemap\Builder')->render();
    }

    public function hallOfFames()
    {
        $users = User::byRolesName('HallOfFame');
        return view('pages.hall_of_fame', compact('users'));
    }
}
