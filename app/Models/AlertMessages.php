<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertMessages extends Model
{
    public static function _save($request,$id=null){
        if ($id){
            if (!$item = self::find($id)){
                return false;
            }
        }else{
            $item=new self();
        }
        if (isset($request['users']) && $request['users']){
            $item->users=$request['users'];
        }
        if (isset($request['type']) && $request['type']){
            $item->type = $request['type'];
        }
        if (isset($request['title']) && $request['title']){
            $item->title = $request['title'];
        }
        if (isset($request['message']) && $request['message']){
            $item->message = $request['message'];
        }

        if ($item->save()){
            return $item;
        }
        return false;
    }
}
