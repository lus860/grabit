@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/cuisines/create')}}" class="btn btn-primary">Add Cuisine</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th colspan="1" width="50">ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Is Top</th>

            <th colspan="1" width="150">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($cuisines)>0)
            @foreach ($cuisines as $cuisine)
                <tr class="bg-info">
                    <td>{{$cuisine->id}}</td>

                    <td>{{$cuisine->name}}</td>
                    <td>
                        @if($cuisine->image != null)
                            <img src="{{$cuisine->image}}" alt="{{$cuisine->name}}" style="width: 50px;" />
                        @endif
                    </td>
                    <td>@if($cuisine->is_top == 1)
                            <span class="is-top"><i class="fa fa-check" aria-hidden="true"></i></span>
                        @else <span class="not-top"><i class="fa fa-times" aria-hidden="true"></i></span> @endif </td>

                    <td>
                        <a href="{{route('cuisines.edit',$cuisine->id)}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        {{--<a href="{{route('cuisines.show',$cuisine->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>--}}

                        <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['cuisines.destroy', $cuisine->id]]) !!}
                            <button type="submit" class= 'btn btn-danger' style="background: red !important;"><i class="fa fa-trash-o"></i></button>
                            {!! Form::close() !!}</div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No cuisine defined.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$cuisines->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/custom.css')}}" >
@endpush
