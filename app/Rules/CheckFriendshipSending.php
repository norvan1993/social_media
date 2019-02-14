<?php

namespace App\Rules;

use App\FriendRequest;
use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckFriendshipSending implements Rule
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
        if (FriendRequest::isRelated($value)) {
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
        return 'wrong receiver id';
    }
}
