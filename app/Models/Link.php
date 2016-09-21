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

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            Cache::forget('phphub_links');
        });
    }

    public static function allFromCache($expire = 1440)
    {
        return Cache::remember('phphub_links', $expire, function () {
            return self::where('is_enabled', 'yes')->get();
        });
    }
}
