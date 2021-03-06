<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Post;

class CheckDeletedPhotos implements Rule
{
    public $request;
    public $postId;
    public $errorMessage;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, $postId)
    {
        $this->request = $request;
        $this->postId = $postId;
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
        if (count($value) == 0) {

            return false;
        }
        $oldPhotos = Post::find($this->postId)->photos;
        if (!$oldPhotos) {
            $oldPhotos = [];
        }
        if (count($value) >= count($oldPhotos)) {
            if (!$this->request->photos || count($this->request->photos) === 0) {
                if (!$this->request->body) {

                    return false;
                }
            }
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
        return "something wrong happen when deleting photos";
    }
}
