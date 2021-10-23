<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Models\City;
use App\Models\Cuisine;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\SSJUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;

class CuisineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $lib;
    public $upload_dir;
    public function __construct(){
        $this->lib = new SSJUtils();
//        $this->upload_dir = '/var/www/app.simbadesign.co.tz/html/mamboz/uploads/';
        $this->upload_dir = public_path().'/admin/uploads/';

    }

    public function index()
    {
        $data['title'] = "Cuisines";
        $data['cuisines'] = Cuisine::paginate(10);
        return view('admin.setting.cuisine.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $data['title'] = "Add Cuisine";
        $data['cities'] = City::all();
        return view('admin.setting.cuisine.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'saved_image' => ['required'],
            'image' => ['required','mimes:png'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $data = $request->all();
        $name = $data['name'];
        $image_url = $request->saved_image;

        $cuisine = new Cuisine();
        $cuisine->name = $name;
        $cuisine->image = $image_url;
        $cuisine->is_top = isset($data['top'])? 1 :0;
        $cuisine->save();

        return redirect(url('/backend/cuisines'));

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
        $data['title'] = "Edit Cuisine";
        $data['cuisine'] = Cuisine::find($id);
        return view('admin.setting.cuisine.edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $data = $request->all();
        $name = $data['name'];
        $image_url = '';
        if (isset($request->image)){
            $validatedData = Validator::make($request->all(),[
                'image' => ['required','mimes:png'],
                'saved_image' => ['required'],
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            $image_url = $request->saved_image;
        }

        $cuisine = Cuisine::find($id);
        $cuisine->name = $name;
        $cuisine->is_top = isset($data['top'])? 1 :0;

        if($image_url != '') {
            if ($cuisine->image){
                $oldimage=explode('/',$cuisine->image);
                $oldimage=array_last($oldimage);
            }
            if(isset($cuisine->image)&& $cuisine->image && File::exists(public_path().'/admin/uploads/'.$oldimage)) {
                File::delete(public_path().'/admin/uploads/'.$oldimage);
            }
            $cuisine->image = $image_url;
        }
        $cuisine->save();

        return redirect(url('/backend/cuisines'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cuisine = Cuisine::find($id);
        $cuisine->delete();
        return redirect(url('/backend/cuisines'));
    }
}
