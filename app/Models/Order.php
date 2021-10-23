<?php

namespace App\Models;

use App\Models\OrderProduct;
use App\Models\Restaurant;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id',
        'order_date',
        'status',
        'product_id',
        'size',
        'img',
        'color',
        'quantity',
        'amount',
    ];

    public function products(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    public function users(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
    public function payment(){
        return $this->belongsTo(Payment::class);
    }
    public function getRider($rider_id){
        if($rider_id != null) {
            $user = User::find($rider_id);
            return $user->name.'['.$user->phone.']';
        }else{
            return 'N/A';
        }
    }
    public function orderProduct(){
        return $this->hasMany(OrderProduct::class);
    }

    /*public function customer(){
        return $this->belongsTo(User::class);
    }*/
}
