<?php
namespace App\Http\Middleware;

use App\Utils\Helpers;
use Closure;

class DocumentExist
{
    private $helper;

    /**
     * DocumentExist constructor.
     */
    public function __construct()
    {
        $this->helper = new Helpers();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->id) {
            return $next($request);
        }

        if (!Helpers::documentExist($request->id)) {
            return Helpers::returnError('Document not found', 404);
        }

        return $next($request);
    }
}
