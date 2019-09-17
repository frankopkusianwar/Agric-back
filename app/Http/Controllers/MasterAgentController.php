<?php

namespace App\Http\Controllers;

use App\Models\MasterAgent;
use App\Utils\Validation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Utils\Helpers;

class MasterAgentController extends BaseController
{
    private $validate;
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validate = new Validation();
    }

    /**
     * Get all master agents
     *
     * @return JsonResponse
     */
    public function getMasterAgents()
    {
        $this->validate->validateLimitAndOffset($this->request);
        $limit = $this->request->input('limit');
        $result = MasterAgent::paginate($limit);
        return Helpers::returnSuccess(200, [
      'count' => $result->total(),
      'result' => $result->items()
    ], "");
    }
}
