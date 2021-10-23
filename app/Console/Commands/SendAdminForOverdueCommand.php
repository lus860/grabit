<?php

namespace App\Console\Commands;

use App\Mail\AdminNotification;
use App\Models\AdminCenterNotification;
use App\Models\Setting;
use App\PendingOrders;
use App\Restaurant;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAdminForOverdueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:overdue';

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
        $data = PendingOrders::where('accept', 1)
            ->where('status',"!=",'status_303')
            ->where('status',"!=",'pending')
            ->where('status',"!=",'Cancelled')
            ->get();
        if ($data->count()) {
            foreach ($data as $value) {
                if ($value->order_type != 1 && $value->status == 'dispatch'){
                    continue;
                }
                if ($value->schedule == 1) {
                    if ($value->order_type == 1) {
                        $time = $value->created_at
                            ->addMinutes((integer)$value->get_vendor->preparation_time)
                            ->addMinutes((integer)Setting::where('keyword', 'delivery_time')->first()->description);
                    } else {
                        $time = $value->created_at
                            ->addMinutes((integer)$value->get_vendor->preparation_time);
                    }
                } else {
                    $time = Carbon::parse($value->schedule_time)->addMinutes(5);
                }
                if (now() >= $time) {
                    $flag = true;
                    if ($value->order_type==1 && $value->status == 'status_303'){
                        $flag=false;
                    }elseif ($value->order_type != 1 && $value->status == 'dispatch'){
                        $flag=false;
                    }
                    if ($flag){
                        $admins = User::where('user_type',1)->get();
                        if ($admins->count()){
                            foreach ($admins as $admin){
                                if ($admin->email){
                                    Mail::to($admin->email)->send(new AdminNotification($value, 'overdue_order'));
                                }
                            }
                        }
                        AdminCenterNotification::_save(['overdue_order' => $value->id]);
                        SendFirebaseNotificationHandlerService::sendAdmin('overdue_order', $value);
                    }
                }
            }
        }
    }
}
