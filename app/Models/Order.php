<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "details",
        "orders",
        "payment",
        "stature",
        "status",
        "type",
        "user_id",
        "vaId",
        "ma_id",
        "_id"
  ];
    protected $table = 'order';
    protected $dates = [];
    public static $rules = [];
    protected $primaryKey = '_id';
    public $incrementing = false;

    /**
     * Takes a comparison operator and an order staus
     * Returns all orders of the specified status
     *
     * @param string $comparator
     * @param string $status
     *
     * @return array query result
     */

    public static function queryOrdersByStatus($comparator, $status)
    {
        $orders = Order::select(
            '_id',
            'details.name',
            'details.phone',
            'details.time',
            'details.district',
            'status',
            'payment',
            'details.totalItems as total_items',
            'details.totalCost as total_cost',
            'orders',
            'created_at',
            'updated_at'
        )
            ->where('LOWER(status)', $comparator, $status)
            ->orderBy('updated_at', 'desc')
            ->get();
        return $orders;
    }
}
