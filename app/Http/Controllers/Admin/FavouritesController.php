<?php

namespace App\Http\Controllers\Admin;

use App\Models\FavouriteRestaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavouritesController extends Controller {

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * on DB restaurant id mean vendor_id
     */
    public function add(Request $request){
        $user = $request->user();
        $check_fav = FavouriteRestaurant::where(['user_id'=>$user->id, 'restaurant_id'=>$request->id])->get();
        if(empty($check_fav)) {
            $favourite = new FavouriteRestaurant();
            $favourite->user_id = $request->user_id;
            $favourite->restaurant_id = $request->id;
            if ($favourite->save()) {
                $favourite_id = $favourite->id;
                $fav = $this->get_favourite($favourite_id);
                $success = 1;
            }
        }else{
            $success = 0;
            $fav = null;
        }
        return response()->json([
            'success' => $success,
            'favourite' => $fav
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_favourite_menu(Request $request){
        $user = $request->user();
        $check_fav = FavouriteRestaurant::where(['user_id'=>$user->id, 'id'=>$request->id])->get();
        if(empty($check_fav)) {
            $favourite = new FavouriteRestaurant();
            $favourite->user_id = $request->user_id;
            $favourite->restaurant_id = $request->restaurant_id;
            if ($favourite->save()) {
                $favourite_id = $favourite->id;
                $fav = $this->get_favourite($favourite_id);
                $success = 1;
            }
        }else{
            $success = 0;
            $fav = null;
        }
        return response()->json([
            'success' => $success,
            'favourite' => $fav
        ]);
    }

    public function get_favourite($id){
        $data = [];
        $favourite = FavouriteRestaurant::find($id);
        if(!empty($favourite)) {
            $data['favourite_id'] = $id;
            $restaurant = $favourite->get_vendor;
            $data['vendor_id'] = $restaurant->id;
            $data['name'] = $restaurant->name;
            $data['phone'] = $restaurant->phone;
            $data['email'] = $restaurant->email;
            $data['area'] = $restaurant->area;
            $data['service_area'] = $restaurant->service_area;
            $data['average_rating'] = $restaurant->average_rating;
            $data['status'] = $restaurant->status;
            $data['preparation_time'] = $restaurant->preparation_time;
            $data['delivery_time'] = 30;
            $data['cost_for_two'] = $restaurant->cost_for_two;

            $data['images'] = [
                'banner' => $restaurant->banner_image,
                'display' => $restaurant->display_image
            ];
        }
        return $data;

    }
    public function api_get_favourites(Request $request){
        $user = $request->user();
        $user_id = $user->id;
        //return response()->json(['success'=>1, 'favourites'=>$this->get_favourites($user_id)]);
        return response()->json($this->get_favourites($user_id));
    }

    public function get_favourites($user_id){
        $data = [];
        $favourites = FavouriteRestaurant::where('user_id', $user_id)->get();
        if(!empty($favourites)){
            foreach($favourites as $key=>$favourite) {
                $restaurant = new RestaurantController();
                /*$data[$key]['favourite_id'] = $favourite->id;
                $data[$key]['created_at'] = $favourite->created_at;
                $data[$key]['restaurant'] = $restaurant->get_restaurant_list('', $favourite->restaurant_id);*/
                $data[] = $restaurant->get_restaurant_list('', $favourite->restaurant_id);
            }
        }
        return $data;
    }

    public function api_get_favourite(Request $request){
        $data = [];
        $favourite = FavouriteRestaurant::find($request->favourite_id);
        if(!empty($favourite)){
            $data['favourite_id'] = $request->favourite_id;
            $data['created_at'] = $favourite->created_at;
            $restaurant = new RestaurantController();
            $data['restaurant'] = $restaurant->get_restaurant_list('', $favourite->restaurant_id);;
        }
        return response()->json(['success'=>1, 'favourite'=>$data]);
    }

    /*public function api_remove_favourite(Request $request){
        if(FavouriteRestaurant::where(['id'=> $request->id, 'user_id'=>$request->user_id])->delete()){
            $success = 1;
        }else{
            $success = 0;
        }
        return response()->json(['success'=>$success, 'favourites'=>$this->get_favourites($request->user_id)]);
    }*/
    public function api_remove_favourite(Request $request){
        $user = $request->user();
        if(FavouriteRestaurant::where(['restaurant_id'=> $request->id, 'user_id'=>$user->id])->delete()){
            $success = 1;
        }else{
            $success = 0;
        }
        return response()->json(['success'=>$success, 'favourites'=>$this->get_favourites($user->id)]);
    }
}
