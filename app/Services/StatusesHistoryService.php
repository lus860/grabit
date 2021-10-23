<?php


namespace App\Services;


use App\Models\StatusesHistory;

class StatusesHistoryService
{
    public static function get_history_statuses($order_id,$courier = null){
        $field = $courier ? 'courier_id' : 'order_id';
        $status_canceled = StatusesHistory::where([ $field =>$order_id,'status'=>'Cancelled'])->get();
//        $status_304 = StatusesHistory::where(['order_id'=>$order_id,'status'=>'status_304'])->get();
        if ($status_canceled->count()){
            return $status_canceled;
        }
//        if ($status_304->count()){
//            return $status_304;
//        }
        return StatusesHistory::where([$field => $order_id])->get();
    }
}
