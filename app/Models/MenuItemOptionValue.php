<?php

namespace App\Models;

use App\Traits\GetIncrement;
use App\Traits\InsertOrUpdate;
use Illuminate\Database\Eloquent\Model;

class MenuItemOptionValue extends Model
{
    use GetIncrement;
    use InsertOrUpdate;
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

    public function menuOption(){
        return $this->belongsTo(MenuItemOption::class,'option_id');
    }
}
