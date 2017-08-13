<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Auth;

trait TopicFilterable
{
    public function getTopicsWithFilter($filter, $limit = 20)
    {
        $filter = $this->getTopicFilter($filter);

        return $this->applyFilter($filter)
                    ->with('user', 'category', 'lastReplyUser')
                    ->paginate($limit);
    }

    public function getCategoryTopicsWithFilter($filter, $category_id, $limit = 20)
    {
        return $this->applyFilter($filter == 'default' ? 'category' : $filter)
                    ->where('category_id', '=', $category_id)
                    ->with('user', 'category', 'lastReplyUser')
                    ->paginate($limit);
    }

    public function getTopicFilter($request_filter)
    {
        $filters = ['noreply', 'vote', 'excellent','recent', 'wiki', 'jobs', 'excellent-pinned', 'index'];
        if (in_array($request_filter, $filters)) {
            return $request_filter;
        }
        return 'default';
    }

    public function applyFilter($filter)
    {
        $query = $this->withoutBlocked()->withoutDraft();

        // 过滤站务信息
        $query = $query->withoutBoardTopics();

        if ( ! if_route('categories.show')) {
            $query->withoutShareLink();
        }

        switch ($filter) {
            case 'noreply':
                return $query->pinned()->orderBy('reply_count', 'asc')->recent();
                break;
            case 'vote':
                return $query->pinned()->orderBy('vote_count', 'desc')->recent();
                break;
            case 'excellent':
                return $query->excellent()->recent();
                break;

            // 主要 API 首页在用，置顶+精华
            case 'excellent-pinned':
                return $query->excellent()->pinned()->recent();
                break;

            case 'random-excellent':
                return $query->excellent()->fresh()->random();
                break;
            case 'recent':
                return $query->pinned()->recent();
                break;
            case 'category':
                return $query->pinned()->recentReply();
                break;

            // for api，分类：教程
            case 'wiki':
                return $query->where('category_id', 6)->pinned()->recent();
                break;
            // for api，分类：招聘
            case 'jobs':
                return $query->where('category_id', 1)->pinned()->recent();
                break;

            case 'index':
                return $query->pinAndRecentReply()->withoutQA()->withoutLIFE();
                break;

            default:
                return $query->pinAndRecentReply();
                break;
        }
    }

    public function scopeWhose($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id)->with('category');
    }

    public function scopeOnlyArticle($query)
    {
        return $query->where('category_id', '=', config('phphub.blog_category_id'));
    }
    public function scopeWithoutArticle($query)
    {
        return $query->where('category_id', '!=', config('phphub.blog_category_id'));
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeRandom($query)
    {
        return $query->orderByRaw("RAND()");
    }

    public function scopePinAndRecentReply($query)
    {
        return $query->fresh()
                     ->pinned()
                     ->orderBy('updated_at', 'desc');
    }

    public function scopePinned($query)
    {
        return $query->orderBy('order', 'desc');
    }

    public function scopeFresh($query)
    {
        return $query->whereRaw("(`created_at` > '".Carbon::today()->subMonths(3)->toDateString()."' or (`order` > 0) )");
    }

    public function scopeRecentReply($query)
    {
        return $query->pinned()
                     ->orderBy('updated_at', 'desc');
    }

    public function scopeExcellent($query)
    {
        return $query->where('is_excellent', '=', 'yes');
    }

    public function scopeWithoutBlocked($query)
    {
        return $query->where('is_blocked', '=', 'no');
    }

    public function scopeWithoutBoardTopics($query)
    {
        if (
            config('phphub.admin_board_cid')
            && (!Auth::check() || !Auth::user()->can('access_board'))
            ) {
            return $query->where('category_id', '!=', config('phphub.admin_board_cid'));
        }
        return $query;
    }

    public function scopeWithoutQA($query)
    {
        if (config('phphub.qa_category_id')) {
            return $query->where('category_id', '!=', config('phphub.qa_category_id'));
        }
        return $query;
    }

    public function scopeWithoutShareLink($query)
    {
        if (config('phphub.hunt_category_id')) {
            return $query->where('category_id', '!=', config('phphub.hunt_category_id'));
        }
        return $query;
    }

    public function scopeWithoutLIFE($query)
    {
        if (config('phphub.life_category_id')) {
            return $query->where('category_id', '!=', config('phphub.life_category_id'));
        }
        return $query;
    }

    public function correctApiFilter($filter)
    {
        switch ($filter) {
            case 'newest':
                return 'recent';

            case 'excellent':
                return 'excellent-pinned';

            case 'nobody':
                return 'noreply';

            default:
                return $filter;
        }
    }
}
