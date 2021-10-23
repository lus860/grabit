<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function checkUser($login)
    {
        $data['login'] = $login;
        $validator = Validator::make( $data,[
            'login' => 'required',
        ]);
        if($validator->fails()){
            return response($validator->messages(), 200);
        }
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $login;
            $response = User::where('email', $data['email'])->first();
        }else{
            if(strlen($login) == 10 && substr($login,0,1) == 0 ){
                $data['phone'] = '255'.substr($login, 1);
            }elseif(strlen($login) == 12 && substr($login,0,3) == 255){
                $data['phone'] = $login;
            }elseif(strlen($login) == 9 ){
                $data['phone'] = '255'.$login;
            }else{
                return 1;
            }
            $response = User::where('phone', $data['phone'])->first();
        }
        if ($response){
                AuthService::sendOtpForLogin($response);
                $response->status = 1;
                return $response;
        }else{
           $new_user = $this->register($data);
           if($new_user){
               $new_user->status = 0;
               return $new_user;
           }
            return false;
        }
        return false;
    }

    public function checkOpt($user_id, $otp, $name = null, $password = null)
    {
       $user = User::find($user_id);
        if($user->otp == $otp){
            if(!$user->name && !$user->password){
                $user->update([
                    'name'=>$name,
                    'password' => bcrypt($password),
                    'otp'=>null
                ]);
            }else{
                $user->update([
                    'otp'=>null
                ]);
            }
          return $user;
        }else{
            return 1;
        }

    }

    public function register($data)
    {
        $validator = $this->validator($data);
        if ($validator->passes()) {
            $user = $this->create($data);
            if ($user['success']){
                AuthService::sendMessagesWhenNewUserRegistered($user['data']);
                AuthService::sendOtpForLogin($user['data']);
                return $user['data'];
            }
            return false;
        }
        return false;
    }

    protected function create(array $data)
    {
        $user = [
            'is_activated' =>1,
        ];
        if (isset($data['email'])){
            $user['login_email']=$data['email'];
        }
        if (isset($data['phone'])){
            $user['phone']=$data['phone'];
        }
        return User::_save($user);

        return false;
    }

    protected function validator(array $data)
    {
        if (isset($data['email'])){
            $rules['email']='required|email|max:255|unique:users,email';
        }
        if (isset($data['phone'])){
            $rules['phone']='required|max:12|unique:users,phone';
        }
        return Validator::make($data, $rules);
    }
}
