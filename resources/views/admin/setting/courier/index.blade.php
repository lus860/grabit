@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <a href="{{route('parcel-type.index')}}" class="btn btn-primary"><i class="fa fa-angle-double-right"></i> To Parcel</a>
    <a href="{{route('carrier.index')}}" class="btn btn-primary"><i class="fa fa-angle-double-right"></i> To Carrier</a>
    <br>
    <br>
    <div class="ssj-form-wrapper">

        <div class="col-lg-5 col-md-5 form-wrapper">

                <div class="row form-row">
                @if(isset($courier) && $courier->count())
                    <a href="{{url('/backend/courier-settings')}}/edit" class="btn btn-primary edit-button">Edit</a>
                    @foreach($courier as $item)

                    @if($item->keyword =='base_fare')
                    <div class="form-group col-lg-9 col-md-9 text-center">
                        <label>Base Fire</label>
                        <p class="item-display">{{$item->description}}</p>
                    </div>
                    @endif
                        @if($item->keyword =='km_price')
                    <div class="form-group col-lg-9 col-md-9 text-center">
                        <label>Price Per Km</label>
                        <p class="item-display">{{$item->description}}</p>
                    </div>
                    @endif
                        @if($item->keyword =='minimum_fare')
                            <div class="form-group col-lg-9 col-md-9 text-center">
                                <label>Minimum Fare</label>
                                <p class="item-display">{{$item->description ? $item->description : 1}}</p>
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
