<?php
use App\Utils\MockData;

class TotalAcreageTest extends TestCase
{
    const URL = '/api/v1/total-acreage';
    const URL_FILTER = '/api/v1/total-acreage/?start_date=2019-10-12&end_date=2020-12-12';
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }
    public function testShouldReturnTotalAcreage()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }
    public function testShouldReturnTotalAcreageByDate()
    {
        $this->get(self::URL_FILTER, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
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
}
