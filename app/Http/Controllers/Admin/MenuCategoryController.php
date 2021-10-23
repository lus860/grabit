<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Models\City;
use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\Country;
use App\Models\SSJUtils;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
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
        $data['title'] = "Menu Category";
        $data['categories'] = MenuCategory::paginate(10);
        return view('admin.setting.menu_category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $data['title'] = "Add Category";
        $data['cities'] = City::all();
        return view('admin.setting.menu_category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $data = $request->all();
        $name = $data['name'];
        $icon = $data['icon'];
        $image = $this->lib->upload('image', $this->upload_dir, $request);
        $image_url = $image['url'];

        $cuisine = new MenuCategory();
        $cuisine->name = $name;
        $cuisine->icon = $icon;
        $cuisine->image = $image_url;
        $cuisine->save();

        return redirect(url('/backend/menu-categories'));

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
        $data['title'] = "Edit Category";
        $data['category'] = MenuCategory::find($id);
        return view('admin.setting.menu_category.edit', $data);
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
        $data = $request->all();
        $name = $data['name'];
        $icon = $data['icon'];

        $image = $this->lib->upload('image', $this->upload_dir, $request);

        $image_url = $image['url'];

        $cuisine = MenuCategory::find($id);
        $cuisine->icon = $icon;
        $cuisine->name = $name;
        if($image_url != '') {
            $cuisine->image = $image_url;
        }
        $cuisine->save();

        return redirect(url('/backend/menu-categories'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cuisine = MenuCategory::find($id);
        $cuisine->delete();
        return redirect(url('/backend/menu-categories'));
    }
}
