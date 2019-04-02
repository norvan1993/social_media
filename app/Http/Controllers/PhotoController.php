<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Post;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_viewable')->only('postPhotos');
    }
    public function show($id)
    {
        $img = Image::make('./../storage/app/images/' . $id);
        return $img->response('jpg');
    }
    /**************************************************************************
     * postPhotos//via GET:api/posts/{post}/photos
     **************************************************************************/
    public function postPhotos($id)
    {
        $json = json_encode(Post::findOrFail($id)->photos()->get()->toArray());
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
