<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Rules\CheckMessagePartner;
use App\Photo;

class MessagesController extends Controller
{
    public function __construct()
    { }
    /*******************************************************************************
     *send//via POST::api/messages/send
     ******************************************************************************/
    public function send(Request $request)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'receiver_id' => ['required', new CheckMessagePartner],
                'body' => ['present'],
                'photos' => ['required_without:body'],
                'photos.*' => ['image'],
            ]
        );
        //storing message
        $input = $request->only(['receiver_id', 'body']);
        $input['sender_id'] = Auth::id();
        $input['status'] = 'new';
        $message = Message::create($input);

        //storing photos if exist
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $photo = new Photo();
                    $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Message', $message->id);
                }
            }
        }
    }
    /*******************************************************************************
     *receive//via PUT::api/messages/receive
     ******************************************************************************/
    public function receive(Request $request)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'user_id' => ['required', new CheckMessagePartner],
            ]
        );
        Message::where('sender_id', '=', $request->user_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'new')->each(function ($message) {
            $message->update(['status' => 'seen']);
        });
        return Message::where('sender_id', '=', Auth::id())->where('receiver_id', '=', $request->user_id)->orWhere('sender_id', '=', $request->user_id)->where('receiver_id', '=', Auth::id())->paginate(20);
    }
    /*******************************************************************************
     *receive//via PUT::api/messages/receive_new
     ******************************************************************************/
    public function receiveNew(Request $request)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'user_id' => ['required', new CheckMessagePartner],
            ]
        );
        //selecting new messages
        $messages = Message::where('sender_id', '=', $request->user_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'new');
        //paginating new messages
        $newMessages = Message::where('sender_id', '=', $request->user_id)->where('receiver_id', '=', Auth::id())->where('status', '=', 'new')->paginate(20);
        //changing status to seen
        $messages->each(function ($message) {
            $message->update(['status' => 'seen']);
        });
        //return new messages
        return $newMessages;
    }
}
