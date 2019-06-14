<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    /*******************************************************************************
     *variables
     *******************************************************************************/
    protected $fillable = [
        'user_id', 'category_id', 'title', 'body', 'privacy'
    ];

    /*******************************************************************************
     *relations
     *******************************************************************************/
    //belongs to user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    //has many polymorhic photos
    public function photos()
    {
        return $this->morphMany('App\Photo', 'photoable');
    }
    //has many polymorhic comments
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
    //has many polymorphic likes
    public function likes()
    {
        return $this->morphMany('App\Like', 'likeable');
    }
    //has many Default_allowed_viewers(many to many with users table)
    public function default_allowed_viewers()
    {
        return $this->belongsToMany('App\User');
    }
    /*******************************************************************************
     *models event
     *******************************************************************************/
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($post) {
            //deleting post photos
            if ($post->photos) {
                $post->photos->each(function ($photo) {
                    $photo->delete();
                });;
            }
        });
    }

    /*******************************************************************************
     *custom functions
     *******************************************************************************/
    //this will return all viewable posts
    public static function viewablePosts()
    {
        return DB::table('posts')
            ->leftJoin('post_privacy', 'posts.id', '=', 'post_privacy.post_id')
            ->leftJoin('friend_requests AS sender', 'posts.user_id', '=', 'sender.sender_id')
            ->leftJoin('friend_requests AS receiver', 'posts.user_id', '=', 'receiver.receiver_id')
            ->where('posts.user_id', '=', Auth::id())
            ->orWhere('posts.privacy', '=', 'public')
            ->where(function ($query) {
                $query->where('sender.receiver_id', '!=', Auth::id())
                    ->orWhere('receiver.sender_id', '!=', Auth::id())
                    ->orWhere('sender.receiver_id', '=', Auth::id())
                    ->where('sender.status', '!=', 'block')
                    ->orWhere('receiver.sender_id', '=', Auth::id())
                    ->where('receiver.status', '!=', 'block');
            })
            ->orWhere('posts.privacy', '=', 'friends')
            ->where(function ($query) {
                $query->where('receiver.sender_id', '=', Auth::id())
                    ->where('receiver.status', '=', 'friend')
                    ->orWhere('sender.receiver_id', '=', Auth::id())
                    ->where('sender.status', '=', 'friend');
            })
            ->orWhere('post_privacy.viewer_id', '=', Auth::id())
            ->select('posts.*')
            ->distinct()
            ->paginate(10);
    }
    //this will return all viewable posts of a user(given by id)
    public static function viewablePostsOfUser($userId)
    {
        return DB::table('posts')
            ->leftJoin('friend_requests AS sender', 'posts.user_id', '=', 'sender.sender_id')
            ->leftJoin('friend_requests AS receiver', 'posts.user_id', '=', 'receiver.receiver_id')
            ->leftJoin('post_privacy', 'posts.id', '=', 'post_privacy.post_id')
            ->where('posts.user_id', '=', $userId)
            ->where(function ($queryA) {
                $queryA->where('posts.privacy', '=', 'public')
                    ->where(function ($queryAA) {
                        $queryAA->where('sender.receiver_id', '=', null)
                            ->where('receiver.sender_id', '=', null)
                            ->orWhere('sender.receiver_id', '=', Auth::id())
                            ->where('sender.status', '!=', 'block')
                            ->orWhere('receiver.sender_id', '=', Auth::id())
                            ->where('receiver.status', '!=', 'block');
                    })
                    ->orWhere('posts.privacy', '=', 'friends')
                    ->where(function ($queryAB) {
                        $queryAB->where('receiver.sender_id', '=', Auth::id())
                            ->where('receiver.status', '=', 'friend')
                            ->orWhere('sender.receiver_id', '=', Auth::id())
                            ->where('sender.status', '=', 'friend');
                    })
                    ->orWhere('post_privacy.viewer_id', '=', Auth::id());
            })
            ->select('posts.*')
            ->distinct()
            ->paginate(10);
    }
    //return true if the post is viewable and false if not
    public function isViewable()
    {
        if (Auth::user()->isAdmin()) {
            return true;
        }
        if ($this->privacy == 'private' && $this->user_id == Auth::id()) {
            return true;
        }
        if ($this->privacy == 'public' && !FriendRequest::isBlocked($this->user_id)) {
            return true;
        }
        if ($this->privacy == 'friends') {
            if (FriendRequest::isFriend($this->user_id)) {
                return true;
            }
        }
        if ($this->privacy == 'custom') {
            $viewer = DB::table('post_privacy')->where('post_id', '=', $this->id)->where('viewer_id', '=', Auth::id())->first();
            if ($viewer) {
                return true;
            }
        }
        return false;
    }
}
