<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function get_all_cities(){
        $cities = City::get();
        return response()->json($cities);
    }
}
