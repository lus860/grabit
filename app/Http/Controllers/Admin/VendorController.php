<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Models\VendorTypes;
use App\Models\WebImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;
class VendorController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        $data['title'] = "Vendor Types";
        $data['vendor'] = VendorTypes::paginate(10);
        return view('admin.setting.vendor.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create(){
        $data['title'] = "Add vendor types";
        return view('admin.setting.vendor.create',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'vendor_name' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if ($request->image){
            $image_name = ImageController::imageUpload($request);
            $request->merge(['image_name'=>$image_name]);
        }
        VendorTypes::_save($request);
        return redirect('/backend/vendor-type')->with(['flash_message' => 'Created new Vendor Type: Success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request){
        $validatedData = Validator::make($request->all(),[
            'id' => ['required'],
        ],['required'=>'please try again']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $answer = VendorTypes::where('id',$request->id)->delete();
        if ($answer){
            return redirect()->back()->with(['flash_message' => 'Deleted Vendor Type']);
        }

        return redirect()->back()->withErrors(['flash_message' => 'Something is wrong,please try later']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Request $request,$id){
        $data['vendor'] = VendorTypes::find($id);
        $data['title'] = "Update vendor types";
        return view('admin.setting.vendor.edit', $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$id){
        $validatedData = Validator::make($request->all(),[
            'vendor_name' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if ($request->image){
            $image_name = ImageController::imageUpload($request);
            $request->merge(['image_name'=>$image_name]);
            $old_image = VendorTypes::find($id);
            if ($old_image && $old_image->image){
                $old_image_name = explode('/',$old_image->image);
                if (File::exists(public_path('/admin/uploads/'.last($old_image_name)))){
                    File::delete(public_path('/admin/uploads/'.last($old_image_name)));
                }
            }
        }
        $answer = VendorTypes::_save($request,$id);
        if ($answer){
            return redirect()->back()->with(['flash_message' => 'Updated Vendor Type name']);
        }
        return redirect()->back()->withErrors(['flash_message' => 'Something is wrong,please try later']);
    }
}
