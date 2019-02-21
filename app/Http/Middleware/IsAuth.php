<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Post;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\ModelItem;

class IsAuth
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
        //setting ownerId(user id)
        $modelItem = new ModelItem($request);
        $ownerId = $modelItem->getOwnerId();
        //checking if the userId is same as the auth id
        if (User::isSame($ownerId, Auth::id())) {
            return $next($request);
        }
        //sending a message if the url banned from authenticated user
        $message = ['status' => 0, 'message' => 'you dont have permissions for this link'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
