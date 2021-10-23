<?php

namespace App\Models;

use App\Models\ManageSubcategory;
use Illuminate\Database\Eloquent\Model;

class ManageCategory extends Model
{
    protected $table='manage_categories';

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
        if (isset($request['saved_image']) && $request['saved_image']){
            $item->image = $request['saved_image'];
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function sub_categories()
    {
        return $this->hasMany(ManageSubcategory::class, 'category_id','id');

    }

    public function sub_category_name(){
        $name = [];
        foreach ($this->sub_categories() as $item){
            $name[] = $item->name;
        }
        return $name;
    }

}
