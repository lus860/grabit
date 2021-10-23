@extends('admin.backend.tblTemplate')
@section('title','Parcel')
@section('body')
    @include('messages.flash_message')
    {{--<form action="{{route('groups.update',$group->id)}}" enctype="multipart/form-data" method="patch">--}}

    {!! Form::model($data,['method' => 'PATCH','route'=>['carrier.update',$data->id]]) !!}
    @csrf
    <div class="ssj-form-wrapper">
        <div class="col-lg-7 col-md-7 form-wrapper">
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Carrier Name</label>
                        <input  value="{{old('carrier_name',$data->carrier_name)}}" required name="carrier_name" type="text" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Price per km</label>
                        <input value="{{ old('km_price',$data->km_price) }}"  required name="km_price" type="number" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Base fare</label>
                        <input value="{{old('base_fare',$data->base_fare)}}"  required name="base_fare" type="number" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Minimum fare</label>
                        <input value="{{old('minimum_fare',$data->minimum_fare)}}"  required name="minimum_fare" type="number" class="form-control" />
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Carrier Status</label>
                        <input @if($data->carrier_status) checked @endif  name="carrier_status" type="checkbox" />
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
