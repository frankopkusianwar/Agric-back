<?php
use App\Utils\MockData;
use App\Models\RequestPassword;
use Firebase\JWT\JWT;

class ForgotPasswordTest extends TestCase
{
    const URL = ['/api/v1/auth/forgot-password', '/api/v1/auth/confirm-password'];

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->token = JWT::encode(["sub" => $this->mock->getLoginDetails()], env('JWT_SECRET'));
        $this->wrongToken = JWT::encode(["sub" => $this->mock->getWrongEmail()], env('JWT_SECRET'));
        RequestPassword::create([ 'token' => $this->token ]);
    }

    public function testShouldReturnAnErrorIfEmailDoesNotExist()
    {
        $this->post(self::URL[0], $this->mock->getWrongEmail());
        $this->seeStatusCode(404);
        $this->seeJson([
            "message" => "We could not find this email in our database.",
            "error" => true,
        ]);
    }

    public function testShouldReturnSuccessIfEmailExist()
    {
        $this->post(self::URL[0], $this->mock->getLoginDetails());
        $this->seeJson([
            "success" => true,
            "message" => "An email with password reset instructions has been sent to your email address. It would expire in 1 hour.",
        ]);
        $this->seeStatusCode(200);
    }

    public function testShouldReturnValidationErrorForInvalidEmail()
    {
        $this->post(self::URL[0], ['email' => $this->mock->getLoginDetails()]);
        $this->seeJson([ "email" =>["The email must be a valid email address."] ]);
        $this->seeStatusCode(422);
    }

    public function testShouldReturnAnErrorIfPasswordConfirmTokenDoesNotExist()
    {
        $this->put(self::URL[1], ['token' => $this->wrongToken, 'password' => 'admin2020']);
        $this->seeJson([
            "message" => "We could not update your password",
            "success" => false,
        ]);
        $this->seeStatusCode(404);
    }

    public function testShouldReturnSuccessIfPasswordConfirmSuccess()
    {
        $this->put(self::URL[1], ['token' => $this->token, 'password' => 'admin2020']);
        $this->seeJson([
            "success" => true,
            "message" => "Your Password has been updated, successfully",
        ]);
        $this->seeStatusCode(200);
    }

    public function testShouldReturnValidationErrorForEmptyToken()
    {
        $this->put(self::URL[1], $this->mock->getEmptyToken());
        $this->seeJson([ "password" => ["The password field is required."],"token" => ["The token field is required."]]);
        $this->seeStatusCode(422);
    }
}
