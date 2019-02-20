<?php

namespace App\Http\Middleware;

use Closure;
use App\FriendRequest;

class IsNotBlocked
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
        $userId = $request->route('user');
        if (FriendRequest::isBlocked($userId)) {
            //sending a message if the url banned from authenticated user
            $message = ['status' => 0, 'message' => 'you dont have permissions for this link'];
            $json = json_encode($message);
            return response($json, 200)->header('Content-Type', 'application/json');
        }

        return $next($request);
    }
}
