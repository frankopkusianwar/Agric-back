<?php
use App\Utils\MockData;

class TopPerformingDistrictTest extends TestCase
{
    const URL = '/api/v1/top-performing-district';
    const District_URL = '/api/v1/top-performing-district/bushenyi';
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }

    public function testShouldReturnTopPerformingDistricts()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnDistrictStatistics()
    {
        $this->get(self::District_URL, ['Authorization' => $this->token]);
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

    public function testShouldCatchException()
    {
        $mock = $this->createMock('App\Http\Controllers\DistrictController');
        $mock->expects($this->once())
      ->method('getTopPerformingDistricts')
      ->willThrowException(new Exception);

        $this->expectException(Exception::class);
        $mock->getTopPerformingDistricts();
        $this->seeJson(['success' => false, 'error' => 'Something went wrong.']);
    }
}
