<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'link',
        'site',
        'topic_id',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
