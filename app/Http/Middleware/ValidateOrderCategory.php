<?php
namespace App\Http\Middleware;

use App\Utils\Helpers;
use Closure;

class ValidateOrderCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $category = ucwords(urldecode($request->category));

        $orders = [
            'Fertilizer',
            'Pesticide',
            'Herbicide',
            'Seeds',
            'Farming Tools',
            'Planting',
            'Spraying',
            'Insurance',
            'Soil_test',
            'Map_cordinates',
        ];

        if (!in_array($category, $orders, false)) {
            return Helpers::returnError("Invalid parameter supplied.", 400);
        }
        return $next($request);
    }
}
