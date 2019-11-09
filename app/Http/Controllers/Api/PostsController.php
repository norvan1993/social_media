<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Auth;
use App\Photo;
use App\Rules\CheckDeletedPhoto;
use App\Rules\CheckDeletedPhotos;
use App\Rules\CheckDefaultPrivacy;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Rules\CheckBodyOfUpdatingPost;
use App\Rules\requireWithoutAllExceptBody;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_auth')->only('update', 'updatePrivacy');
        $this->middleware('is_admin_or_auth')->only('destroy');
        $this->middleware('is_viewable')->only('show');
        $this->middleware('is_not_blocked')->only('userPosts');
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
        $request->validate(
            [
                'title' => 'required',
                'body' => ['required_without:photos'],
                'privacy' => ['required', 'json', new CheckDefaultPrivacy],
                'photos.*' => ['required_without:body', 'image'],
            ]
        );
        //selecting the direct inputs from the user
        $input = $request->only(['title', 'body']);
        //setting user id
        $input['user_id'] = Auth::id();

        /*
         * converting json request(privacy field) to array
        privacy json structure
        {
            "status":"private|public|friends|custom",
            "id_list":[id1,id2,.......]

        }
         */
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
        $photo = null;
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $photo = new Photo;
                    $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\Post', $postModel->id);
                }
            }
        }
        //sending json message
        $json = json_encode(['status' => 1, 'message' => 'success', 'width' => $photo->oldWidth, 'height' => $photo->oldHeight]);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /*************************************************************************
     * show
     **************************************************************************/
    public function show($id)
    {
        $post = Post::select('id', 'user_id', 'title', 'body')->find($id);
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
                'title' => [new requireWithoutAllExceptBody($request)],
                'body' => [new requireWithoutAllExceptBody($request), new CheckBodyOfUpdatingPost($request, $id)],
                'photos' => [new requireWithoutAllExceptBody($request), 'array'],
                'photos.*' => ['image'],
                'deleted_photos' => [new requireWithoutAllExceptBody($request), 'array', new CheckDeletedPhotos($request, $id)],
                'deleted_photos.*' => [new CheckDeletedPhoto],

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
        if (isset($request->deleted_photos)) {
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
        User::findOrFail($userId);
        if (Auth::user()->role_id == 1 || Auth::id() == $userId) {
            return Post::where('user_id', '=', $userId)->orderBy('created_at', 'desc')->paginate(10);
        }
        return Post::viewablePostsOfUser($userId);
    }
}
