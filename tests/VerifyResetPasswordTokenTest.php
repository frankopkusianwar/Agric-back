<?php
use App\Utils\MockData;
use App\Models\RequestPassword;

class VerifyResetPasswordTokenTest extends TestCase
{
    const URL = '/api/v1/auth/password-verification-token';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->token = $this->mock->getInvalidToken();
        RequestPassword::create([ 'token' => $this->token ]);
    }

    public function testShouldReturnAnErrorIfTokenDoesNotExist()
    {
        $this->post(self::URL, $this->mock->getFakeToken());
        $this->seeStatusCode(401);
        $this->seeJson([
            "success" => false,
        ]);
    }

    public function testShouldReturnValidationErrorForEmptyToken()
    {
        $user = $this->post(self::URL, $this->mock->getEmptyToken());
        $this->seeStatusCode(422);
        $this->seeJson([
            "token" => ["The token field is required."]
        ]);
    }

    public function testShouldReturnSuccessIfTokenExist()
    {
        $user = $this->post(self::URL, ['token' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson([
            "success" => true,
            "message" => "verified",
        ]);
    }
}
