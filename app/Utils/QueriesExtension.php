<?php
namespace App\Utils;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

/** @phan-file-suppress PhanPossiblyFalseTypeArgumentInternal, PhanPossiblyFalseTypeMismatchProperty, PhanPossiblyNonClassMethodCall, PhanPossiblyNullTypeArgumentInternal, PhanPossiblyNonClassMethodCall, PhanPossiblyNullTypeArgument, PhanPartialTypeMismatchReturn, PhanPartialTypeMismatchArgument */
class QueriesExtension extends BaseController
{
    /**
     *@param string $category
     *@return array $query result
     */
    public static function getInputCategory($category)
    {
        $order = DB::select("SELECT " . getenv('DB_DATABASE') . ".created_at, item
        FROM " . getenv('DB_DATABASE') . "
        UNNEST orders
        AS item
        WHERE " . getenv('DB_DATABASE') . ".type = 'order'
        AND item.category='$category'");

        return $order;
    }

    /**
     * GET available stock
     *@param string $category
     * @return array query result
     */
    public static function getOrderCategory($category)
    {
        $inputs = DB::select("SELECT created_at from " . getenv('DB_DATABASE') . " where type = '$category'");

        return $inputs;
    }
}
