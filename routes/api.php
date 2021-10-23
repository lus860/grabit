<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Banner;
use App\Models\Category;

Route::get('/test-auth', 'Admin\UsersController@test_auth');
Route::post('/get-token', 'Admin\UsersController@get_token');
Route::get('/get-rating-content', 'Admin\RatingController@getRating');
Route::get('/get_vendor_types', 'Admin\RatingController@vendorTypes');
Route::post('/finalize-order', 'Admin\UsersController@finalize_order');
Route::post('/order-update', 'Admin\UsersController@finalize_order');

//Route::middleware('client_credentials')->group(function () {
//    Route::post('/register-user', 'UsersController@add_user_api');
//    Route::post('/get-code', 'UsersController@send_verification_code');
//    Route::post('/verify-code', 'UsersController@verify_code');
//    Route::post('/verify-phone', 'UsersController@verify_phone');
//    Route::post('/sign-in', 'UsersController@sign_in');
//
//    Route::post('/send-code', 'UsersController@send_reset_code');
//    Route::post('/validate-code', 'UsersController@validate_reset_code');
//    Route::post('/change-password', 'UsersController@change_password');
//
//    Route::get('/banners', function(){
//        return Banner::all();
//    });
//    Route::get('/categories', function(){
//        return Category::where('parent_id', 0)->get();
//    });
//    Route::get('/categories/{id}', function($id){
//        return Category::where('parent_id', $id)->get();
//    });
//    Route::get('/cuisines', 'ProductsController@cuisines');
//    Route::get('/cuisine', 'ProductsController@cuisine');
//    Route::get('/search', 'ProductsController@search');
//    Route::get('/popular-items', 'ProductsController@popular_items');
//    Route::get('/products', 'ProductsController@index');
//    Route::get('/products/{category}', 'ProductsController@category');
//    Route::get('get-restaurants', 'RestaurantController@api_get_restaurant_list');
//    Route::get('get-restaurant-menu', 'RestaurantController@api_get_restaurant_menu');
//
//    Route::post('/add-favourite', 'FavouritesController@add');
//    //Route::get('/get-favourites', 'FavouritesController@api_get_favourites');
//    Route::get('/get-favourite', 'FavouritesController@api_get_favourite');
//
//    Route::get('/userdata/{id}', 'UsersController@get_user_data');
//    //Route::get('/refreshToken', 'UsersController@refreshToken');
//    //Route::get('/user',"UsersController@get_all_user_data");
//
//    Route::get('/get-country',"AreaController@get_country");
//    Route::get('/get-city',"AreaController@get_city");
//    Route::get('/get-area',"AreaController@get_area");
//
//    Route::post('/prepare-order', 'UsersController@start_task');
//    Route::post('/dispatch-order', 'UsersController@complete_task');
//});
//
//Route::middleware('auth:api')->group(function () {
//
//    /*Route::get('/userdata/{id}', 'UsersController@get_user_data');*/
//
//    Route::post('/add-address', 'UsersController@add_address');
//    Route::post('/delete-address', 'UsersController@delete_address');
//    Route::post('/change-address', 'UsersController@set_default_address');
//    Route::post('/place-order', 'UsersController@place_order');
//    Route::get('/orders', 'UsersController@list_orders');
//    Route::get('/order/{id}', 'UsersController@get_order');
//    Route::get('/restaurant-orders', 'UsersController@list_restaurant_orders');
//    Route::post('/remove-favourite', 'FavouritesController@api_remove_favourite');
//    Route::post('/save-firebase-id', 'UsersController@save_firebase_id');
//    Route::get('/refreshToken', 'UsersController@refreshToken');
//    Route::get('/user',"UsersController@get_all_user_data");
//    Route::get('/get-favourites', 'FavouritesController@api_get_favourites');
//});

/*============== New Api =================*/
Route::post('/check', 'Api\V1\ClientsController@checkClient');
Route::post('/status/{type}', 'Api\V1\MenuController@change_status');
Route::get('/user/update-mail', 'Api\V1\UserController@check_update_mail');
Route::post('/notify-from-dash-delivery', 'Api\V1\OrdersController@notify_from_dash_delivery');
Route::post('/courier/notify-from-dash-delivery', 'Api\V1\OrdersController@courier_notify_from_dash_delivery');
Route::get('/get_courier_settings', 'Api\V1\CourierSettingsController@get_courier_settings');
Route::get('/list_of_businesses ', 'Api\V1\LoyaltyController@list_of_businesses');
Route::post('/calculate_courier_price', 'Api\V1\CourierSettingsController@calculate_courier_price');


Route::post('/vendor/login', 'Api\V1\RestaurantsController@login');
Route::post('/vendor/forgot_password', 'Api\V1\RestaurantsController@forgot_password');
//rider
Route::prefix('/rider')->group(function () {
    Route::post('/login', 'Api\V1\RidersController@login');

    Route::middleware('rider_user')->group(function () {
        Route::get('/get_all_orders', 'Api\V1\RidersController@get_all_orders');
        Route::post('/order/status_transit', 'Api\V1\RidersController@change_status_transit');
        Route::post('/order/status_delivery', 'Api\V1\RidersController@change_status_delivery');
        Route::post('/set_firebase_token', 'Api\V1\RidersController@set_firebase_token');
    });
});

Route::prefix('/vendor')->group(function () {
    Route::post('/get_all_vendors', 'Api\V1\RestaurantsController@getRestaurants');
    Route::post('/get_vendor_details', 'Api\V1\RestaurantsController@show');
    Route::post('/set_firebase_token', 'Api\V1\RestaurantsController@set_firebase_token');

    //generator qr code
});
Route::post('/message_read', 'Api\V1\RestaurantsController@message_read');

Route::middleware('restaurant_user')->prefix('/vendor')->group(function () {
    Route::post('/set_new_password', 'Api\V1\RestaurantsController@set_new_password');
    Route::post('/get_orders', 'Api\V1\RestaurantsController@get_orders');
    Route::post('/post_orders', 'Api\V1\RestaurantsController@post_orders');
    Route::post('/dispatch_order', 'Api\V1\RestaurantsController@dispatch_order');
    Route::post('/update-vendor-settings', 'Api\V1\VendorSettingsController@update_settings');
    Route::post('/add-user-loyalty', 'Api\V1\LoyaltyController@add_user_loyalty');
    Route::post('/get-user-name', 'Api\V1\UserController@get_user_name');

    Route::get('/get_qr_code', 'Api\V1\RestaurantsController@get_qr_code');

    Route::post('/check-credits', 'Api\V1\RestaurantsController@check_credits_for_vendor');

    //message
    Route::post('/message_to_user', 'Api\V1\RestaurantsController@message_to_user');

    //all_messages_for_vendor
    Route::get('/all_messages', 'Api\V1\RestaurantsController@all_messages_for_vendor');

    //add_courier_task
    Route::post('/add_courier_task', 'Api\V1\RestaurantsController@add_courier_task');
    //Vendors add redemption services

    Route::post('/add_redemption_services', 'Api\V1\RedemptionController@add_redemption_services');
    Route::post('/delete_redemption_services', 'Api\V1\RedemptionController@delete_redemption_services');



});

Route::middleware('client')->group(function (){

    Route::post('/user_firebase', 'Api\V1\ClientsController@user_firebase');

    Route::prefix('/user')->group(function () {
        Route::post('/check', 'Api\V1\ClientsController@checkUser');
        Route::post('/register', 'Api\V1\Auth\RegisterController@register');
        Route::post('/login', 'Api\V1\Auth\LoginController@login');
        Route::middleware('auth_api')->group(function (){
            //update user info
            Route::post('/update', 'Api\V1\UserController@updateUser');
            Route::post('/update/phone', 'Api\V1\UserController@check_update_phone');
            Route::get('/get_updated_profile', 'Api\V1\UserController@get_updated_profile');
            Route::post('/add_new_address', 'Api\V1\UserController@add_new_address');
            Route::post('/get_all_addresses', 'Api\V1\UserController@get_all_addresses');
            Route::post('/edit_existing_address', 'Api\V1\UserController@edit_existing_address');
            Route::delete('/delete_existing_address', 'Api\V1\UserController@delete_existing_address');
            Route::post('/add_new_favourite', 'Api\V1\FavoriteController@add_new_favourite');
            Route::delete('/delete_existing_favourite', 'Api\V1\FavoriteController@delete_existing_favourite');
            Route::post('/get_favourites', 'Api\V1\FavoriteController@get_favourites');

            //orders
            Route::post('/pending_orders', 'Api\V1\OrdersController@pending_orders');
            Route::post('/verify_orders', 'Api\V1\OrdersController@ordersType');
            Route::post('/place_orders', 'Api\V1\OrdersController@ordersType');
            Route::get('/get_vendor_orders', 'Api\V1\OrdersController@get_user_orders');

            //courier orders
            Route::post('/add_courier_task', 'Api\V1\CourierSettingsController@add_courier_task');
            Route::get('/courier_tasks_history', 'Api\V1\CourierSettingsController@courier_tasks_history');
            Route::post('/courier/rating/add', 'Api\V1\CourierRatingsController@create_rating');

            //setting
            Route::post('/get_user_appsettings', 'Api\V1\SettingsController@get_user_app_settings');

            //calculare rating
            Route::post('/vendor_rating', 'Api\V1\RatingsController@create');
            Route::post('/user_notification_history', 'Api\V1\RatingsController@create');

            //notification
            Route::get('/user_notification_history', 'Api\V1\NotificationController@get_history');

            //loyalty
            Route::get('/businesses_loyalty', 'Api\V1\LoyaltyController@get_user_loyalty');
            Route::post('/use-available-credit', 'Api\V1\UserController@check_available_credit');

            //generator qr code
            Route::get('/get_qr_code', 'Api\V1\UserController@get_qr_code');

            //message
            Route::post('/message_to_vendor', 'Api\V1\UserController@message_to_vendor');

            //all_message_for_user
            Route::get('/all_messages', 'Api\V1\UserController@all_messages_for_user');

            Route::get('/chats_user_vendor', 'Api\V1\UserController@chats_user_vendor');


        });
    });

});

Route::prefix('/cuisines')->group(function () {
    Route::get('/get_cuisines', 'Api\V1\CuisinesController@get');
//    Route::get('/get_cuisines', 'Api\V1\CuisinesController@get');
});

Route::prefix('/cities')->group(function () {
    Route::get('/get_cities_areas', 'Api\V1\CitiesController@getAreas');
});
Route::get('/get_delivery_information', 'Api\V1\SettingsController@get_delivery_information');

Route::fallback(function($e){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
});

