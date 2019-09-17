<?php

use App\Utils\MockData;
use App\Utils\Helpers;
use App\Http\Controllers\ActivityController;

class ActivityLogTest extends TestCase
{
    protected $response;
    protected $token;
    protected $wrongUser;
    protected $mock;
    protected $activityLog;
    protected $data;
    const URL = '/api/v1/activity-log/?limit=17&offset=5';
    const ACTIVITYLOG_URL = '/api/v1/active-users';
    const MOBILE_ACTIVITYLOG_URL = '/api/v1/active-mobile-users';

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->response = $this->call(
            'POST',
            '/api/v1/auth/login',
            $this->mock->getLoginDetails()
        );

        $this->data = json_decode($this->response->getContent(), true);
        $this->token = $this->data['token'];
    }

    public function testShouldReturnActivityLogs()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());

        $this->seeStatusCode(200);
        $this->assertArrayHasKey('activityLog', $res_array);
    }

    public function testShouldCreateNewActivityLog()
    {
        $email = 'fake-admin-email@fakeemail.com';
        $target_email = 'fake-user@fakemail.com';
        $target_account_name = 'samuel';
        $activity = 'request a dev. partner account.';

        $activityLog = Helpers::logActivity([
            'email' => $email,
            'target_account_name' => $target_account_name,
            'target_email' => $target_email,
        ], $activity);

        $res_array = (array) json_decode($activityLog);
        $this->assertEquals($email, $activityLog->email);
        $this->assertEquals($activity, $activityLog->activity);
        $this->assertArrayHasKey('target_account_name', $res_array);
    }

    public function testShouldReturnActiveUsers()
    {
        $this->get(self::ACTIVITYLOG_URL, ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('allUsersCount', $res_array);
        $this->assertArrayHasKey('activeUsersCount', $res_array);
    }

    public function testShouldReturnMobileActiveUsers()
    {
        $this->get(self::MOBILE_ACTIVITYLOG_URL, ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('allMobileUsersCount', $res_array);
        $this->assertArrayHasKey('activeMobileUsersCount', $res_array);
    }

    public function testShouldReturnMobileActiveUsersWithDates()
    {
        $start_date = '2019-08-13';
        $end_date   = '2019-08-18';
        $this->get(
            self::MOBILE_ACTIVITYLOG_URL . "?start_date=" . $start_date . "&end_date=" . $end_date,
            ['Authorization' => $this->token]
        );
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
        $this->assertArrayHasKey('allMobileUsersCount', $res_array);
        $this->assertArrayHasKey('activeMobileUsersCount', $res_array);
    }
}
