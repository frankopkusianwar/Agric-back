<?php
use App\Utils\MockData;

class GetMostOrderedProductsAndServicesTest extends TestCase
{
    const URL = '/api/v1/most-ordered?';
    const validDistrictQueryParameter = 'type=districts&filter=Ntoroko';
    const validEnterpriseQueryParameter = 'type=enterprises&filter=Maize';
    const invalidQueryParameter = 'type=&filter=';
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }
    public function testShouldReturnMostOrderedProductsAndServicesForDistrict()
    {
        $this->get(self::URL.self::validDistrictQueryParameter, ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('data', $res_array);
        $this->assertArrayHasKey('data', $res_array);
    }

    public function testShouldReturnMostOrderedProductsAndServicesForEnterprise()
    {
        $this->get(self::URL.self::validEnterpriseQueryParameter, ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('data', $res_array);
        $this->assertArrayHasKey('data', $res_array);
    }

    public function testShouldReturnErrorIfInvalidQueryParameterIsSupplied()
    {
        $this->get(self::URL.self::invalidQueryParameter, ['Authorization' => $this->token]);
        $this->seeStatusCode(422);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false, 'error' => 'Please supply both the filter and type parameters.']);
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
