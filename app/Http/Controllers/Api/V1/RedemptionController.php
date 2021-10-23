<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\VendorsRedemptionServices;
use App\Services\AuthService;
use Illuminate\Http\Request;

class RedemptionController extends ApiControllers
{

    public function __construct(VendorsRedemptionServices $model)
    {
        $this->model=$model;
        $this->name='redemption_services';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function add_redemption_services(Request $request)
    {
        $result = $this->validateData($request, [
            'name' => ['required'],
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        $old = VendorsRedemptionServices::where([
            'vendor_id' => AuthService::getRestaurantUser()['vendor_id'],
            'name' => $request->name
        ])->first();
        if ($old){
            return $this->sendError(0);
        }
        if (VendorsRedemptionServices::_save([
            'vendor_id' => AuthService::getRestaurantUser()['vendor_id'],
            'name' => $request->name
        ])) {
            return $this->sendResponse(null, null);
        }
        return $this->sendError(0);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function delete_redemption_services(Request $request)
    {
        $result = $this->validateData($request, [
            'id' => ['required', 'numeric'],
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        $redemption_service = VendorsRedemptionServices::find($request->id);
        if ($redemption_service) {
            $redemption_service->delete();
            return $this->sendResponse(null, null);
        }
        return $this->sendError(0);
    }
}
