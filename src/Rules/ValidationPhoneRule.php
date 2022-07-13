<?php

namespace Desoft\DVoyager\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidationPhoneRule implements Rule
{
    public $country_code;
    public $phone_lenght;
    public $attribute = 'attribute';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($country_code = 53, $phone_lenght = 8)
    {
        $this->country_code = $country_code;
        $this->phone_length = $phone_lenght;
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
        $customRegex = "~^\\+".$this->country_code."[0-9]{".$this->phone_length."}$~";

        return preg_match($customRegex, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $nss = "";
        for($i = 0; $i < $this->phone_length; $i++)
        {
            $nss = $nss.'#';
        }

        return __('dvoyager::validation.validation_phone',['country_code' => $this->country_code,
                                                           'phone_length' => $nss,
                                                           'attribute' => $this->attribute
                                                          ]);
    }

    public function __toString()
    {
        return 'validation_phone:'.$this->country_code.','.$this->phone_length;
    }
}