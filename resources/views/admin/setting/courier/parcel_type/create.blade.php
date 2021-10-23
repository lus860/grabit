@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/parcel-type" enctype="multipart/form-data" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        @if($errors->any())
            <h3 style="color: red;">Error: </h3>
            <p style="color: red;">{{$errors->first()}}</p>
        @endif
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Parcel Type</h3>
            <div class="col-lg-7 col-md-7 form-wrapper">
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-4">
                                <label>Parcel Name</label>
                                <input  required name="parcel_name" type="text" class="form-control" />
                            </div>
                        <div class="form-group col-lg-4 col-md-4">
                                <label>Parcel Status</label>
                                <input  name="parcel_status" type="checkbox" />
                            </div>
                        </div>
                    </div>

                </div>


                <div class="other-items"></div>

                <script>
                    $(function(){
                        $(".add-more-options").click(function() {
                            $(this).closest('.the-menu-items').find('.other-menu-options').append($('#original-item-content').html());
                        });
                    });
                </script>
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

    </form>
    @include('errors.error_layout')
@stop
