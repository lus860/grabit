<?php

namespace App\Models;

use App\Traits\SetToken;
use Illuminate\Database\Eloquent\Model;

class Riders extends Model
{

    protected $fillable=['token'];

    public static function _save($request,$id=null){
        if (!$item = self::find($id)){
            $item = new self();
        }
        if (isset($request['name']) && $request['name']){
            $item->name = $request['name'];
        }
        if (isset($request['phone']) && $request['phone']){
            $item->phone = SSJUtils::add255($request['phone']);
        }
        if (isset($request['password']) && $request['password']){
            $item->password = bcrypt($request['password']);
        }
        if (isset($request['plate']) && $request['plate']){
            $item->plate = $request['plate'];
        }
        if (isset($request['username']) && $request['username']){
            $item->username = $request['username'];
        }
        if (isset($request['status']) && $request['status']){
            $item->status = $request['status'];
        }else{
            $item->status =0;
        }
        $item->token=SetToken::setToken();

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('api.error_message')['other_error']];
    }

    public function pending_orders(){
        return $this->morphedByMany(PendingOrders::class, RidersOrders::class);
    }

    public function source(){
        return $this->hasOne(ClientSources::class, 'rider_user','id');
    }

}
