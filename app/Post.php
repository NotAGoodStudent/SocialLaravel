<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['owner', 'content', 'image', 'post_rep_id', 'topic_id', 'created_at', 'updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function retweets()
    {
        return $this->hasMany(Retweet::class, 'post_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_rep_id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}
