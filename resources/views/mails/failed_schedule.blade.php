@component('mail::message')
    Hello ,  {{-- use double space for line break --}}

    You have received an failed jobs.Please check
    Error:
    {{$message}}

@component('mail::button', ['url' => $link])
www.grabit.co.tz
@endcomponent

    Regards,
    Merchant Service Team!

@endcomponent