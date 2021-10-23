@component('mail::message')
Hello {{$restaurant_name}},  {{-- use double space for line break --}}

Oopps! We have received a request from your side for resetting your profile.

Your one-time passcode is {{$otp}}. Kindly use this code to reset your grab it merchant account!

    Regards,
    Merchant Service Team!

@endcomponent