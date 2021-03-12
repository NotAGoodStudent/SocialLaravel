<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blocked extends Model
{
    public function blocked()
    {
        return $this->belongsTo(User::class);
    }
}
