<?php

namespace App;

use App\Models\Address;
use App\Models\ClientSources;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\SSJUtils;
use App\Traits\SetToken;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens,SetToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'avatar', 'email', 'password', 'is_activated', 'user_type','otp','token','remember_token','phone','notification'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function _save($request,$id=null){
        if (!$item = self::find($id)){
            $item = new self();
        }
        if (isset($request['name']) && $request['name']){
            $item->name = $request['name'];
        }
        if (isset($request['login_email']) && $request['login_email']){
            $item->email = $request['login_email'];
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
        if (isset($request['otp']) && $request['otp']){
            $item->otp = $request['otp'];
        }else{
            $item->otp = null;
        }
        if (isset($request['is_activated']) && $request['is_activated']){
            $item->is_activated = $request['is_activated'];
        }
        if (isset($request['qr_code']) && $request['qr_code']){
            $item->qr_code = $request['qr_code'];
        }
        if (isset($request['user_type']) && $request['user_type']){
            $role_id = $request['user_type'];
            $item->user_type = $request['user_type'];
        }else{
            $role_id = 2;
            $item->user_type=2;
        }
        $item->token=SetToken::setToken();

        if ($item->save()){
            $role=['user_id'=>$item->id,'role_id'=>$role_id];
            if (RoleUser::_save($role,$id)){
                return ['success'=>true,'data'=>$item];
            }
        }
        return ['success'=>false,'data'=>config('api.error_message')['other_error']];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    public function getOrigin()
    {
        return $this->origin == 1 ? 'IOS' : 'Android';
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function source(){
        return $this->hasOne(ClientSources::class,'user_id','id');
    }

    public static function hasRole($role){
        dd();
    }

}
