<?php

namespace Desoft\DVoyager\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomUrlRule implements Rule
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
        $customUrlRegex = "~https?://[-a-zA-Z0-9@:%._\\+\\~\\#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b([-a-zA-Z0-9()@:%_\\+.\\~\\#?&//=]*)~";

        return preg_match($customUrlRegex, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('dvoyager::validation.custom_url', ['attribute' => $this->attribute]);
    }

    public function __toString()
    {
        return "custom_url";
    }
}