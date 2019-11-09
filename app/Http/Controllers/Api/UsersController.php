<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;

use App\Rules\CheckIsActive;
use App\Rules\CheckRoleId;
use Illuminate\Support\Facades\DB;
use App\Photo;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\json_encode;

class UsersController extends Controller
{
    public function __construct()
    {

        $this->middleware(['guest:api'])->only('store');
        $this->middleware(['auth:api', 'is_auth'])->only(['update', 'updateProfilePhoto', 'oldPassword', 'resetPassword', 'defaultPrivacy']);
        $this->middleware(['auth:api', 'is_admin'])->only(['updateAdmin']);
        $this->middleware(['auth:api', 'is_admin_or_auth'])->only(['destroy']);
    }
    /**************************************************************************
     * index
     **************************************************************************/
    public function index()
    {
        $json = json_encode(User::select('id', 'name')->paginate(10));
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * store
     **************************************************************************/
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'photo' => 'sometimes|image',
        ]);

        $input = $request->only(['name', 'email']);
        $input['password'] = bcrypt($request->password);
        $input['is_active'] = "1";
        $input['role_id'] = "2";
        $input['default_post_privacy'] = "public";
        $user = User::create($input);
        if ($file = $request->file('photo')) {
            if ($file->isValid()) {
                /**set new profile photo */
                $photo = new Photo();
                $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\User', $user->id);
                /** */
            }
        }
    }

    /**************************************************************************
     * show
     **************************************************************************/
    public function show($id)
    {
        $userArray = User::select('id', 'name')->where('id', '=', $id)->get()->toArray();
        $json = json_encode($userArray);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * update
     **************************************************************************/
    public function update(Request $request, $id)
    {
        //validating request
        $validatedData = $request->validate([
            'name' => 'sometimes|required',
            'is_active' => ['sometimes', 'required', new CheckIsActive],

        ]);

        //check user id
        $user = User::findOrFail($id);
        $input = $request->only('name', 'is_active');

        //updating user
        $user->update($input);
    }
    /**************************************************************************
     * updateProfilePhoto
     **************************************************************************/
    public function updateProfilePhoto(Request $request, $id)
    {
        //validating request
        $request->validate([
            'photo' => 'required|image',
        ]);

        //check user id
        $user = User::findOrFail($id);

        $photo = null;
        //check user photo(profile photo)
        if ($file = $request->file('photo')) {
            if ($file->isValid()) {
                /**delete old profile photo if exist*/
                if ($user->photo) {
                    $user->photo->delete();
                }
                /**set new profile photo */
                $photo = new Photo();
                $photo->handleFile($file)->namingPhoto()->savingPhotoToDir()->savingPhotoToDatabase('App\\User', $id);
                /** */
            }
        }
        $profilePhotoArray = array();
        $profilePhotoArray['photoName'] = $photo->file;
        $json = json_encode($profilePhotoArray);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     * destroy
     **************************************************************************/
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
    /**************************************************************************
     * oldPassword
     **************************************************************************/
    public function oldPassword(Request $request, $user)
    {

        //validating request
        $validatedData = $request->validate([
            'old_password' => ['required'],
        ]);
        //check user id
        $userModel = User::findOrFail($user);

        //check if the given password is correct
        if ($userModel->password == bcrypt($request->old_password)) {
            return "correct";
        }

        return "wrong";
    }

    /**************************************************************************
     * resetPassword
     **************************************************************************/
    public function resetPassword(Request $request, $user)
    {
        //validating request
        $validatedData = $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required'],
        ]);
        //check user id
        $userModel = User::findOrFail($user);

        //check if the given password is correct and set the new password
        if ($userModel->password == bcrypt($request->old_password)) {
            $userModel->password = $request->new_password;
            $userModel->save();
        }

        return "wrong";
    }
    /**************************************************************************
     * adminUpdate
     **************************************************************************/
    public function adminUpdate(Request $request, $user)
    {
        //validating request
        $validatedData = $request->validate([
            'is_active' => ['sometimes', 'required', new CheckIsActive],
            'role_id' => ['sometimes', 'required', new CheckRoleId],
        ]);
        //check user id
        $userModel = User::findOrFail($user);
        //update user
        $input = $request->only('is_active', 'role_id');
        $userModel->update($input);
    }
    /**************************************************************************
     * authId
     **************************************************************************/
    public function authId()
    {
        return Auth::id();
    }
    /**************************************************************************
     *getPrivacy
     **************************************************************************/
    public function defaultPrivacy()
    {
        $privacy['status'] = Auth::user()->default_post_privacy;
        if ($privacy['status'] == "custom") {
            $privacy['id_list'] = Auth::user()->default_viewers->pluck('viewer_id')->toArray();
        }
        $json = json_encode($privacy);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /**************************************************************************
     *getProfilePhoto
     **************************************************************************/
    public function getProfilePhoto($id)
    {
        if (User::find($id)->photo) {
            $profilePhotoArray = array();
            $profilePhotoArray['photoName'] = User::find($id)->photo->file;
            $json = json_encode($profilePhotoArray);
            return response($json, 200)->header('Content-Type', 'application/json');
        }
    }
}
