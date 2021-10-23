@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="message-for-upload hide">
        <div class="alert alert-success">
            File is uploaded
        </div>
    </div>
    <form action="{{url('/')}}/backend/vendor-type" enctype="multipart/form-data" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Vendor Type</h3>
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Name</label>
                        <input placeholder="name vendor name" value="" required name="vendor_name" type="text" class="form-control" />
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
