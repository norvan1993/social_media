<?php

namespace App;

use Illuminate\Http\Request;



class ModelItem
{
    /****************************************************************
     * variables
     ***************************************************************/
    protected $request;

    /****************************************************************
     * construct magic method
     ***************************************************************/
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /***************************************************************
     * modelName
     ***************************************************************/
    protected function modelName()
    {
        foreach ($this->paths() as $path => $modelName) {
            if ($this->request->is($path)) {
                return $modelName;
            }
        }
    }
    /***************************************************************
     * paths
     ***************************************************************/
    protected function paths()
    {
        return [
            'api/users/*' => 'User',
            'api/posts/*' => 'Post',
            'api/comments/*' => 'Comment',
            'api/descriptions/*' => 'Description',
            'api/replies/*' => 'Reply'
        ];
    }
    /**************************************************************
     * routeParam
     **************************************************************/
    protected function routeParam()
    {
        $paramName = lcfirst($this->modelName());
        return $this->request->route($paramName);
    }
    /**************************************************************
     * modelItem
     **************************************************************/
    public function getItem()
    {
        $modelName = 'App\\' . $this->modelName();
        $model = new $modelName;
        return $model->findOrFail($this->routeParam());
    }
    /**************************************************************
     * itemOwnerId
     **************************************************************/
    public function getOwnerId()
    {
        //getting model item 
        $item = $this->getItem();
        $itemOwnerId = null;

        //getting item owner id
        switch ($this->modelName()) {
            case 'User':
                $itemOwnerId = $item->id;
                break;
            case 'Description':
                $itemOwnerId = $item->photo->photoable->user_id;
                break;
            default:
                $itemOwnerId = $item->user_id;
                break;
        }
        //return item owner id
        return $itemOwnerId;
    }
    /****************************************************************
     *
     ***************************************************************/
}
