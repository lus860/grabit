<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorsRedemptionServices extends Model
{
    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['vendor_id']) && $request['vendor_id'] && $request['vendor_id'] !='courier') {
            $item->vendor_id = $request['vendor_id'];
        }

        if (isset($request['name']) && $request['name']) {
            $item->name = $request['name'];
        }


        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }
}
