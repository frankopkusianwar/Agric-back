<?php
use App\Utils\Email;
use App\Utils\MockData;

class EmailTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new MockData();
        $this->mailer = new Email();
        $this->url = getenv('FRONTEND_URL');

        $this->subject = 'random email subject';
        $this->body = 'some random email body';
    }

    public function testShouldReturnAnErrorIfEmailDoesNotSent()
    {
        $sentMail = $this->mailer->sendMail('fakemail', $this->subject, $this->body);
        $this->assertIsBool($sentMail);
        $this->assertFalse($sentMail);
    }

    public function testShouldReturnSuccessIfEmailSent()
    {
        $sentMail = $this->mailer->sendMail($this->mock->getLoginDetails()['email'], $this->subject, $this->body);
        $this->assertIsBool($sentMail);
        $this->assertTrue($sentMail);
    }

    public function testShouldReturnAnErrorForResetPassword()
    {
        $sentMail = $this->mailer->mailWithTemplate('fakemail', '/hjbsfj', 'RESET_PASSWORD', 'some-random-token');
        $this->assertIsBool($sentMail);
        $this->assertFalse($sentMail);
    }
}
