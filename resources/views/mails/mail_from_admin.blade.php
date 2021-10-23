@component('mail::message')
{!! $data !!}

@component('mail::button', ['url' => $link])
www.grabit.co.tz
@endcomponent

    Regards,
    Merchant Service Team!

@endcomponent