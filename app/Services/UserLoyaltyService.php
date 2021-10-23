<?php


namespace App\Services;


use App\Models\Loyalty;
use App\Models\UserCredits;

class UserLoyaltyService
{
    /**
     * @param $order
     * @param $user_id
     * @return string
     */
    public static function checkUserAvailableCredit($order,$user_id)
    {
        $user_credit = UserCredits::where(['user_id' => $user_id, 'vendor_id' => $order['vendor_id']])->first();
        if ($user_credit) {
            $loyalty_amount = $order['loyalty_amount'] ?? 0;
            $vendor_loyalty = Loyalty::where('vendor_id',$order['vendor_id'])->first();
            if ($loyalty_amount) {
                if (!$vendor_loyalty){
                    return 'This vendor is not enrolled with the loyalty program';
                }
                if ($loyalty_amount > $user_credit->available_credit){
                    return 'You do not have enough loyalty credits to continue';
                }
                $current_spend = $user_credit->current_spend + $order['discounted_price'];
                $total_spend = $user_credit ->total_spend + $order['discounted_price'];
                $available_credit=$user_credit->available_credit;
                $calculate_spend = self::getCurrentSpend($current_spend,$vendor_loyalty,$available_credit);
                $current_spend = $calculate_spend['current_spend'];
                $available_credit = $calculate_spend['available_credit'] - $loyalty_amount;
                $user_credit_amount = $user_credit->used_credit+$loyalty_amount;
                $data=[
                    'user_id'=>$user_id,
                    'vendor_id'=>$order['vendor_id'],
                    'current_spend'=>$current_spend,
                    'available_credit'=>$available_credit,
                    'total_spend'=>$total_spend,
                    'used_credit'=>$user_credit_amount,
                ];
                UserCredits::_save($data,$user_credit->id);
                $order['discount'] = $loyalty_amount;
                $order['discounted_price'] = $order['price']-$loyalty_amount;
                return $order;
            }
            if ($vendor_loyalty){
                $current_spend = $user_credit->current_spend + $order['discounted_price'];
                $total_spend = $user_credit ->total_spend + $order['discounted_price'];
                $available_credit=$user_credit->available_credit;
                $calculate_spend = self::getCurrentSpend($current_spend,$vendor_loyalty,$available_credit);
                $current_spend = $calculate_spend['current_spend'];
                $available_credit = $calculate_spend['available_credit'];

                $data=[
                    'user_id'=>$user_id,
                    'vendor_id'=>$order['vendor_id'],
                    'current_spend'=>$current_spend,
                    'available_credit'=>$available_credit,
                    'total_spend'=>$total_spend,
                    'used_credit'=>$user_credit->used_credit,
                ];
                UserCredits::_save($data,$user_credit->id);
            }
            $order['discount'] = 0;
            $order['discounted_price'] = $order['price'];
            return $order;
        }else{
            if (isset($order['loyalty_amount']) && $order['loyalty_amount']){
                return 'You do not have enough loyalty credits to continue';
            }
            $vendor_loyalty = Loyalty::where('vendor_id',$order['vendor_id'])->first();
            if ($vendor_loyalty){
                $current_spend = $order['price'];
                $total_spend = $order['price'];
                $available_credit=0;
                $calculate_spend = self::getCurrentSpend($current_spend,$vendor_loyalty,$available_credit);
                $current_spend = $calculate_spend['current_spend'];
                $available_credit = $calculate_spend['available_credit'];

                $data=[
                    'user_id'=>$user_id,
                    'vendor_id'=>$order['vendor_id'],
                    'current_spend'=>$current_spend,
                    'available_credit'=>$available_credit,
                    'total_spend'=>$total_spend,
                    'used_credit'=>0,
                ];
                UserCredits::_save($data);
                $order['discount'] = 0;
                $order['discounted_price'] = $order['price'];
                return $order;
            }else{
                $order['discount'] = 0;
                $order['discounted_price'] = $order['price'];
                return $order;
            }
        }
    }

    /**
     * @param $current_spend
     * @param $vendor
     * @param $available_credit
     * @return array
     */
    private static function getCurrentSpend($current_spend,$vendor,$available_credit){
        if ($current_spend > $vendor->spend){
            $available_credit += $vendor->redemption;
            $current_spend = $current_spend - $vendor->spend;
            return ($current_spend > $vendor->spend) ? self::getCurrentSpend($current_spend,$vendor,$available_credit):
                ['current_spend'=>$current_spend,'available_credit'=>$available_credit];
        }
        return ['current_spend'=>$current_spend,'available_credit'=>$available_credit];
    }

    /**
     * @param $order
     * @param $user_id
     * @return bool|string
     */
    public static function checkUserUseLoyalty($order,$user_id)
    {
        $user_credit = UserCredits::where(['user_id' => $user_id, 'vendor_id' => $order['vendor_id']])->first();
        if ($user_credit) {
            $loyalty_amount = $order['loyalty_amount'] ?? 0;
            $vendor_loyalty = Loyalty::where('vendor_id', $order['vendor_id'])->first();
            if ($loyalty_amount) {
                if (!$vendor_loyalty) {
                    return 'This vendor is not enrolled with the loyalty program';
                }
                if ($loyalty_amount > $user_credit->available_credit) {
                    return 'You do not have enough loyalty credits to continue';
                }
                $order['discount'] = $loyalty_amount;
                $order['discounted_price'] = $order['price'] - $loyalty_amount;
                return $order;
            }else{
                $order['discount'] = 0;
                $order['discounted_price'] = $order['price'];
            }
        } else {
            if (isset($order['loyalty_amount']) && $order['loyalty_amount']) {
                return 'You do not have enough loyalty credits to continue';
            }
            $order['discount'] = 0;
            $order['discounted_price'] = $order['price'];
        }
        return $order;
    }
}
