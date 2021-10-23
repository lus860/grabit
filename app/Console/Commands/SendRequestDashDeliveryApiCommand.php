<?php

namespace App\Console\Commands;

use App\Models\Notifications;
use App\Models\Setting;
use App\PendingOrders;
use App\Services\DashDeliveryService;
use App\Services\SendFirebaseNotificationHandlerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRequestDashDeliveryApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:dash_api';

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
        $notifications=Notifications::with('order')->where(['dash_api'=>null,'courier_order_id'=>null])->get();
        foreach ($notifications as $notification){
            if ($notification->order->schedule == 2){
                $call_dash_api_time=Carbon::parse($notification->order->schedule_time)
                    ->subMinutes(10)
                    ->subminutes((integer)Setting::where('keyword','delivery_time')->first()->description);
                $now=Carbon::now();
                if ($notification->order->accept==1 && $notification->order->order_type == 1){
                    if ($now>$call_dash_api_time){
//                        DashDeliveryService::sendRequestDashDelivery($notification->order);
//                        SendFirebaseNotificationHandlerService::sendUser(['status'=>'accepted'],$notification->order);
                        $notification->update(['dash_api'=>1]);
                    }
                }else if($notification->order->accept==1 && $notification->order->order_type != 1){
                    if ($now>$call_dash_api_time){
//                        SendFirebaseNotificationHandlerService::sendUser(['status'=>'accepted'],$notification->order);
                        $notification->update(['dash_api'=>1]);

                    }
                }

            }
            if ($notification->order->order_type != 1){
                $notification->update(['dash_api'=>1]);
            }
        }
        return true;
    }
}
