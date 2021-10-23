<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\City;
use App\Http\Controllers\Api\ApiControllers;
use Illuminate\Http\Request;

class CitiesController extends ApiControllers
{
    public function __construct(City $model)
    {
        $this->model=$model;
        $this->name='cities';
        $this->limit=100;
    }

    public function getAreas(Request $request, $whereHas = '', $relation = null, $where = [], $select = '*', $whereIn = [])
    {
        $select=[
            'id',
            'id as city_id',
            'name as city_name'
        ];
        $relation=['area'=>function($query){
           $query->select('id as area_id','city_id','name as area_name') ;
        }];
        $result = $this->get($request,$whereHas, $relation,$where,$select,$whereIn);
        if ($result->getOriginalContent()['success']){
            $result=$this->correctFormResult($result->getOriginalContent()[$this->name]);
            return $this->sendResponse($result,$this->name,'Ok',200);
        }else{
            return $this->sendError('No Cities',404);
        }
    }

    private function correctFormResult($sources){
        foreach ($sources as $source) {
            $source->setHidden(['id']);
            if (!empty($source->area)) {
                foreach ($source->area as $area) {
                    $area->setHidden(['city_id']);
                }
            }
        }
        return $sources;
    }

}
