<?php

namespace App\Models;

use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\MenuItemOptionValue;
use App\Models\PendingOrders;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;

class AdminCenterNotification extends Model
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

        if (isset($request['vendor_offline']) && $request['vendor_offline']) {
            $item->vendor_offline = $request['vendor_offline'];
        }
        if (isset($request['product_not_available']) && $request['product_not_available']) {
            $item->product_not_available = $request['product_not_available'];
        }
        if (isset($request['overdue_order']) && $request['overdue_order']) {
            $item->overdue_order = $request['overdue_order'];
        }
        if (isset($request['status']) && $request['status']) {
            $item->status = $request['status'];
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor(){
        return $this->belongsTo(Restaurant::class,'vendor_offline','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu_item(){
        return $this->belongsTo(MenuItem::class,'item_not_available','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu_item_option(){
        return $this->belongsTo(MenuItemOptionValue::class,'product_not_available','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function get_overdue_order(){
        return $this->belongsTo(PendingOrders::class,'overdue_order','id');
    }
}
