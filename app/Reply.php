<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FriendRequest;
use Illuminate\Support\Facades\Auth;

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
    //has one polymorhic photo
    public function photo()
    {
        return $this->morphOne('App\Photo', 'photoable');
    }
    /****************************************************************************
     * custom functions
    *****************************************************************************/
    public function isViewable()
    {
        //check if the authenticated user is admin
        if (Auth::user()->isAdmin()) {
            return true;
        }
        //check if the reply owner is the authenticated user
        if ($this->user_id == Auth::id()) {
            return true;
        }
        //check if the reply owner is not blocked
        if (FriendRequest::isBlocked($this->user_id)) {
            return false;
        }
        //check if the comment is viewable
        if (!$this->comment->isViewable()) {
            return false;
        }
        //return true if it is viewable
        return true;
    }
}
