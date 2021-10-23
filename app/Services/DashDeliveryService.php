<?php


namespace App\Services;


use App\Models\Address;
use App\Models\DashApi;
use App\Models\PendingOrders;
use App\Models\CourierOrders;
use App\User;
use League\Flysystem\Config;

class DashDeliveryService
{
    /**
     * @return bool
     *
     * for this function now we not needed
     *
     */
//    public static function getAccessToken(){
//        $body=[
//            'grant_type'=>config('api.dash_delivery.get')['grant_type'],
//            'client_id'=>config('api.dash_delivery.get')['client_id'],
//            'client_secret'=>config('api.dash_delivery.get')['client_secret'],
//            'username'=>config('api.dash_delivery.get')['username'],
//            'password'=>config('api.dash_delivery.get')['password'],
//        ];
//        $token = SendGuzzleRequestService::sendByFormParams(
//            config('api.dash_delivery.get')['url'],
//            $body,['Content-Type'=>'application/x-www-form-urlencoded']
//        );
//        if ($token && $token->access_token){
//            return $token->access_token;
//        }
//        return false;
//    }

    /**
     * @param $order
     * @param string $class
     * @return bool
     */
    public static function sendRequestDashDelivery($order,$className = PendingOrders::class){
        $token = config('api.dash_delivery.add')['token'];
        if ($token){
            $order = $className::find($order->id);
            if ($order){
                $answer = self::addDeliveryTask($token,$order,$className);
            }
            if (isset($answer) && $answer && $className == PendingOrders::class){
                $order->notification->update(['dash_api'=>1]);
                return true;
            }
        }
        return false;
    }

    /**
     * @param $token
     * @param $order
     * @param $class
     * @return bool
     */
    public static function addDeliveryTask($token,$order,$className){
        $className= array_last(explode('\\',$className));
        $bodyFromFunctionName = 'bodyFrom'.$className;
        $user = User::find($order->user_id);
        $phone = $user->phone;
        if ($phone){
            $phone = substr($phone, 3);
            $phone = '075'.$phone;
        }
        $body = self::$bodyFromFunctionName($order,$phone,$user,$token);
        $response = SendGuzzleRequestService::sendByFormParams(
            config('api.dash_delivery.add')['url'],
            $body,
            [
                'Content-type'=>'application/x-www-form-urlencoded',
                'Authorization'=>'Bearer '.$token,
            ]
        );
        if (isset($response) && $response->success){
            $result = DashApi::_save($response,null,['token'=>$token,'user_id'=>$order->user_id],$className);
            if(!empty($result) && $result['success']){
                return true;
            }
        }
        return false;
    }

//    public static function getTask(){
//
//        $body=[
//            'task_id'=>'',
//        ];
//        $response = SendGuzzleRequestService::sendByFormParams(
//            config('api.dash_delivery.get_task')['url'],
//            $body,
//            [
//                'Content-type'=>'application/x-www-form-urlencoded',
//                'Authorization'=>'Bearer '.$token,
//            ]
//        );
//        if (isset($response) && $response->success){
//            return $response;
//        }
//        return false;
//    }

    /**
     * @param $order
     * @return bool|int|mixed
     *
     * from this function we get delivery information
     * we need that for get rider name
     */
    public static function trackDelivery($order){
        $token = config('api.dash_delivery.add')['token'];

        $body=[];
        if ($order && isset($order->get_dash_api) && isset($order->get_dash_api->task_id)){
            $response = SendGuzzleRequestService::sendForDelivery(
                config('api.dash_delivery.get_task')['url'].$order->get_dash_api->task_id,
                $body,
                [
                    'Content-type'=>'application/x-www-form-urlencoded',
                    'Authorization'=>'Bearer '.$token,
                ]
            );
            if (isset($response) && $response->success){
                return $response;
            }
        }
        return false;
    }

    /**
     * @param $order
     * @return bool|string
     * get restaurant address from order
     */
    public static function getAddressRestaurant($order){
        if (isset($order->get_vendor) && isset($order->get_vendor->address1)){
            $address =$order->get_vendor->address1;
        }
        if (isset($order->get_vendor) && isset($order->get_vendor->address2)){
            if (isset($address)){
                $address .=', '.$order->get_vendor->address2;
            }else{
                $address =$order->get_vendor->address2;
            }

        }
        if (isset($order->get_vendor) && isset($order->get_vendor->get_service_area)){
            if (isset($address)){
                $address .=', '.$order->get_vendor->get_service_area->name;
            }else{
                $address =$order->get_vendor->get_service_area->name;
            }
        }
        return isset($address)? $address:'';
    }

    /**
     * @param $id Address id
     * @return bool|string
     * get user address
     */
    public static function getAddressUser($id){
        $address = Address::find($id);
        if (isset($address->line_1) && $address->line_1){
            $order_address = $address->line_1;
        }
        if (isset($address->line_2) && $address->line_2){
            if (isset($order_address)){
                $order_address .=', '.$address->line_2;
            }else{
                $order_address = $address->line_2;
            }

        }
        if (isset($address->landmark) && $address->landmark){
            if (isset($order_address)){
                $order_address .=', '.$address->landmark;
            }else{
                $order_address = $address->landmark;
            }

        }
        if (isset($address->area) && $address->area){
            if (isset($order_address)){
                $order_address .=', '.$address->area->name;
            }else{
                $order_address = $address->area->name;
            }
        }
        return isset($order_address)? $order_address:'';
    }

    /**
     * @param $order
     * @param $phone
     * @param $user
     * @param $token
     * @return array
     */
    public static function bodyFromPendingOrders($order,$phone,$user,$token){
        return [
            'user_id'=> config('api.dash_delivery.add')['user_id'],
            'date'=> $order->created_at,
            'origin_address'=>DashDeliveryService::getAddressRestaurant($order),
            'origin_lng'=> isset($order->get_vendor)?$order->get_vendor->longitude:'',
            'origin_lat'=>isset($order->get_vendor)?$order->get_vendor->latitude:'',
            'customer_email'=>config('api.dash_delivery.add')['customer_email'],
            'customer_tel'=> $phone,
            'customer_name'=> $user ? $user->name : '',
            'destination_address'=> DashDeliveryService::getAddressUser($order->address_id),
            'destination_lng'=> (isset($order->address)&&$order->address->longitude)?$order->address->longitude:'',
            'destination_lat'=>(isset($order->address)&&$order->address->latitude)?$order->address->latitude:'',
            'order_id'=>''.$order->transaction_id,
            'weight'=> config('api.dash_delivery.add')['weight'],
            'order_amount'=> $order->order_total,
            'payment_amount'=> $order->order_total + $order->delivery_fee,
            'request_token'=>$token,
            'comments'=>$order->order_notes,
            'payment_method'=>config('api.order.payment')[ $order->payment]['payment_method'],
            'payment_status'=>config('api.order.payment')[ $order->payment]['payment_status'],
            'notify_url'=>url('/api/v1/notify-from-dash-delivery'),
        ];
    }

    /**
     * @param $order
     * @param $phone
     * @param $user
     * @param $token
     * @return array
     */
    public static function bodyFromCourierOrders($order,$phone,$user,$token){
        return [
            'user_id'=> config('api.dash_delivery.add')['user_id'],
            'date'=> $order->created_at->toDateTimeString(),
            'origin_address'=> $order->pick_up_address,
            'origin_lng'=>  (float) $order->pick_up_longitude ,
            'origin_lat'=>  (float) $order->pick_up_latitude,
            'customer_email'=>config('api.dash_delivery.add')['customer_email'],
            'customer_tel'=> $phone,
            'customer_name'=> $user ? $user->name : '',
            'destination_address'=> $order->delivery_address ,
            'destination_lng'=> (float) $order->delivery_longitude,
            'destination_lat'=> (float) $order->delivery_latitude,
            'order_id'=>''.$order->transaction_id,
            'weight'=> $order->weight,
            'order_amount'=> $order->price,
            'payment_amount'=>$order->price,
            'request_token'=>$token,
            'comments'=>$order->comments,
            'payment_method'=>config('api.courier_order.payment')[$order->payment]['payment_method'],
            'payment_status'=>config('api.courier_order.payment')[$order->payment]['payment_status'],
            'notify_url'=> url('/api/v1/courier/notify-from-dash-delivery'),
        ];
    }
}
