<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VendorTypes extends Model
{
    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return false;
            }
        } else {
            $item = new self();
        }

        if (isset($request['vendor_name']) && $request['vendor_name']) {
            $item->vendor_name = $request['vendor_name'];
        }

        if (isset($request['image_name']) && $request['image_name']) {
            $item->image = $request['image_name'];
        }

        if ($item->save()) {
            return  $item;
        }
        return false;
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toDateTimeString();
    }
}
