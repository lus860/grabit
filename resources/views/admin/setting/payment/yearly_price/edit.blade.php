@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/backend/yearly-price/edit')}}" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        @include('errors.error_layout')
            @if(isset($payment) && $payment->count())
            <div class="col-lg-5 col-md-8 form-wrapper">
                <div class="row form-row">
                        @if($payment->keyword == 'yearly_price')
                            <div class="form-group col-lg-5 col-md-5">
                                <label class="">Yearly Price</label>
                            </div>
                            <div class="form-group col-lg-7 col-md-7 text-center">
                                <input  name="yearly_price_description" type="text" class="form-control" value="{{$payment->description}}">
                                <input  name="yearly_price_id" type="hidden" class="form-control" value="{{$payment->id}}">
                            </div>
                        @endif
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{url('/backend/yearly-price')}}" type="submit" class="btn btn-primary">Go back</a>
                        </div>
                    </div>
                </div>
            </div>
            @else
        <div class="form-group col-lg-12 col-md-12">
            <button type="button" class="btn btn-primary add-blocklist my-5">Add Yearly price</button><br>
                <div class="clearfix"></div>
                <input type="text" name="yearly_price_description" class="form-control">
            <a href="{{url('/backend/yearly-price')}}" type="submit" class="btn btn-primary">Go back</a>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
        @endif
    </div>
    </form>
@endsection
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
