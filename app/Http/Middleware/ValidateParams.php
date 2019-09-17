<?php
namespace App\Http\Middleware;

use App\Utils\Helpers;
use Closure;

class ValidateParams
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
        if (!preg_match('/\b(government|masteragent|offtaker)\b/', $request->user)) {
            return Helpers::returnError("Invalid parameter supplied.", 400);
        }
        return $next($request);
    }
}
