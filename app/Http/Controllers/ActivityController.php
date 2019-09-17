<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\DateRequestFilter;
use App\Models\ActivityLog;
use App\Utils\Helpers;
use App\Models\InputOrder;
use App\Models\Planting;
use App\Models\SoilTest;
use App\Models\MapCoordinate;
use App\Utils\Validation;

class ActivityController extends Controller
{
    private $request;
    private $db;
    private $validate;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->db = getenv('DB_DATABASE');
        $this->validate = new Validation();
    }

    /**
     * get all activities in the log
     *
     */
    public function getActivityLog()
    {
        $this->validate->validateLimitAndOffset($this->request);
        $limit = $this->request->input('limit');
        $activities = ActivityLog::paginate($limit);
        return Helpers::returnSuccess(200, ['count' => $activities->total(),'activityLog' => $activities->items()], "");
    }

    public function getPercentages($startDate, $endDate)
    {
        $inputOrders = InputOrder::whereBetween('created_at', [$startDate, $endDate])->pluck('details')->toArray();
        $acresPlanted = Planting::whereBetween('created_at', [$startDate, $endDate])->pluck('acreage')->toArray();
        $soilTestAcreage = SoilTest::whereBetween('created_at', [$startDate, $endDate])->pluck('acreage')->toArray();
        $gardenMapped = MapCoordinate::whereBetween('created_at', [$startDate, $endDate])->pluck('acreage')->toArray();
        return [
        'Input orders' => array_sum(array_column($inputOrders, 'totalCost')),
        'Planting' => array_sum($acresPlanted) . ' Acres',
        'Soil testing' => array_sum($soilTestAcreage) . ' Acres',
        'Garden Mapping' => array_sum($gardenMapped) . ' Acres',
        'Produce Sold' => 0 . ' Tons',
        ];
    }

    public function getActivitySummary(Request $request)
    {
        $requestArray = DateRequestFilter::getRequestParam($request);
        list($startDate, $endDate) = $requestArray;


        $oldInputOrdersSum = InputOrder::where('created_at', '<=', $startDate)->sum('details.totalCost');
        $oldInputAcresPlanted = Planting::where('created_at', '<=', $startDate)->pluck('acreage')->toArray();
        $oldInputSoilTestAcreage = SoilTest::where('created_at', '<=', $startDate)->pluck('acreage')->toArray();
        $oldInputGardenMapped = MapCoordinate::where('created_at', '<=', $startDate)->sum('acreage');

        $newInputOrdersSum = InputOrder::where('created_at', '<=', $endDate)->sum('details.totalCost');
        $newInputAcresPlanted = Planting::where('created_at', '<=', $endDate)->pluck('acreage')->toArray();
        $newInputSoilTestAcreage = SoilTest::where('created_at', '<=', $endDate)->pluck('acreage')->toArray();
        $newInputGardenMapped = MapCoordinate::where('created_at', '<=', $endDate)->sum('acreage');


        $percentageInputOrders = DateRequestFilter::getPercentage($oldInputOrdersSum, $newInputOrdersSum);
        $percentageAcresPlanted = DateRequestFilter::getPercentage(array_sum($oldInputAcresPlanted), array_sum($newInputAcresPlanted));
        $percentageSoilTestAcreage = DateRequestFilter::getPercentage(array_sum($oldInputSoilTestAcreage), array_sum($newInputSoilTestAcreage));
        $percentageGardenMapped = DateRequestFilter::getPercentage($oldInputGardenMapped, $newInputGardenMapped);

        return Helpers::returnSuccess(200, [
        'activities' => $this->getPercentages($startDate, $endDate),
        'activitiesPercentage' => [
            'Input orders' => $percentageInputOrders,
            'Planting' => $percentageAcresPlanted,
            'Soil testing' => $percentageSoilTestAcreage,
            'Garden Mapping' => $percentageGardenMapped,
            'Produce Sold' => 0,
        ]
        ], "");
    }
}
