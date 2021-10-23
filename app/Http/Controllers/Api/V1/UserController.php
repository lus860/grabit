<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ImageController;
use App\Models\Address;
use App\Models\ClientSources;
use App\Http\Controllers\Api\ApiControllers;
use App\Models\CreditHistory;
use App\Models\Loyalty;
use App\Models\Restaurant;
use App\Models\RestaurantUsers;
use App\Models\UserCredits;
use App\Services\AuthService;
use App\Services\EmailService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\Services\SmsService;
use App\Traits\ApiResources;
use App\Traits\SetToken;
use App\Models\Message;
use Illuminate\Http\Request;
use Cookie;
use App\User;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiControllers
{
    use ApiResources;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->name = 'users';
        $this->limit = 100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     * @throws \Exception
     */
    public function updateUser(Request $request)
    {
        $this->name = 'user';
        $rules = [];
        if ($request->input('name')) {
            $rules['name'] = 'required';
        }
        if ($request->input('email')) {
            $rules['email'] = 'required|email|max:255|unique:users,email';
        }
        if ($request->input('phone')) {
            $rules['phone'] = 'required|max:12|unique:users,phone';
        }
        if (empty($rules)) {
            return $this->sendError(config('api.error_message')['not_parameter']);
        }
        $result = $this->validateData($request, $rules, [], 'user', true);
        if (gettype($result) == 'object') {
            return $result;
        }
        if ($request->input('email')) {
            $email = $this->updateUserEmail($request);
            $request->except(['email']);
        }
        if ($request->input('phone')) {
            $phone = $this->updateUserPhone($request);
            $request->except(['phone']);
        }
        if ($request->input('name')) {
            $user = User::_save($request, AuthService::getUser()['id']);
        }
        if (isset($email) || isset($phone)) {
            return $this->sendResponse(['token' => AuthService::getUser()['token']], 'user',
                config('api.success_message')['sending_message'], 200);
        }
        if (isset($user)) {
            return $this->sendResponse(['token' => $user['data']->token], 'user', config('api.success_message')['user_updated'], 200);
        }
        return $this->sendError(config('api.error_message')['other_error']);
    }

    /**
     * @param Request $request
     * @return int
     */
    public function updateUserEmail(Request $request)
    {
        $newToken = SetToken::setToken();
        EmailService::sendEmailWhenUserUpdateEmail($request->input('email'), $newToken);
        User::find(AuthService::getUser()['id'])->update(['remember_token' => $newToken]);
        return 1;
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Exception
     */
    public function updateUserPhone(Request $request)
    {
        $data = json_decode(cache('updated_phone'), true);
        $data[AuthService::getUser()['token']] = $request->phone;
        cache(['updated_phone' => json_encode($data)], now()->addMinutes(10));
        $user = User::find(AuthService::getUser()['id']);
        $otp = AuthService::getOtp(User::class, $user);
        SmsService::sendOtp($otp->otp, $request->phone);
        return 1;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     * @throws \Exception
     */
    public function check_update_phone(Request $request)
    {
        $rules['otp'] = 'required|max:4';
        $result = $this->validateData($request, $rules);
        if (gettype($result) == 'object') {
            return $result;
        }
        $user = User::where('otp', $request->otp)->first();
        if ($user) {
            $data = json_decode(cache('updated_phone'), true);
            $user->update(['phone' => $data[AuthService::getUser()['token']],
                'otp' => null
            ]);
            unset($data[AuthService::getUser()['token']]);
            cache(['updated_phone' => json_encode($data)], now()->addMinutes(10));
            return $this->sendResponse(null, null, 1, 200);
        }
        return $this->sendError(config('api.error_message')['error_otp']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|void
     */
    public function check_update_mail(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
            'token' => 'required'
        ];
        $result = $this->validateData($request, $rules);
        if (gettype($result) == 'object') {
            return abort(404);
        }
        $user = User::where('remember_token', $request->token)->first();
        if ($user) {
            $user->update(['email' => $request->email, 'remember_token' => null]);
            return view('updated_email')->with('flash_message', 'Your email is updated');
        }
        abort(404);
    }

    public function get_updated_profile(Request $request)
    {
        $token = $request->header('token-login');
        $user = User::where('token', $token)->select(['id as user_id', 'name', 'email', 'phone', 'token'])->first();
        if ($user) {
            return $this->sendResponse($user, 'user');
        }
        $this->sendError(0);
    }


    /**
     * @param Request $request
     * @param Address $address
     * @return bool|mixed
     */
    public function add_new_address(Request $request, Address $address)
    {
        $this->model = $address;
        $this->name = 'addresses';
        $result = $this->validateData($request, [
            'user_id' => ['required', 'integer', 'min:1'],
            'city' => ['required', 'integer', 'min:1'],
            'area' => ['required', 'integer', 'min:1'],
            'address_type' => ['required'],
            'line_1' => ['required'],
            'line_2' => ['required'],
            'longitude' => ['required'],
            'latitude' => ['required'],
            'landmark' => ['required'],
            'is_default' => ['required'],
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        return $this->_save($request);
    }

    /**
     * @param Request $request
     * @param Address $address
     * @return mixed
     */
    public function get_all_addresses(Request $request, Address $address)
    {
        $this->model = $address;
        $this->name = 'addresses';
//        $validatedData = Validator::make($request->all(),[
//            'user_id' => ['required','integer','min:1'],
//        ]);
//        if ($validatedData->fails()) {
//            return $this->sendError('Could not reach to the server or any message',406,[$validatedData->errors()]);
//        }
        $select = [
            'id as address_id',
            'address_type',
            'line_1',
            'line_2',
            'landmark',
            'is_default',
            'area_id as area',
            'longitude',
            'latitude',
            'city_id as city'
        ];
        $result = $this->get($request, '', null,
            ['user_id' => AuthService::getUser()['id']],
            $select, []);
        if ($result->getOriginalContent()['success']) {
            $result = $this->correctFormAddress($result->getOriginalContent()[$this->name]);
            return $this->sendResponse($result, $this->name);
        }
        return $this->sendError('You have no saved addresses in your profile, add an address to view customised services offering in your area');
    }

    /**
     * @param Request $request
     * @param Address $address
     * @return bool|mixed
     */
    public function edit_existing_address(Request $request, Address $address)
    {
        $this->name = 'address';
        $this->model = $address;
        $result = $this->validateData($request, [
            'user_id' => ['required', 'integer', 'min:1'],
            'address_id' => ['required', 'integer', 'min:1'],
            'city' => ['required', 'integer', 'min:1'],
            'area' => ['required', 'integer', 'min:1'],
            'address_type' => ['required'],
            'line_1' => ['required'],
            'line_2' => ['required'],
            'landmark' => ['required'],
            'is_default' => ['required'],
            'longitude' => ['required'],
            'latitude' => ['required']
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        return $this->_save($request, $request['address_id']);
    }

    /**
     * @param Request $request
     * @param Address $address
     * @return bool|mixed
     */
    public function delete_existing_address(Request $request, Address $address)
    {
        $this->name = 'address';
        $this->model = $address;
        $result = $this->validateData($request, [
            'user_id' => ['required', 'integer', 'min:1'],
            'address_id' => ['required', 'integer', 'min:1']
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        return $this->_delete(['user_id' => $request->user_id, 'id' => $request->address_id]);
    }

    /**
     * @param $result
     * @return mixed
     */
    private function correctFormAddress($result)
    {
        $newResult = $result->toArray();
        foreach ($newResult as $key => $value) {
            $newResult[$key]['is_default'] = ($value['is_default'] == 1) ? 1 : 0;
        }
        return $newResult;
    }

    public function get_user_name(Request $request)
    {
        $result = $this->validateData($request, [
            'phone_number' => ['required', 'integer', 'min:1'],
        ]);

        $user = User::where('phone', $request->phone_number)->first();
        if ($user) {
            return $this->sendResponse($user->name, 'user');
        }
        return $this->sendError(0);
    }

    public function check_available_credit(Request $request)
    {
        $result = $this->validateData($request, [
            'vendor_code' => ['required', 'integer'],
            'amount' => ['required'],
        ]);

        $user = AuthService:: getUser();
        $vendor = Restaurant::where('qr_code',$request->vendor_code)->first();
        if($user && count($user) && $vendor){
            $user_credit = UserCredits::where(['vendor_id'=> $vendor->id,'user_id'=> $user['id']])->first();
            if ($user_credit) {
                if($user_credit->available_credit >= $request->amount){
                    $available_credit = $user_credit->available_credit - $request->amount;
                    $used_credit = $user_credit->used_credit + $request->amount;
                    $user_credit->update(['available_credit'=> $available_credit,'used_credit'=>$used_credit]);
                    $restaurant = Restaurant::find($vendor->id);
                    $message_user = ['amount'=>$request->amount,'vendor_name'=>$restaurant->name,'status'=>'check_loyalty_amount_user','created_at'=>$user_credit->updated_at ->format('H:i:s')];
                    SendFirebaseNotificationHandlerService:: sendUser($message_user,$user);
                    $message_vendor = ['amount'=>$request->amount,'user_name'=>$user['name'],'status'=>'check_loyalty_amount_vendor','created_at'=>$user_credit->updated_at ->format('H:i:s')];
                    SendFirebaseNotificationHandlerService:: sendRestaurantUser($message_vendor,$user);
                    SmsService::sendSmsWhenVendorCheckUserLoyaltyAmount($user, $request->amount, $user_credit->updated_at->format('H:i:s'), $restaurant->phone);
                    $data = [
                        'transaction_id'=> self::setTransactionIdForCredit(),
                        'user_id'=>$user['id'],
                        'vendor_id'=> $vendor->id,
                        'vendor_type_id'=> $vendor->vendor_type->id,
                        'amount'=>$request->amount,
                        'txn_type'=>'Redeemed',
                    ];
                    if (isset($request->branch_id) && $request->branch_id){
                        $data['branch_id']=$request->branch_id;
                    }
                    CreditHistory::_save($data);
                    return $this->sendResponse(null,null);

                }
                return $this->sendError("You do not have sufficient loyalty credits to perform this transaction");
            }
        }
        return $this->sendError(0);
    }

    public function get_qr_code(){
        $user = AuthService:: getUser();
        if ($user['qr_code']){
            return $this->sendResponse($user['qr_code'],'qr_code');
        }
        return $this->sendError("User didn't have QR code");

    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function message_to_vendor(Request $request)
    {
        $result = $this->validateData($request, [
            'vendor_id' => ['required', 'integer'],
        ]);
        if (!isset($request->message) && !isset($request->image)){
            return $this->sendError('message or image must be on request!');
        }
        if (gettype($result) == 'object') {
            return $result;
        }
        $user = AuthService:: getUser();
        if ($user) {
            $restaurant = Restaurant::find($request->vendor_id);
            if ($restaurant) {
                $data = ['type' => 'user', 'from' => $user['id'], 'to' => $request->vendor_id, 'message' => $request->message];
                if (isset($request->image) && $request->image) {
                    $data['file'] = ImageController::imageUpload($request);
                }
                Message::_save($data);
                $message_user = [
                    'user_name' => $user['name'],
                    'message' => $request->message, 'status' => 'message_user'];
                SendFirebaseNotificationHandlerService::sendRestaurantUser($message_user, $restaurant);
                return $this->sendResponse(null, null);
            }
        }
        return $this->sendError(0);
    }

    /**
     * @return mixed
     */
    public function all_messages_for_user(){
        $user = AuthService:: getUser();
        if ($user && count($user)) {
            $user_messages = Message::where(['to' => $user['id']])->orWhere(['from' => $user['id']])->orderby('created_at','desc')->get();
            if($user_messages && $user_messages->count()){
                $chats = [];
                $all_vendor_id = [];
                foreach ($user_messages as $user_message){
                    if( count($chats) && count($all_vendor_id) && (array_search($user_message->from, $all_vendor_id) || array_search($user_message->to, $all_vendor_id))){
                        $messages = (object)[];
                        $messages->from = $user_message->type;
                        $messages->message_id = $user_message->id;
                        $messages->image = $user_message->file;
                        $messages->message = $user_message->message;
                        $messages->read = $user_message->read == 0? false:true;
                        $messages->created_at = $user_message->created_at->format('Y-m-d H:i:s') ;
                        if(array_search($user_message->from, $all_vendor_id)){
                            $chats[array_search($user_message->from, $all_vendor_id) -1]->messages[] = $messages;
                        }elseif(array_search($user_message->to, $all_vendor_id) || array_search($user_message->to, $all_vendor_id) == 0){
                            $chats[array_search($user_message->to, $all_vendor_id) -1]->messages[] = $messages;
                        }

                    } elseif(count($chats) && count($all_vendor_id) && (!array_search($user_message->from, $all_vendor_id) && !array_search($user_message->to, $all_vendor_id))) {
                        $messages = (object)[];
                        $data = (object)[];
                        $messages->from = $user_message->type;
                        $messages->message_id = $user_message->id;
                        $messages->image = $user_message->file;
                        $messages->message = $user_message->message;
                        $messages->read = $user_message->read == 0? false:true;
                        $messages->created_at = $user_message->created_at->format('Y-m-d H:i:s');
                        if($user_message->type == 'user'){
                            $data->vendor_id = $user_message->to;
                            $vendor= Restaurant::find($user_message->to);
                            $data->vendor_name = $vendor?$vendor->name:null;
                            $loyalty = Loyalty::where('vendor_id',$user_message->to )->first();
                            $data->image = $loyalty?$loyalty->image:null;
                        } elseif ($user_message->type == 'vendor'){
                            $data->vendor_id = $user_message->from;
                            $vendor= Restaurant::find($user_message->from);
                            $data->vendor_name = $vendor?$vendor->name:null;
                            $loyalty = Loyalty::where('vendor_id',$user_message->from)->first();
                            $data->image = $loyalty?$loyalty->image:null;
                        }

                        $data->messages[] = $messages;
                        $chats[] = $data;
                        $all_vendor_id[count($chats)] = $data->vendor_id;
                    } elseif (!count($chats)){
                        $data = (object)[];
                        $messages = (object)[];
                        $messages->from = $user_message->type;
                        $messages->message_id = $user_message->id;
                        $messages->image = $user_message->file;
                        $messages->message = $user_message->message;
                        $messages->read = $user_message->read == 0? false:true;
                        $messages->created_at = $user_message->created_at->format('Y-m-d H:i:s');
                        if($user_message->type == 'user'){
                            $data->vendor_id = $user_message->to;
                            $vendor= Restaurant::find($user_message->to);
                            $data->vendor_name = $vendor?$vendor->name:null;
                        } elseif ($user_message->type == 'vendor'){
                            $data->vendor_id = $user_message->from;
                            $data->vendor_name = Restaurant::find($user_message->from)->name;
                            $loyalty = Loyalty::where('vendor_id',$user_message->from)->first();
                            $data->image = $loyalty?$loyalty->image:null;
                        }

                        $data->messages[] = $messages;
                        $chats[] = $data;
                        $all_vendor_id[count($chats)] = $data->vendor_id;

                    }

                }
                if ($chats) {
                    return $this->sendResponse($chats, 'chats');
                }
            }else{
                $chat = 'You have no messages';
                return $this->sendError($chat);
            }

        }
        return $this->sendError(0);
    }

    public function chats_user_vendor(Request $request){

        $result = $this->validateData($request, [
            'vendor_id' => ['required', 'integer'],
        ]);

        if (gettype($result) == 'object') {
            return $result;
        }

        $user = AuthService:: getUser();
        $chat = 'You have no messages';

        if ($user && count($user)) {
            $from_user_messages = Message::where(['type'=>'user', 'from' => $user['id'], 'to' => $request->vendor_id])
                ->select(['id','type','created_at', 'to as vendor_id', 'message','file' ])
                ->orderby('created_at','desc')->get();
            $from_vendor_messages = Message::where(['type'=>'vendor', 'from' => $request->vendor_id, 'to' => $user['id']])
                ->select(['id', 'created_at', 'type', 'from as vendor_id', 'message','file'])
                ->orderby('created_at','desc')->get();
            $all_messages = $from_user_messages->merge($from_vendor_messages);
            if($all_messages->count()){
                $messages = [];
                $chats = (object)[];
                foreach ($all_messages as $all_message){
                    $message = (object)[];
                    $message->from = $all_message->type;
                    $message->message_id = $all_message->id;
                    $message->image = $all_message->file;
                    $message->message = $all_message->message;
                    $message->read = $all_message->read == 0? false:true;
                    $message->created_at = $all_message->created_at->format('Y-m-d H:i:s');
                    $messages[] = $message;
                }
                $chats->vendor_id = (int)$request->vendor_id;
                $vendor = Restaurant::find($request->vendor_id);
                $chats->vendor_name = $vendor?$vendor->name:null;
                $loyalty = Loyalty::where('vendor_id',$request->vendor_id)->first();
                $chats->image = $loyalty?$loyalty->image:null;
                $chats->messages = $messages;
                if ($chats) {
                    return $this->sendResponse($chats, 'chats');
                }
            }

            return $this->sendError($chat);
        }
        return $this->sendError($chat);
    }
}
