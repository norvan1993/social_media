<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Comment;
use App\FriendRequest;
use Illuminate\Support\Facades\Auth;

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
        //getting post owner id
        $postOwnerId = null;
        if ($comment->commentable_type == 'App\\Post') {
            $postOwnerId = $comment->commentable->user_id;
        }
        if ($comment->commentable_type == 'App\\Photo') {
            $postOwnerId = $comment->commentable->photoable->user_id;
        }
        //check if the post owner is the authenticated user
        if ($postOwnerId == Auth::id()) {
            return true;
        }
        //check if the post owner is friend
        if (!FriendRequest::isFriend($postOwnerId)) {
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
