@extends('backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/groups" enctype="multipart/form-data" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        @if($errors->any())
            <h3 style="color: red;">Error: </h3>
            <p style="color: red;">{{$errors->first()}}</p>
        @endif
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Group Detail</h3>
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Restaurant</label>
                        <select required name="restaurant_id" class="form-control">
                            <option value="">Select Restaurant</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Group Name</label>
                        <input placeholder="Eg. Choice of bread" required name="name" type="text" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Group Type</label>
                        <select required name="ctype" class="form-control">
                            <option value="1">Multiple Select (Addon)</option>
                            <option value="2">Single Select (Variant)</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Maximum Selection</label>
                        <input value="1" required name="max_selection" type="number" class="form-control" />
                    </div>

                </div>
            </div>
        </div>

        <div class="clearfix"></div>
            <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Group Values</h3>
                <div class="row form-row the-menu-items">
                    <div id="original-item-content">
                        <div class="row item-option-container">
                            <div class="form-group col-lg-12 col-md-12">
                                <label>Value Name</label>
                                <input placeholder="Enter item name" required name="item_name[]" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="other-menu-options"></div>

                    <div class="row" style="padding-bottom: 0 !important;">
                        <div class="form-group col-lg-12 col-md-12">
                            <a id="add-more-options-" class="add-more-options btn btn-success"><i class="fa fa-plus"></i> Add more</a>
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