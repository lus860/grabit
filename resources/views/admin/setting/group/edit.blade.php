@extends('backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    {{--<form action="{{route('groups.update',$group->id)}}" enctype="multipart/form-data" method="patch">--}}

        {!! Form::model($group,['method' => 'PATCH','route'=>['groups.update',$group->id]]) !!}
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
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Group Name</label>
                            <input placeholder="Eg. Choice of bread" value="{{$group->name}}" required name="name" type="text" class="form-control" />
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Group Type</label>
                            <select required name="ctype" class="form-control">
                                <option @if($group->ctype == '1') selected @endif value="1">Multiple Select (Addon)</option>
                                <option @if($group->ctype == '2') selected @endif value="2">Single Select (Variant)</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Maximum Selection</label>
                            <input value="{{$group->select_max}}" required name="max_selection" type="number" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
                <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Group Values</h3>
                <div class="row form-row the-menu-items">

                    @foreach($group->values as $key=>$value)
                        <div class="row item-option-container" style="position:relative;">
                            <div class="form-group col-lg-12 col-md-12">
                                <label>Value Name</label>
                                <input placeholder="Enter item name" value="{{$value->name}}" required name="item_name[]" type="text" class="form-control" />
                                <a class="remove-item" style="color: red; font-weight: bold; margin-top: 4px; cursor: pointer;"><i class="fa fa-times"></i> Remove</a>
                            </div>
                        </div>
                    @endforeach

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
                            var _html = '<div class="row item-option-container">\n' +
                                '<div class="form-group col-lg-12 col-md-12">\n' +
                                '<label>Value Name</label>\n' +
                                '<input placeholder="Enter item name" value="" required name="item_name[]" type="text" class="form-control" />\n' +
                                '</div>\n' +
                                '</div>';
                            $(this).closest('.the-menu-items').find('.other-menu-options').append(_html);
                        });

                        $(".remove-item").click(function(){
                            $(this).parent().remove();
                        })
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

    {!! Form::close() !!}
    @include('errors.error_layout')
@stop