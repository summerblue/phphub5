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

    public function setCoverAttribute($file_name)
    {
        if (starts_with($file_name, 'http')) {
            $parser_url = explode('/', $file_name);
            $file_name = end($parser_url);
        }

        $this->attributes['cover'] = 'uploads/banners/'.$file_name;
    }

    public function getCoverAttribute($file_name)
    {
        if (starts_with($file_name, 'http')) {
            return $file_name;
        }

        return cdn($file_name);
    }

    public static function allFromCache($expire = 1440)
    {
        return Cache::remember('phphub_links', $expire, function () {
            return self::where('is_enabled', 'yes')->get();
        });
    }
}
