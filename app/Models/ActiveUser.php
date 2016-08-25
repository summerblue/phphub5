<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class ActiveUser extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function fetchAll()
    {
        $data = Cache::remember('phphub_active_users', 30, function(){
            return self::with('user')
                       ->orderBy('weight', 'DESC')
                       ->limit(8)
                       ->get()
                       ->pluck('user');
        });

        return $data;

    }
}
