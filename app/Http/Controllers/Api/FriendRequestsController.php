<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckFriendshipSending;
use App\Rules\CheckFriendshipCanceling;
use App\Rules\CheckFriendshipRejecting;
use App\Rules\CheckFriendshipAccepting;
use App\Rules\CheckUnfriending;
use App\Rules\CheckBlocking;
use App\Rules\CheckBlockRemoving;
use App\FriendRequest;
use App\User;

class FriendRequestsController extends Controller
{
    public function __construct()
    { }
    /**************************************************************************
     * send//via POST:api/friendship/send
     **************************************************************************/
    public function send(Request $request)
    {
        //request validating
        $request->validate([
            'receiver_id' => ['required', new CheckFriendshipSending],
        ]);

        //sending friend request
        $friendshipSending = ['sender_id' => Auth::id(), 'receiver_id' => $request->receiver_id, 'status' => 'sent'];
        FriendRequest::create($friendshipSending);
        //return successfull message to user
        $message = ['status' => 1, 'message' => 'friend request sent successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * cancel//via DELETE:api/friendship/cancel
     **************************************************************************/

    public function cancel(Request $request)
    {
        //request validating
        $validatedData = $request->validate([
            'receiver_id' => ['required', new CheckFriendshipCanceling],
        ]);

        //canceling friend request(deleting)
        FriendRequest::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $request->receiver_id)->first()->delete();
        //return successfull message to user
        $message = ['status' => 1, 'message' => 'friend request has been canceled successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * reject//via DELETE:api/friendship/reject
     **************************************************************************/
    public function reject(Request $request)
    {
        //request validating
        $validatedData = $request->validate([
            'sender_id' => ['required', new CheckFriendshipRejecting],
        ]);

        //rejecting friend request(deleting)
        FriendRequest::where('sender_id', '=', $request->sender_id)->where('receiver_id', '=', Auth::id())->delete();
        //return successfull message to user
        $message = ['status' => 1, 'message' => 'friend request has been rejected successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * accept//via PUT:api/friendship/accept
     **************************************************************************/
    public function accept(Request $request)
    {
        //request validating
        $validatedData = $request->validate([
            'sender_id' => ['required', new CheckFriendshipAccepting],
        ]);

        //accepting friend request
        FriendRequest::where('sender_id', '=', $request->sender_id)->where('receiver_id', '=', Auth::id())->update(['status' => 'friend']);
        //return successfull message to user
        $message = ['status' => 1, 'message' => 'friend request has been accepted successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * unfriend//via DELETE:api/friendship/unfriend
     **************************************************************************/

    public function unfriend(Request $request)
    {
        //validating request
        $validatedData = $request->validate([
            'friend_id' => ['required', new CheckUnfriending],
        ]);
        //unfriending request
        if ($friend = FriendRequest::where('sender_id', '=', $request->friend_id)->where('receiver_id', '=', Auth::id())) {
            $friend->delete();
        }
        if ($friend = FriendRequest::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $request->friend_id)) {
            $friend->delete();
        }
        //return successfull message to user
        $message = ['status' => 1, 'message' => 'you have deleted a friend successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * block//via POST:api/friendship/block
     **************************************************************************/
    public function block(Request $request)
    {
        //validating request
        $validatedData = $request->validate([
            'receiver_id' => ['required', new CheckBlocking],
        ]);
        //blocking request

        if ($user = FriendRequest::where('sender_id', '=', $request->receiver_id)->where('receiver_id', '=', Auth::id())->first()) {
            $user->update(['sender_id' => Auth::id(), 'receiver_id' => $request->receiver_id, 'status' => 'block']);
        } else if ($user = FriendRequest::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $request->receiver_id)->first()) {
            $user->update(['status' => 'block']);
        } else {
            FriendRequest::create(['sender_id' => Auth::id(), 'receiver_id' => $request->receiver_id, 'status' => 'block']);
        }

        //return successfull message to user
        $message = ['status' => 1, 'message' => 'you have blocked the user successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * removeBlock//via DELETE:api/friendship/remove_block
     **************************************************************************/
    public function removeBlock(Request $request)
    {
        //validating request
        $validatedData = $request->validate([
            'receiver_id' => ['required', new CheckBlockRemoving],
        ]);

        //remove blocking(delete)
        FriendRequest::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $request->receiver_id)->delete();
        //return successfull message to user
        $message = ['status' => 1, 'message' => 'you have removed the block from  user successfully'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * friendList//via GET:api/friendship/{user_id}/friends_list
     **************************************************************************/
    public function friendsList($id)
    {

        $array1 = FriendRequest::where('sender_id', '=', $id)->where('status', '=', 'friend')->pluck('receiver_id')->toArray();
        $array2 = FriendRequest::where('receiver_id', '=', $id)->where('status', '=', 'friend')->pluck('sender_id')->toArray();

        $friendsArray["friendsList"] = array_merge($array1, $array2);
        //sending list of friends id as json
        $json = json_encode($friendsArray);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * friendStatus//via GET:api/friendship/{id}/friend_status
     **************************************************************************/
    public function friendStatus($id)
    {
        $friendStatusArray["status"] = FriendRequest::relationType($id);
        //sending friend status for given id
        $json = json_encode($friendStatusArray);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
