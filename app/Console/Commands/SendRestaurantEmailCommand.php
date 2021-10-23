<?php

namespace App\Console\Commands;

use App\Mail\AdminFailedNotification;
use App\Mail\AdminNotification;
use App\Mail\NewOrderForRestaurantNotification;
use App\Models\Notifications;
use App\PendingOrders;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRestaurantEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:restaurant';

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
            $notifications=Notifications::where(function ($query){
                $query->where('created_at','<', Carbon::now()->subMinutes(5));
                $query->where('restaurant_notification',null);
            })->get();
            $ids=[];
            if ($notifications->count()){
                foreach ($notifications as $notification){
                    $emails = $notification->order->get_vendor->restaurantEmail;
                    if ($emails->count()){
                        foreach ($emails as $email){
                            Mail::to($email->email)->send(new NewOrderForRestaurantNotification($notification->order->get_vendor,$notification->order));
                        }
                    }
                    Mail::to($notification->order->get_vendor->user->email)->send(new NewOrderForRestaurantNotification($notification->order->get_vendor,$notification->order));
                    $ids[]=$notification->id;
                }
                Notifications::whereIn('id',$ids)->update(['restaurant_notification'=>1]);

            }
        }catch (\Exception $e) {
            Mail::to(config('api.admin')['email'])->send(new AdminFailedNotification($e->getMessage()));
        }
    }
}
