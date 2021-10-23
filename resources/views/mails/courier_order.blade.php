@component('mail::message')
@if($type == 'cancelled')

Hello, {{$order->user->name}}.

We're sorry to inform you that "{{$order->user->name}}"
has cancelled your order number {{$order->transaction_id}} due to "{{$order->accept_message}}".
@endif

    Regards,
    Merchant Service Team!

@endcomponent