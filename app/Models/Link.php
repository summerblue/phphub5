<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Link extends Model
{
    protected $guarded = ['id'];
    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'deleted_at'
    ];
    use SoftDeletes;

    public static function allFromCache($expire = 1440)
    {
        $cache_name = 'links';

        return Cache::remember($cache_name, $expire, function () {
            return self::where('is_enabled', 'yes')->get();
        });
    }
}
