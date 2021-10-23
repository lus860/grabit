<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin/login', function (){
    return redirect('/login');
});

Route::middleware('admin:admin')
    ->prefix('backend')
    ->group(function () {
        Route::post('/change-order-status','Admin\OrdersController@change_status');
        Route::post('/change-order-status-courier','Admin\CourierController@change_status');

        //ratings
        Route::get('/orders/ratings', 'Admin\RatingsController@index');
        Route::get('/orders/ratings/{id}', 'Admin\RatingsController@show');
        Route::delete('/ratings/delete', 'Admin\RatingsController@delete')->name('ratings-delete');
        Route::get('/courier-orders/ratings', 'Admin\RatingsController@courier_ratings');
        Route::get('/courier-orders/ratings/{id}', 'Admin\RatingsController@show_courier_ratings');
//        Route::get('brands', 'BackendController@brand');
//        Route::any('brands/edit', 'BackendController@brandsEdit');
//        Route::get('category', 'BackendController@category');
//        Route::any('category/edit', 'BackendController@CategoryEdit');
//        Route::get('products', 'BackendController@products');
//        Route::any('products/edit', 'BackendController@productsEdit');
//        Route::get('pending-orders', 'BackendController@orders');
//        Route::any('orders/edit', 'BackendController@ordersEdit');
        Route::get('/send-alert', 'Admin\AlertMessagesController@create');
        Route::get('/send-alert-history', 'Admin\AlertMessagesController@index');
        Route::post('/send-alert-messages', 'Admin\AlertMessagesController@store');
        Route::post('/get-data-for-alert-message', 'Admin\AlertMessagesController@get_data_for_alert_message');

        Route::get('/admin', 'Admin\BackendController@dashboard');
        Route::get('roles', 'Admin\UsersController@role');
        Route::get('profile', 'Admin\UsersController@profile');
        Route::get('admin/add-phone-numbers', 'Admin\AdminController@add_phones_form');
        Route::post('admin/add-phone-numbers', 'Admin\AdminController@add_phones');
        Route::get('admin/add-emails', 'Admin\AdminController@add_emails_form');
        Route::post('admin/add-emails', 'Admin\AdminController@add_emails');
        Route::post('roles', 'Admin\UsersController@createRole');
//        Route::get('subcategory', 'BackendController@category');
//        Route::any('subcategory/edit', 'BackendController@CategoryEdit');
//        Route::get('articles/search', 'ArticlesController@search');
        Route::get('/manage-categories', 'Admin\AdvertsController@manage_categories');
        Route::get('/manage-listings', 'Admin\AdvertsController@manage_listings');
        Route::get('/add-category', 'Admin\AdvertsController@add_category');
        Route::post('/create-category', 'Admin\AdvertsController@create_category');
        Route::post('/create-subcategory', 'Admin\AdvertsController@create_subcategory');
        Route::get('/add-sub-category', 'Admin\AdvertsController@add_sub_category');
        Route::delete('/delete', 'Admin\AdvertsController@delete')->name('category-delete');
        Route::delete('/delete/subcategory', 'Admin\AdvertsController@delete_subcategorey')->name('subcategory-delete');
        Route::get('/category/{id}', 'Admin\AdvertsController@show');
        Route::get('/subcategory/{id}', 'Admin\AdvertsController@show_subcategory');
        Route::post('/edit-category/{id}', 'Admin\AdvertsController@edit_category');
        Route::post('/edit-subcategory/{id}', 'Admin\AdvertsController@edit_subcategory');
        Route::get('/sub-category', 'Admin\AdvertsController@index_subcategory');

        Route::get('/order-status/{id}', 'Admin\BackendController@order_status');
        Route::post('/order-status/', 'Admin\BackendController@order_status_update');
        Route::post('/menu-item/add/{menu_id}', 'Admin\MenuItemController@add');
        Route::post('/menu-item/remove/{item_id}', 'Admin\MenuItemController@remove');

        Route::post('/get-customization-values', 'Admin\RestaurantAjaxController@get_customization_options_ajax');

        Route::get('/orders/cancelled', 'Admin\OrdersController@cancelled');
        Route::get('/orders/delivered', 'Admin\OrdersController@delivered');
        Route::resource('orders', 'Admin\OrdersController');

        Route::get('/courier-orders/cancelled', 'Admin\CourierController@cancelled');
        Route::get('/courier-orders/delivered', 'Admin\CourierController@delivered');
        Route::resource('courier-orders', 'Admin\CourierController');
        Route::resource('carrier', 'Admin\CarrierController');
        Route::resource('parcel-type', 'Admin\ParcelTypesController');

        Route::get('vendors/menu/{restaurant}', 'Admin\RestaurantController@menu');

        Route::resource('articles', 'Admin\ArticlesController');
        Route::resource('users', 'Admin\UsersController');
        Route::resource('vendors', 'Admin\RestaurantController');
        Route::resource('menu', 'Admin\MenuController')->except('show');
//        Route::resource('groups', 'CustomizationGroupsController');
        Route::resource('cities', 'Admin\CityController');
        Route::resource('areas', 'Admin\AreaController');
        Route::resource('cuisines', 'Admin\CuisineController');
        Route::get('app-settings', 'Admin\SettingController@app_settings');
        Route::get('app-settings/edit', 'Admin\SettingController@app_settings_edit_form');
        Route::post('app-settings/edit', 'Admin\SettingController@app_settings_store');
        Route::get('app-settings/show-black-list', 'Admin\SettingController@show_block_list');
        Route::post('app-settings/blok-list/remove', 'Admin\SettingController@remove_block_list');

        Route::get('monthly-price', 'Admin\SettingController@monthly_price');
        Route::get('monthly-price/edit', 'Admin\SettingController@monthly_price_edit_form');
        Route::post('monthly-price/edit', 'Admin\SettingController@monthly_price_store');

        Route::get('yearly-price', 'Admin\SettingController@yearly_price');
        Route::get('yearly-price/edit', 'Admin\SettingController@yearly_price_edit_form');
        Route::post('yearly-price/edit', 'Admin\SettingController@yearly_price_store');
//        Route::get('courier-settings', 'SettingController@courierSettings');
//        Route::post('courier-settings/edit', 'SettingController@courierSettingsEdit');
//        Route::get('courier-settings/edit', 'SettingController@courierSettingsEdit');
        Route::get('deliveries', 'Admin\SettingController@delivery');
        Route::get('deliveries/edit', 'Admin\SettingController@deliveryEdit');
        Route::post('deliveries/edit', 'Admin\SettingController@deliveryEdit');

        Route::get('/get-cities', 'Admin\AreaController@get_country');
        Route::get('/get-areas', 'Admin\AreaController@get_city');
        Route::get('/check-existing-user', 'Admin\AjaxController@check_existing_user');

        Route::get('/check-existing-restaurant-user', 'Admin\AjaxController@check_existing_restaurant_user');

        Route::get('/finalize-order', 'Admin\UsersController@finalize_order');

        //vendor-type
        Route::get('/vendor-type', 'Admin\VendorController@index');
        Route::get('/vendor-type/edit/{id}', 'Admin\VendorController@edit');
        Route::post('/vendor-type/edit/{id}', 'Admin\VendorController@update')->name('vendor-update');
        Route::get('/vendor-type/create', 'Admin\VendorController@create');
        Route::post('/vendor-type', 'Admin\VendorController@store');
        Route::delete('/vendor-type/delete', 'Admin\VendorController@delete')->name('vendor-delete');

        //Notification messages
        Route::get('/notification/user/{name}', 'Admin\NotificationMessagesController@user_form');
        Route::post('/notification/update/{type}/{name}', 'Admin\NotificationMessagesController@create_or_update');
        Route::get('/notification/vendor/{name}', 'Admin\NotificationMessagesController@vendor_form');

        //notifications-for-admin
        Route::post('/order/admin-center-notification-change-status','Admin\AjaxController@admin_center_notification_change_status_order');
        Route::post('/vendor/admin-center-notification-change-status','Admin\AjaxController@admin_center_notification_change_status_vendor');
        Route::get('/all-notifications-for-admin','Admin\BackendController@all_notifications_for_admin');

        //loyalty
        Route::get('/loyalty', 'Admin\LoyaltyController@index');
        Route::get('/loyalty/create', 'Admin\LoyaltyController@create');
        Route::post('/loyalty', 'Admin\LoyaltyController@store');
        Route::get('/loyalty/edit/{loyalty}', 'Admin\LoyaltyController@edit');
        Route::post('/loyalty/edit/{loyalty}', 'Admin\LoyaltyController@update')->name('loyalty-update');

        Route::get('/loyalty/branches/{loyalty}', 'Admin\LoyaltyController@add_branches');
        Route::post('/loyalty/branches/{loyalty}', 'Admin\LoyaltyController@create_branches');
        Route::delete('/loyalty/branch-destroy/{loyalty}', 'Admin\LoyaltyController@branch_destroy')->name('branch-destroy');

        Route::delete('/loyalty/destroy/{loyalty}', 'Admin\LoyaltyController@destroy')->name('loyalty-destroy');

        //web images
        Route::get('/web-images', 'Admin\WebImagesController@index');
        Route::get('/web-images/create', 'Admin\WebImagesController@create');
        Route::post('/web-images/create', 'Admin\WebImagesController@store');
        Route::get('/web-images/edit/{image}', 'Admin\WebImagesController@edit');
        Route::post('/web-images/edit/{image}', 'Admin\WebImagesController@update')->name('web-image-update');

        //manage riders and orders module
        Route::prefix('/manage')->group(function () {
            //orders
            Route::get('/orders', 'Admin\ManageOrdersController@index');
            Route::get('/orders/{tran_id}', 'Admin\ManageOrdersController@show');
            Route::post('/order/change-order-status','Admin\AjaxController@manage_order_change_status');

            //riders
            Route::get('/riders', 'Admin\RidersController@index');
            Route::get('/riders/add', 'Admin\RidersController@create');
            Route::get('/riders/edit/{rider}', 'Admin\RidersController@edit');
            Route::post('/riders/add', 'Admin\RidersController@store');
            Route::post('/riders/edit/{rider}', 'Admin\RidersController@update')->name('rider-update');
            Route::post('/order/change-order-rider','Admin\AjaxController@manage_order_change_rider');


            Route::delete('/riders/destroy/{rider}', 'Admin\RidersController@destroy')->name('rider-destroy');
            Route::post('/riders/check-user-name', 'Admin\RidersController@check_user_name');

        });

        //reports
        Route::prefix('/reports')->group(function () {
            //couriers
            Route::get('/couriers', 'Admin\ReportController@index');
            Route::get('/couriers/filter', 'Admin\ReportController@filter');
            Route::get('/report-for-couriers-export-csv/{filters}','Admin\ReportController@export_to_csv');
            Route::get('/credit','Admin\ReportController@credits');
            Route::get('/credit/filter','Admin\ReportController@filter_credit');
            Route::get('/credit/get-vendor-branches','Admin\ReportController@filter_credit_get_branches');

        });
    });

Route::post('/image-cropper/upload','ImageController@upload');


/*   Front end side     */
Route::get('/vue-api/get-all-cities','Vue\CitiesController@get_all_cities');
Route::get('/vue-api/get-areas-for-city/{id}','Vue\AreasController@get_areas_for_cities');
Route::get('/vue-api/get-vendors-for-area/{id}','Vue\VendorsController@get_vendors_for_areas');
Route::get('/vue-api/get-vendors-types','Vue\VendorsController@get_vendors_types');
Route::get('/vue-api/get-images-for-homepage','Vue\HomeController@get_images_for_homepage');
Route::get('/vue-api/check-user/{login}','Vue\UserController@checkUser');
Route::get('/vue-api/check-opt/{user_id}/{otp}/{name?}/{password?}','Vue\UserController@checkOpt');
Route::get('/vue-api/check-opt/{user_id}/{otp}','Vue\UserController@checkOpt');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/signin', function () {
    return view('signin');
});
