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
            return 'this page not allowed for you';
        }

        return $next($request);
    }
}
