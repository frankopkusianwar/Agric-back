<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MapCoordinate;
use App\Models\SoilTest;
use App\Models\Planting;
use App\Models\Spraying;
use App\Utils\DateRequestFilter;
use App\Utils\Helpers;

class TotalPaymentController extends Controller
{
    public function getPaymentSum($startDate, $endDate)
    {
        $sumMapCordinate = MapCoordinate::whereBetween('created_at', [$startDate, $endDate])->sum('acreage');
        $sumOrder = Order::whereBetween('created_at', [$startDate, $endDate])->sum('details.totalCost');
        $sumSoilTest = SoilTest::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $sumPlanting = Planting::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $sumSpraying = Spraying::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        return $sumMapCordinate + $sumOrder + $sumSoilTest + $sumPlanting + $sumSpraying;
    }

    /**
     * Get total payment made by farmers of EzyAgric
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTotalPayment(Request $request)
    {
        $requestArray = DateRequestFilter::getRequestParam($request);
        list($startDate, $endDate) = $requestArray;

        $startDateCountMapCordinate = MapCoordinate::where('created_at', '<=', $startDate)->sum('acreage');
        $startDateCountOrder = Order::where('created_at', '<=', $startDate)->sum('details.totalCost');
        $startDateCountSoilTest = SoilTest::where('created_at', '<=', $startDate)->sum('total');
        $startDateCountPlanting = Planting::where('created_at', '<=', $startDate)->sum('total');
        $startDateCountSpraying = Spraying::where('created_at', '<=', $startDate)->sum('total');

        $startDateTotal  = ($startDateCountMapCordinate + $startDateCountOrder + $startDateCountSoilTest + $startDateCountPlanting + $startDateCountSpraying);

        $endDateCountMapCordinate = MapCoordinate::where('created_at', '<=', $endDate)->sum('acreage');
        $endDateCountOrder = Order::where('created_at', '<=', $endDate)->sum('details.totalCost');
        $endDateCountSoilTest = SoilTest::where('created_at', '<=', $endDate)->sum('total');
        $endDateCountPlanting = Planting::where('created_at', '<=', $endDate)->sum('total');
        $endDateCountSpraying = Spraying::where('created_at', '<=', $endDate)->sum('total');

        $endDateTotal  = ($endDateCountMapCordinate + $endDateCountOrder + $endDateCountSoilTest + $endDateCountPlanting + $endDateCountSpraying);

        $percentage = DateRequestFilter::getPercentage($startDateTotal, $endDateTotal);

        try {
            return Helpers::returnSuccess(200, [
        'totalPayment' => $this->getPaymentSum($startDate, $endDate),
        'percentagePayment' => $percentage
      ], "");
        } catch (\Exception $e) {
            return Helpers::returnError('Could not get total payment.', 503);
        }
    }
}
