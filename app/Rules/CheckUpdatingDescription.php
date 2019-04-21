<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Photo;
use Illuminate\Support\Facades\Auth;

class CheckUpdatingDescription implements Rule
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
        $photo = Photo::find($value);
        //finding photo_id
        if (!$photo) {
            return false;
        }
        //check if the photoable_type is post
        if ($photo->photoable_type != 'App\\Post') {
            return false;
        }
        //check if the photoable(post) belongs to authenticated user
        if ($photo->photoable->user_id != Auth::id()) {
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
