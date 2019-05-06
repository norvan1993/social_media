<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class requireWithoutAllExceptBody implements Rule
{
    public $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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

        //required_without_all except body(present)
        if (
            !$this->request->exists('body') &&
            !$this->request->title &&
            !$this->request->photos &&
            !$this->request->deleted_photos
        ) {
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
        return $this->errorMessage;
    }
}
