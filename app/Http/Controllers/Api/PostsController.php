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
use App\Rules\CheckDefaultPrivacy;
use Illuminate\Support\Facades\DB;
use App\User;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_auth')->only('update', 'updatePrivacy');
        $this->middleware('is_auth_or_admin')->only('delete');

    }
    /**************************************************************************
     * index
     **************************************************************************/
    public function index()
    {
        switch (Auth::user()->role_id) {
            case 1:
                return Post::paginate(10);
                break;

            default:
                return Post::viewablePosts();
                break;
        }

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
                'privacy' => ['required', 'json', new CheckDefaultPrivacy],
                'photos.*' => ['sometimes', 'required', 'image'],

            ]
        );
//selecting the direct inputs from the user
        $input = $request->only(['title', 'body']);
//setting user id
        $input['user_id'] = Auth::id();
//converting json request(privacy field) to array
        $array = json_decode($request->privacy, true);
//saving the status in post table(inside privacy column in posts table)
        $input['privacy'] = $array['status'];
//storing the post
        $postModel = Post::create($input);
//inserting  viewers id's in case of custom privacy
        if ($array['status'] == 'custom') {
            foreach ($array['id_list'] as $id) {
                DB::table('post_privacy')->insert(
                    ['post_id' => $postModel->id, 'viewer_id' => $id]
                );
            }
        }
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
        $postModel = Post::findOrFail($id);

//validate request
        $validatedData = $request->validate(
            [
                'title' => ['sometimes', 'required'],
                'body' => ['sometimes', 'required'],
                'privacy' => ['sometimes', 'required', new CheckDefaultPrivacy],
                'photos.*' => ['sometimes', 'required', 'image'],
                'deleted_photos' => ['sometimes', 'required', 'array'],
                'deleted_photos.*' => ['sometimes', 'required', new CheckDeletedPhotos],

            ]
        );
//set direct user inputs
        $input = $request->only('title', 'body');
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
    /**************************************************************************
     * updatePrivacy//via PUT:api/posts/{post}/update_privacy
     **************************************************************************/
    public function updatePrivacy(Request $request, $post)
    {
//validating request
        $validatedData = $request->validate(
            [
                'privacy' => ['required', 'json', new CheckDefaultPrivacy],
            ]
        );
//converting json request(privacy field) to array
        $array = json_decode($request->privacy, true);
//deleting old custom viewers
        $old_viewers = DB::table('post_privacy')->where('post_id', '=', $post);
        if ($old_viewers) {
            $old_viewers->delete();
        }
//inserting  viewers id's in case of custom privacy
        if ($array['status'] == 'custom') {
            foreach ($array['id_list'] as $id) {
                DB::table('post_privacy')->insert(
                    ['post_id' => $post, 'viewer_id' => $id]
                );
            }
        }
//saving the status in post table(inside privacy column in posts table)
        $input['privacy'] = $array['status'];

    }
    /**************************************************************************
     * userPosts//via GET:api/users/{user}/posts
     **************************************************************************/
    public function userPosts($userId)
    {
        return DB::table('posts')
            ->leftJoin('friend_requests AS sender', 'posts.user_id', '=', 'sender.sender_id')
            ->leftJoin('friend_requests AS receiver', 'posts.user_id', '=', 'receiver.receiver_id')
            ->leftJoin('post_privacy', 'posts.id', '=', 'post_privacy.post_id')
            ->where('posts.user_id', '=', $userId)
            ->where(function ($query1) {
                $query1->where('posts.privacy', '=', 'public')
                    ->orWhere('posts.privacy', '=', 'friends')
                    ->where(function ($query2) {
                        $query2->where('receiver.sender_id', '=', Auth::id())
                            ->where('receiver.status', '=', 'friend')
                            ->orWhere('sender.receiver_id', '=', Auth::id())
                            ->where('sender.status', '=', 'friend');
                    })
                    ->orWhere('post_privacy.viewer_id', '=', Auth::id());
            })
            ->select('posts.*')
            ->distinct()
            ->paginate(10);


    }


}
