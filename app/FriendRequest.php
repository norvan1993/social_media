<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FriendRequest extends Model
{
    /*****************************************************************************
     *variables
     *****************************************************************************/
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    /*****************************************************************************
     *relations
     *****************************************************************************/
    public function user()
    {
        return $this->belongsTo('App\User', 'sender_id', 'id');
    }
    /*****************************************************************************
     *custom functions
     ***************************************************************************/
    //isSentTo
    public static function isSentTo($receiver_id)
    {
        if (self::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $receiver_id)->where('status', '=', 'sent')->first()) {
            return true;
        }
        return false;
    }
    //isReceivedFrom
    public static function isReceivedFrom($sender_id)
    {
        if (self::where('sender_id', '=', $sender_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'sent')->first()) {
            return true;
        }
        return false;
    }
    //isRelated
    public static function isRelated($user_id)
    {
        if (self::where('sender_id', '=', $user_id)->where('receiver_id', '=', Auth::id())->first()) {
            return true;
        }

        if (self::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $user_id)->first()) {
            return true;
        }
        return false;
    }
    //isBlockByUser
    public static function isBlockedByUser($user_id)
    {
        if (self::where('sender_id', '=', $user_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'block')->first()) {
            return true;
        }
        return false;
    }
    //isBlockedByAuth
    public static function isBlockedByAuth($user_id)
    {
        if (self::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $user_id)->where('status', '=', 'block')->first()) {
            return true;
        }
        return false;
    }
    //isBlocked
    public static function isBlocked($user_id)
    {
        if (self::isBlockedByAuth($user_id)) {
            return true;
        }
        if (self::isBlockedByUser($user_id)) {
            return true;
        }

        return false;
    }
    //isFriend
    public static function isFriend($user_id)
    {
        if (self::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $user_id)->where('status', '=', 'friend')->first()) {
            return true;
        }
        if (self::where('sender_id', '=', $user_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'friend')->first()) {
            return true;
        }
        return false;
    }
    //isSender
    public static function isSender($user_id)
    {
        $tableRow = self::where('sender_id', '=', $user_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'friend')->first();
        if ($tableRow) {
            return true;
        }
        return false;
    }
    //isReceiver
    public static function isReceiver($user_id)
    {
        $tableRow = self::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $user_id)->where('status', '=', 'friend')->first();
        if ($tableRow) {
            return true;
        }
        return false;
    }
    //relationType
    public static function relationType($user_id)
    {
        $relation = null;
        if (Auth::id() == $user_id) {
            return "owner";
        }
        if (self::isFriend($user_id)) {
            return "friend";
        }
        if ($relation = self::where('sender_id', '=', $user_id)->where('receiver_id', '=', Auth::id())->first()) {
            if ($relation->status == "sent") {
                return "sender";
            }
            return $relation->status;
        }
        if ($relation = self::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $user_id)->first()) {
            if ($relation->status == "sent") {
                return "receiver";
            }
            return $relation->status;
        }
        return "not_friend";
    }
}
