<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loyalty;
use App\Models\Restaurant;
use App\Models\VendorBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;

class LoyaltyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = "Loyalty program";
        $data['loyalties'] = Loyalty::paginate(10);
        return view('admin.loyalty.index', $data);
    }

    public function add_branches(Loyalty $loyalty)
    {
       // dd($loyalty->id);
        $branches = VendorBranch::where('vendor_id',$loyalty->vendor_id)->get();
        $data['title'] = "Add Loyalty Branches";
        $data['branches'] = $branches;
        $data['loyalty'] = $loyalty;

        return view('admin.loyalty.branches',$data);
    }

    public function create_branches(Request $request){
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'name.*' => ['required'],
            'vendor_id' => ['required']
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        foreach ($request->name as $name){
            VendorBranch::_save([
                'vendor_id'=>$request->vendor_id,
                'name'=>$name
            ]);
        }
        return  redirect()->back()->with(['flash_message'=>'Success']);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create(){
        $data['title'] = "Add Loyalty program";
        $vendors = Restaurant::get();
        $data['courier'] = Loyalty::where('vendor_id',null)->first();
        if ($vendors->count()){
            foreach ($vendors as $key=>$value){
                if ($value->loyalty){
                    unset($vendors[$key]);
                }
            }
        }

        $data['vendors'] =$vendors;
        return view('admin.loyalty.create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'vendor_id' => ['required'],
            'image' => ['required'],
            'saved_image' => ['required'],
            'spend' => ['required','numeric'],
            'redemption' => ['required','numeric','lt:spend'],
        ],['vendor_id.required'=>'You must choose business name','lt'=>'Redemption amount must be less than Total spend']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $request->merge(['saved_image'=>$request->saved_image]);
        Loyalty::_save($request);
        return redirect('/backend/loyalty')->with(['flash_message'=>'Success']);
    }

    /**
     * @param Request $request
     * @param Loyalty $loyalty
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Request $request,Loyalty $loyalty){
        $data['title'] = "Edit Loyalty program";
        $vendors = Restaurant::get();
        $data['courier'] = Loyalty::where('vendor_id',null)->first();

        if ($vendors->count()){
            foreach ($vendors as $key=>$value){
                if ($value->loyalty && $value->loyalty->id == $loyalty->id){
                    continue;
                }
                if ($value->loyalty){
                    unset($vendors[$key]);
                }
            }
        }

        $data['vendors'] =$vendors;
        $data['loyalty'] = $loyalty;
        return view('admin.loyalty.edit', $data);
    }

    /**
     * @param Request $request
     * @param Loyalty $loyalty
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,Loyalty $loyalty){
        $validatedData = Validator::make($request->all(),[
            'vendor_id' => ['required'],
            'spend' => ['required','numeric'],
            'redemption' => ['required','numeric','lt:spend'],
        ],['vendor_id.required'=>'You must choose business name','lt'=>'Redemption amount must be less than Total spend']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $image_url = '';
        if (isset($request->image)){
            $validatedData = Validator::make($request->all(),[
                'image' => ['required'],
                'saved_image' => ['required'],
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
        }
        if($request->saved_image != '') {
            if ($loyalty->image){
                $oldimage=explode('/',$loyalty->image);
                $oldimage=array_last($oldimage);
            }
            if(isset($loyalty->image)&& $loyalty->image && File::exists(public_path().'/admin/uploads/'.$oldimage)) {
                File::delete(public_path().'/admin/uploads/'.$oldimage);
            }
            $loyalty->image = $image_url;
        }
        Loyalty::_save($request,$loyalty->id);
        return redirect('/backend/loyalty')->with(['flash_message'=>'Success']);
    }

    /**
     * @param Request $request
     * @param Loyalty $loyalty
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request,Loyalty $loyalty){
        if (isset($loyalty) && $loyalty->id){
            $loyalty->delete();
            return redirect('/backend/loyalty')->with(['flash_message'=>'Success: Deleted']);
        }
        return redirect()->back()
            ->with(['error_message'=>'Something is wrong!!!']);
    }

    public function branch_destroy(Request $request,VendorBranch $loyalty){
        if (isset($loyalty) && $loyalty->id){
            $loyalty->delete();
            return redirect()->back()->with(['flash_message'=>'Success: Deleted']);
        }
        return redirect()->back()
            ->with(['error_message'=>'Something is wrong!!!']);
    }
}
