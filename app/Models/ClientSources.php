<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSources extends Model
{
    protected $table='client_sources';

    protected $fillable=['restaurant_user','user_id','firebase_token','rider_user'];

    public static function _save($request,$id=null){
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }
        if (isset($request['source_id']) && $request['source_id']){
            $item->source_id=$request['source_id'];
        }
        if (isset($request['token']) && $request['token']){
            $item->token = $request['token'];
        }
        if (isset($request['user_id']) && $request['user_id']){
            $item->user_id = $request['user_id'];
        }
        if (isset($request['firebase_token']) && $request['firebase_token']){
            $item->firebase_token = $request['firebase_token'];
        }
        if (isset($request['restaurant_user']) && $request['restaurant_user']){
            $item->restaurant_user = $request['restaurant_user'];
        }
        if (isset($request['rider_user']) && $request['rider_user']){
            $item->rider_user = $request['rider_user'];
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }
}
