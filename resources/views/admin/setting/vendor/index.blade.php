@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    @include('errors.error_layout')

    <a href="{{url('backend/vendor-type/create')}}" class="btn btn-primary">Add vendor type</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th >ID</th>
            <th class="text-right">Name</th>
            <th class="text-right">Notification messages</th>
            <th colspan="1" class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($vendor)>0)
            @foreach ($vendor as $value)
                <tr class="bg-info">
                    <td >{{$value->id}}</td>
                    <td class="text-right" >{{$value->vendor_name}}</td>
                    <td class="text-right" >
                        <a href="{{url('backend/notification/user/'.strtolower($value->vendor_name))}}" class="btn btn-primary">User</a>
                        <a href="{{url('backend/notification/vendor/'.strtolower($value->vendor_name))}}" class="btn btn-primary">{{$value->vendor_name}}</a>
                    </td>
                    <td class="text-right">
                        <a href="{{ url('/backend/vendor-type/edit/'.$value->id) }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No data.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$vendor->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
