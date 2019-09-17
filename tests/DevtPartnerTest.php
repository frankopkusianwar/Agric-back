<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class DevtPartnerTest extends TestCase
{
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $mock;
    protected $userMock;

    const URL = '/api/v1/devt-partners';
    const URL_FILTER = '/api/v1/devt-partners/?start_date=2019-10-12&end_date=2020-12-12';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->userMock = new userMockData();
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

    public function testShouldReturnDevtPartners()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnDevtPartnersByDate()
    {
        $this->get(self::URL_FILTER, ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('count', $res_array);
        $this->assertArrayHasKey('result', $res_array);
        $this->assertArrayHasKey('percentage', $res_array);
    }

    public function testShouldReturnErrorForNoToken()
    {
        $this->get(self::URL);
        $this->seeStatusCode(401);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false]);
        $this->seeJson(['error' => 'Please log in first.']);
    }
    public function testShouldReturnErrorIfNonsenseToken()
    {
        $this->get(self::URL, ['Authorization' => $this->mock->getNonsenseToken()]);
        $this->seeStatusCode(400);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false]);
        $this->seeJson(['error' => 'An error occured while decoding token.']);
    }
    public function testShouldReturnErrorIfInvalidToken()
    {
        $this->get(self::URL, ['Authorization' => $this->mock->getInvalidToken()]);
        $this->seeStatusCode(400);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false]);
        $this->seeJson(['error' => 'An error occured while decoding token.']);
    }
    public function testShouldReturnErrorIfExpiredToken()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewDevtPartner(),
            ['Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJuMjlCQjB4IiwiaWF0IjoxNTYwNzk3OTYwLCJleHAiOjE1NjA4MDE1NjB9.htsI-0CmYkZom0_KDJokLc3AnaBovVmzRejKxw4Ffcs']
        );
        $this->seeStatusCode(400);
        $this->seeJson(['error' => 'Your current session has expired, please log in again.']);
    }

    public function testShouldReturnErrorIfUserIsNotAdmin()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewDevtPartner(),
            ['Authorization' => $this->wrongUser]
        );
        $this->seeStatusCode(403);
    }
    public function testShouldReturnUserIfTokenIsValid()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewDevtPartner(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }
}
