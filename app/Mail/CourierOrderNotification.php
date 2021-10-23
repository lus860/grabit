<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CourierOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type,$order)
    {
        $this->type=$type;
        $this->order=$order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject(config('api.mail_subjects')['order_'.$this->type])
            ->markdown('mails.courier_order')
            ->with([
                'order' => $this->order,
                'type' => $this->type,
            ]);
    }
}
