<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class CreateFarmerTest extends TestCase
{
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $mock;
    protected $userMock;

    const URL = 'api/v1/farmers';

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

    public function testShouldReturnErrorIfNoToken()
    {
        $this->get('api/v1/farmers');
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
        $this->seeJson(['success' => false]);
    }

    public function testShouldReturnErrorInvalidToken()
    {
        $this->get('api/v1/farmers', $this->userMock->getAdminData(), ['Authorization' => 'sfdsfeftrgfbdfd5648970457534']);
        $this->seeStatusCode(401);
        $this->seeJson(['success' => false]);
    }

    public function testShouldReturnAllFarmers()
    {
        $this->get('api/v1/farmers', ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }
}
