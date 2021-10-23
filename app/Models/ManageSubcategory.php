<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageSubcategory extends Model
{
    protected $table='manage_subcategory';

    public static function _save($request,$id=null){
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }
        if (isset($request['name']) && $request['name']){
            $item->name=$request['name'];
        }
        if (isset($request['category_id']) && $request['category_id']){
            $item->category_id=$request['category_id'];
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function category()
    {
        return $this->hasOne(ManageCategory::class, 'id', 'category_id');

    }


}
