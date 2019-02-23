<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /******************************************************************************
     * variables
     *****************************************************************************/
    protected $fillable = ['user_id', 'comment_id', 'body'];
    /****************************************************************************
     * relations
    *****************************************************************************/
    //belongs to comment
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }
    /****************************************************************************
     * custom functions
    *****************************************************************************/
    public function isViewable()
    { }
}
