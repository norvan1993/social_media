<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdminOrAuth
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
//getting user id from different routes
        if ($request->is('api/users/*')) {
            $user_id = $request->route('user');
        }
        if ($request->is('api/posts/*')) {
            $post_id = $request->route('post');
            $user_id = Post::find($post_id)->user_id;
        }
//checking if the authenticated user is admin
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }
//checking if the user_id is same as the auth id
        if (User::isSame($user_id, Auth::id())) {
            return $next($request);
        }
//sending a message if the url banned from authenticated user
        $message = ['status' => 0, 'message' => 'you dont have permissions for this link'];
        $json = json_encode($message);
        return response($json, 200)->header(' Content - Type ', ' application / json');

    }
}
