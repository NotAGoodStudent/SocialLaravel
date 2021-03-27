<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['owner', 'content', 'likes', 'retweets', 'topic_id', 'created_at', 'updated_at'];
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

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}
