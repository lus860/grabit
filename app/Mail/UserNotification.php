<?php

namespace App\Mail;

use App\Services\AuthService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $user;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type,$user,$token=null)
    {
        $this->type=$type;
        $this->user=$user;
        $this->url='https://grabit.co.tz/';
        if ($type == 'update_email'){
            $this->url=url('/').'/api/v1/user/update-mail?token='.$token.'&email='.config('api.global')['updated_email_'.AuthService::getUser()['id']];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject(config('api.mail_subjects')['user_'.$this->type])
            ->markdown('mails.user')
            ->with([
                'name' => $this->user->name,
                'type' => $this->type,
//                'otp' => $this->otp,
                'link' => $this->url
            ]);
    }
}
