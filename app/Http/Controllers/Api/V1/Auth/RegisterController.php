<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiControllers;
use App\Mail\Activate;
use App\Services\AuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends ApiControllers
{


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|max:255',
            'otp' => 'required|max:4',
            'token' => 'required',
        ];
        if (isset($data['email'])){
            $rules['email']='required|email|max:255|unique:users,email';
        }
        if (isset($data['phone'])){
            $rules['phone']='required|max:12|unique:users,phone';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return mixed
     */
    protected function create(array $data)
    {
        $user = [
            'name' => $data['name'],
            'password' => bcrypt($data['password']),
            'is_activated' =>1,
        ];
        if (isset($data['email'])){
            $user['login_email']=$data['email'];
        }
        if (isset($data['phone'])){
            $user['phone']=$data['phone'];
        }
        $new_user = User::where(['otp'=>$data['otp'],'token'=>$data['token']])->first();
        if ($new_user){
            $client = new \GuzzleHttp\Client();
            $url='https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$user['phone'];
            $response = $client->get($url);
            $response = json_decode($response->getBody());
            $user['qr_code'] = $url;
            $user['otp']=null;
            return User::_save($user,$new_user->id);
        }
        return false;
    }

    /**
     * Register new user
     *
     * @param  array $data
     * @return User
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->passes()) {
            $user = $this->create($request->all());
            if ($user['success']){
                AuthService::sendMessagesWhenNewUserRegistered($user['data']);

                return $this->sendResponse([
                    'user_id'=>$user['data']->id,
                    'name'=>$user['data']->name,
                    'email'=>$user['data']->email,
                    'phone'=>$user['data']->phone,
                    'token'=>$user['data']->token,
                ],'user',"OK",200);
            }
            return $this->sendError(config('api.error_message')['user_register_error'],200);
        }
        return $this->sendError(0,Response::HTTP_OK,[$validator->errors()]);
    }
}
