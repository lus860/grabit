<?php

namespace App\Console\Commands;

use App\Mail\AdminFailedNotification;
use App\Mail\AdminNotification;
use App\Models\CourierOrders;
use App\Models\Notifications;
use App\Models\Setting;
use App\Models\PendingOrders;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAdminEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:admin';

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
        try {
            $admin_info = Setting::where(['title'=>'admin','keyword'=>'notification'])->first();

            $newOrders=PendingOrders::where(function ($query){
                $query->where('created_at','<', Carbon::now()->subMinutes(2));
                $query->where('seen',null);
                $query->where('status','waiting');
            })->select('id')->get();
            if ($newOrders->count()){
                $count = 0;
                $ids=[];

                foreach ($newOrders as $order){
                    if ($order->notification->admin_notification==null){
                        $count++;
                        $ids[]=$order->notification->id;
                        if ($order->notification->admin_firebase==null){
                            SendFirebaseNotificationHandlerService::sendAdmin('sendAdminAboutOrder',$order);
                        }
                        if ($admin_info){
                            $admin_info = unserialize($admin_info->description);
                            if ($admin_info['emails']){
                                if (is_array($admin_info['emails'])){
                                    foreach ($admin_info['emails'] as $email){
                                        Mail::to($email)->send(new AdminNotification($order,'order'));
                                    }
                                }else{
                                    Mail::to($admin_info['emails'])->send(new AdminNotification($order,'order'));
                                }
                            }
                        }
                        $admins = User::where('user_type',1)->get();
                        if ($admins->count()){
                            foreach ($admins as $admin){
                                if ($admin->email){
                                    Mail::to($admin->email)->send(new AdminNotification($order,'order'));
                                }
                            }
                        }
                    }
                }
                if (isset($ids)){
                    Notifications::whereIn('id',$ids)->update([
                        'admin_notification'=>1,
                        'admin_firebase'=>1
                    ]);
                }
            }
            $newOrdersCourier=CourierOrders::where(function ($query) {
                $query->where('created_at', '<', Carbon::now()->subMinutes(1));
                $query->where('seen', null);
                $query->where('status', 'waiting');
            })->get();
            if ($newOrdersCourier->count()){
                $admin_info = Setting::where(['title'=>'admin','keyword'=>'notification'])->first();
                foreach ($newOrdersCourier as $order) {
                    if ($admin_info) {
                        $admin_info = unserialize($admin_info->description);
                        if ($admin_info['emails']) {
                            if (is_array($admin_info['emails'])) {
                                foreach ($admin_info['emails'] as $email) {
                                    Mail::to($email)->send(new AdminNotification($order, 'courier_order'));
                                }
                            } else {
                                Mail::to($admin_info['emails'])->send(new AdminNotification($order, 'courier_order'));
                            }
                        }
                    }
                    $admins = User::where('user_type', 1)->get();
                    if ($admins->count()) {
                        foreach ($admins as $admin) {
                            if ($admin->email) {
                                Mail::to($admin->email)->send(new AdminNotification($order, 'courier_order'));
                            }
                        }
                    }
                }
            }
        }catch (\Exception $e) {
            Mail::to(config('api.admin')['email'])->send(new AdminFailedNotification($e->getMessage()));
        }
    }
}
