<?php
use App\Utils\MockData;

class InputCategoryTest extends TestCase
{
    protected $response;
    protected $token;
    protected $mock;

    const URL = 'api/v1/farmers-orders';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );

        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }

    public function testShouldReturnNumberOfFarmersWhoOrderedInputsOfDifferentCategories()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }


    public function testShouldReturnErrorIfNoToken()
    {
        $this->get(self::URL);
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
        $this->seeJson(['success' => false]);
    }
}
