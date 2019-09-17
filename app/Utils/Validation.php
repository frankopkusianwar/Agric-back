<?php

namespace App\Utils;

use App\Rules\AccountType;
use App\Rules\AdminRole;
use App\Rules\District;
use App\Rules\Email;
use App\Rules\EmailUpdate;
use App\Rules\ValueChain;
use App\Rules\PhoneNumber;
use App\Rules\UserName;
use App\Rules\ValidateInputCategory;
use App\Rules\AgronomicalInfo;
use Laravel\Lumen\Routing\Controller as BaseController;

class Validation extends BaseController
{
    private $email;
    public function validateAdmin($data)
    {
        $this->validate($data, [
            'firstname' => 'required|regex:/^([a-zA-z\s\-\(\)]*)$/',
            'lastname' => 'required|regex:/^([a-zA-z\s\-\(\)]*)$/',
            'email' => ['required', 'email', new Email($data['email'])],
            'password' => 'required|min:6',
            'confirmPassword' => 'required',
            'adminRole' => ['required', new AdminRole],
        ]);
    }
    public function validateForgotPassword($data)
    {
        $this->validate($data, [
            'email' => 'required|email',
        ]);
    }
    public function validateConfirmPassword($data)
    {
        $this->validate($data, [
            'password' => 'required|min:8',
            'token' => 'required|min:25',
        ]);
    }
    public function validateVerifyPasswordToken($data)
    {
        $this->validate($data, [
            'token' => 'required|min:25',
        ]);
    }
    public function validateLogin($data)
    {
        $this->validate($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
    /**
     * @param \Illuminate\Http\Request $data
     */
    public function validateNewAccount($data)
    {
        $this->validate($data, [
            'email' => ['required', 'email', new Email($data['email'])],
            'password' => 'required|min:6',
            'account_type' => ['required', new AccountType()],
            'account_name' => 'required|regex:/^([a-zA-z0-9\s\-\(\)]*)$/',
            'username' => ['required', new UserName($data['username']), 'regex:/^([a-zA-z0-9\s\-\(\)]*)$/'],
            'contact_person' => 'required|regex:/^([a-zA-z\s\-\+\(\)]*)$/',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:17',
            'district' => ['required', new District()],
            'address' => 'required|regex:/^([a-zA-z0-9\s\,\(\)]*)$/',
            'value_chain' => ['required', new ValueChain()],
        ]);
    }
    public function validateAccountRequest($data)
    {
        $this->validate($data, [
            'email' => ['required', 'email', new Email($data['email'])],
            'account_name' => 'required|regex:/^([a-zA-z\s\-\(\)]*)$/',
            'username' => ['required', new UserName($data['username']), 'regex:/^([a-zA-z0-9\s\-\(\)]*)$/'],
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:17',
            'address' => 'required|regex:/^([a-zA-z\s\-\(\)]*)$/',
        ]);
    }
    public function validateContactForm($data)
    {
        $this->validate($data, [
            'email' => 'required|email',
            'name' => 'required||regex:/^([a-zA-z\s\-\+\(\)]*)$/',
            'message' => 'required',
        ]);
    }
    public function validateAdminChangePassword($data)
    {
        $this->validate($data, [
            'oldPassword' => 'required|min:6',
            'newPassword' => 'required|min:6',
        ]);
    }
    public function validateExistingAccount($data, $id)
    {
        $this->validate($data, [
            'email' => ['email', new EmailUpdate($data['email'], $id)],
            'account_type' => [new AccountType()],
            'firstname' => 'regex:/^([a-zA-z\s\-\(\)]*)$/',
            'lastname' => 'regex:/^([a-zA-Z\s\-\(\)]*)$/',
            'contact_person' => 'regex:/^([a-zA-z\s\-\+\(\)]*)$/',
            'phonenumber' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:17',
            'district' => [new District()],
            'address' => 'regex:/^([a-zA-z0-9\s\,\(\)]*)$/',
            'value_chain' => [new ValueChain()],
            'adminRole' => [new AdminRole()],
        ]);
    }
    public function validateVillageAgentData($data)
    {
        // dd($data);
        $this->validate($data, [
        'villageAgents.*.va_gender' => 'required',
        'villageAgents.*.va_region' => 'required',
        'villageAgents.*.va_subcounty' => 'required',
        'villageAgents.*.va_phonenumber' => ['required', new PhoneNumber()],
        'villageAgents.*.va_district' => [new District()]
      ]);
    }
    public function validateLimitAndOffset($data)
    {
        $this->validate($data, [
            'offset' => 'numeric',
            'limit' => 'numeric',
            'page' => 'numeric'
        ]);
    }
    public function validateInputData($data)
    {
        $this->validate($data, [
            'name' => 'required|regex:/^([a-zA-z\s\-\(\)]*)$/',
            'crops' => 'required',
            'category' => [new ValidateInputCategory()],
            'description' => 'required|string',
            'price' => 'required',
            'unit' => 'required',
            'supplier' => 'required|string|max:20',
            'quantity' => 'required|numeric'
        ]);
    }
    public function validateDiagnosisInformation($data)
    {
        $this->validate($data, [
        'name' => 'required',
        'photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        'control' => 'required',
        'explanation' => 'required',
        'crop' => 'required',
        'cause' => 'required'
      ]);
    }

    public function validateOurCropsData($data)
    {
        $this->validate($data, [
          'photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
          'crop' => 'required|regex:/^([a-zA-Z\s\-\(\)]*)$/']);
    }
    /**
     * Catch unaccepted data types and formats passed in the request.
     *
     * @param  \Illuminate\Http\Request $data
     *
     */
    public function validateAgronomicalInput($data)
    {
        $this->validate($data, [
            'purpose'=>['sometimes', new AgronomicalInfo()],
            'title'=>'sometimes',
            'description' => 'sometimes',
            'photo' => 'sometimes | mimes:jpeg,bmp,png,jpg| max:2048'
        ]);
    }
}
