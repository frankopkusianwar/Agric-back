<?php namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Utils\Validation;
use App\Utils\Helpers;
use App\Models\CropInfo;

class AgronomicalController extends Controller
{
    const IMAGE_PATH = 'crop_info/';
    private $validate;
    private $request;
    public function __construct(Request $request)
    {
        $this->validate = new Validation();
        $this->request = $request;
    }

    /**
     * Retrieve a specific CropInfo document.
     * @param string $id
     * @return array
     */
    public static function getSpecificId($id)
    {
        return CropInfo::where('_id', '=', $id);
    }

    /** @suppress PhanNonClassMethodCall */
    public function imageUpload($request, $specificIdRetriever)
    {
        $requestBody = $request->all();
        if ($request->hasFile('photo')) {
            // @codeCoverageIgnoreStart
            $uploadedFile = $request->file('photo');
            $generatedImageUrl = Helpers::processImageUpload($uploadedFile, self::IMAGE_PATH);
            $requestBody['photo_url'] = $generatedImageUrl;
            unset($requestBody['photo']);
            $photoUrl = explode('/', $specificIdRetriever->first()->attributesToArray()['photo_url']);
            count($photoUrl) == 6 ? Helpers::imageActions($photoUrl[4] . '/'. $photoUrl[5], null, 'delete') : null;
            // @codeCoverageIgnoreEnd
        }
        return $requestBody;
    }

    /**
     * Update a specific CropInfo document.
     *
     * @param  \Illuminate\Http\Request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    /** @suppress PhanNonClassMethodCall */
    public function updateAgronomicalInfo(Request $request, $id)
    {
        try {
            $this->validate->validateAgronomicalInput($request);
            $specificIdRetriever = self::getSpecificId($id);
            $error = Helpers::returnError('Agronomical info doesnt exist.', 400);
            if (!$specificIdRetriever->first()) {
                return $error;
            }
            $requestBody = $this->imageUpload($request, $specificIdRetriever);
            $successfulUpdate = $specificIdRetriever->update($requestBody);
            return Helpers::returnSuccess(
                200,
                [ 'data' => $successfulUpdate ],
                'update successful'
            );
        } catch (ValidationException $e) {
            // @phan-suppress-next-line PhanUndeclaredProperty
            return Helpers::returnError($e->response->original, 422);
        } catch (\Exception $e) {
            return Helpers::returnError('An error occurred while updating agronomical info', 503);
        }
    }

    /**
     * Delete a specific CropInfo document.
     *
     * @param  \Illuminate\Http\Request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    /** @suppress PhanNonClassMethodCall */
    public function deleteAgronomicalInfo($id)
    {
        try {
            $specificIdRetriever = self::getSpecificId($id);
            $error = Helpers::returnError('Agronomical info doesnt exist.', 400);
            if (!$specificIdRetriever->first()) {
                return $error;
            }
            $specificIdRetriever->delete();
            return Helpers::returnSuccess(200, [], 'Agronomical info successfully deleted.');
        } catch (\Exception $e) {
            return Helpers::returnError('An error occurred while deleting agronomical info', 503);
        }
    }
}
