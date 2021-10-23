<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RestaurantUsers extends Model
{
    protected $fillable=['restaurant_id','otp','token'];

    public static function _save($request,$id=null){
        if (!$item = self::find($id)){
            $item = new self();
        }
        if (isset($request['login_email']) && $request['login_email']){
            $item->email = $request['login_email'];
        }
        if (isset($request['password']) && $request['password']){
            $item->password = bcrypt($request['password']);
        }
        if (isset($request['otp']) && $request['otp']){
            $item->otp = null;
        }
        if (!$id){
            $token = Str::random(60);
            $item->token = hash('sha256', $token);
        }
        if ($item->save()){
            return $item;
        }
        return false;
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
