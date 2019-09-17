<?php
namespace App\Http\Controllers;

use App\Models\RequestPassword;
use App\Utils\Email;
use App\Utils\Helpers;
use App\Utils\Validation;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

// @phan-file-suppress PhanPartialTypeMismatchArgument

class AuthController extends BaseController
{/**
 * The request instance.
 *
 * @var \Illuminate\Http\Request
 */
    private $request;
    private $email;
    private $validate;
    private $db;

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
        $this->db = getenv('DB_DATABASE');
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @return mixed
     */

    public function authenticate()
    {
        try {
            $this->validate->validateLogin($this->request);

            $user = DB::select('SELECT * FROM ' . $this->db . ' WHERE email = ? AND (type = "ma" OR type  = "offtaker" OR type = "admin" OR type="partner")', [$this->request->input('email')]);
            if (!$user) {
                return response()->json([
                    'success' => false, 'error' => 'The Email or password supplied is incorrect.'], 404);
            }
            // Verify the password and generate the token
            if (Hash::check($this->request->input('password'), $user[0][$this->db]['password'])) {
                $token = Helpers::jwt($user, $this->db);
                unset($user[0][$this->db]['password']);
                $userInfo = [
                    'email' => $user[0][$this->db]['email'],
                    'target_account_name' => $user[0][$this->db]['firstname'],
                    'target_email' => $user[0][$this->db]['email'],
                ];
                $user ? Helpers::logActivity($userInfo, 'logged in') : [];
                return response()->json(['success' => true, 'token' => $token, 'user' => $user[0][$this->db],
                ], 200);
            }
            return response()->json(['success' => false, 'error' => 'The Email or password supplied is incorrect.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.']);
        }
    }

    /**
     * Check if user credencial exists, if it does, send an email
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function forgotPassword()
    {
        $this->validate->validateForgotPassword($this->request);
        try {
            $user = DB::select('select * from ' . $this->db . ' WHERE email = ? AND (type = "ma" OR type  = "offtaker" OR type = "admin" OR type="partner")', [$this->request->input('email')]);
            if ($user) {
                $token = Helpers::jwt(['_id' => $user[0][$this->db]['_id'], 'email' => $user[0][$this->db]['email']]);
                RequestPassword::create(['_id' => Helpers::generateId(), 'token' => $token]);

                $this->email->mailWithTemplate(
                    $this->request->input('email'),
                    getenv('FRONTEND_URL') . "/confirm-password?token=$token",
                    'RESET_PASSWORD'
                );

                return response()->json([
                    'success' => true,
                    'message' => 'An email with password reset instructions has been sent to your email address. It would expire in 1 hour.'], 200);
            }
            return response()->json(['error' => true,
                'message' => 'We could not find this email in our database.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.']);
        }
    }

    /**
     * Check if user credencial exists, if it does, send an email
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function confirmPassword()
    {
        $this->validate->validateConfirmPassword($this->request);
        try {
            $token = ($this->request->input('token'));
            $decodedToken = JWT::decode($token, env('JWT_SECRET'), array('HS256'));

            $requestPasswordDocument = RequestPassword::where('token', $token)->first();

            if ($requestPasswordDocument) {
                if ($requestPasswordDocument->token === $token) {
                    DB::select('UPDATE ' . $this->db . ' SET `password`=? WHERE email=?', [Hash::make($this->request->input('password')), $decodedToken->sub->email]);
                    $requestPasswordDocument->delete();
                    return response()->json(['success' => true,
                        'message' => 'Your Password has been updated, successfully'], 200);
                }
            }
            return response()->json(['success' => false,
                'message' => 'We could not update your password'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.']);
        }
    }
    /**
     * Check if token credencial exists,
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function verifyResetPasswordToken()
    {
        $this->validate->validateVerifyPasswordToken($this->request);
        try {
            $token = ($this->request->input('token'));
            $type = 'request-password';

            $user = DB::select("select * from " . $this->db . " where token= ? AND  type = ?", [$token, $type]);

            if ($user) {
                return Helpers::returnSuccess(200, [], "verified");
            }

            return Helpers::returnError("Unauthorized.", 401);
        } catch (\Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }
}
