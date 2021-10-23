<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cuisine;
use App\Http\Controllers\Api\ApiControllers;
use Illuminate\Http\Request;

class CuisinesController extends ApiControllers
{
    public function __construct(Cuisine $model)
    {
        $this->model=$model;
        $this->name='cuisines';
        $this->limit=100;
    }

    public function get(Request $request, $whereHas = '', $relation = null, $where = [], $select = '*', $whereIn = [])
    {
        $select=[
            'id as cuisines_id',
            'name as cuisines_name',
            'image as cuisines_image',
            'is_top as cuisines_top',
            ];
        $result = parent::get($request,$whereHas, $relation,$where,$select,$whereIn);

        if ($result->getOriginalContent()['success']){
            $result=$this->correctFormResult($result->getOriginalContent()[$this->name]);
            return $this->sendResponse($result,$this->name,'Ok',200);
        }else{
            return $this->sendError('No cuisines',404);
        }
    }

    private function correctFormResult($sources){
        foreach ($sources as $source){
                if ($source->cuisines_top){
                    $source->cuisines_top=true;
                }else{
                    $source->cuisines_top=false;
                }
        }
        return $sources;
    }

}
