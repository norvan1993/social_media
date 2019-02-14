<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        if (!Auth::user()->isAdmin()) {
            $message = ['status' => 0, 'message' => 'you dont have permissions for this link'];
            $json = json_encode($message);
            return response($json, 200)->header('Content-Type', 'application/json');
        }

        return $next($request);
    }
}
