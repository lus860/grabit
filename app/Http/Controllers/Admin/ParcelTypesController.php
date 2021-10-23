<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParcelTypes;
use App\Models\Country;
use Illuminate\Http\Request;

class ParcelTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "ParcelTypes";
        $data['parcelTypes'] = ParcelTypes::paginate(10);
        return view('admin.setting.courier.parcel_type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $data['title'] = "Add ParcelTypes";
        $data['parcelTypes'] = ParcelTypes::all();
        return view('admin.setting.courier.parcel_type.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
            $parcelTypes = new ParcelTypes();
            $parcelTypes->parcel_name = $request->parcel_name;
            $parcelTypes->parcel_status = isset($request->parcel_status) ? 'true' : 'false';
            $parcelTypes->save();
        return redirect('/backend/parcel-type');
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
        $data['tittle'] = 'Parcel';
        $data['data'] = ParcelTypes::find($id);
        return view('admin.setting.courier.parcel_type.edit', $data);
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
        $data = ParcelTypes::find($id);
        $data->parcel_name = $request->parcel_name;
        $data->parcel_status = isset($request->parcel_status) ? 'true' : 'false';
        $data->save();
        return redirect('/backend/parcel-type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = ParcelTypes::find($id);
        $area->delete();
        return redirect(url('/backend/parcel-type'));
    }
}
