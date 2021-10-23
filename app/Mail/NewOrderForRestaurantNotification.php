<?php

namespace App\Mail;

use App\Models\Order;
use App\PendingOrders;
use App\Restaurant;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderForRestaurantNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Restaurant $restaurant,PendingOrders $order)
    {
        $this->restaurant=$restaurant;
        $this->order=$order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        dd($this->order->updated_at);
//        dd(Carbon::createFromFormat('Y-m-d H:i:s', $this->order->updated_at)->format('d M Y h:m a'));
//        $order_time = Carbon::create($this->order->updated_at)->format('d M Y h:m a');
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject(config('mail.subjects')['new_order_for_restaurant'])
            ->markdown('mails.new_order_for_restaurant')
            ->with([
                'restaurant_name' => $this->restaurant->name,
                'order_time' => $this->order->updated_at->format('d M Y h:i a'),
                'link' => 'https://grabit.co.tz/'
            ]);
    }
}
