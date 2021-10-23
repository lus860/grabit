<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RidersOrders extends Model
{

    protected $fillable=['rider_id'];

    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['rider_id']) && $request['rider_id']) {
            $item->rider_id = $request['rider_id'];
        }
        if (isset($request['order_id']) && $request['order_id']) {
            $item->order_id = $request['order_id'];
        }
        if (isset($request['courier_id']) && $request['courier_id']) {
            $item->courier_id = $request['courier_id'];
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }
}
