<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\CreditHistory;
use App\Models\Loyalty;
use App\Models\PendingOrders;
use App\Models\RestaurantUsers;
use App\Models\UserCredits;
use App\Models\VendorBranch;
use App\Models\VendorTypes;
use App\Traits\ApiResources;
use App\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\Restaurant;
use App\Services\SendFirebaseNotificationHandlerService;

class LoyaltyController extends ApiControllers
{
    use ApiResources;
    /**
     * LoyaltyController constructor.
     * @param Loyalty $model
     */
    public function __construct(Loyalty $model)
    {
        $this->model = $model;
        $this->name = 'loyalty';
        $this->limit = 100;
    }

    /**
     * @return mixed
     */
    public function list_of_businesses()
    {
        $loyalty = Loyalty::get();
        $data = [];
        if ($loyalty->count()) {
            foreach ($loyalty as $value) {
                if (!$value->vendor_id) {
                   continue;
                } else {
                    $busines = [
                        'vendor_id' => $value->vendor->id,
                        'vendor_name' => $value->vendor->name,
                        'vendor_type_id'=> $value->vendor->vendor_type->id??'',
                        'vendor_code'=> $value->vendor->qr_code??'',
                        'spend_amount' => $value->spend,
                        'redemption_amount' => $value->redemption,
                        'image' => $value->image,
                        'branches'=>VendorBranch::where('vendor_id',$value->vendor->id)->select(['id as branch_id','name'])->get()->toArray()
                    ];
                    if ($value->vendor->redemptionServices->count()){
                        foreach ($value->vendor->redemptionServices as $redemptionService){
                            $busines['redemption_service'][]=['name'=>$redemptionService->name];
                        }
                    }else{
                        $busines['redemption_service']=[];
                    }
                    $data['businesses'][] = $busines;
                }
            }
        }
        return $this->sendResponse($data, $this->name);
    }

    /**
     * @return mixed
     */
    public function get_user_loyalty()
    {
        $loyalties = UserCredits::where('user_id', AuthService::getUser()['id'])->get();
        if ($loyalties->count()) {
            $data = [];
            $total_user_savings = 0;
            $total_available_credit = 0;
            foreach ($loyalties as $key => $value) {
                $total_user_savings += $value->used_credit;
                $total_available_credit += $value->available_credit;
                $redemption_service = [];
                if ($value->vendor->redemptionServices->count()){
                    foreach ($value->vendor->redemptionServices as $redemptionService){
                        $redemption_service[]=['name'=>$redemptionService->name];
                    }
                }
                $data['vendors'][] = [
                    'vendor_type_id' => $value->vendor->vendor_type->id,
                    'vendor_type' => $value->vendor->vendor_type->vendor_name,
                    'vendor_id' => $value->vendor->id,
                    'vendor_name' => $value->vendor->name,
                    'vendor_code' => $value->vendor->qr_code??'',
                    'current_spend' => $value->current_spend,
                    'available_credit' => $value->available_credit,
                    'total_spend' => $value->total_spend,
                    'used_credit' => $value->used_credit,
                    "spend_amount" => $value->vendor->loyalty->spend,
                    "redemption_amount" => $value->vendor->loyalty->redemption,
                    "image" => $value->vendor->loyalty->image,
                    'branches'=>VendorBranch::where('vendor_id',$value->vendor->id)->select(['id as branch_id','name'])->get()->toArray(),
                    'redemption_service'=>$redemption_service
                ];

            }
//            dd($total_user_savings);
//            array_unshift($data,$te);

            $te = $data;
            $te['total_available_credit'] = $total_available_credit;
            $te['total_user_savings'] = $total_user_savings;
            return $this->sendResponse($te, $this->name);
        }
        return $this->sendError('No Loyalties');
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function add_user_loyalty(Request $request)
    {
        $result = $this->validateData($request,[
            'phone_number' => ['required','integer','min:1'],
            'amount' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $vendor_id = AuthService::getRestaurantUser()['vendor_id'];
        $loyaltys = Loyalty::where('vendor_id', $vendor_id)->first();
        if($loyaltys && $loyaltys->status == 0){
            $message = 'You have been temporarily blocked from loyalty services, please make payments to continue your loyalty program';
            return $this->sendError($message);
        }
        $user = User::where('phone',$request->phone_number)->first();
        if ($user && $loyaltys){
            $user_credit = UserCredits::where(['vendor_id'=> $vendor_id,'user_id'=> $user->id])->first();
            if($user_credit){
                $total_spend = $user_credit->total_spend + $request->amount;
                $avaliable_credit = $user_credit->available_credit;
                $current_spend = $user_credit->current_spend + $request->amount;
                if ($loyaltys->spend<=$current_spend){
                    $current_spend = $current_spend - $loyaltys->spend;
                    $avaliable_credit += $loyaltys->redemption;
                }
                $user_credit->update([
                    'total_spend'=>$total_spend,
                    'available_credit'=>$avaliable_credit,
                    'current_spend'=>$current_spend
                ]);

                $restaurant = Restaurant::find($vendor_id);

                if($restaurant){
                    $restaurant_name = $restaurant->name;
                    $message = ['amount'=>$request->amount,'vendor_name'=>$restaurant_name,'status'=>'add_loyalty_amount'];
                    SmsService::sendSmsWhenVendorAddUserLoyaltyAmount($request->phone_number, $request->amount, $restaurant_name);
                    SendFirebaseNotificationHandlerService::sendUser($message,$user);
                }
                $data = [
                    'transaction_id'=> self::setTransactionIdForCredit(),
                    'user_id'=>$user->id,
                    'vendor_id'=> $vendor_id,
                    'vendor_type_id'=> $loyaltys->vendor->vendor_type->id,
                    'amount'=>$request->amount,
                    'txn_type'=>'Shopped',
                ];
                if (isset($request->branch_id) && $request->branch_id){
                    $data['branch_id']=$request->branch_id;
                }
                CreditHistory::_save($data);

                return $this->sendResponse(null,null);
            }
            $avaliable_credit=0;
            $used_credit=0;
            $total_spend = $request->amount;
            $current_spend = $request->amount;
            if ($loyaltys->spend<=$request->amount){
                $current_spend = $request->amount - $loyaltys->spend;
                $avaliable_credit += $loyaltys->redemption;
            }

            UserCredits::_save([
                'user_id'=>$user->id,
                'vendor_id'=>$vendor_id,
                'current_spend'=>$current_spend,
                'available_credit'=>$avaliable_credit,
                'total_spend'=>$total_spend,
                'used_credit'=>$used_credit,
            ]);
            $data = [
                'transaction_id'=> self::setTransactionIdForCredit(),
                'user_id'=>$user->id,
                'vendor_id'=> $vendor_id,
                'vendor_type_id'=> $loyaltys->vendor->vendor_type->id,
                'amount'=>$request->amount,
                'txn_type'=>'Shopped',
            ];
            if (isset($request->branch_id) && $request->branch_id){
                $data['branch_id']=$request->branch_id;
            }
            CreditHistory::_save($data);
            $restaurant = Restaurant::find($vendor_id);

            if($restaurant){
                $restaurant_name = $restaurant->name;
                $message = ['amount'=>$request->amount,'vendor_name'=>$restaurant_name,'status'=>'add_loyalty_amount'];
                SmsService::sendSmsWhenVendorAddUserLoyaltyAmount($request->phone_number, $request->amount, $restaurant_name);
                SendFirebaseNotificationHandlerService::sendUser($message,$user);
            }

            return $this->sendResponse(null,null);
        }
        return $this->sendError(0);
    }


}
