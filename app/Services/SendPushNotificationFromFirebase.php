<?php


namespace App\Services;


use App\Models\ClientSources;
use App\Models\NotificationsHistory;
use App\Models\PendingOrders;
use App\Models\RestaurantUsers;
use App\Models\VendorUsersHistory;
use App\User;
use Illuminate\Http\Request;


class SendPushNotificationFromFirebase
{
    protected static $serverKey;

    /**
     * @param $message
     * @param $order
     */
    public static function sendRestaurantUser($message,$order){
        $token = self::getTokenRestaurantUser($order);
        if ($token && $token->source_id == 1){
            self::$serverKey=config('app.restaurant_firebase_server_key_android');
        }elseif($token && $token->source_id == 2){
            self::$serverKey=config('app.restaurant_firebase_server_key_ios');
        }
        if ($token){
            return self::sendPush($token->firebase_token,$message);
        }
        return false;
    }

    /**
     * @param $message
     * @param $order
     * @return bool
     */
    public static function sendRiderUser($message,$order){
        $token = self::getTokenRiderUser($order);
        if ($token && $token->source_id == 2){
            self::$serverKey=config('app.rider_firebase_server_key_android');
        }elseif($token && $token->source_id == 1){
            self::$serverKey=config('app.rider_firebase_server_key_ios');
        }
        if ($token){
            return self::sendPush($token->firebase_token,$message);
        }
        return false;
    }

    /**
     * @param $message
     * @param $order
     */
    public static function sendRestaurantUserWithoutOrder($message,$vendor){
        $tokens = self::getTokenRestaurantUserByVendor($vendor);
        if ($tokens && $tokens->count()){
            foreach ($tokens as $token){
                if ($token && $token->source == 2){
                    self::$serverKey=config('app.restaurant_firebase_server_key_android');
                }elseif($token && $token->source == 1){
                    self::$serverKey=config('app.restaurant_firebase_server_key_ios');
                }
                if ($token){
                    self::sendPush($token->firebase_token,$message);
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @param $message
     * @param $order
     */
    public static function sendUser($message,$order){
        $token = self::getTokenUserById($order->user_id);
        if ($token && $token->source_id == 1){
            self::$serverKey=config('app.firebase_server_key_android');
        }elseif($token && $token->source_id == 2){
            self::$serverKey=config('app.firebase_server_key_ios');
        }
        if ($token){
            self::sendPush($token->firebase_token,$message);
            return self::store_notification($message,$order->user_id);
        }
        return false;
    }

    public static function sendUserWithoutOrder($message,$user){
        if (is_array($user)){
            $token = self::getTokenUserById($user['id']);
        }else{
            $token = self::getTokenUserById($user->id);
        }
        if ($token && $token->source_id == 2){
            self::$serverKey=config('app.firebase_server_key_android');
        }elseif($token && $token->source_id == 1){
            self::$serverKey=config('app.firebase_server_key_ios');
        }
        if ($token){
            self::sendPush($token->firebase_token,$message);
            if(is_array($user)){
                return self::store_notification($message,$user['id']);
            }
            return self::store_notification($message,$user->id);
        }
        return false;
    }

    /**
     * @param $message
     * @param $order
     */
    public static function sendUsersFromAdmin($message,$user){
        if (gettype($user) == 'object'){
            $token = self::getTokenUserById($user->id);
        }else{
            $token = self::getTokenUserById($user);
        }
        if ($token && $token->source_id == 1){
            self::$serverKey=config('app.firebase_server_key_android');
        }elseif($token && $token->source_id == 2){
            self::$serverKey=config('app.firebase_server_key_ios');
        }
        if ($token){
            return self::sendPush($token->firebase_token,$message);
        }
//        if ($token){
//            return self::sendPush($token,$message);
//        }
        return false;
    }

    /**
     * @param $message
     */
    public static function sendPushAdmin($message){
        $token = self::getTokenAdmin();
        if ($token->source_id == 1){
            self::$serverKey=config('app.firebase_server_key_android');
        }elseif($token->source_id == 2){
            self::$serverKey=config('app.firebase_server_key_ios');
        }
        if ($token){
            return self::sendPush($token->firebase_token,$message);
        }
        return false;
    }
    /**
     * @param $message
     */
    public static function sendAdminNotification($message){
        $admins = self::getAdmins();
        if ($admins && $admins->count()){
            foreach ($admins as $admin){
                if ($admin->source->source_id == 1){
                    self::$serverKey=config('app.firebase_server_key_android');
                }elseif($admin->source->source_id == 2){
                    self::$serverKey=config('app.firebase_server_key_ios');
                }
                if ($admin->source){
                    self::sendPush($admin->source->firebase_token,$message);
                }
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private static function getAdmins(){
        $user = User::where('user_type',1)->first();
        if ($user){
            return $user;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function getTokenAdmin(){
        $token = ClientSources::where('firebase_token','!=',null)->select('firebase_token')->get();
        if ($token->count()) return $token->toArray();
        return false;
    }

    /**
     * @return bool
     */
    public static function getTokenUser(){
        $token=\Illuminate\Support\Facades\Request::header('token');
        if (gettype($token) =='object'){
            $token=$token->header('token');
        }
        $user = ClientSources::where(
            ['user_id'=>AuthService::getUser()['id'],
                'token'=>$token])->first();
        if ($user){
            return $user;
        }
        return false;
    }

    public static function getTokenUserById($id){
        $user = ClientSources::where(['user_id'=>$id])->first();
        if ($user){
            return $user;
        }
        return false;
    }

    public static function getTokenRestaurantUser(PendingOrders $order){
        if ($order->vendor_id){
            $token = VendorUsersHistory::where(['vendor_id'=>$order->vendor_id])->first();
            if ($token && $token->firebase_token){
                return $token;
            }
        }
        return false;
    }

    public static function getTokenRiderUser($order){
        if ($order->rider){
            $token = $order->rider->source;
            if ($token && $token->firebase_token){
                return $token;
            }
        }
        return false;
    }

    public static function getTokenRestaurantUserByVendor($vendor){
        if ($vendor){
            $token = VendorUsersHistory::where(['vendor_id'=>$vendor['id']])->get();
            if ($token && $token->count()){
                return $token;
            }
        }
        return false;
    }

    private static function store_notification($message,$id){
        if ($id){
            $data=[
                'user_id'=>$id,
                'title'=>$message['title'],
                'message'=>$message['message'],
            ] ;
            return NotificationsHistory::_save($data);
        }
        return false;
    }


    public static function sendPush ($token,$message)
    {
        $serverKey = self::$serverKey;
        $data = [
            "to" => $token,
            "notification" =>
                [
                    "title" => $message['title'],
                    "body" => $message['message'],
                    "icon" => asset(config('api.firebase.push_notification')['icon'])
                ],
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $serverKey,
//            'Authorization: key=' . self::$serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $x = curl_exec($ch);
        return true;
    }

//    public static function sendNotificationIos($token,$title,$message)
//    {
//        self::setServerKey();
//
//        $url = "https://fcm.googleapis.com/fcm/send";
//        $registrationIds = $token;
//        $serverKey =self::$serverKey;
//        $title = $title;
//        $body = $message;
//        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' =>'1');
//
//        $arrayToSend = array('to' => $registrationIds, 'notification'=>$notification,'priority'=>'high');
//        $json = json_encode($arrayToSend);
//        $headers = array();
//        $headers[] = 'Content-Type: application/json';
//        $headers[] = 'Authorization: key='. $serverKey;
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        //Send the request
//        $result = curl_exec($ch);
//        if ($result === FALSE)
//        {
//            die('FCM Send Error: ' . curl_error($ch));
//        }
//
//        curl_close( $ch );
//        return $result;
//    }
//
//    public static function sendNotificationAndroid($token,$title,$message)
//    {
//        self::setServerKey();
//        $registrationIds = $token;
//        // prep the bundle
//        $msg = array
//        (
//            'message' => $message,
//            'title' => $title,
//            'subtitle' => 'This is a subtitle. subtitle',
//            'vibrate' => 1,
//            'sound' => 1,
//            'largeIcon' => 'large_icon',
//            'smallIcon' => 'small_icon'
//        );
//
//        $fields = ['to ' => $registrationIds, 'data' => $msg];
//
////            'to ' => $registrationIds //for single user
////	'registration_ids' => $registrationIds, //  for  multiple users
//
//        $headers = ['Authorization: key=' . self::$serverKey, 'Content-Type: application/json'];
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//        $result = curl_exec($ch);
//        if ($result === FALSE) {
//            die('FCM Send Error: ' . curl_error($ch));
//        }
//        curl_close($ch);
//        return $result;
//    }
//
//    public static function sendPushByOrder (string $message,$user_id)
//    {
//        $token = self::getTokenUserById($user_id);
//        if ($token) {
//            self::setServerKey();
//
//            $data = [
//                "to" => $token,
//                "notification" =>
//                    [
//                        "title" => config('api.firebase.push_notification')['title'],
//                        "body" => $message,
//                        "icon" => asset(config('api.firebase.push_notification')['icon'])
//                    ],
//            ];
//            $dataString = json_encode($data);
//
//            $headers = [
//                'Authorization: key=' . self::$serverKey,
//                'Content-Type: application/json',
//            ];
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
//            $x = curl_exec($ch);
//            return true;
//        }
//        return false;
//    }
}
