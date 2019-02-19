<?php

namespace App\Http\Middleware;

use Closure;
use App\Post;
use App\ModelItem;

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
//getting the model item from request
                $modelItem = new ModelItem($request);
                $item = $modelItem->getItem();
//check if the item is viewable
                if ($item->isViewable()) {
                        return $next($request);
                }
//return message if the link is not accessable
                $message = ['status' => 0, 'message' => 'you dont have permissions for this link-is auth'];
                $json = json_encode($message);
                return response($json, 200)->header('Content-Type', 'application/json');

        }
}
