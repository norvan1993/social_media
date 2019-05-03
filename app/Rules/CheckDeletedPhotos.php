<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Photo;
use Illuminate\Support\Facades\Auth;



class CheckDeletedPhotos implements Rule
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
        $photo = null;
        if ($value !== null && $value !== '' && !empty($value)) {
            if (!$photo = Photo::find($value)) {
                return false;
            }
            if (!($photo->photoable->user_id == Auth::id())) {
                return false;
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
        return 'selected photos id for delete were wrong';
    }
}
