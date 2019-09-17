<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\Helpers;
use App\Utils\Validation;
use App\Models\InputSupplier;

/**
 * @phan-file-suppress PhanPartialTypeMismatchArgument
 */
class InputController extends Controller
{
    const IMAGE_PATH = 'inputs/';
    protected $request;
    protected $validate;
    public function __construct(Request $request)
    {
        $this->validate = new Validation();
        $this->request = $request;
    }
    /**
     * View single Input
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInputDetails($id)
    {
        try {
            $input = Helpers::checkInput($id)->first();
            return $input ? Helpers::returnSuccess(200, [
                'result' => $input
            ], "") : Helpers::returnError("Input does not exist.", 404);
        } catch (\Exception $e) {
            return Helpers::returnError("Error occured please try again later.", 503);
        }
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInput($id)
    {
        $this->validate->validateInputData($this->request);
        try {
            $newInputInfo = $this->request->all();
            $input = Helpers::checkInput($id);
            if (!$input->first()) {
                return Helpers::returnError("Input does not exist.", 404);
            }
            // upload image
            if ($this->request->hasFile('photo')) {
                // @codeCoverageIgnoreStart
                $uploadedFile = $this->request->file('photo');
                $newImageUrl = Helpers::processImageUpload($uploadedFile, self::IMAGE_PATH);
                $newInputInfo['photo_url'] = $newImageUrl;
                // @codeCoverageIgnoreEnd
            }
            $newInputInfo['price'] = Helpers::stringToArray(',', $this->request->input('price'));
            $newInputInfo['crops'] = Helpers::stringToArray(',', $this->request->input('crops'));
            $newInputInfo['unit'] = Helpers::stringToArray(',', $this->request->input('unit'));

            // update input
            $input->update($newInputInfo);
            return Helpers::returnSuccess(200, [
                'message' => 'Input has been successfully Edited.'
            ], "");
        } catch (\Exception $e) {
            return Helpers::returnError("Error occurred while updating inputs.", 503);
        }
    }

    /**
     * delete Input
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInput($id)
    {
        try {
            $input = Helpers::checkInput($id);
            if (!$input->first()) {
                return Helpers::returnError("Input does not exist.", 404);
            }
            $input->delete();
            return Helpers::returnSuccess(200, [
                'message' => 'Input has been successfully deleted.',
            ], "");
        } catch (\Exception $e) {
            return Helpers::returnError("Error occurred while removing input, please try again later.", 503);
        }
    }
    /**
     * Get all Inputs
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInputs()
    {
        try {
            $result = InputSupplier::all();
            return Helpers::returnSuccess(200, ['success' => true, 'count' => count($result), 'result' => $result], "");
        } catch (\Exception $e) {
            return Helpers::returnError("Could not get inputs.", 503);
        }
    }
    /**
     * Create an input
     * @return \Illuminate\Http\JsonResponse
     */
    public function createInput()
    {
        $this->validate->validateInputData($this->request);
        try {
            $newInputInfo = $this->request->all();
            if ($this->request->file('photo')) {
                // @codeCoverageIgnoreStart
                $uploadedFile = $this->request->file('photo');
                $newImageUrl = Helpers::processImageUpload($uploadedFile, self::IMAGE_PATH);
                $newInputInfo['photo_url'] = $newImageUrl;
                // @codeCoverageIgnoreEnd
            }
            $newInputInfo['price'] = Helpers::stringToArray(',', $this->request->input('price'));
            $newInputInfo['crops'] = Helpers::stringToArray(',', $this->request->input('crops'));
            $newInputInfo['unit'] = Helpers::stringToArray(',', $this->request->input('unit'));
            $input = InputSupplier::firstOrCreate(['name' => $newInputInfo['name'], 'category' => $newInputInfo['category'], 'supplier' => $newInputInfo['supplier']], $newInputInfo + ['_id' => Helpers::generateId()]);
            if (!$input->wasRecentlyCreated) {
                return Helpers::returnError("This input already exist", 409);
            }
            $userInfo = [
                'email' => $this->request->admin->email,
                'target_account_name' => $this->request->admin->firstname . ' ' . $this->request->admin->lastname,
                'target_email' => $this->request->admin->email
            ];
            Helpers::logActivity($userInfo, 'User created an input');

            return Helpers::returnSuccess(201, ['input' => $input], "");
        } catch (\Exception $e) {
            return Helpers::returnError("Failed to create input.", 503);
        }
    }
}
