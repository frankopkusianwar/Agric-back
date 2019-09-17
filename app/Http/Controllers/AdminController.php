<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Rules\ValidateInputFields;
use App\Utils\DateRequestFilter;
use App\Utils\Helpers;
use App\Utils\Queries;
use App\Utils\Validation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

// @phan-file-suppress PhanPartialTypeMismatchArgument
class AdminController extends BaseController
{
    private $request;
    private $validate;
    private $db;
    private $validateInput;
    private $helpers;
    private $queries;
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
        $this->queries = new Queries();
        $this->validateInput = new ValidateInputFields($this->request->all());
    }
    /**
     * Get all admins
     * @return object \Illuminate\Http\JsonResponse
     */
    public function getAdmins()
    {
        try {
            $result = Admin::all();
            if ($result) {
                return Helpers::returnSuccess(200, [
                    'count' => count($result),
                    'result' => $result,
                ], "");
            } else {
                return Helpers::returnError("Could not get admins.", 503);
            }
        } catch (\Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }
    /**
     * Create Admin Account
     * @return object \Illuminate\Http\JsonResponse
     */
    public function createAdmin()
    {
        $this->validate->validateAdmin($this->request);
        try {
            if ($this->request->password === $this->request->confirmPassword) {
                $data = Admin::create($this->request->all() + ['_id' => Helpers::generateId()]);
                $userInfo = [
                    'email' => $this->request->admin->email,
                    'target_account_name' => $data->firstname . ' ' . $data->lastname,
                    'target_email' => $data->email,
                ];
                $data ? Helpers::logActivity($userInfo, 'admin account created') : null;
                return $data ?
                Helpers::returnSuccess(200, ['admin' => $data], "") : Helpers::returnError("Could not create user.", 408);
            }
            return Helpers::returnError("Passwords do not match.", 401);
        } catch (Exception $err) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }


    /**
     * Check if user credencial exists, if it does, send an email
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword()
    {
        try {
            $this->validate->validateAdminChangePassword($this->request);
            $user = DB::select('select * from ' . $this->db . ' where _id = ?', [$this->request->auth]);
            if (Hash::check($this->request->input('oldPassword'), $user[0][$this->db]['password'])) {
                Admin::where('_id', $this->request->auth)->update(['password' => Hash::make($this->request->input('newPassword'))]);
                Helpers::logActivity([
                    'target_account_name' => $user[0][$this->db]['firstname'] . ' ' . $user[0][$this->db]['lastname'],
                    'email' => $this->request->admin->email,
                    'target_email' => $user[0][$this->db]['email'],
                ], 'password changed.');
                return Helpers::returnSuccess(200, [], "Your Password has been changed successfully.");
            }
            return Helpers::returnError("Current password is incorrect.", 400);
        } catch (Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }
    /**
     * Returns top performing master or village agents
     */
    public function getTopAgents()
    {
        $requestArray = DateRequestFilter::getRequestParam($this->request);
        list($start_date, $end_date) = $requestArray;
        if ($this->request->agent !== 'ma' && $this->request->agent !== 'va') {
            return Helpers::returnError("Invalid parameter supplied.", 400);
        }
        $field = ($this->request->agent === 'ma') ? 'ma_id' : 'vaId';
        try {
            $topAgents = [];
            $agentFarmerCount = DB::select("SELECT {$field}, COUNT(*) AS farmer_count FROM " . $this->db . "
                    WHERE type = 'farmer'  AND (created_at > '" . $start_date . "' AND created_at < '" . $end_date . "')
                    GROUP BY {$field} ORDER BY farmer_count DESC LIMIT 5");
            foreach ($agentFarmerCount as $agent) {
                $topAgent = [];
                $topAgent['farmerCount'] = $agent['farmer_count'];
                $agentOrderCount = DB::select("SELECT COUNT({$field}) AS order_count FROM
                " . $this->db . " WHERE type = 'order' AND {$field}='" . $agent[$field] . "'");
                $topAgent['orderCount'] = $agentOrderCount[0]['order_count'];
                $topPerformers = ($this->request->agent === 'ma') ? $this->masterAgent($agent, $field, $topAgent) : $this->villageAgent($agent, $topAgent);
                array_push($topAgents, $topPerformers);
            }
            $result = array_filter($topAgents);
            return Helpers::returnSuccess(200, ['data' => $result], "");
        } catch (\Exception $e) {
            return Helpers::returnError("Something went wrong.", 503);
        }
    }
    /**
     * Returns top performing village agents filtered by date and location
     */
    public function villageAgent($agent, $topAgent)
    {
        $district = $this->request->input('district');
        $agentNameQuery = "SELECT va_name AS agent_name FROM
    " . $this->db . " WHERE type = 'va' AND _id='" . $agent['vaId'] . "'";
        if ($district) {
            $agentNameQuery .= " AND va_district='" . $district . "'";
        }
        $agentName = DB::select($agentNameQuery);
        if (count($agentName) > 0) {
            $topAgent['name'] = $agentName[0]['agent_name'];
            return $topAgent;
        }
    }
    /**
     * Returns top performing master agents filtered by date and location
     */
    public function masterAgent($agent, $field, $topAgent)
    {
        $district = $this->request->input('district');
        $agentNameQuery = "SELECT account_name AS agent_name FROM
    " . $this->db . " WHERE type = 'ma' AND _id='" . $agent[$field] . "'";
        if ($district) {
            $agentNameQuery .= " AND district='" . $district . "'";
        }
        $agentName = DB::select($agentNameQuery);
        if (count($agentName) > 0) {
            $topAgent['name'] = $agentName[0]['agent_name'];
            return $topAgent;
        }
    }
}
