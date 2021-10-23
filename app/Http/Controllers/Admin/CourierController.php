<?php

namespace App\Http\Controllers\Admin;

use App\Models\CourierOrders;
use App\Models\DashApi;
use App\Http\Controllers\Controller;
use App\Services\SendFirebaseNotificationCourierHandlerService;
use App\User;
use App\Models\PendingOrders;
use App\Services\DashDeliveryService;
use App\Services\EmailService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\Services\SendGuzzleRequestService;
use App\Services\SmsService;
use App\Services\StatusesHistoryService;
use App\Models\StatusesHistory;
use App\Traits\OrderResources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourierController extends Controller
{
    use OrderResources;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $orders = CourierOrders::where('status','!=','Cancelled')
            ->where('status','!=','status_303')
            ->where('status','!=','status_304')->orderBy('seen', 'asc')
            ->orderBy('created_at', 'desc')->paginate(10);
        $statuses=[];
        foreach ($orders as $order){
            $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id,'courier');
        }
        return view('admin.courier.index', [
            'orders'=>$orders,
            'statuses'=>$statuses,
            'title'=>'Courier Orders'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($id){
        $data = CourierOrders::find($id);
        $order = $this->getMenu(json_decode($data->action,true));
        $map = MapController::map($data);
        return view('admin.courier.show', [
            'order'=>$data,
            'menus'=>$order,
            'map'=>$map,
            'title'=>'Order Detail'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change_status(Request $request){
        $validatedData = Validator::make($request->all(),[
            'order_id' => ['required'],
            'status' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $order = CourierOrders::find($request->order_id);
        StatusesHistory::_save(['courier_id'=>$request->order_id,'status'=>$request->status]);
        $order->update(['status'=>$request->status,'status_text'=>$request->status_text,'seen' => null]);
        if($request->status == 'accepted'){
//            DashDeliveryService::sendRequestDashDelivery($order,CourierOrders::class);
            $vendor_user = $order->vendor_user;
            if ($vendor_user){
                SendFirebaseNotificationHandlerService::accepted_vendor_user_order($order);
            }else{
                SendFirebaseNotificationCourierHandlerService::sendUser('accepted',$order);
            }
        }
        elseif ($request->status == "Cancelled"){
            EmailService::sendEmailCourierWhenOrderCancelled($order);
            SmsService::sendSmsWhenOrderCancelledCourier($order,$request->status_text);
            SendFirebaseNotificationCourierHandlerService::sendUser('Cancelled',$order);
        }
        return redirect()->back()->with(['flash_message' => 'Status was updated.']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function cancelled(){
        $orders = CourierOrders::where('status','Cancelled')->orwhere('status','status_304')->orderBy('seen', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        $statuses=[];
        foreach ($orders as $order){
            $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id,true);
        }
        return view('admin.courier.cancelled', [
            'orders'=>$orders,
            'statuses'=>$statuses,
            'title'=>'Cancelled Orders'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function delivered(){
        $orders = CourierOrders::where('status','status_303')
            ->orderBy('seen', 'asc')->orderBy('created_at', 'desc')->paginate(10);
        $statuses=[];
        foreach ($orders as $order){
            $statuses[$order->id]=StatusesHistoryService::get_history_statuses($order->id,true);
        }
        return view('admin.courier.delivered', [
            'orders'=>$orders,
            'statuses'=>$statuses,
            'title'=>'Delivered Orders'
        ]);
    }

}
