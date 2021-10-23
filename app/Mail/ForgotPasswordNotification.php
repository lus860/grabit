<?php

namespace App\Mail;

use App\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;
    public $otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Restaurant $restaurant,$otp)
    {
        $this->restaurant=$restaurant;
        $this->otp=$otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
        ->subject(config('mail.subjects')['forgot_password'])
        ->markdown('mails.forgot_password')
        ->with([
            'restaurant_name' => $this->restaurant->name,
            'otp' => $this->otp,
            'link' => 'https://grabit.co.tz/'
        ]);
    }
}
