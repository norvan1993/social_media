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
    public function viewablePosts()
    {
        return DB::table('posts')
            ->leftJoin('post_privacy', 'posts.id', '=', 'post_privacy.post_id')
            ->leftJoin('friend_requests AS sender', 'posts.user_id', '=', 'sender.sender_id')
            ->leftJoin('friend_requests AS receiver', 'posts.user_id', '=', 'receiver.receiver_id')
            ->where('posts.user_id', '=', Auth::id())
            ->orWhere('posts.privacy', '=', 'public')
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
    public function viewablePostsOfUser($userId)
    {
        return DB::table('posts')
            ->leftJoin('friend_requests AS sender', 'posts.user_id', '=', 'sender.sender_id')
            ->leftJoin('friend_requests AS receiver', 'posts.user_id', '=', 'receiver.receiver_id')
            ->leftJoin('post_privacy', 'posts.id', '=', 'post_privacy.post_id')
            ->where('posts.user_id', '=', $userId)
            ->where(function ($query1) {
                $query1->where('posts.privacy', '=', 'public')
                    ->orWhere('posts.privacy', '=', 'friends')
                    ->where(function ($query2) {
                        $query2->where('receiver.sender_id', '=', Auth::id())
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


}
