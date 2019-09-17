<?php

use App\Utils\AgronomicalInfoMockData;
use App\Models\CropInfo;
use App\Utils\Helpers;

class AgronomicalInfoTest extends TestCase
{
    const URL = '/api/v1/agronomical-info/';
    const ID = 'fake';
    protected $response;
    protected $mock;
    protected $data;
    protected $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->mock =  new AgronomicalInfoMockData();
        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );
        $this->data = json_decode($this->response->getContent(), true);
        $this->token = $this->data['token'];
    }

    public function testShouldEditAgronomicalInfo()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->post(self::URL.$requestBody['id'], $this->mock->getAgronomicalInfoData(), ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true, 'message' => 'update successful']);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
    }

    public function testShouldDeleteAgronomicalInfo()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->delete(self::URL.$requestBody['id'], [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true, 'message' => 'Agronomical info successfully deleted.']);
    }

    public function testShouldNotDeleteAgronomicalInfoOfNonExistingDocument()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->delete(self::URL.self::ID, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->seeJson(['success' => false, 'error' => 'Agronomical info doesnt exist.']);
    }

    public function testShouldNotEditAgronomicalInfoOfNonExistingDocument()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->post(self::URL.self::ID, $this->mock->getAgronomicalInfoData(), ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->seeJson(['success' => false, 'error' => 'Agronomical info doesnt exist.']);
    }

    public function testShouldNotEditAgronomicalInfoWithWrongType()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->post(self::URL.$requestBody['id'], $this->mock->getWrongAgronomicalInfoData(), ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(422);
        $this->assertArrayHasKey('error', $res_array);
    }

    public function testUnAuthorisedEdit()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->post(self::URL.$requestBody['id'], []);
        $this->seeStatusCode(401);
        $this->seeJson(['success' => false, 'error' => 'Please log in first.']);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
    }
    
    public function testUnAuthorisedDelete()
    {
        $requestBody = CropInfo::create($this->mock->getAgronomicalInfoData())->getAttributes();
        $this->delete(self::URL.$requestBody['id'], []);
        $this->seeStatusCode(401);
        $this->seeJson(['success' => false, 'error' => 'Please log in first.']);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
    }
}
