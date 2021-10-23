<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminCenterNotification;
use App\Models\CourierOrders;
use App\Models\Notifications;
use App\Models\PendingOrders;
use App\Models\RestaurantUsers;
use App\Models\Riders;
use App\Models\RidersOrders;
use App\Services\OurDashModuleService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller
{
    public function check_existing_user(Request $request){
        $exists = 0;
        $user = User::where(['email'=>$request->email])->first();
        if(!empty($user)){
            $exists = 1;
        }
        return response()->json([
            'exists'=>$exists
        ]);
    }

    public function check_existing_restaurant_user(Request $request){
        $exists = 0;
        $user = RestaurantUsers::where(['email'=>$request->email])->first();
        if(!empty($user)){
            $exists = 1;
        }
        return response()->json([
            'exists'=>$exists
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_center_notification_change_status_order(Request $request){
        $validatedData = Validator::make($request->all(),[
            'data' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return response()->json(['res'=>false]);
        }
        $data = $request->data;
        foreach ($data as $key=>$value){
            if ($value==null){
                unset($data[$key]);
            }
        }
        $type = $request->type == 'true' ? true:false;
        if ($type){
            Notifications::whereIn('id',$data)->update(['admin_center'=>null]);
            return response()->json(['res'=>true]);
        }else{
            Notifications::whereIn('id',$data)->update(['admin_center'=>1]);
            return response()->json(['res'=>true]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admin_center_notification_change_status_vendor(Request $request){
        $validatedData = Validator::make($request->all(),[
            'data' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return response()->json(['res'=>false]);
        }
        $data = $request->data;
        foreach ($data as $key=>$value){
            if ($value==null){
                unset($data[$key]);
            }
        }
        $type = $request->type == 'true' ? true:false;
        if ($type){
            AdminCenterNotification::whereIn('id',$data)->update(['status'=>null]);
            return response()->json(['res'=>true]);
        }else{
            AdminCenterNotification::whereIn('id',$data)->update(['status'=>1]);
            return response()->json(['res'=>true]);
        }
    }

    public function manage_order_change_status(Request $request){
        $validatedData = Validator::make($request->all(),[
            'data' => ['required'],
            'data.tran_id' => ['required','numeric'],
            'data.select' => ['required'],
            'data.rider' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return response()->json(['res'=>false],404);
        }
        $order = PendingOrders::where('transaction_id',$request->data['tran_id'])->first();
        $courier_order = CourierOrders::where('transaction_id',$request->data['tran_id'])->first();
        if ($order){
            $order->update(['status'=>$request->data['select'],'rider_name'=>$request->data['rider']?:null]);
            OurDashModuleService::changeStatusToOrder($order,$request->data['select']);
            $this->getRiderOrder($order,$request->data['rider']);
            return response()->json([1]);
        }elseif ($courier_order){
            $courier_order->update(['status'=>$request->data['select'],'rider_name'=>$request->data['rider']?:null]);
            OurDashModuleService::changeStatusToOrder($courier_order,$request->data['select'],true);
            $this->getRiderOrder($courier_order,$request->data['rider'],true);
            return response()->json([1]);
        }
        return response()->json(['res'=>false],404);
    }

    public function manage_order_change_rider(Request $request){
        $validatedData = Validator::make($request->all(),[
            'data' => ['required'],
            'data.tran_id' => ['required','numeric'],
            'data.rider' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return response()->json(['res'=>false],404);
        }
        $updated_data = ['rider_name'=>$request->data['rider']?:null];
        if (isset($request->data['status']) && $request->data['status']){
            $updated_data['status'] = $request->data['status'];
        }
        $order = PendingOrders::where('transaction_id',$request->data['tran_id'])->first();
        $courier_order = CourierOrders::where('transaction_id',$request->data['tran_id'])->first();
        if ($order){
            $order->update($updated_data);
            OurDashModuleService::changeStatusToOrder($order,$request->data['status']);
            $this->getRiderOrder($order,$request->data['rider']);
            if ($updated_data['status'] == 'status_301'){
                SendFirebaseNotificationHandlerService::new_order_for_rider_user($order);
            }
            return response()->json([1]);
        }elseif ($courier_order){
            $courier_order->update($updated_data);
            OurDashModuleService::changeStatusToOrder($courier_order,$request->data['status'],true);
            $this->getRiderOrder($courier_order,$request->data['rider'],true);
            if ($updated_data['status'] == 'status_301'){
                $vendor_user = $courier_order->vendor_user;
                if ($vendor_user){
                    SendFirebaseNotificationHandlerService::dispatch_vendor_user_order($courier_order);
                }else{
                    SendFirebaseNotificationHandlerService::new_order_for_rider_user($courier_order);
                }
            }
            return response()->json([1]);
        }
        return response()->json(['res'=>false],404);
    }

    /**
     * @param $order
     * @param $rider_id
     * @param bool $type
     * @return int
     */
    private function getRiderOrder($order,$rider_id,$type=false){
        if ($type){
            $rider_order = RidersOrders::where('courier_id',$order->transaction_id)->first();
            if (!$rider_order){
                RidersOrders::_save(['rider_id'=>$rider_id,'courier_id'=>$order->transaction_id]);
            }
            $order->update(['rider_id'=>$rider_id]);
        }else{
            $rider_order = RidersOrders::where('order_id',$order->transaction_id)->first();
            if (!$rider_order){
                RidersOrders::_save(['rider_id'=>$rider_id,'order_id'=>$order->transaction_id]);
            }
            $order->update(['rider_id'=>$rider_id]);
        }
        return 1;
    }
}
