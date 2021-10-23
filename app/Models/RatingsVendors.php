<?php

namespace App\Models;

use App\Models\CourierOrders;
use App\Models\PendingOrders;
use App\Models\Restaurant;
use App\User;
use Illuminate\Database\Eloquent\Model;

class RatingsVendors extends Model
{
//    protected $appends = ['VendorRatingMessage'];

    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['vendor_id']) && $request['vendor_id']) {
            $item->vendor_id = $request['vendor_id'];
        }
        if (isset($request['user_id']) && $request['user_id']) {
            $item->user_id = $request['user_id'];
        }
        if (isset($request['transaction_id']) && $request['transaction_id']) {
            $item->transaction_id = $request['transaction_id'];
        }
        if (isset($request['delivery_rating']) && $request['delivery_rating']) {
            $item->delivery_rating = $request['delivery_rating'];
        }
        if (isset($request['vendor_rating']) && $request['vendor_rating']) {
            $item->vendor_rating = $request['vendor_rating'];
        }
        if (isset($request['delivery_rating_message']) && $request['delivery_rating_message']) {
            $item->delivery_rating_message = $request['delivery_rating_message'];
        }
        if (isset($request['vendor_rating_message']) && $request['vendor_rating_message']) {
            $item->vendor_rating_message = $request['vendor_rating_message'];
        }

        if ($item->save()) {
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function getDeliveryRatingMessageAttribute()
    {
        return json_decode($this->attributes['delivery_rating_message'], 1);
    }


    public function getVendorRatingMessageAttribute()
    {
        return json_decode($this->attributes['vendor_rating_message'], 1);

    }

    public function user(){
        return $this->belongsTo(User::class);
    }

//    public function vendor_type(){
//        return $this->belongsTo(Ve::class);
//    }

    public function vendor(){
        return $this->belongsTo(Restaurant::class,'vendor_id','id');
    }

    public function order(){
        return $this->belongsTo(PendingOrders::class,'transaction_id','transaction_id');
    }

    public function courier_order(){
        return $this->belongsTo(CourierOrders::class,'transaction_id','transaction_id');
    }

}
