<?php
namespace App\Rules;

use App\Models\InputCategory;
use Illuminate\Contracts\Validation\Rule;

class ValidateInputCategory implements Rule
{
    private $category;
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
        $this->category = $value;
        return InputCategory::where('name', $this->category)->get()->count() === 1;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid input category';
    }
}
