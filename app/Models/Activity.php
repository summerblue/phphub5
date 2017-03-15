<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['causer', 'indentifier', 'type', 'data'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
