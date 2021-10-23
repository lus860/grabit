<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Restaurant;
use App\Models\VendorBranch;
use DB;

class CreditHistory extends Model
{
    protected $table='credits_history';


    public static function _save($request, $id = null)
    {
        if ($id){
            if (!$item = self::find($id)){
                return ['success'=>false,'data'=>config('errors.errors_save')['error_id']];
            }
        }else{
            $item=new self();
        }

        if (isset($request['transaction_id']) && $request['transaction_id']) {
            $item->transaction_id = $request['transaction_id'];
        }

        if (isset($request['user_id']) && $request['user_id']) {
            $item->user_id = $request['user_id'];
        }

        if (isset($request['vendor_id']) && $request['vendor_id']) {
            $item->vendor_id = $request['vendor_id'];
        }

        if (isset($request['amount']) && $request['amount']) {
            $item->amount = $request['amount'];
        }

        if (isset($request['txn_type']) && $request['txn_type']) {
            $item->txn_type = $request['txn_type'];
        }
        if (isset($request['vendor_type_id']) && $request['vendor_type_id']) {
            $item->vendor_type_id = $request['vendor_type_id'];
        }
        if (isset($request['branch_id']) && $request['branch_id']) {
            $item->branch_id = $request['branch_id'];
        }

        if ($item->save()){
            return ['success'=>true,'data'=>$item];
        }
        return ['success'=>false,'data'=>config('errors.errors_save')['error_conn']];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','');
    }

    public function vendor()
    {
        return $this->belongsTo(Restaurant::class,'vendor_id');

    }

    public function branche(){

        return $this->belongsTo(VendorBranch::class,'branch_id','id');
    }

    public function vendorBranch($vendor_id){

        return VendorBranch::where('vendor_id',$vendor_id)->get();

    }


    public function branches_name($vendor_id){
        $name = [];
        foreach ($this->vendorBranch($vendor_id) as $branch){
            $name[] = $branch->name;
        }
        return $name;
    }




}

