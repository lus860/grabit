@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/carrier" enctype="multipart/form-data" method="post">
        @csrf
        <div class="ssj-form-wrapper">
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Carrier</h3>
                <div class="col-lg-12 col-md-12 form-wrapper">
                    <div class="row form-row">
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Carrier Name</label>
                                <input  required name="carrier_name" type="text" class="form-control" />
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Price per km</label>
                                <input  required name="km_price" type="number" class="form-control" />
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Base fare</label>
                                <input  required name="base_fare" type="number" class="form-control" />
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Minimum fare</label>
                                <input  required name="minimum_fare" type="number" class="form-control" />
                            </div>
                            <div class="form-group col-lg-12 col-md-12">
                                <label>Carrier Status</label>
                                <input name="carrier_status" type="checkbox"  />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
    @include('errors.error_layout')
@stop
