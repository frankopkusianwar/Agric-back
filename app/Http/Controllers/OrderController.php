<?php
namespace App\Http\Controllers;

use App\Models\InputSupplier;
use App\Models\Order;
use App\Utils\Helpers;
use App\Utils\Queries;
use App\Utils\QueriesExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class OrderController extends BaseController
{
    protected $request;
    protected $queries;
    protected $order;

    public function __construct(Request $request, Order $order)
    {
        $this->request = $request;
        $this->queries = new Queries();
        $this->order = $order;
    }
    /**
     * get total new orders
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders()
    {
        try {
            $order = Queries::getNewOrders($this->request);
            return Helpers::returnSuccess(200, ['totalNewOrders' => $order[0]['newOrders']], "");
        } catch (\Exception $e) {
            return Helpers::returnError('Could not get orders', 503);
        }
    }
    /**
     * Route - GET /orders/{type}
     *
     * @return object HTTP response
     */
    public function getOrdersByType($type)
    {
        try {
            $response = Queries::getOrdersByType($type);
        } catch (\Exception $e) {
            $response = Helpers::returnError('Could not get orders', 503);
        }
        return $response;
    }
    /**
     * GET available stock
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInputsStock()
    {
        try {
            $stock = [];
            $inputs = InputSupplier::select(
                'category',
                DB::raw('SUM(quantity) as quantity')
            )->groupBy('category')->get();
            for ($i = 0; $i < count($inputs); $i++) {
                $stock += [$inputs[$i]["category"] => $inputs[$i]["quantity"]];
            }
            return Helpers::returnSuccess(200, [
                "available_stock" => $stock,
                "total" => array_sum($stock),
            ], "");
        } catch (\Exception $e) {
            return Helpers::returnError("Could not get input stock.", 503);
        }
    }
    /**

     * DELETE order of a given ID
     *
     * @param string $orderID - id of the order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrderOfId($orderID)
    {
        Order::where('_id', $orderID)->delete();
        return Helpers::returnSuccess(200, ['data' => null], 'Order deleted successfully');
    }

    /**
     * PATCH update the status of a new order
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsCleared($id)
    {
        $order = $this->order->where('_id', $id)->first();
        if ($order && strtolower($order->status) == 'new') {
            $order->status = 'Intransit';
            $order->save();
            return Helpers::returnSuccess(200, [], 'Successfully marked order as cleared.');
        } else {
            return Helpers::returnError('Order already cleared.', 403);
        }
    }

    /**
     *@param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDistribution($category)
    {
        try {
            $orders = ['planting', 'spraying', 'insurance', 'soil_test', 'map_cordinates'];

            $order = in_array(strtolower($category), $orders, false) ?
            QueriesExtension::getOrderCategory(strtolower($category)) :
            QueriesExtension::getInputCategory(ucwords(urldecode($category)));

            return Helpers::returnSuccess(200, [
                'orderDistribution' => $order,
                'category' => $category,
            ], '');
        } catch (\Exception $e) {
            return Helpers::returnError($e->getMessage(), 503);
        }
    }
}
