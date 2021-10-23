<?php

namespace App\Http\Controllers\Vue;

use App\Http\Controllers\Controller;
use App\Models\WebImages;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function get_images_for_homepage(){
        $data['images_third'] = WebImages::where(['page'=>1,'name'=>1])->get();
        $data['images_fifth'] = WebImages::where(['page'=>1,'name'=>2])->get();
        return response()->json($data);
    }
}
