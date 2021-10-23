@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/edit-category/{{$category->id}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Information</h3>
            <div class="row">
                <div class="form-group col-lg-6 col-md-6">
                    <label>Edit Name</label>
                    <input name="name" type="text" class="form-control" value='{{$category->name}}' @if(old('name'))value="{{old('name')}}"@endif/>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-md-6">
                    <label>Edit Image</label>
                    <input  accept=".png" name="image" type="file" class="form-control banner_image" value='{{$category->image}}'/>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
