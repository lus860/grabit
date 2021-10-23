<?php


namespace App\Services;


use App\Models\CourierOrders;
use App\Models\PendingOrders;
use App\Models\StatusesHistory;

class OurDashModuleService
{
    public static function changeStatusToOrder($order,$status,$type=false){
//        $order->update(['status'=>$status]);
        $notify_by_status=StatusesHistory::_save([$type?'courier_id':'order_id'=>$order->id,'status'=>$status]);
        if ($status=='status_303' || $status=='status_304') $order->update(['seen'=>null]);
        if ($type){
            SendFirebaseNotificationCourierHandlerService::sendUser($status,$order);
        }else{
            SendFirebaseNotificationHandlerService::sendUser($status,$order);
        }
        $notify_by_status['data']->update(['user_firebase'=>1]);
        return true;
    }
}
