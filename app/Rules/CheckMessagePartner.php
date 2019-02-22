<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\FriendRequest;

class CheckMessagePartner implements Rule
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
        //check id in users table
        $user = User::find($value);
        if (!$user) {
            return false;
        }
        //check if the message partner is same as authenticated user
        if (User::isSame(Auth::id(), $value)) {
            return false;
        }
        //check if the message partner is blocked
        if (FriendRequest::isBlocked($value)) {
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
        return 'wrong partner_id';
    }
}
