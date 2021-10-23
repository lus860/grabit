<?php


namespace App\Mail;

use App\PendingOrders;
use App\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$type=false)
    {
        $this->data=$data;
        $this->type=$type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject(config('api.mail_subjects')['message_for_admin'])
            ->markdown('mails.new_order_for_admin')
            ->with([
                'data' => $this->data,
                'type' => $this->type,
                'link' => 'https://grabit.co.tz/'
            ]);
    }
}