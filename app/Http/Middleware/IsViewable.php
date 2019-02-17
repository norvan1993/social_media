<?php

namespace App\Http\Middleware;

use Closure;
use App\Post;

class IsViewable
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
//getting poost id from route
        $postId = $request->route('post');
//checking if the post exist
        $post = Post::findOrFail($postId);
//checking if the post is viewable
        if ($post->isViewable()) {
            return $next($request);

        }
//sending a message if the url banned from authenticated user
        $message = ['status' => 0, 'message' => 'you dont have permissions for this link-is auth'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');

    }
}
