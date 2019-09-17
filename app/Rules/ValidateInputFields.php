<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/** @phan-file-suppress PhanUnusedPublicMethodParameter, PhanPossiblyNullTypeArgumentInternal, PhanPossiblyFalseTypeMismatchProperty */
class ValidateInputFields implements Rule
{
    private static $data;

    public function __construct($data)
    {
        self::$data = $data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value !== '';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Empty strings are not allowed.';
    }

    public static function isEmpty()
    {
        $errors = [];
        foreach (self::$data as $key => $value) {
            if (empty(trim($value))) {
                array_push($errors, ucfirst($key) . ' cannot be empty.');
            }
        }
        if (count($errors) > 0) {
            return $errors;
        }

        return false;
    }
}
