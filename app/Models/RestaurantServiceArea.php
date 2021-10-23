<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantServiceArea extends Model
{
    protected $fillable = ['area_id','restaurant_id'];

    public static function _save($request,$id=null){
        if(isset($request['areas']) && is_array($request['areas'])){
            foreach ($request['areas'] as $key=>$value){
                if($value != '') {
                    $area = new self();
                    $area->area_id = $value;
                    $area->restaurant_id = $request['restaurant_id'];
                    $area->save();
                }
            }
            return true;
        }elseif(isset($request['areas'])){
            $area = new self();
            $area->area_id = $request['areas'];
            $area->restaurant_id = $request['restaurant_id'];
            $area->save();
            return true;
        }
        return false;
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
