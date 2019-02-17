<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{
    /*******************************************************************************
     *variables
     *******************************************************************************/
    protected $fillable = [
        'likeable_type', 'likeable_id', 'sender_id'
    ];
    /***********************************************************************
     * relations
     ***********************************************************************/
    public function commentable()
    {
        return $this->morphTo();
    }
    /******************************************************************************
     * custom functions
     ******************************************************************************/
    //isLiked
    public static function isLiked($likeableType, $likeableId)
    {

        $isLiked = self::where('likeable_type', '=', $likeableType)->where('likeable_id', '=', $likeableId)->where('sender_id', '=', Auth::id())->first();
        if ($isLiked) {
            return true;
        }
        return false;
    }
}
