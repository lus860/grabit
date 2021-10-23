@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/cities/create')}}" class="btn btn-primary">Add City</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>City Name</th>
            <th>Country Type</th>
            <th colspan="1" width="150">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($cities)>0)
            @foreach ($cities as $city)
                <tr class="bg-info">
                    <td>{{$city->id}}</td>
                    <td>{{$city->name}}</td>
                    <td>{{$city->country->name}}</td>
                    <td>
                        <a href="{{route('cities.edit',$city->id)}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a href="{{route('cities.show',$city->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                        <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['cities.destroy', $city->id]]) !!}
                            <button type="submit" class= 'btn btn-danger' style="background: red !important;"><i class="fa fa-trash-o"></i></button>
                            {!! Form::close() !!}</div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No customization groups defined.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$cities->links()}}

        </nav>
    </div>
    <div class="row">
    </div>
@endsection
