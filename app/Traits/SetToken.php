<?php


namespace App\Traits;


use App\Models\Restaurant;
use App\Models\RestaurantUsers;
use App\Models\Riders;
use App\User;

trait SetToken
{
    public static function setToken(){
        $token = hash('sha256',uniqid('',true));
        $token_user = User::where('token',$token)->orWhere('remember_token',$token)->first();
        $token_restaurant = RestaurantUsers::where('token',$token)->first();
        $token_rider = Riders::where('token',$token)->first();
        if ($token_user || $token_restaurant || $token_rider){
            self::setToken();
        }
        return $token;
    }

    public static function setQrCode(){
        $qr_code = rand(1000,9999);
        $token_user = Restaurant::where('qr_code',$qr_code)->first();
        if ($token_user){
            self::setQrCode();
        }
        return $qr_code;
    }

}
