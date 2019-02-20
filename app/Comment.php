<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    /*****************************************************************
     * variables
     ****************************************************************/
    protected $fillable = ['user_id', 'commentable_type', 'commentable_id', 'body'];
    /****************************************************************
     * relations
     ****************************************************************/
//belongsTo user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
//has one polymorhic photo
    public function photo()
    {
        return $this->morphOne('App\Photo', 'photoable');
    }
//belongs to polymorphic parent
    public function commentable()
    {
        return $this->morphTo();
    }
    /****************************************************************
     * custom functions
     ****************************************************************/
    public function isViewable()
    {
//set commenter id
        $commenterId = $this->user_id;
//set commentable id
        $commentableId = $this->commentable_id;
//setting commentable
        $commentable = null;
        if ($this->commentable_type == 'App\\Post') {
            $commentable = Post::findOrFail($commentableId);
        }
//check if the authenticater user is admin
        if (Auth::user()->isAdmin()) {
            return true;
        }
//check if commentable is viewable
        if (!$commentable->isViewable()) {
            return false;
        }
//check if commenter is blocked
        if (FriendRequest::isBlocked($commenterId)) {
            return false;
        }
//return true in case of passing all above if satatement(the comment is viewable)
        return true;
    }

}
