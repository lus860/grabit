<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ImageController;
use App\Models\Day;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use \Exception;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'Menus',
            'restaurants' => Restaurant::adminList(),
            'current_restaurant_id' => $request->query('vendor_id'),
        ];
        $data['menus'] = Menu::adminList($data['current_restaurant_id']);
        return view('admin.menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $restaurant_id = $request->query('vendor_id');
        $data = [
            'title' => 'Create new menu',
            'restaurant_id' => old('restaurant_id', $restaurant_id),
            'restaurants' => Restaurant::adminList(),
            'days' => Day::adminList(),
            'menu_types' => config('menu.types'),
            'edit' => false,
            'render' => old('menu_items'),
            'sort'=>Menu::get()->count() +1,
        ];
        return view('admin.menu.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (!isset($inputs['start_time']) && !isset($inputs['end_time']) && !isset($inputs['same_as_restaurant'])){
            return redirect()->back()->withErrors(['flash_message'=>"choose or time or same as restaurant filed one"])->withInput();
        }
        if (isset($inputs['image']) && $inputs['image']){
            $image_name = ImageController::imageUpload($request);
            $inputs['image'] =$image_name;
        }
        $restaurant_id = Menu::saveData($inputs);
        return redirect()->route('menu.index', ['vendor_id'=>$restaurant_id])->with(['flash_message' => 'Menu was created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $item = Menu::getDataForEdit($id);
        $data = [
            'title' => 'Editing menu "'.$item['name'].'"',
            'restaurant_id' => old('restaurant_id', $item['restaurant_id']),
            'restaurants' => Restaurant::adminList(),
            'days' => Day::adminList(),
            'menu_types' => config('menu.types'),
            'edit' => true,
            'item' => $item,
            'render' => old('menu_items', $item['menu_items']),
            'sort'=>Menu::where('restaurant_id',$item['restaurant_id'])->get()->count(),
            'sort_id'=>Menu::where('id',$id)->first()->sort_id
        ];
        return view('admin.menu.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $model = Menu::query()->findOrFail($id);
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (!isset($inputs['start_time']) && !isset($inputs['end_time']) && !isset($inputs['same_as_restaurant'])){
            return redirect()->back()->withErrors(['flash_message'=>"choose or time or same as vendor filed one"])->withInput();
        }
        if (isset($inputs['image']) && $inputs['image']){
            $image_name = ImageController::imageUpload($request);
            $inputs['image'] =$image_name;
            if ($model->image){
                ImageController::imageDelete($model->image);
            }
        }
        $restaurant_id=Menu::saveData($inputs, $model);
        if ($inputs['blank'] == 1){
            return redirect()->route('menu.edit', ['menu'=>$id])
                ->with(['flash_message' => 'Menu was updated successfully.']);
        }
        return redirect()->route('menu.index',['vendor_id'=>$restaurant_id])
            ->with(['flash_message' => 'Menu was updated successfully.']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $menu = Menu::query()->findOrFail($id);
        $restaurant_id = $menu['restaurant_id'];
        $menu->delete();
        return redirect()->route('menus.index', ['vendor_id' => $restaurant_id]);
    }

    /**
     * Create Validator instance for the request
     *
     * @param array $inputs
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator($inputs) {
        return Validator::make($inputs, [
            'name' => 'required|string|max:191',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'sort_id' => 'required|integer',
            'menu_items' => 'required|array|min:1',
            'menu_items.*.name' => 'required|string|max:191',
            'menu_items.*.price' => 'required|numeric',
            'menu_items.*.type' => 'required|in:0,1,2',
            'menu_items.*.max_quantity' => 'required|integer|digits_between:1,10|min:1',
            'menu_items.*.description' => 'nullable|string|max:5000',
            'menu_items.*.container_price' => 'required|numeric',
            'menu_items.*.offer_price' => 'required|numeric',
            'menu_items.*.special_offer' => 'required|numeric',
            'menu_items.*.popular_item' => 'required|numeric',
            'menu_items.*.options' => 'nullable|array',
            'menu_items.*.options.*.type' => 'required|in:addon,variant',
            'menu_items.*.options.*.name' => 'required|string|max:191',
            'menu_items.*.options.*.values' => 'required|array|min:1',
            'menu_items.*.options.*.values.*.value' => 'required|string|max:191',
            'menu_items.*.options.*.values.*.price' => 'required|integer|digits_between:1,10',
        ], [], [
            'name' => 'Category Name',
            'restaurant_id' => 'Restaurant',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'menu_items.*.name' => 'Item Name',
            'menu_items.*.price' => 'Price',
            'menu_items.*.type' => 'Item Type',
            'menu_items.*.max_quantity' => 'Max Quantity',
            'menu_items.*.description' => 'Description',
            'menu_items.*.container_price' => 'Container Price',
            'menu_items.*.offer_price' => 'Offer Price',
            'menu_items.*.special_offer' => 'Special Offer',
            'menu_items.*.popular_item' => 'Popular Item',
            'menu_items.*.options.*.type' => 'Option Type',
            'menu_items.*.options.*.name' => 'Category Name',
            'menu_items.*.options.*.values.*.value' => 'Category Value',
            'menu_items.*.options.*.values.*.price' => 'Price For Value',

        ]);
    }
}
