<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class PhotoController extends Controller
{
    public function show($id)
    {
        $img = Image::make('./../storage/app/images/' . $id);
        return $img->response('jpg');
    }
}
