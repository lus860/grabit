<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = ['id','cuisines_name','carrier_status'];

    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['carrier_name']) && $request['carrier_name']) {
            $item->carrier_name = $request['carrier_name'];
        }
        if (isset($request['km_price']) && ($request['km_price'] || $request['km_price'] === 0)) {
            $item->km_price = $request['km_price'];
        }
        if (isset($request['base_fare']) && ($request['base_fare'] || $request['base_fare'] === 0)) {
            $item->base_fare = $request['base_fare'];
        }
        if (isset($request['minimum_fare']) && ($request['minimum_fare'] || $request['minimum_fare'] === 0)) {
            $item->minimum_fare = $request['minimum_fare'];
        }
        if (isset($request['carrier_status']) && $request['carrier_status']) {
            $item->carrier_status = true;
        }else{
            $item->carrier_status = false;
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }
}
