@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/manage/riders/add')}}" class="btn btn-primary">Add rider</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Rider name</th>
            <th>Plate number</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Username</th>
            <th colspan="1" style="width:150px">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->plate}}</td>
                <td>{{ $user->phone}}</td>
                <td>{{ $user->status?'Online':'Offline' }}</td>
                <td>{{ $user->username}}</td>
                <td>
                    <a href="{{url('/backend/manage/riders/edit/'.$user->id)}}" class="btn btn-warning"><i class="fa fa-pencil"></i> </a>
{{--                    <a href="{{url('/backend/manage/riders/show/'.$user->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>--}}
                    <div style="display: inline-block; margin-left: 5px;">
                        <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$user->id}}"><i class="fa fa-trash-o"></i></button>
                    </div>
                    <div class="modal fade" id="modal_{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                        <div class="modal-dialog modal-m modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 10px;">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h3 class="modal-title" id="myModalLabel">Did you want Delete Rider?</h3>
                                </div>
                                <div class="modal-footer">
                                    {!! Form::open(['method' => 'DELETE','class'=>'form-accept-modal' ,'route'=>['rider-destroy','rider'=>$user->id]]) !!}
                                    <button type="submit" class="btn btn-danger ">Yes</button>
                                    {!! Form::close() !!}
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
