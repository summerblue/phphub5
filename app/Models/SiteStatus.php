<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SiteStatus extends Model
{
    public static function newUser()
    {
        self::collect('new_user');
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
        }

        $todayStatus->save();
    }
}
