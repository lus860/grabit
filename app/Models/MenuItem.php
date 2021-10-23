<?php

namespace App\Models;

use App\Traits\GetIncrement;
use App\Traits\InsertOrUpdate;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    use GetIncrement;
    use InsertOrUpdate;

    public $timestamps = false;
    protected $table = 'menu_items';

    public static function _save($request,$id=null){
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }
        if (isset($request['status']) && $request['status']){
            $item->status = 1;
        }else{
            $item->status = 0;
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['other_error']];

    }

    public function Menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function options(){
        return $this->hasMany(MenuItemOption::class, 'item_id');
    }

    public function price()
    {
        return $this->hasOne(MenuItemPrice::class, 'item_id');
    }

    public function getType()
    {
        return $this->item_type == 1 ? 'Veg' : 'Non-Veg';
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

}
