<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\ClientSources;
use App\Models\CourierOrders;
use App\Models\PendingOrders;
use App\Models\Riders;
use App\Models\RidersOrders;
use App\Services\AuthService;
use App\Services\OurDashModuleService;
use App\Traits\SetToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RidersController extends ApiControllers
{
    /**
     * RidersController constructor.
     * @param Riders $model
     */
    public function __construct(Riders $model)
    {
        $this->model=$model;
        $this->name='rider';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function login(Request $request){
        $result = $this->validateData($request,[
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $user = Riders::where('username',$request->username)->first();
        if ($user){
            if (password_verify($request->password,$user->password)){
                $user->update(['token'=>SetToken::setToken()]);
                return $this->sendResponse(['token'=>$user->token],$this->name);
            }
        }
        return $this->sendError(config('api.error_message')['rider_login_error']);
    }

    /**
     * @return mixed
     */
    public function get_all_orders(){
        $user = AuthService::getRiderUser();
        $pending_orders = DB::table('riders')->join('riders_orders', 'riders.id', '=', 'riders_orders.rider_id')
                ->join('pending_orders', 'riders_orders.order_id', '=', 'pending_orders.transaction_id')
            ->where('riders.id',$user->id)
            ->whereDate('pending_orders.created_at',Carbon::today())
            ->where(function ($query){
                $query->where('pending_orders.status','dispatch');
                $query->orWhere('pending_orders.status','accepted');
                $query->orWhere('pending_orders.status','status_301');
                $query->orWhere('pending_orders.status','status_302');
                $query->orWhere('pending_orders.status','status_303');
                $query->orWhere('pending_orders.status','status_304');
            })
            ->select(['pending_orders.*'])
            ->orderBy('created_at')
            ->get();
        $courier_orders = DB::table('riders')->join('riders_orders', 'riders.id', '=', 'riders_orders.rider_id')
            ->join('courier_orders', 'riders_orders.courier_id', '=', 'courier_orders.transaction_id')
            ->where('riders.id',$user->id)
            ->whereDate('courier_orders.created_at',Carbon::today())
            ->where(function ($query){
                $query->where('courier_orders.status','accepted');
                $query->orWhere('courier_orders.status','status_301');
                $query->orWhere('courier_orders.status','status_302');
                $query->orWhere('courier_orders.status','status_303');
                $query->orWhere('courier_orders.status','status_304');
            })
            ->select(['courier_orders.*'])
            ->orderBy('created_at')
            ->get();
        $orders = $courier_orders->merge($pending_orders);
        if ($orders->count()){
            foreach ($orders as $key=>$order){
                $orders[$key]->action = json_decode($order->action,true);
                $orders[$key]->status = $this->change_status_name($order->status);
                $orders[$key]->status_text = $this->change_status_text($orders[$key]->status);
                if (isset($order->action)){
                    $orders[$key]->type = 'vendor';
                }else{
                    $orders[$key]->type = 'courier';
                }
            }

            return $this->sendResponse($orders,$this->name);
        }
        return $this->sendError(config('api.error_message')['rider_no_order_error']);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function change_status_transit(Request $request){
        $result = $this->validateData($request,[
            'transaction_id' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $pending_order = PendingOrders::where('transaction_id',$request->transaction_id)->first();
        if ($pending_order){
            $pending_order->update(['status'=>'status_302']);
            OurDashModuleService::changeStatusToOrder($pending_order,'status_302');
            return $this->sendResponse(null,null);
        }else{
            $courier_order = CourierOrders::where('transaction_id',$request->transaction_id)->first();
            if ($courier_order){
                $courier_order->update(['status'=>'status_302']);
                OurDashModuleService::changeStatusToOrder($courier_order,'status_302',true);
                return $this->sendResponse(null,null);
            }
        }
        return $this->sendError(0);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function change_status_delivery(Request $request){
        $result = $this->validateData($request,[
            'transaction_id' => ['required'],
            'status' => ['required','numeric','between:0,1'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $pending_order = PendingOrders::where('transaction_id',$request->transaction_id)->first();
        $status = $request->status == 1?'status_303':'status_304';
        if ($pending_order){
            $pending_order->update(['status'=>$status]);
            OurDashModuleService::changeStatusToOrder($pending_order,$status);
            return $this->sendResponse(null,null);
        }else{
            $courier_order = CourierOrders::where('transaction_id',$request->transaction_id)->first();
            if ($courier_order){
                $courier_order->update(['status'=>$status]);
                OurDashModuleService::changeStatusToOrder($courier_order,$status,true);
                return $this->sendResponse(null,null);
            }
        }
        return $this->sendError(0);
    }

    private function change_status_name($status){
        if ($status == 'status_301'){
            return '301';
        }elseif ($status == 'status_302'){
            return '302';
        }elseif ($status == 'status_303'){
            return '303';
        }elseif ($status == 'status_304'){
            return '304';
        }
    }

    private function change_status_text($status){
        if ($status == '301'){
            return 'Dispatched';
        }elseif ($status == '302'){
            return 'Transit';
        }elseif ($status == '303'){
            return 'Delivered';
        }elseif ($status == '304'){
            return 'Cancelled';
        }
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function set_firebase_token(Request $request)
    {
        $result = $this->validateData($request, [
            'firebase_token' => ['required'],
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        $rider = AuthService::getRiderUser();
        if ($rider){
            $user = ClientSources::where('rider_user', $rider->id)->first();
            if ($user) {
                $user->update(['firebase_token' => $request->firebase_token]);
                return $this->sendResponse(null, null);
            }
            $response = ClientSources::_save(['source_id'=>2,'firebase_token' => $request->firebase_token, 'rider_user' => $rider->id]);
            if ($response['success']) {
                return $this->sendResponse(null, null);
            }
        }

        return $this->sendError(0);

    }
}
