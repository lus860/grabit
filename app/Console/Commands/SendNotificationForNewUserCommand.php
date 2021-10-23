<?php

namespace App\Console\Commands;

use App\Models\Carrier;
use App\Services\SendFirebaseNotificationHandlerService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotificationForNewUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification_to_user';

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
        $users = User::where('created_at','<=',Carbon::now()->subMinutes(20))->where('notification',null)->get();
        if($users->count()){
            foreach ($users as $user){
                SendFirebaseNotificationHandlerService::send_notification_for_new_user($user);
                $user->update(['notification'=>1]);
            }
        }
    }
}
