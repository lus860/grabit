<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourierOrders;
use App\Models\PendingOrders;
use App\Models\Riders;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ManageOrdersController extends Controller
{

    public function index(){
        $data['title'] = "Manage orders";
        $pending = PendingOrders::where('order_type',1)->where(function ($query){
            $query->where('status','dispatch');
            $query->orWhere('status','accepted');
            $query->orWhere('status','status_300');
            $query->orWhere('status','status_301');
            $query->orWhere('status','status_302');
        })->orderBy('created_at','desc')->get();
        $couriers = CourierOrders::where(function ($query){
            $query->where('status','dispatch');
            $query->orWhere('status','accepted');
            $query->orWhere('status','status_300');
            $query->orWhere('status','status_301');
            $query->orWhere('status','status_302');
        })->orderBy('created_at','desc')->get();
        $pending = $pending->merge($couriers);
        $pending = $pending->sortByDesc('created_at');
        $pending = $this->correctForm($pending);
        $data['orders']=collect($pending)->paginate(10);
        return view('admin.manage.orders.index', $data);
//        return redirect()->back();
    }


    private function correctForm($data){
        $newData=[];
        foreach ($data as $key=>$value){
            $newData[$key]['transaction_id']=$value->transaction_id;
            $newData[$key]['rider_name']=isset($value->rider_name)?(isset($value->rider)?$value->rider->name:'N/A'):'N/A';
            if ($value->vendor_user){
                $newData[$key]['customer_name'] = $value->vendor_user->restaurant->name;
            }else{
                $newData[$key]['customer_name']=$value->user->name??'N/A';
            }
            if ($value->get_vendor){
                $newData[$key]['pick_up']=$value->get_vendor->name.','.$value->get_vendor->address1.','.$value->get_vendor->address2.','.$value->get_vendor->area->name;
                $newData[$key]['delivery']=$value->address->line_1.','.$value->address->line_2.','.$value->address->landmark.','.$value->address->area->name.','.$value->address->city->name;
                $newData[$key]['carrier_name']='';
                if ($value->status == 'accepted' || $value->status == 'status_300'){
                    $newData[$key]['status'] = 'new';
                }else{
                    $newData[$key]['status'] = $value->status;
                }

                $action = json_decode($value->action,true);
                $newData[$key]['price']=$action[0]['discounted_price']??$action[0]['price'];
            }else{
                $newData[$key]['pick_up']=$value->pick_up_address;
                $newData[$key]['delivery']=$value->delivery_address;
                $newData[$key]['carrier_name']=$value->carrierRelation ? $value->carrierRelation->carrier_name:'';
                if ($value->status == 'accepted'){
                    $newData[$key]['status'] = 'new';
                }else{
                    $newData[$key]['status'] = $value->status;
                }
                $newData[$key]['price']=$value->price;
            }
            $newData[$key]['distance ']='';
            $newData[$key]['payment']=config('api.order.payment')[$value->payment]['title'];
            $newData[$key]['created_at']=$value->created_at;
        }
        return $newData;
    }

    public function show(Request $request,$transaction_id){
        $data['title']="Manage Order";
        $data['order'] = PendingOrders::where('transaction_id',$transaction_id)->first();
        $data['riders'] = Riders::get();
        if ($data['order']){
            return view('admin.manage.orders.show_pending',$data);
        }
        $data['order'] = CourierOrders::where('transaction_id',$transaction_id)->first();
        return view('admin.manage.orders.show_courier',$data);
    }
}
