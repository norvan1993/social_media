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
    //has one polymorhic photo
    public function photo()
    {
        return $this->morphOne('App\Photo', 'photoable');
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
    //has many default viewers
    public function dafault_viewers()
    {
        return $this->hasMany('App\DefaultPostPrivacy', 'owner_id', 'id');
    }
    //has many default owners
    public function dafault_owners()
    {
        return $this->hasMany('App\DefaultPostPrivacy', 'viewer_id', 'id');
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
            //deleting user photo
            if ($user->photo) {
                //delete user photo from directory
                $user->deleteUserPhotoFromDir();
                //delete user photo from database
                $user->photo->delete();
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
    public function deleteUserPhotoFromDir()
    {
        if ($this->photo) {
            $fullPath = './../storage/app/images/' . $this->photo->file;
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
