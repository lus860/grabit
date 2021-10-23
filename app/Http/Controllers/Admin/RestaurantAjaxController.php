<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomizationValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantAjaxController extends Controller
{
    public function get_customization_options_ajax(Request $request){
        $options = CustomizationValue::where('group_id', $request->id)->get();
        return response()->json($options);
    }
}
