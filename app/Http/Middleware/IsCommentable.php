<?php

namespace App\Http\Middleware;

use Closure;
use App\FriendRequest;
use App\Post;

class IsCommentable
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
        $userId = null;
        $postId = null;
        $post = null;
        //setting userId and post
        if ($request->is('api/posts/*')) {
            $postId = $request->route('post');
            $post = Post::findOrFail($postId);
            $userId = $post->user_id;
        }
        //check if friend
        if (!FriendRequest::isFriend($userId)) {
            $message = ['status' => 0, 'message' => 'you dont have permissions for this link'];
            $json = json_encode($message);
            return response($json, 200)->header('Content-Type', 'application/json');
        }

        //check if the post is viewable
        if (!$post->isViewable()) {
            $message = ['status' => 0, 'message' => 'you dont have permissions for this link'];
            $json = json_encode($message);
            return response($json, 200)->header('Content-Type', 'application/json');
        }


        return $next($request);
    }
}
