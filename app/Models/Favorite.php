<?php

namespace App\Models;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use App\User;


class Favorite extends Model
{
    public static function _save($request,$id=null){
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }
        if (isset($request['user_id']) && $request['user_id']){
            $item->user_id = $request['user_id'];
        }

        if (isset($request['vendor_id']) && $request['vendor_id']){
            $item->restaurant_id = $request['vendor_id'];
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'id', 'restaurant_id');
    }

}
