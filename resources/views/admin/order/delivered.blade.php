@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    <div style="overflow-x: scroll;font-size:11px">
        <table class="ssj-table">
            <thead>
            <tr class="bg-info">
                <th>Transaction ID</th>
                <th>Customer Name</th>
                <th>Order From</th>
                <th>Vendor Name</th>
                <th>Vendor type</th>
                <th>Order Type</th>
                <th>Schedule</th>
                <th>Price</th>
                <th>Delivery fee</th>
                <th>Cooking Instruction</th>
                <th>Other Notes</th>
                <th>Payment</th>
                <th>Rider Name</th>
                <th>Due in</th>
                <th>Created</th>
                <th>Status</th>
                <th>Action</th>
                {{--<th>Created At</th>--}}
                {{--            <th colspan="1" width="160">Actions</th>--}}
            </tr>
            </thead>
            <tbody>
            @if(count($orders)>0)
                @foreach ($orders as $order)
                    <tr class="bg-info">
                        <td>{{!empty($order->transaction_id)?$order->transaction_id??'N/A': 'N/A'}}</td>
                        <td>{{!empty($order->user)?$order->user->name??'N/A': 'N/A'}}</td>
                        <td>{{!empty($order->order_from)?config('api.order.order_from')[$order->order_from]: 'N/A'}}</td>
                        <td>{{($order->get_vendor->name)??'N/A'}}</td>
                        <td>{{($order->get_vendor)?($order->get_vendor->vendor_type?$order->get_vendor->vendor_type->vendor_name:'N/A'):"N/A"}}</td>

                        <td>{{!empty($order->order_type)?config('api.order.order_type')[$order->order_type]: 'N/A'}}</td>
                        <td>
                            {{!empty($order->schedule)?config('api.order.schedule')[$order->schedule]: 'N/A'}}

                            @if(isset($order->schedule) && $order->schedule ==2)
                                "{{$order->schedule_time}}"
                            @endif
                        </td>
                        <td>{{!empty($order->order_total)?$order->order_total: 'N/A'}}</td>
                        <td>{{!empty($order->delivery_fee)?$order->delivery_fee: 'N/A'}}</td>
                        <td>{{!empty($order->cooking_directions)?$order->cooking_directions: 'N/A'}}</td>
                        <td>{{!empty($order->order_notes)?$order->order_notes: 'N/A'}}</td>
                        <td>{{!empty($order->payment)?config('api.order.payment')[$order->payment]['title']: 'N/A'}}</td>
                        <td>{{!empty($order->rider_name)?$order->rider_name: 'N/A'}}</td>
                        <td>{{!empty($order->due_in)?\App\Traits\TimeTrack::check($order->due_in)?"Already due":$order->due_in: 'N/A'}}</td>

                        <td>{{$order->created_at}}</td>
                        <td>
                            <button class="button-for-popup" data-toggle="modal" data-target="#modal_{{$order->id}}">
                                {{!empty($order->status)?config('api.order.status')[$order->status]: 'N/A'}}
                            </button>
                            <div class="modal fade" id="modal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                                <div class="modal-dialog modal-m modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 10px;">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h3 class="modal-title" id="myModalLabel">Statuses History</h3>
                                        </div>
                                        <div class="modal-body">
                                            @if(isset($statuses) && isset($statuses[$order->id]) && count($statuses[$order->id]))
                                                @foreach($statuses[$order->id] as $status)
                                                    <h4>
                                                        {{config('api.order.status')[$status->status]}} : {{$status->created_at}}
                                                    </h4>
                                                @endforeach
                                            @else
                                                <h4 class="text-center">No data to show</h4>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{route('orders.show',$order->id)}}" class="btn btn-primary mytooltip">
                                <i class="fa fa-eye"></i>
                                <span class="tooltiptext">Show</span>

                            </a>
                        </td>
                        {{--                    <td>--}}
                        {{--                        <a href="{{route('orders.edit',$order->id)}}" class="btn btn-success"><i class="fa fa-pencil"></i></a>--}}
                        {{--                        <a href="{{route('orders.show',$order->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>--}}
                        {{--                        <div style="display: inline-block; margin-left: 5px;">--}}
                        {{--                            <form action="{{route('orders.destroy', $order->id)}}" method="POST">--}}
                        {{--                                {{method_field('DELETE')}}--}}
                        {{--                                {{csrf_field()}}--}}
                        {{--                                <button type="submit" class= 'btn btn-danger'><i class="fa fa-trash-o"></i></button>--}}
                        {{--                            </form>--}}
                        {{--                        </div>--}}
                        {{--                    </td>--}}
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6">No data available</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    <div>
        <nav>
            {{$orders->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
