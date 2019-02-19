<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Route;
use App\Photo;


class CheckPhotoStatus implements Rule
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
        $route = Route::current();
        $commentId = $route->originalParameter('comment');
        if ($value == 'same') {
            if (!Photo::where('photoable_type', '=', 'App\\Comment')->where('photoable_id', '=', $commentId)->first()) {
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
        return 'wrong photo status';
    }
}
