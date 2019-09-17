<?php
namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

class Authenticate
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
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'error' => 'Please log in first.'], 401);
        }
        try {
            // @phan-suppress-next-line PhanPartialTypeMismatchArgument
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Your current session has expired, please log in again.'], 400);
        } catch (\ Exception $e) {
            return response()->json([
              'success' => false,
              'error' => 'An error occured while decoding token.'], 400);
        }
        //Get subscriber
        $user = $credentials->sub;
        if ($user) {
            $request->request->add(['auth' => $user]);
        }
        response()->json(['success' => false, 'error' => 'Invalid token. Please log in again.'], 400);
        return $next($request);
    }
}
