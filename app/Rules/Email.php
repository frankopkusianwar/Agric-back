<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class Email implements Rule
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

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
        $db = getenv('DB_DATABASE');
        $user_email = DB::select('select * from ' . $db . ' where email = ?', [$this->email]);
        return (!$user_email) ? $value : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email has been taken';
    }
}
