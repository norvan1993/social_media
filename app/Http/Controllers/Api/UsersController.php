<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;
use App\UserImage;
use App\Rules\CheckIsActive;
use App\Rules\CheckRoleId;
use Illuminate\Support\Facades\DB;
use App\Photo;


class UsersController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth:api', 'is_admin'])->only('store');
        $this->middleware(['auth:api', 'is_auth'])->only(['update', 'oldPassword', 'resetPassword']);
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
            'role_id' => ['required', new CheckRoleId],
            'is_active' => ['required', new CheckIsActive],
            'password' => 'required',
            'photo' => 'sometimes|required|image',
        ]);

        $input = $request->only(['name', 'is_active', 'role_id', 'email']);
        $input['password'] = bcrypt($request->password);

        if ($file = $request->file('photo')) {
            if ($file->isValid()) {
                $nameWithExtension = time() . random_int(100000, 1000000000) . '.' . $file->getClientOriginalExtension();
                $img = Image::make($file)->resize(100, 100);
                $img->save('./../storage/app/images/' . $nameWithExtension);
                $userImage = UserImage::create([' file ' => $nameWithExtension]);
                $input['user_image_id'] = $userImage->id;
            }
        }

        User::create($input);
    }

    /**************************************************************************
     * show
     **************************************************************************/
    public function show($id)
    {
        $userArray = DB::table('users')
            ->leftJoin('user_images', 'users.user_image_id', '=', 'user_images.id')
            ->where('users.id', '=', $id)->select('users.id', 'users.name', 'user_images.file')->distinct()->get()->toArray();

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
            'photo' => 'sometimes|required|image',

        ]);

        //check user id
        $user = User::findOrFail($id);
        $input = $request->only('name', 'is_active');
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

                $nameWithExtention = time() . random_int(100000, 1000000000) . '.' . $file->getClientOriginalExtension();
                $img = Image::make($file)->resize(100, 100);
                $img->save('./../storage/app/images/' . $nameWithExtention);
                if ($userImage = UserImage::find($user->user_image_id)) {
                    $user->delete_user_image();
                    $userImage->update(['file' => $nameWithExtention]);
                } else {
                    $userImage = UserImage::create(['file' => $nameWithExtention]);
                }
                $input['user_image_id'] = $userImage->id;
            }
        }
        //updating user
        $user->update($input);
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
}
