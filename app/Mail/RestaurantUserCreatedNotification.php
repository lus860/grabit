<?php

namespace App\Mail;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RestaurantUserCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant=$restaurant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), config('mail.from')['name'])
            ->subject(config('mail.subjects')['welcome'])
            ->markdown('mails.welcome_restaurant')
            ->with([
                'restaurant_name' => $this->restaurant->name,
                'link' => 'https://grabit.co.tz/'
            ]);
    }
}
