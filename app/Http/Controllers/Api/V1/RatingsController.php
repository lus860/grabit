<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\RatingsVendors;
use App\Models\Restaurant;
use App\Services\AuthService;
use App\Traits\CalculateRating;
use Illuminate\Http\Request;

class RatingsController extends ApiControllers
{
    use CalculateRating;
    /**
     * RatingsController constructor.
     * @param RatingsVendors $model
     */
    public function __construct(RatingsVendors $model)
    {
        $this->model=$model;
        $this->name='rating_vendor';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function create(Request $request){
        $result = $this->validateData($request,[
            'transaction_id' => ['required','numeric'],
            'vendor_id' => ['required','numeric'],
            'vendor_rating' => ['required','numeric'],
            'vendor_rating_message' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $request->merge(['user_id'=>AuthService::getUser()['id']]);
        if ($this->check_rating($request)){
            return $this->sendError('You cannot rate more than once for the same order');
        }
        $result = RatingsVendors::_save($request);
        if(!empty($result) && $result['success']){
            $ratings = RatingsVendors::where('vendor_id',$request->vendor_id)->select('vendor_rating')->get();
            if ($ratings->count()){
                Restaurant::find($request->vendor_id)->update(['average_rating'=>$this->calulcate_rating($ratings)]);
            }
            return $this->sendResponse($result['data'],$this->name);
        }
        return $this->sendError(config('errors')['other_error']);
    }

}
