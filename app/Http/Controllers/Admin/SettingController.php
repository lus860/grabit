<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\PendingOrders;
use App\Services\SendPushNotificationFromFirebase;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Integer;

class SettingController extends Controller {
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
        $data['title'] = "Settings";
        return view('admin.setting.index', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function delivery(){
        $data['title'] = "Delivery Information";
        $data['delivery']= Setting::where('title','delivery')->get();
        return view('admin.deliveries.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function courierSettings(){
        $data['title'] = "Courier Settings Information";
        $data['courier'] = Setting::where('title','courier')->get();
        return view('admin.setting.courier.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function courierSettingsEdit(Request $request){
        if ($request->getMethod()=='GET'){
            $data['title'] = "Edit Courier Setting Information";
            $data['courier']= Setting::where('title','courier')->get();
            return view('admin.setting.courier.edit', $data);
        }
        $validatedData = Validator::make($request->all(),[
            'km_price' => ['required','integer'],
            'base_fare' => ['required','integer'],
            'minimum_fare' => ['required','integer'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if (Setting::_save_by_title('courier',$request->all())){
            return redirect('backend/courier-settings')->with(['flash_message'=>'Success']);
        }
        return redirect()->back()->withErrors(['flash_message'=>'Error updating Delivery Information']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function deliveryEdit(Request $request){
        if ($request->getMethod()=='GET'){
            $data['title'] = "Edit Delivery Information";
            $data['delivery']= Setting::where('title','delivery')->get();
            return view('admin.deliveries.edit', $data);
        }
        $validatedData = Validator::make($request->all(),[
            'under_price' => ['required','integer'],
            'above_price' => ['required','integer'],
            'delivery_time' => ['required','integer','between:5,99999999'],
        ],['between'=>'The delivery time must be minimum 5']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if (Setting::_save_by_title('delivery',$request->all())){
            return redirect('backend/deliveries')->with(['flash_message'=>'Success']);
        }
        return redirect()->back()->withErrors(['flash_message'=>'Error updating Delivery Information']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function app_settings(){
        $data['title'] = "App Setting Information";
        $data['app_settings']= Setting::where('title','app_setting')->get();
        return view('admin.setting.app_setting.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function app_settings_edit_form(Request $request){
        $data['title'] = "Edit App Setting Information";
        $data['app_settings']= Setting::where('title','app_setting')->get();
        $data['block_list']= Setting::where(['title'=>'app_setting','keyword'=>'block_list'])->first();
        if ($data['block_list'] && $data['block_list']->description){
            if ($data['block_list']->description){
                $data['block_list']= unserialize($data['block_list']->description);
            }else{
                $data['block_list']=null;
            }
            $new_array=[];
            foreach ($data['block_list'] as $value){
                $new_array[] = (integer)$value['id'];
            }
            $data['block_list']=$new_array;
        }

        $data['users']= User::where(['is_activated'=>1,'user_type'=>2])->get();
        return view('admin.setting.app_setting.edit', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show_block_list(){
        $data['block_lists']= Setting::where(['title'=>'app_setting','keyword'=>'block_list'])->first();
        if ($data['block_lists']->description){
            $data['block_lists']= unserialize($data['block_lists']->description);
            $new_array=[];
            foreach ($data['block_lists'] as $value){
                $new_array[] = (integer)$value['id'];
            }
            $data['users'] = User::whereIn('id',$new_array)->get();
            if ($data['users']->count()){
                foreach ($data['users'] as $user){
                    foreach ($data['block_lists'] as $value){
                        if ($user->id == $value['id']){
                            $user->message = $value['message'];
                        }
                    }
                }
            }
        }else{
            $data['block_lists']=null;
        }
        $data['title']='Show blocked list';
        return view('admin.setting.app_setting.block_list', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove_block_list(Request $request){
        $validatedData = Validator::make($request->all(),[
            'users' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        Setting::where(['title'=>'app_setting','keyword'=>'block_list'])->update(['description'=>serialize($request->users)]);
        return redirect()->back()->with(['flash_message'=>'Success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function app_settings_store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'min_ios' => ['required'],
            'min_android' => ['required','between:0,99999999999.9999999'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
//        dd(serialize([['id'=>2,'message'=>'fsdfds']]));
        $data = Setting::_save_by_title('app_setting',$request->except(['block_list']));
        if ($data){
            $maintenance_mode = isset($request->maintenance_mode)? true: false;
            $old_block_list = Setting::where(['title'=>'app_setting','keyword'=>'block_list'])->first();
            if (isset($request->block_list)){
                if ($old_block_list && $old_block_list->description){
                    $block_list = unserialize($old_block_list->description);
                    $flag = true;
                    foreach ($block_list as $value){
                        if ($value['id']==$request->block_list){
                            $value['message']=$request->message;
                            $flag=false;
                        }
                    }
                    if ($flag){
                        array_push($block_list,['id'=>$request->block_list,'message'=>$request->message]);
                    }
                    $old_block_list->update(['description'=>serialize($block_list)]);
                }else{
                    $block_list=[['id'=>$request->block_list,'message'=>$request->message]];
                    $old_block_list->update(['description'=>serialize($block_list)]);
                }
            }
            Setting::where(['title'=>'app_setting','keyword'=>'maintenance_mode'])->update(['description'=>$maintenance_mode]);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
    }


    public function monthly_price(){
        $data['title'] = "Monthly price";
        $data['monthly_price']= Setting::where(['title'=>'payment','keyword'=>'monthly_price'])->get();
        return view('admin.setting.payment.monthly_price.index', $data);
    }

    public function monthly_price_edit_form(Request $request){
        $data['title'] = "Edit Monthly price Information";
        $data['payment']= Setting::where(['title'=>'payment','keyword'=>'monthly_price'])->first();

       // $data['users']= User::where(['is_activated'=>1,'user_type'=>2])->get();
        return view('admin.setting.payment.monthly_price.edit', $data);
    }

    public function monthly_price_add_form(Request $request){
        $data['title'] = "Edit Monthly price Information";
        $data['payment']= Setting::where(['title'=>'payment','keyword'=>'monthly_price'])->first();

        // $data['users']= User::where(['is_activated'=>1,'user_type'=>2])->get();
        return view('admin.setting.payment.monthly_price.edit', $data);
    }

    public function monthly_price_store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'monthly_price_description' => ['required'],
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if (isset($request->monthly_price_description)){
        $monthly_price_description = $request->monthly_price_description;
            $old_monthly_price = Setting::find($request->monthly_price_id);
        if ($old_monthly_price){
            $old_monthly_price->update(['description'=>$monthly_price_description]);
            return redirect()->back()->with(['flash_message'=>'Success']);
            }

         Setting::_save(
            ['title'=>'payment',
                'keyword'=>'monthly_price',
                'description'=>$monthly_price_description,
            ]);
        return redirect()->back()->with(['flash_message'=>'Success']);
    }
    }


    public function yearly_price_edit_form(Request $request){
        $data['title'] = "Edit Yearly price Information";
        $data['payment']= Setting::where(['title'=>'payment','keyword'=>'yearly_price'])->first();

        // $data['users']= User::where(['is_activated'=>1,'user_type'=>2])->get();
        return view('admin.setting.payment.yearly_price.edit', $data);
    }

    public function yearly_price_add_form(Request $request){
        $data['title'] = "Edit Yearly price Information";
        $data['payment']= Setting::where(['title'=>'payment','keyword'=>'yearly_price'])->first();

        // $data['users']= User::where(['is_activated'=>1,'user_type'=>2])->get();
        return view('admin.setting.payment.yearly_price.edit', $data);
    }

    public function yearly_price_store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'yearly_price_description' => ['required'],
        ]);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if (isset($request->yearly_price_description)){
            $yearly_price_description = $request->yearly_price_description;
            $old_yearly_price = Setting::find($request->yearly_price_id);
            if ($old_yearly_price){
                $old_yearly_price->update(['description'=>$yearly_price_description]);
                return redirect()->back()->with(['flash_message'=>'Success']);
            }

            Setting::_save(
                ['title'=>'payment',
                    'keyword'=>'yearly_price',
                    'description'=>$yearly_price_description,
                ]);
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
    }

    public function yearly_price(){
        $data['title'] = "Yearly Price";
        $data['yearly_price']= Setting::where(['title'=>'payment','keyword'=>'yearly_price'])->get();
        return view('admin.setting.payment.yearly_price.index', $data);
    }

}
