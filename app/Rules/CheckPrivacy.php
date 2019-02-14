<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckPrivacy implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        if ($value == 'public') {
            return true;
        }
        if ($value == 'private') {
            return true;
        }
        if ($value == 'friends') {
            return true;
        }
        if ($value == 'specific') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'wrong privacy value';
    }
}
