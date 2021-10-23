<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\Restaurant;
use App\Models\RestaurantUsers;
use Illuminate\Http\Request;

class VendorSettingsController extends ApiControllers
{
    public function __construct(Restaurant $model)
    {
        $this->model=$model;
        $this->name='vendor';
        $this->limit=100;
    }



    public function update_settings(Request $request){
        $result = $this->validateData($request,[
            'status' => ['numeric','between:0,1'],
            'preparation_time' => ['numeric','between:5,99999999'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $vendor = RestaurantUsers::where('token',$request->header('token-login'))->first()->restaurant;
        $data_for_update=[];
        if (isset($request->status)){
            if ($request->status){
                $data_for_update['status']=$request->status;
            }elseif ($request->status == 0){
                $data_for_update['status']=2;
            }
        }
        if (isset($request->preparation_time) && ($request->preparation_time || $request->preparation_time == 0)){
            $data_for_update['preparation_time']=$request->preparation_time;
        }
        $response='';
        if (!empty($data_for_update)){
            $response = $vendor->update($data_for_update);
        }
        if ($response){
            return $this->sendResponse(null,null);
        }
        return $this->sendError('Something is wrong');
    }
}
