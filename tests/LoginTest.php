<?php
use App\Utils\MockData;

class Login extends TestCase
{
    const URL = '/api/v1/auth/login';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
    }

    public function testShouldReturnAnErrorIfWrongEmail()
    {
        $this->post(self::URL, $this->mock->getWrongEmail());
        $this->seeStatusCode(404);
        $this->seeJson(['error' => 'The Email or password supplied is incorrect.']);
    }

    public function testShouldReturnAnErrorIfWrongPassword()
    {
        $this->post(self::URL, $this->mock->getWrongPassword());
        $this->seeJson(['success' => false]);
        $this->seeStatusCode(404);
        $this->seeJson(['error' => 'The Email or password supplied is incorrect.']);
    }

    public function testShouldReturnSuccessOnSuccessfulLogin()
    {
        $user = $this->post(self::URL, $this->mock->getLoginDetails());
        $this->seeJson(['success' => true]);
        $this->seeStatusCode(200);
        $this->assertIsObject($user);
    }
}
