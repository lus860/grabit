<?php

namespace App\Console\Commands;

use App\Models\Notifications;
use App\Models\Setting;
use App\Services\SendFirebaseNotificationHandlerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendFirebaseNotificationUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
//        $notifications=Notifications::with('order')->where('user_notification',null)->get();
//        foreach ($notifications as $notification){
//            if ($notification->order->schedule == 2){
//                $call_dash_api_time=Carbon::parse($notification->order->schedule_time)
//                    ->subMinutes((integer)$notification->order->restaurant->preparation_time)
//                    ->subminutes((integer)Setting::where('keyword','delivery_time')->first()->description);
//                $now=Carbon::now();
//                if ($notification->order->accept==1){
//                    if ($now>=$call_dash_api_time){
//                        SendFirebaseNotificationHandlerService::sendUser(['status'=>'accepted'],$notification->order);
//                        $notification->update(['user_notification'=>1]);
//                    }
//                }
//            }
//            if ($notification->order->schedule != 2){
//                $notification->update(['restaurant_notification'=>1]);
//            }
//        }
//        return true;
    }
}
