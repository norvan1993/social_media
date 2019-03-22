<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{
    /****************************************************************************
     *variables
     ****************************************************************************/
    protected $fillable = ['photoable_id', 'photoable_type', 'file'];
    protected $path = '/images/';
    private $photoFile;
    private $photoName;
    public $oldHeight;
    public $oldWidth;

    /****************************************************************************
     *relations
     ****************************************************************************/
    //has one description
    public function description()
    {
        return $this->hasOne('App\Description');
    }
    //belongs to polymorphic parent
    public function photoable()
    {
        return $this->morphTo();
    }
    //has many polymorhic comments
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
    /****************************************************************************
     *model events
     ****************************************************************************/
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($photo) {

            $fullPath = './../storage/app/images/' . $photo->file;
            unlink($fullPath);
        });
    }
    /****************************************************************************
     *custom functions
     ****************************************************************************/
    //return  the url of photo
    public function imageWithFolder()
    {
        return '/photos/' . $this->file;
    }
    //handling the photo file--@return $this
    public function handleFile($file)
    {
        $this->photoFile = $file;
        return $this;
    }
    //naming the photo file--@return $this
    public function namingPhoto()
    {
        $this->photoName = time() . random_int(100000, 1000000000) . '.' . $this->photoFile->getClientOriginalExtension();
        return $this;
    }
    //saving photo to directory--@return $this
    public function savingPhotoToDir()
    {
        $img = Image::make($this->photoFile);
        $this->oldWidth = $img->width();
        $this->oldHeight = $img->height();
        $dimensions = $this->newDimensions($img->width(), $img->height());
        $img->resize($dimensions['newWidth'], $dimensions['newHeight']);
        $img->save('./../storage/app/images/' . $this->photoName);
        return $this;
    }
    //saving photo details to photos table--@return $this
    public function savingPhotoToDatabase($photoableType, $photoableId)
    {
        $this->file = $this->photoName;
        $this->photoable_type = $photoableType;
        $this->photoable_id = $photoableId;
        $this->save();
        return $this;
    }
    //isViewable
    public function isViewable()
    {
        //check if the authenticated user is admin
        if (Auth::user()->isAdmin()) {
            return true;
        }
        //check if the photoable is viewable
        if ($this->photoable->isViewable()) {
            return true;
        }
        //return false if it is not viewable
        return false;
    }
    //return the new dimension for resizing photo
    public function newDimensions($oldWidth, $oldHeight)
    {
        if ($oldWidth >= $oldHeight) {
            $factor = $oldWidth / 300;
            $newHeight = $oldHeight / $factor;
            $newWidth = 300;
        } else {
            $factor = $oldHeight / 300;
            $newWidth = $oldWidth / $factor;
            $newHeight = 300;
        }
        return [
            'newWidth' => $newWidth,
            'newHeight' => $newHeight
        ];
    }
}
