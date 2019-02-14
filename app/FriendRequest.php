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
     *****************************************************************************/
//sending validation
    public static function sendingValidation($sender_id, $receiver_id)
    {
        if ($sender_id == $receiver_id) {
            return false;
        }

        if (self::where('sender_id', '=', $sender_id)->where('receiver_id', '=', $receiver_id)->first()) {
            return false;
        }

        if (self::where('sender_id', '=', $receiver_id)->where('receiver_id', '=', $sender_id)->first()) {
            return false;
        }

        return true;
    }
//check if a specific friend request has been sent
    public static function isSent($sender_id, $receiver_id)
    {

        if (self::where('sender_id', '=', $sender_id)->where('receiver_id', '=', $receiver_id)->where('status', '=', 'sent')->first()) {
            return true;
        }

        return false;

    }
    /****************************************************************************
     * 12-2-2019 logic
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

}
