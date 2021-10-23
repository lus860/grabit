@component('mail::message')
Hello {{$restaurant_name}},  {{-- use double space for line break --}}

Welcome to Grab it! We’re delighted to have you on-board and are excited to
start pushing in sales and orders to your restaurant.

Good news is you are now live on our Application and users can start ordering from
your restaurant immediately! To learn more, please visit

@component('mail::button', ['url' => $link])
www.grabit.co.tz
@endcomponent

    Regards,
    Merchant Service Team!

@endcomponent