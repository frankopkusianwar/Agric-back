<?php

class ContactTest extends TestCase
{
    const URL = '/api/v1/contact';

    public function testShouldReturnSendContactForm()
    {
        $this->post(self::URL, [
            'email' => 'fake@mail.com',
            'name' => 'fake name',
            'message' => 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. meaning that "There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain..."'
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            "message" => "Thanks for contacting us, we would get back to you, shortly.",
            "success" => true,
        ]);
    }
}
