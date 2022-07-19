<?php

namespace Desoft\DVoyager\Rules;

use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Validation\Rule;

class DimensionsValidationRule implements Rule
{
    public $attribute = 'attribute';
    public $min_width;
    public $min_height;
    public $file_name;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min_width, $min_height, $file_name)
    {
        $this->min_width = $min_width;
        $this->min_height = $min_height;
        $this->file_name =  $file_name;
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
        $h = Image::make($value)->height();
        $w = Image::make($value)->width();

        return ($w >= $this->min_width) && ($h >= $this->min_height);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('dvoyager::validation.custom_dimensions',[
                                                  'attribute' => $this->attribute,
                                                  'file_name' => $this->file_name,
                                                  'value_h' => $this->min_height,
                                                  'value_w' => $this->min_width]);
    }

    public function __toString()
    {
        return 'dimensions_validation';
    }
}