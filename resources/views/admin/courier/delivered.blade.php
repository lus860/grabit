@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    <div style="overflow-x: scroll;font-size:11px">
        <table class="ssj-table">
            <thead>
            <tr class="bg-info">
                <th>Transaction ID</th>
                <th>Customer Name</th>
                <th>Mobile Number</th>
                <th>Pick Up Address</th>
                <th>Pick Up Information</th>
                <th>Pick Up Area</th>
                <th>Pick Up City</th>
                <th>Pick Up Latitude</th>
                <th>Pick Up Longitude</th>
                <th>Delivery Address</th>
                <th>Delivery Area</th>
                <th>Delivery City</th>
                <th>Delivery Information</th>
                <th>Distance</th>
                <th>Price</th>
                <th>Weight</th>
                <th>Rider Name</th>
                <th>Comment</th>
                <th>Carrier Name</th>
                <th>Parcel Type</th>
                <th>Payment</th>
                <th>Status Text</th>
                <th>Envelope</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if(count($orders)>0)
                @foreach ($orders as $key=>$order)
                    @php if ($order->seen == null) $seen[]=$order->id @endphp
                    <tr class="bg-info @if($order->seen == null) bg-seen @endif">
                        <td>{{$order->transaction_id ? $order->transaction_id : 'N/A'}}</td>
                        <td>{{$order->user ? $order->user->name : 'N/A'}}</td>
                        <td>{{$order->user ? $order->user->phone : 'N/A'}}</td>
                        <td>{{$order->pick_up_address ? $order->pick_up_address : 'N/A'}}</td>
                        <td>{{$order->pick_up_information ? $order->pick_up_information : 'N/A'}}</td>
                        <td>{{$order->pickUpArea ? $order->pickUpArea->name : 'N/A'}}</td>
                        <td>{{$order->pickUpCity ? $order->pickUpCity->name : 'N/A'}}</td>
                        <td>{{$order->pick_up_latitude ? $order->pick_up_latitude : 'N/A'}}</td>
                        <td>{{$order->pick_up_longitude ? $order->pick_up_longitude : 'N/A'}}</td>
                        <td>{{$order->delivery_address ? $order->delivery_address : 'N/A'}}</td>
                        <td>{{$order->deliveryArea ? $order->deliveryArea->name : 'N/A'}}</td>
                        <td>{{$order->deliveryCity ? $order->deliveryCity->name : 'N/A'}}</td>
                        <td>{{$order->delivery_information ? $order->delivery_information : 'N/A'}}</td>
                        <td>{{$order->distance ? $order->distance : 'N/A'}}</td>
                        <td>{{$order->price ? $order->price : 'N/A'}}</td>
                        <td>{{$order->weight ? $order->weight : 'N/A'}}</td>
                        <td>{{$order->rider_name ? $order->rider_name : 'N/A'}}</td>
                        <td>{{$order->comments ? $order->comments : 'N/A'}}</td>
                        <td>{{!empty($order->carrierRelation) ? $order->carrierRelation->carrier_name : 'N/A'}}</td>
                        <td>{{!empty($order->parcelType) ? $order->parcelType->parcel_name: 'N/A'}}</td>
                        <td>{{$order->payment ? config('api.courier_order.payment')[$order->payment]['title'] : 'N/A'}}</td>
                        <td>{{$order->status_text ? $order->status_text : 'N/A'}}</td>
                        <td>{{$order->envelope ? $order->envelope : 'N/A'}}</td>
                        <td>{{$order->created_at ? $order->created_at : 'N/A'}}</td>
                        <td>
                            <button class="button-for-popup" data-toggle="modal" data-target="#modal_{{$order->id}}">
                                {{!empty($order->status) ? config('api.courier_order.status')[$order->status]: 'N/A'}}
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
                                                        {{config('api.courier_order.status')[$status->status]}} : {{$status->created_at}}
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
                            <a href="{{route('courier-orders.show',$order->id)}}" class="btn btn-primary mytooltip">
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
                    @php if (isset($seen) && $orders->count()-1 == $key){
                        \App\Models\CourierOrders::whereIn('id',$seen)->update(['seen'=>1]);
                        \App\Models\Notifications::whereIn('order_id',$seen)->update(['admin_notification'=>1]);
                    }
                    @endphp
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
