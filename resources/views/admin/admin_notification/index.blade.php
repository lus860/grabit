@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Title</th>
            <th>Message</th>
            <th>Created at</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if($notifications_for_admin_table->total())
            @foreach ($notifications_for_admin_table as $value)
                @if(isset($value->get_overdue_order) && $value->get_overdue_order)
                    <tr class="bg-info @if(!$value->status) bg-seen @endif">
                        <td>
                            An order is overdue
                        </td>
                        <td>
                            An order of “{{config('api.order.order_type')[$value->get_overdue_order->order_type]}}” from “{{$value->get_overdue_order->get_vendor->name}}” has been overdue now, please help speed up completion to avoid unhappy customers.
                        </td>
                        <td>
                            {{$value->created_at}}
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btn @if($value->status) btn-success @else btn-warning @endif check-admin-notify ">
                                <i class="fa fa-check @if(!$value->status) hide @endif"></i>
                                <i class="fa fa-eye @if($value->status) hide @endif"></i>
                                <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification @if($value->status) checked-for-admin @endif">
                            </a>
                        </td>

                    </tr>
                @endif
                @if(isset($value->menu_item_option) && $value->menu_item_option)
                    <tr class="bg-info @if(!$value->status) bg-seen @endif">
                        <td>
                            Menu item not available for over 48 hours
                        </td>
                        <td>
                            “{{$value->menu_item_option->menuOption->menuItem->Menu->get_vendor->name}}” has not got “{{$value->menu_item_option->value}}” on their “{{$value->menu_item_option->menuOption->name}}” menu
                        </td>
                        <td>
                            {{$value->created_at}}
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btn @if($value->status) btn-success @else btn-warning @endif check-admin-notify ">
                                <i class="fa fa-check @if(!$value->status) hide @endif"></i>
                                <i class="fa fa-eye @if($value->status) hide @endif"></i>
                                <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification @if($value->status) checked-for-admin @endif">
                            </a>
                        </td>

                    </tr>
                @endif
                @if(isset($value->menu_item) && $value->menu_item)
                    <tr class="bg-info @if(!$value->status) bg-seen @endif">
                        <td>
                            Menu item not available for over 48 hours
                        </td>
                        <td>{{$value->menu_item->Menu->get_vendor->name}}” has not got “{{$value->menu_item->name}}” on their items menu

                        </td>
                        <td>
                            {{$value->created_at}}
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btn @if($value->status) btn-success @else btn-warning @endif check-admin-notify ">
                                <i class="fa fa-check @if(!$value->status) hide @endif"></i>
                                <i class="fa fa-eye @if($value->status) hide @endif"></i>
                                <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification @if($value->status) checked-for-admin @endif">
                            </a>
                        </td>

                    </tr>
                @endif
                @if(isset($value->order) && $value->order)
                    <tr class="bg-info @if(!$value->admin_center) bg-seen @endif">
                        <td>
                            Pending order for “schedule = {{$value->order->schedule}}” not accepted by vendor
                        </td>
                        <td>
                            “{{$value->order->get_vendor->name}}” has not accepted an order of transaction ID
                            “{{$value->order->transaction_id}}” posted at “{{$value->order->created_at}}”
                        </td>
                        <td>
                            {{$value->created_at}}
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btn @if($value->admin_center) btn-success @else btn-warning @endif check-admin-notify ">
                                <i class="fa fa-check @if(!$value->admin_center) hide @endif"></i>
                                <i class="fa fa-eye @if($value->admin_center) hide @endif"></i>
                                <input type="hidden" data-type="order" data-id="{{$value->id}}" class="send-ajax-for-read-notification @if($value->admin_center) checked-for-admin @endif">
                            </a>
                        </td>

                    </tr>
                @endif
                @if(isset($value->vendor) && $value->vendor)
                    <tr class="bg-info @if(!$value->status) bg-seen @endif">
                        <td>
                            Offline vendor for over 24 hours
                        </td>
                        <td>“{{$value->vendor->name}}” has been offline for over 24 hours, kindly contact the vendor to check if all is good</td>
                        <td>
                            {{$value->created_at}}
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btn @if($value->status) btn-success @else btn-warning @endif check-admin-notify ">
                                <i class="fa fa-check @if(!$value->status) hide @endif"></i>
                                <i class="fa fa-eye @if($value->status) hide @endif"></i>
                                <input type="hidden" data-type="vendor" data-id="{{$value->id}}" class="send-ajax-for-read-notification @if($value->status) checked-for-admin @endif">
                            </a>
                        </td>

                    </tr>
                @endif
            @endforeach
        @else
            <tr><td colspan="6">No Data.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$notifications_for_admin_table->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/custom.css')}}" >
@endpush
