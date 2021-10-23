@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
        {!! Form::model($category,['method' => 'PATCH','route'=>['menu-categories.update',$category->id], 'enctype'=>'multipart/form-data']) !!}
        @csrf
        <div class="ssj-form-wrapper">
            @if($errors->any())
                <h3 style="color: red;">Error: </h3>
                <p style="color: red;">{{$errors->first()}}</p>
            @endif
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Cuisine Detail</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Cuisine Name</label>
                            <input placeholder="Eg. Choice of bread" value="{{$category->name}}" required name="name" type="text" class="form-control" />
                        </div>

                        <div class="form-group col-lg-6 col-md-6">
                            <label>Image</label>
                            <input @if(!isset($category)) required @endif name="image" type="file" class="form-control" />
                        </div>

                        <div class="form-group col-lg-6 col-md-6">
                            <label>Icon</label>
                            <input @if(!isset($category)) required @endif name="icon" type="file" class="form-control" />
                        </div>

                        @if($category->image != null)
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Current Image</label>
                            <img src="{{$cuisine->image}}" width="120" />
                        </div>
                        @endif
                        @if($category->image != null)
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Current Icon</label>
                            <img src="{{$category->icon}}" width="120" />
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
                    </div>
                </div>
            </div>
        </div>

    {!! Form::close() !!}
    @include('errors.error_layout')
@stop
