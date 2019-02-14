<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Auth;
use App\Photo;
use App\Rules\CheckPrivacy;
use App\Rules\CheckDeletedPhotos;
use Intervention\Image\ImageManagerStatic as Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_auth')->only('update');
        $this->middleware('is_auth_or_admin')->only('delete');
    }
    /**************************************************************************
     * index
     **************************************************************************/
    public function index()
    {
        $json = json_encode(Post::select('id', 'user_id', 'title', 'body', 'created_at', 'updated_at')->with('photos')->paginate(10));
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * store
     **************************************************************************/
    public function store(Request $request)
    {
//validating request
        $validatedData = $request->validate(
            [
                'title' => 'required',
                'body' => ['sometimes', 'required'],
                'privacy' => ['required', new CheckPrivacy],
                'photos.*' => ['sometimes', 'required', 'image'],

            ]
        );
//selecting the direct inputs from the user
        $input = $request->only(['title', 'body', 'privacy']);
//setting user id
        $input['user_id'] = Auth::id();
//storing the post
        $postModel = Post::create($input);

//storing images
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $photo = new Photo;
                    $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Post', $postModel->id);
                }
            }
        }
        $json = json_encode(['status' => 1, 'message' => 'success']);
        return response($json, 200)->header('Content-Type', 'application/json');
    }




    /*************************************************************************
     * show
     **************************************************************************/

    public function show($id)
    {
        $postModel = Post::select('id', 'user_id', 'title', 'body')->find($id);
        $json = json_encode($post);
        return response($json, 200)->header('Content-Type', 'application/json');

    }
    /**************************************************************************
     * update
     **************************************************************************/
    public function update(Request $request, $id)
    {
//check post id
        $postModel = Post::with('photos')->findOrFail($id);

//validate request
        $validatedData = $request->validate(
            [
                'title' => ['sometimes', 'required'],
                'body' => ['sometimes', 'required'],
                'privacy' => ['sometimes', 'required', new CheckPrivacy],
                'photos.*' => ['sometimes', 'required', 'image'],
                'deleted_photos' => ['sometimes', 'required', 'array'],
                'deleted_photos.*' => ['sometimes', 'required', new CheckDeletedPhotos],

            ]
        );
//set direct user inputs
        $input = $request->only('title', 'body', 'privacy');
//set user id
        $input['user_id'] = Auth::id();
//update post
        $postModel->update($input);
//storing new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $photo = new Photo;
                    $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Post', $postModel->id);
                }
            }
        }
//deleting old Photos
        if ($request->deleted_photos) {
            foreach ($request->deleted_photos as $deleted_photo) {
                Photo::find($deleted_photo)->delete();
            }

        }


//send response
        $json = json_encode($postModel);
        return response($json, 200)->header('Content-Type', 'application/json');

    }
    /**************************************************************************
     * destroy
     **************************************************************************/
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        $array = ['message' => 'deleted_successfully'];
        $json = json_encode($array);
        return response($json, 200)->header('Content-Type', 'application/json');
    }

}
