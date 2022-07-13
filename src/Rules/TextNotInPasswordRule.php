<?php

namespace Desoft\DVoyager\Rules;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TextNotInPasswordRule extends Rule
{

    public $attribute = 'attribute';    
    public $text;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
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
        $stripped_password = str_replace(' ','',$value);
        $lower_stripped_password = strtolower($stripped_password);

        $stripped_text = str_replace(' ','',$this->text);
        $lower_stripped_text = strtolower($stripped_text);

        return !Str::contains($lower_stripped_password, $lower_stripped_text);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.text_not_in_password', [
            'attribute' => $this->attribute
        ]);
    }

    public function __toString()
    {
        return "text_not_in_password:".$this->text;
    }
}