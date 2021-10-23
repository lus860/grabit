@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/backend/courier-settings/edit')}}" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        @include('errors.error_layout')
            @if(isset($courier) && $courier->count())
            <div class="col-lg-5 col-md-7 form-wrapper">
                <div class="row form-row">
                    @foreach($courier as $item)
                        @php
                            $km_price = ($item->keyword =='km_price') ? old('km_price')??$item->description:'';
                            $base_fare = ($item->keyword =='base_fare') ? old('base_fare')??$item->description:'';
                            $minimum_fare = ($item->keyword =='minimum_fare') ? old('minimum_fare')??$item->description:'';
                        @endphp
                        @if($item->keyword =='km_price')
                            <div class="form-group col-lg-4 col-md-4 ">
                                <label class="delivery-label-other">Price Per Km</label>
                            </div>
                        @endif
                        @if($item->keyword =='km_price')
                            <div class="form-group col-lg-8 col-md-8 text-center">
                                <label>Price</label>
                                <input  name="km_price" value="{{ $km_price}}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                        @if($item->keyword =='base_fare')
                            <div class="form-group col-lg-4 col-md-4">
                                <label class="delivery-label-other">Base Fire</label>
                            </div>
                        @endif
                        @if($item->keyword =='base_fare')
                            <div class="form-group col-lg-8 col-md-8 text-center">
                                <label>Base</label>
                                <input  name="base_fare" value="{{$base_fare}}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                        @if($item->keyword =='minimum_fare')
                            <div class="form-group col-lg-4 col-md-4 ">
                                <label class="delivery-label-other">Minimum Fare</label>
                            </div>
                        @endif
                        @if($item->keyword =='minimum_fare')
                            <div class="form-group col-lg-8 col-md-8 text-center">
                                <label>Fare</label>
                                <input  name="minimum_fare" value="{{$minimum_fare ? $minimum_fare : 1 }}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{url('/backend/courier-settings/')}}" type="submit" class="btn btn-primary">Go back</a>
                </div>
            </div>
        </div>
    </div>
    </form>
    @include('errors.error_layout')
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush


