<?php


namespace App\Services;

use App\Models\ClientSources;
use App\Models\Restaurant;
use App\Models\RestaurantUsers;
use App\Models\Riders;
use App\Models\VendorUsersHistory;
use App\Traits\SetToken;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * @param null $token
     * @return mixed
     */
    public static function getUser($token=null){
        $token_login=(!empty($token))?$token: \Illuminate\Support\Facades\Request::header('token-login');
        if (gettype($token_login) =='object'){
            $token_login=$token_login->header('token-login');
        }
        $user=User::where('token',$token_login)->first();
        return $user?$user->toArray():null;
    }

    public static function getRiderUser($token=null){
        $token_login=(!empty($token))?$token: \Illuminate\Support\Facades\Request::header('token-login');
        if (gettype($token_login) =='object'){
            $token_login=$token_login->header('token-login');
        }
        $user=Riders::where('token',$token_login)->first();
        return $user?$user:null;
    }

    /**
     * @param null $token
     * @return mixed
     */
    public static function getRestaurantUser($token=null){
        $token_login=(!empty($token))?$token: \Illuminate\Support\Facades\Request::header('token-login');
        if (gettype($token_login) =='object'){
            $token_login=$token_login->header('token-login');
        }
        return VendorUsersHistory::where('token_login',$token_login)->first()->toArray();
    }
    /**
     * @param Request $request
     * @return array
     */
    public static function loginRestaurantUser(Request $request){
        $user = RestaurantUsers::where('email',$request->email)->first();
        if (!empty($user)){
            if (password_verify($request->password,$user->password)){
                $user->update(['token'=>SetToken::setToken()]);

                return  ['success'=>true,'data'=>[
                    'user_id'=>$user->id,
                    'vendor_id'=>$user->restaurant_id,
                    'login_token'=>$user->token
                ]
                ];
            }
            return ['success'=>false,'data'=>config('errors')['password_incorrect']];
        }
        return ['success'=>false,'data'=>config('errors')['no_user']];
    }

    /**
     * @param Request $request
     * @return array|bool[]
     */
    public static function forgetPasswordRestaurantUser(Request $request){
        $user = RestaurantUsers::where('email',$request->email)->first();
        if (!empty($user)){
            $answer = self::getOtp(RestaurantUsers::class,$user);
            EmailService::sendEmailWhenForgetPasswordRestaurantUser($user,$user->restaurant,$answer->otp);
            return  ['success'=>true,'data'=>true];
        }
        return ['success'=>false,'data'=>config('errors')['no_user']];
    }

    /**
     * @param Request $request
     * @return array|bool[]
     */
    public static function setNewPasswordRestaurantUser(Request $request){
        $user = RestaurantUsers::where('otp',$request->otp)->first();
        if (!empty($user)){
            $newUser = RestaurantUsers::_save($request,$user->id);
            if ($newUser){
                return  ['success'=>true,'data'=>true];
            }
        }
        return ['success'=>false,'data'=>config('errors')['error_otp']];
    }

    /**
     * @param User $user
     */
    public static function sendMessagesWhenNewUserRegistered(User $user){
        if ($user->email){
            EmailService::sendEmailWhenNewUserRegistered($user);
        }
    }

    /**
     * @param $model
     * @param $item
     * @return int
     */
    public static function getOtp($model,$item=null){
        $otp = mt_rand(1000,9999);
        $answer = $model::where('otp',$otp)->first();
        if (empty($answer)){
            if ($item){
                $item->update(['otp'=>$otp]);
                return $item;
            }
            $newItem = $model::_save(['otp'=>$otp]);
            return $newItem;
        }
        return self::getOtp($model,$item);
    }

    /**
     * @param Request $request
     * @return int
     */
    public static function sendOtpForRegister(Request $request){
        $answer = self::getOtp(User::class);

        if ($request->input('phone_number')){
            SmsService::sendOtp($answer['data']->otp,$request->input('phone_number'));
        }
        if ($request->input('email')){
            $user = (object)[
                'email'=>$request->input('email'),
                'name'=>null
            ];
            EmailService::sendEmailUserForOtp($user,$answer['data']->otp,2);
        }
        return $answer;
    }

    /**
     * @param User $user
     * @return int
     */
    public static function sendOtpForLogin(User $user){
        $answer = self::getOtp(User::class,$user);
        if ($user->phone){
            SmsService::sendOtp($answer->otp,$user->phone);
        }
        if ($user->email){
            $user= (object)[
                'email'=>$user->email,
                'name'=>$user->name??null
            ];

            EmailService::sendEmailUserForOtp($user,$answer->otp,1);
        }
        return $answer;
    }

}
