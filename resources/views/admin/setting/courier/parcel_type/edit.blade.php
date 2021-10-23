@extends('admin.backend.tblTemplate')
@section('title','Parcel')
@section('body')
    @include('messages.flash_message')
    {{--<form action="{{route('groups.update',$group->id)}}" enctype="multipart/form-data" method="patch">--}}

        {!! Form::model($data,['method' => 'PATCH','route'=>['parcel-type.update',$data->id]]) !!}
        @csrf
        <div class="ssj-form-wrapper">
            @if($errors->any())
                <h3 style="color: red;">Error: </h3>
                <p style="color: red;">{{$errors->first()}}</p>
            @endif
            <div class="col-lg-7 col-md-7 form-wrapper">
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Parcel Name</label>
                            <input  value="{{$data->parcel_name}}" required name="parcel_name" type="text" class="form-control" />
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Parcel Status</label>
                            <input @if($data->parcel_status  == 'true') checked @endif
                                   id="parcel_status" name="parcel_status" type="checkbox" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
    @include('errors.error_layout')
@stop
