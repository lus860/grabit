<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Favorite;
use App\Http\Controllers\Api\ApiControllers;
use App\Models\Restaurant;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends ApiControllers
{
    /**
     * FavoriteController constructor.
     * @param Favorite $model
     */
    public function __construct(Favorite $model)
    {
        $this->model=$model;
        $this->name='favourite';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function get_favourites(Request $request){
        $select = [
            'id',
            'restaurant_id',
        ];

        $results = $this->get($request,'','restaurants',
            ['user_id'=>AuthService::getUser()['id']],
            $select,[]);
        if ($results->getOriginalContent()['success']){
            return $this->sendResponse($this->filterData($results->getOriginalContent()['favourite']),'favourites');
        }
        return $this->sendError('You don’t have any favourites in your profile');
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function add_new_favourite(Request $request){
        $result = $this->validateData($request,[
            'vendor_id' => ['required','integer','min:1']
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $old = Favorite::where(['user_id'=>AuthService::getUser()['id'],'restaurant_id'=>$request->vendor_id])->first();
        if ($old){
            return $this->sendError('This vendor already exists in your favourite list');
        }
        $request->merge(['user_id'=>AuthService::getUser()['id']]);
        $select=['id as favourite_id','user_id','restaurant_id as vendor_id','created_at','updated_at'];
        return $this->_save($request,null,true,$select);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function delete_existing_favourite(Request $request){
        $result = $this->validateData($request,[
            'favourite_id' => ['required','integer','min:1']
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        return $this->_delete(['id'=>$request->favourite_id]);
    }

    private function filterData($data){
        $answer = [];
        foreach ($data as $item){
            $favourite=(object)[];
            $favourite->favourite_id = $item->id;
            $favourite->vendor_name = $item->restaurant->name;

            $favourite->vendor_id = $item->restaurant->id;
            $favourite->average_rating = $item->restaurant->average_rating;
            $images=(object)[];
            $images->banner = $item->restaurant->banner_image;
            $images->display = $item->restaurant->display_image;
            $favourite->images = $images;
            // $favourite->area = $item->area->namer;
            // $favourite->city = $item->city->name;
            $favourite->preparation_time = $item->restaurant->preparation_time;
            $cuisines = [];
            $restaurantCuisines = $item->restaurant->restaurantCuisine()->get();


            foreach ($restaurantCuisines as $restaurantCuisine){
                $itemCuisine=(object)[];
                $itemCuisine->id=$restaurantCuisine->cuisine->id;
                $itemCuisine->name=$restaurantCuisine->cuisine->name;
                $cuisines[]= $itemCuisine;
            }
            $favourite->cuisines = $cuisines;
            $answer[]=$favourite;
        }
        return $answer;

    }
}
