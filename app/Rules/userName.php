<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserName implements Rule
{
    private $username;
    private $db;

    public function __construct($username)
    {
        $this->username = $username;
        $this->db = getenv('DB_DATABASE');
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
        $userName = DB::select('SELECT * FROM ' . $this->db . ' WHERE username = ?', [strtolower($this->username)]);
        return (!$userName) ? $value : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Username has been taken.';
    }
}
