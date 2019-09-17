<?php
namespace App\Utils;

use App\Models\ActivityLog;
use App\Models\Diagnosis;
use App\Models\InputSupplier as Input;
use App\Models\MasterAgent;
use App\Services\GoogleStorage;
use App\Utils\Email;
use Crisu83\ShortId\ShortId;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

/** @phan-file-suppress PhanPossiblyFalseTypeArgumentInternal, PhanPossiblyFalseTypeMismatchProperty, PhanPossiblyNonClassMethodCall, PhanPossiblyNullTypeArgumentInternal, PhanPossiblyNonClassMethodCall, PhanPossiblyNullTypeArgument, PhanPartialTypeMismatchReturn, PhanPartialTypeMismatchArgument */
class Helpers extends BaseController
{
    /** @var string $url */
    private static $url;
    private static $mail;
    private static $db;
    private static $masterAgent;
    public static $user;
    public function __construct()
    {
        self::$mail = new Email();
        self::$url = getenv('FRONTEND_URL');
        self::$db = getenv('DB_DATABASE');
        self::$masterAgent = new MasterAgent();
    }
    /**
     * Create a new token for user object.
     *
     * @param array   $user
     * @param $db
     * @return string
     */
    public static function jwt($user, $db = null, $exp = (60 * 60 * 24 * 7))
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $db !== null ? $user[0][$db]['_id'] : $user, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + $exp, // Expiration time
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }
    /**
     * Generate Random Numbers
     * @return string
     */
    public static function generateId()
    {
        $shortid = ShortId::create();
        return $shortid->generate();
    }
    public static function requestInfo($password)
    {
        return [
            '_id' => self::generateId(),
            'district' => 'N/A',
            'value_chain' => 'N/A',
            'account_type' => 'Generic',
            'contact_person' => 'N/A',
            'password' => $password,
        ];
    }
    /**
     * Send user password via mail
     * @param string $password
     * @param string $email
     * @return boolean true or false
     */
    public function sendPassword($password, $email)
    {
        $sendEmail = self::$mail->mailWithTemplate(
            $email,
            self::$url,
            'LOGIN',
            $password
        );
        return ($sendEmail) ? true : false;
    }
    public static function logActivity($info, $activity, $type = 'admin')
    {
        return ActivityLog::create([
            'email' => $info['email'],
            'target_email' => $info['target_email'],
            'target_account_name' => $info['target_account_name'],
            'activity' => $activity,
            'type' => $type,
        ]);
    }
    public static function returnError($errorMessage, $statusCode)
    {
        return response()->json([
            "success" => false,
            "error" => $errorMessage,
        ], $statusCode);
    }
    public static function returnSuccess($statusCode, $data = [], $successMessage = null)
    {
        $result = array_merge(array_filter(["success" => true, "message" => $successMessage]), $data);
        return response()->json($result, $statusCode);
    }
    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|string|array|null $file - uploaded file object
     * @param string $imagePath - path for the uploaded image
     * @return string - url of the uploaded image
     */
    public static function processImageUpload($file, $imagePath)
    {
        $originalPhoto = explode('.', $file->getClientOriginalName());
        $imageName = $imagePath . $originalPhoto[0] . '_' . time() . '.' . $file->getClientOriginalExtension();
        $newImageUrl = self::imageActions($imageName, $file, 'upload');
        return $newImageUrl;
    }
    /**
     * Checks if a document with supplied ID exist
     *
     * @param $id - id of the document to check for
     * @return boolean true/false
     */
    public static function documentExist($id)
    {
        $document = DB::select('SELECT * FROM ' . self::$db . ' WHERE _id="' . $id . '"');
        $doesExist = $document ? true : false;
        return $doesExist;
    }
    /**
     * Google Storage image actions
     *
     * @param string $imageName
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|string|null $imageFile
     * @param string $action
     * @return string|void $imageURL
     */
    public static function imageActions($imageName, $imageFile = null, $action = 'upload')
    {
        switch ($action) {
            case 'upload':
                return GoogleStorage::uploadImage($imageName, $imageFile);
            case 'delete':
                GoogleStorage::deleteImage($imageName);
                break;
            default:
                break;
        }
    }
    /**
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function checkInput($id)
    {
        $input = Input::where('_id', '=', $id);
        return $input ?: false; // @phan-suppress-current-line PhanTypeMismatchReturn
    }

    /**
     * Loops through an object's attributes setting the fields specified in an
     * array to uppercase first.
     *
     * @param object $instance
     * @param array $fields
     *
     * @return boolean
     */
    public static function mutateAttributes($instance, $fields)
    {
        $attributes = $instance->getAttributes();
        unset($attributes['password']);

        foreach ($attributes as $attributeKey => $attributeValue) {
            if (in_array($attributeKey, $fields, true)) {
                $attributeValue = Helpers::stringToUcFirst($attributeValue);
                $instance->setAttribute($attributeKey, $attributeValue);
            } else {
                $instance->setAttribute($attributeKey, $attributeValue);
            }
        }

        return true;
    }

    /**
     * transforms each word in a string containing space
     * separate words to uppercase first
     *
     * @param string $multipleWords
     *
     * @return string
     */
    private static function stringToUcFirst($multipleWords)
    {
        $temp = explode(" ", $multipleWords);
        foreach ($temp as $key => $value) {
            $temp[$key] = ucfirst($value);
        }
        $multipleWords = implode(" ", $temp);
        return $multipleWords;
    }
    /**
     * @param string $splitType
     * @param string $value
     */
    public static function stringToArray(String $splitType, String $value)
    {
        $result = explode($splitType, $value);
        return $result;
    }

    /**
     * @param array $user
     * @return string user credential
     */

    public static function getName($user)
    {
        return ($user[0][self::$db]['type'] === 'admin') ?
        $user[0][self::$db]['firstname'] . ' ' . $user[0][self::$db]['lastname'] :
        $user[0][self::$db]['account_name'];
    }

    /**
     * delete diagnosis by name for testing purpose
     * @param string $name
     * @return void
     */
    public static function deleteDiagnosis($name)
    {
        Diagnosis::where('type', 'diagnosis')->where('name', $name)->delete();
    }
}
