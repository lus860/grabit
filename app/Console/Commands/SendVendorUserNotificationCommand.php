<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use App\Models\RestaurantUsers;
use App\Services\SendFirebaseNotificationHandlerService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendVendorUserNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification_to_vendor';
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
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $check_day = Carbon::now()->endOfMonth()->modify('-1 days')->toDateString();
        if ($today >= $check_day){
            $restaurant_users = RestaurantUsers::all();
            foreach ($restaurant_users as $restaurant_user){
                if($restaurant_user){
                    $vendor = Restaurant::find($restaurant_user->restaurant_id)->toArray();
                    $message_vendor = ['status'=>'notification_user'];
                    SendFirebaseNotificationHandlerService::sendRestaurantUser($message_vendor, $vendor);
                }
            }
        }
    }
}
