<?php


namespace App\Services;


use Illuminate\Http\Request;
use Symfony\Component\Mime\Encoder\Base64Encoder;

class SmsService
{
//    public function sendSms(Request $request){
//        $data=[];
//
////        if ($request->input('phone_number')){
////            $data['phone_number']=['required','integer'];
////        }
////        if ($request->input('email')){
////            $data['email']=['required','email'];
////        }
////        if (empty($data)){
////            return $this->sendError('not parameter',400);
////        }
////        $result = $this->validateData($request,$data);
////        if (gettype($result) == 'object'){
////            return $result;
////        }
//        if ($request->input('phone_number') && !empty($request->input('phone_number'))){
//            $where['phone']=$request->input('phone_number');
//        }
//        if ($request->input('email') && !empty($request->input('email'))){
//            $where['email']=$request->input('email');
//        }
//        $user=User::where($where)->first();
//        if ($user){
//            $otp=AuthService::getOtp(User::class,$user);
//            if ($user->phone){
//                SmsService::sendOtp($otp,$user->phone);
//            }
//            if ($user->email){
//                EmailService::sendEmailUserForOtp($user,$otp);
//            }
//            return $this->sendResponse(null,null,1,200);
//        }
//        return $this->sendError(0);
//
//    }
    /**
     * @param $order
     * @param $message
     * @return int
     */
    public static function sendSmsWhenOrderCancelled($order,$message){
        $message=str_replace('"','',$message);
        return self::sendSmsForm($message,$order->user->phone);
    }

    public static function sendSmsWhenVendorAddUserLoyaltyAmount($phone,$amount, $vendor_name){
        $message = 'You have spent Tzs '. $amount. ' at ' .$vendor_name. ' and collected loyalty points';
        return self::sendSmsForm($message,$phone);
    }
    /**
     * @param $order
     * @param $message
     * @return int
     */
    public static function sendSmsWhenOrderCancelledCourier($order,$message){
        $message=str_replace('"','',$message);
        $message = __('firebase_messages.courier_messages.sms.cancelled',['transaction_id'=>$order->transaction_id,'cancellation_message'=>$message]);
        return self::sendSmsForm($message,$order->user->phone);
    }
    public static function sendSmsWhenVendorCheckUserLoyaltyAmount($user,$amount, $created_at, $phone){
        $message = $user['name'].' has reedemed Tsz '. $amount . ' at your store on '. $created_at. ' time' ;
        return self::sendSmsForm($message,$phone);
    }

    /**
     * @param $message
     * @param $phone
     * @return int
     */
    public static function sendOtp($message,$phone){
        return self::sendSmsForm(config('api.sms_fasthub')['message'].$message,$phone);
    }

    /**
     * @param $message
     * @param $phone
     * @return int
     */
    public static function sendSmsForm($message,$phone){
        $client = new \GuzzleHttp\Client();
//        $message="We\'re sorry to inform you that first restroan has cancelled your order number 2022050002 due to Cancelled by Admin Grab it";
        $body="{
            \"channel\":{
                \"channel\":".config('api.sms_fasthub.config')['channel'].",
                \"password\":\"".base64_encode(hash("sha256", config('api.sms_fasthub.config')['password']))."\"
            },
            \"messages\":[
                {
                    \"text\":\"".$message."\",
                    \"msisdn\":\"".$phone."\",
                    \"source\":\"".config('api.sms_fasthub.config')['source']."\"
                }
	        ]}";

        $response = $client->post(config('api.sms_fasthub.config')['url'],[
                'headers'=>[
                    'Content-type'=> config('api.sms_fasthub.config')['headers']],
                    'Content-type'=> 'text/plain',
                'body'=>$body]);

        $answer = json_decode($response->getBody());
        if ($answer->isSuccessful){
            return 1;
        }
        return 0;
    }

}
