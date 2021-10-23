<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\RestaurantServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "Areas";
        $data['areas'] = Area::paginate(10);
        return view('admin.setting.area.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $data['title'] = "Add Areas";
        $data['cities'] = City::all();
        return view('admin.setting.area.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $data = $request->all();
        $city_id = $data['city_id'];
        foreach($data['area_name'] as $key=>$val) {
            $area = new Area();
            $area->city_id = $city_id;
            $area->name = $val;
            $area->save();
        }
        return redirect(url('/backend/areas'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = Area::find($id);
        $area->delete();
        return redirect(url('/backend/areas'));
    }

    public function get_country(Request $request){
        $data = [];
        $country = Country::where('id', $request->id)->first();
        $data['country_id'] = $country->id;
        $data['country_name'] = $country->name;

        $all_cities = City::where('country_id', $country->id)->get();
        $cities = [];
        foreach($all_cities as $key=>$city){
            $cities[$key]['id'] = $city->id;
            $cities[$key]['name'] = $city->name;
            $cities[$key]['city_id'] = $city->id;
            $cities[$key]['city_name'] = $city->name;
            $areas = [];

            $all_areas = Area::where('city_id', $city->id)->get();
            foreach($all_areas as $key2=>$area){
                $areas[$key2]['id'] = $area->id;
                $areas[$key2]['name'] = $area->name;
            }
            $cities[$key]['areas'] = $areas;
        }

        $data['cities'] = $cities;
        return response()->json(['success'=>1, 'data'=>$data]);
    }
    public function get_city(Request $request){
        $data = [];
        $city_id = $request->id;
        //$city = City::where('name', 'like', "%$city_id%")->orWhere('id', $city_id)->first();
        if(is_int($city_id)) {
            $city = City::where('id', $city_id)->first();
        }else{
            //$keyword = urldecode($city_id);
            //$city = City::where('name', 'like', "%$city_id%")->first();
            $city_id = strtolower($city_id)=='dar es salam'?'dar es salaam':$city_id;
            $the_city = City::where('name', 'like', "%$city_id%")->first();
            $the_city = DB::select("select * from `cities` where `name` like '%$city_id%'");
            if(!empty($the_city)) {
                //$the_city_id = $the_city->id;
                $city = City::where('id', $the_city[0]->id)->first();
            }else{
                $city = new \stdClass();
            }
        }

        if(!empty($city)) {
            $data['id'] = $city->id;
            $data['name'] = $city->name;
            $areas = [];

            $all_areas = Area::where('city_id', $city->id)->get();
            foreach ($all_areas as $key2 => $area) {
                $areas[$key2]['id'] = $area->id;
                $areas[$key2]['name'] = $area->name;
            }
            $data['areas'] = $areas;
            return response()->json(['success'=>1, 'data'=>$data]);
        }else{
            return response()->json(['success'=>0, 'data'=>[
                'areas'=>new \stdClass(),
                'id'=>null,
                'name'=>null
            ]]);
        }
    }
    public function get_area(Request $request){
        $area = Area::where('id', $request->id)->get();
        return response()->json(['success'=>1, 'data'=>$area]);
    }
}
