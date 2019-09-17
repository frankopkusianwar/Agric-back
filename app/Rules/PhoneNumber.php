<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\VillageAgent;

class PhoneNumber implements Rule
{
    public $phone;
    public $emptyError;

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
        $this->phone = $value;
        return VillageAgent::where('va_phonenumber', $this->phone)->get()->count() < 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A village agent with phone number ' . $this->phone . ' already exists';
    }
}
