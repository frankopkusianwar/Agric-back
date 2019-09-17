<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AccountType implements Rule
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
        return $value === 'Custom' || $value === 'Generic' || $value === 'Test';
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid account type';
    }
}
