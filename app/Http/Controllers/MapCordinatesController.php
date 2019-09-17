<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MapCoordinate;

use App\Utils\DateRequestFilter;
use App\Utils\Helpers;

class MapCordinatesController extends Controller
{
    /**
     * Get total acreage for farmers using ezyagric
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getTotalAcreage(Request $request)
    {
        $requestArray = DateRequestFilter::getRequestParam($request);
        list($start_date, $end_date) = $requestArray;
        try {
            $result = ($request->input('start_date') && $request->input('end_date')) ? MapCoordinate::whereBetween('created_at', [$start_date, $end_date])
        ->sum('acreage') : MapCoordinate::all()->sum('acreage'); // @phan-suppress-current-line PhanUndeclaredFunctionInCallable, PhanPossiblyNonClassMethodCall
            return Helpers::returnSuccess(200, ['totalAcreage' => $result], "");
        } catch (\Exception $e) {
            return Helpers::returnError('Could not get total acreage.', 503);
        }
    }
}
