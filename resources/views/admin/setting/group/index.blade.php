@extends('backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/groups/create')}}" class="btn btn-success">Add Group</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Group Name</th>
            <th>Group Type</th>
            <th>Max Selection</th>
            <th>Items</th>
            <th colspan="1" width="150">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($groups)>0)
            @foreach ($groups as $group)
                <tr class="bg-info">
                    <td>{{$group->id}}</td>
                    <td>{{$group->name}}</td>
                    <td>{{$group->getCtype()}}</td>
                    <td>{{$group->select_max}}</td>
                    <td>{{count($group->values)}}</td>
                    <td>
                        <a href="{{route('groups.edit',$group->id)}}" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                        <a href="{{route('groups.show',$group->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>

                        <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['groups.destroy', $group->id]]) !!}
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

        </nav>
    </div>
    <div class="row">
    </div>
@endsection