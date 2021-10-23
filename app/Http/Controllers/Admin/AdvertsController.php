<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\ManageCategory;
use App\Models\ManageSubcategory;
use App\Models\WebImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertsController extends Controller
{
    public function manage_categories()
    {
        $data['title'] = "Manage categories";
        $data['categories'] = ManageCategory::all();

        return view('admin.adverts.category.index', $data);
    }

    public function add_category()
    {
        $title = "Add Category";
        return view('admin.adverts.category.add_category', compact('title'));
    }

    public function add_sub_category()
    {
        $data['title'] = "Add Subcategory";
        $data['categories'] = ManageCategory::all();

        return view('admin.adverts.category.add_sub_category', $data);
    }

    public function create_category(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => ['required'],

        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if ($request->image) {
            $image = ImageController::imageUploadDefault($request);
            $request->merge(['saved_image' => $image]);
        }

        ManageCategory::_save($request);
        return redirect('/backend/manage-categories')->with(['flash_message' => 'Added new category: Success']);
    }

    public function create_subcategory(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => ['required'],
            'category_id' => ['required'],

        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        ManageSubcategory::_save($request);
        return redirect('/backend/sub-category')->with(['flash_message' => 'Added new subcategory: Success']);
    }

    public function delete(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'id' => ['required'],
        ], ['required' => 'please try again']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $answer = ManageCategory::where('id', $request->id)->delete();
        if ($answer) {
            return redirect()->back()->with(['flash_message' => 'Deleted Category']);
        }

        return redirect()->back()->withErrors(['flash_message' => 'Something is wrong,please try later']);
    }

    public function show(Request $request,$id){
        $data['title'] = "Edit Category";
        $data['category'] = ManageCategory::find($id);
        if ($data['category']){
            return view('admin.adverts.category.show',$data);
        }
        return abort(404);
    }

    public function show_subcategory(Request $request,$id){
        $data['title'] = "Edit Subcategory";
        $data['subcategory'] = ManageSubcategory::find($id);
        $data['categories'] = ManageCategory::all();
        if ($data['subcategory']){
            return view('admin.adverts.subcategory.show',$data);
        }
        return abort(404);
    }

    public function edit_category(Request $request,$id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => ['required'],
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if ($request->image) {
            $image = ImageController::imageUploadDefault($request);
            $request->merge(['saved_image' => $image]);
        }
        ManageCategory::_save($request,$id);
        return redirect('backend/manage-categories')->with(['flash_message'  => 'Edited Category']);
    }

    public function edit_subcategory(Request $request,$id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => ['required'],
            'category_id' => ['required'],

        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        ManageSubcategory::_save($request,$id);
        return redirect('/backend/sub-category')->with(['flash_message' => 'Etided subcategory: Success']);
    }


    public function index_subcategory()
    {
        $data['title'] = "Manage categories";
        $data['subcategory'] = ManageSubcategory::all();

        return view('admin.adverts.subcategory.index', $data);

    }

    public function delete_subcategorey(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'id' => ['required'],
        ], ['required' => 'please try again']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $answer = ManageSubcategory::where('id', $request->id)->delete();
        if ($answer) {
            return redirect()->back()->with(['flash_message' => 'Deleted Category']);
        }

        return redirect()->back()->withErrors(['flash_message' => 'Something is wrong,please try later']);

    }



}
