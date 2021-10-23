@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="message-for-upload hide">
        <div class="alert alert-success">
            File is uploaded
        </div>
    </div>
    <form action="/backend/web-images/create" enctype="multipart/form-data" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Add image</h3>
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Page</label>
                        <select class="form-control" name="page" id="">
                            <option value="" selected disabled>Select which page</option>
                            <option value="1">Home</option>
                            <option value="2">other</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Name</label>
                        <select class="form-control" name="name" id="">
                            <option value="" selected disabled>Select name which will be key</option>
                            <option value="1">Third layer</option>
                            <option value="2">Fifth layer</option>
                            <option value="3">other</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Description</label>
                        <input type="text" class="form-control" name="description" value="">
                    </div>

                    <div class="form-group col-lg-4 col-md-6">
                        <label>Image</label>
                        <input name="image" type="file" class="form-control image" />
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

