<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveUser extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function fetchAll($limit = 8)
    {
        return self::with('user')
                   ->orderBy('weight', 'DESC')
                   ->limit($limit)
                   ->get()
                   ->pluck('user');
    }
}
