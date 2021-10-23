<?php


namespace App\Traits;


use App\Models\CourierOrders;
use App\Models\CreditHistory;
use App\Models\PendingOrders;
use Carbon\Carbon;

trait ApiResources
{

    public function __construct()
    {

    }

    /**
     * @param $id
     * @param $obj
     * @return int|string
     */

    public function setTransactionId($id,$obj){
        $item = $obj::find($id);
        if ($item && $item->transaction_id){
            return $item->transaction_id;
        }
        $data =Carbon::now()->format('y-d-m');
        $dataExploded = explode('-',$data);
        $transaction_id=implode('',$dataExploded).'0001';
//        $last_id = $obj::select('transaction_id')->orderBy('transaction_id','desc')->first();
//        if ($last_id && $last_id->transaction_id>=$transaction_id){
//            $transaction_id=$transaction_id+($last_id->transaction_id-$transaction_id)+1;
//        }
        return $this->uniqTransactionId($transaction_id);
    }

    public function setTransactionIdForCredit(){
//        $item = CreditHistory::find($id);
//        if ($item && $item->transaction_id){
//            return $item->transaction_id;
//        }
        $data =Carbon::now()->format('y-d-m');
        $dataExploded = explode('-',$data);
        $transaction_id = implode('',$dataExploded).'0001';

        return $this->uniqTransactionIdForCredit($transaction_id);

        }

    public function uniqTransactionIdForCredit($id){
        $credithistory = CreditHistory::where('transaction_id',"L".$id)->first();
        if($credithistory){
            $id = (int) substr($credithistory->transaction_id, 1);
            return $this->uniqTransactionIdForCredit($id+1);
        }
        else{
            return "L".$id;
        }
    }


    public function uniqTransactionId($id){
        $courier = CourierOrders::where('transaction_id',$id)->first();
        $order = PendingOrders::where('transaction_id',$id)->first();
        if($courier || $order){
            return $this->uniqTransactionId($id+1);
        }
        else{
            return $id;
        }
    }
}
