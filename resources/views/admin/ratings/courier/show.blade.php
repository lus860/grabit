@extends('admin.backend.tblTemplate')
@section('title',$title)
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
    {!! $map['js'] !!}
@endpush
@section('body')
    <div class="row ssj-form-wrapper">
        <div class="col-lg-5 col-md-12">
            {!! $map['html'] !!}
        </div>
        <div class="col-lg-7 col-md-12 ">
            <div class="row">
                @if(isset($order) && $order->count())
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Pick Up Address</label>
                            <p>{{!empty($order->pick_up_address) ? $order->pick_up_address : 'N/A'}}</p>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Pick Up Area</label>
                            <p class="item-display">
                                {{!empty($order->pickUpArea)?$order->pickUpArea->name: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Pick Up City</label>
                            <p class="item-display">
                                {{!empty($order->pickUpCity)?$order->pickUpCity->name: 'N/A'}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Pick Up Latitude</label>
                            <p>{{!empty($order->pick_up_latitude) ? $order->pick_up_latitude : 'N/A'}}</p>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Pick Up Longitude</label>
                            <p class="item-display">
                                {{!empty($order->pick_up_longitude)?$order->pick_up_longitude: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Pick Up Information</label>
                            <p class="item-display">
                                {{!empty($order->pick_up_information)?$order->pick_up_information: 'N/A'}}
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery Address</label>
                            <p class="item-display">
                                {{!empty($order->delivery_address) ? $order->delivery_address : 'N/A'}}
                            </p>
                        </div>

                        <div id="myModal" class="canceled-modal">
                            <div class="canceled-modal-content">
                                <span class="close">&times;</span>
                                <p>Text for canceling</p>
                                <input  id="modal-input" class="modal-input form-control">
                                <button disabled id="send" class="btn btn-primary">Send</button>
                            </div>

                        </div>
                        <div class="col-lg-4 col-md-4">
                            <form action="/backend/change-order-status-courier" id="status" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <input type="hidden" id="status_text" name="status_text" value="">
                                <label class="delivery_label">Status</label>
                                <select class="form-control status-select"  name="status" id="status_selected">
                                    @php $statuses = array_except(config('api.courier_order.status'), array('status_300','status_301', 'status_302','status_304','pending')) @endphp
                                    @foreach(config('api.courier_order.status') as $key=>$status)
                                        @if(!empty($order->status) && $key == $order->status)
                                            <option class="status-crop" value="{{$key}}" selected disabled>
                                                {{$status}}
                                            </option>
                                        @endif
                                        @if(($key == 'Cancelled' || $key== 'status_303' || $key == 'accepted') && (!empty($order->status) && $key != $order->status))
                                            <option class="status-crop" value="{{$key}}">
                                                {{$status}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>

                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Transaction ID</label>
                            <p class="item-display">
                                {{!empty($order->transaction_id)?$order->transaction_id: 'N/A'}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery Area</label>
                            <p>{{!empty($order->deliveryArea) ? $order->deliveryArea->name : 'N/A'}}</p>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery City</label>
                            <p class="item-display">
                                {{!empty($order->deliveryCity)?$order->deliveryCity->name: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery Information</label>
                            <p class="item-display">
                                {{!empty($order->delivery_information)?$order->delivery_information: 'N/A'}}
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery Latitude</label>
                            <p>{{!empty($order->delivery_latitude) ? $order->delivery_latitude : 'N/A'}}</p>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery Longitude</label>
                            <p class="item-display">
                                {{!empty($order->delivery_longitude)?$order->delivery_longitude: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Distance</label>
                            <p class="item-display">
                                {{!empty($order->distance)?$order->distance: 'N/A'}}
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Price</label>
                            <p>{{!empty($order->price) ? $order->price : 'N/A'}}</p>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Weight</label>
                            <p class="item-display">
                                {{!empty($order->weight)?$order->weight: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Comment</label>
                            <p class="item-display">
                                {{!empty($order->comments) ? $order->comments: 'N/A'}}
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Carrier Name</label>
                            <p>{{!empty($order->carrierRelation) ? $order->carrierRelation->cuisines_name : 'N/A'}}</p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Parcel Type</label>
                            <p class="item-display">
                                {{!empty($order->parcelType) ? $order->parcelType->parcel_name: 'N/A'}}
                            </p>
                        </div> <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Created At</label>
                            <p class="item-display">
                                {{$order->created_at ? $order->created_at: 'N/A'}}
                            </p>
                        </div>

                    </div>

                @else
                    <p class="item-display">No data defined</p>
                @endif
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        var modal = document.getElementById("myModal");
        $(function () {
            $('.status-select').on('change',function (event) {
                let status =  document.getElementById("status_selected").value;
                if(status == 'Cancelled'){
                    modal.style.display = "block";
                }else{
                    document.getElementById("status").submit();
                }
            })
        });
        const modalInput = document.getElementById("modal-input");
        modalInput.onkeyup = () => {
            const buttonSend = document.getElementById("send");
            if(modalInput.value && modalInput.value.length){
                buttonSend.disabled = false;
            }else{
                buttonSend.disabled = true;
            }
            buttonSend.onclick = () => {
                let input = document.getElementById("modal-input");
                document.getElementById("status_text").value = input.value;
                document.getElementById("status").submit();
            }
        }

        let options = document.getElementsByClassName('status-crop');
        options = Array.from(options);
        options.map((option,index)=>{
            let text = option.innerHTML.trim();
            if (text.length>22){
                 text = text.substring(0,22) + "...";
                options[index].innerHTML=text;
            }
        });
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
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
    </style>
@endpush
