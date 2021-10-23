<?php


namespace App\Traits;


use App\Models\RatingsVendors;
use App\Models\Restaurant;
use App\Services\AuthService;

trait CalculateRating
{
    /**
     * @param $ratings
     * @return int
     */
    public function calulcate_rating($ratings){
        $sum=0;
        if ($ratings->count()){
            foreach ($ratings as $rating){
                $sum+=$rating->vendor_rating;
            }
            $finished_rating = number_format((float) ($sum/$ratings->count()), 1);
        }
        return $sum;
    }

    /**
     *
     */
    private function check_rating($request){
        $rating = RatingsVendors::where(['transaction_id'=>$request->transaction_id])->first();
        if ($rating) return true;
        return false;
    }
}
