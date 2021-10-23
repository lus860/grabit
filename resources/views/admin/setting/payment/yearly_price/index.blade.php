@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="ssj-form-wrapper">

        <div class="col-lg-5 col-md-5 form-wrapper">
            <div class="row form-row">
                @if(isset($yearly_price) && $yearly_price->count())
                    <a href="{{url('/backend/yearly-price/edit')}}" class="btn btn-primary edit-button">Edit</a>

                @foreach($yearly_price as $item)
                        @if($item->keyword =='yearly_price')
                            <div class="form-group col-lg-9 col-md-9 text-center">
                                <label>Edit Yearly Price</label>
                                <p class="item-display">{{$item->description}}</p>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p class="item-display">No data defined</p>
                    <a href="{{url('/backend/yearly-price/edit')}}" class="btn btn-primary edit-button">Add</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
