@component('mail::message')
    Hello ,  {{-- use double space for line break --}}

@if($type == 'order')
    There is a new Pending order of transaction ID {{$data->transaction_id}} for “schedule = {{$data->schedule}}”
    not accepted by “{{$data->get_vendor->name}}” which was posted at “{{$data->created_at}}”
@endif
@if($type == 'vendor_offline')
    “{{$data->name}}” has been offline for over 24 hours. Kindly contact the vendor to check if all is good.
@endif
@if($type == 'product_not_item')
    {{$data->Menu->get_vendor->name}}” has not got “{{$data->name}}” on their items menu
@endif
@if($type == 'product_not')
    “{{$data->menuOption->menuItem->Menu->get_vendor->name}}” has not got “{{$data->value}}” on their “{{$data->menuOption->name}}” menu
@endif
@if($type == 'overdue_order')
    An order of “{{config('api.order.order_type')[$data->order_type]}}” from “{{$data->get_vendor->name}}” has been overdue now, please help speed up completion to avoid unhappy customers.
@endif
@if($type == 'courier_order')
    There is a new Courier order of transaction ID {{$data->transaction_id}}
    not accepted, which was posted at “{{$data->created_at}}
@endif

@component('mail::button', ['url' => $link])
www.grabit.co.tz
@endcomponent

    Regards,
    Automated Mailing Service

@endcomponent
