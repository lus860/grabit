<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table='role_user';

    public static function _save($request,$id=null){
        if (!$item = self::find($id)){
            $item = new self();
        }
        if (isset($request['user_id']) && $request['user_id']){
            $item->user_id = $request['user_id'];
        }
        if (isset($request['role_id']) && $request['role_id']){
            $item->role_id = $request['role_id'];
        }

        if ($item->save()){
            return $item;
        }
        return false;

    }
}
