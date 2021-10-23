<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantEmail extends Model
{
    protected $table = 'restaurant_emails';
//    public $timestamps = false;

    public static function _save($request,$id=null){
        if(isset($request['emails']) && is_array($request['emails'])){
            foreach ($request['emails'] as $key=>$value){
                if($value != '') {
                    $email = new self();
                    $email->email = $value;
                    $email->restaurant_id = $request['restaurant_id'];
                    $email->save();
                }
            }
            return true;
        }elseif(isset($request['emails'])){
            $email = new self();
            $email->email = $request['emails'];
            $email->restaurant_id = $request['restaurant_id'];
            $email->save();
            return true;
        }
        return false;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
