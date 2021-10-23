<?php

namespace App\Models;

use App\Services\AuthService;
use Illuminate\Database\Eloquent\Model;

class DashApi extends Model
{
    protected $fillable=['status'];

    public static function _save($request,$id=null,$someElse=null,$className){
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }
        if (isset($someElse) && isset($someElse['user_id']) && $someElse['user_id']){
            $item->user_id = $someElse['user_id'];
        }
        if (isset($request->task_id) && $request->task_id){
            $item->task_id=$request->task_id;
        }
        if (isset($request->order) && $request->order){
            if($className == 'CourierOrders'){
                $item->courier_order = $request->order;
            }
            else{
                $item->order = $request->order;
            }
        }
        if (isset($someElse) && isset($someElse['token']) && $someElse['token']){
            $item->token = $someElse['token'];
        }
        if ($request->statuses && $request->statuses[0]->status_code){
            $item->status = $request->statuses[0]->status_code;
        }
        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function get_order(){
        return $this->belongsTo(PendingOrders::class,'order','transaction_id');
    }

    public function get_courier_order(){
        return $this->belongsTo(CourierOrders::class,'courier_order','transaction_id');
    }
}
