@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
        {!! Form::open(['method' => 'POST','route'=>['web-image-update',$image->id],'enctype'=>'multipart/form-data']) !!}
        @csrf
        <div class="ssj-form-wrapper">
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Web image</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Page</label>
                            <select class="form-control" name="page" id="">
                                <option value="" selected disabled>Select which page</option>
                            @foreach(config('web_images.names.pages') as $key=>$value)
                                    <option value="{{$key}}" @if($key == $image->page) selected @endif>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Name</label>
                            <select class="form-control" name="name" id="">
                                <option value="" selected disabled>Select name which will be key</option>
                                @foreach(config('web_images.names.names') as $key=>$value)
                                    <option value="{{$key}}" @if($key == $image->name) selected @endif>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Description</label>
                            <input type="text" class="form-control" name="description" value="{{old('description',$image->description)}}">
                        </div>

                        <div class="form-group col-lg-4 col-md-6">
                            <label>Image</label>
                            <input name="image" type="file" class="form-control image" />
                        </div>
                        @if($image->image != null)
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Current Image</label>
                                <img src="{{$image->image}}" width="120" />
                            </div>
                        @endif
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

@endsection

@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
   <style type="text/css">
        img {
            display: block;
            max-width: 90%;
        }
        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg{
            max-width: 1000px !important;
        }
    </style>
@endpush
