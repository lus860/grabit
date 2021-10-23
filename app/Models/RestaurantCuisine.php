<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantCuisine extends Model
{
//    public $timestamps = false;
    protected $table = 'restaurant_cuisines';

    public static function _save($request,$id=null){
        if(isset($request['data']) && is_array($request['data'])){
            foreach ($request['data'] as $key=>$value){
                if($value != '') {
                    $item = new self();
                    $item->cuisine_id = $value;
                    $item->restaurant_id = $request['restaurant_id'];
                    $item->save();
                }
            }
            return true;
        }elseif(isset($request['data'])){
            $item = new self();
            $item->cuisine_id = $request['data'];
            $item->restaurant_id = $request['restaurant_id'];
            $item->save();
            return true;
        }
        return false;
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }
}
