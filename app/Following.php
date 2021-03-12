<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    public function following()
    {
        return $this->belongsTo(User::class);
    }
}
