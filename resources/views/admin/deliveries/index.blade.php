@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="ssj-form-wrapper">
        <div class="col-lg-5 col-md-5 form-wrapper">
                <div class="row form-row">
                @if(isset($delivery) && $delivery->count())
                    <a href="{{url('/backend/deliveries')}}/edit" class="btn btn-primary edit-button">Edit</a>
                    @foreach($delivery as $item)
                    @if($item->keyword =='under')
                    <div class="form-group col-lg-3 col-md-3">
                        <label class="delivery_label">{!! $item->description !!}</label>
                    </div>
                    @endif
                    @if($item->keyword =='under_price')
                    <div class="form-group col-lg-9 col-md-9 text-center">
                        <label>Price</label>
                        <p class="item-display">{{$item->description}}</p>
                    </div>
                    @endif
                    @if($item->keyword =='above')
                    <div class="form-group col-lg-3 col-md-3">
                        <label class="delivery_label">{!! $item->description !!}</label>
                    </div>
                    @endif
                    @if($item->keyword =='above_price')
                    <div class="form-group col-lg-9 col-md-9 text-center">
                        <label>Price</label>
                        <p class="item-display">{{$item->description}}</p>
                    </div>
                    @endif
                    @if($item->keyword =='delivery_time')
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Delivery time in minutes</label>
                        <p class="item-display">{{$item->description}}</p>
                    </div>
                    @endif
                @endforeach
                @else
                    <p class="item-display">No data defined</p>
                @endif
                </div>
        </div>
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
