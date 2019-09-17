<?php

use App\Utils\MockData;
use App\Utils\UserMockData;

class AdminTest extends TestCase
{
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $mock;
    protected $decodeUser;
    protected $data;
    protected $userMock;
    const URL = '/api/v1/admin';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->userMock = new UserMockData();
        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );

        $this->data = json_decode($this->response->getContent(), true);
        $this->token = $this->data['token'];
        $data2 = $this->call('POST', '/api/v1/auth/login', $this->userMock->getMasterAgentData());

        $decoded_data = json_decode($data2->getContent(), true);
        $this->wrongUser = $decoded_data['token'];
    }

    public function testShouldReturnErrorIfNoToken()
    {
        $this->post(self::URL, $this->userMock->getAdminData());
        $this->seeStatusCode(401);
        $this->seeJson(['error' => 'Please log in first.']);
    }

    public function testShouldReturnErrorIfInvalidToken()
    {
        $this->post(
            self::URL,
            ['Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.
            eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.
            jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs']
        );
        $this->seeStatusCode(401);
    }

    public function testShouldReturnUserIfTokenIsValid()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }
    public function testShouldReturnValidationError()
    {
        $this->post(
            self::URL,
            [$this->userMock->getNewAdmin()],
            ['Authorization' => $this->token]
        );
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(422);
        $this->seeJson(["email" => ["The email field is required."]]);
    }
    public function testShouldReturnErrorIfPasswordMismatch()
    {
        $this->post(
            self::URL,
            $this->mock->getPasswordMismatchData(),
            ['Authorization' => $this->token]
        );
        $this->seeJson(['error' => 'Passwords do not match.']);
    }
    public function testShouldNotActivateAccountIfWrongId()
    {
        $this->patch('/api/v1/activate/1', [], ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
        $this->seeJson(['error' => 'User not found.']);
    }

    public function testShouldReturnInvalidParams()
    {
        $this->patch('/api/v1/fake_action/1', [], ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->seeJson(['error' => 'Invalid parameters supplied.']);
    }

    public function testShouldActivateAccount()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = $response->admin->_id;
        $this->patch('/api/v1/activate/' . $id, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['message' => 'Account activated successfully.']);
    }

    public function testShouldNotSuspendAccountIfWrongId()
    {
        $this->patch('/api/v1/suspend/1', [], ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
        $this->seeJson(['error' => 'User not found.']);
    }

    public function testShouldSuspendAccount()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = $response->admin->_id;
        $this->patch('/api/v1/suspend/' . $id, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['message' => 'Account suspended successfully.']);
    }

    public function testShouldReturnErrorIfUserIsNotAdmin()
    {
        $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->wrongUser]
        );
        $this->seeStatusCode(403);
    }
    public function testShouldReturnActivitySummary()
    {
        $this->get('/api/v1/activity-summary', ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }
    public function testShouldReturnActivitySummaryByDate()
    {
        $this->get('/api/v1/activity-summary/?start_date=2018-12-12&end_date=2019-12-12', ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('activities', $res_array);
    }

    public function testShouldReturnAllAdmins()
    {
        $this->get('/api/v1/admins', ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnChangeAdminPasswordSuccessful()
    {
        $this->post(
            '/api/v1/change-password',
            $this->mock->getChangePassword(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(200);
        $this->seeJson([
            'success' => true,
            'message' => 'Your Password has been changed successfully.']);
    }
    public function testShouldReturnChangeAdminPasswordDoesNotMatch()
    {
        $this->post(
            '/api/v1/change-password',
            $this->mock->getWrongChangePassword(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(400);
        $this->seeJson([
            'success' => false,
            'error' => 'Current password is incorrect.']);
    }

    public function testShouldReturnChangeAdminPasswordCatchError()
    {
        $this->post('/api/v1/change-password', [$this->mock->getWrongEmail()], ['Authorization' => $this->token]);
        $this->seeJson([
            'error' => 'Something went wrong.']);
    }
    public function testShouldGetUser()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = $response->admin->_id;
        $this->get('/api/v1/account/' . $id, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }

    public function testShouldNotGetUser()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = 'fake-id';
        $this->get('/api/v1/account/' . $id, ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
        $this->seeJson(['error' => 'User does not exist.']);
    }

    public function testShouldUpdateAndDeleteUsers()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = $response->admin->_id;
        $email = $response->admin->email;
        $this->patch('/api/v1/account/'.$id, ['email' => $email], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['message' => 'Account updated successfully.']);
        $this->delete('/api/v1/account/' . $id, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
    }

    public function testShouldReturnErrorIfUserIsNotFound()
    {
        $this->delete('/api/v1/account/345678', [], ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
    }

    public function testShouldReturnErrorOnUpdateAccount()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = $response->admin->_id;
        $this->patch('/api/v1/account/'.$id, ['email' => 'text'], ['Authorization' => $this->token]);
        $this->seeStatusCode(422);
    }

    public function testShouldReturnErrorIfUpdateEmailIsTaken()
    {
        $response = $this->post(
            self::URL,
            $this->userMock->getNewAdmin(),
            ['Authorization' => $this->token]
        )->response->getData();
        $id = $response->admin->_id;
        $this->patch('/api/v1/account/'.$id, ['email' => 'admin2020@gmail.com'], ['Authorization' => $this->token]);
        $this->seeStatusCode(422);
        $this->delete('/api/v1/account/' . $id, [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
    }

    public function testShouldNotReturnUser()
    {
        $this->get('/api/v1/users/fakes', ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->seeJson(['error' => 'Invalid parameters supplied.']);
    }

    public function testShouldNotReturnTopPerformingAgents()
    {
        $this->get('/api/v1/top-performing/vaa', ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(400);
        $this->seeJson(['error' => 'Invalid parameter supplied.']);
    }

    public function testShouldNotReturnTopPerformingMasterAgents()
    {
        $this->get('/api/v1/top-performing/ma', ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }

    public function testShouldNotReturnTopPerformingVillageAgents()
    {
        $this->get('/api/v1/top-performing/va', ['Authorization' => $this->token]);
        $res_array = (array)json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnErrorIfInvalidAdminRole()
    {
        $this->post(
            self::URL,
            $this->userMock->getWrongAdminRole(),
            ['Authorization' => $this->token]
        );
        $this->seeStatusCode(422);
    }
}
