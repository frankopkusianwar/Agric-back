<?php

namespace App\Http\Controllers;

use App\Models\MasterAgent;
use App\Models\DevtPartner;
use App\Rules\ValidateInputFields;
use App\Utils\DateRequestFilter;
use App\Utils\Helpers;
use App\Utils\Queries;
use App\Utils\Validation;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

// @phan-file-suppress PhanPartialTypeMismatchArgument
//
class AdminExtendedController extends BaseController
{
    private $request;
    private $validate;
    private $db;
    private $validateInput;
    private $helpers;

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
        $this->db = getenv('DB_DATABASE');
        $this->helpers = new Helpers();
        $this->validateInput = new ValidateInputFields($this->request->all());
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount($id)
    {
        try {
            $user = Queries::checkUser($id);
            if (!$user) {
                return Helpers::returnError("User does not exist.", 404);
            }
            if (Queries::deleteUser($id)) {
                Helpers::logActivity([
            'email' => $this->request->admin->email,
            'target_account_name' => $user['firstname'] . ' '. $user['lastname'],
            'target_email' => $user['email'],
        ], 'Account deleted');
                return Helpers::returnSuccess(200, [], "Account deleted successfully.");
            } else {
                return Helpers::returnError("Account could not be deleted.", 503);
            }
        } catch (Exception $e) {
            return  Helpers::returnError("Something went wrong.", 503);
        }
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function editAccount($id)
    {
        $user = Queries::checkUser($id);
        if (!$user) {
            return Helpers::returnError("User does not exist.", 404);
        }

        $isEmpty = $this->validateInput->isEmpty();
        if ($isEmpty) {
            return Helpers::returnError($isEmpty, 422);
        }
        $this->validate->validateExistingAccount($this->request, $id);

        if (Queries::editAccount($id, $this->request->all())) {
            $updatedUser = Queries::queryUser($id);
            unset($updatedUser[0][$this->db]['password']);
            Helpers::logActivity([
                'email' => $this->request->admin->email,
                'target_account_name' => $updatedUser[0][$this->db]['type'] == 'admin'?
                $updatedUser[0][$this->db]['firstname']. '' .$updatedUser[0][$this->db]['lastname']:
                $updatedUser[0][$this->db]['account_name'],
                'target_email' => $updatedUser[0][$this->db]['email']
            ], 'account updated');
            
            return Helpers::returnSuccess(200, ["user" => $updatedUser[0][$this->db]], "Account updated successfully.");
        } else {
            return Helpers::returnError("Could not edit account", 503);
        }
    }
    /**
     * check and modify village agents object
     *
     * @return void
     */
    public function editVillageAgents($result)
    {
        if (($this->request->user) === "village-agents") {
            $updatedResult = $result->map(function ($agent) {
                $agent->master_agent = (MasterAgent::where('_id', $agent->ma_id)->first())??'NA';
                $agent->devt_partner = (DevtPartner::where('_id', $agent->partner_id)->first())??'NA';
                return $agent;
            });
            $result = $updatedResult;
            return $result;
        }
    }

    /**
     * Get all users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        if (!preg_match('/\b(government|offtakers|village-agents|input-suppliers)\b/', $this->request->user)) {
            return Helpers::returnError("Invalid parameters supplied.", 400);
        }
        $this->validate->validateLimitAndOffset($this->request);
        $limit = $this->request->input('limit');
        $model = [
      "offtakers" => "OffTaker",
      "village-agents" => "VillageAgent",
      "input-suppliers" => "InputSupplier",
      "government" => "Government"
    ];
        $model = 'App\Models\\' . $model[$this->request->user];

        $requestArray = DateRequestFilter::getRequestParam($this->request);
        list($start_date, $end_date) = $requestArray;
        $startDateCount = $model::where('created_at', '<=', $start_date)->get()->count();
        $endDateCount = $model::where('created_at', '<=', $end_date)->get()->count();
        $percentage = DateRequestFilter::getPercentage($startDateCount, $endDateCount);

        $result = $model::whereBetween('created_at', [$start_date, $end_date])->paginate($limit);
        $this->editVillageAgents($result);
        return Helpers::returnSuccess(200, [
      'count' => $result->total(),
      'result' => $result->items(),
      'percentage' => $percentage
    ], "");
    }
}
