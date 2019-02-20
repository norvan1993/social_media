<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Comment;

class CheckAddingCommentLike implements Rule
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
        $comment = Comment::find($value);
        if (!$comment) {
            return false;
        }
        if (!$comment->isViewable()) {
            return false;
        }
        if (Like::isLiked('App\\Comment', $value)) {
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
        return 'wrong comment id';
    }
}
