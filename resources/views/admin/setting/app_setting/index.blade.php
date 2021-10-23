@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <a href="{{url('/backend/app-settings/show-black-list')}}" class="btn btn-primary " style="margin-bottom: 10px">Show Block list</a>

    <div class="ssj-form-wrapper">

        <div class="col-lg-5 col-md-5 form-wrapper">
            <div class="row form-row">
                @if(isset($app_settings) && $app_settings->count())
                    <a href="{{url('/backend/app-settings/edit')}}" class="btn btn-primary edit-button">Edit</a>

                @foreach($app_settings as $item)
                        @if($item->keyword =='min_ios')
                            <div class="form-group col-lg-9 col-md-9 text-center">
                                <label>Minimum iOS App Version</label>
                                <p class="item-display">{{$item->description}}</p>
                            </div>
                        @endif
                        @if($item->keyword =='min_android')
                            <div class="form-group col-lg-9 col-md-9 text-center">
                                <label>Minimum Android App Version</label>
                                <p class="item-display">{{$item->description}}</p>
                            </div>
                        @endif
                        @if($item->keyword =='maintenance_mode')
                            <div class="form-group col-lg-9 col-md-9 text-center">
                                <label>Maintenance Mode</label>
                                <p class="item-display">{{$item->description?'true':'false'}}</p>
                            </div>
                        @endif

                    @endforeach
                @else
                    <p class="item-display">No data defined</p>
                    <a href="{{url('/backend/app-settings/edit')}}" class="btn btn-primary edit-button">Add</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
