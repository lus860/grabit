@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    <div class="row">
        <div class="col-md-4 card-custom">
            <div class="card-header-custom">
                <p>Total envelope deliveries</p>
            </div>
            <div class="clearfix"></div>
            <div class="card-body-custom">
                <p>
                    @if(isset($envelope_deliveries)) {{$envelope_deliveries}} @endif
                </p>
            </div>
        </div>
        <div class="col-md-4 card-custom">
            <div class="card-header-custom">
                <p>Total distance</p>
            </div>
            <div class="card-body-custom">
                <p>
                    @if(isset($total_distance)) {{$total_distance}} @endif
                </p>
            </div>
        </div>
        <div class="col-md-4 card-custom">
            <div class="card-header-custom">
                <p>Total price</p>
            </div>
            <div class="card-body-custom">
                <p>
                    @if(isset($total_price)) {{$total_price}} @endif
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-7 form-wrapper">
            <h3>Filter</h3>
            <form action="{{ url('/backend/reports/couriers/filter') }}" method="get">
                <div class="row form-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Rider</label>
                            <select name="rider_name" class="form-control">
                                <option value="" selected>Select Rider</option>
                                @foreach($riders as $rider)
                                    <option value="{{ $rider->id }}"
                                            @if(isset($filters) && isset($filters['rider_name']) && $filters['rider_name'] == $rider->id) selected @endif>
                                        {{ $rider->name }}({{ $rider->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Delivery City</label>
                            <select name="delivery_city" class="form-control">
                                <option value="" selected>Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                            @if(isset($filters) && isset($filters['delivery_city']) && $filters['delivery_city'] == $city->id) selected @endif>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Pick Up City</label>
                            <select name="pick_up_city" class="form-control">
                                <option value="" selected>Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                            @if(isset($filters) && isset($filters['pick_up_city']) && $filters['pick_up_city'] == $city->id) selected @endif>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Carriers</label>
                            <select name="carrier" class="form-control">
                                <option value="" selected>Select Carriers</option>
                                @foreach($carriers as $carrier)
                                    <option value="{{ $carrier->id }}"
                                            @if(isset($filters) && isset($filters['carrier']) && $filters['carrier'] == $carrier->id) selected @endif>{{ $carrier->carrier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row form-row">
                        <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>from</label>
                                <input name="created_from" type="date" class="form-control"
                                       @if(isset($filters) && isset($filters['created_from']) && $filters['created_from']) value="{{$filters['created_from']}}" @endif>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>to</label>
                                <input name="created_to" type="date" class="form-control"
                                       @if(isset($filters) && isset($filters['created_to']) && $filters['created_to']) value="{{$filters['created_to']}}" @endif>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Status</label>
                                <select name="status" class="form-control">
                                    <option value="" selected>Select Status</option>
                                    <option value="status_303"
                                            @if(isset($filters) && isset($filters['status']) && $filters['status'] == 'status_303') selected @endif>Delivered</option>
                                    <option value="Cancelled"
                                            @if(isset($filters) && isset($filters['status']) && $filters['status'] == 'Cancelled') selected @endif>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(isset($filters) && count($filters))
                        <a href="/backend/reports/report-for-couriers-export-csv/{{json_encode($filters)}}" target="_blank" class="btn btn-primary">Export</a>
                        @else
                            <a href="/backend/reports/report-for-couriers-export-csv/{{json_encode([])}}" target="_blank" class="btn btn-primary">Export</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

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
            @if(isset($orders) && $orders->count())
                @foreach ($orders as $order)
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
                            <div class="modal fade" id="modal_{{$order->id}}" tabindex="-1" role="dialog"
                                 aria-labelledby="smallModal" aria-hidden="true">
                                <div class="modal-dialog modal-m modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 10px;">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                &times;
                                            </button>
                                            <h3 class="modal-title" id="myModalLabel">Statuses History</h3>
                                        </div>
                                        <div class="modal-body">
                                            @if(isset($statuses) && isset($statuses[$order->id]) && count($statuses[$order->id]))
                                                @foreach($statuses[$order->id] as $status)
                                                    <h4>
                                                        {{config('api.courier_order.status')[$status->status]}}
                                                        : {{$status->created_at}}
                                                    </h4>
                                                @endforeach
                                            @else
                                                <h4 class="text-center">No data to show</h4>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close
                                            </button>
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
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6">No Courier orders</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    <div>
        <nav>
            @if(isset($filters))
            {{isset($orders)?$orders->appends($filters)->links():''}}
            @else
                {{isset($orders)?$orders->links():''}}
            @endif
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
<script !src="">
    function changeStatus(status, orderId) {
        document.getElementById('status-input-' + orderId).value = status;
        document.getElementById("status-form-" + orderId).submit();
    }

    function modalNone(id) {
        let modal = document.getElementById("modal-status-" + id);
        modal.style.display = "none";
    }

    function modalCancell(status, id) {

        var modal = document.getElementById("modal-status-" + id);
        modal.style.display = "block";
        const modalInput = document.getElementById("modal-input-" + id);
        modalInput.onkeyup = () => {
            const buttonSend = document.getElementById("send-" + id);
            if (modalInput.value && modalInput.value.length) {
                buttonSend.disabled = false;
            } else {
                buttonSend.disabled = true;
            }
            buttonSend.onclick = () => {
                let input = document.getElementById("modal-input-" + id);
                document.getElementById("status_text_" + id).value = input.value;
                document.getElementById('status-input-' + id).value = status;
                document.getElementById("status-form-" + id).submit();
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
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
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

    .fixing-position {
        margin-top: 2em;
    }
</style>

