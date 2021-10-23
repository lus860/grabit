<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\RatingsVendors;
use App\Models\Setting;
use App\Models\CourierOrders;
use App\Models\PendingOrders;
use App\Models\StatusesHistory;
use App\Traits\ApiResources;
use App\User;
use App\Models\Carrier;
use App\Models\ParcelTypes;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CourierSettingsController extends ApiControllers
{
    use ApiResources;

    protected $courier;

    /**
     * CourierSettingsController constructor.
     * @param Setting $model
     * @param CourierOrders $courier
     */
    public function __construct(Setting $model,CourierOrders $courier)
    {
        $this->model = $model;
        $this->courier = $courier;
    }

    /**
     * @return mixed
     */
    public function get_courier_settings()
    {
        $carriers = Carrier::select('id', 'carrier_name', 'carrier_status')->get();
        $parcel = ParcelTypes::select('id', 'parcel_name', 'parcel_status')->get()->toArray();
        if (!$carriers && !$parcel) {
            return $this->sendError(0);
        }
        if ($carriers->count()) {
            foreach ($carriers as $key => $carrier) {
                $carriers[$key]['carrier_status'] = $carrier->carrier_status ? true : false;
            }
        }
        $data['carrier'] = $carriers->toArray();
        $data['parcel_type'] = $parcel;
        return $this->sendResponse($data, 'courier');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function add_courier_task(Request $request){
        $validatedData = Validator::make($request->all(),[
            'pick_up_address' => ['required'],
            'pick_up_area' => ['required'],
            'pick_up_city' => ['required'],
            'pick_up_latitude' => ['required'],
            'pick_up_longitude' => ['required'],
            'pick_up_information' => ['required'],
            'delivery_address' => ['required'],
            'delivery_area' => ['required'],
            'delivery_city' => ['required'],
            'delivery_information' => ['required'],
            'delivery_latitude' => ['required'],
            'delivery_longitude' => ['required'],
            'distance' => ['required'],
            'price' => ['required'],
            'weight' => ['required'],
            'carrier' => ['required'],
            'parcel_type' => ['required'],
            'comments' => ['required'],
            'payment' => ['required'],
        ]);

        if ($validatedData->fails()) {
            return $this->sendResponse([],'order','Error',403);
        }
        $request->request->add(['user_id' =>User::where('token',$request->header('token-login'))->first()->id]);
        $request->request->add(['status' => 'waiting']);
        $data = CourierOrders::_save($request->all());
        if ($data) {
            StatusesHistory::_save(['courier_id'=>$data->id,'status'=>'waiting']);
            $data['transaction_id'] = $this->setTransactionId($data['id'],$this->courier);
            $data->save();
            return $this->sendResponse(['transaction_id' => $data['transaction_id']],'order','ok',200);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function courier_tasks_history(Request $request){
        $token = $request->header('token-login') ?? null;
        if($token) {
            $user_id = User::where('token', $token)->first()->id;
            $orders = $this->courier->where('user_id', $user_id)->get()->toArray();
            $data = [];
            if(count($orders)){
                foreach($orders as $key => $order){
                    $user= User::find($order['user_id']);
                    $data[$key]['transaction_id'] = $order['transaction_id'];
                    $data[$key]['created_at'] = Carbon::parse($order['created_at'])->toDateTimeString();
                    $data[$key]['status_message'] = config('api')['courier_order']['status'][$order['status']];
                    $courier_info['pick_up_address'] = $order['pick_up_address'];
                    $courier_info['user_id'] = $user?$user->id:null;
                    $courier_info['phone'] = $user?$user->phone:null;
                    $courier_info['pick_up_area'] = $order['pick_up_area'];
                    $courier_info['pick_up_city'] = $order['pick_up_city'];
                    $courier_info['pick_up_latitude'] = $order['pick_up_latitude'];
                    $courier_info['pick_up_longitude'] = $order['pick_up_longitude'];
                    $courier_info['pick_up_information'] = $order['pick_up_information'];
                    $courier_info['delivery_address'] = $order['delivery_address'];
                    $courier_info['delivery_area'] = $order['delivery_area'];
                    $courier_info['delivery_city'] = $order['delivery_city'];
                    $courier_info['delivery_information'] = $order['delivery_information'];
                    $courier_info['delivery_latitude'] = $order['delivery_latitude'];
                    $courier_info['delivery_longitude'] = $order['delivery_longitude'];
                    $courier_info['distance'] = $order['distance'];
                    $courier_info['price'] = $order['price'];
                    $courier_info['weight'] = $order['weight'];
                    $courier_info['comments'] = $order['comments']??'';
                    $courier_info['rider_name'] = $order['rider_name']??'';
                    $courier_info['payment'] = $order['payment']??0;
                    $courier_info['envelope'] = $order['envelope']??'';
                    $rating = RatingsVendors::where('transaction_id',$order['transaction_id'])->first();
                    $courier_info['rating'] = $rating?$rating->delivery_rating:0;
                    $data[$key]['courier_information'] = $courier_info;
                }
            }
            return $this->sendResponse($data,'response');
        }
        return $this->sendError([],401,'unauthorized ');
    }

    public function calculate_courier_price(Request $request){
        $validatedData = Validator::make($request->all(),[
            'distance' => ['required','numeric'],
        ]);
        if ($validatedData->fails()) {
            return $this->sendError($validatedData->getMessageBag());
        }
        $carriers = Carrier::where('carrier_status',1)->get();
        if($carriers->count()){
            $data=[];
            foreach ($carriers as $carrier){
                $price = $carrier->km_price * $request->distance + $carrier->base_fare;
                $data[]=[
                    'carrier_id'=>$carrier->id,
                    'price'=>$price > $carrier->minimum_fare ? $price : $carrier->minimum_fare,
                ];
            }
            return $this->sendResponse($data,'carriers');
        }
        return $this->sendError('No Carriers');
    }

}
