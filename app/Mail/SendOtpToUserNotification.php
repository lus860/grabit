<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpToUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $otp;
    public $type;
    public $formType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$otp,$formType)
    {
        if (!$name){
            $name=0;
        }
        $this->name=$name;
        $this->otp=$otp;
        $this->type='otp';
        $this->formType=$formType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subjcet = $this->formType==1? 'login_verification_code':'registration_verification_code';
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject(config('api.mail_subjects')[$subjcet])
            ->markdown('mails.user')
            ->with([
                'name' => $this->name,
                'otp' => $this->otp,
                'type' => $this->type,
                'formType' => $this->formType,
                'link' => 'https://grabit.co.tz/'
            ]);
    }
}
