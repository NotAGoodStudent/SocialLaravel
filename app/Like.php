<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    public function likes()
    {
        return $this->hasOne(User::class);
    }
}
