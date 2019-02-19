<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**************************************************************************
     *variables
     **************************************************************************/
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'user_image_id', 'is_active', 'default_post_privacy'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**************************************************************************
     *relations
     **************************************************************************/

//belongs to Role
    public function role()
    {
        return $this->belongsTo('App\Role');
    }
//belongs to UserImage
    public function user_image()
    {
        return $this->belongsTo('App\UserImage');
    }
//has many send requests(FriendRequest)
    public function send_requests()
    {
        return $this->hasMany('App\FriendRequest', 'sender_id', 'id');
    }
//has many received requests(FriendRequest)
    public function received_requests()
    {
        return $this->hasMany('App\FriendRequest', 'receiver_id', 'id');
    }
//has many posts
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
//has many likes
    public function likes()
    {
        return $this->hasMany('App\Like', 'sender_id');

    }
//has many comments
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /***********************************************************************
     *model events
     ************************************************************************/
    protected
        static function boot()
    {
        parent::boot();
//
        static::deleting(function ($user) {
//deleting user image
            if ($user->user_image) {
                $user->delete_user_image();
                $user->user_image->delete();

            }
//deleting user posts
            if ($user->posts) {
                $user->posts->each(function ($post) {
                    $post->delete();
                });
            }
        });
    }

    /***********************************************************************
     *custom functions
     ************************************************************************/

//deleting user(profile) image from directory---@return true on success false on fail
    public function delete_user_image()
    {
        if ($this->user_image) {
            $fullPath = './../storage/app/images/' . $this->user_image->file;
            unlink($fullPath);
            return true;
        }
        return false;
    }
//@return true if the user is administrator,false if not
    public function isAdmin()
    {
        if ($this->role) {
            if ($this->role->name == 'adminstrator') {
                return true;
            }

        }
        return false;
    }
//@return true if the two params are same value ,false if not
    public static function isSame($id1, $id2)
    {
        if ($id1 == $id2) {
            return true;
        }
        return false;
    }




}
