<?php

use App\Utils\MockData;
use App\Utils\CropMockData;
use App\Models\OurCrop;

class OurCropTest extends TestCase
{
    protected $response;
    protected $token;
    protected $mock;
    const URL = '/api/v1/crops/';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->cropMock = new CropMockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $data = json_decode($this->response->getContent(), true);
        $this->token = $data['token'];
    }

    public function testShouldCheckIfOurCropIsCreatedWithValidData()
    {
        $this->post(
            self::URL,
            $this->cropMock->getNewCropData(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(201);
    }

    public function testShouldFailCreatingCropWithInvalidData()
    {
        $this->post(
            self::URL,
            $this->cropMock->getInvalidNewCropData(),
            ['Authorization' => $this->token]
        );
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeStatusCode(503);
    }

    public function testShouldReturnCrops()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('data', $res_array);
    }
    
    public function testShouldReturnSingleCrop()
    {
        $cropInfo = $this->cropMock->getNewCropData();
        $crop = OurCrop::create($cropInfo)->getAttributes();
        $this->get(self::URL . $crop['id'], ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('data', $res_array);
    }

    public function testShouldCorrectlyEditCropInformation()
    {
        $cropInfo = $this->cropMock->getNewCropData();
        $crop = OurCrop::create($cropInfo)->getAttributes();
        $this->post(
            self::URL. $crop['id'],
            $this->cropMock->getEditCropData(),
            ['Authorization' => $this->token]
        );
        $res_array = json_decode($this->response->content(), true);
        $this->seeStatusCode(200);
        $this->assertEquals($res_array['data']['crop'], $this->cropMock->getEditCropData()['crop']);
    }

    public function testShouldDeleteSingleCropInformationSuccessfully()
    {
        $cropInfo = $this->cropMock->getNewCropData();
        $crop = OurCrop::create($cropInfo)->getAttributes();
        $this->delete(self::URL . $crop['id'], [], ['Authorization' => $this->token]);
        $res_array = json_decode($this->response->content(), true);
        $this->seeStatusCode(200);
        $this->assertEquals($res_array['data'], null);
    }

    public function testShouldReturnErrorForInvalidCropIDOnDeleted()
    {
        $cropInfo = $this->cropMock->getNewCropData();
        $crop = OurCrop::create($cropInfo)->getAttributes();
        $fakeID= 345;
        $this->delete(self::URL . $fakeID, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(500);
    }

    public function testShouldReturnErrorIfInvalidCropIsEdited()
    {
        $cropInfo = $this->cropMock->getNewCropData();
        $crop = OurCrop::create($cropInfo)->getAttributes();
        $this->post(self::URL. $crop['id'], [], ['Authorization' => $this->token]);
        $this->seeStatusCode(503);
    }
}
