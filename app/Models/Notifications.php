<?php

namespace App\Models;

use App\Models\CourierOrders;
use App\Models\PendingOrders;
use App\Models\Restaurant;
use App\Traits\TimeTrack;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable=['dash_api','restaurant_notification'];

    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['order_id']) && $request['order_id']) {
            $item->order_id = $request['order_id'];
        }
        if (isset($request['admin_notification']) && $request['admin_notification']) {
            $item->admin_notification = $request['admin_notification'];
        }
        if (isset($request['restaurant_notification']) && $request['restaurant_notification']) {
            $item->restaurant_notification = $request['restaurant_notification'];
        }
        if (isset($request['dash_api']) && $request['dash_api']) {
            $item->dash_api = $request['dash_api'];
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }

    public function order(){
        return $this->belongsTo(PendingOrders::class,'order_id');
    }
    public function courier_order(){
        return $this->belongsTo(CourierOrders::class,'courier_order_id','id');
    }
}
