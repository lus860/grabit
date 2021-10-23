<?php


namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;

class SendFirebaseNotificationHandlerService
{
    /**
     * @param $message
     * @param $order
     * @return mixed
     */
    public static function sendRestaurantUser($message,$order,$type=null){
        if (is_array($message)){
            $function_name = $message['status'].'_restaurant';
        }else{
            $function_name = $message.'_restaurant';
        }
        return self::$function_name($message,$order,$type);
    }

    /**
     * @param $message
     * @param $order
     * @return mixed
     */
    public static function sendUser($message,$order){
        if (is_array($message)){
            $function_name = $message['status'];
        }else{
            $function_name = $message;
        }
        return self::$function_name($message,$order);
    }
    /**
     * @param $message
     * @param $order
     * @return mixed
     */
    public static function sendUserSenondType($message,$order,$type){
        if ($type==1){
            if (is_array($message)){
                $function_name = $message['status'];
            }else{
                $function_name = $message;
            }
            return self::$function_name($message,$order);
        }
        if (is_array($message)){
            $function_name = $message['status'].'_pick_up';
        }else{
            $function_name = $message.'_pick_up';
        }
        return self::$function_name($message,$order);


    }

    /**
     * @param $message
     * @param $order
     * @return mixed
     */
    public static function sendUserFromAdmin($message,$order){
        if (is_array($message)){
            $function_name = $message['status'].'_admin';
        }else{
            $function_name =$message.'_admin';
        }
        return self::$function_name($message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function waiting_restaurant($message,$order,$type){
        $order_type=config('api.order.order_type')[$order->order_type];
        $delivery_time =Setting::where(['title'=>'delivery','keyword'=>'delivery_time'])->first();
        $time = Carbon::parse($order->schedule_time);
        $time = $order->schedule == 2? $order->order_type == 1?$time->subMinutes($delivery_time->description): $time:'immediately';
        $time = gettype($time)=='object'?$time->toDateTimeString():$time;

        $title = $order->schedule == 1 ? 'order received':'scheduled order';
        $new_message['title'] = __('firebase_messages.food.vendor.'.$message.'.title',['order'=>$title]);
        $new_message['message'] = __('firebase_messages.food.vendor.'.$message.'.message',['order_type'=>$order_type,'time'=>$time,'transaction_id'=>$order->transaction_id]);

        SendPushNotificationFromFirebase::sendRestaurantUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function accepted_restaurant($message,$order,$type =null){
        $order_type=config('api.order.order_type')[$order->order_type];
        $delivery_time =Setting::where(['title'=>'delivery','keyword'=>'delivery_time'])->first();
        $time = Carbon::parse($order->schedule_time);
        $time = $order->schedule == 2? $order->order_type == 1?$time->subMinutes($delivery_time->description): $time:'immediately';
        $time = gettype($time)=='object'?$time->toDateTimeString():$time;
        if ($type){
            $new_message['title'] = __('firebase_messages.food.vendor.reminder.title');
            $new_message['message'] = __('firebase_messages.food.vendor.reminder.message',['order_type'=>$order_type,'time'=>$time,'transaction_id'=>$order->transaction_id]);
        }else{
            $new_message['title'] = __('firebase_messages.food.vendor.'.$message['status'].'.title');
            $new_message['message'] = __('firebase_messages.food.vendor.'.$message['status'].'.message',['order_type'=>$order_type,'time'=>$time,'transaction_id'=>$order->transaction_id]);
        }
        SendPushNotificationFromFirebase::sendRestaurantUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function accepted($message,$order){
        $restaurant_name = $order->get_vendor->name;
        if ($order->order_type==1){
            $new_message['title'] = __('firebase_messages.food.user.'.$message['status'].'.title');
            $new_message['message'] = __('firebase_messages.food.user.'.$message['status'].'.message',['restaurant_name'=>$restaurant_name,'transaction_id'=>$order->transaction_id]);
        }else{
            $new_message['title'] = __('firebase_messages.food.user.accepted_pick_up.title');
            $new_message['message'] = __('firebase_messages.food.user.accepted_pick_up.message',['restaurant_name'=>$restaurant_name,'transaction_id'=>$order->transaction_id]);
        }
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function accepted_pick_up($message,$order){
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.food.user.'.$message['status'].'.title');
        $new_message['message'] = __('firebase_messages.food.user.'.$message['status'].'.message',['restaurant_name'=>$restaurant_name,'transaction_id'=>$order->transaction_id]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function Cancelled($message,$order){
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.food.user.'.$message['status'].'.title');
        $new_message['message'] = __('firebase_messages.food.user.'.$message['status'].'.message',['restaurant_name'=>$restaurant_name,'accept_message'=>$message['accept_message'],'transaction_id'=>$order->transaction_id]);

        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_301($message,$order){
        $rider_name='';
        if (!empty($order->rider) && isset($order->rider->name)){
            $rider_name=$order->rider->name;
        }
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.food.user.'.$message.'.title');
        $new_message['message'] = __('firebase_messages.food.user.'.$message.'.message',['rider_name'=>$rider_name,'restaurant_name'=>$restaurant_name]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_302($message,$order){
        $rider_name='';
        if (!empty($order->rider) && isset($order->rider->name)){
            $rider_name=$order->rider->name;
        }
        $new_message['title'] = __('firebase_messages.food.user.status_302.title');
        $new_message['message'] = __('firebase_messages.food.user.status_302.message',['rider_name'=>$rider_name]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_303($message,$order){
//        $trackDelivery = DashDeliveryService::trackDelivery($order);
        $rider_name='';
        if (!empty($order->rider) && isset($order->rider->name)){
            $rider_name=$order->rider->name;
        }
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.food.user.'.$message.'.title');
        $new_message['message'] = __('firebase_messages.food.user.'.$message.'.message',['rider_name'=>$rider_name,'restaurant_name'=>$restaurant_name,'transaction_id'=>$order->transaction_id]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_304($message,$order){
        $rider_name='';
        if (!empty($order->rider) && isset($order->rider->name)){
            $rider_name=$order->rider->name;
        }
        $new_message['title'] = __('firebase_messages.food.user.status_304.title');
        $new_message['message'] = __('firebase_messages.food.user.status_304.message',['rider_name'=>$rider_name]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function dispatch($message,$order){
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.food.user.'.$message.'.title');
        $new_message['message'] = __('firebase_messages.food.user.'.$message.'.message',['restaurant_name'=>$restaurant_name]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function Cancelled_admin($message,$order){
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.messages_user_from_admin.'.$message['status'].'.title');
        $new_message['message'] = __('firebase_messages.messages_user_from_admin.'.$message['status'].'.message',['restaurant_name'=>$restaurant_name,'accept_message'=>$message['accept_message']]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_303_admin($message,$order){
        $trackDelivery = DashDeliveryService::trackDelivery($order);
        $rider_name='';
        if (!empty($trackDelivery) && isset($trackDelivery->task) && isset($trackDelivery->task->user) && isset($trackDelivery->task->user->name)){
            $rider_name=$trackDelivery->task->user->name;
        }
        $restaurant_name = $order->get_vendor->name;
        $new_message['title'] = __('firebase_messages.messages_user_from_admin.'.$message.'.title');
        $new_message['message'] = __('firebase_messages.messages_user_from_admin.'.$message.'.message',['rider_name'=>$rider_name,'restaurant_name'=>$restaurant_name]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $order
     */
    public static function sendAdmin($function_name,$data){
        return self::$function_name($data);
    }

    /**
     * @param $vendor
     */
    private static function vendor_offline($vendor){
        $new_message['title'] =__('firebase_messages.admin.vendor_offline.title');
        $new_message['message'] = __('firebase_messages.admin.vendor_offline.message',['vendor_name'=>$vendor->name]);
        SendPushNotificationFromFirebase::sendAdminNotification($new_message);
    }

    /**
     * @param $order
     */
    private static function sendAdminAboutOrder($order){
        $new_message['title'] =__('firebase_messages.admin.about_order.title',['schedule'=>$order->schedule]);
        $new_message['message'] = __('firebase_messages.admin.about_order.message',['vendor_name'=>$order->get_vendor->name,'transaction_id'=>$order->transaction_id,'created_at'=>$order->created_at]);
        SendPushNotificationFromFirebase::sendAdminNotification($new_message);
    }

    /**
     * @param $item
     */
    private static function product_not_item($item){
        $new_message['title'] =__('firebase_messages.admin.product_not_item.title');
        $new_message['message'] = __('firebase_messages.admin.product_not_item.message',
            ['vendor_name'=>$item->Menu->get_vendor->name,'item_name'=>$item->name]);
        SendPushNotificationFromFirebase::sendAdminNotification($new_message);
    }

    /**
     * @param $option
     */
    private static function product_not($option){
        $new_message['title'] =__('firebase_messages.admin.product_not.title');
        $new_message['message'] = __('firebase_messages.admin.product_not.message',
            ['vendor_name'=>$option->menuOption->menuItem->Menu->get_vendor->name,
                'item_name'=>$option->menuOption->name,
                'option_name'=>$option->value,
            ]);
        SendPushNotificationFromFirebase::sendAdminNotification($new_message);
    }

    /**
     * @param $option
     */
    private static function overdue_order($order){
        $new_message['title'] =__('firebase_messages.admin.overdue_order.title');
        $new_message['message'] = __('firebase_messages.admin.overdue_order.message',
            ['order_type'=>config('api.order.order_type')[$order->order_type],
                'vendor_name'=>$order->get_vendor->name,
            ]);
        SendPushNotificationFromFirebase::sendAdminNotification($new_message);
    }


    /**
     * @param $option
     */
    private static function add_loyalty_amount($message, $user){
        $new_message['title'] =__('firebase_messages.admin.add_loyalty_amount.title', ['vendor_name'=>$message['vendor_name']]);
        $new_message['message'] = __('firebase_messages.admin.add_loyalty_amount.message', ['amount'=>$message['amount']]);
        SendPushNotificationFromFirebase::sendUserWithoutOrder($new_message,$user);
    }

    private static function check_loyalty_amount_user($message,$user,$type =null){
        $new_message['title'] ='Loyalty redeemed at '.$message['vendor_name'];
        $new_message['message'] ='You have redeemed Tzs '. $message['amount']. ' at ' . $message['vendor_name']. ' on ' .$message['created_at'] ;
        SendPushNotificationFromFirebase::sendUserWithoutOrder($new_message,$user);
    }

    private static function check_loyalty_amount_vendor_restaurant($message,$user,$type =null){
        $new_message['title'] ='Loyalty redeemed by '.$message['user_name'];
        $new_message['message'] = $message['user_name'].' has reedemed Tsz '. $message['amount'] . ' at your store on '. $message['created_at']. ' time';
        SendPushNotificationFromFirebase::sendRestaurantUserWithoutOrder($new_message,$user);
    }

    public static function send_notification_for_new_user($user){
        $new_message['title'] ='Hello '.$user->name??'Dear Customer';
        $new_message['message'] ='Welcome to Grab it. You can easily schedule couriers and earn loyalty points whenever you shop at your local vendors.';
        SendPushNotificationFromFirebase::sendUserWithoutOrder($new_message,$user);
    }

    public static function new_order_for_rider_user($order){
        $new_message['title'] ='New order for delivery';
        $new_message['message'] ='You have received a new order,open the application and proceed ASAP!';
        SendPushNotificationFromFirebase::sendRiderUser($new_message,$order);
    }
    public static function dispatch_vendor_user_order($order){
        $new_message['title'] ='Rider en route to pick up point';
        $new_message['message'] ='Good news! '. $order->rider_name .' is en route to the pick up location to collect your parcel. Please make all neccessary arrangements so the rider does not have to wait any longer';
        SendPushNotificationFromFirebase::sendRestaurantUser($new_message,$order);
    }

    public static function accepted_vendor_user_order($order){
        $new_message['title'] ='Courier order accepted';
        $new_message['message'] ='Your order is accepted of transaction ID'. $order->transaction_id. '. Our rider is being dispatched to your provided pick up location';
        SendPushNotificationFromFirebase::sendRestaurantUser($new_message,$order);
    }

    private static function message_user_restaurant($message,$vendor,$type =null){
        $new_message['title'] ='Message from '. $message['user_name'];
        $new_message['message'] = $message['message'];
        SendPushNotificationFromFirebase::sendRestaurantUserWithoutOrder($new_message,$vendor);
    }

    private static function message_vendor($message_vendor, $user){
        $new_message['title'] = 'Message from '. $message_vendor['vendor_name'];
        $new_message['message'] = $message_vendor['message'];
       SendPushNotificationFromFirebase::sendUserWithoutOrder($new_message,$user);
    }

    private static function notification_user_restaurant($message,$vendor_user,$type =null){
        $new_message['title'] = 'Payment reminder';
        $new_message['message'] = 'If you have not paid your subscription fee, please make payments as soon as possible to avoid being blocked from vendor services';
        SendPushNotificationFromFirebase::sendRestaurantUserWithoutOrder($new_message,$vendor_user);
    }

}
