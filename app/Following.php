<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    protected $table = 'following';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
