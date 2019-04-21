<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Description;
use App\Rules\CheckAddingDescription;
use App\Rules\CheckUpdatingDescription;
use App\Photo;

class DescriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_viewable')->only('show');
        $this->middleware('is_auth')->only('update', 'destroy');
    }
    /***************************************************************************
     * store
     ****************************************************************************/
    public function store(Request $request)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'photo_id' => ['required', new CheckAddingDescription],
                'body' => ['required', 'string'],
            ]
        );
        //storing description
        $input = $request->only('photo_id', 'body');
        $descriptiion = Description::create($input);
        return $descriptiion;
    }
    /***************************************************************************
     * show
     ****************************************************************************/
    public function show($id)
    {
        return Description::findOrFail($id);
    }
    /***************************************************************************
     * update
     ****************************************************************************/
    public function update(Request $request, $id)
    {
        //validating request
        $validatedData = $request->validate(
            [
                'photo_id' => ['required', new CheckUpdatingDescription],
                'body' => ['required', 'string'],
            ]
        );
        //finding description
        $descriptiion = Description::findOrFail($id);

        //updating description
        $input = $request->only('photo_id', 'body');
        $descriptiion->update($input);
        return $descriptiion;
    }
    /***************************************************************************
     * destroy
     ****************************************************************************/
    public function destroy($id)
    {
        //deleting a description
        Description::findOrFail($id)->delete();
    }
    /**************************************************************************
     * photoDescription//via GET::api/photos/{photo}/description
     ***************************************************************************/
    public function photoDescription($id)
    {
        $photo = Photo::findOrFail($id);
        return $photo->description;
    }
}
