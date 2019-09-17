<?php
use App\Utils\MockData;
use App\Utils\UserMockData;

class AccountRequestTest extends TestCase
{
    protected $mock;
    protected $response;
    protected $userMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->userMock = new UserMockData();
    }

    public function testShouldReturnOfftakersRequestDetails()
    {
        $this->post('api/v1/request/offtaker', $this->userMock->getAccountRequest());
        $this->seeStatusCode(201);
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnMasteragentRequestDetails()
    {
        $this->post('api/v1/request/masteragent', $this->userMock->getAccountRequest());
        $this->seeStatusCode(201);
        $this->seeJson(['success' => true]);
    }
}
