<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBranch extends Model
{
    protected $table = 'vendor_branches';

    public static function _save($branches,$id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return false;
            }
        } else {
            $item = new self();
        }

        if (isset($branches['vendor_id']) && $branches['vendor_id']) {
            $item->vendor_id = $branches['vendor_id'];
        }

        if (isset($branches['name']) && $branches['name']) {
            $item->name = $branches['name'];
        }


        if ($item->save()) {
            return  $item;
        }
        return false;
    }

}
