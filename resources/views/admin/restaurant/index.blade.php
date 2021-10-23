@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/vendors/create')}}" class="btn btn-primary">Add vendor</a>
    <hr>
    <div style="overflow-x: scroll;font-size: 11px">
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Name</th>
            <th>Vendor type</th>
            <th>POC Name</th>
            <th>POC Phone</th>
            {{--<th>Contact Name</th>
            <th>Email</th>--}}
            <th>Total Orders</th>
            <th>Country</th>
            <th>Area</th>
            <th>Delivery Commission</th>
            <th>Collection Commission</th>
            <th>Dine in Commission</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Created at</th>
            <th colspan="1" style="width: 150px">Actions</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($restaurants as $restaurant)
                <tr class="bg-info">
                    <td>{{$restaurant->id}}</td>
                    <td>{{$restaurant->name}}</td>
                    <td>{{$restaurant->vendor_type?$restaurant->vendor_type->vendor_name:'N/A'}}</td>
                    <td>{{$restaurant->contact_name}}</td>
                    <td>{{$restaurant->phone}}</td>
                    {{--<td>Contact Name</dh>
                    <td>Email</td>--}}
                    <td>0</td>
                    <td>{{$restaurant->country->name}}</td>
                    <td>{{$restaurant->area->name}}</td>
                    <td>{{$restaurant->delivery_commission}}%</td>
                    <td>{{$restaurant->collection_commission}}%</td>
                    <td>{{$restaurant->dine_commission}}%</td>
                    <td>{{$restaurant->average_rating !== null?$restaurant->average_rating:'0'}} stars</td>
                    <td>{{$restaurant->getStatus()}}</td>
                    <td>{{\App\Models\SSJUtils::FormatDate($restaurant->created_at)}}</td>
                    <td>
                        <a href="{{url('/backend/menu')}}?vendor_id={{$restaurant->id}}" class="btn btn-primary"><i class="fa fa-list"></i></a>
                        {{--<a href="{{route('restaurants.edit',$restaurant->id)}}" class="btn btn-warning"><i class="fa fa-pencil"></i> </a>--}}
                        <a href="{{route('vendors.show',$restaurant->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                        <a href="{{url('/backend/vendors/menu/'.$restaurant->id)}}" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                        <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['vendors.destroy', $restaurant->id]]) !!}
                            <button type="submit" class= 'btn btn-danger'><i class="fa fa-trash-o"></i></button>
                            {!! Form::close() !!}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div>
        <nav>
            {!! $restaurants->links() !!}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
