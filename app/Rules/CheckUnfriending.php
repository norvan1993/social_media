<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\FriendRequest;
use Illuminate\Support\Facades\Auth;
use App\User;

class CheckUnfriending implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!User::find($value)) {
            return false;
        }
        if (User::isSame($value, Auth::id())) {
            return false;
        }
        if (!FriendRequest::isFriend($value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'wrong friend_id';
    }
}
