<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\Providers\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roleIds)
    {
        $userRoleId = Auth::user()->role_id;

        if (in_array($userRoleId, $roleIds)) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}