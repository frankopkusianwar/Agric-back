<?php
namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Farmer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\DateRequestFilter;
use App\Utils\Helpers;
use App\Utils\Queries;

class DistrictController extends Controller
{
    /** @var string */
    protected $bucket;
    protected $helpers;
    protected $queries;
    /**
     * DistrictController constructor.
     */
    public function __construct()
    {
        // @phan-suppress-next-line PhanPossiblyFalseTypeMismatchProperty
        $this->bucket = getenv('DB_DATABASE');
        $this->helpers = new Helpers();
        $this->queries = new Queries();
    }
    /**
     * Get top performing districts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopDistricts(Request $request)
    {
        $requestArray = DateRequestFilter::getRequestParam($request);
        list($start_date, $end_date) = $requestArray;
        $startDateCount = Farmer::where('created_at', '<=', $start_date)->get()->count();
        $endDateCount = Farmer::where('created_at', '<=', $end_date)->get()->count();
        $percentage = DateRequestFilter::getPercentage($startDateCount, $endDateCount);
        $districts = ($start_date && $end_date) ? Farmer::whereBetween('created_at', [$start_date, $end_date])
      ->pluck('farmer_district')->toArray() : Farmer::pluck('farmer_district')->toArray();
        $allDistricts = [];
        foreach ($districts as $district) {
            if (array_key_exists($district, $allDistricts)) {
                $allDistricts[$district] = $allDistricts[$district] += 1;
            } else {
                $allDistricts[$district] = 1;
            }
        }
        arsort($allDistricts);
        $topDistricts = array_slice($allDistricts, 0, 4, true);
        return Helpers::returnSuccess(200, [
      'districtCount' => count($allDistricts),
      'farmerCount' => count($districts),
      'topDistricts' => $topDistricts,
      'allDistricts' => $allDistricts,
      'percentage' => $percentage
    ], "");
    }
    /**
     * Get top performing districts based on app downloads and web users
     */
    public function getTopPerformingDistricts($district = null)
    {
        try {
            if ($district == null) {
                $topDistrictsByAppDownloads = Queries::getTopDistrictsByAppDownloads();
            } else {
                $topDistrictsByAppDownloads = Queries::getTopDistrictsByAppDownloads($district);
            }
            foreach ($topDistrictsByAppDownloads as $index => $topDistrictsByAppDownload) {
                $topDistrictWebUsers = DB::select("SELECT COUNT(1) AS district_web_users
        FROM " . $this->bucket . "
        WHERE type IN ['ma', 'offtaker', 'va'] 
        AND ((district = '" . $topDistrictsByAppDownload['name'] . "') OR (va_district = '" . $topDistrictsByAppDownload['name'] . "'))");
                $topDistrictAppPurchases = DB::select("SELECT COUNT(1) AS district_app_purchases
        FROM " . $this->bucket . "
        WHERE type IN ['planting', 'spraying', 'order', 'soil_test'] 
        AND ((district = '" . $topDistrictsByAppDownload['name'] . "') OR (details.district = '" . $topDistrictsByAppDownload['name'] . "'))");
                $topDistrictsByAppDownloads[$index]['webUsers'] = $topDistrictWebUsers[0]['district_web_users'];
                $topDistrictsByAppDownloads[$index]['appPurchases'] = $topDistrictAppPurchases[0]['district_app_purchases'];
            }
            return Helpers::returnSuccess(200, ['data' => $topDistrictsByAppDownloads], "");
        } catch (Exception $e) {
            return Helpers::returnError('Something went wrong.', 503);
        }
    }
  
    /**
     * Gets all districts in the database
     * @return object http response
     */
    public function getDistricts()
    {
        try {
            $districts = District::orderBy('name', 'asc')->get(['name']);
            return Helpers::returnSuccess(200, ['data' => $districts], "Districts retrieved successfully");
        } catch (Exception $e) {
            return Helpers::returnError('Something went wrong.', 503);
        }
    }
}
