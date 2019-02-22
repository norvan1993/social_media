<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //has many polymorhic photo
    public function photo()
    {
        return $this->morphMany('App\Photo', 'photoable');
    }
}
