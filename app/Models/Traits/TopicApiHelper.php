<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\User;

// 为了兼容 API 的写法
// {{url}}/topics?include=node,last_reply_user,user&filter=jobs&per_page=15&page=1

trait TopicApiHelper
{

    public function last_reply_user()
    {
        return $this->belongsTo(User::class, 'last_reply_user_id');
    }

    public function node()
    {
        return $this->belongsTo(Category::class);
    }

}

