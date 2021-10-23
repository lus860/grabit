@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
        {!! Form::open(['method' => 'POST','route'=>['vendor-update',$vendor->id],'files' => true]) !!}
        @csrf
        <div class="ssj-form-wrapper">
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Vendor type</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Vendor name</label>
                            <input placeholder="name" value="{{$vendor->vendor_name}}" name="vendor_name" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Image</label>
                            <input type="file" name="image" type="text" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="/backend/vendor-type" class="btn btn-primary">Go back</a>
                    </div>
                </div>
            </div>
        </div>

    {!! Form::close() !!}
    @include('errors.error_layout')
@endsection
