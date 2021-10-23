<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorUsersHistory extends Model
{
    protected $table='vendor_users_history';

    protected $fillable=['vendor_id','source','token_login','firebase_token'];

    public static function _save($request, $id = null)
    {
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }

        if (isset($request['vendor_id']) && $request['vendor_id']) {
            $item->vendor_id = $request['vendor_id'];
        }

        if (isset($request['source']) && $request['source']) {
            $item->source = $request['source'];
        }

        if (isset($request['token_login']) && $request['token_login']) {
            $item->token_login = $request['token_login'];
        }

        if (isset($request['firebase_token']) && $request['firebase_token']) {
            $item->firebase_token = $request['firebase_token'];
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }
}
