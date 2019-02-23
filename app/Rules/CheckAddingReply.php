<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Comment;

class CheckAddingReply implements Rule
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
        //check if the comment exist
        $comment = Comment::find($value);
        if (!$comment) {
            return false;
        }
        //check if the comment is viewable
        if (!$comment->isViewable()) {
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
        return 'The validation error message.';
    }
}
