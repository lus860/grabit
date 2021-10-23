<?php

namespace App\Models;

use App\Models\Notifications;
use App\Models\RatingsVendors;
use App\Models\Setting;
use App\Traits\TimeTrack;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PendingOrders extends Model
{
    use TimeTrack;
    protected $fillable=[
        'accept',
        'accept_message',
        'status',
        'seen',
        'admin_notification',
        'restaurant_notification',
        'rider_name',
        'action',
        'discount',
        'discounted_price',
        'collection_amount',
    ];


    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['user_id']) && $request['user_id']) {
            $item->user_id = $request['user_id'];
        }
        if (isset($request['vendor_id']) && $request['vendor_id']) {
            $item->vendor_id = $request['vendor_id'];
        }
        if (isset($request['address_id']) && $request['address_id']) {
            $item->address_id = $request['address_id'];
        }
        if (isset($request['order_from']) && $request['order_from']) {
            $item->order_from = $request['order_from'];
        }
        if (isset($request['order_type']) && $request['order_type']) {
            $item->order_type = $request['order_type'];
        }
        if (isset($request['schedule']) && $request['schedule']) {
            $item->schedule = $request['schedule'];
            if (isset($request['schedule_time'])){
                $item->schedule_time = ($request['schedule']==1)?null:$request['schedule_time'];
            }
        }
        if (isset($request['delivery_fee']) && $request['delivery_fee']) {
            $item->delivery_fee = $request['delivery_fee'];
        }
        if (isset($request['cooking_directions']) && $request['cooking_directions']) {
            $item->cooking_directions = $request['cooking_directions'];
        }
        if (isset($request['discount']) && ($request['discount'] || $request['discount']==0)) {
            $item->discount = $request['discount'];
        }
        if (isset($request['discounted_price']) && $request['discounted_price']) {
            $item->discounted_price = $request['discounted_price'];
        }
        if (isset($request['collection_amount']) && $request['collection_amount']) {
            $item->collection_amount = $request['collection_amount'];
        }
        if (isset($request['order_notes']) && $request['order_notes']) {
            $item->order_notes = $request['order_notes'];
        }
        if (isset($request['order_total']) && $request['order_total']) {
            $item->order_total = $request['order_total'];
        }
        if (isset($request['transaction_id']) && $request['transaction_id']) {
            $item->transaction_id = $request['transaction_id'];
        }
        if (isset($request['accept_message']) && $request['accept_message']) {
            $item->accept_message = $request['accept_message'];
        }
        if (isset($request['accept']) && $request['accept']) {
            $item->accept = $request['accept'];
        }
        if ($id){
            $item->due_in = TimeTrack::create(Restaurant::where('id',$item->vendor_id)->first()->preparation_time);
        }else if (isset($request['vendor_id']) && $request['vendor_id']) {
            $item->due_in = TimeTrack::create(Restaurant::where('id',$request['vendor_id'])->first()->preparation_time);
        }

        if (isset($request['payment']) && !empty($request['payment'])) {
            $item->payment = $request['payment'];
        }
        if (isset($request['order']) && !empty($request['order'])) {
            $item->action = $request['order'];
        }
        if (isset($request['status']) && !empty($request['status'])) {
            $item->status = $request['status'];
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function get_vendor(){
        return $this->belongsTo(Restaurant::class,'vendor_id','id');
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function notification(){
        return $this->hasOne(Notifications::class,'order_id');
    }

    public function statuses(){
        return $this->hasMany(StatusesHistory::class,'order_id');
    }

    public function ratings(){
        return $this->hasMany(RatingsVendors::class,'transaction_id','transaction_id');
    }

    public function get_dash_api(){
        return $this->hasOne(DashApi::class,'order','transaction_id');
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toDateTimeString();
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
