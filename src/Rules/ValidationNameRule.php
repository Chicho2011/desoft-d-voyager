<?php

namespace Desoft\DVoyager\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidationNameRule implements Rule
{
    public $attribute = 'attribute';
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
        $this->attribute = $attribute;
        $customRegex = "~^[^0-9]+$~";

        return preg_match($customRegex, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('dvoyager::validation.validation_name', ['attribute' => $this->attribute]);
    }

    public function __toString()
    {
        return 'validation_name';
    }
}