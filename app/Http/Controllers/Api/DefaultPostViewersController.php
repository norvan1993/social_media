<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\CheckDefaultPrivacy;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefaultPostViewersController extends Controller
{
    /*****************************************************************
     * setViewers  via PUT:/api/set_default_viewers
     *****************************************************************/

    public function setViewers(Request $request)
    {
        //request validating
        $validatedData = $request->validate([
            'default_post_privacy' => ['required', 'json', new CheckDefaultPrivacy],
        ]);

        //converting json request to array
        $array = json_decode($request->default_post_privacy, true);

        //deleting old custom viewers if exists
        $old_viewers = DB::table('default_post_privacy')->where('owner_id', '=', Auth::id());
        if ($old_viewers) {
            $old_viewers->delete();
        }

        //inserting new default viewers id's in case of custom privacy
        if ($array['status'] == 'custom') {
            foreach ($array['id_list'] as $id) {
                DB::table('default_post_privacy')->insert(
                    ['owner_id' => Auth::id(), 'viewer_id' => $id]
                );
            }
        }

        //saving the status in user table(inside default_post_privacy column)
        $user = User::find(Auth::id());
        $user->update(['default_post_privacy' => $array['status']]);

        //return successfull message to user
        $message = ['status' => 1, 'message' => 'you have set the default post privacy successfuly'];
        $json = json_encode($message);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
    /*****************************************************************
     * getViewers // via GET:/api/get_default_viewers
     *****************************************************************/
    public function getViewers()
    {
        $array = [];
        $array['status'] = Auth::user()->default_post_privacy;
        if ($array['status'] == 'custom') {
            $viewers = DB::table('default_post_privacy')->where('owner_id', '=', Auth::id())->pluck('viewer_id');
            $array['id_list'] = $viewers;
        }

        //return default viewers of post to user
        $json = json_encode($array);
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
