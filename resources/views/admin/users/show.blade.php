@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    <h3>User Detail</h3>

    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Total Orders</th>
            <th>Highest Order Value</th>
            <th>Average Order Value</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email}}</td>
                <td>{{ \App\SSJUtils::add255($user->phone)}}</td>
                <td align="right">{{ count($user['orders'])}}</td>
                <td align="right">{{ number_format($user['stats']['highest_order_value'], 2)}}</td>
                <td align="right">{{ number_format($user['stats']['average_order_value'], 2)}}</td>
            </tr>
        </tbody>
    </table>
    <div style="display: none">
        <div class="row">
        <div class="col-lg-6 col-md-6">
            <p class="col-lg-4">Customer name</p>
            <p class="col-lg-8">{{ $user->name }}</p>
        </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
            <p class="col-lg-4">Mobile number</p>
            <p class="col-lg-8">{{ $user->phone}}</p>
        </div>
        </div>
        <div class="row"><div class="col-lg-6 col-md-6">
            <p class="col-lg-4">Email</p>
            <p class="col-lg-8">{{ $user->email}}</p>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
            <p class="col-lg-4">Total Orders</p>
            <p class="col-lg-8">{{ count($user['orders'])}}</p>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
            <p class="col-lg-4">Highest order value</p>
            <p class="col-lg-8">{{ $user['stats']['highest_order_value']}}</p>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
            <p class="col-lg-4">Average order value</p>
            <p class="col-lg-8">{{ $user['stats']['average_order_value']}}</p>
        </div>
        </div>

    </div>

    <h3>User Orders</h3>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Date</th>
            <th>Source</th>
            <th>Restaurant</th>
            <th>Order type</th>
            <th>Order total</th>
            <th>Instructions</th>
            <th>Delivery Note</th>
            <th>Payment Method</th>
            <th>Rider</th>
            <th>Food Rating</th>
            <th>Delivery Rating</th>
            <th>Feedback</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user['orders'] as $order)
        <tr>
            <td>{{$order['date']}}</td>
            <td>{{$order['source'] !== null? $order['source']:'-'}}</td>
            <td>{{$order['restaurant'] !== null?$order['restaurant']:'-'}}</td>
            <td>{{$order['type'] !== null?$order['type']:'-'}}</td>
            <td align="right">{{number_format($order['amount'], 2)}}</td>
            <td>{{$order['instruction'] !== null?$order['instruction']:'-'}}</td>
            <td>{{$order['notes'] !== null?$order['notes']:'-'}}</td>
            <td>{{$order['payment'][0]->method}}</td>
            <td>{{$order['rider'] !== null?$order['rider']:'-'}}</td>
            <td>{{$order['food_rating'] !== null?$order['food_rating']:'-'}}</td>
            <td>{{$order['delivery_rating'] !== null? $order['delivery_rating']: '-'}}</td>
            <td>{{$order['feedback'] !== null?$order['feedback']:'-'}}</td>
            <td>{{$order['status']}}</td>
        </tr>
            @endforeach
        </tbody>
    </table>

    <div style=" margin-top: 35px;">{!! Form::open(['method' => 'DELETE', 'route'=>['users.destroy', $user->id]]) !!}
        <a style="" href="{{route('users.index')}}" class="btn btn-success ssj-back-button"><i class="fa fa-arrow-left"></i> Back to users</a>
        <button type="submit" class= 'btn btn-danger'><i class="fa fa-trash-o"></i> Delete user</button>
        {!! Form::close() !!}</div>

@stop
