@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/areas/create')}}" class="btn btn-primary">Add Area</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Area Name</th>
            <th>City Name</th>
            <th>Country Type</th>
            <th colspan="1" width="150">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($areas)>0)
            @foreach ($areas as $area)
                <tr class="bg-info">
                    <td>{{$area->id}}</td>
                    <td>{{$area->name}}</td>
                    <td>{{$area->city->name}}</td>
                    <td>{{$area->city->country->name}}</td>
                    <td>
                        <a href="{{route('areas.edit',$area->id)}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        <a href="{{route('areas.show',$area->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                        <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['areas.destroy', $area->id]]) !!}
                            <button type="submit" class= 'btn btn-danger' style="background: red !important;"><i class="fa fa-trash-o"></i></button>
                            {!! Form::close() !!}</div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No area defined.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$areas->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
