<?php
use App\Utils\MockData;

class NumberOfVisitorsTest extends TestCase
{
    const URL = '/api/v1/visitor';
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }

    public function testShouldReturnNumberOfVisitors()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }
}
