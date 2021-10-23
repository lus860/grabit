<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRole;
use App\Http\Requests\CreateUser;
use App\Http\Requests\EditUser;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\OrderProduct;
use App\Repositories\UserRepository;
use App\Models\Restaurant;
use App\Services\SendPushNotificationFromFirebase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Ultraware\Roles\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\SSJUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\OrderStatus;

class UsersController extends Controller
{
    protected $user;
    public $status_codes = array(297,298,299,300,301,302,303, 304);
    public $status_texts = array('Accepted', 'Preparing', 'Prepared','RiderNotified','RiderPickup','Delivering','Delivered','Cancelled');
    public $status_ids = array(1,2,3,4,5,6,7,8);

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $title = $this->user->getTableName();
        $users = $this->user->userWithPaginate(2);
        foreach($users as $key=>$usr){
            $orders = SSJUtils::get_user_orders($usr->id);
            $data[$key]['user'] = $usr;
            $data[$key]['user']['orders'] = count($orders);
        }

        return view('admin.users.index', compact('users', 'title'));
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(){
        $title = "Admins List";
        $users = $this->user->userWithPaginate(1);
        /*$user = $this->user->all();*/
        foreach($users as $key=>$usr){
            $orders = SSJUtils::get_user_orders($usr->id);
            $data[$key]['user'] = $usr;
            $data[$key]['user']['orders'] = count($orders);
        }

        return view('admin.users.profile', compact('users', 'title'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $title = $this->user->getTableName();
        $users = $this->user->userWithRole();
        return view('admin.users.create', compact('title', 'users'));
    }

    /**
     * Show the create role form to the user.
     *
     * @return Response
     */

    public function role()
    {
        $title = $this->user->getTableName();
        return view('admin.users.roles', compact('title'));
    }

    /**
     * Store role in database.
     *
     * @param CreateRole $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createRole(CreateRole $request)
    {
        Role::create($request->all());
        session()->flash('flash_message', 'Role successfully added!');
        return redirect()->back();
    }

    /**
     * Store users in database.
     *
     * @param CreateUser $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUser $request)
    {

        $data = $request->except(['password']);
        if($request->input('password') != '') {
            $data['password'] = bcrypt($request->input('password'));
        }
        $data['origin']= 1;
        $this->user->create($data);
        session()->flash('flash_message', 'User successfully added!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int $id
     * @return Response
     */

    public function edit($id)
    {
        $data['title'] = $this->user->getTableName();
        $data['roles'] = Role::all();
        $data['user'] = $this->user->with('role')->findOrFail($id);
        return view('admin.users.edit', $data);
    }

    /**
     * Update the specified user.
     *
     * @param $id
     * @param EditUser $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, EditUser $request){

        $user = $this->user->find($id);
        //$user = $this->user->find(auth()->id()); Get admin password
        // Only admin can change user settings

        $user->find($id);
        if(isset($request->change_password)){
            $data['password']= bcrypt($request->input('password'));
            $user->update($data);
            session()->flash('flash_message', 'Password changed successfully!');
            return redirect()->back();
        }else {
            $data = $request->except(['password']);
            $user->update($data);
//            dd($user);

            $user->role()->sync($request->input('role'));
            session()->flash('flash_message', 'User successfully updated!');
            return redirect()->back();
        }

        /*if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect()->back()->withErrors('Your old password does not match');
        } else {
            $user->find($id);

            $data = $request->except(['password']);
            $data['password']= bcrypt($request->input('password'));

            $user->update($data);
            $user->role()->sync($request->input('role'));
            session()->flash('flash_message', 'User password and role successfully updated!');
            return redirect()->back();
        }*/
    }
    public function change_password_admin($id, EditUser $request){
        $user = $this->user->find($id);
        $user->find($id);
        $data['password']= bcrypt($request->input('password'));
        $user->update($data);
        session()->flash('flash_message', 'Password changed successfully!');
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id){
        $this->user->delete($id);
        //Without database OnDdelete Cascade
        //$product->size()->detach($id);
        session()->flash('flash_message', 'User acount successfully deleted!');
        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id){
        $data['title'] = 'User detail';
        $user = $this->user->findOrFail($id);
        $orders = SSJUtils::get_user_orders($user->id);
        $data['user'] = $user;
        $data['user']['stats'] = SSJUtils::get_user_order_stats($user->id);
        $data['user']['orders'] = $orders;
        return view('admin.users.show', $data);
    }

    public function add_user_api(Request $request){
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;

        //$phone = '255'.substr($username, -9);

        //$user_id = DB::table('users')->insertGetId(['id'=>null, 'name'=>$name, 'email'=>$username, 'password'=>Hash::make($password), 'phone'=>$phone]);

        $checkUser = User::where(['email'=>$email])->first();
        if(!empty($checkUser)){
            return response()->json([
                'success' => -1,
                'data' => null,
                'error' => null,
                'msg' => 'Error registering new user, user exists',
                'loginToken' => null
            ]);
        }else {
            $muser = new User();
            $muser->name = $name;
            $muser->email = $email;
            $muser->phone = SSJUtils::add255($phone);
            $muser->password = Hash::make($password);
            $muser->save();

            if ($muser->id != '') {
                $user = User::find($muser->id);
                Auth::loginUsingId($user->id, TRUE);
                return response()->json([
                    'success' => 1,
                    'data' => $this->get_user_data($muser->id),
                    'error' => null,
                    'msg' => 'Success',
                    'loginToken' => $user->createToken('Token')->accessToken
                ]);

            } else {
                return response()->json([
                    'success' => 0,
                    'data' => null,
                    'error' => null,
                    'msg' => 'Error registering new user',
                    'loginToken' => null
                ]);
            }
        }
    }
    public function send_reset_code(Request $request){
        $phone_number = $request->username;
        $phone = '255'.substr($phone_number, -9);
        $code = SSJUtils::RandomString(5);

        $check_user = DB::table('users')->where(['phone'=> $phone])->get();
        if (count($check_user)>0) {
            DB::table('users')->where(['id'=>$check_user[0]->id])->update(['resetcode'=>$code]);
            SSJUtils::send_sms($phone, "Password reset code: ". $code);
            return ['success'=>1, 'msg'=>'Success'];
        } else {
            return ['success'=>0, 'msg'=>'User not found'];
        }
    }

    public function send_verification_code(Request $request){
        $phone_number = $request->phone;
        $phone = '255' . substr($phone_number, -9);

        $check_user = User::where('phone', $phone)->first();

        if (!empty($check_user)) {
            $status = 1;
        } else {
            $status = 0;
        }

        try {
            $code = SSJUtils::RandomString(4);
            DB::table('phone_verification')->insertGetId(['msisdn' => $phone, 'code' => $code]);
            //$sms = "Hello, welcome to Mamboz Food, your verification code is: " . $code;
            //TODO: Add extra field from app
            $source_app = "Mamboz Food";
            $sms = "$source_app Login Code: ".$code;
            //SSJUtils::send_sms($phone, "Verification code: ". $code);
            SSJUtils::send_sms_message_bongolive($phone, $sms, '', '255788557786');
            return [
                'success' => 1,
                'msg' => 'Success',
                'number' => "$phone",
                'existing' => $status
            ];
        }catch(\Exception $e){
            return [
                'success' => 0,
                'msg' => 'Can not send code',
                'number' => "$phone",
                'existing' => $status
            ];
        }
    }

    public function verify_phone(Request $request){
        $phone_number = $request->phone;
        $code = $request->code;
        $phone = SSJUtils::add255($phone_number);
        $get_code = DB::table('phone_verification')->where(['code' => $code, 'msisdn' => $phone])->get();

        if (count($get_code) > 0) {
            return response()->json([
                'success' => 1,
                'msg' => 'Success'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'msg' => 'Code not found'
            ]);
        }
    }

    public function verify_code(Request $request){
        $phone_number = $request->phone;
        $code = $request->code;
        $type = $request->type;
        $phone = SSJUtils::add255($phone_number);
        $get_code = DB::table('phone_verification')->where(['code'=>$code, 'msisdn'=>$phone])->get();
        if($type == 'login'){
            if(count($get_code) > 0) {
                $user = User::where('phone','=', $phone)->first();
                Auth::loginUsingId($user->id, TRUE);
                $user_data = $this->get_user_data($user->id);

                DB::table('phone_verification')->where(['code'=>$code, 'msisdn'=>$phone])->update(['status'=>'1']);


                /*$user = User::where('phone', '=', $phone)->first();
                Auth::loginUsingId($user->id, TRUE);
                $user_data = $this->get_user_data($user->id);*/


                return response()->json([
                    'success'=>1,
                    'data'=>$user_data,
                    'error'=> null,
                    'msg'=>'Success',
                    'loginToken' => $user->createToken('Token')->accessToken
                ]);

                /*return ['success' => 1, 'msg' => 'Success', 'data'=>$user_data];*/
            }else{
                return response()->json([
                    'success' => 0,
                    'msg' => 'Code not found',
                    'data'=> response()->json(new \stdClass()),
                    'error'=> 'Failed',
                    'loginToken' => ''
                ]);
            }
        }elseif($type == 'register'){
            if(count($get_code) > 0) {
                DB::table('phone_verification')->where(['code'=>$code, 'msisdn'=>$phone])->update(['status'=>'1']);

                $name = $request->name;
                $email = $request->email;
                $origin = $request->origin;

                DB::table('users')->insertGetId(
                    ['id'=>null,
                        'name'=>$name,
                        'email'=>$email,
                        'phone'=>$phone,
                        'origin'=>$origin,
                        'password'=>Hash::make('Foodie@2019!')
                    ]);

                $user = User::where('phone', '=', $phone)->first();
                Auth::loginUsingId($user->id, TRUE);
                $user_data = $this->get_user_data($user->id);

                return response()->json([
                    'success'=>1,
                    'data'=>$user_data,
                    'error'=> null,
                    'msg'=>'Success',
                    'loginToken' => $user->createToken('Token')->accessToken
                ]);

                //return ['success' => 1, 'msg' => 'Success', 'data'=>array()];
            }else{
                return [
                    'data'=>new \stdClass(),
                    'error'=> null,
                    'loginToken' =>null,
                    'success' => 0,
                    'msg' => 'Code not found'
                ];
            }
        }else{
            return ['success' => 0, 'msg' => 'Unknown type of verification'];
        }
    }

    public function validate_reset_code(Request $request){
        $code = $request->code;

        $check_user = DB::table('users')->where(['resetcode'=>$code])->get();
        if (count($check_user)>0) {
            return ['success'=>1, 'msg'=>'Success', 'id'=>$check_user[0]->id];
        } else {
            return ['success'=>0, 'msg'=>'User not found', 'id'=>null];
        }
    }
    public function change_password(Request $request){
        $password = $request->password;
        $id = $request->id;
        $code = $request->code;

        $check_user = DB::table('users')->where(['id'=> $id, 'resetcode'=>$code])->get();
        if (count($check_user)>0) {
            DB::table('users')->where(['id'=>$check_user[0]->id])->update(['password'=>Hash::make($password), 'resetcode'=>'']);
            return ['success'=>1, 'msg'=>'Success', 'id'=>$id];
        } else {
            return ['success'=>0, 'msg'=>'Error changing password', 'id'=>null];
        }
    }

    public function get_user_data($user_id, $return='json'){
        //$users = DB::table('users')->where(['id'=>$user_id])->get();
        $user = User::find($user_id);
        $user_data = array();
        //foreach ($users as $key=>$user){
            //$user_id = $user->id;

            $user_data['id'] = $user->id;
            $user_data['name'] = $user->name;
            $user_data['phone'] = $user->phone;
            $user_data['token'] = $user->token;
            $user_data['email'] = $user->email;
            $user_data['app_expires'] = SSJUtils::app_expire_date();

            $payment_method = Payment::all();
            $delivery_method = Shipping::all();
            //$address = Address::where('user_id', $user_id)->get();

            $user_data['userData'] = array(
                'payment_options'=>$payment_method,
                'delivery_options'=>$delivery_method,
                'address'=>$this->get_addresses($user->id)
            );
        //}
        if($return == 'json') {
            return response()->json($user_data);
        }elseif($return == 'array'){
            return $user_data;
        }else{
            return response()->json($user_data);
        }
    }
    public function get_addresses($user_id){
        $address= [];
        $address_list = Address::where('user_id', $user_id)->get();
        foreach($address_list as $key=>$addr){
            //$address[$key]['address'] = $addr;
            $address[$key]["id"] = $addr->id;
            $address[$key]["user_id"] =$addr->user_id;
            $address[$key]["address_type"]=$addr->address_type;
            $address[$key]["address"] = $addr->address;
            //$address[$key]["country"] = $addr->country;
            //$address[$key]["city"] = !is_string($addr->city)?$addr->city->name:$addr->city;
            $address[$key]["city_name"] = "$addr->city";
            $address[$key]["area_id"] = $addr->area_id;
            $address[$key]["street"] = $addr->street;
            $address[$key]["town"]= $addr->town;
            $address[$key]["landmark"] = $addr->landmark;
            $address[$key]["house_number"] = $addr->house_number;
            $address[$key]["latitude"] = $addr->latitude;
            $address[$key]["longitude"] = $addr->longitude;
            $address[$key]["is_default"] =$addr->is_default;
            $address[$key]["status"] = $addr->status;
            $address[$key]["created_at"] = $addr->created_at;
            $address[$key]["updated_at"] = $addr->updated_at;
            $address[$key]['area'] = $addr->area;
            /*$address[$key]['city'] = [];*/
            if($addr->area != null) {
                $address[$key]['city'] = $addr->area->city;
            }
            $address[$key]['country'] = $addr->getCountry;
        }
        return $address;
    }
    public function get_all_user_data(Request $request){
        $user_data = array();
        $user = $request->user();
        if(!empty($user)) {
            $user_restaurant = Restaurant::where(['user_id'=>$user->id])->first();
            $user_id = $user->id;
            $payment_method = Payment::all();
            $delivery_method = Shipping::all();
            //$orders = Order::where('user_id', $user_id)->get();
            $address = [];
            $address_list = Address::where('user_id', $user_id)->get();
            foreach ($address_list as $key => $addr) {
                //$address[$key]['address'] = $addr;
                $address[$key]["id"] = $addr->id;
                $address[$key]["user_id"] = $addr->user_id;
                $address[$key]["address_type"] = $addr->address_type;
                $address[$key]["address"] = $addr->address;
                //$address[$key]["country"] = $addr->country;
                $address[$key]["city"] = $addr->city;
                $address[$key]["area_id"] = $addr->area_id;
                $address[$key]["street"] = $addr->street;
                $address[$key]["town"] = $addr->town;
                $address[$key]["landmark"] = $addr->landmark;
                $address[$key]["house_number"] = $addr->house_number;
                $address[$key]["latitude"] = $addr->latitude;
                $address[$key]["longitude"] = $addr->longitude;
                $address[$key]["is_default"] = $addr->is_default;
                $address[$key]["status"] = $addr->status;
                $address[$key]["created_at"] = $addr->created_at;
                $address[$key]["updated_at"] = $addr->updated_at;
                $address[$key]['area'] = $addr->area;
                $address[$key]['country'] = $addr->getCountry;
            }
            $favourite = new FavouritesController();

            $user_data['id'] = $user_id;
            $user_data['name'] = $user->name;
            $user_data['phone'] = $user->phone;
            $user_data['token'] = $user->token;
            $user_data['email'] = $user->email;
            $user_data['app_expires'] = SSJUtils::app_expire_date();

            $user_data['userData'] = array(
                'payment_options' => $payment_method,
                'delivery_options' => $delivery_method,
                'favourites' => $favourite->get_favourites($user_id),
                'address' => $address,
                'restaurant'=> !empty($user_restaurant)?$user_restaurant->getBasicData():new \stdClass(),
                'orders' => SSJUtils::get_user_orders($user_id),
                'options' => array(
                    'delivery_charge' => 0,
                    'couponCode' => 0
                )
            );
        }
        return response()->json($user_data);
    }

    public function list_orders(Request $request){
        $user= $request->user();
        return response()->json([
            'success'=>1,
            'orders'=>SSJUtils::get_user_orders($user->id)
        ]);
    }

    public function list_restaurant_orders(Request $request){
        $user= $request->user();
        return response()->json([
            'success'=>1,
            'new_orders'=>SSJUtils::get_restaurant_orders($user->restaurant_id, 1),
            'progressing_orders'=>SSJUtils::get_restaurant_orders($user->restaurant_id, 2),
            'delivered_orders'=>SSJUtils::get_restaurant_orders($user->restaurant_id, 3)
        ]);
    }

    public function refreshToken(Request $request){
        $user = $request->user();
        $imei = $request->imei;
        $token = $request->token;
        $userId = $user->id;

        if($userId != ''){
            if (DB::table('users')->where('id', $userId)->update(['token' => $token])) {
                return response()->json(['success' => 1, 'msg' => 'Token Updated.']);
            } else {
                return response()->json(['success' => 0, 'msg' => 'Error Updating Token.']);
            }
        }else{
            return response()->json(['success' => 0, 'msg' => 'Error Updating Token.']);
        }
    }

    public function get_user_tickets($id, Request $request){
    }

    public function sign_in(Request $request){
        $phone = $request->phone;
        $phone = SSJUtils::add255($phone);
        $check_user = User::where('phone','=', $phone)->first();
        Auth::loginUsingId($check_user->id, TRUE);
        $user = Auth::user();
    }

    public function add_address(Request $request){
        $address = new Address();
        //$user = $request->user();
        $user_address = Address::where('user_id', $request->user_id)->get();

        $address->user_id = $request->user_id;
        $address->area_id = $request->area_id;
        $address->address = $request->address;
        $address->address_type = $request->address_type;
        $address->city = $request->city != 'null'?$request->city:'';
        $address->town = $request->town!= 'null'?$request->town:'';
        $address->street = $request->street;
        $address->landmark = $request->landmark != ''?$request->landmark:'';
        $address->status = 1;
        $address->is_default = 1;

        //$address->floor = $request->floor_number != ''?$request->floor_number:'';
        $address->house_number = $request->house_number!=''?$request->house_number:'';
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->address_type = $request->addressType;



        if(count($user_address)>0) {
            DB::table('address')->where('user_id', $request->user_id)->update(['is_default' => 0]);
            if($address->save()){
                $success = 1;
            }else{
                $success = 0;
            }
        }else{
            if($address->save()){
                $success = 1;
            }else{
                $success = 0;
            }
        }

        $user_address = $this->get_addresses($request->user_id);
        return response()->json(['success'=>$success, 'address'=>$user_address]);
    }
    public function delete_address(Request $request){
        if(Address::where(['id'=>$request->id, 'user_id'=>$request->user_id])->delete()) {
            $success = 1;
        }else{
            $success = 0;
        }
        $user_address = $this->get_addresses($request->user_id);
        return response()->json(['success' => $success, 'address' => $user_address]);
    }
    public function set_default_address(Request $request){
        DB::table('address')->where('user_id', $request->user_id)->update(['is_default' => 0]);
        DB::table('address')->where('id', $request->id)->update(['is_default' => 1]);

        $user_address = $this->get_addresses($request->user_id);
        return response()->json(['success'=>1, 'address'=>$user_address]);
    }

    public function place_order(Request $request){
        $data = $request->all();
        $user = $request->user();

        $total = $data['total'];
        $user_id = $user->id;
        $address_id = $data['shipping_address'];
        $payment_method = $data['payment_method'];
        $quantity = $data['quantity'];
        $source = $data['source'];

        $delivery_type = \GuzzleHttp\json_decode($data['delivery_type']);
        $instruction = $data['instruction'];
        //print_r($delivery_type);
        $delivery_method = $delivery_type->deliveryType;

        $product_data = $data['products'];
        $products = \GuzzleHttp\json_decode($product_data);

        /*"[{"id":659,"name":"Test Menu 1","price":5000,"image":"https://app.simbadesign.co.tz/mamboz/uploads/99a23280048873e89aef0ab21d27397b.jpeg","quantity":1,"options":[{"Choice_of_bread":"Italian"},{"Sauces":"Tomato Sauce"},{"Sauces":"Mint Sauce"}]}]"*/

        if(!empty($products)){
            $image = $products[0]->image;
        }else{
            $image = 'order_default.png';
        }
        $restaurant_id = 4;

        $order = new Order();
        $order->user_id=$user_id;
        $order->status=1;
        $order->quantity=$quantity;
        $order->quantity=$quantity;
        $order->restaurant_id=$restaurant_id;
        $order->amount=$total;
        $order->shipping_id=$address_id;
        $order->payment_id=$payment_method;
        $order->address_id=$address_id;
        $order->img=$image;
        $order->instruction=$instruction;
        $order->source=$source;
        $order->delivery_method=$delivery_method;

        if($order->save()){
            $order_id = $order->id;

            foreach($products as $key=>$value){
                $order_product = new OrderProduct();
                $order_product->order_id = $order_id;
                $order_product->product_id = $value->id;
                //$order_product->options = $value->options;
                //$order_product->quantity = isset($value->quantity)?$value->quantity:15;
                $order_product->save();
            }

            $order_status = new OrderStatus();
            $order_status->status = $this->status_texts[0];
            $order_status->status_id = $this->status_ids[0];
            $order_status->order_id = $order_id;
            $order_status->save();

            $notification = new NotificationController($order_id);
            $notification->send_admin();
            $notification->send_customer();
            //SSJUtils::send_order_to_delivery($order_id);

            $restaurant_user = User::where('restaurant_id', $restaurant_id)->first();
            $token = $restaurant_user->token;

            $title = $order->restaurant->name;
            $big_text = "New order received from ".$user->name;
            $message = "New order received from ".$user->name;

            $data = array(
                'title' => $title,
                'type' => 'new_order',
                'body' => $message,
                'description' => $message,
                'order_id' => $order_id,
            );
            $notify = SSJUtils::send_notification($data, [$token]);
        }
        return response()->json(array('orders'=> SSJUtils::get_user_orders($user_id), 'success'=>1));
    }

    public function authenticate($client_id, $client_secret){
        header("Content-type: application/json");
        $guzzle = new Client();

        try {
            $response = $guzzle->post(url('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'scope' => '*'
                ],
            ]);
            return (string)$response->getBody();
        }catch(BadResponseException $e){
            $response = $e->getResponse();
            return (string)$response->getBody();
        }
    }


    public function get_token(Request $request){
        $response = $this->authenticate($request->client_id, $request->client_secret);
        return $response;
    }
    public function save_firebase_id(Request $request){
        $user = $request->user();
        $user->firebase_id = $request->firebase_user_id;
        $user->save();
        return response()->json([
            'success'=>1
        ]);
    }






    public function order_status_update($order_id, $status){
        $check_order_status = OrderStatus::where(['status_id'=> $status, 'order_id'=>$order_id])->first();
        $success = 0;
        if(empty($check_order_status)) {
            $order_status = new OrderStatus();
            $order_status->status_id = $status;
            $order_status->order_id = $order_id;

            if ($order_status->save()) {

                $order= Order::find($order_id);
                $order->status=$status;
                $order->save();

                if(!empty($order)) {
                    //$user = User::find($order->user_id);
                    $token = $order->users->token;
                    $name = $order->users->name;

                    $title = $order->restaurant->name;
                    /*$big_text = "Hello $name, your order #$order_id updated with status [".$this->status_texts[$status]."]";
                    $message = "Hello $name, your order #$order_id updated with status [".$this->status_texts[$status]."]";*/

                    switch($status){
                        case 1:
                            $message = "Your order #$order_id is received";
                            $do_notify = 1;
                            break;
                        case 2:
                            $message = "Your order #$order_id is being prepared";
                            $do_notify = 1;
                            break;
                        case 3:
                            //TODO: Send this to rider and dash admin, not customer
                            $message = "Your order #$order_id preparation done";
                            $do_notify = 1;
                        break;
                        case 4:
                            $message = "";
                            $do_notify = 0;
                        break;
                        case 5:
                            $message = "Rider picked up your order #$order_id";
                            $do_notify = 0;
                        break;
                        case 6:
                            $message = "Get ready! Rider started delivering your order #$order_id";
                            $do_notify = 1;
                        break;
                        case 7:
                            $message = "Your order is delivered";
                            $do_notify = 1;
                        break;
                        default:
                            $do_notify = 1;
                            $message = "Hello $name, your order #$order_id updated with status [".$this->status_texts[$status]."]";
                    }

                    $data = array(
                        'title' => $title,
                        'type' => 'order_update',
                        'body' => $message,
                        'description' => $message,
                        'order_id' => $order_id,
                    );
                    echo $message;
                    if($do_notify == 1) {

                        $notify = SSJUtils::send_notification($data, [$token]);
                    }
                }
                $success = 1;
            }
        }
        return $success;
    }


    public function complete_task(Request $request){
        $order_id = $request->order_id;
        $this->order_status_update($order_id, $this->status_ids[2]);
        $this_task = SSJUtils::get_order($order_id);
        return response()->json(array('success' => 1, 'order' => $this_task, 'statuses'=>SSJUtils::order_status($order_id)));
    }
    public function start_task(Request $request){
        $order_id = $request->order_id;

        $order = Order::find($order_id);
        if($order->delivery_method == 'Delivery') {
            $task_response = json_decode(SSJUtils::send_order_to_delivery($order_id));
            $delivery_task_id = $task_response->task_id;
            $order->delivery_task_id = $delivery_task_id;
            $order->save();
        }

        $this->order_status_update($order_id, $this->status_ids[1]);
        return response()->json(array('success'=>1));
    }

    public function update_task_async(Request $request){
        $order = Order::find($request->id);
        $order->firebase_id = $request->firebase_id;

        if($order->save()){
            return response()->json(array('success'=>1));
        }else{
            return response()->json(array('success'=>0));
        }
    }
    public function get_order($id){
        $order = SSJUtils::get_order($id);
        if(!empty($order)){
            return response()->json(array('success'=>1, 'order'=>$order));
        }else{
            return response()->json(array('success'=>0, 'order'=>[]));
        }
    }
    public function finalize_order(Request $request){
        $data = $request->getContent();
        //$json = '{"task_id":"187", "order_id": "1", "status":"301", "rider_name":"Said", "rider_tel":"0753443398","statuses":"","token":"12345"}';
        //$body = json_decode($json);
        $body = json_decode($data);
        //print_r($body);

        if($body->status == '303') {
            $order_id = $body->order_id;
            $this->order_status_update($order_id, $this->status_ids[6]);

            $order = Order::find($order_id);
            $token = $order->users->token;

            $title = $order->restaurant->name;
            $message = "Hello ".$order->users->name.", your order has arrived";

            $data = array(
                'title' => $title,
                'type' => 'order_completed',
                'body' => $message,
                'description' => $message,
                'order_id' => $order_id,
            );
            $notify = SSJUtils::send_notification($data, [$token]);

        }else if($body->status == '302'){
            $order_id = $body->order_id;
            //$this->order_status_update($order_id, $this->status_ids[3]);

            $order = Order::find($order_id);
            $token = $order->users->token;

            $this->order_status_update($order_id, $this->status_ids[5]);

            /*$title = $order->restaurant->name;
            $message = "Hello ".$order->users->name.", rider is delivering your order";

            $data = array(
                'title' => $title,
                'type' => 'order_completed',
                'body' => $message,
                'description' => $message,
                'order_id' => $order_id,
            );
            $notify = SSJUtils::send_notification($data, [$token]);*/

        }else if($body->status == '301'){
            $order_id = $body->order_id;
            $this->order_status_update($order_id, $this->status_ids[4]);
        }else if($body->status == '300'){
            $order_id = $body->order_id;
            $this->order_status_update($order_id, $this->status_ids[3]);
        }

        /*$order = SSJUtils::get_order($id);
        if(!empty($order)){
            return response()->json(array('success'=>1, 'order'=>$order));
        }else{
            return response()->json(array('success'=>0, 'order'=>[]));
        }*/
    }

    public function test_login_auth(){
        echo "Getting Login Token: ";
        $response = \GuzzleHttp\json_decode(SSJUtils::get_login_token('mamboz@dash.co.tz', 'Mamboz@2019!'));
        $access_token = $response->access_token;
        $refresh_token = $response->refresh_token;

        return $access_token;
    }
}
