@component('mail::message')
Hello {{$restaurant_name}},  {{-- use double space for line break --}}

You have received a new order on {{$order_time}}.

Please visit your application to accept the order, or call us

@component('mail::button', ['url' => $link])
www.grabit.co.tz
@endcomponent

    Regards,
    Merchant Service Team!

@endcomponent