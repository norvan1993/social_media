<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Post;

class CheckBodyOfUpdatingPost implements Rule
{
    public $request;
    public $postId;
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
        //check if no body and no photos
        if (!isset($this->request->body)) {
            $oldPhotos = Post::find($this->postId)->photos;
            if (!$oldPhotos) {
                if (!$this->request->photos) {
                    return false;
                }
                $oldPhotos = [];
            }
            if ($this->request->deleted_photos) {
                if (count($this->request->deleted_photos) >= count($oldPhotos)) {
                    if (!isset($this->request->photos)) {
                        return false;
                    }
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
        return 'The validation error message.';
    }
}
