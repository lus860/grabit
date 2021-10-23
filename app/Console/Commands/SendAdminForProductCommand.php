<?php

namespace App\Console\Commands;

use App\Mail\AdminNotification;
use App\MenuItem;
use App\MenuItemOption;
use App\MenuItemOptionValue;
use App\Models\AdminCenterNotification;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAdminForProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:not_product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkMenuItem();
        $this->checkMenuOption();
//        $this->checkMenuOptionForVariant();
    }

    private function checkMenuItem(){
        $items = MenuItem::where('status',0)->get();
        if ($items->count()){
            $data_from_cache=json_decode(cache('menu_not_available'),true);
            $new_data_for_cache=[];
            foreach ($items as $value){
                if (isset($data_from_cache['items']) && isset($data_from_cache['items'][$value->id]) && $data_from_cache['items'][$value->id]){
                    $cached_time=Carbon::parse($data_from_cache['items'][$value->id]);
                    if (now() >= $cached_time){
                        $admins = User::where('user_type',1)->get();
                        if ($admins->count()){
                            foreach ($admins as $admin){
                                if ($admin->email){
                                    Mail::to($admin->email)->send(new AdminNotification($value,'product_not_item'));
                                }
                            }
                        }
                        Mail::to($admin->email)->send(new AdminNotification($value,'product_not_item'));
                        AdminCenterNotification::_save(['item_not_available'=>$value->id]);
                        SendFirebaseNotificationHandlerService::sendAdmin('product_not_item',$value);
                    }else{
                        $new_data_for_cache['items'][$value->id]=$data_from_cache['items'][$value->id];
                    }
                }else{
                    $new_data_for_cache['items'][$value->id]=now()->addHours(48)->toDateTimeString();
                }
            }
            cache(['menu_not_available'=>json_encode($new_data_for_cache)],now()->addHours(48));
        }
    }

    private function checkMenuOption(){
        $add_ons = MenuItemOptionValue::where(['status'=>0])->get();
        if ($add_ons->count()){
            $data_from_cache=json_decode(cache('menu_not_available'),true);
            $new_data_for_cache=[];
            foreach ($add_ons as $value){
                if (isset($data_from_cache['options']) && isset($data_from_cache['options'][$value->id]) && $data_from_cache['options'][$value->id]){
                    $cached_time=Carbon::parse($data_from_cache['options'][$value->id]);
                    if (now() >= $cached_time){
                        $admins = User::where('user_type',1)->get();
                        if ($admins->count()){
                            foreach ($admins as $admin){
                                if ($admin->email){
                                    Mail::to($admin->email)->send(new AdminNotification($value,'product_not'));
                                }
                            }
                        }
                        AdminCenterNotification::_save(['product_not_available'=>$value->id]);
                        SendFirebaseNotificationHandlerService::sendAdmin('product_not',$value);
                    }else{
                        $new_data_for_cache['options'][$value->id]=$data_from_cache['options'][$value->id];
                    }
                }else{
                    $new_data_for_cache['options'][$value->id]=now()->addHours(48)->toDateTimeString();
                }
            }
            cache(['menu_not_available'=>json_encode($new_data_for_cache)],now()->addHours(48));
        }
    }

//    private function checkMenuOptionForVariant(){
//        $variant = MenuItemOption::where(['status'=>0,'type'=>'variant'])->get();
//        if ($variant->count()){
//            $data_from_cache=json_decode(cache('menu_not_available'),true);
//            $new_data_for_cache=[];
//            foreach ($variant as $value){
//                if (isset($data_from_cache['variant']) && isset($data_from_cache['variant'][$value->id]) && $data_from_cache['variant'][$value->id]){
//                    $cached_time=Carbon::parse($data_from_cache['variant'][$value->id]);
//                    if (now() >= $cached_time){
//                        Mail::to(config('api.admin')['email'])->send(new AdminNotification($value,'product_not'));
//                        AdminCenterNotification::_save(['product_not_available'=>$value->id]);
//                        SendFirebaseNotificationHandlerService::sendAdmin('product_not',$value);
//                    }else{
//                        $new_data_for_cache['variant'][$value->id]=$data_from_cache['variant'][$value->id];
//                    }
//                }else{
//                    $new_data_for_cache['variant'][$value->id]=now()->addHours(48)->toDateTimeString();
//                }
//            }
//            cache(['menu_not_available'=>json_encode($new_data_for_cache)],now()->addHours(48));
//        }
//    }
}
