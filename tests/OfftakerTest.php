<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class OfftakerTest extends TestCase
{
    protected $mock;
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $userMock;
    const URL = 'api/v1/users/offtakers';
    const POST_URL = 'api/v1/users/offtaker';
    const URL_FILTER = '/api/v1/users/offtakers?start_date=2019-10-12&end_date=2020-12-12';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->userMock = new UserMockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];

        $data2 = $this->call('POST', '/api/v1/auth/login', $this->userMock->getMasterAgentData());

        $decoded_data = json_decode($data2->getContent(), true);
        $this->wrongUser = $decoded_data['token'];
    }

    public function testShouldReturnOfftakers()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnOfftakersByDate()
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
        $this->post(
            self::URL,
            ['Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.
            eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.
            jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs']
        );
        $this->seeStatusCode(401);
    }

    public function testShouldReturnErrorIfNoToken()
    {
        $this->post(self::POST_URL, $this->userMock->getAdminData());
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
    }

    public function testShouldReturnUserIfTokenIsValid()
    {
        $this->post(
            self::POST_URL,
            $this->userMock->getNewOfftaker(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(201);
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnErrorIfUserIsNotAdmin()
    {
        $this->post(
            self::POST_URL,
            $this->userMock->getNewOffTaker(),
            ['Authorization' => $this->wrongUser]
        );
        $this->seeStatusCode(403);
    }
}
