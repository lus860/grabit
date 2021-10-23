<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Mockery\Exception;

class AreasController extends Controller
{
    public function get_areas_for_cities(Request $request,$id){
        try {
            $area = Area::where('city_id',$id)->get();
            return response()->json($area);
        }catch (Exception $e){

        }
    }
}
