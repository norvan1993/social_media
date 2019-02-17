<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Like;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckAddingPostLike;
use App\Rules\CheckRemovingPostLike;

class LikesController extends Controller
{
    /********************************************************************************
     * addLike//via POST:api/post/add_like
     ********************************************************************************/
    public function addLikeToPost(Request $request)
    {
//validating request
        $validatedData = $request->validate(
            [
                'post_id' => ['required', new CheckAddingPostLike],
            ]
        );
//add like in likes table
        $input['likeable_type'] = 'App\\Post';
        $input['likeable_id'] = $request->post_id;
        $input['sender_id'] = Auth::id();
        Like::create($input);
    }
    /********************************************************************************
     * removeLike//via DELETE:api/post/remove_like
     ********************************************************************************/
    public function removeLikeFromPost(Request $request)
    {
//validating request
        $validatedData = $request->validate(
            [
                'post_id' => ['required', new CheckRemovingPostLike],
            ]
        );
//deleting like record from likes table
        Like::where('likeable_type', '=', 'App\\Post')->where('likeable_id', '=', $request->post_id)->where('sender_id', '=', Auth::id())->delete();
    }

}
