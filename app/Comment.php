<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function comments()
    {
        return $this->belongsTo(User::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
}
