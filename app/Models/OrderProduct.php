<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_products';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'product_id');
    }

    public function getOptions()
    {
        /*$options = \GuzzleHttp\json_decode($this->options);

        foreach($options as $key=>$option){
            print_r($option);
        }*/
        return $this->options;
    }
}
