<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    //has many replies
    public function replies()
    {
        return $this->hasMany('App\Reply');
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
    //viewable comments of post
    public static function viewableCommentsOfPost($postId)
    {
        $post = Post::findOrFail($postId);
        if ($post->isViewable()) {
            return DB::table('comments')
                ->leftJoin('friend_requests AS sender', 'comments.user_id', '=', 'sender.sender_id')
                ->leftJoin('friend_requests AS receiver', 'comments.user_id', '=', 'receiver.receiver_id')
                ->where('comments.commentable_type', '=', 'App\\Post')
                ->where('comments.commentable_id', '=', $postId)
                ->where(function ($query) {
                    $query->where('sender.receiver_id', '!=', Auth::id())
                        ->orWhere('receiver.sender_id', '!=', Auth::id())
                        ->orWhere('sender.receiver_id', '=', Auth::id())
                        ->where('sender.status', '!=', 'block')
                        ->orWhere('receiver.sender_id', '=', Auth::id())
                        ->where('receiver.status', '!=', 'block');
                })
                ->select('comments.*')
                ->distinct()
                ->paginate(10);
        }
    }
}
