<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveUser extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public static function fetchAll($limit = 10)
    {
        return self::orderBy('weight', 'DESC')
                   ->limit($limit)
                   ->get();
    }
}
