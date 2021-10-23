<?php

namespace App\Models;

use App\Models\Restaurant;
use App\Models\VendorBranch;
use Illuminate\Database\Eloquent\Model;


class Loyalty extends Model
{
    protected $fillable=['vendor_id'];

    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['vendor_id']) && $request['vendor_id'] && $request['vendor_id'] !='courier') {
            $item->vendor_id = $request['vendor_id'];
        }
        if (isset($request['spend']) && $request['spend']) {
            $item->spend = $request['spend'];
        }
        if (isset($request['redemption']) && $request['redemption']) {
            $item->redemption = $request['redemption'];
        }
        if (isset($request['saved_image']) && $request['saved_image']) {
            $item->image = $request['saved_image'];
        }
        if (isset($request['status']) && $request['status']) {
            $item->status =1;
        }else{
            $item->status =0;
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }

    public function vendor(){
        return $this->belongsTo(Restaurant::class,'vendor_id','id');
    }

    public function branches(){
        return $this->belongsTo(VendorBranch::class,'vendor_id','vendor_id');
    }

    public function branches_name(){
        $name = [];
        foreach ($this->branches()->get() as $branch){
            $name[] = $branch->name;
        }
        return $name;
    }
}
