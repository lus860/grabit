<?php


namespace App\Services;


use App\Jobs\SendEmail;
use App\Mail\AdminNotification;
use App\Mail\AdminToUserNotification;
use App\Mail\CourierOrderNotification;
use App\Mail\ForgotPasswordNotification;
use App\Mail\NewOrderForRestaurantNotification;
use App\Mail\OrderNotification;
use App\Mail\RestaurantUserCreatedNotification;
use App\Mail\SendOtpToUserNotification;
use App\Mail\UserNotification;
use App\Models\PendingOrders;
use App\Models\Restaurant;
use App\Models\RestaurantEmail;
use App\Models\RestaurantUsers;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * @param Restaurant $restaurant
     * @param RestaurantUsers $user
     */
    public static function sendEmailWhenNewRestaurantUserRegistered(Restaurant $restaurant,RestaurantUsers $user){
        Mail::to($user->email)->send(new RestaurantUserCreatedNotification($restaurant));
//        Mail::to('agnessa.antabian@gmail.com')->send(new RestaurantUserCreatedNotification($restaurant));
    }

    /**
     * @param $order
     */
    public static function sendEmailWhenOrderCancelled($order){
        Mail::to($order->user->email)->send(new OrderNotification('cancelled',$order));
    }
    /**
     * @param $order
     */
    public static function sendEmailCourierWhenOrderCancelled($order){
        Mail::to($order->user->email)->send(new CourierOrderNotification('cancelled',$order));
    }

    /**
     * @param RestaurantUsers $user
     * @param Restaurant $restaurant
     * @param $otp
     */
    public static function sendEmailWhenForgetPasswordRestaurantUser(RestaurantUsers $user,Restaurant $restaurant,$otp){
        Mail::to($user->email)->send(new ForgotPasswordNotification($restaurant,$otp));
    }

    /**
     * @param $user
     * @param $otp
     */
    public static function sendEmailUserForOtp($user,$otp,$type){
        Mail::to($user->email)->send(new SendOtpToUserNotification($user->name,$otp,$type));
    }

    /**
     * @param User $user
     */
    public static function sendEmailWhenNewUserRegistered(User $user){
        Mail::to($user->email)->send(new UserNotification('create',$user));
    }

    /**
     * @param $email
     * @param $token
     */
    public static function sendEmailWhenUserUpdateEmail($email,$token){
        $user = AuthService::getUser();
        config(['api.global.updated_email_'.$user['id']=>$email]);
        Mail::to($email)->send(new UserNotification('update_email',(object)$user,$token));
    }

    /**
     * @param $data
     * @param $email
     */
    public static function sendEmailFromAdminToUsers($data,$email){
        Mail::to($email)->send(new AdminToUserNotification($data));
    }

//    /**
//     *
//     */
//    public static function sendEmailWhenHaveNewOrder($count){
//
////            $details['email']=config('api.admin')['email'];
//////            $details['restaurant']=$restaurant;
////            $details['order']=$count;
////            SendEmail::dispatch($details);
//////
////        Mail::to(config('api.admin')['email'])->send(new AdminNotification($count));
//    }

//    /**
//     * @param PendingOrders $order
//     */
//    public static function sendEmailWhenCreatedNewOrderToRestaurant(PendingOrders $order){
//        $restaurant = Restaurant::find($order->restaurant_id);
//        $emails = RestaurantEmail::where('restaurant_id',$order->restaurant_id)->select('email')->get()->toArray();
//        $emails[]['email']=$restaurant->user->email;
//        $details['emails']=$emails;
//        $details['restaurant']=$restaurant;
//        $details['order']=$order;
//        foreach ($emails as $email){
//            $details['email']=$email;
//            $details['restaurant']=$restaurant;
//            $details['order']=$order;
//            SendEmail::dispatch($details);
//        }
////        Artisan::queue('work');
////        if ($restaurant->restaurantEmail->count()){
////            foreach ($emails as $email){
////                Mail::to($email['email'])->send(new NewOrderForRestaurantNotification($restaurant,$order));
////            }
////            Mail::to($restaurant->user->email)->send(new NewOrderForRestaurantNotification($restaurant,$order));
//////            Mail::to($restaurant->user->email)->cc($emails)->send(new NewOrderForRestaurantNotification($restaurant,$order));
////        }else{
////            Mail::to($restaurant->user->email)->send(new NewOrderForRestaurantNotification($restaurant,$order));
////        }
//    }

}
