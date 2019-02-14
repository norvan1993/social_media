<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function show($id)
    {
        $img = Image::make('./../storage/app/images/' . $id);
        return $img->response('jpg');
    }
}
