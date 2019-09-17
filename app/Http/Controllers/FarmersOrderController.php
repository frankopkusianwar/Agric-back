<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Utils\Helpers;
use stdClass;

/** @phan-file-suppress PhanPossiblyNonClassMethodCall, PhanUndeclaredFunctionInCallable */

class FarmersOrderController extends Controller
{

    /**
     * Returns transformed order data with category of input and name of farmer
     */
    private static function getTransformedOrders()
    {
        $transformedOrders = Order::all()->transform(function ($item) {
            $orders = collect([]);
            foreach ($item->orders as $itemOrder) {
                // extract order details
                $order = new stdClass();
                $order->category = $itemOrder['category'];
                $order->farmer = $item->details['name'];
                $orders->push($order);
            }
            return $orders;
        });
        return $transformedOrders;
    }

    /**
     * Returns number of Farmers who ordered inputs of different categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getNumberOfFarmersWhoOrderedDifferentInputCategories()
    {
        $orders = self::getTransformedOrders()->flatten()
            ->groupBy('category')
            ->map(function ($collection) {
                // count unique farmers
                return $collection->unique('farmer')->count();
            });
        return Helpers::returnSuccess(200, ['farmers_orders' => $orders], "");
    }

    public static function TransformedOrders()
    {
        return self::getTransformedOrders();
    }
}
