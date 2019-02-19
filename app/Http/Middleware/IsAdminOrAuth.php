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
        $userId = null;
        if ($request->is('api/users/*')) {
            $userId = $request->route('user');
        }
        if ($request->is('api/posts/*')) {
            $postId = $request->route('post');
            $userId = Post::findOrFail($postId)->userId;
        }
        if ($request->is('api/comments/*')) {
            $commentId = $request->route('comment');
            $userId = Comment::findOrFail($commentId)->post->user_id;
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
