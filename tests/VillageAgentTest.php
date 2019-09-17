<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class VillageAgentTest extends TestCase
{
    protected $token;
    protected $userMock;
    const URL = '/api/v1/users/village-agents';
    const POST_URL = 'api/v1/village-agents';
    const URL_FILTER = '/api/v1/users/village-agents/?start_date=2019-10-12&end_date=2020-12-12';
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
    public function testShouldReturnVillageAgents()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }
    public function testShouldReturnVillageAgentsByDate()
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
    public function testShouldReturnSuccessForValidVaData()
    {
        $this->post(self::POST_URL, $this->userMock->getValidVaData(), ['Authorization' => $this->token]);
        $this->seeStatusCode(201);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->seeJson(['message' => 'Village agents added successfully.']);
    }
    public function testShouldReturnErrorForDuplicateVaPhoneNo()
    {
        $this->post(self::POST_URL, $this->userMock->getDuplicateVaPhoneNo(), ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false]);
        $this->seeJson(['error' => 'Duplicate phone numbers found in entry. Phone numbers must be unique.']);
    }
    public function testShouldReturnErrorForInvalidVaData()
    {
        $this->post(self::POST_URL, $this->userMock->getInvalidVaData(), ['Authorization' => $this->token]);
        $this->seeStatusCode(422);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
    }
}
