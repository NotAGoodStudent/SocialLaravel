<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retweet extends Model
{
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
