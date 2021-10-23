@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    <div class="row">
        <div class="col-md-4 card-custom">
            <div class="card-header-custom">
                <p>Waiting for admin to accept your order</p>
            </div>
            <div class="clearfix"></div>
            <div class="card-body-custom">
                <p>{{\App\Models\CourierOrders::where('status','waiting')->get()->count()}}</p>
            </div>
        </div>
        <div class="col-md-4 card-custom">
            <div class="card-header-custom">
                <p>Preparing your delivery</p>
            </div>
            <div class="card-body-custom">
                <p>{{\App\Models\CourierOrders::where('status','accepted')->orwhere('status','status_300')
                            ->orwhere('status','status_301')->orwhere('status','status_302')->get()->count()}}</p>
            </div>
        </div>
        <div class="col-md-4 card-custom">
            <div class="card-header-custom">
                <p>Your delivery is on the way</p>
            </div>
            <div class="card-body-custom">
                <p>{{\App\Models\CourierOrders::where('status','delivered')->orwhere('status','status_303')->get()->count()}}</p>
            </div>
        </div>
    </div>
    <div style="overflow-x: scroll;font-size:11px">
        <table class="ssj-table">
            <thead>
            <tr class="bg-info">
                <th>Transaction ID</th>
                <th>Name</th>
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
                        @if($order->vendor_user)
                            <td>{{$order->vendor_user ? $order->vendor_user->restaurant->name: 'N/A'}}</td>
                            <td>{{$order->vendor_user ? $order->vendor_user->restaurant->phone : 'N/A'}}</td>
                        @else
                            <td>{{$order->user ? $order->user->name : 'N/A'}}</td>
                            <td>{{$order->user ? $order->user->phone : 'N/A'}}</td>
                        @endif
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
                            <div id="modal-status-{{$order->id}}" class="canceled-modal">
                                <div class="canceled-modal-content">
                                    <p>Text for canceling</p>
                                    <input  id="modal-input-{{$order->id}}" class="modal-input form-control">
                                    <button disabled id="send-{{$order->id}}" class="btn btn-primary">Send</button>
                                    <button class="btn btn-danger" onclick="modalNone({{$order->id}})" >Exit</button>

                                </div>
                            </div>
                            <a href="{{route('courier-orders.show',$order->id)}}" class="btn btn-primary mytooltip">
                                <i class="fa fa-eye"></i>
                                <span class="tooltiptext">Show</span>

                            </a>
                            @if($order->status != 'accepted' && $order->status != 'Cancelled' && $order->status != 'status_301' && $order->status != 'status_302' )
                            <a onclick="changeStatus('accepted',{{$order->id}})" class="btn btn-warning mytooltip">
                                <i class="fa fa-check"></i>
                                <span class="tooltiptext">Tick</span>
                            </a>
                            <a onclick="modalCancell('Cancelled',{{$order->id}})" class="btn btn-danger mytooltip">
                                <i class="fa fa-times"></i>
                                <span class="tooltiptext">Cross</span>
                            </a>
                            @endif
                        </td>
                        <form action="/backend/change-order-status-courier" id="status-form-{{$order->id}}" method="post">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order->id}}">
                            <input type="hidden" name="status" value="" id="status-input-{{$order->id}}">
                            <input type="hidden" name="status_text" value="" id="status_text_{{$order->id}}">
                        </form>
                    </tr>
                    @php if (isset($seen) && $orders->count()-1 == $key){
                        \App\Models\CourierOrders::whereIn('id',$seen)->update(['seen'=>1]);
                        \App\Models\Notifications::whereIn('courier_order_id',$seen)->update(['admin_notification'=>1]);
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
<script !src="">
   function changeStatus(status,orderId){
        document.getElementById('status-input-'+orderId).value = status;
        document.getElementById("status-form-"+orderId).submit();
    }
    function modalNone(id) {
        let modal = document.getElementById("modal-status-"+id);
        modal.style.display = "none";
    }
    function modalCancell(status,id) {

        var modal = document.getElementById("modal-status-"+id);
        modal.style.display = "block";
        const modalInput = document.getElementById("modal-input-"+id);
        modalInput.onkeyup = () => {
            const buttonSend = document.getElementById("send-"+id);
            if(modalInput.value && modalInput.value.length){
                buttonSend.disabled = false;
            }else{
                buttonSend.disabled = true;
            }
            buttonSend.onclick = () => {
                let input = document.getElementById("modal-input-"+id);
                document.getElementById("status_text_"+id).value = input.value;
                document.getElementById('status-input-'+id).value = status;
                document.getElementById("status-form-"+id).submit();
            }
        }
    }




</script>
<style>
    .canceled-modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .canceled-modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 50px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
    }
    .modal-input {
        width: 90%
    }

    .fixing-position{
        margin-top: 2em;
    }
</style>
