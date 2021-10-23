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
                            <label class="delivery_label">Customer Name</label>
                            <p>{{!empty($order->user_id)?$order->user->name: 'N/A'}}</p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Order From</label>
                            <p class="item-display">
                                {{!empty($order->order_from)?config('api.order.order_from')[$order->order_from]: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Vendor</label>
                            <p class="item-display">
                                {{!empty($order->vendor_id)?$order->get_vendor->name: 'N/A'}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Order Type</label>
                            <p class="item-display">
                                {{!empty($order->order_type)?config('api.order.order_type')[$order->order_type]: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Schedule</label>
                            <p class="item-display">
                                {{!empty($order->schedule)?config('api.order.schedule')[$order->schedule]: 'N/A'}}
                                <br>
                                @if(isset($order->schedule) && $order->schedule ==2)
                                    {{$order->schedule_time}}
                                @endif
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Price</label>
                            <p class="item-display">
                                {{!empty($order->order_total)?$order->order_total: 'N/A'}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Delivery fee</label>
                            <p class="item-display">
                                {{!empty($order->delivery_fee)?$order->delivery_fee: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Payment</label>
                            <p class="item-display">
                                {{!empty($order->payment)?config('api.order.payment')[$order->payment]['title']: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Rider Name</label>
                            <p class="item-display">
                                {{!empty($order->rider_name)?$order->rider_name: 'N/A'}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Created</label>
                            <p class="item-display">
                                {{$order->created_at}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="delivery_label">Dui in</label>
                            <p class="item-display">
                                {{!empty($order->due_in)?\App\Traits\TimeTrack::check($order->due_in)?"Already due":$order->due_in: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <form action="/backend/change-order-status" id="status" method="post">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <label class="delivery_label">Status</label>
                                <select class="form-control status-select"  name="status">
                                    @php $statuses = array_except(config('api.order.status'), array('status_300','status_301', 'status_302','status_304','pending')) @endphp
                                    @foreach(config('api.order.status') as $key=>$status)
                                        @if(!empty($order->status) && $key == $order->status)
                                            <option class="status-crop" value="{{$key}}" selected disabled>
                                                {{$status}}
                                            </option>
                                        @endif
                                        @if(($key == 'Cancelled' || $key== 'status_303') && (!empty($order->status) && $key != $order->status))
                                            <option class="status-crop" value="{{$key}}">
                                                {{$status}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label class="delivery_label">Cooking directions</label>
                            <p class="item-display">
                                {{!empty($order->cooking_directions)?$order->cooking_directions: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="delivery_label">Other notes</label>
                            <p class="item-display">
                                {{!empty($order->order_notes)?$order->order_notes: 'N/A'}}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label class="delivery_label">Vendor address</label>
                            <p class="item-display">
                                @if(!empty($order->get_vendor))
                                    {{isset($order->get_vendor->address1)?$order->get_vendor->address1.', ':''}}
                                    {{isset($order->get_vendor->address2)?$order->get_vendor->address2.', ':''}}
                                    {{isset($order->get_vendor->area)?$order->get_vendor->area->name.', ':''}}
                                    {{isset($order->get_vendor->city)?$order->get_vendor->city->name.'.':''}}
                                @else
                                    {{'N/A'}}
                                @endif
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="delivery_label">Delivery address</label>
                            <p class="item-display">
                                @if(isset($order->user->address))
                                    {{isset($order->user->address->address_type)?$order->user->address->address_type.', ':''}}
                                    {{isset($order->user->address->line_1)?$order->user->address->line_1.', ':''}}
                                    {{isset($order->user->address->line_2)?$order->user->address->line_2.', ':''}}
                                    {{isset($order->user->address->landmark)?$order->user->address->landmark.',':''}}
                                    {{isset($order->user->address->area)?$order->user->address->area->name.',':''}}
                                    {{isset($order->user->address->city)?$order->user->address->city->name.'.':''}}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label class="delivery_label">Transaction ID</label>
                            <p class="item-display">
                                {{!empty($order->transaction_id)?$order->transaction_id: 'N/A'}}
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label class="delivery_label">Amount to collect</label>
                            <p class="item-display">
                                {{\App\Services\OrderService::getOrderCollectionAmount($order,json_decode($order->action,true))}}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="item-display">No data defined</p>
                @endif
            </div>
        </div>
    </div>
    <div class="row" style="border: 1px solid black">
        @if(isset($menus) && count($menus))
            @foreach($menus as $key=>$menu)
                <div class="col-lg-12 col-md-12 ">
                    <div class="row form-row">
                        @if($menu['category_name'])
                            <div class="form-group col-lg-2 col-md-4">
                                <label class="delivery_label">Category name</label>
                                <p>{{$menu['category_name']}}</p>
                            </div>
                        @endif
                        @if($menu['item_name'])
                            <div class="form-group col-lg-2 col-md-4">
                                <label class="delivery_label">Item name</label>
                                <p>{{$menu['item_name']}}</p>
                            </div>
                        @endif
                        @if(isset($menu['add_ons']) && is_array($menu['add_ons']))
                            <div class="form-group col-lg-2 col-md-4">
                            <label class="delivery_label">Add one</label>
                            @foreach($menu['add_ons'] as $add_on)
                                <p>*{{$add_on}}</p>
                            @endforeach
                            </div>
                        @endif
                        @if(isset($menu['variants']) && is_array($menu['variants']))
                            <div class="form-group col-lg-2 col-md-4">
                            <label class="delivery_label">Variants</label>
                            @foreach($menu['variants'] as $variant)
                                <p>*{{$variant}}</p>
                            @endforeach
                            </div>
                        @endif
                        @if($menu['quantity'])
                            <div class="form-group col-lg-1 col-md-4">
                                <label class="delivery_label">Quantity</label>
                                <p>{{$menu['quantity']}}</p>
                            </div>
                        @endif
                        @if($menu['price'])
                            <div class="form-group col-lg-1 col-md-4">
                                <label class="delivery_label">Price</label>
                                <p>{{$menu['price']}}</p>
                            </div>
                        @endif
                        @if($key === 'price')
                            <div class="form-group col-lg-11 col-md-4 text-right">
                                <label class="delivery_label">Total Price</label>
                                <p>{{(isset($order->delivery_fee))?$order->delivery_fee+$menu:$menu}}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
@push('js')
    <script>
        $(function () {
            $('.status-select').on('change',function () {
                $('#status').submit();
            })
        })
        let options = document.getElementsByClassName('status-crop');
        options = Array.from(options)
        options.map((option,index)=>{
            let text = option.innerHTML.trim();
            if (text.length>22){
                 text = text.substring(0,22) + "...";
                options[index].innerHTML=text;
            }
        });
    </script>
@endpush
