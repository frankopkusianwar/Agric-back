<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EmailUpdate implements Rule
{
    private $email;
    private $id;

    public function __construct($email, $id)
    {
        $this->email = $email;
        $this->id = $id;
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
        $user = DB::select('select * from ' . $db . ' where email = ? AND (type = "ma" OR type  = "offtaker" OR type = "admin" OR type="partner")', [$this->email]);
        return (!$user || $user[0][$db]['_id'] === $this->id) ? $value : false;
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
