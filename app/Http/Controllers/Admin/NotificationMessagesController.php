<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;
use File;

class NotificationMessagesController extends Controller
{
//    protected $statuses_user=[
//        'pending','waiting','accepted','accepted_pick_up','Cancelled','dispatch',
//        'status_301','status_302','status_303','status_304'
//    ];

    public function user_form(Request $request,$name){
        $data['title'] = ucfirst($name)." notification messages for users";
        $data['messages'] = __("firebase_messages.$name.user");
        $data['name'] =$name;
//        $data['statuses']=null;
        $data['type']='user';
        if (isset($data['messages']) && is_array($data['messages'])){
            unset($data['messages']['waiting']);
            unset($data['messages']['pending']);
            $data['messages']['accepted']['text']='This alert is sent to user when vendor accepts a delivery order';
            $data['messages']['accepted_pick_up']['text']='This alert is sent to user when vendor accepts a delivery order';
            $data['messages']['dispatch']['text']='This alert is sent to user when vendor marks a pick up/dine in order as ready';
            $data['messages']['status_301']['text']='This alert is sent to user when rider is on the way to vendor to collect order';
            $data['messages']['status_302']['text']='This alert is sent to user when rider has picked up order from vendor and is on the way to deliver';
            $data['messages']['status_303']['text']='This alert is sent to user when rider delivers order';
            $data['messages']['Cancelled']['text']='This alert is sent to user when a delivery order is cacelled by the vendor or admin';
        }
        return view('admin.notifications_messages.index', $data);
    }

    public function vendor_form(Request $request,$name){
        $data['title'] = ucfirst($name)." notification messages for vendor";
        $data['messages'] = __("firebase_messages.$name.vendor");
        $data['name'] =$name;
//        $data['statuses']=null;
        $data['type']='vendor';
        if (isset($data['messages']) && is_array($data['messages'])){
            $data['messages']['waiting']['text']='This alert is sent to the vendor when a new delivery/pick up/dine in order is received and too for scheduled order';
            $data['messages']['reminder']['text']='This alert is sent to the vendor for reminnder to prepapre the scheduled order for delivery';
        }
        return view('admin.notifications_messages.index', $data);
    }

    public function create_or_update(Request $request,$type,$name){
        $file = [];
        $data = $request->except(['_token']);
        if (file_exists(resource_path('lang/en/firebase_messages.php'))){
            $file = include_once(resource_path('lang/en/firebase_messages.php')) ;
            if (count($file) && is_array($file)){
                foreach ($file as $key=>$value){
                    if ($key == $name){
                        $file[$key][$type]=$data;
                    }
                }
            }
        }else{
            $file[$name][$type]=$data;
        }
        $code = '<?php return '.var_export($file, true).';'.PHP_EOL;
        file_put_contents(resource_path('lang/en/firebase_messages.php'), $code);
        return redirect('/backend/vendor-type')->with(['flash_message'=>'Success']);
    }
}
