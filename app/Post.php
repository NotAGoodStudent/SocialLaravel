<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function posts()
    {
        return $this->belongsTo(User::class);
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
