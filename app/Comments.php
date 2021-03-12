<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public function comments()
    {
        return $this->belongsTo(User::class);
    }
}
