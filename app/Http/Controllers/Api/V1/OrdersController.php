<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ClientSources;
use App\Models\DashApi;
use App\Http\Controllers\Api\ApiControllers;
use App\Models\MenuItem;
use App\Models\CourierOrders;
use App\Models\MenuItemOption;
use App\Models\MenuItemOptionValue;
use App\Models\Notifications;
use App\Models\Order;
use App\Models\Setting;
use App\Models\PendingOrders;
use App\Services\AuthService;
use App\Services\DashDeliveryService;
use App\Services\EmailService;
use App\Services\OrderService;
use App\Services\SendFirebaseNotificationCourierHandlerService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\Services\SendPushNotificationFromFirebase;
use App\Services\UserLoyaltyService;
use App\Models\StatusesHistory;
use App\Traits\ApiResources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\This;

class OrdersController extends ApiControllers
{
    use ApiResources;

    public function __construct(PendingOrders $model)
    {
        $this->model=$model;
        $this->name='order';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function pending_orders(Request $request){
        $result = $this->validateData($request,[
            'user_id' => ['required','integer','min:1'],
            'id' => ['required','integer','min:1'],
            'address_id' => ['required','integer','min:1'],
            'order_from' => ['required','integer','min:1'],
            'order_type' => ['required','integer','min:1'],
            'schedule' => ['required','integer','min:1'],
            'delivery_fee' => ['required'],
            'cooking_directions' => ['required'],
            'order_notes' => ['required'],
            'payment' => ['required'],
            'order' => ['required']]);
        if (gettype($result) == 'object'){
            return $result;
        }
        return $this->_save($request);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function ordersType(Request $request){
        if (isset($request->verify_orders)){
            return $this->verify_orders($request);
        }
        if (isset($request->place_orders)){
            return $this->place_orders($request);
        }
        return $this->sendError('Not Found',404);

    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function verify_orders(Request $request){
        $result = $this->validateData($request,
            [
            'verify_orders' => ['required']
            ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $orders = json_decode($request->verify_orders,true);
        $sum=0;
        foreach ($orders as $key=>$order){
            if(isset($order['price'])){
                $order_type=$orders[$key]['order_type'];
                $key_for_add=$key;
                $data = ['vendor_id'=>$order['vendor_id']??null];
                continue;
            }
            if (isset($order_type)){
                $sum+=$this->getPrice($order,$order_type);
            }
        }
        $orders[$key_for_add]['price']=$sum;
        $answer = UserLoyaltyService::checkUserUseLoyalty($orders[$key_for_add],AuthService::getUser()['id']);
        if (gettype($answer) == 'string'){
            return $this->sendError($answer);
        }
        $orders[$key_for_add] = $answer;
        $client=ClientSources::where('token',$request->header('token'))->first();
        $data = [
            'order'=>json_encode($orders),
            'status'=>'pending',
            'order_total'=>$sum,
            'order_type'=>$orders[$key_for_add]['order_type']??null,
            'schedule'=>$orders[$key_for_add]['schedule']??null,
            'schedule_time'=>$orders[$key_for_add]['schedule_time']??null,
            'delivery_fee'=>$orders[$key_for_add]['delivery_fee']??null,
            'cooking_directions'=>$orders[$key_for_add]['cooking_directions']??null,
            'order_notes'=>$orders[$key_for_add]['order_notes']??null,
            'address_id'=>$orders[$key_for_add]['address_id']??null,
            'payment'=>$orders[$key_for_add]['payment']??null,
            'vendor_id'=>$orders[$key_for_add]['vendor_id']??null,
            'user_id'=>AuthService::getUser()['id'],
            'order_from'=>($client)?$client->source_id:null,
            'discount'=>$answer['discount'],
            'discounted_price'=>$answer['discounted_price'],
        ];
        try {
            $save = PendingOrders::_save($data);
            if($save['success']){
                $save['data']->update(['collection_amount'=>OrderService::getOrderCollectionAmount($save['data'],$answer)]);
            }
        }catch (\Exception $e){
            return $this->sendError($e->getMessage(),406);
        }
        if(!$save['success']){
            return $this->sendError(config('errors')['other_error'],406);
        }
        $responseData = [
            'price'=>$sum,
            'discount'=>$answer['discount'],
            'discounted_price'=>$answer['discounted_price'],
            'collection_amount'=>$save['data']['collection_amount'],
            'order_id'=>$save['data']->id,
            'vendor_id'=>$save['data']->vendor_id
        ];

        if ($sum == $orders[$key_for_add]['price']){
            return $this->sendResponse($responseData,'order','Ok',200);
        }else{
            return $this->sendError(config('errors')['changed_price'],'406',$responseData);
        }

    }

    /**
     * @param $order
     * @param $order_type
     * @return float|int
     */
    private function getPrice($order,$order_type){
        $simpleSum=0;
        $menu = MenuItem::where('id',$order['item_id'])->first();
        if (!empty($menu)){
            $simpleSum= $menu->price;
        }
        if (is_array($order['add_ons']) && !empty($order['add_ons'])){
            foreach ($order['add_ons'] as $value){
                $item = MenuItemOptionValue::where('id',$value)->first();
                if (!empty($item)){
                    $simpleSum+= $item->price;
                }
            }
        }else{
            $item= MenuItemOptionValue::where('id',$order['add_ons'])->first();
            if (!empty($item)){
                $simpleSum+= $item->price;
            }
        }
        if (is_array($order['variants']) && !empty($order['variants'])){
            foreach ($order['variants'] as $value){
                $item=MenuItemOptionValue::where('id',$value)->first();
                if (!empty($item)){
                    $simpleSum+= $item->price;
                }
            }
        }else{
            $item=MenuItemOptionValue::where('id',$order['variants'])->first();
            if (!empty($item)){
                $simpleSum+= $item->price;
            }
        }
        if (!empty($menu)){
            $simpleSum+=$menu->container_price;
        }

        return $simpleSum * $order['quantity'];
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function place_orders(Request $request){
        $data = json_decode($request->place_orders,true);

        $data['status']='waiting';
        $data['transaction_id']=$this->setTransactionId($data['order_id'],$this->model);
        $select=['id as order_id','action as order','delivery_fee','schedule_time','transaction_id','created_at'];
        $where=['id'=>$data['order_id']];
        $response =  $this->_save($data,$data['order_id'],true,$select,$where);
        if ($response->getOriginalContent()['success']){
//            SendPushNotificationFromFirebase::sendPush(config('api.firebase.messages')['waiting']);
            $order = PendingOrders::where('id',$data['order_id'])->first();
            $notify_by_status = StatusesHistory::_save(['order_id'=>$order->id,'status'=>'waiting']);

            if ($order && $order->order_type != 1){
                Notifications::_save(['order_id'=>$order->id,'dash_api'=>1]);
            }else{
                Notifications::_save(['order_id'=>$order->id]);
            }
            /*
             * some changes in notification work only in cron
             *
             * see SendRequestDashDeliveryCommand
             *
             * */
            SendFirebaseNotificationHandlerService::sendRestaurantUser('waiting',$order);
            $notify_by_status['data']->update(['restaurant_firebase'=>1]);

//            EmailService::sendEmailWhenCreatedNewOrderToRestaurant(PendingOrders::find($data['order_id']));
        }
        return $this->correctFormForPlaceorder($response->getOriginalContent());
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function notify_from_dash_delivery(Request $request){
//        $task_id = $request->task_id??null;
//        $status = $request->status??null;
//        if ($task_id && $status){
//            $order = DashApi::where('task_id',$task_id)->first();
//            if ($order){
//                $order->update(['status'=>$status]);
//                $trackDelivery = DashDeliveryService::trackDelivery($order->get_order);
//                $rider_name='';
//                if (!empty($trackDelivery) && isset($trackDelivery->task) && isset($trackDelivery->task->user) && isset($trackDelivery->task->user->name)){
//                    $rider_name=$trackDelivery->task->user->name;
//                }
//                $order->get_order->update(['status'=>'status_'.$status,'rider_name'=>$rider_name]);
//                $notify_by_status=StatusesHistory::_save(['order_id'=>$order->get_order->id,'status'=>'status_'.$status]);
//                if ($status==303 || $status==304) PendingOrders::where('transaction_id',$order->order)->update(['seen'=>null]);
//                SendFirebaseNotificationHandlerService::sendUser('status_'.$status,$order->get_order);
//                $notify_by_status['date']->update(['user_firebase'=>1]);
//                return $this->sendResponse(null,null);
//            }
//            return $this->sendError(0);
//        }
        return $this->sendError(0);
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function courier_notify_from_dash_delivery(Request $request){
        $task_id = $request->task_id??null;
        $status = $request->status??null;
        if ($task_id && $status){
            $order = DashApi::where('task_id',$task_id)->first();
            if ($order){
                $order->update(['status'=>$status]);
                $trackDelivery = DashDeliveryService::trackDelivery($order->get_courier_order);
                $rider_name='';
                if (!empty($trackDelivery) && isset($trackDelivery->task) && isset($trackDelivery->task->user) && isset($trackDelivery->task->user->name)){
                    $rider_name=$trackDelivery->task->user->name;
                }
                $order->get_courier_order->update(['status'=>'status_'.$status,'rider_name'=>$rider_name]);
                $notify_by_status=StatusesHistory::_save(['courier_id'=>$order->get_courier_order->id,'status'=>'status_'.$status]);
                if ($status==303 || $status==304) CourierOrders::where('transaction_id',$order->courier_order)->update(['seen'=>null]);
                SendFirebaseNotificationCourierHandlerService::sendUser('status_'.$status,$order->get_courier_order);
                $notify_by_status['date']->update(['user_firebase'=>1]);
                return $this->sendResponse(null,null);
            }
            return $this->sendError(0);
        }
        return $this->sendError(0);
    }

    /**
     * @param $response
     * @return mixed
     */
    private function correctFormForPlaceorder($response){
        if (isset($response['order']['order'])){
            $order=json_decode($response['order']['order'],true);
            $first_part_array = $order[0];
            $first_part_array['created_at']=$response['order']['created_at'];
            $first_part_array['collection_amount']=OrderService::getOrderCollectionAmount(['id'=>$response['order']['order_id']],$order);
            array_shift($order);
            array_push($order,$first_part_array);
            $response['order']['order']=$order;
            $response['order']['schedule_time']=$response['order']['schedule_time']??'';
            unset($response['order']['created_at']);
            return $this->sendResponse($response['order'],'order','Ok',201);
        }
        return $this->sendError(config('api.error_message.other_error'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function get_user_orders(Request $request){
        $orders = PendingOrders::where('user_id',AuthService::getUser()['id'])->where('status','!=','pending')
            ->select([
                'created_at',
                'transaction_id',
                'status as order_status',
                'accept_message as status_message',
                'vendor_id as vendor_type_id',
                'vendor_id as vendor_type',
                'vendor_id',
                'vendor_id as vendor_name',
                'order_total as price',
                'discount',
                'discounted_price',
                'collection_amount',
                'user_id',
                'order_type',
                'schedule',
                'schedule_time',
                'delivery_fee',
                'cooking_directions',
                'order_notes',
                'address_id',
                'payment',
                'payment',
                'id as average_rating',
                'id as user_rating',
                'rider_name',
                'action as order',
            ])->get();
        if ($orders->count()){
            foreach ($orders as $key=>$order){
                $order->order_status = config('api.order.status_for_api.user_get_order')[$order->order_status];
                $order->vendor_type = $order->get_vendor->vendor_type->vendor_name;
                $order->vendor_id = $order->get_vendor->id;
                $order->vendor_name = $order->get_vendor->name;
                $order->discount=$order->discount??0;
                $order->discounted_price=$order->discounted_price??0;
                $order->collection_amount = $order->collection_amount??0;
                $order->average_rating = $order->get_vendor->average_rating;
                $user_rating = $order->ratings->where('user_id',AuthService::getUser()['id'])->first();
                $order->user_rating = $user_rating?$user_rating->vendor_rating:null;
                $order_action = json_decode($order->order,true);
                array_shift($order_action);
                $orders[$key]['order']=OrderService::changeOrderActionToStings($order_action);
                $order->vendor_id = $order->get_vendor->vendor_type->id;
                unset($orders[$key]->get_vendor);
            }
            return $this->sendResponse($orders,'vendor_orders');
        }
        return $this->sendResponse(null,null,'You have no orders yet');
    }
}
