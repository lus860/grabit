@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/admin/add-phone-numbers')}}" class="btn btn-primary">Phones for Notification</a>
    <a href="{{url('backend/admin/add-emails')}}" class="btn btn-primary">Emails for Notification</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Total Orders</th>
            <th>Origin</th>
            <th>Source App</th>
            <th>Created at</th>
            <th>Active</th>
            <th colspan="1" style="width:150px">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email}}</td>
                <td>{{ $user->phone}}</td>
                <td>{{ $user['orders']}}</td>
                <td>{{ $user->origin == 1?'WEB':$user->origin == 2?'ANDROID':'IOS'}}</td>
                <td>{{ $user->source_app}}</td>
                <td>{{ \App\Models\SSJUtils::FormatDate($user->created_at)}}</td>
                {{--<td>{{implode(",", $user->role->pluck("slug")->all())}}</td>--}}
                <td>{{ $user->is_activated == 1 ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{route('users.edit',$user->id)}}" class="btn btn-warning"><i class="fa fa-pencil"></i> </a>
                    <a href="{{route('users.show',$user->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>

                    <div style="display: inline-block; margin-left: 5px;">{!! Form::open(['method' => 'DELETE', 'route'=>['users.destroy', $user->id]]) !!}
                        <button type="submit" class= 'btn btn-danger'><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}</div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        <nav>
            {!! $users->links() !!}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
