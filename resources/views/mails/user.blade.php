@component('mail::message')
@if($type == 'otp')

    Hello,
    @if($formType==1)
Welcome to Grab it! Your login verification code (OTP) is: {{$otp}}
    @elseif($formType==2)
Welcome to Grab it! Your registration verification code (OTP) is: {{$otp}}
    @endif
@endif
    @if($type == 'create')
Hello {{$name}},    {{-- use double space for line break --}}

    Welcome to Grab it!
    @endif
    @if($type == 'update_email')
    Hello,

    Click in button for update your email:

@component('mail::button', ['url' => $link])
Update email
@endcomponent
    @endif

    Regards,
    Merchant Service Team!

@endcomponent