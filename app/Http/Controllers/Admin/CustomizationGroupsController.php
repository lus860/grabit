<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomizationGroup;
use App\Models\CustomizationValue;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CustomizationGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $data['title'] = 'Customization Groups';
        $data['groups'] = CustomizationGroup::all();
        return view('admin.setting.group.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $data['title'] = 'Add Group';
        $data['groups'] = [];
        $data['restaurants'] = Restaurant::all();
        return view('admin.setting.group.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $data = $request->all();
        $group = new CustomizationGroup();
        $group->name = $data['name'];
        $group->ctype = $data['ctype'];
        $group->restaurant_id = $data['restaurant_id'];
        $group->select_max = $data['max_selection'];
        if($group->save()){
            foreach($data['item_name'] as $key=>$item) {
                if(trim($item) != '') {
                    $customization = new CustomizationValue();
                    $customization->group_id = $group->id;
                    $customization->name = $item;
                    $customization->save();
                }
            }
        }
        return redirect('/backend/groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $group = CustomizationGroup::find($id);
        $data['title'] = $group->name;
        $data['group'] = $group;
        return view('admin.setting.group.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $group = CustomizationGroup::find($id);
        $data['title'] = "Edit ".$group->name;
        $data['group'] = $group;
        $data['restaurants'] = Restaurant::all();
        return view('admin.setting.group.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $data = $request->all();
        $group = CustomizationGroup::findOrFail($id);
        $group->name = $data['name'];
        $group->ctype = $data['ctype'];
        $group->select_max = $data['max_selection'];
        if($group->save()){
            CustomizationValue::where('group_id', $id)->delete();
            foreach($data['item_name'] as $key=>$item) {
                if(trim($item) != '') {
                    $customization = new CustomizationValue();
                    $customization->group_id = $group->id;
                    $customization->name = $item;
                    $customization->save();
                }
            }
        }
        return redirect('/backend/groups');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        CustomizationGroup::where('id', $id)->delete();
        CustomizationValue::where('group_id', $id)->delete();
        return redirect('/backend/groups');
    }
}
