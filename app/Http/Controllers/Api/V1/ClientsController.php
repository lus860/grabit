<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ClientSources;
use App\Http\Controllers\Api\ApiControllers;
use App\Models\RestaurantUsers;
use App\Services\AuthService;
use App\Services\EmailService;
use App\Services\SmsService;
use App\Traits\SetToken;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientsController extends ApiControllers
{
    public function __construct(ClientSources $model)
    {
        $this->model=$model;
        $this->name='client';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function checkClient(Request $request){
        $result = $this->validateData($request,[
            'clientID' => ['required'],
            'clientSecret' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $types = config('api_verify.types');
        $source_id=0;
        foreach ($types as $key =>$type){
            if ($request->clientID == $type['clientID'] && $request->clientSecret == $type['clientSecret']){
                $source_id = array_search ($key, config('api_verify.sources'));
            }
        }
        if (!$source_id){
            return $this->sendError('unauthenticated',401);
        }
        $token = SetToken::setToken();
        return $this->_save(['token'=>$token,'source_id'=>$source_id],null,true,['token'],['token'=>$token]);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function checkUser(Request $request){
        $data=[];
        self::$successMessageKeyName='existing_user';
        self::$errorMessageKeyName='existing_user';

        if ($request->input('phone_number')){
            $data['phone_number']=['required','integer'];
        }
        if ($request->input('email')){
            $data['email']=['required','email'];
        }
        if (empty($data)){
            return $this->sendError('not parameter',400);
        }
        $result = $this->validateData($request,$data);
        if (gettype($result) == 'object'){
            return $result;
        }
        $phone = $request->input('phone_number')??null;
        $email = $request->input('email')??'';
        $response=User::where(function ($query) use ($phone,$email){
            if ($phone){
                $query->where('phone',$phone);
            }
            if ($email){
                $query->orWhere('email',$email);
            }
        })->first();
        if ($response){
            $answer = AuthService::sendOtpForLogin($response);
            return $this->sendResponse(['otp'=>$answer->otp,'token'=>$answer->token],'data',1,200);
        }
        $answer = AuthService::sendOtpForRegister($request);
        return $this->sendResponse(['otp'=>$answer['data']->otp,'token'=>$answer['data']->token],'data',0,200);
    }

    public function user_firebase(Request $request){
        $result = $this->validateData($request,[
            'user_id' => ['required','integer'],
            'firebase_token' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $user=User::where(['id'=>$request->user_id,'token'=>$request->header('token-login')])->first();
        $restaurant_user=RestaurantUsers::where(['id'=>$request->user_id,'token'=>$request->header('token-login')])->first();
        $header_token = $request->header('token');

        if ($user){
            $old_user=ClientSources::where('user_id',$request->user_id)->first();
            if ($old_user){
                $old_user->update(['firebase_token'=>$request->firebase_token]);
                $answer=true;
            }else{
                $answer = ClientSources::where('token',$header_token)->update([
                    'user_id'=>$request->user_id,
                    'firebase_token'=>$request->firebase_token,
                ]);
            }
        }
        if ($restaurant_user){
            $old_user=ClientSources::where('restaurant_user',$request->user_id)->first();
            if ($old_user){
                $old_user->update(['firebase_token'=>$request->firebase_token]);
                $answer=true;
            }else{
                $answer = ClientSources::where('token',$header_token)->update([
                    'restaurant_user'=>$request->user_id,
                    'firebase_token'=>$request->firebase_token,
                ]);
            }
        }
        if (isset($answer) && $answer){
            return $this->sendResponse(null,null,1);
        }
        return $this->sendError(config('api.error_message')['unauthorised_user'],404);
    }
}
