@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/push-notification" enctype="multipart/form-data" method="post">
        @csrf
        <div class="ssj-form-wrapper">
            @if($errors->any())
                <h3 style="color: red;">Error: </h3>
                <p style="color: red;">{{$errors->first()}}</p>
            @endif
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Message</h3>
                <div class="row form-row the-menu-items">
                    <div id="original-item-content">
                        <div class="row item-option-container">
                            <div class="form-group col-lg-12 col-md-12">
                                <label>Title</label>
                                <input placeholder="Enter title" name="title" type="text" class="form-control" />
                            </div>
                            <div class="form-group col-lg-12 col-md-12">
                                <label>Message</label>
                                <input placeholder="Enter message" name="message" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
    @include('errors.error_layout')
@stop
