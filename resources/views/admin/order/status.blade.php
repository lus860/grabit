@extends('admin.backend.tblTemplate')
@section('title','Order status')
@section('body')
<style>
    .delivery-list{
        list-style: none;
        margin: 0;
        padding: 30px 0;
    }
    .delivery-list li{
        height: 60px;
    }
    .delivery-list li h4{
        margin: 0;
        padding: 0 0 10px;
        font-size: 16px;
    }
    .delivery-list li h5{
        margin: 0;
        padding: 0 0 10px;
        font-size: 11px;
    }
    .clear{
        clear: both;
    }
    .delivery-list .status-icon{
        float: left;
        width: 48px;
        height: 48px;
        border-radius: 72px;
        text-align: center;
    }
    .delivery-list .status-icon i{
        font-size: 24px;
        line-height: 48px;
    }
    .delivery-list .status-content{
        float: left;
        margin-left: 20px;
        padding-top: 6px;
    }
</style>

    <form action="{{url('/')}}/backend/order-status" method="post" id="statusForm">
        <input type="hidden" name="_token" value="{{@csrf_token()}}" />
        <input type="hidden" name="order_id" value="{{$order['id']}}" />

        <div class="form-group">
            <label for="status">Current status: #{{$order['id']}}</label>
            <div class="form-control-">
                <ul class="delivery-list">
                @php


                        foreach($order['statuses'] as $stat){

                            if($stat['status'] == "New"){
                                $iconName =  "shopping-cart";
                            }elseif($stat['status'] == "Dispatched"){
                                $iconName =  "circle";
                            }elseif($stat['status'] == "Transit"){
                                 $iconName = "truck";
                            }elseif($stat['status'] == "Delivered"){
                                 $iconName = "thumbs-up";
                             }else{
                             $iconName = "shopping-cart";
                             }

                             if($stat['status'] == "New"){
                                $orderStatus =  "Order Placed";
                            }elseif($stat['status'] == "Dispatched"){
                                $orderStatus =  "Order Dispatched";
                            }elseif($stat['status'] == "Transit"){
                                 $orderStatus = "Order In Transit";
                            }elseif($stat['status'] == "Delivered"){
                                 $orderStatus = "Delivery Successfully";
                             }else{
                             $orderStatus = "Unknown status";
                             }

                              if($stat['status'] == "New"){
                                $statusColor =  '#260894';
                            }elseif($stat['status'] == "Dispatched"){
                                $statusColor =  '#260894';
                            }elseif($stat['status'] == "Transit"){
                                 $statusColor = '#260894';
                            }elseif($stat['status'] == "Delivered"){
                                 $statusColor = '#260894';
                             }else{
                                $statusColor = "#333";
                             }

                            if($stat['status'] == 'Transit' && $order['status'] != 'Delivered'){
                                    echo '<li><div class="status-icon" style="border: 1px solid '.$statusColor.';"><i style="color: '.$statusColor.'" class="fa fa-'.$iconName.'"></i></div>
                                        <div class="status-content">
                                            <h4 style="color: '.$statusColor.'"><a style="color: '.$statusColor.'" href="">'.$orderStatus.' [<strong>Track Delivery</strong>]</a></h4>
                                            <h5 style="color: '.$statusColor.'">'.$stat['created_at'].'</h5>
                                        </div>
                                        <div class="clear"></div>
                                    </li>';
                            }else{
                                echo '<li><div class="status-icon" style="border: 1px solid '.$statusColor.';"><i style="color: '.$statusColor.'" class="fa fa-'.$iconName.'"></i></div>
                                        <div class="status-content">
                                            <h4 style="color: '.$statusColor.'">'.$orderStatus.'</h4>
                                            <h5 style="color: '.$statusColor.'">'.$stat['created_at'].'</h5>
                                        </div>
                                        <div class="clear"></div>
                                    </li>';
                            }
                        }
                @endphp
                </ul>

            </div>
        </div>
        <div class="form-group">
            <label for="status">Next status is: <strong>{{$next_status}}</strong></label>
            <input type="hidden" name="status" value="{{$next_status}}" />
            {{--<select class="form-control" name="status" required id="changeStatus">
                <option value="{{$next_status}}">{{$next_status}}</option>
                @foreach($statuses as $key=>$val)
                    @if(!in_array($val, $status_array))
                        <option value="{{$val}}">{{$val}}</option>
                    @endif
                @endforeach
            </select>--}}
        </div>
        @if($next_status == 'Transit')
        <div class="form-group">
            <label for="status">Delivery Provider</label>
            <select class="form-control" name="provider" id="provider" required >
                <option value="">Select delivery provider</option>
                @foreach($providers as $provider)
                    <option value="{{$provider['id']}}">{{$provider['method']}}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if($next_status != '')
        <div class="form-group">
            <input type="submit" value="{{$button_label}}" class="form-control btn btn-primary" style="width: 120px" />
        </div>
        @endif
    </form>

    <script type="text/javascript">
        $(function(){
            /*$("#changeStatus").change(function () {
               let value = $(this).val();
               if(value === 'Transit'){
                   $("#driverList").fadeIn().find('select').removeAttr('disabled');
               }
            });*/

            /*$("#statusForm").submit(function(){
                let status = $("#changeStatus").val(),
                    driver_id = $("#driver").val();
                if(status === 'Transit' && driver_id !== ''){
                    return true;
                }else{
                    return false;
                }
            });*/

        });
    </script>

@stop
