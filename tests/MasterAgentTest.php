<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class CreateMasterAgentTest extends TestCase
{
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $mock;
    protected $userMock;

    const URL = '/api/v1/users/masteragent';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->userMock = new UserMockData();

        $this->userMock = new UserMockData();
        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );

        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];

        $data2 = $this->call('POST', '/api/v1/auth/login', $this->userMock->getMasterAgentData());

        $decoded_data = json_decode($data2->getContent(), true);
        $this->wrongUser = $decoded_data['token'];
    }

    public function testShouldReturnErrorIfNoToken()
    {
        $this->post(self::URL, $this->userMock->getAdminData());
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
    }

    public function testShouldReturnMasteragents()
    {
        $this->get('api/v1/masteragents', ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnErrorIfInvalidToken()
    {
        $this->post(
            self::URL,
            ['Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.
            eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.
            jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs', ]
        );
        $this->seeStatusCode(401);
    }
    public function testShouldReturnUserIfTokenIsValid()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewMasterAgent(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(201);
        $this->seeJson(['success' => true]);
    }
    public function testShouldReturnErrorIfInvalidData()
    {
        $this->post(
            self::URL,
            $this->userMock->getInvalidData(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(422);
    }

    public function testShouldReturnErrorIfEmailExists()
    {
        $this->post(
            self::URL,
            $this->userMock->getExistingMasterAgent(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(422);
    }

    public function testShouldReturnErrorIfNonsenseToken()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewMasterAgent(),
            ['Authorization' => 'xfdgghhjk']
        );
        $this->seeStatusCode(400);
        $this->seeJson(['error' => 'An error occured while decoding token.']);
    }

    public function testShouldReturnErrorIfExpiredToken()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewMasterAgent(),
            ['Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJuMjlCQjB4IiwiaWF0IjoxNTYwNzk3OTYwLCJleHAiOjE1NjA4MDE1NjB9.htsI-0CmYkZom0_KDJokLc3AnaBovVmzRejKxw4Ffcs']
        );
        $this->seeStatusCode(400);
        $this->seeJson(['error' => 'Your current session has expired, please log in again.']);
    }

    public function testShouldReturnErrorIfUserIsNotAdmin()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewMasterAgent(),
            ['Authorization' => $this->wrongUser]
        );
        $this->seeStatusCode(403);
    }
}
