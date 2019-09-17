<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AdminRole implements Rule
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
        return $value === 'Super Admin' || $value === 'Analyst' || $value === 'Moderate Editor';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid role assigned to admin';
    }
}
