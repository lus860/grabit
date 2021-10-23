@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Transaction ID</th>
            <th>Rider name</th>
            <th>Carrier</th>
            <th>Name</th>
            <th>Pick up</th>
            <th>Delivery to</th>
            <th>Distance</th>
            <th>Price</th>
            <th>Payment method</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(count($orders)>0)
            @foreach ($orders as $key=>$order)
                <tr class="bg-info">
                    <td>{{!empty($order['transaction_id'])?$order['transaction_id']: 'N/A'}}</td>
                    <td>{{!empty($order['rider_name'])?$order['rider_name']: 'N/A'}}</td>
                    <td>{{!empty($order['carrier_name'])?$order['carrier_name']: 'N/A'}}</td>
                    <td>{{!empty($order['customer_name'])?$order['customer_name']: 'N/A'}}</td>
                    <td>{{!empty($order['pick_up'])?$order['pick_up']: 'N/A'}}</td>
                    <td>{{!empty($order['delivery'])?$order['delivery']: 'N/A'}}</td>
                    <td>{{!empty($order['distance'])?$order['distance']: 'N/A'}}</td>
                    <td>{{!empty($order['price'])?$order['price']: 'N/A'}}</td>
                    <td>{{!empty($order['payment'])?$order['payment']: 'N/A'}}</td>
                    <td>
                        @if(!empty($order['status']))
                            @if ($order['status'] == 'new')
                                <span class="p-2" style="background-color: green;padding: 5px;color: white">{{$order['status']}}</span>
                            @else
                                {{config('api.order.status_for_manage')[$order['status']]}}
                            @endif
                        @endif
                    </td>
                    <td>{{!empty($order['created_at'])?$order['created_at']: 'N/A'}}</td>
                    <td>
                        <a href="{{url('/backend/manage/orders/'.$order['transaction_id'])}}" class="btn btn-primary">
                            <i class="fa fa-truck"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No data available</td></tr>
        @endif
        </tbody>
    </table>
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
