<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /****************************************************************************
     * variables
     ***************************************************************************/
    protected $fillable = ['receiver_id', 'sender_id', 'body', 'status'];
    /****************************************************************************
     * relations
     ***************************************************************************/
    //has many polymorhic photo
    public function photo()
    {
        return $this->morphMany('App\Photo', 'photoable');
    }
}
