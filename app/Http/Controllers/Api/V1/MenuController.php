<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemOptionValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends ApiControllers
{
    public function __construct(Menu $model)
    {
        $this->model=$model;
    }

    /**
     * @param Request $request
     * @param $type
     * @return bool|mixed
     */
    public function change_status(Request $request,$type){
        if ($type == 'menu_item'){
            return $this->menu_item_status($request);
        }
        if ($type == 'option_value'){

            return $this->option_value_status($request);
        }
        return $this->sendError('Not Found',404);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    private function menu_item_status(Request $request){
        $result = $this->validateData($request,
            [
                'item_id' => ['required'],
                'status' => ['required','integer','between:0,1']
            ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        try {
            $items = json_decode($request->item_id,true);
            foreach ($items as $item){
                $result = MenuItem::_save($request,$item);
            }
            if ($result['success']){
                return $this->sendResponse(true,'changed');
            }
            return $this->sendError(config('errors')['error_menu_item_id']);

        }catch (\Exception $e){
            return $this->sendError($e->getMessage());
        }

        return $this->sendError(config('errors')['other_error']);

    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    private function option_value_status(Request $request){
        $result = $this->validateData($request,
            [
                'option_value_id' => ['required'],
                'status' => ['required','integer','between:0,1']
            ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        try {
            $option_values = json_decode($request->option_value_id,true);
            foreach ($option_values as $item){
                $result = MenuItemOptionValue::_save($request,$item);
            }
            if ($result['success']){
                return $this->sendResponse(true,'changed');
            }
            return $this->sendError(config('errors')['error_option_value_id']);

        }catch (\Exception $e){
            return $this->sendError($e->getMessage());
        }
        return $this->sendError(config('errors')['error_option_value_id']);

    }
}
