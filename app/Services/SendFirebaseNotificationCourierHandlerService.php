<?php


namespace App\Services;

use App\Models\Setting;

class SendFirebaseNotificationCourierHandlerService
{
    /**
     * @param $message
     * @param $order
     * @return mixed
     */
    public static function sendUser($message,$order,$courier=false){
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
     */
    private static function accepted($message,$order){
        $new_message['title'] = __('firebase_messages.courier_messages.notification.accepted.title');
        $new_message['message'] = __('firebase_messages.courier_messages.notification.accepted.message',['transaction_id'=>$order->transaction_id]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }


    /**
     * @param $message
     * @param $order
     */
    private static function Cancelled($message,$order){
        $new_message['title'] = __('firebase_messages.courier_messages.notification.cancelled.title');
        $new_message['message'] = __('firebase_messages.courier_messages.notification.cancelled.message',['transaction_id'=>$order->transaction_id,'cancellation_message'=>$order->status_text]);
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
        $new_message['title'] = __('firebase_messages.courier_messages.notification.status_301.title');
        $new_message['message'] = __('firebase_messages.courier_messages.notification.status_301.message',['rider_name'=>$rider_name]);
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
        $new_message['title'] = __('firebase_messages.courier_messages.notification.status_302.title');
        $new_message['message'] = __('firebase_messages.courier_messages.notification.status_302.message',['rider_name'=>$rider_name]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_303($message,$order){
        $rider_name='';
        if (!empty($order->rider) && isset($order->rider->name)){
            $rider_name=$order->rider->name;
        }
        $new_message['title'] = __('firebase_messages.courier_messages.notification.status_303.title');
        $new_message['message'] = __('firebase_messages.courier_messages.notification.status_303.message',['rider_name'=>$rider_name,'transaction_id'=>$order->transaction_id]);
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }

    /**
     * @param $message
     * @param $order
     */
    private static function status_304($message,$order){
//        $trackDelivery = DashDeliveryService::trackDelivery();
//        $rider_name='';
//        if (!empty($trackDelivery) && isset($trackDelivery->rider) && $trackDelivery->rider){
//            #TODO add rider name to Message
//        }
//        $restaurant_name = $order->restaurant->name;
//        $new_message['title'] = __('firebase_messages.messages_user.'.$message.'.title');
//        $new_message['message'] = __('firebase_messages.messages_user.'.$message.'.message',['rider_name'=>$rider_name,'restaurant_name'=>$restaurant_name]);
////
//        $new_message['title'] = 'title for status 304';
//        $new_message['message'] ='simple message';
        $new_message['title'] = 'test title courier';
        $new_message['message'] = 'test message for courier status_304';
        SendPushNotificationFromFirebase::sendUser($new_message,$order);
    }
}
