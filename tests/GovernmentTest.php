<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class GovernmentTest extends TestCase
{
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $mock;
    protected $userMock;

    const URL = '/api/v1/users/government';
    const INVALIDURL = '/api/v1/users/something';


    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->userMock = new UserMockData();

        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );

        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }

    public function testShouldReturnUserIfTokenIsValid()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewGovernmentPartner(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(201);
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnErrorIfParameterIsInvalid()
    {
        $this->post(
            self::INVALIDURL,
            $this->userMock->getNewGovernmentPartner(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(400);
        $this->seeJson(['success' => false]);
    }
}
