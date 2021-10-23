@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/backend/deliveries/edit')}}" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        @include('errors.error_layout')
            @if(isset($delivery) && $delivery->count())
            <div class="col-lg-5 col-md-7 form-wrapper">
                <div class="row form-row">
                    @foreach($delivery as $item)
                        @php
                            $under_price = ($item->keyword =='under_price') ? old('under_price')??$item->description:'';
                            $above_price = ($item->keyword =='above_price') ? old('above_price')??$item->description:'';
                            $delivery_time = ($item->keyword =='delivery_time') ? old('delivery_time')??$item->description:'';
                        @endphp
                        @if($item->keyword =='under')
                            <div class="form-group col-lg-4 col-md-4 ">
                                <label class="delivery-label-other">{{$item->description}}</label>
                            </div>
                        @endif
                        @if($item->keyword =='under_price')
                            <div class="form-group col-lg-8 col-md-8 text-center">
                                <label>Price</label>
                                <input  name="under_price" value="{{$under_price}}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                        @if($item->keyword =='above')
                            <div class="form-group col-lg-4 col-md-4">
                                <label class="delivery-label-other">{{$item->description}}</label>
                            </div>
                        @endif
                        @if($item->keyword =='above_price')
                            <div class="form-group col-lg-8 col-md-8 text-center">
                                <label>Price</label>
                                <input  name="above_price" value="{{$above_price}}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                        @if($item->keyword =='delivery_time')
                            <div class="form-group col-lg-12 col-md-12">
                                <label>Delivery time in minutes</label>
                                <input min="5"  name="delivery_time" value="{{$delivery_time}}"
                                        type="number" step="5" class="form-control">
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
                    <a href="{{url('/backend/deliveries/')}}" type="submit" class="btn btn-primary">Go back</a>
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


