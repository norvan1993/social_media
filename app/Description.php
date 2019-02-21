<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    /*********************************************************************************
    * variables
    *********************************************************************************/
    protected $fillable = ['photo_id', 'body'];
    /*********************************************************************************
    * relations
    *********************************************************************************/
    //belongs to photo
    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }
    /*********************************************************************************
    * custom functions
    *********************************************************************************/
    public function isViewable()
    {
        $post = $this->photo->photoable;
        if ($post->isViewable()) {
            return true;
        }
        return false;
    }
}
