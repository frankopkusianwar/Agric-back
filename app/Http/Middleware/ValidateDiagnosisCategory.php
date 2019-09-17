<?php
namespace App\Http\Middleware;

use App\Utils\Helpers;
use Closure;

class ValidateDiagnosisCategory
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
        $category = strtolower($request->category);
        if ($category != 'pest' && $category != 'disease') {
            return Helpers::returnError('Invalid category supplied.', 422);
        }
        return $next($request);
    }
}
