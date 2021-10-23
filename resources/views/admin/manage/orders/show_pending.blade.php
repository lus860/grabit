@extends('admin.backend.tblTemplate')
@section('title',$title)
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
    {{--    <script type="text/javascript">let centreGot = false;</script>--}}
    {{--    {!! $map['js'] !!}--}}
@endpush
@section('body')
    <div class="row form-wrapper">
        <input type="hidden" class="order-transaction" value="{{$order->transaction_id}}">
        <div class="col-xs-12 col-md-5 " style="border: 1px solid #c8ccd0">
            <h4 class="manage-order-title">Order Details</h4>
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Type
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{config('api.order.order_type')[$order->order_type]}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        From
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{$order->get_vendor->name.', '.$order->get_vendor->address1.', '.$order->get_vendor->address2.', '.$order->get_vendor->area->name}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        To
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{$order->address->line_1.', '.$order->address->line_2.', '.$order->address->landmark.', '.$order->address->area->name.', '.$order->address->city->name}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Delivery Price
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{($order->discounted_price?$order->discounted_price:$order->price)+($order->delivery_fee?$order->delivery_fee:0)}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Payment
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{(config('api.order.payment')[$order->payment]['payment_status'])}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Notes
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{$order->order_notes}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Status
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <select name="status" class="order-status-select form-control" id="" @if(!$order->rider_name) disabled @endif>
                        <option value="" selected disabled>Change Status</option>
                        @php $statuses = Arr::except(config('api.order.status'), array('waiting', 'dispatch','Cancelled','accepted','pending','status_300')) @endphp
                        @foreach($statuses as $key=>$status)
                            @if(!empty($order->status) && $key == $order->status)
                                <option class="order-status-option" value="{{$key}}" selected>
                                    {{$status}}
                                </option>
                            @else
                                <option class="status-crop" value="{{$key}}">
                                    {{$status}}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Date
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{$order->created_at}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Customer name
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{$order->user->name}}
                    </p>
                </div>
            </div>
            <hr class="my-hr">
            <div class="row">
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-title">
                        Customer phone
                    </p>
                </div>
                <div class="col-xs-12 col-md-6 text-left">
                    <p class="manage-order-body">
                        {{$order->user->phone?:'N/A'}}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-1">
            <div class="clearfix"></div>
        </div>
        <div class="col-xs-12 col-md-5 offset-md-1" style="border: 1px solid #c8ccd0">
            <h4>Riders</h4>
            @foreach($riders as $rider)
                <div class="row">
                    <div class="col-xs-12 col-md-4 text-left my-p-1">
                        <p>
                            {{$rider->name}}
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-4 text-right my-p-1">
                        <p>
                            <span class="@if($rider->status)manage-order-status-online @else manage-order-status-offline @endif">{{$rider->status?'ONLINE':'OFFLINE'}}</span>
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-4 text-right">
                        <p>
                            @if($rider->id == $order->rider_name)
                                <button @if(!$rider->status) disabled @endif class="btn btn-success selected-button manage-rider-button" data-action="{{$rider->id}}">
                                    <i class="fa fa-check"></i>Selected
                                </button>
                            @else
                                <button @if(!$rider->status) disabled @endif class="btn btn-primary manage-rider-button" data-action="{{$rider->id}}">
                                    Select
                                </button>
                            @endif
                        </p>
                    </div>
                </div>
                <hr style="border: 1px solid #eee">
            @endforeach
            <div class="clearfix"></div>

            <button class="btn btn-primary dispatch-button text-right my-mb-15" @if(!$order->rider_name) disabled @endif>
                Dispatch
            </button>
        </div>
    </div>
    @include('admin/manage/modal_messages')
@endsection
@push('js')
    <script>
        $(function () {
            $('.order-status-select').on('change', function () {
                if($('.selected-button').length){
                    let select = $(this).val();
                    let tran_id = $('.order-transaction').val();
                    let rider = $('.selected-button').data('action');
                    sendAjax('/backend/manage/order/change-order-status', {select, tran_id, rider:rider});
                }

            });
            $('.dispatch-button').on('click', function () {
                if($('.selected-button').length){
                    let rider = $('.selected-button').data('action');
                    let tran_id = $('.order-transaction').val();
                    sendAjax('/backend/manage/order/change-order-rider', {rider:rider, tran_id, status:'status_301'});
                    setTimeout(function () {
                        window.location = '/backend/manage/orders';
                    },1000)
                }
            });
            $('.manage-rider-button').on('click',function () {
                let rider = $(this).data('action');
                let tran_id = $('.order-transaction').val();
                if($(this).hasClass('selected-button')){
                    $(this).removeClass('btn-success').addClass('btn-primary')
                    $(this).removeClass('selected-button');
                    $(this)[0].innerHTML='Select';
                    // sendAjax('/backend/manage/order/change-order-rider', {rider:0 , tran_id});
                    checkSelectedRider();
                    checkSelectedRiderForSelect();
                    return 0;
                }
                if($('.selected-button').length){
                    $('.selected-button').removeClass('btn-success');
                    $('.selected-button').addClass('btn-primary');
                    $('.selected-button')[0].innerHTML='Select';
                    $('.selected-button').removeClass('selected-button');
                }
                $(this).removeClass('btn-primary').addClass('btn-success selected-button')
                $(this)[0].innerHTML='<i class="fa fa-check"></i>  Selected';
                // sendAjax('/backend/manage/order/change-order-rider', {rider:rider, tran_id});
                checkSelectedRider();
                checkSelectedRiderForSelect();
            })

            let sendAjax = (url, data) => {
                    let token = $('input[name=_token]').val();
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: {data, data, _token: token}
                    }).done(function (response) {
                        $('#status-is-changed').modal('toggle');
                        $(`.order-status-select option[value=${data.status}]`).prop('selected',true);
                    }).catch(function (response) {
                        $('#something-is-wrong').modal('toggle');
                        // setTimeout(function () {
                        //     $('#something-is-wrong').modal('toggle');
                        // }, 2000)
                    })
                },
                checkSelectedRider=()=>{
                    if($('.selected-button').length){
                        $('.dispatch-button').attr('disabled',false);
                    }else{
                        $('.dispatch-button').attr('disabled','disabled');
                    }
                };
            checkSelectedRiderForSelect=()=>{
                if($('.selected-button').length){
                    $('.order-status-select').attr('disabled',false);
                }else{
                    $('.order-status-select').attr('disabled','disabled');
                }
            };

        })
    </script>
@endpush
