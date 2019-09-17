<?php
namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class AuthenticateAdmin
{
    private $userId;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->userId = $this->request->auth;
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
        $userId = $this->request->auth;
        $admin = Admin::where('_id', '=', $userId)
            ->where('adminRole', 'Super Admin')->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'error' => 'You are not an authorized user.'], 403);
        }
        $request->request->add(['admin' => $admin]);

        return $next($request);
    }
}
