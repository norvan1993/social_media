<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reply;
use Illuminate\Support\Facades\Auth;

class Repliescontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('is_auth')->only('update');
    }
    /******************************************************************************
    * store
    ******************************************************************************/
    public function store(Request $request)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'comment_id' => ['required', new CheckAddingReply],
                'body' => ['present'],
                'photo' => ['required_without:body', 'image'],
            ]
        );
        //storing reply
        $input = $request->only(['comment_id', 'body']);
        $input['user_id'] = Auth::id();
        $reply = Reply::create($input);
        //storing image if exist
        if ($file = $request->file('photo')) {
            if ($file->isValid()) {
                $photo = new Photo();
                $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Reply', $reply->id);
            }
        }
        //sending json message
        $json = json_encode(['status' => 1, 'message' => 'success']);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /******************************************************************************
    * show
    ******************************************************************************/
    public function show($id)
    {
        return Reply::findOrFail($id);
    }
    /******************************************************************************
    * update
    ******************************************************************************/
    public function update(Request $request, $id)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'body' => ['present'],
                'photo_status' => ['required_without:body', Rule::in(['same', 'new']), new CheckPhotoStatus],
                'photo' => ['required_if:photo_status,new', 'image'],
            ]
        );
        //check reply id
        $reply = Reply::findOrFail($id);
        //delete old photo
        if (!$request->photo_status) {
            if ($reply->photo) {
                $reply->photo->delete();
            }
        }
        //update photo
        if ($request->photo_status == 'new') {
            if ($reply->photo) {
                $reply->photo->delete();
            }

            if ($file = $request->file('photo')) {
                if ($file->isValid()) {
                    $photo = new photo();
                    $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Reply', $reply->id);
                }
            }
        }
        //update the reply
        $input['body'] = $request->body;
        $reply->update();
    }
    /******************************************************************************
    * destroy
    ******************************************************************************/
    public function destroy($id)
    {
        Reply::findOrFail($id)->delete();
    }
}
