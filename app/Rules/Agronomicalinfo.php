<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AgronomicalInfo implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return bool
     */
    /** @phan-suppress-next-line PhanUnusedPublicMethodParameter */
    public function passes($attribute, $value)
    {
        return $value === 'Land Preparation' || $value === 'Planting' || $value === 'Management' || $value === 'Post Harvest';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid activity';
    }
}
