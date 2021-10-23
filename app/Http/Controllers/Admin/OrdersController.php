<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\MenuItemOptionValue;
use App\Models\Order;
use App\Models\PendingOrders;
use App\Services\EmailService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\Services\SmsService;
use App\Services\StatusesHistoryService;
use App\Models\StatusesHistory;
use App\Traits\OrderResources;
use App\Traits\TimeTrack;
use Cassandra\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    use TimeTrack, OrderResources;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $orders = PendingOrders::where('status','!=','pending')
            ->where('status','!=','Cancelled')
            ->where('status','!=','status_303')
            ->where('status','!=','status_304')
            ->orderBy('created_at', 'desc')->paginate(10);
        $statuses=[];

        foreach ($orders as $key=>$order){
            if ($order->order_type != 1 && $order->status == 'dispatch'){
                $orders->forget($key);
            }
            $statuses[$order->id]=StatusesHistoryService::get_history_statuses($order->id);
        }

        return view('admin.order.index', [
           'orders'=>$orders,
           'statuses'=>$statuses,
            'title'=>'Orders'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function cancelled(){
        $orders = PendingOrders::where('status','Cancelled')->orWhere('status','status_304')
            ->orderBy('created_at', 'desc')->paginate(10);
        $statuses=[];
        foreach ($orders as $order){
            $statuses[$order->id]=StatusesHistoryService::get_history_statuses($order->id);
        }
        return view('admin.order.cancelled', [
            'orders'=>$orders,
            'statuses'=>$statuses,
            'title'=>'Cancelled Orders'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function delivered(){
        $orders = PendingOrders::
        where('status','status_303')
        ->Orwhere(function ($query){
            $query->where('order_type','!=',1);
            $query->where('status','dispatch');
        })->orderBy('created_at', 'desc')->paginate(10);
        $statuses=[];

        foreach ($orders as $order){
            $statuses[$order->id]=StatusesHistoryService::get_history_statuses($order->id);
        }

        return view('admin.order.delivered', [
            'orders'=>$orders,
            'statuses'=>$statuses,
            'title'=>'Delivered Orders'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($id){
        $data = PendingOrders::find($id);
        $order = $this->getMenu(json_decode($data->action,true));
        $map = MapController::map($data);
//        $map=[];
        return view('admin.order.show', [
            'order'=>$data,
            'menus'=>$order,
            'map'=>$map,
            'title'=>'Order Detail'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return view('admin.order.edit', [
            'order'=>$order,
            'title'=>'Edit Order'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $order
     * @return float|int
     */
    private function getPrice($order)
    {
        if (is_array($order)) {
            $sum = 0;
            $simpleSum = 0;
            $menu = MenuItem::where('id', $order['item_id'])->first();
            if (!empty($menu)) {
                $simpleSum = $menu->price;
            }
            if (is_array($order['add_ons']) && !empty($order['add_ons'])) {
                foreach ($order['add_ons'] as $value) {
                    $item = MenuItemOptionValue::where('id', $value)->first();
                    if (!empty($item)) {
                        $simpleSum += $item->price;
                    }
                }
            } else {
                $item = MenuItemOptionValue::where('id', $order['add_ons'])->first();
                if (!empty($item)) {
                    $simpleSum += $item->price;
                }
            }
            if (is_array($order['variants']) && !empty($order['variants'])) {
                foreach ($order['variants'] as $value) {
                    $item = MenuItemOptionValue::where('id', $value)->first();
                    if (!empty($item)) {
                        $simpleSum += $item->price;
                    }
                }
            } else {
                $item = MenuItemOptionValue::where('id', $order['variants'])->first();
                if (!empty($item)) {
                    $simpleSum += $item->price;
                }
            }
            if (isset($order['order_type']) && $order['order_type'] == 2 && !empty($menu)) {
                $simpleSum += $menu->container_price;
            }
            $sum += $simpleSum * $order['quantity'];
            return $sum;
        }
        return false;
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
        $order = PendingOrders::find($request->order_id);
        $order->update(['status'=>$request->status]);
        StatusesHistory::_save(['order_id'=>$request->order_id,'status'=>$request->status]);
        if ($request->status == 'Cancelled'){
            $accept['accept']='Cancelled';
            $accept['accept_message']='Cancelled by Admin Grab it';
            $accept['status']='Cancelled';
            $order->update(['accept_message'=>'Cancelled by Admin Grab it']);
            if ($request->accept==0){
                $messages_cancelled = __('messages.cancelled',
                    [
                        'name'=>mb_substr($order->restaurant->name, 0, 13),
                        'id'=>$order->transaction_id,
                        'accept_message'=>$order->accept_message,
                    ]);
                EmailService::sendEmailWhenOrderCancelled($order);
                SmsService::sendSmsWhenOrderCancelled($order,$messages_cancelled);
            }
        }else{
            $accept='status_303';
        }
        SendFirebaseNotificationHandlerService::sendUserFromAdmin($accept,$order);
        return redirect()->back()->with(['flash_message' => 'Status was updated.']);
    }
}
