<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'votable_id', 'votable_type', 'is'];

    public function votable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByWhom($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeWithType($query, $type)
    {
        return $query->where('is', '=', $type);
    }
}
