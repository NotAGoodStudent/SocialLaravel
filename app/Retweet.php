<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class retweet extends Model
{
    public function retweets()
    {
        return $this->hasOne(User::class);
    }
}
