<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValueChain implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    /** @phan-suppress-next-line PhanUnusedPublicMethodParameter */
    public function passes($attribute, $value)
    {
        return $value === 'Crop' || $value === 'Dairy' || $value === 'N/A';
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid value chain';
    }
}
