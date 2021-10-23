<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiControllers;
use App\Traits\SetToken;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends ApiControllers
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $data['otp']=['required','max:4'];
        $data['token']=['required'];
        $result = $this->validateData($request,$data);
        if (gettype($result) == 'object'){
            return $result;
        }
        $user = $this->validateLogin($request);
        if ($user) {
            if ($user->is_activated){
                $user->update([
                    'otp'=>null,
                    'token'=>SetToken::setToken()
                ]);
                return $this->sendResponse([
                    'user_id'=>$user->id,
                    'name'=>$user->name,
                    'email'=>$user->email,
                    'phone'=>$user->phone,
                    'token'=>$user->token,
                ],'user','OK',200);
            }
            return $this->sendError(config('api.error_message')['user_not_registered'],200);
        }

        return $this->sendError(config('api.error_message')['user_login_error'],Response::HTTP_OK);

    }

    private function validateLogin(Request $request){
        $user = User::where(['otp'=>$request->otp,'token'=>$request->token])->first();
        if ($user){
            return $user;
        }
        return false;
    }
}
