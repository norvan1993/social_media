<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    /***************************
     *variables
     ***************************/
    protected $fillable = [
        'file'
    ];

    /***********************************************************
     *relations
     ***********************************************************/
//has one user
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /************************************************************
     *custom functions
     ************************************************************/
//return  the url of user image
    public function imageWithFolder()
    {
        return '/photos/' . $this->file;

    }


}
