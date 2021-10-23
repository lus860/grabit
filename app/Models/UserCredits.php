<?php

namespace App\Models;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;

class UserCredits extends Model
{

    protected $fillable=['total_spend','available_credit','current_spend','used_credit'];

    public static function _save($order,$id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return false;
            }
        } else {
            $item = new self();
        }

        if (isset($order['user_id']) && $order['user_id']) {
            $item->user_id = $order['user_id'];
        }

        if (isset($order['vendor_id']) && $order['vendor_id']) {
            $item->vendor_id = $order['vendor_id'];
        }

        if (isset($order['current_spend']) && $order['current_spend']) {
            $item->current_spend = $order['current_spend'];
        }

        if (isset($order['available_credit']) && ($order['available_credit'] || $order['available_credit'] == 0)) {
            $item->available_credit = $order['available_credit'];
        }

        if (isset($order['total_spend']) && $order['total_spend']) {
            $item->total_spend = $order['total_spend'];
        }

        if (isset($order['used_credit']) && ($order['used_credit'] || $order['used_credit'] ==0)) {
            $item->used_credit = $order['used_credit'];
        }

        if ($item->save()) {
            return  $item;
        }
        return false;
    }

    public function vendor(){
        return $this->belongsTo(Restaurant::class,'vendor_id','id');
    }
}
