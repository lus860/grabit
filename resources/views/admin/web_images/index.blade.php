@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    @include('errors.error_layout')

    <a href="{{url('backend/web-images/create')}}" class="btn btn-primary">Add image</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Page</th>
            <th>Name</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($images)>0)
            @foreach ($images as $value)
                <tr class="bg-info">
                    <td>{{$value->id}}</td>
                    <td>{{config('web_images.names.pages')[$value->page]}}</td>
                    <td>{{config('web_images.names.names')[$value->name]}}</td>
                    <td>
                        <img src="{{$value->image}}" alt="" width="15%">
                    </td>
                    <td >
                        <a href="{{ url('/backend/web-images/edit/'.$value->id) }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
            {{$images->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
