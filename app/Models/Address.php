<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;

class Address extends Model
{
    protected $table = 'address';

    public static function _save($request,$id=null){
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }
        if (isset($request['user_id']) && $request['user_id']){
            $item->user_id=$request['user_id'];
        }
        if (isset($request['city']) && $request['city']){
            $item->city_id = $request['city'];
        }
        if (isset($request['area']) && $request['area']){
            $item->area_id = $request['area'];
        }
        if (isset($request['address_type']) && $request['address_type']){
            $item->address_type = $request['address_type'];
        }
        if (isset($request['line_1']) && $request['line_1']){
            $item->line_1 = $request['line_1'];
        }
        if (isset($request['line_2']) && $request['line_2']){
            $item->line_2 = $request['line_2'];
        }
        if (isset($request['landmark']) && $request['landmark']){
            $item->landmark = $request['landmark'];
        }
        if (isset($request['longitude']) && $request['longitude']){
            $item->longitude = $request['longitude'];
        }
        if (isset($request['latitude']) && $request['latitude']){
            $item->latitude = $request['latitude'];
        }
        if (isset($request['is_default']) && !empty($request['is_default'])){
            if ((gettype($request['is_default']) == 'boolean' && $request['is_default']) || $request['is_default'] == 'true'){
                Address::where('user_id',$request['user_id'])->update(['is_default'=>0]);
            }
            $item->is_default = ((gettype($request['is_default']) == 'boolean' && $request['is_default']) || $request['is_default'] == 'true')?1:0;

        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getCountry()
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
