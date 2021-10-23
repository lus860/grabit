<?php

namespace App\Console\Commands;

use App\Models\Notifications;
use App\Models\Setting;
use App\Services\DashDeliveryService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\StatusesHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendFirebaseNotificationRestaurantUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:restaurantNotification';

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
        $notify_by_status=StatusesHistory::with('order')->where(
            ['restaurant_firebase'=>null,'status'=>'accepted','courier_id'=>null])->get();
        if ($notify_by_status->count()){
            foreach ($notify_by_status as $notification){
                if ($notification->order->schedule == 2){
                    if ($notification->order->order_type == 1){
                        $call_dash_api_time=Carbon::parse($notification->order->schedule_time)
                            ->subMinutes((integer)$notification->order->get_vendor->preparation_time)
                            ->subminutes((integer)Setting::where('keyword','delivery_time')->first()->description);
                    }else{
                        $call_dash_api_time=Carbon::parse($notification->order->schedule_time)
                            ->subMinutes((integer)$notification->order->get_vendor->preparation_time);
                    }
                    $now=Carbon::now();
                    if ($notification->order->accept==1){
                        if ($now>=$call_dash_api_time){
                            SendFirebaseNotificationHandlerService::sendRestaurantUser(['status'=>'accepted'],$notification->order,true);
                            $notification->update(['restaurant_firebase'=>1]);
                        }
                    }
                }
//            if ($notification->order->schedule != 2){
//                $notification->update(['restaurant_notification'=>1]);
//            }
            }
        }
        return true;
    }
}
