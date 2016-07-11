<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Link extends Model
{

    public static function allFromCache($expire = 1440)
    {
        $cache_name = 'links';

        return Cache::remember($cache_name, $expire, function () {
            return self::all();
        });
    }
}
