<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Email;
use App\Utils\Validation;

class ContactController extends Controller
{
    /**
    * The request instance.
    *
    * @var \Illuminate\Http\Request
    */
    private $request;
    private $email;
    private $validate;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validate = new Validation();
        $this->email = new Email();
    }

    /**
     * send message through contact form
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendContactForm()
    {
        $this->validate->validateContactForm($this->request);
        $recieverEmail = 'info@akorion.com';
        $response = $this->email->sendMail(
            $recieverEmail,
            'Inquiry from '.$this->request->input('name'),
            "<h3 stye='font-size: 32px;'> Name: " . $this->request->input('name') . '<br/>'.
            "Email: " . $this->request->input('email') . '<h3/>'.
            "<p stye='font-size: 24px;'> ". $this->request->input('message')."</p>"
        );

        return response()->json([
            'success' => $response,
            'message' => $response ? 'Thanks for contacting us, we would get back to you, shortly.' : 'We could not send your message, due to server error. please, try again later.',
        ], $response ? 200 : 500);
    }
}
