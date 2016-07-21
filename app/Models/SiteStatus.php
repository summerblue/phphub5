<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SiteStatus extends Model
{
    public static function newUser($driver)
    {
        self::collect('new_user');
        switch ($driver) {
            case 'github':
                self::collect('new_user_from_github');
                break;
            case 'wechat':
                self::collect('new_user_from_wechat');
                break;
        }
    }
    public static function newTopic()
    {
        self::collect('new_topic');
    }
    public static function newReply()
    {
        self::collect('new_reply');
    }
    public static function newImage()
    {
        self::collect('new_image');
    }

    /**
     * Collection site status
     *
     * @param  [string] $action
     * @return void
     */
    public static function collect($subject)
    {
        $today = Carbon::now()->toDateString();

        if (!$todayStatus = SiteStatus::where('day', $today)->first()) {
            $todayStatus = new SiteStatus;
            $todayStatus->day = $today;
        }

        switch ($subject) {
            case 'new_user':
                $todayStatus->register_count += 1;
                break;
            case 'new_topic':
                $todayStatus->topic_count += 1;
                break;
            case 'new_reply':
                $todayStatus->reply_count += 1;
                break;
            case 'new_image':
                $todayStatus->image_count += 1;
                break;
            case 'new_user_from_github':
                $todayStatus->github_regitster_count += 1;
                break;
            case 'new_user_from_wechat':
                $todayStatus->wechat_registered_count += 1;
                break;
        }

        $todayStatus->save();
    }
}
