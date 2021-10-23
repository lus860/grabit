@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/edit-subcategory/{{$subcategory->id}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Information</h3>
            <div class="row">
                <div class="form-group col-lg-6 col-md-6">
                    <label>Add Name</label>
                    <input name="name" type="text" class="form-control" value="{{$subcategory->name}}" @if(old('name'))value="{{old('name')}}"@endif/>
                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <label>Add Category Name</label>
                    <select  name="category_id" id="country" class="form-control" >
                        <option selected disabled>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if($subcategory->category_id == $category->id) selected @endif>
                                {{$category->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
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


