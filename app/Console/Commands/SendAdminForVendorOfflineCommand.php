<?php

namespace App\Console\Commands;

use App\Mail\AdminNotification;
use App\Models\AdminCenterNotification;
use App\Models\Notifications;
use App\Restaurant;
use App\Services\AuthService;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAdminForVendorOfflineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:vendor_offline';

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
        $data = Restaurant::where('status',2)->get();
        if ($data->count()){
            $data_from_cache=json_decode(cache('vendor_offline'),true);
            $new_data_for_cache=[];
            foreach ($data as $value){
                if (isset($data_from_cache[$value->id]) && $data_from_cache[$value->id]){
                    $cached_time=Carbon::parse($data_from_cache[$value->id]);
                    if (now() >= $cached_time){
                        $admins = User::where('user_type',1)->get();
                        if ($admins->count()){
                            foreach ($admins as $admin){
                                if ($admin->email){
                                    Mail::to($admin->email)->send(new AdminNotification($value,'vendor_offline'));
                                }
                            }
                        }
                        AdminCenterNotification::_save(['vendor_offline'=>$value->id]);
                        SendFirebaseNotificationHandlerService::sendAdmin('vendor_offline',$value);
                    }else{
                        $new_data_for_cache[$value->id]=$data_from_cache[$value->id];
                    }
                }else{
                    $new_data_for_cache[$value->id]=now()->addHours(24)->toDateTimeString();
                }
            }
            cache(['vendor_offline'=>json_encode($new_data_for_cache)],now()->addHours(30));
        }
    }
}
