<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Post;
use App\Like;
use App\FriendRequest;

class CheckAddingPostLike implements Rule
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
        $post = Post::find($value);
        if (!$post) {
            return false;
        }
        if (!FriendRequest::isFriend($post->user_id)) {
            return false;
        }
        if (!$post->isViewable()) {
            return false;
        }
        if (Like::isLiked('App\\Post', $value)) {
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
        return 'wrong post id';
    }
}
