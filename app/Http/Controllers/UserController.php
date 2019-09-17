<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\VillageAgent;
use App\Utils\DateRequestFilter;
use App\Utils\Helpers;
use App\Utils\Queries;
use App\Utils\Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use PHPUnit\Framework\Constraint\Exception;

class UserController extends BaseController
{
    private $request;
    private $email;
    private $requestPassword;
    private $model;
    private $db;
    private $validate;
    private $helpers;
    private $queries;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validate = new Validation();
        $this->helpers = new Helpers();
        $this->queries = new Queries();
        $this->model = 'App\Models\\' . $this->request->user;
        $this->db = getenv('DB_DATABASE');
        $this->requestPassword = (string)$this->request->input('password');
        $this->email = (string)$this->request->input('email');
    }
    public function requestAccount()
    {
        try {
            $this->validate->validateAccountRequest($this->request);
            $password = Helpers::generateId();
            $user = $this->model::create($this->request->all()
                 + Helpers::requestInfo($password));
            if (!$user) {
                return Helpers::returnError("Could not create user.", 408);
            }
            $this->helpers->sendPassword($password, $this->request->email);
            Helpers::logActivity([
                'email' => $user->email,
                'target_account_name' => $user->account_name,
                'target_email' => $user->email,
            ], 'request a dev. partner account.');
            return Helpers::returnSuccess(201, [$this->request->user => $user], "");
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong.", 408);
        }
    }
    public function createUser()
    {
        $this->validate->validateNewAccount($this->request);
        try {
            $user = $this->model::create($this->request->all() + ['_id' => Helpers::generateId()]);
            if (!$user) {
                return Helpers::returnError("Could not create user.", 503);
            }
            $this->helpers->sendPassword($this->requestPassword, $this->email);
            return Helpers::returnSuccess(201, ['offtaker' => $user], "Please check your mail for your login password.");
        } catch (\Exception $e) {
            return Helpers::returnError("Something went wrong", 503);
        }
    }

    public function getAllActiveUsers()
    {
        $requestArray = DateRequestFilter::getRequestParam($this->request);
        list($start_date, $end_date) = $requestArray;
        try {
            $result = DB::select('SELECT * FROM ' . $this->db . ' WHERE type = "ma" OR type  = "offtaker" OR type="partner"');
            $filterUsers = ActivityLog::select('DISTINCT(email)')
                ->where('activity', '=', 'logged in')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->get()->count();
            return Helpers::returnSuccess(200, [
                'allUsersCount' => count($result),
                'activeUsersCount' => $filterUsers,
            ], "");
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }
    public function getActiveMobileUsers()
    {
        $requestArray = DateRequestFilter::getRequestParam($this->request);
        list($start_date, $end_date) = $requestArray;
        try {
            $userResult = DB::select('SELECT * FROM ' . $this->db . ' WHERE type = "account"');
            $countServiceResult = Queries::getActiveMobileUsers(
                $this->db, //@phan-suppress-current-line PhanPossiblyFalseTypeArgument
                $start_date,
                $end_date
            );
            return Helpers::returnSuccess(200, [
                'allMobileUsersCount' => count($userResult),
                'activeMobileUsersCount' => count($countServiceResult),
            ], "");
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }
    private function isInvalidVaData()
    {
        $this->validate->validateVillageAgentData($this->request);
        $phoneNumbers = $duplicatePhoneNumbers = [];
        // Check for duplicate phonenumbers and throw error if any found
        foreach ($this->request->villageAgents as $value) {
            array_push($phoneNumbers, $value['va_phonenumber']);
        }
        foreach (array_count_values($phoneNumbers) as $value => $count) {
            if ($count > 1) {
                $duplicatePhoneNumbers[] = $value;
            }
        }
        return count($duplicatePhoneNumbers) > 0;
    }
    private function getVaFromInput()
    {
        if ($this->isInvalidVaData()) {
            return false;
        }
        $villageAgents = [];
        // create new array from input and set id for each village agent
        foreach ($this->request->villageAgents as $value) {
            $value['vaId'] = Helpers::generateId();
            $value['type'] = 'va';
            $value['created_at'] = $value['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
            array_push($villageAgents, $value);
        }
        return $villageAgents;
    }

    /**
     * Add village agent
     * @return object \Illuminate\Http\JsonResponse
     */
    public function addVillageAgents()
    {
        try {
            $villageAgents = $this->getVaFromInput();
            if (!$villageAgents) {
                return Helpers::returnError("Duplicate phone numbers found in entry. Phone numbers must be unique.", 400);
            }
            return VillageAgent::insert($villageAgents) ?
            Helpers::returnSuccess(201, [], "Village agents added successfully.") : Helpers::returnError("Could not add village agents", 503);
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong", 503);
        }
    }

    /**
     * Get user by id
     * @param string $id
     * @return object \Illuminate\Http\JsonResponse
     */
    public function getUser($id)
    {
        try {
            $user = Queries::checkUser($id);
            if ($user) {
                return Helpers::returnSuccess(200, ['user' => $user], "");
            }
            return Helpers::returnError("User does not exist.", 404);
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }

    /**
     * Activate user account
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountAction()
    {
        if (!preg_match('/\b(suspend|activate)\b/', $this->request->action)) {
            return Helpers::returnError("Invalid parameters supplied.", 400);
        }
        $action = ($this->request->action === 'activate') ? 'active' : 'suspended';
        try {
            if (Queries::changeStatus($this->request->id, $action)) {
                $user = Queries::queryUser($this->request->id);
                unset($user[0][$this->db]['password']);
                $message = ($action === 'active') ? 'Account activated successfully.' : 'Account suspended successfully.';
                $name = Helpers::getName($user);
                Helpers::logActivity(
                    [
                        'email' => $this->request->admin->email,
                        'target_account_name' => $name,
                        'target_email' => $user[0][$this->db]['email'],
                    ],
                    str_replace(' successfully', '', $message)
                );
                return Helpers::returnSuccess(200, ['user' => $user[0][$this->db]], $message);
            } else {
                return Helpers::returnError("User not found.", 404);
            }
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }

    /**
     * Get all users
     * @return object \Illuminate\Http\JsonResponse
     */

    public function getUsers()
    {
        if (!preg_match('/\b(government|offtakers|village-agents|input-suppliers)\b/', $this->request->user)) {
            return Helpers::returnError("Invalid parameters supplied.", 400);
        }
        $model = [
            "offtakers" => "OffTaker",
            "village-agents" => "VillageAgent",
            "input-suppliers" => "InputSupplier",
            "government" => "Government",
        ];
        $model = 'App\Models\\' . $model[$this->request->user];
        $requestArray = DateRequestFilter::getRequestParam($this->request);
        list($start_date, $end_date) = $requestArray;
        $startDateCount = $model::where('created_at', '<=', $start_date)->get()->count();
        $endDateCount = $model::where('created_at', '<=', $end_date)->get()->count();
        $percentage = DateRequestFilter::getPercentage($startDateCount, $endDateCount);
        $result = $model::whereBetween('created_at', [$start_date, $end_date])->get();
        return Helpers::returnSuccess(200, [
            'count' => count($result),
            'result' => $result,
            'percentage' => $percentage,
        ], "");
    }
}
