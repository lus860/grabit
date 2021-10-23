<?php

namespace App\Mail\Api\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject('Warm welcome to Grab it!')
            ->markdown('mails.exmpl')
            ->with([
                'restaurant_name' => 'New Mailtrap User',
                'link' => 'https://grabit.co.tz/'
            ]);
    }
}
