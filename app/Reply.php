<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    //belongs to reply
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }
}
