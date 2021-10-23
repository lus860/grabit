@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/menu-categories/create')}}" class="btn btn-success">Add Category</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th width="50">ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Icon</th>
            <th colspan="1" width="150">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($categories)>0)
            @foreach ($categories as $category)
                <tr class="bg-info">
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>
                    <td>
                        @if($category->image != null)
                            <img src="{{$category->image}}" alt="{{$category->name}}" style="width: 50px;" />
                        @endif
                    </td>
                    <td>
                        @if($category->icon != null)
                            <img src="{{$category->icon}}" alt="{{$category->icon}}" style="width: 50px;" />
                        @endif
                    </td>
                    <td>
                        <a href="{{route('menu-categories.edit',$category->id)}}" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                        {{--<a href="{{route('cuisines.show',$category->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>--}}

                        <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['menu-categories.destroy', $category->id]]) !!}
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
            {{$categories->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
