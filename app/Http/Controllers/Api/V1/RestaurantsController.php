<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\ImageController;
use App\Models\Area;
use App\Models\City;
use App\Models\ClientSources;
use App\Models\CreditHistory;
use App\Models\Day;
use App\Http\Controllers\Api\ApiControllers;
use App\Models\MenuDay;
use App\Models\Loyalty;
use App\Models\Message;
use App\Models\Setting;
use App\Models\VendorTypes;
use App\Models\Offering;
use App\Models\PendingOrders;
use App\Models\Restaurant;
use App\Models\RestaurantCuisine;
use App\Models\RestaurantOffering;
use App\Models\RestaurantOpeningTime;
use App\Models\RestaurantServiceArea;
use App\Models\RestaurantUsers;
use App\Models\VendorUsersHistory;
use App\Services\AuthService;
use App\Services\DashDeliveryService;
use App\Services\EmailService;
use App\Services\OrderService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\Services\SendPushNotificationFromFirebase;
use App\Services\SmsService;
use App\Services\UserLoyaltyService;
use App\Models\StatusesHistory;
use App\Traits\ApiResources;
use Carbon\Carbon;
use App\Models\CourierOrders;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RestaurantsController extends ApiControllers
{

    use ApiResources;

    public function __construct(Restaurant $model)
    {
        $this->model=$model;
        $this->name='restaurants';
        $this->limit=100;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function getRestaurants(Request $request)
    {
        $result = $this->validateData($request,[
            'city' => ['required','integer','min:1'],
            'service_area' => ['required','integer','min:0'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        if ($request->service_area == 0){
            $relations=['service_area', 'country', 'break_times','menu','redemptionServices',
                'menu.menu_items','menu.menu_items.options', 'menu.menu_items.options.optionValues'];
            $where=['city_id'=>$request->city];
            $whereHas=[];
            $result = $this->get($request,$whereHas, $relations,$where);

            if ($result->getOriginalContent()['success']){
                $result=$this->correctFormResult($result->getOriginalContent()[ $this->name],$relations,false);
                return $this->sendResponse($result,$this->name);
            }else{
                return $this->sendError(config('api.error_message')['no_data']);
            }
        }
        $area = RestaurantServiceArea::where('area_id',$request->service_area)->select('restaurant_id')->get()->toArray();
        if (count($area)){
            $data=[];
            foreach ($area as $value){
                $data[]=$value['restaurant_id'];
            }
            $relations=['service_area', 'country', 'break_times','menu',
                'menu.menu_items','menu.menu_items.options', 'menu.menu_items.options.optionValues'];
            $where=['city_id'=>$request->city];
            $whereHas=[];

            $result = $this->get($request,$whereHas, $relations,$where,'*',$data);

            if ($result->getOriginalContent()['success']){
                $result=$this->correctFormResult($result->getOriginalContent()[ $this->name],$relations,false);
                return $this->sendResponse($result,$this->name);
            }else{
                return $this->sendError(config('api.error_message')['no_data']);
            }
        }

        return $this->sendError(config('api.error_message')['no_data']);
    }

    /**
     * @param Request $request
     * @param string $whereHas
     * @param null $relation
     * @param array $where
     * @param string $select
     * @param array $whereIn
     * @return bool|mixed
     */
    public function show(Request $request, $whereHas = '', $relation = null, $where = [], $select = '*', $whereIn = [])
    {

        $result = $this->validateData($request, [
            'vendor_id' => ['required', 'integer', 'min:1'],
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        $where = ['id' => $request->vendor_id];
        $relation = ['service_area', 'country', 'break_times', 'menu','redemptionServices',
            'menu.menu_items', 'menu.menu_items.options', 'menu.menu_items.options.optionValues'];
        $result = parent::show($request, $whereHas, $relation, $where, $select, $whereIn);

        if ($result->getOriginalContent()['success']) {
            $result = $this->correctFormResult($result->getOriginalContent()[$this->name], $relation, true);
            return $this->sendResponse($result, $this->name, 'Ok', 200);
        } else {
            return $this->sendError('No restaurants with that requirements!!!', 404);
        }
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function get_orders(Request $request){
        $result = $this->validateData($request,[
            'vendor_id' => ['required','integer','min:1'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $user=RestaurantUsers::where(['restaurant_id'=>$request->vendor_id,'token'=>$request->header('token-login')])->first();
        if ($user){
            $orders = PendingOrders::where(['vendor_id'=>$request->vendor_id])->where('status','!=','pending')->orderBy('created_at',"desc")->get();
            if ($orders->count()){
                return $this->sendResponse($this->correctFormOrdersForRestaurant($orders),'orders');
            }
        }
        return $this->sendError('You have no orders available yet');
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function post_orders(Request $request){
        $result = $this->validateData($request,[
            'vendor_id' => ['required','integer','min:1'],
            'transaction_id' => ['required'],
            'accept' => ['required'],
            'accept_message' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $user=RestaurantUsers::where(['restaurant_id'=>$request->vendor_id,'token'=>$request->header('token-login')])->first();
        $order=PendingOrders::where(['vendor_id'=>$request->vendor_id,'transaction_id'=>$request->transaction_id])->first();
        if ($user && $order){
            $status=($request->accept==1)?'accepted':'Cancelled';
            $notify_by_status = StatusesHistory::_save(['order_id'=>$order->id,'status'=>$status]);

            $accept=['accept'=>$request->accept,'accept_message'=>$request->accept_message,'status'=>$status];
            if ($request->accept==0){
                $accept['seen']=null;
            }
            $order->update($accept);
//            if ($order->order_type==1 && $request->accept==1 && $order->schedule == 1){
//                DashDeliveryService::sendRequestDashDelivery($order);
//            }
            if ($request->accept==0){
                $messages_cancelled = __('messages.cancelled',
                    [
                        'name'=>$order->get_vendor->name,
                        'id'=>$order->transaction_id,
                        'accept_message'=>$order->accept_message,
                    ]);
                if ($order->user && isset($order->email)){
                    EmailService::sendEmailWhenOrderCancelled($order);
                }
                SmsService::sendSmsWhenOrderCancelled($order,$messages_cancelled);
                SendFirebaseNotificationHandlerService::sendUser($accept,$order);
                $notify_by_status['data']->update(['user_firebase'=>1]);
            }else{
                $order_for_check = json_decode($order->action,true);
                $answer = UserLoyaltyService::checkUserAvailableCredit($order_for_check[0],$order->user_id);
                if (gettype($answer) == 'string'){
                    return $this->sendError($answer);
                }
                $order_for_check[0] = $answer;
//                $order->action = json_encode($order_for_check);
                $order->update([
                    'action'=>json_encode($order_for_check),
                    'discount'=>$answer['discount'],
                    'discounted_price'=>$answer['discounted_price'],
                    'collection_amount'=>OrderService::getOrderCollectionAmount($order,$answer)
                ]);
                SendFirebaseNotificationHandlerService::sendUser($accept,$order);
                $notify_by_status['data']->update(['user_firebase'=>1]);

//                if ($order->schedule == 2){
//                    SendFirebaseNotificationHandlerService::sendRestaurantUser($accept,$order);
//                }
                if ($order->schedule == 1){
                    $notify_by_status['data']->update(['restaurant_firebase'=>1]);
                }
            }
//            if ($order->order_type != 1){
//                SendFirebaseNotificationHandlerService::sendUserSenondType($accept,$order,$order->order_type);
//            }
            return $this->sendResponse(null,null);
        }
        return $this->sendError(0,404);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function dispatch_order(Request $request){
        $result = $this->validateData($request,[
            'vendor_id' => ['required','integer','min:1'],
            'transaction_id' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $user=RestaurantUsers::where(['restaurant_id'=>$request->vendor_id,'token'=>$request->header('token-login')])->first();
        $order=PendingOrders::where(['vendor_id'=>$request->vendor_id,'transaction_id'=>$request->transaction_id])->first();
        if ($user && $order){
            PendingOrders::where('transaction_id',$request->transaction_id)->update(['status'=>'dispatch']);
            $notify_by_status = StatusesHistory::_save(['order_id'=>$order->id,'status'=>'dispatch']);
            if ($order->order_type != 1){
                SendFirebaseNotificationHandlerService::sendUser('dispatch',$order);
                $notify_by_status['data']->update(['user_firebase'=>1]);
            }
            return $this->sendResponse(null,null);
        }
        return $this->sendError(0,404);
    }

    /**
     * @param $orders
     * @return array
     */
    private function correctFormOrdersForRestaurant($orders){
        $newArray=[];
        $new_action=[];
        foreach ($orders->toArray() as $key =>$order){
            $action = json_decode($order['action'],true);
            if (!empty($action)){
                foreach ($action as $key2=>$value){
                    if ($orders[$key]->schedule == 2){
                        if ($orders[$key]->order_type == 1){
                            $scheduled_time=Carbon::parse($orders[$key]->schedule_time)
                                ->subMinutes((integer)$orders[$key]->get_vendor->preparation_time)
                                ->subminutes((integer)Setting::where('keyword','delivery_time')->first()->description);
                        }else{
                            $scheduled_time=Carbon::parse($orders[$key]->schedule_time)
                                ->subMinutes((integer)$orders[$key]->get_vendor->preparation_time);
                        }
                    }
                    $now=Carbon::now();
                    if (isset($value['price'])){
                        $new_action['created_at']=Carbon::parse($order['created_at'])->toDateTimeString();
                        $new_action['transaction_id']=$order['transaction_id'];
                        if ($orders[$key]->accept==1 && $orders[$key]->schedule == 2){
                            if ($now>=$scheduled_time){
                                $new_action['order_status']='Process';
                            }else{
                                $new_action['order_status']='Scheduled';
                            }
                        }elseif ($orders[$key]->status!='Cancelled' && $orders[$key]->schedule == 2){
                            $new_action['order_status']='Scheduled';
                        }else{
                            $new_action['order_status']=config('api.order.status_for_api.restaurant_get_order')[$orders[$key]->status];
                        }
                        $new_action['price']=$value['price'];
                        $new_action['discount']=$value['discount']??0;
                        $new_action['discounted_price']=$value['discounted_price']??0;
                        $new_action['collection_amount']=OrderService::getOrderCollectionAmount($order,$value);
                        $new_action['user_id']=$order['user_id'];
                        $new_action['order_type']=$order['order_type'];
                        $new_action['schedule']=$order['schedule'];
                        $new_action['schedule_time']=($order['schedule']==2)?$order['schedule_time']:'';
                        $new_action['delivery_fee']=$order['delivery_fee'];
                        $new_action['cooking_directions']=$order['cooking_directions'];
                        $new_action['order_notes']=$order['order_notes'];
                        $new_action['address_id']=$order['address_id'];
                        $new_action['payment']=$order['payment'];
                        $new_action['customer_name']=$orders[$key]->user->name;
                        $new_action['customer_phone']=$orders[$key]->user->phone;
                        if ($orders[$key]->schedule == 2){
                            $new_action['prepare_time']=$scheduled_time->format('Y-m-d H:i:s');
                        }else{
                            $new_action['prepare_time']=null;
                        }
                        $action[$key2]=$new_action;
                    }
                }
                $newArray[]=$action;
            }
        }
        return $newArray;
    }

    /**
     * @param $results
     * @param $relation
     * @param $type
     * @return array
     */
    private function correctFormResult($results,$relation,$type){
        $newResult=[];
        foreach ($results as $key=>$result){
            if (empty($result->menu) && !$type){
                continue;
            }
            $newResult[$key]['vendor_type_id']=$result['vendor_id']??null;
            $newResult[$key]['vendor_type']=$result['vendor_id']?VendorTypes::find($result['vendor_id'])->vendor_name:null;
            $newResult[$key]['vendor_type_image']=$result['vendor_id']?VendorTypes::find($result['vendor_id'])->image:null;
            $newResult[$key]['vendor_id']=$result['id'];
            $newResult[$key]['vendor_name']=$result['name'];
            $newResult[$key]['vendor_code']=$result->qr_code;
            $loyalty = Loyalty::where('vendor_id',$result['id'])->first();
            $newResult[$key]['loyalty']=$loyalty?1:0;
            $newResult[$key]['spend_amount']=($loyalty && $loyalty->spend)?  $loyalty->spend:0;
            $newResult[$key]['redemption_amount']=($loyalty && $loyalty->redemption)?  $loyalty->redemption:0;
            if ($result->redemptionServices->count()){
                foreach ($result->redemptionServices as $redemptionService){
                    $newResult[$key]['redemption_service'][]=['name'=>$redemptionService->name];
                }
            }else{
                $newResult[$key]['redemption_service']=[];
            }
            $newResult[$key]['contact_number_customers']="".$result['number_for_customers'];

            $newResult[$key]['restaurant_area']=$result->area->name;
            $service_area = RestaurantServiceArea::with('area')->where('restaurant_id',$result['id'])->get();
            foreach ($service_area as $area){
                $newResult[$key]['service_area'][]=$area->area->name;
            }
            $newResult[$key]['average_rating']=$result['average_rating']??0;
            $newResult[$key]['status']=$result['status'];
            $newResult[$key]['preparation_time']=$result['preparation_time'];
            $newResult[$key]['minimum_order']=$result['minimum_order']??0;
            $newResult[$key]['cost_for_two']=$result['cost_for_two'];
            $newResult[$key]['images']['banner']=$result['banner_image'];
            $newResult[$key]['images']['display']=$result['display_image'];
//            $newResult[$key]['delivery_time']=$result['preparation_time']; #TODO es chka

            //get openings days
            $break_time=$result->break_times??null;
            $newResult[$key]['opening_times']=$this->openingDays($result['id'],$break_time);
            //create location array
            $newResult[$key]['location']['country']=$result->country->name;
            $newResult[$key]['location']['city']=$result->city->name;
            $newResult[$key]['location']['address1']=$result['address1'];
            $newResult[$key]['location']['address2']=$result['address2'];
            $newResult[$key]['location']['lat']=$result['latitude'];
            $newResult[$key]['location']['lng']=$result['longitude'];
            //get offerings
            $newResult[$key]['offerings']=$this->getOffering($result['id']);
            //get cuisines
            $newResult[$key]['cuisines']=$this->getCuisines($result['id']);
            $branches = $result->branches()->select(['id as branch_id','name'])->get();
            $newResult[$key]['branches']=$branches->count()?$branches->toArray():[];
            //get menu in correct form

            if (isset($relation) && in_array('menu',$relation)){
                $newResult[$key]['menus']=$this->correctFormMenu($result->menu);
            }
        }
        return $newResult;
    }

    /**
     * @param $menus
     * @return array
     */
    private function correctFormMenu($menus){
        $newMenu=[];
        foreach ($menus as $key=>$menu){
            $newMenu[$key]['category_id']=$menu['id'];
            $newMenu[$key]['category_name']=$menu['name'];
            $newMenu[$key]['start_time']=$menu->getStartTimeAttribute();
            $newMenu[$key]['end_time']=$menu->getEndTimeAttribute();
            $newMenu[$key]['availability']=$menu['availability'];
            $newMenu[$key]['sort_id']=$menu['sort_id'];
            $newMenu[$key]['image']=$menu['image']??null;
            $newMenu[$key]['early_schedule_time']=$menu['early_schedule_time']??'';
            $newMenu[$key]['latest_schedule_time']=$menu['latest_schedule_time']??'';
            if ($menu['availability'] == 'specific_days'){
                $days = MenuDay::where('menu_id',$menu['id'])->get();
                if ($days->count()>0){
                    foreach ($days as $key5=>$day){
                        $newMenu[$key]['availability_days'][]=config('menu.days')[$day->day_id-1];
                    }
                }
            }
            $items=[];
            if ($menu->menu_items && !empty($menu->menu_items)) {
                foreach ($menu->menu_items as $key2 => $item) {
                    $items[$key2]['item_id'] = $item['id'];
                    $items[$key2]['name'] = $item['name'];
                    $items[$key2]['description'] = $item['description']??'';
                    $items[$key2]['item_type_code'] = $item['type'];
                    if ($item['type'] == 1){
                        $items[$key2]['item_type'] = 'Veg';
                    }elseif ($item['type'] == 2){
                        $items[$key2]['item_type'] = 'Non Veg';
                    }else{
                        $items[$key2]['item_type'] = '';
                    }

                    $items[$key2]['price'] = $item['price'];
                    $items[$key2]['max_quantity'] = $item['max_quantity'];
                    $items[$key2]['container_price'] = $item['container_price'];
                    $items[$key2]['popular_item'] = $item['popular_item'];
                    $items[$key2]['special_offer'] = $item['special_offer'];
                    $items[$key2]['offer_price'] = $item['offer_price'];
                    $items[$key2]['status'] = $item['status'];
                    $items[$key2]['image'] = $item['image'];
                    $options = [];
                    if ($item->options && !empty($item->options)) {
                        foreach ($item->options as $key3 => $option) {
                            $options[$key3]['option_id'] = $option['id'];
                            $options[$key3]['option_key'] = $option['name'];
                            $options[$key3]['option_type'] = ($option['type'] == 'addon') ? 'Add on' : 'Variant';
                            $options[$key3]['option_type_code'] = ($option['type'] == 'addon') ? 2 : 1;
                            (isset($option['item_maximum']) && $option['item_maximum']) ? $options[$key3]['item_maximum'] = $option['item_maximum'] : '';
                            $newValues = [];
                            if ($option->optionValues && !empty($option->optionValues)) {
                                foreach ($option->optionValues as $key4 => $value) {
                                    $newValues[$key4]['value_id'] = $value['id'];
                                    $newValues[$key4]['option_value'] = $value['value'];
                                    $newValues[$key4]['status'] = $value['status'];
                                    $newValues[$key4]['added_price'] = $value['price'];
                                }
                            }
                            $options[$key3]['values'] = $newValues;
                        }
                    }
                    $items[$key2]['group_options'] = $options;
                }
            }
            $newMenu[$key]['items']=$items;
        }
        return $newMenu;
    }

    /**
     * @param $id
     * @param null $break_times
     * @return array
     */
    private function openingDays($id,$break_times=null){
        $days = RestaurantOpeningTime::where('restaurant_id',$id)->get();
        $newDays=[];
        foreach ($days as $key1 =>$day){
            $newDays[$key1]['day']=config('menu.days')[$day['day']];
            $newDays[$key1]['opening_time']=$day['opening_time'];
            $newDays[$key1]['closing_time']=$day['closing_time'];
            $newDays[$key1]['open_status']=$day['open_status'];
            if (isset($break_times) && $break_times->count()){
                foreach ($break_times as $break_time){
                    if ($break_time->day == $day['day']){
                        $newDays[$key1]['break_time_start']=$break_time->time_from;
                        $newDays[$key1]['break_time_end']=$break_time->time_to;
                    }
                }
            }
        }
        return $newDays;
    }

    /**
     * @param $id
     * @return array
     */
    private function getOffering($id){
        $offerings = RestaurantOffering::where('restaurant_id',$id)->get();
        $allOfferings = Offering::all();
        $newOfferings=[];
        if ($offerings->count()){
            foreach ($allOfferings as $key1=>$value) {
                foreach ($offerings as $offering) {
                    if ($value->id == $offering->offering_id) {
                        $newOfferings[$value->title] = true;
                    }
                }
                if (empty($newOfferings[$value->title])){
                    $newOfferings[$value->title]=false;
                }
            }
        }
        return $newOfferings;
    }

    /**
     * @param $id
     * @return array
     */
    private function getCuisines($id){
        $cuisines = RestaurantCuisine::with('cuisine')->where('restaurant_id',$id)->get();
        $newCuisines=[];
        if ($cuisines->count()){
            foreach ($cuisines as $key1=>$cuisine){
                $newCuisines[$key1]['id']=$cuisine->cuisine->id;
                $newCuisines[$key1]['name']=$cuisine->cuisine->name;
            }
        }
        return $newCuisines;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function login(Request $request){
        $result = $this->validateData($request,[
            'email' => ['required','email'],
            'password' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $response = AuthService::loginRestaurantUser($request);
        if ($response['success']){
            return $this->sendResponse($response['data'],'login','ok',200);
        }
        return $this->sendError($response['data'],200);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function forgot_password(Request $request){
        $result = $this->validateData($request,[
            'email' => ['required','email'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $response = AuthService::forgetPasswordRestaurantUser($request);
        if ($response['success']){
            return $this->sendResponse($response['data'],'forgot_password','ok',200);
        }
        return $this->sendError($response['data'],406);
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function set_new_password(Request $request){
        $result = $this->validateData($request,[
            'otp' => ['required','integer'],
            'password' => ['required'],
        ]);
        if (gettype($result) == 'object'){
            return $result;
        }
        $response = AuthService::setNewPasswordRestaurantUser($request);
        if ($response['success']){
            return $this->sendResponse($response['data'],'set_new_password','ok',200);
        }
        return $this->sendError($response['data'],406);
    }



    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function set_firebase_token(Request $request)
    {
        $result = $this->validateData($request, [
            'vendor_id' => ['required', 'integer'],
            'firebase_token' => ['required'],
            'source' => ['required'],
            'token' => ['required','unique:vendor_users_history,token_login'],
        ]);
        if (gettype($result) == 'object') {
            return $result;
        }
        $old_user = VendorUsersHistory::where('token_login',$request->token)->first();
        if ($old_user){
            $response = VendorUsersHistory::_save([
                'vendor_id' =>  $request->vendor_id,
                'source' => $request->source,
                'firebase_token' => $request->firebase_token
            ],$old_user->id);

            if ($response['success']) {
                return $this->sendResponse(null, null);
            }
        }
        $response = VendorUsersHistory::_save([
            'vendor_id' =>  $request->vendor_id,
            'source' => $request->source,
            'token_login'=> $request->token,
            'firebase_token' => $request->firebase_token
        ]);
        if ($response['success']) {
            return $this->sendResponse(null, null);
        }
        return $this->sendError(0);

    }

    public function get_qr_code(){
        $restaurant_user = AuthService:: getRestaurantUser();
        $restaurant = Restaurant::where('id', $restaurant_user['restaurant_id'])->first();
        if ($restaurant){
            $qr_url = $restaurant->qr_url;
            if ($qr_url){
                return $this->sendResponse($qr_url ,'qr_code');
            }
        }
        return $this->sendError(0);
    }

    public function check_credits_for_vendor()
    {
        $restaurant_user = AuthService::getRestaurantUser();

        $vendor_id = $restaurant_user['vendor_id'];

        if($vendor_id){
            if (CreditHistory::where(['vendor_id'=> $vendor_id])->get()->count())
            {
                $this_month_shopped = CreditHistory::whereMonth('created_at', Carbon::now()->month)
                    ->where(['vendor_id'=> $vendor_id, 'txn_type'=> 'Shopped'])
                    ->sum('amount');

                $shopped = CreditHistory::select(['user_id', 'branch_id', 'amount', 'created_at'])
                    ->where(['vendor_id'=>$vendor_id, 'txn_type'=>'Shopped'])->get();

                if($shopped->count()>0){
                    foreach ($shopped as $key=>$item){
                        $shopped[$key]->created = Carbon::parse($item->created_at)->toDateTimeString();;
                        $shopped[$key]->user_name = $item->user->name;
                        if(isset($item->branch_id) && $item->branche){
                            $shopped[$key]->branch_name = $item->branche->name;
                        }
                        unset($shopped[$key]['user']);
                        unset($shopped[$key]['branche']);
                        unset($shopped[$key]['user_id']);
                        unset($shopped[$key]['branch_id']);
                        unset($shopped[$key]['created_at']);
                    }
                    $credit['shopped'] = $shopped;

                }

                $redeemed = CreditHistory::select(['user_id', 'branch_id', 'amount', 'created_at'])
                    ->where(['vendor_id'=>$vendor_id, 'txn_type'=>'Redeemed'])->get();

                if($redeemed->count()>0) {
                    foreach ($redeemed as $key=>$item) {
                        $redeemed[$key]->created = Carbon::parse($item->created_at)->toDateTimeString();;
                        $redeemed[$key]->user_name = $item->user->name;
                        if(isset($item->branch_id)){
                            $redeemed[$key]->branch_name = $item->branche->name;
                        }
                        unset($redeemed[$key]['user']);
                        unset($redeemed[$key]['branche']);
                        unset($redeemed[$key]['user_id']);
                        unset($redeemed[$key]['branch_id']);
                        unset($redeemed[$key]['created_at']);
                    }
                    $credit['redeemed'] = $redeemed;

                }
                if ($this_month_shopped){
                    $credit['sales_this_month'] = $this_month_shopped;
                }
                return $this->sendResponse(!empty($credit)?$credit:0, 'data');
            }
            return $this->sendError(0);
        }
        return $this->sendError(0);
    }


    public function message_to_user(Request $request)
    {
        $result = $this->validateData($request, [
            'user_id' => ['required', 'integer'],
        ]);
        if (!isset($request->message) && !isset($request->image)){
            return $this->sendError('message or image must be on request!');
        }
        if (gettype($result) == 'object') {
            return $result;
        }

        $vendor = AuthService::getRestaurantUser();
        if ($vendor && count($vendor)) {
            $user = User::find($request->user_id);
            if ($user) {
                $data = ['type' => 'vendor', 'from' => $vendor['vendor_id'], 'to' => $request->user_id, 'message' => $request->message];
                if (isset($request->image) && $request->image) {
                    $data['file'] = ImageController::imageUpload($request);
                }
                Message::_save($data);
                $restaurant = Restaurant::find($vendor['vendor_id']);
                $message_vendor = [
                    'vendor_name' => $restaurant->name,
                    'message' => $request->message,
                    'status' => 'message_vendor'
                ];
                SendFirebaseNotificationHandlerService::sendUser($message_vendor, $user);
                return $this->sendResponse(null, null);
            }
        }
        return $this->sendError(0);
    }

//    public function all_messages_for_vendor(){
//
//        $vendor = AuthService::getRestaurantUser();
//        if ($vendor && count($vendor)) {
//            $vendor_messages = Message::where(['type' => 'user', 'to' => $vendor['vendor_id']])
//                ->select(['file as image', 'from as user_id', 'message'])
//                ->get()->groupBy('from')->toArray();
//
//            if ($vendor_messages && count($vendor_messages)) {
//                $messages_for_vendor = [];
//                foreach ($vendor_messages as $items) {
//                    foreach ($items as $key => $item) {
//                        $messages_for_vendor[$key]['user_name'] = User::find($item['user_id'])->name;
//                        $messages_for_vendor[$key]['user_id'] = $item['user_id'];
//                        $messages_for_vendor[$key]['message'] = $item['message'];
//                        $messages_for_vendor[$key]['image'] = $item['image'];
//                    }
//                }
//            }
//
//                return $this->sendResponse($messages_for_vendor, 'messages_for_vendor');
//
//        }
//        return $this->sendError(0);
//    }


    public function all_messages_for_vendor()
    {
        $vendor = AuthService::getRestaurantUser();

        if ($vendor && count($vendor)) {
            $vendor_messages = Message::where(['to' => $vendor['vendor_id']])->orWhere(['from' => $vendor['vendor_id']])->orderBy('created_at','desc')->get();

            if ($vendor_messages->count()) {
                $chats = [];
                $all_user_id = [];
                foreach ($vendor_messages as $key => $vendor_message) {
                    if (count($chats) && count($all_user_id) && (array_search($vendor_message->from, $all_user_id) || array_search($vendor_message->to, $all_user_id))) {
                        $messages = (object)[];
                        $messages->from = $vendor_message->type;
                        $messages->message_id = $vendor_message->id;
                        $messages->image = $vendor_message->file;
                        $messages->message = $vendor_message->message;
                        $messages->read = $vendor_message->read == 0 ? false : true;
                        $messages->created_at = $vendor_message->created_at->format('Y-m-d H:i:s');
                        if (array_search($vendor_message->from, $all_user_id)) {
                            $chats[array_search($vendor_message->from, $all_user_id) - 1]->messages[] = $messages;
                        } elseif (array_search($vendor_message->to, $all_user_id) || array_search($vendor_message->to, $all_user_id) == 0) {
                            $chats[array_search($vendor_message->to, $all_user_id) - 1]->messages[] = $messages;
                        }

                    } elseif (count($chats) && count($all_user_id) && (!array_search($vendor_message->from, $all_user_id) && !array_search($vendor_message->to, $all_user_id))) {
                        $messages = (object)[];
                        $data = (object)[];
                        $messages->from = $vendor_message->type;
                        $messages->message_id = $vendor_message->id;
                        $messages->image = $vendor_message->file;
                        $messages->message = $vendor_message->message;
                        $messages->read = $vendor_message->read == 0 ? false : true;
                        $messages->created_at = $vendor_message->created_at->format('Y-m-d H:i:s');
                        if ($vendor_message->type == 'user') {
                            $data->user_id = $vendor_message->from;
                            $user = User::find($vendor_message->from);
                            $data->user_name = $user?$user->name:null;

                            $loyalty = Loyalty::where('vendor_id',$vendor_message->to)->first();
                            $data->image = $loyalty?$loyalty->image:null;
                        } elseif ($vendor_message->type == 'vendor') {
                            $data->user_id = $vendor_message->to;
                            $user = User::find($vendor_message->to);
                            $data->user_name = $user?$user->name:null;

                            $loyalty = Loyalty::where('vendor_id',$vendor_message->from)->first();
                            $data->image = $loyalty?$loyalty->image:null;
                        }
                        $data->messages[] = $messages;
                        $chats[] = $data;
                        $all_user_id[count($chats)] = $data->user_id;
                    } elseif (!count($chats)) {
                        $data = (object)[];
                        $messages = (object)[];
                        $messages->from = $vendor_message->type;
                        $messages->message_id = $vendor_message->id;
                        $messages->image = $vendor_message->file;
                        $messages->message = $vendor_message->message;
                        $messages->read = $vendor_message->read == 0 ? false : true;
                        $messages->created_at = $vendor_message->created_at->format('Y-m-d H:i:s');
                        if ($vendor_message->type == 'user') {
                            $data->user_id = $vendor_message->from;
                            $user = User::find($vendor_message->from);
                            $data->user_name = $user?$user->name:null;

                            $loyalty = Loyalty::where('vendor_id',$vendor_message->to)->first();
                            $data->image = $loyalty?$loyalty->image:null;

                        } elseif ($vendor_message->type == 'vendor') {
                            $data->user_id = $vendor_message->to;
                            $user = User::find($vendor_message->to);
                            $data->user_name =$user?$user->name:null;

                            $loyalty = Loyalty::where('vendor_id',$vendor_message->from)->first();
                            $data->image = $loyalty?$loyalty->image:null;
                        }

                        $data->messages[] = $messages;
                        $chats[] = $data;
                        $all_user_id[count($chats)] = $data->user_id;
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


    public function message_read(Request $request)
    {
        $result = $this->validateData($request, [
            'message_id' => ['required', 'integer'],
            'read' => ['required', 'in:true,false'],
            'secret_key' => ['required']

        ]);

        if (gettype($result) == 'object') {
            return $result;
        }
        if ($request->secret_key == config('app.secret_key_for_message')) {
            $request->read == true ? $read = 1 : $read = 0;
            Message::where('id', $request->message_id)->update(['read' => $read]);
            return $this->sendResponse(null, null);
        }
        return $this->sendError(0);
    }

    public function add_courier_task(Request $request){
        $validatedData = Validator::make($request->all(),[
            'pick_up_address' => ['required'],
            'pick_up_area' => ['required'],
            'pick_up_city' => ['required'],
            'pick_up_latitude' => ['required'],
            'pick_up_longitude' => ['required'],
            'pick_up_information' => ['required'],
            'delivery_address' => ['required'],
            'delivery_area' => ['required'],
            'delivery_city' => ['required'],
            'delivery_information' => ['required'],
            'delivery_latitude' => ['required'],
            'delivery_longitude' => ['required'],
            'distance' => ['required'],
            'price' => ['required'],
            'weight' => ['required'],
            'carrier' => ['required'],
            'parcel_type' => ['required'],
            'comments' => ['required'],
            'payment' => ['required'],
        ]);

        if ($validatedData->fails()) {
            return $this->sendResponse([],'order','Error',403);
        }
        $request->request->add(['restaurant_user_id' => RestaurantUsers::where('token',$request->header('token-login'))->first()->id]);
        $request->request->add(['status' => 'waiting']);
        $data = CourierOrders::_save($request->all());
        if ($data) {
            StatusesHistory::_save(['courier_id'=>$data->id,'status'=>'waiting']);
            $data['transaction_id'] = $this->setTransactionId($data['id'],CourierOrders::class);
            $data->save();
            return $this->sendResponse(['transaction_id' => $data['transaction_id']],'order','ok',200);
        }
    }

}
