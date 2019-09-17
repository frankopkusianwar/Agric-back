<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Validation\ValidationException;
use App\Utils\Helpers;
use App\Utils\Validation;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    private $validate;
    const IMAGE_PATH = 'diagnosis/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validate = new Validation();
    }

    /**
     * Get Diagnosis information for Diseases/Pests
     *
     * @param string $category - pest / disease
     * @param string $id - id of the diagnosis
     * @return \Illuminate\Http\JsonResponse response
     */
    public function getDiagnosis($category, $id = null)
    {
        try {
            $category = ucfirst($category);

            $diagnosisInformationQuery = Diagnosis::query()->select()->where('category', $category)
                ->orderBy('updated_at', 'DESC');

            $diagnosisInformationQuery->when($id, function ($diagnosis) use ($id) {
                return $diagnosis->where('_id', $id);
            });

            $diagnosisInformation = $diagnosisInformationQuery->get()->toArray();
            return Helpers::returnSuccess(200, ['data' => $diagnosisInformation], '');
        } catch (Exception $e) {
            return Helpers::returnError("Error retrieving diagnosis.", 503);
        }
    }

    /**
     * Delete diagnosis information
     *
     * @param string $id - id of the diagnosis information
     * @return \Illuminate\Http\JsonResponse response
     */
    public function deleteDiagnosis($id)
    {
        try {
            $singleDiagnosisInformation = Diagnosis::query()
              ->select()
              ->where('_id', $id)->first();

            $photoUrl = explode('/', $singleDiagnosisInformation->attributesToArray()['photo_url']);
            count($photoUrl) == 6 ? Helpers::imageActions($photoUrl[4] . '/'. $photoUrl[5], null, 'delete') : null;

            Diagnosis::query()->select()->where('_id', $id)->delete();
            return Helpers::returnSuccess('200', ['data' => null], '');
        } catch (Exception $e) {
            return Helpers::returnError("Error deleting diagnosis information.", 503);
        }
    }

    /**
     * edit diagnosis information
     *
     * @param Request  $request
     * @param $id - id of the diagnosis information
     * @return \Illuminate\Http\JsonResponse response
     */
    public function editDiagnosisInformation(Request $request, $id)
    {
        try {
            $this->validate->validateDiagnosisInformation($request);

            try {
                $newDiagnosisInformation = $request->all();
                $diagnosisInformation = Diagnosis::query()->select()->where('_id', $id)->first();
                if ($request->hasFile('photo')) {
                    // @codeCoverageIgnoreStart
                    $uploadedFile = $request->file('photo');
                    $newImageUrl = Helpers::processImageUpload($uploadedFile, self::IMAGE_PATH);
                    $newDiagnosisInformation['photo_url'] = $newImageUrl;
                    unset($newDiagnosisInformation['photo']);
                    $photoUrl = explode('/', $diagnosisInformation->attributesToArray()['photo_url']);
                    count($photoUrl) == 6 ? Helpers::imageActions($photoUrl[4] . '/'. $photoUrl[5], null, 'delete') : null;
                    // @codeCoverageIgnoreEnd
                }
                $updatedDiagnosis = Diagnosis::query()->where('_id', $id)->update($newDiagnosisInformation);
                return Helpers::returnSuccess(200, ['data' => $updatedDiagnosis], '');
            } catch (Exception $e) {
                return Helpers::returnError("Error editing diagnosis information", 503);
            }
        } catch (ValidationException $validationError) {
            // @phan-suppress-next-line PhanUndeclaredProperty
            return Helpers::returnError($validationError->response->original, 422);
        }
    }

    /**
     * create diagnosis information
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse response
     */
    public function createDiagnosis(Request $request)
    {
        try {
            $this->validate->validateDiagnosisInformation($request);
            $diagnosisInfo = $request->all();
            $diagnosisInfo['category'] = ucfirst($request->category);
            $count = Diagnosis::where('name', $diagnosisInfo['name'])->where('category', $diagnosisInfo['category'])->count();

            if ($count > 0) {
                return Helpers::returnError("The ". $diagnosisInfo['category'] ." name is already taken", 409);
            }

            if ($request->hasFile('photo')) {
                // @codeCoverageIgnoreStart
                $uploadedFile = $request->file('photo');
                $imgUrl = Helpers::processImageUpload($uploadedFile, self::IMAGE_PATH);
                $diagnosisInfo['photo_url'] = $imgUrl;
                // @codeCoverageIgnoreEnd
            }
            Diagnosis::create($diagnosisInfo + ['_id' => Helpers::generateId()]);
            $response = Diagnosis::latest()->first();
            return Helpers::returnSuccess(201, [
                'diagnosis' => $response
            ], "Diagnosis added successfully", );
        } catch (ValidationException $e) {
            // @phan-suppress-next-line PhanUndeclaredProperty
            return Helpers::returnError($e->response->original, 422);
        } catch (Exception $e) {
            return Helpers::returnError("Could not create diagnosis information", 503);
        }
    }
}
