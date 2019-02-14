<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
//there are some functions should be here soon

}
