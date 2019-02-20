<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use App\Photo;
use Illuminate\Validation\Rule;
use App\Rules\CheckPhotoStatus;
use Illuminate\Support\Facades\Route;
use App\ModelItem;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_commentable')->only('comment');
        $this->middleware('is_viewable')->only('show');
        $this->middleware('is_auth')->only('update');
        $this->middleware('is_admin_or_auth')->only('destroy');

    }
    /*******************************************************************
     * comment
     ******************************************************************/
    public function comment(Request $request, $commentableId)
    {

        $commentableType = null;
        if ($request->is('api/posts/*')) {
            $commentableType = 'App\\Post';
        }
       
//validating request
        $validatedData = $request->validate(
            [
                'body' => ['present'],
                'photo' => ['required_without:body', 'image'],
            ]
        );
//setting commentable type and id

//store comment
        $input['commentable_type'] = $commentableType;
        $input['commentable_id'] = $commentableId;
        $input['body'] = $request->body;
        $input['user_id'] = Auth::id();
        $comment = Comment::create($input);
//storing images
        if ($file = $request->file('photo')) {
            if ($file->isValid()) {
                $photo = new Photo();
                $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Comment', $comment->id);
            }
        }
//sending json message
        $json = json_encode(['status' => 1, 'message' => 'success']);
        return response($json, 200)->header('Content-Type', 'application/json');

    }
    /*******************************************************************
     * show
     ******************************************************************/
    public function show($id)
    {
        $comment = Comment::select('body')->with('photo')->find($id);
        $json = json_encode($comment);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /*******************************************************************
     * update
     ******************************************************************/
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
//checking comment id
        $comment = Comment::findOrFail($id);
//delete old photo 
        if (!$request->photo_status) {
            $comment->photo->delete();
        }
//update photo
        if ($request->photo_status == 'new') {
            if ($comment->photo) {
                $comment->photo->delete();
            }

            if ($file = $request->file('photo')) {
                if ($file->isValid()) {
                    $photo = new photo();
                    $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Comment', $comment->id);
                }
            }
        }
//update comment
        $input['body'] = $request->body;
        $comment->update($input);
    }
    /*******************************************************************
     * destroy
     ******************************************************************/
    public function destroy($id)
    {
        Comment::find($id)->delete();
    }
}
