<?php

namespace App\Utils;

use Crisu83\ShortId\ShortId;

class MockData
{
    public $wrongEmail = ['email' => 'someone@gmail.com', 'password' => '1234567'];
    public $wrongPassword = ['email' => 'admin1234@gmail.com', 'password' => '1234567'];
    public $wrongChangePassword = ['oldPassword' => '234retgfd23', 'newPassword' => 'admin2020'];
    public $correctPasswordChange = ['oldPassword' => 'admin2020', 'newPassword' => 'admin2020'];
    public $loginDetails = ['email' => 'admin2020@gmail.com', 'password' => 'admin2020'];
    public $passwordMismatchData = [
        'password' => 'admin12346',
        'confirmPassword' => 'admin12345',
        'adminRole' => 'Analyst',
        'firstname' => 'maxxy',
        'lastname' => 'max',
    ];

    public $invalidToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.
    eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.
    jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs';
    public $nonsenseToken = 'eyJ0eXAipPs';
    public $fakeToken = ['token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiI5NjM3ZDBmYi0yNjBkLTM3YTQtODI2ZS00NmQxNzI2M2Y1OWYiLCJpYXQiOjE1NjUyMTM3ODgsImV4cCI6MTU2NTgxODU4OH0.rRkJSE7-OxciThK95ghbl3kT4KSGsZw7vwDqFne464'];
    public $emptyToken = ['token' => ''];
    public $invalidData = [
        'email' => 'admin2020@gmail.com',
        'password' => 'masterAgent12345',
        'account_type' => 'someaccount',
        'value_chain' => 'somevalue',
        'account_name' => 'masteragent',
        'username' => 'masteragent',
        'contact_person' => 'Samuel',
        'phone_number' => '234567897654',
        'district' => 'somewhere',
        'address' => 'somewhere',
    ];

    public $shortid;
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->shortid = ShortId::create();
    }

    public function getWrongEmail()
    {
        return $this->wrongEmail;
    }
    public function getWrongPassword()
    {
        return $this->wrongPassword;
    }
    public function getLoginDetails()
    {
        return $this->loginDetails;
    }

    public function getPasswordMismatchData()
    {
        $this->passwordMismatchData['email'] = $this->shortid->generate() . '@gmail.com';
        return $this->passwordMismatchData;
    }

    public function getInvalidToken()
    {
        return $this->invalidToken;
    }
    public function getNonsenseToken()
    {
        return $this->nonsenseToken;
    }
    public function getFakeToken()
    {
        return $this->fakeToken;
    }
    public function getEmptyToken()
    {
        return $this->emptyToken;
    }

    public function getInvalidData()
    {
        return $this->invalidData;
    }
    public function getWrongChangePassword()
    {
        return $this->wrongChangePassword;
    }
    public function getChangePassword()
    {
        return $this->correctPasswordChange;
    }
}
