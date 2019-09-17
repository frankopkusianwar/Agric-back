<?php

namespace App\Http\Controllers;

use App\Models\DevtPartner;
use App\Utils\Helpers;
use App\Utils\Validation;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Utils\DateRequestFilter;

// @phan-file-suppress PhanPartialTypeMismatchProperty
class DevtPartnerController extends BaseController
{
    /** @var string $requestPassword */
    private $requestPassword;
    /** @var string */
    private $email;
    private $validate;
    private $request;
    private $helpers;

    /**
     *
     * @param object $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validate = new Validation();
        $this->requestPassword = $this->request->input('password');
        $this->email = $this->request->input('email');

        $this->helpers = new Helpers();
    }

    /**
     * Get all development partners
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDevtPartners(Request $request)
    {
        $requestArray = DateRequestFilter::getRequestParam($request);
        list($start_date, $end_date) = $requestArray;

        $startDateCount = DevtPartner::where('created_at', '<=', $start_date)->get()->count();
        $endDateCount = DevtPartner::where('created_at', '<=', $end_date)->get()->count();
        $percentage = DateRequestFilter::getPercentage($startDateCount, $endDateCount);

        $result = DevtPartner::whereBetween('created_at', [$start_date, $end_date])->get();
        return Helpers::returnSuccess(200, [
            'count' => count($result),
            'result' => $result,
            'percentage' => $percentage
        ], "");
    }

    /**
     * Create development partner account
     * @return object http response
     */
    public function createDevtPartner()
    {
        $this->validate->validateNewAccount($this->request);
        try {
            $devtPartner = DevtPartner::create($this->request->all() + ['_id' => Helpers::generateId()]);

            if (!$devtPartner) {
                return Helpers::returnError("Could not create user.", 408);
            }

            $this->helpers->sendPassword($this->requestPassword, $this->email);

            Helpers::logActivity([
                'email' => $this->request->admin->email,
                'target_account_name' => $devtPartner->account_name,
                'target_email' => $devtPartner->email,
            ], 'dev. partner account created.');

            return Helpers::returnSuccess(200, ['devtPartner' => $devtPartner], "Please check your mail for your login password.");
        } catch (\Exception $e) {
            return Helpers::returnError("Could not create development partner", 408);
        }
    }
}
