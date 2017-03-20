<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'blog_subscribers');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'blog_managers');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'blog_topics');
    }

    public function authors()
    {
        return $this->belongsToMany(User::class, 'blog_authors');
    }

    public function link($params = [])
    {
        return route('wildcard', array_merge([$this->slug], $params));
    }
}
