<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use App\Models\ManageCategory;
use App\Models\Restaurant;
use App\Models\VendorTypes;
use Illuminate\Http\Request;

class VendorsController extends Controller
{
    public function get_vendors_for_areas(Request $request,$id){
        $vendors = Restaurant::where('area_id',$id)->get();
        $ids= [];
        if ($vendors->count()){
            foreach ($vendors as $vendor){
                $ids[]=$vendor->vendor_id;
            }
            $vendor_types = VendorTypes::whereIn('id',$ids)->get();
            if ($vendor_types->count()){
                return response()->json($vendor_types);
            }
        }
        return response()->json([]);
    }

    public function get_vendors_types(){
        $vendor_types = ManageCategory::get();
        return response()->json($vendor_types);

    }
}
