<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\Setting;
use App\Services\AuthService;
use Illuminate\Http\Request;

class SettingsController extends ApiControllers
{
    public function __construct(Setting $model)
    {
        $this->model=$model;
        $this->name='settings';
        $this->limit=100;
    }

    /**
     * @return mixed
     */
    public function get_delivery_information(){
        $this->name='delivery_details';
        $deliveries= Setting::where('title','delivery')->get();
        $result=[];
        if ($deliveries->count()){
            foreach ($deliveries as $delivery){
                if ($delivery->keyword=='under_price'){
                    $result['under_15km']=$delivery->description;
                }
                if ($delivery->keyword=='above_price'){
                    $result['above_15km']=$delivery->description;
                }
                if ($delivery->keyword=='delivery_time'){
                    $result['delivery_time_minutes']=$delivery->description;
                }
            }
            return $this->sendResponse($result,$this->name,'OK',200);
        }
        return $this->sendError('Not Found Result',404);
    }

    public function get_user_app_settings(){
        $app_settings= Setting::where('title','app_setting')->get();
        $user_id = AuthService::getUser()['id'];
        $result=[];
        if ($app_settings->count()){
            foreach ($app_settings as $value){
                if ($value->keyword=='min_ios'){
                    $result['min_ios_app_version']=$value->description;
                }
                if ($value->keyword=='min_android'){
                    $result['min_android_app_version']=$value->description;
                }
                if ($value->keyword=='maintenance_mode'){
                    $result['maintenance_mode']=$value->description?true:false;
                }
                if ($value->keyword=='block_list'){
                    $flag=true;
                    foreach (unserialize($value->description) as $blocked_user_id){
                        if ($blocked_user_id['id'] == $user_id){
                            $result['blocked_status']=true;
                            $flag=false;
                        }
                    }
                    if ($flag){
                        $result['blocked_status']=false;
                    }
                }
            }
            return $this->sendResponse($result,'app_settings','OK',200);
        }
        return $this->sendError('Not Found Result',404);
    }
}
