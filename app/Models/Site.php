<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Laracasts\Presenter\PresentableTrait;
use Phphub\Presenters\SitePresenter;

class Site extends Model
{
    use PresentableTrait;
    protected $presenter = SitePresenter::class;

    protected $guarded = ['id'];

    public static function allFromCache($expire = 1440)
    {
        $cache_name = 'phphub_sites';

        $raw_sites = self::orderBy('order', 'desc')->get();
        $sorted = [];

        $sorted['site'] = $raw_sites->filter(function ($item) {
            return $item->type == 'site';
        });
        $sorted['blog'] = $raw_sites->filter(function ($item) {
            return $item->type == 'blog';
        });
        $sorted['weibo'] = $raw_sites->filter(function ($item) {
            return $item->type == 'weibo';
        });
        $sorted['dev_service'] = $raw_sites->filter(function ($item) {
            return $item->type == 'dev_service';
        });

        return $sorted;

        // return Cache::remember($cache_name, $expire, function () {
        //     return self::all();
        // });
        // return self::all();
    }

    public function setFaviconAttribute($value)
    {
        $this->attributes['favicon'] = (strpos($value, 'uploads/sites/') !== true && !empty($value))
                                        ? 'uploads/sites/' . $value
                                        : $value;
    }
}
