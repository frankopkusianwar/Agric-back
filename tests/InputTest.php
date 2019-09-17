<?php

use App\Utils\InputData;
use App\Models\InputSupplier as InputModel;
use App\Utils\InputSupplierHelpers;

class InputTest extends TestCase
{
    protected $token;
    protected $response;
    protected $data;
    protected $mock;
    protected $createInput;
    protected $modelPath = 'App\Models\InputSupplier';
    protected $URL = '/api/v1/inputs/';
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new InputData();
        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );
        $this->data = json_decode($this->response->getContent(), true);
        $this->token = $this->data['token'];
    }
    // view Details
    public function testShouldGetInputDetails()
    {
        // a given user should view input
        $input = factory($this->modelPath)->create();

        $data = $input->_id;
        $URL = $this->URL . $data . '';
        $this->get($URL, ['Authorization' => $this->token]);
        $data = (array) json_decode($this->response->content());
        $this->seeJson(['success' => true]);
        $this->seeStatusCode(200);
        $this->assertArrayHasKey('result', $data);
    }
    public function testUnauthorizedWhenUserTriedToViewInputDetails()
    {
        // a given User should not view Input when he/she is not authenticated
        $input = InputModel::all()->last();
        $URL = $this->URL . $input->_id;
        $this->get($URL, []);
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
    }

    public function testShouldReturn404Error()
    {
        // a given user should not view input when it is not exist

        $URL = $this->URL . 'e4f9235e-7656-3c73-96fe-b058ac436cf7';
        $this->get($URL, ['Authorization' => $this->token]);
        $this->seeJson(['success' => false, 'error' => 'Input does not exist.']);
        $this->seeStatusCode(404);
    }

    public function testShouldNotUpdateInputWhenIdNotFound()
    {
        // given user should not update input
        // when input not exist
        $URL = $this->URL . 'e4f9235e';
        $this->put($URL, $this->mock->getInputsData(), ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
        $this->seeJson(['success' => false, 'error' => 'Input does not exist.']);
    }
    public function testShouldUpdateInput()
    {
        $input = InputModel::all()->last();
        $URL = $this->URL . $input->_id;
        $inputs = $this->mock->getInputsData();
        $this->put($URL, $inputs, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true, 'message' => 'Input has been successfully Edited.']);
    }

    public function testUnauthorizedWhenUserTriedToUpdateInput()
    {
        // given user should not update Input when he/she is not authenticated
        $input = InputModel::all()->last();
        $URL = $this->URL . $input->_id;
        $this->put($URL, []);
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
    }
    /**
     * should throw error when error occurs
     */
    public function testShouldThrowExceptionError()
    {
        // given user should not update input
        // when there a validationError
        $input = InputModel::all()->last();
        $data = $this->mock->getInputsData();
        $data['name;'] = 'yes';
        $URL = $this->URL . $input->_id;
        $this->put($URL, $data, ['Authorization' => $this->token]);
        $this->seeJson(['error' => 'Error occurred while updating inputs.']);
        $this->seeStatusCode(503);
    }

    //
    public function testShouldDeleteInput()
    {
        // given user should delete input
        $input = InputModel::all()->last();
        $URL = $this->URL . $input->_id;
        $this->delete($URL, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true, 'message' => 'Input has been successfully deleted.']);
    }

    public function testShouldReturn404ErrorOnDeleteRequest()
    {
        // given user should not delete input
        // when input does not exist

        $URL = $this->URL . 'e4f9235e-7656-3c73-96fe-b058ac436cf7';
        $this->delete($URL, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
        $this->seeJson(['success' => false, 'error' => 'Input does not exist.']);
    }
    /**
     * @group input
     */
    public function testShouldReturnInputs()
    {
        $this->get('/api/v1/input-list/', ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertArrayHasKey('result', $res_array);
    }
    /**
     * @group input
     */
    public function testShouldCreateInput()
    {
        $this->post(
            $this->URL,
            $this->mock->getInputsData(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(201);
    }
    /**
     * @group input
     */
    public function testShouldReturnErrorIfInputExist()
    {
        $validInput = $this->mock->getInputsData();
        $this->post(
            $this->URL,
            $validInput,
            ['Content-type' => 'multipart/form-data', 'Authorization' => $this->token]
        );
        $this->seeStatusCode(409);
        $value = InputSupplierHelpers::deleteInput($validInput['name']);
    }
    /**
     * @group input
     */
    public function testShouldCatchInvalidData()
    {
        $this->post(
            $this->URL,
            $this->mock->getInvalidInputData(),
            ['Content-type' => 'multipart/form-data', 'Authorization' => $this->token]
        );

        $this->seeStatusCode(422);
        $this->seeJson([
            'crops'  => ['The crops field is required.'],
            'name'       => ['The name format is invalid.'],
            'quantity'   => ['The quantity must be a number.']
        ]);
    }
}
