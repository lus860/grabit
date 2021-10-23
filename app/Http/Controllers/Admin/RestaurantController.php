<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Models\City;
use App\Models\Cuisine;
use App\Models\CustomizationGroup;
use App\Models\CustomizationValue;
use App\Models\Day;
use App\Http\Controllers\Controller;
use App\Mail\RestaurantUserCreatedNotification;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\VendorTypes;
use App\Models\Offering;
use App\Models\Country;
use App\Models\PaymentFrequency;
use App\Models\RestaurantBreakTime;
use App\Models\RestaurantCuisine;
use App\Models\RestaurantEmail;
use App\Models\RestaurantMenu;
use App\Models\RestaurantOffering;
use App\Models\RestaurantOpeningTime;
use App\Models\RestaurantServiceArea;
use App\Models\RestaurantUsers;
use App\Services\EmailService;
use App\Models\SSJUtils;
use App\Traits\SetToken;
use App\User;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use File;

class RestaurantController extends Controller
{
    use SetToken;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $lib;
    public $opening_times;
    public $opening_days;

    /**
     * RestaurantController constructor.
     */
    public function __construct()
    {
        $this->lib = new SSJUtils();

        $this->opening_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $this->opening_times = [
            '06:00', '06:30', '07:00', '07:30', '08:00',
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
            '18:00', '18:00', '19:00', '19:30', '20:00', '20:30',
            '21:00', '21:30', '22:00', '23:00', '24:00', '24:30'];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $data['title'] = 'Vendors listing';
        $data['opening_days'] = $this->opening_days;
        $data['restaurants'] = Restaurant::orderBy('updated_at','desc')->paginate(10);
        return view('admin.restaurant.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data['title'] = 'Create new';
//        $data['payment_frequencies'] = PaymentFrequency::all();
        $data['countries'] = Country::all();
        $data['cuisines'] = Cuisine::all();
        $data['cuisine_offering'] = Offering::all();
        $data['banks'] = [
            'CRDB BANK', 'NMB BANK', 'AMANA BANK', 'KCB BANK', 'EXIM BANK', 'DCB BANK', 'DTB BANK', 'TPB BANK'
        ];

        $data['opening_days'] = $this->opening_days;
        $data['opening_times'] = $this->opening_times;
        $data['vendor_type'] = VendorTypes::get();

        return view('admin.restaurant.create', $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function menu(Request $request,$id){
        $items = Menu::query()->select('id', 'name', 'restaurant_id', 'start_time', 'end_time', 'availability','same_as_restaurant')->where('restaurant_id', $id)->with([
            'days' => function($q){
                $q->select('days.id');
            },
            'menu_items' => function($q) {
                $q->select('id', 'menu_id', 'name', 'type', 'price', 'max_quantity', 'description', 'container_price', 'offer_price', 'special_offer', 'popular_item')->with(['options' => function($q){
                    $q->select('id', 'item_id', 'type', 'name', 'item_maximum')->with(['values'=>function($q){
                        $q->select('id', 'option_id', 'value', 'price');
                    }]);
                }]);
            }
        ])->get();
        foreach ($items as $item){
            $item['restaurant_name'] = Restaurant::find($item->restaurant_id)->name;
            if (count($item['days'])) $item['day_id'] = $item['days']->pluck('id')->toArray();
            unset($item['days']);
        }
        $items=$items->toArray();
        $data = [
            'title' => 'menu restaurant',
            'data' =>$items,
            'days' => Day::adminList()->toArray(),
        ];
        return view('admin.restaurant.entire_menu', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'company_name' => ['required'],
            'contact_name' => ['required'],
            'vendor_id' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'banner_image' => ['required','mimes:png'],
            'display_image' => ['required','mimes:png'],
            'banner_saved_image' => ['required'],
            'display_saved_image' => ['required'],
            'login_email' => ['required','email','unique:restaurant_users,email'],
            'password' => ['required'],
            'cuisine.*' => ['required'],
            'cuisine' => ['required'],
            'cuisine_prep_time' => ['required','numeric','between:5,99999999'],
            'cuisine_cost_for_two' => ['required','numeric'],
            'country' => ['required'],
            'city' => ['required'],
            'area' => ['required'],
            'service_area.*' => ['required'],
            'latitude' => ['required','numeric'],
            'longitude' => ['required','numeric'],
            'address1' => ['required'],
            'address2' => ['required'],
            'status' => ['required'],
            'number_for_customers' => ['required'],
            'opening_day.*' => ['required'],
            'opening_time.*' => ['required'],
            'closing_time.*' => ['required'],
            'opening_status.*' => ['required'],
            'delivery_commission' => ['numeric'],
            'collection_commission' => ['numeric'],
            'dine_in_commission' => ['numeric'],
        ],['cuisine_prep_time.between'=>'The delivery time must be minimum 5']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }

        $data = $request->all();
        $cuisines = $request->input('cuisine')??'';
        $cuisine_offering = $request->input('cuisine_offering')??'';
        $service_area = explode( ',', $request->input('service_area')[0]);
        $notification_email = $request->input('notification_email');

//        $file_destination = '/var/www/app.simbadesign.co.tz/html/mamboz/uploads/';
        $file_destination = public_path().'/admin/uploads/';
        //upload certificates
        $registration_certificate = $this->lib->upload('registration_certificate', $file_destination, $request);
        $tin_certificate = $this->lib->upload('tin_certificate', $file_destination, $request);
        $business_license = $this->lib->upload('business_license', $file_destination, $request);
        $director_id = $this->lib->upload('director_id', $file_destination, $request);
        $agreement = $this->lib->upload('agreement', $file_destination, $request);
        $certificate=[
            'registration_certificate'=>$registration_certificate['url'],
            'tin_certificate'=>$tin_certificate['url'],
            'business_license'=>$business_license['url'],
            'director_id'=>$director_id['url'],
            'agreement'=>$agreement['url']
        ];

        //upload images
        $banner_image =isset($request->banner_saved_image)?$request->banner_saved_image:'';
        $display_image =isset($request->display_saved_image)?$request->display_saved_image:'';
        $images=['banner'=>$banner_image,'display'=>$display_image];
        $user = RestaurantUsers::_save($request);

        if($user) {
            $restaurant=Restaurant::_save($request,null,$images,$certificate);
            $qr_code = self::setQrCode();
            $client = new \GuzzleHttp\Client();
            $url='https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='. $qr_code;
            $response = $client->get($url);
            $response = json_decode($response->getBody());
            $restaurant->update(['qr_url'=>$url, 'qr_code'=>$qr_code]);
            if ($restaurant) {
                if (isset($notification_email) && !empty($notification_email)) {
                    RestaurantEmail::_save([
                        'emails'=>$notification_email,
                        'restaurant_id'=>$restaurant->id
                    ]);

                }
                if (isset($service_area) && !empty($service_area)){
                    RestaurantServiceArea::_save([
                        'areas'=>$service_area,
                        'restaurant_id'=>$restaurant->id
                    ]);
                }
                if(!empty($data['opening_day'])){
                    $i=0;
                    foreach($data['opening_day'] as $key=>$val){
                        $opening_time = new RestaurantOpeningTime();
                        $opening_time->restaurant_id = $restaurant->id;
                        $opening_time->day = $data['opening_day'][$key];
                        if ($data['opening_status'][$key] == 1){
                            $opening_time->opening_time = $data['opening_time'][$i];
                            $opening_time->closing_time = $data['closing_time'][$i];
                            $i++;
                        }else{
                            $opening_time->opening_time = null;
                            $opening_time->closing_time = null;
                        }
                        $opening_time->open_status = $data['opening_status'][$key];
                        $opening_time->save();
                    }
                }

                if(isset($data['break_day']) && !empty($data['break_day'])){
                    foreach($data['break_day'] as $key=>$val){
                        if($key != 0) {
                            $opening_time = new RestaurantBreakTime();
                            $opening_time->restaurant_id = $restaurant->id;
                            $opening_time->day = $data['break_day'][$key];
                            $opening_time->time_from = $data['break_start_time'][$key];
                            $opening_time->time_to = $data['break_end_time'][$key];
                            $opening_time->save();
                        }
                    }
                }

                if (isset($cuisine_offering) && !empty($cuisine_offering)) {
                    RestaurantOffering::_save([
                        'data'=>$cuisine_offering,
                        'restaurant_id'=>$restaurant->id
                    ]);

                }
                if (!empty($cuisines)) {
                    RestaurantCuisine::_save([
                        'data'=>$cuisines,
                        'restaurant_id'=>$restaurant->id
                    ]);
                }
                $user->update(['restaurant_id'=>$restaurant->id]);
                EmailService::sendEmailWhenNewRestaurantUserRegistered($restaurant,$user);
                return redirect(url('/backend/vendors'))->with(['flash_message'=>'Success']);
            } else {
                return redirect()->back()->withErrors(['flash_message'=>'Error saving vendor']);
            }
        }else{
            return redirect()->back()->withErrors(['flash_message'=>'Error saving vendor']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data['title'] = 'Vendor detail';
        $restaurant = Restaurant::find($id);
        $data['restaurant'] = $restaurant;
        $data['break_times'] = RestaurantBreakTime::where('restaurant_id',$id)->get();
        $data['opening_days'] = $this->opening_days;
        $data['opening_times'] = $this->opening_times;
        $data['vendor_type'] = VendorTypes::get();

        return view('admin.restaurant.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $data = [];
        $data['title'] = 'Edit Vendor';

        $restaurant = Restaurant::with('restaurantCuisine','restaurantOffering', 'openingTimes')->find($id);
        $data['restaurant'] = $restaurant;
        $data['countries'] = Country::all();
        $data['cities'] = City::where('country_id',$restaurant->country_id)->get();

        $data['countries'] = Country::all();
        $data['opening_days'] = $this->opening_days;
        $data['opening_times'] = $this->opening_times;
        $data['edit_type'] = $request->type;
        $data['service_areas'] =  RestaurantServiceArea::where(['restaurant_id' => $restaurant->id])->get();;
        $data['areas'] = Area::where('city_id',$restaurant->city_id)->get();
        $data['banks'] = [
            'CRDB BANK', 'NMB BANK', 'AMANA BANK', 'KCB BANK', 'EXIM BANK', 'DCB BANK', 'DTB BANK', 'TPB BANK'
        ];

//        $data['payment_frequencies'] = PaymentFrequency::all();
        $data['countries'] = Country::all();
        $data['cuisines'] = Cuisine::all();
        $data['cuisine_offering'] = Offering::all();
        $data['notification_emails'] = RestaurantEmail::where('restaurant_id',$id)->get();
        $data['vendor_type'] = VendorTypes::get();

        return view('admin.restaurant.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Restaurant $vendor)
    {
        $func = 'update_'.$request->type;
        $answer =$this->$func($request,$vendor);
        $code = (gettype($answer) != 'boolean') ? $answer->status()??null :null;
        if (isset($code) && $code==302){
            return $answer;
        }elseif ($answer){
            return redirect()->back()->with(['flash_message'=>'Success']);
        }
        return redirect()->back()->withErrors(['flash_message'=>'Error updating restaurant']);
    }

    private function update_logins(Request $request,$vendor){
        $validate=[
            'login_email'=>['required','unique:restaurant_users,email'],
        ];
        if ($vendor->user && $vendor->user->email){
            if (isset($request->login_email) && $request->login_email){
                if ($vendor->user->email == $request->login_email){
                    $validate['login_email']=['required'];
                }
            }
        }
        $validatedData = Validator::make($request->all(),$validate);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        if (RestaurantUsers::_save($request->all(),$vendor->user->id)){
            return true;
        }
        return false;
    }

    private function update_info(Request $request,$vendor){
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'company_name' => ['required'],
            'contact_name' => ['required'],
            'vendor_id' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $file_destination = public_path().'/admin/uploads/';
        $images=null;
        if ($request->banner_image){
            $validatedData = Validator::make($request->all(),[
                'banner_image' => ['required','mimes:png'],
                'banner_saved_image' => ['required']
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            $images['banner'] = $request->banner_saved_image;
        }
        if ($request->display_image){
            $validatedData = Validator::make($request->all(),[
                'display_image' => ['required','mimes:png'],
                'display_saved_image' => ['required']
            ]);
            if ($validatedData->fails()) {
                return redirect()->back()
                    ->withErrors($validatedData)
                    ->withInput();
            }
            $images['display'] = $request->display_saved_image;
        }
        if (isset($request->notification_email) && !empty($request->notification_email)) {
            RestaurantEmail::where('restaurant_id',$vendor->id)->delete();
            foreach ($request->notification_email as $key => $val) {
                if($val != '') {
                    $notification_eml = new RestaurantEmail();
                    $notification_eml->email = $val;
                    $notification_eml->restaurant_id = $vendor->id;
                    $notification_eml->save();
                }
            }
        }elseif (isset($request->oldEmailCount) && $request->oldEmailCount){
            RestaurantEmail::where('restaurant_id',$vendor->id)->delete();
        }
        if (Restaurant::_save($request->all(),$vendor->id,$images)){
            return true;
        }
        return false;
    }

    private function update_cuisines(Request $request,$vendor){
        $validatedData = Validator::make($request->all(),[
            'cuisine' => ['required'],
            'cuisine_prep_time' => ['required','numeric','between:5,99999999'],
            'cuisine_cost_for_two' => ['required'],
        ],['between'=>'The delivery time must be minimum 5']);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        foreach ($request->all() as $key=>$data){
            if ($key == 'cuisine_offering' && count($data)>0) {
                RestaurantOffering::where('restaurant_id',$vendor->id)->delete();
                foreach ($data as $offering) {
                    RestaurantOffering::insert([
                        'restaurant_id'=>$vendor->id,
                        'offering_id'=>$offering,
                    ]);
                }
            }
            if ($key == 'cuisine' && count($data)>0) {
                RestaurantCuisine::where('restaurant_id',$vendor->id)->delete();
                foreach ($data as $cuisine) {
                    RestaurantCuisine::insert([
                        'restaurant_id'=>$vendor->id,
                        'cuisine_id'=>$cuisine,
                    ]);
                }
            }
        }
        $data=$request->all();
        if (isset($data['cuisine_prep_time']) && $data['cuisine_prep_time']){
            Restaurant::where('id',$vendor->id)->update(['preparation_time'=>$data['cuisine_prep_time']]);
        }
        if (isset($data['cuisine_cost_for_two']) && $data['cuisine_cost_for_two']){
            Restaurant::where('id',$vendor->id)->update(['cost_for_two'=>$data['cuisine_cost_for_two']]);
        }
        if (isset($data['minimum_order']) && $data['minimum_order']){
            Restaurant::where('id',$vendor->id)->update(['minimum_order'=>$data['minimum_order']]);
        }
        return true;
    }

    private function update_location($request,$vendor){
        $validatedData = Validator::make($request->all(),[
            'country' => ['required'],
            'city' => ['required'],
            'area' => ['required'],
            'service_area' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'address1' => ['required'],
            'address2' => ['required'],
            'delivery_commission' => ['required'],
            'collection_commission' => ['required'],
            'dine_in_commission' => ['required'],
            'number_for_customers' => ['required'],
            'status' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        //dd(explode( ',', $request->input('service_area')[0] ));
        $service_area = explode( ',', $request->input('service_area')[0]);
        if (Restaurant::_save($request->all(),$vendor->id)){
            RestaurantServiceArea::where(['restaurant_id'=>$vendor->id])->delete();
            if (RestaurantServiceArea::_save([
                'areas'=>$service_area,
                'restaurant_id'=>$vendor->id
            ])){
                return redirect()->route('vendors.show',$vendor->id)->with(['flash_message'=>'Success']);
            }
            return false;
        }
        return false;
    }

    private function update_times($request,$vendor){
        $validatedData = Validator::make($request->all(),[
            'opening_day' => ['required'],
            'opening_status' => ['required'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
        ]);
        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        }
        $i=0;
        RestaurantOpeningTime::where('restaurant_id',$vendor->id)->delete();
        foreach($request->input('opening_day') as $key=>$val){
            $opening_time = new RestaurantOpeningTime();
            $opening_time->restaurant_id = $vendor->id;
            $opening_time->day = $request->input('opening_day')[$key];
            if ($request->input('opening_status')[$key] == 1){
                $opening_time->opening_time = $request->input('opening_time')[$i];
                $opening_time->closing_time = $request->input('closing_time')[$i];
                $i++;
            }else{
                $opening_time->opening_time = null;
                $opening_time->closing_time = null;
            }
            $opening_time->open_status = $request->input('opening_status')[$key];
            $opening_time->save();
        }
        return true;
    }

    private function update_break_time($request,$vendor){
        $data=$request->all();
        RestaurantBreakTime::where('restaurant_id',$vendor->id)->delete();
        if(isset($data['break_day']) && !empty($data['break_day'])){
            foreach($data['break_day'] as $key=>$val){
                if($key != 0) {
                    $opening_time = new RestaurantBreakTime();
                    $opening_time->restaurant_id = $vendor->id;
                    $opening_time->day = $data['break_day'][$key];
                    $opening_time->time_from = $data['break_start_time'][$key];
                    $opening_time->time_to = $data['break_end_time'][$key];
                    $opening_time->save();
                }
            }
        }
        return true;
    }

    private function update_bank_details($request,$vendor){
        if (Restaurant::_save($request,$vendor->id)){
            return true;
        }
        return false;
    }

    private function update_attachments($request,$vendor){
        $file_destination = public_path().'/uploads/';
        //upload certificates
        if($request->registration_certificate){
            $registration_certificate = $this->lib->upload('registration_certificate', $file_destination, $request);
            $certificate=[
                'registration_certificate'=>$registration_certificate['url'],
            ];
        }

        if($request->tin_certificate){
            $tin_certificate = $this->lib->upload('tin_certificate', $file_destination, $request);
            $certificate=[
                'tin_certificate'=>$tin_certificate['url'],
            ];
        }
        if($request->business_license){
            $business_license = $this->lib->upload('business_license', $file_destination, $request);
            $certificate=[
                'business_license'=>$business_license['url'],
            ];
        }
        if($request->director_id){
            $director_id = $this->lib->upload('director_id', $file_destination, $request);
            $certificate=[
                'director_id'=>$director_id['url'],
            ];
        }
        if($request->agreement){
            $agreement = $this->lib->upload('agreement', $file_destination, $request);
            $certificate=[
                'agreement'=>$agreement['url']
            ];
        }

        if (isset($certificate) && is_array($certificate)){
            if (Restaurant::_save($request,$vendor->id,null,$certificate)){
                return true;
            }
            return false;
        }
        return false;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);
        //$restaurant->delete();
        $menus = RestaurantMenu::where(['restaurant_id' => $id])->get();
        foreach ($menus as $menu) {
            $menu_items = MenuItem::where(['menu_id' => $menu->menu_id])->get();
            $menuItems = '';
            foreach ($menu_items as $item) {
                $menuItems .= $item->id . ',';
            }
            $menuItems = substr($menuItems, 0, -1);
            MenuItem::where(['menu_id' => $id])->delete();
            if ($menuItems != '') {
                DB::delete("delete from `menu_item_options` where `item_id` in ($menuItems)");
                DB::delete("delete from `menu_item_prices` where `item_id` in ($menuItems)");
            }
            $this_menu = Menu::find($menu->id);
            if (!empty($this_menu)) {
                $this_menu->delete();
            }
        }
        $restaurant->delete();
        return redirect(url('/backend/vendors'))->with(['msg' => 'Success']);
    }

    public function api_get_restaurant_list(Request $request)
    {
        if (isset($request->id) && $request->id != '') {
            return $this->get_restaurant_list('', $request->id);
        } else {
            if (isset($request->cuisine) && $request->cuisine != '') {
                return $this->get_restaurant_list($request->area, '', $request->cuisine);
            } elseif (isset($request->keyword) && $request->keyword != '') {
                return $this->get_restaurant_list($request->area, '', '', $request->keyword);
            } else {
                return $this->get_restaurant_list($request->area);
            }

        }
    }

    public function api_get_restaurant_menu(Request $request)
    {
        return $this->get_restaurant_menu($request->id);
    }

    public function get_restaurant_list($area = '', $id = '', $cuisine = '', $keyword = '')
    {
        if (trim($id) != '') {
            $restaurants = Restaurant::where('id', $id)->get();
        } else {
            if ($area == 'all') {
                $restaurants = Restaurant::all();
            } else {
                $ids = [];
                if (trim($cuisine) != '') {
                    $the_list = DB::select("select `id` from `restaurants` where 1
                                                and `id` in (select `restaurant_id` from `restaurant_service_areas` where `area_id`='$area')
                                                and `id` in (select `restaurant_id` from `restaurant_cuisines` where `cuisine_id`='$cuisine')");
                    if (!empty($the_list)) {
                        foreach ($the_list as $list) {
                            $ids[] = $list->id;
                        }
                        //$ids = substr($ids,0,-1);
                    }
                    //$restaurants = Restaurant::where('id','in', "$ids")->get();
                } elseif (trim($keyword) != '') {
                    $the_list = DB::select("select `id` from `restaurants` where 1
                                                and `id` in (select `restaurant_id` from `restaurant_service_areas` where `area_id`='$area')
                                                and `name` like '%$keyword%'");
                    if (!empty($the_list)) {
                        foreach ($the_list as $list) {
                            $ids[] = $list->id;
                        }
                        //$ids = substr($ids,0,-1);
                    }
                    //$restaurants = Restaurant::where('id','in', "$ids")->get();
                } else {
                    $the_list = DB::select("select `id` from `restaurants` where 1
                                                and `id` in (select `restaurant_id` from `restaurant_service_areas` where `area_id`='$area')");
                    if (!empty($the_list)) {
                        foreach ($the_list as $list) {
                            $ids[] = $list->id;
                        }
                        //$ids = substr($ids,0,-1);
                    }
                    //$restaurants = Restaurant::where('area_id', $area)->orWhere('service_area', $area)->get();
                }
                $restaurants = Restaurant::whereIn('id', $ids)->get();

                /*echo "ID: ". $ids;
                */
                /*print_r($restaurants);
                die();*/
            }
        }
        $data = [];
        if (!empty($restaurants)) {
            $key = 0;
            foreach ($restaurants as $restaurant) {
                //$service_area = Area::where('id', $restaurant->service_area)->first();
                $data[$key]['id'] = $restaurant->id;
                $data[$key]['name'] = $restaurant->name;
                $data[$key]['phone'] = $restaurant->phone;
                $data[$key]['email'] = $restaurant->email;
                $data[$key]['area'] = $restaurant->area->name;
                $data[$key]['service_area'] = $restaurant->getServiceAre();
                $data[$key]['service_area_id'] = $restaurant->service_area;
                $data[$key]['average_rating'] = $restaurant->average_rating;
                $data[$key]['status'] = $restaurant->status;
                $data[$key]['preparation_time'] = $restaurant->preparation_time;
                $data[$key]['delivery_time'] = 30;
                $data[$key]['cost_for_two'] = $restaurant->cost_for_two;
                /*$data[$key]['isFavorite'] = count($restaurant->favourite)>0;*/

                $data[$key]['images'] = [
                    'banner' => $restaurant->banner_image,
                    'display' => $restaurant->display_image
                ];
                $opening_times = [];
                if (!empty($restaurant->openingTimes)) {
                    foreach ($restaurant->openingTimes as $key2 => $openingTime) {
                        $opening_times[$key2]['day'] = $this->opening_days[$openingTime->day];
                        $opening_times[$key2]['opening_time'] = substr($openingTime->opening_time, 0, -3);
                        $opening_times[$key2]['closing_time'] = substr($openingTime->closing_time, 0, -3);
                        $opening_times[$key2]['open_status'] = $openingTime->open_status;
                    }

                    $data[$key]['opening_times'] = $opening_times;
                }

                $data[$key]['location'] = [
                    'country' => $restaurant->country->name,
                    'city' => $restaurant->city->name,
                    'address1' => $restaurant->address1,
                    'address2' => $restaurant->address2,
                    'lat' => $restaurant->latitude,
                    'lng' => $restaurant->longitude,
                ];
                $offering = [];
                $offerings = [];

                if (!empty($restaurant->restaurantOffering)) {
                    foreach ($restaurant->restaurantOffering as $key2 => $value2) {
                        $offerings[] = $value2->offering->title;
                        //$offering[$key2]['title'] = $value2->offering->title;

                    }
                }
                if (in_array('Delivery', $offerings)) {
                    $offering['delivery'] = true;
                } else {
                    $offering['delivery'] = false;
                }

                if (in_array('Collection', $offerings)) {
                    $offering['collection'] = true;
                } else {
                    $offering['collection'] = false;
                }

                if (in_array('Dine in', $offerings)) {
                    $offering['dine_in'] = true;
                } else {
                    $offering['dine_in'] = false;
                }

                $data[$key]['offerings'] = $offering;
                $cuisine = [];

                if (!empty($restaurant->restaurantCuisine)) {
                    foreach ($restaurant->restaurantCuisine as $key3 => $value3) {
                        $cuisine[$key3]['id'] = $value3->cuisine->id;
                        $cuisine[$key3]['name'] = $value3->cuisine->name;
                        $cuisine[$key3]['image'] = $value3->cuisine->image;
                        $cuisine[$key3]['icon'] = $value3->cuisine->icon;
                    }
                }
                $data[$key]['cuisines'] = $cuisine;

                $menus = [];
                /*if(!empty($restaurant->menu)){
                    foreach($restaurant->menu as $key4=>$menu){
                        $menus[$key4]['id'] = $menu->id;
                        $menus[$key4]['name'] = $menu->name;
                        $menus[$key4]['imageUri'] = $menu->image?$menu->image:url('/images/logo.png');
                        $menus[$key4]['icon'] = $menu->icon?$menu->icon:url('/images/logo.png');
                        $menus[$key4]['start_time'] = SSJUtils::FormatDate($menu->start_time, 'H:i');
                        $menus[$key4]['end_time'] = SSJUtils::FormatDate($menu->end_time, 'H:i');

                        $menu_items = [];
                        if(!empty($menu->menuItem)){
                            foreach($menu->menuItem as $key5=>$val5){
                                $menu_items[$key5]['id'] = $val5->id;
                                $menu_items[$key5]['name'] = $val5->name;
                                $menu_items[$key5]['description'] = $val5->description;
                                $menu_items[$key5]['item_type_code'] = $val5->item_type;
                                $menu_items[$key5]['item_type'] = $val5->item_type == 1?'Veg':'Non Veg';
                                $menu_items[$key5]['max_quantity'] = $val5->max_quantity;
                                $menu_items[$key5]['price'] = $val5->price;

                                $menu_items[$key5]['rating'] = 0;
                                $menu_items[$key5]['quantity'] = 1;
                                $menu_items[$key5]['container_price'] = $val5->container_price;
                                $menu_items[$key5]['offer_price'] = $val5->offer_price;
                                $menu_items[$key5]['discountPercentage'] = 0;
                                $menu_items[$key5]['imageUri'] = $menu->image?$menu->image:url('/images/logo.png');

                                //TODO: Planned to have multiple prices as price history and offers


                                $menu_items_options = [];

                                $sql = "select distinct(`option_key`) as `option_key` from `menu_item_options` where `item_id`='".$val5->id."'  group by `option_key`";
                                $group_values = DB::select($sql);
                                if(!empty($group_values)){
                                    foreach($group_values as $key6=>$val6){
                                        $group_data = CustomizationGroup::where('id', $val6->option_key)->first();
                                        $menu_items_options[$key6]['id'] = $group_data->id;
                                        $menu_items_options[$key6]['group_name'] = $group_data->name;
                                        $menu_items_options[$key6]['group_type'] = $group_data->ctype == 1?'multiple':'single';
                                        $menu_items_options[$key6]['group_type_code'] = $group_data->ctype;
                                        $menu_items_options[$key6]['select_max'] = $group_data->select_max;
                                        $the_group_values = [];
                                        foreach($group_data->values as $key8=>$val8){
                                            $the_option = MenuItemOption::where(['option_value'=> $val8->id])->first();
                                            $the_group_values[$key8]['item_id'] = $val8->id;
                                            $the_group_values[$key8]['item_name'] = $val8->name;
                                            $the_group_values[$key8]['price'] = !empty($the_option)?$the_option->added_price:0;
                                        }
                                        $menu_items_options[$key6]['group_values'] = $the_group_values;
                                    }
                                }
                                $menu_items[$key5]['customization_groups'] = $menu_items_options;
                            }

                        }

                        $menus[$key4]['items'] = $menu_items;
                    }
                }*/
                $data[$key]['menus'] = $menus;
                $key++;
            }
        }
        return $data;
    }

    public function get_restaurant_menu($restaurant_id)
    {
        $restaurant = Restaurant::find($restaurant_id);
        $menus = [];
        if (!empty($restaurant)) {
            if (!empty($restaurant->menu)) {
                foreach ($restaurant->menu as $key4 => $menu) {
                    $menus[$key4]['id'] = $menu->id;
                    $menus[$key4]['name'] = $menu->menuCategory->name;
                    $menus[$key4]['imageUri'] = $menu->menuCategory->image;
                    $menus[$key4]['icon'] = $menu->menuCategory->icon;
                    $menus[$key4]['start_time'] = SSJUtils::FormatDate($menu->start_time, 'H:i');
                    $menus[$key4]['end_time'] = SSJUtils::FormatDate($menu->end_time, 'H:i');

                    $menu_items = [];
                    if (!empty($menu->menuItem)) {
                        foreach ($menu->menuItem as $key5 => $val5) {
                            $menu_items[$key5]['id'] = $val5->id;
                            $menu_items[$key5]['name'] = $val5->name;
                            $menu_items[$key5]['description'] = $val5->description;
                            $menu_items[$key5]['item_type_code'] = $val5->item_type;
                            $menu_items[$key5]['item_type'] = $val5->item_type == 1 ? 'Veg' : 'Non Veg';
                            $menu_items[$key5]['max_quantity'] = $val5->max_quantity;
                            $menu_items[$key5]['price'] = $val5->price;

                            $menu_items[$key5]['rating'] = 0;
                            $menu_items[$key5]['quantity'] = 1;
                            $menu_items[$key5]['container_price'] = $val5->container_price;
                            $menu_items[$key5]['offer_price'] = $val5->offer_price;
                            $menu_items[$key5]['discountPercentage'] = 0;
                            $menu_items[$key5]['imageUri'] = $menu->image ? $menu->image : url('/admin/images/logo.png');

                            //TODO: Planned to have multiple prices as price history and offers
                            $menu_items_options = [];

                            $sql = "select distinct(`option_key`) as `option_key` from `menu_item_options` where `item_id`='" . $val5->id . "'  group by `option_key`";
                            $group_values = DB::select($sql);
                            if (!empty($group_values)) {
                                foreach ($group_values as $key6 => $val6) {
                                    $group_data = CustomizationGroup::where('id', $val6->option_key)->first();
                                    $menu_items_options[$key6]['id'] = $group_data->id;
                                    $menu_items_options[$key6]['group_name'] = $group_data->name;
                                    $menu_items_options[$key6]['group_type'] = $group_data->ctype == 1 ? 'multiple' : 'single';
                                    $menu_items_options[$key6]['group_type_code'] = $group_data->ctype;
                                    $menu_items_options[$key6]['select_max'] = $group_data->select_max;
                                    $the_group_values = [];
                                    foreach ($group_data->values as $key8 => $val8) {
                                        $the_option = MenuItemOption::where(['option_value' => $val8->id])->first();
                                        $the_group_values[$key8]['item_id'] = $val8->id;
                                        $the_group_values[$key8]['item_name'] = $val8->name;
                                        $the_group_values[$key8]['price'] = !empty($the_option) ? $the_option->added_price : 0;
                                    }
                                    $menu_items_options[$key6]['group_values'] = $the_group_values;
                                }
                            }
                            $menu_items[$key5]['customization_groups'] = $menu_items_options;
                        }

                    }

                    $menus[$key4]['items'] = $menu_items;
                }
            }
        }
        return $menus;
    }
}
