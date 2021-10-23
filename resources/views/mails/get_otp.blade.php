@component('mail::message')
@if($name)
Hello {{$name}},  {{-- use double space for line break --}}
@else
    Hello,
@endif

Welcome to Grab it! Your login verification code (OTP) is: {{$otp}}

    Regards,
    Merchant Service Team!

@endcomponent