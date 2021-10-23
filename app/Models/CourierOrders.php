<?php

namespace App\Models;

use App\Models\Notifications;
use App\User;
use App\Models\RestaurantUsers;
use Illuminate\Database\Eloquent\Model;

class CourierOrders extends Model
{
    protected $fillable = ['pick_up_address', 'pick_up_area', 'pick_up_city', 'pick_up_latitude',
        'pick_up_longitude', 'pick_up_information', 'delivery_address', 'delivery_area',
        'delivery_city', 'delivery_information', 'delivery_latitude', 'delivery_longitude', 'distance',
        'price', 'weight', 'order_type', 'status_text', 'status', 'seen','user_id','parcel_type','carrier','comments','payment','rider_name','restaurant_user_id'];

    public function notification()
    {
        return $this->hasOne(Notifications::class, 'courier_order_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function deliveryCity(){
        return $this->belongsTo(City::class, 'delivery_city');
    }
    public function deliveryArea(){
        return $this->belongsTo(Area::class, 'delivery_area');
    }

    public function pickUpCity(){
        return $this->belongsTo(City::class, 'pick_up_city');
    }
    public function pickUpArea(){
        return $this->belongsTo(Area::class, 'pick_up_area');
    }
    public function carrierRelation(){
        return $this->belongsTo(Carrier::class, 'carrier');
    }
    public function parcelType(){
        return $this->belongsTo(ParcelTypes::class, 'parcel_type');
    }

    public function vendor_user(){
        return $this->belongsTo(RestaurantUsers::class,'restaurant_user_id');
    }

    public static function _save($request,$id=null){
        if (!$item = self::find($id)){
            $item = new self();
        }
        if (isset($request['user_id']) && $request['user_id']){
            $item->user_id = $request['user_id'];
        }
        if (isset($request['restaurant_user_id']) && $request['restaurant_user_id']){
            $item->restaurant_user_id = $request['restaurant_user_id'];
        }
        if (isset($request['status']) && $request['status']){
            $item->status = $request['status'];
        }
        if (isset($request['pick_up_address']) && $request['pick_up_address']){
            $item->pick_up_address = $request['pick_up_address'];
        }
        if (isset($request['pick_up_area']) && $request['pick_up_area']){
            $item->pick_up_area = $request['pick_up_area'];
        }
        if (isset($request['pick_up_city']) && $request['pick_up_city']){
            $item->pick_up_city = $request['pick_up_city'];
        }
        if (isset($request['pick_up_latitude']) && $request['pick_up_latitude']){
            $item->pick_up_latitude = $request['pick_up_latitude'];
        }
        if (isset($request['pick_up_longitude']) && $request['pick_up_longitude']){
            $item->pick_up_longitude = $request['pick_up_longitude'];
        }
        if (isset($request['pick_up_information']) && $request['pick_up_information']){
            $item->pick_up_information = $request['pick_up_information'];
        }
        if (isset($request['delivery_address']) && $request['delivery_address']){
            $item->delivery_address = $request['delivery_address'];
        }
        if (isset($request['delivery_area']) && $request['delivery_area']){
            $item->delivery_area = $request['delivery_area'];
        }
        if (isset($request['delivery_city']) && $request['delivery_city']){
            $item->delivery_city = $request['delivery_city'];
        }
        if (isset($request['delivery_information']) && $request['delivery_information']){
            $item->delivery_information = $request['delivery_information'];
        }
        if (isset($request['delivery_latitude']) && $request['delivery_latitude']){
            $item->delivery_latitude = $request['delivery_latitude'];
        }
        if (isset($request['delivery_longitude']) && $request['delivery_longitude']){
            $item->delivery_longitude = $request['delivery_longitude'];
        }
        if (isset($request['distance']) && $request['distance']){
            $item->distance = $request['distance'];
        }
        if (isset($request['price']) && $request['price']){
            $item->price = $request['price'];
        }
        if (isset($request['weight']) && $request['weight']){
            $item->weight = $request['weight'];
        }
        if (isset($request['carrier']) && $request['carrier']){
            $item->carrier = $request['carrier'];
        }
        if (isset($request['parcel_type']) && $request['parcel_type']){
            $item->parcel_type = $request['parcel_type'];
        }
        if (isset($request['comments']) && $request['comments']){
            $item->comments = $request['comments'];
        }
        if (isset($request['payment']) && $request['payment']){
            $item->payment = $request['payment'];
        }
        if (isset($request['envelope']) && $request['envelope']){
            $item->envelope = $request['envelope'];
        }

        if ($item->save()){
            return $item;
        }
        return false;
    }

    public function get_dash_api(){
        return $this->hasOne(DashApi::class,'courier_order','transaction_id');
    }

    public function riders()
    {
        return $this->morphToMany(Riders::class, RidersOrders::class);
    }

    public function rider()
    {
        return $this->belongsTo(Riders::class, 'rider_name','id');
    }

}
