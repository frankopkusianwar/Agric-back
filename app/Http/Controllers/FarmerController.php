<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Utils\Helpers;

class FarmerController extends BaseController
{

  /**
   * Get all farmers
   *
   * @return \Illuminate\Http\JsonResponse
   */
    public function getFarmers()
    {
        $result = Farmer::all();
        return Helpers::returnSuccess(200, [
      'count' => count($result),
      'result' => $result
    ], "");
    }
}
