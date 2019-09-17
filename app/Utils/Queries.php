<?php
namespace App\Utils;

use App\Models\Farmer;
use App\Models\InputOrder;
use App\Models\Insurance;
use App\Models\MapCoordinate;
use App\Models\MasterAgent;
use App\Models\Order;
use App\Models\Planting;
use App\Models\SoilTest;
use App\Models\Spraying;
use App\Utils\Email;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

/** @phan-file-suppress PhanPossiblyFalseTypeArgumentInternal, PhanPossiblyFalseTypeMismatchProperty, PhanPossiblyNonClassMethodCall, PhanPossiblyNullTypeArgumentInternal, PhanPossiblyNonClassMethodCall, PhanPossiblyNullTypeArgument, PhanPartialTypeMismatchReturn, PhanPartialTypeMismatchArgument */
class Queries extends BaseController
{
    /** @var string $url */
    private static $url;
    private static $mail;
    private static $db;
    private static $masterAgent;
    public static $user;
    public function __construct()
    {
        self::$mail = new Email();
        self::$url = getenv('FRONTEND_URL');
        self::$db = getenv('DB_DATABASE');
        self::$masterAgent = new MasterAgent();
    }
    /**
     * Get a user by id
     * @param string $id
     * @return array $user
     */
    public static function queryUser($id)
    {
        return DB::select('select * from ' . getenv('DB_DATABASE') . ' where _id = ?', [$id]);
    }
    /**
     * Change user account status
     * @param string $id userid
     * @param string $status
     * @return boolean true or false
     */
    public static function changeStatus($id, $status)
    {
        $user = self::queryUser($id);
        if (count($user) < 1) {
            return false;
        }
        $query = "UPDATE " . getenv('DB_DATABASE') .
            " SET status='" . $status . "'WHERE _id= '" . $id .
            "' Returning * ";
        $result = DB::statement($query);
        $resultArray = json_decode(json_encode($result), true);
        return ($resultArray['status'] === 'success') ? true : false;
    }
    /**
     * delete user
     * @param string $id
     * @return object|boolean query result
     */
    public static function deleteUser($id)
    {
        $result = DB::statement('delete from ' . getenv('DB_DATABASE') . ' where _id = ? returning *', [$id]);
        $resultArray = json_decode(json_encode($result), true);
        if ($resultArray) {
            return $resultArray['rows'];
        }
        return false;
    }
    /**
     * Edit account
     * @param string $id
     * @param array $data
     * @return object|boolean query result
     */
    public static function editAccount($id, $data)
    {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "${key} = ?";
            $values[] = $value;
        }
        array_push($values, $id);
        $query = 'UPDATE ' . getenv('DB_DATABASE') . ' SET ' . join(",", $fields) . ' WHERE _id=?';
        $queryResult = DB::statement($query, $values);
        if (!$queryResult) {
            return false;
        }
        return $queryResult;
    }
    public static function checkUser($id)
    {
        $user = self::queryUser($id);
        unset($user[0][getenv('DB_DATABASE')]['password']);
        return $user ? $user[0][getenv('DB_DATABASE')] : false;
    }
    /**
     * Gets the top districts ranked by the number of app downloads
     */
    public static function getTopDistrictsByAppDownloads($district = null)
    {
        $districtClause = $district ? " AND farmer.farmer_district =='" . $district . "'" : "";
        $topDistrictsByAppDownloads = DB::select("SELECT farmer.farmer_district AS name, COUNT(farmer.farmer_district) AS appDownloads
      FROM " . self::$db . " farmer
      INNER JOIN " . getenv('DB_DATABASE') . " account
      ON KEYS ('account::' || farmer.farmer_id)
      WHERE account.type == 'account'
        AND farmer.type == 'farmer'"
            . $districtClause . "
      GROUP BY farmer.farmer_district
      ORDER BY appDownloads DESC LIMIT 5");
        return $topDistrictsByAppDownloads;
    }
    /**
     * Returns the most ordered products,
     * filtered by enterprise(crop) or district
     *
     * @param string $type - filter type
     * @param string $filter - filter value
     * @return array products
     */
    public static function getMostOrderedProducts($type = 'enterprise', $filter = '')
    {
        $products = [];
        if ($type == 'enterprises') {
            $enterpriseFarmers = Farmer::query()->select('_id')->where('value_chain', '=', $filter)->get()->toArray();
            $farmerIDs = array_map(function ($enterpriseFarmer) {
                return $enterpriseFarmer['_id'];
            }, $enterpriseFarmers);
            $productOrders = InputOrder::query()->select('orders[*].product')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $products = array_map(function ($product) {
                return $product['product'];
            }, $productOrders);
        } elseif ($type == 'districts') {
            $productOrders = InputOrder::query()->select('orders[*].product')->where('details.district', '=', $filter)->get()->toArray();
            $products = array_map(function ($product) {
                return $product['product'];
            }, $productOrders);
        }
        return $products;
    }
    /**
     * Returns the most ordered services,
     * filtered by enterprise(crop) or district
     *
     * @param string $type - filter type
     * @param string $filter - filter value
     * @return array services
     */
    public static function getMostOrderedServices($type = 'enterprise', $filter = '')
    {
        $services = [];
        if ($type == 'enterprises') {
            $enterpriseFarmers = Farmer::query()->select('_id')->where('value_chain', '=', $filter)->get()->toArray();
            $farmerIDs = array_map(function ($enterpriseFarmer) {
                return $enterpriseFarmer['_id'];
            }, $enterpriseFarmers);
            $mapCoordinates = MapCoordinate::query()->select('COUNT(*) AS garden_mapping')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $soilTest = SoilTest::query()->select('COUNT(*) AS soil_test')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $planting = Planting::query()->select('COUNT(*) AS planting')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $spraying = Spraying::query()->select('COUNT(*) AS spraying')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $insurance = Insurance::query()->select('COUNT(*) AS insurance')->where('request.crop_insured', '=', $filter)->get()->toArray();
            $services = array_merge($mapCoordinates, $soilTest, $planting, $spraying, $insurance);
        } elseif ($type == 'districts') {
            $enterpriseFarmers = Farmer::query()->select('_id')->where('farmer_district', '=', $filter)->get()->toArray();
            $farmerIDs = array_map(function ($enterpriseFarmer) {
                return $enterpriseFarmer['_id'];
            }, $enterpriseFarmers);
            $mapCoordinates = MapCoordinate::query()->select('COUNT(*) AS garden_mapping')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $soilTest = SoilTest::query()->select('COUNT(*) AS soil_test')->where('district', '=', $filter)->get()->toArray();
            $planting = Planting::query()->select('COUNT(*) AS planting')->where('district', '=', $filter)->get()->toArray();
            $spraying = Spraying::query()->select('COUNT(*) AS spraying')->where('district', '=', $filter)->get()->toArray();
            $insurance = Insurance::query()->select('COUNT(*) AS insurance')->whereIn('user_id', $farmerIDs)->get()->toArray();
            $services = array_merge($mapCoordinates, $soilTest, $planting, $spraying, $insurance);
        }
        return $services;
    }
    /**
     * Return the list of active mobile users based on
     * accessed services
     *
     * @param string $db
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public static function getActiveMobileUsers($db, $start_date, $end_date)
    {
        $result = (!empty($start_date) && isset($start_date) ? DB::select(
            'SELECT DISTINCT user_id FROM ' . $db .
            ' WHERE (type IN
            ["order", "map_cordinates", "soil_test", "planting", "spraying", "insurance"])
                AND
        (
          DATE_FORMAT_STR(created_at, "1111-11-11") BETWEEN
          DATE_FORMAT_STR("' . $start_date . '", "1111-11-11") AND
          DATE_FORMAT_STR("' . $end_date . '", "1111-11-11")
        )'
        ) : DB::select(
            'SELECT DISTINCT user_id FROM ' . $db .
            ' WHERE type IN
            ["order", "map_cordinates", "soil_test", "planting", "spraying", "insurance"] '
        ));
        return $result;
    }
    /**
     * Get total number of orders
     * @param  \Illuminate\Http\Request $request
     * @return array query result
     */
    public static function getNewOrders($request)
    {
        $requestArray = DateRequestFilter::getRequestParam($request);
        list($start_date, $end_date) = $requestArray;
        $orders = DB::select("SELECT COUNT(1) AS newOrders
        FROM " . getenv('DB_DATABASE') . "
        WHERE type
        IN ['order', 'planting', 'spraying', 'insurance', 'soil_test', 'map_cordinates']
        AND status = 'new'
        OR stature='new'
        AND (created_at BETWEEN '" . $start_date . "' AND  '" . $end_date . "')");
        return $orders;
    }
    /**
     * Returns all orders of the specified order type
     *
     * @param string $orderType
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getOrdersByType($orderType)
    {
        if ($orderType == 'completed') {
            $orders = Order::queryOrdersByStatus('=', 'delivered');
        } elseif ($orderType == 'received') {
            $orders = Order::queryOrdersByStatus('!=', 'delivered');
        } else {
            $errorMessage = 'Invalid parameters supplied in request';
            return Helpers::returnError($errorMessage, 400);
        }
        return Helpers::returnSuccess(200, [$orderType . '_orders' => $orders, 'count' => count($orders)], "");
    }
}
