@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{ $edit?route('menu.update', ['menu'=>$item['id'],]):route('menu.store') }}" id="form" enctype="multipart/form-data" method="post">
        @csrf
        @if ($edit)
            @method('put')
        @endif
        <div class="ssj-form-wrapper">
            @if($errors->any())
                <div class="alert alert-danger">
                    <h3 style="margin-top:0;">Errors: </h3>
                    @foreach($errors->all() as $error)
                        <p>{{ $loop->iteration }}. {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="col-lg-12 col-md-12 form-wrapper">
                <h3>Menu Detail</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-4">
                            <label>Category Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $item['name']??null) }}">

                        </div>
                        <div class="form-group col-lg-2 col-md-4">
                            <label>Vendor</label>
                            <select name="restaurant_id" class="form-control" id="restaurant_id">
                                <option value="">Select</option>
                                @foreach($restaurants as $restaurant)
                                    @if($restaurant_id == $restaurant->id)
                                        <option selected value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @else
                                        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2 col-md-4">
                            <label>Start Time</label>
                            <input name="start_time" type="time" class="form-control" @if(isset($item) && !$item['same_as_restaurant']) value="{{ old('start_time', $item['start_time']??null) }}" @else value="" @endif {{(isset($item['same_as_restaurant']) && $item['same_as_restaurant'])?'disabled':''}}/>
                        </div>
                        <div class="form-group col-lg-2 col-md-4">
                            <label>End Time</label>
                            <input name="end_time" type="time" class="form-control" @if(isset($item) && !$item['same_as_restaurant']) value="{{ old('end_time', $item['end_time']??null) }}" @else value="" @endif {{(isset($item['same_as_restaurant']) && $item['same_as_restaurant'])?'disabled':''}}/>
                        </div>
                        <div class="form-group col-lg-2 col-md-4">
                            <label>Same as vendor</label>
                            <input name="same_as_restaurant" type="checkbox" class="form-control same-restaurant" @if(old('same_as_restaurant')) checked @endif {{(isset($item['same_as_restaurant']) && $item['same_as_restaurant'])?'checked':''}}/>
                        </div>

                        <div class="form-group col-lg-2 col-md-4">
                            <label>Availability</label>
                            <select name="availability" id="menu_availability" class="form-control">
                                @php $availability = old('availability', $item['availability']??null) @endphp
                                @foreach(config('menu.availability') as $key=>$value)
                                    <option value="{{$key}}" {!! $key==$availability?'selected':'' !!}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Sort Position</label>
                            <select name="sort_id" id="sort_id" class="form-control">
                                @php $sort_id = old('sort_id', $sort_id??null) @endphp
                                @for($i=1;$i <= $sort ;$i++)
                                    <option value="{{$i}}" {!! $i==$sort_id?'selected':'' !!}>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Upload image</label>
                            <input type="file" name="image" class="image-upload form-control">
                        </div>
                        <div class="form-group col-lg-2 col-md-4">
                            <label>Early schedule time</label>
                            <input type="time" name="early_schedule_time" class="image-upload form-control" @if(isset($item)) value="{{ old('early_schedule_time', $item['early_schedule_time']??null) }}" @else value="" @endif>
                        </div>
                        <div class="form-group col-lg-2 col-md-4">
                            <label>Latest schedule time</label>
                            <input type="time" name="latest_schedule_time" class="image-upload form-control" @if(isset($item)) value="{{ old('latest_schedule_time', $item['latest_schedule_time']??null) }}" @else value="" @endif>
                        </div>
                    </div>
                    <div class="row" id="menu_other_days" {!! $availability!='specific_days'?'style="display: none"':'' !!}>
                        <div class="form-group col-lg-12 col-md-12">
                            <label>Select Available Days</label>
                            <div>
                                @php $checked_days = old('day_id', $item['day_id']??[]) @endphp
                                @foreach($days as $day)
                                    <label><input type="checkbox" name="day_id[]"
                                                  value="{{$day->id}}" {!! in_array($day->id, $checked_days)?'checked':false !!} /> {{$day->long_name}} </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-lg-12 col-md-7 form-wrapper">
                <h3>Menu Items</h3>

                <div id="menu-items"></div>

                <div class="row form-row" style="border-top: 1px solid #aaa; padding-top: 20px; ">
                    <div class="row" style="padding-bottom: 0 !important;">
                        <div class="form-group col-lg-12 col-md-12">
                            <a style="background: red !important; border: 0 !important;" id="add-menu-item"
                               class="btn btn-primary"><i class="fa fa-plus"></i> Add menu item</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <button type="button" onclick="formSubmit(1)"  class="btn btn-primary" id="form-submit">Apply</button>
                        <button type="button" onclick="formSubmit(2)" class="btn btn-primary" id="form-submit-new-page">Save Changes</button>
                        <input id="choose-submit-type" type="hidden" name="blank" value="1">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="hidden">
        <div id="example-menu-item" class="other-product-item" data-prefix="menu_items">
            <input type="hidden" class="hidden menu-item-id" data-name="[id]">
            <div class="row">
                <div class="form-group col-lg-2 col-md-6">
                    <label>Item Name</label>
                    <input data-name="[name]" type="text" class="form-control"/>
                </div>
                <div class="form-group col-lg-2 col-md-6">
                    <label>Item Type</label>
                    <select data-name="[type]" class="form-control">
                        <option value="0">none</option>
                        <option value="1">Vegetarian</option>
                        <option value="2">Non-Vegetarian</option>
                    </select>
                </div>
                <div class="form-group col-lg-2 col-md-6">
                    <label>Price</label>
                    <input data-name="[price]" value="0" type="number" min="0"
                           class="form-control"/>
                </div>
                <div class="form-group col-lg-3 col-md-6">
                    <label>Max Order Quantity</label>
                    <input data-name="[max_quantity]" value="1" min="1" type="number"
                           class="form-control"/>
                </div>
                <div class="form-group col-lg-3 col-md-12">
                    <label>Description</label>
                    <textarea data-name="[description]" class="form-control"
                              placeholder="Description"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-3 col-md-6">
                    <label>Container Price</label>
                    <input data-name="[container_price]" value="0" type="number" min="0"
                           class="form-control"/>
                </div>
                <div class="form-group col-lg-3 col-md-6">
                    <label>Offer Price</label>
                    <input data-name="[offer_price]" value="0" type="number" min="0"
                           class="form-control"/>
                </div>
                <div class="form-group col-lg-2 col-md-6">
                    <label>Special Offer</label>
                    <select data-name="[special_offer]" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                <div class="form-group col-lg-2 col-md-6">
                    <label>Popular Item</label>
                    <select data-name="[popular_item]" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group col-lg-2 col-md-6">
                    <label>Item Image</label>
                    <input data-name="[item_image]" type="file" checked class="form-control"/>
                </div>
                <div class="form-group col-lg-1 col-md-6">
                    <label>Status</label>
                    <input data-name="[status]" type="checkbox" checked class="form-control"/>
                </div>
            </div>
            <div class="menu-options other-menu-options"></div>
            <div class="row addMoreButtonSection" style="padding-bottom: 0 !important;">
                <div class="form-group col-lg-12 col-md-12" data-click="1">
                    <div class="menu-item-unique pull-left" style="display:none">
                        <div class="menu-item-number item-number"
                             style="display:inline-block; vertical-align: middle"></div>
                        <div class="menu-item-delete item-number"
                             style="display:inline-block; vertical-align: middle; cursor:pointer"><i
                                    class="fa fa-trash-o"></i></div>
                    </div>
                    <div class="dropdown float-right add-more-groups" data-which-item="0">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Add
                            more group options
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" class="add-group-option" data-type="addon">Addon</a></li>
                            <li><a href="javascript:void(0)" class="add-group-option" data-type="variant">Variant</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <div id="example-group-option" data-prefix="[options]">
            <div class="row item-option-container">
                <div class="form-group col-lg-2 col-md-12">
                    <input type="hidden" class="hidden group-option-id" data-name="[id]">
                    <input type="hidden" data-name="[type]" class="group-option-type">
                    <div style="font-weight: bold; margin-bottom: 5px">Type: <i class="group-option-type-text"
                                                                                style="text-transform: capitalize"></i>
                    </div>
                    <div style="margin-bottom: 10px">
                        <label>Category name</label>
                        <input type="text" data-name="[name]" class="form-control">
                    </div>
                    <div style="margin-bottom: 10px" class="if-group-addon">
                        <label>Maximum</label>
                        <select data-name="[item_maximum]" class="form-control" style="width: 100%">
                            @for($i=1; $i<=$menu_types['addon']['max']; ++$i)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group col-lg-2 col-md-12 add-category-name-container">
                    <div style="margin-bottom: 5px">&nbsp;</div>
                    <label>&nbsp;</label>
                    <div>
                        <div class="add-button add-category-name"
                             style="display:inline-block; vertical-align: middle; cursor:pointer">
                            <i class="fa fa-plus"></i>
                        </div>
                        <div class="group-option-delete item-number"
                             style="display:inline-block; vertical-align: middle; cursor:pointer">
                            <i class="fa fa-trash-o"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="example-category-name" class="form-group col-lg-2 col-md-12" data-prefix="[values]">
            <input type="hidden" class="hidden option-value-id" data-name="[id]">
            <div style="font-weight: bold; margin-bottom: 5px;"><a style="color:#ff0000; visibility: hidden;"
                                                                   href="javascript:void(0)"
                                                                   class="remove-category-name">REMOVE</a></div>
            <div style="margin-bottom:10px;">
                <label>Category value</label>
                <input type="text" data-name="[value]" class="form-control">
            </div>
            <div style="margin-bottom:10px;">
                <label>Price for Value</label>
                <input type="text" data-name="[price]" class="form-control">
            </div>
            <div style="margin-bottom:10px;">
                <label>Status</label>
                <input type="checkbox" checked data-name="[status]" >
            </div>
        </div>
    </div>
    {{--    @include('errors.error_layout')--}}
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        (function () {
            let types = {!! json_encode($menu_types) !!},
                updateIndexes = function () {
                    let items = $('.menu-item');
                    if (items.length === 1) {
                        items.find('.menu-item-unique').hide();
                        items.find('.menu-item-number').html('');
                    } else $.each(items, function (i, e) {
                        let self = $(e);
                        self.find('.menu-item-number').html((i + 1).toString());
                        self.find('.menu-item-unique').show();
                    });
                    $.each($('.group-option'), function (i, e) {
                        let group = $(e),
                            categoryNames = group.find('.category-name'),
                            max = types[group.data('type')].max;
                        group.find('.remove-category-name').css('visibility', categoryNames.length > 1 ? 'visible' : 'hidden');
                        if (categoryNames.length >= max) group.find('.add-category-name').hide();
                        else group.find('.add-category-name').show();
                    });
                },
                addCategoryName = function (group, id) {
                    let btn = group.find('.add-category-name-container'),
                        optionValue = $('#example-category-name').clone().removeAttr('id').addClass('category-name').insertBefore(btn);
                    if (id) optionValue.find('.option-value-id').val(id);
                    else optionValue.find('.option-value-id').remove();
                    updateIndexes();
                    return optionValue;
                },
                addGroupOption = function(item, type, id){
                    let group = $('#example-group-option').clone().removeAttr('id').addClass('group-option').data('type', type).appendTo(item.find('.menu-options'));
                    if (type !== 'addon') group.find('.if-group-addon').remove();
                    group.find('.group-option-type').attr('value', type);
                    group.find('.group-option-type-text').html(type);
                    if (id) group.find('.group-option-id').val(id);
                    else group.find('.group-option-id').remove();
                    return group;
                },
                addMenuItem = function (id) {
                    let item = $('#example-menu-item').clone().removeAttr('id').addClass('menu-item').appendTo('#menu-items');
                    if (id) item.find('.menu-item-id').val(id);
                    else item.find('.menu-item-id').remove();
                    updateIndexes();
                    return item;
                },
                setVal = function (item, name, value) {
                    if (typeof value !== 'undefined' && name=='[status]' && value == 1){
                        item.find('[data-name="'+name+'"]').prop('checked', true);
                        return;
                    }

                    if (typeof value !== 'undefined') item.find('[data-name="'+name+'"]').val(value);
                },
                setVals = function (item, inserts, object){
                    inserts.forEach(insert => setVal(item, '['+insert+']', object[insert]));
                },
                addInputNames = function (parent, prefix = '') {
                    if (typeof prefix !== 'string') prefix = '';
                    let parents = parent.find('[data-prefix]').filter(function (index, item) {
                        return $(item).parents('[data-prefix]').length === 0;
                    });
                    if (!parents.length) return false;
                    $.each(parents, function (i, e) {
                        let el = $(e),
                            contPrefix = prefix + el.attr('data-prefix') + '[' + i + ']';
                        el.removeAttr('data-prefix');
                        $.each(el.find('[data-name]').filter(function (index, item) {
                            return $(item).parents('[data-prefix]').length === 0;
                        }), function (k, input) {
                            let inp = $(input);
                            inp.attr('name', contPrefix + inp.attr('data-name')).removeAttr('data-name');
                        });
                        addInputNames(el, contPrefix);
                    });
                },
                render = function(items){
                    // console.log(items)
                    items.forEach(item => {
                        let newMenuItem = addMenuItem(item.id),
                            inserts = ['name', 'type', 'price', 'max_quantity', 'description', 'container_price', 'offer_price', 'special_offer', 'popular_item','status'];
                        setVals(newMenuItem, inserts, item);
                        if (item.options && item.options.length > 0) item.options.forEach(option => {
                            let newGroupOption = addGroupOption(newMenuItem, option.type, option.id),
                                inserts = ['name', 'type', 'price', 'max_quantity', 'description', 'container_price', 'offer_price', 'special_offer','item_maximum' ,'popular_item','status'];
                            setVals(newGroupOption, inserts, option);
                            if (option.values && option.values.length > 0) option.values.forEach(value => {
                                let newOptionValue = addCategoryName(newGroupOption, value.id),
                                    inserts = ['value', 'price','status'];
                                setVals(newOptionValue, inserts, value);
                            });
                            else addCategoryName(newGroupOption);
                        });
                    });
                };
            $("#menu_availability").on('change', function () {
                if ($(this).val() === 'specific_days') $("#menu_other_days").slideDown();
                else $("#menu_other_days").slideUp();
            });
            $('#add-menu-item').on('click', function () {
                addMenuItem();
            });
            $('#menu-items').on('click', '.menu-item-delete', function () {
                $(this).parents('.menu-item').remove();
                updateIndexes();
            }).on('click', '.add-group-option', function () {
                let btn = $(this),
                    item = btn.parents('.menu-item'),
                    type = btn.data('type'),
                    group = addGroupOption(item, type);
                addCategoryName(group);
            }).on('click', '.group-option-delete', function () {
                $(this).parents('.group-option').remove();
            }).on('click', '.add-category-name', function () {
                let group = $(this).parents('.group-option');
                addCategoryName(group);
            }).on('click', '.remove-category-name', function () {
                $(this).parents('.category-name').remove();
                updateIndexes();
            });
            // $('#form').on('submit', function (e) {
            //     let self = $(this);
            //     e.preventDefault();
            //     addInputNames(self, '');
            //     self.off('submit').submit();
            //     $('#form-submit').attr('disabled', 'disabled');
            // });
            window.formSubmit = function (val){
                $('#choose-submit-type').val(val)
                submitForm();
            };
            let submitForm = function(){
                let self = $('#form');
                addInputNames(self, '');
                self.submit();
                $('#form-submit').attr('disabled', 'disabled');
            }
            @if ($render)
            render({!! json_encode($render) !!});
            @else
            addMenuItem();
            @endif
            setTimeout(function () {
                let checkboxes = $('#menu-items').find(':checkbox');
                checkboxes.removeAttr('value')
            },1000);
            $('.same-restaurant').on('change',function () {
                if($(this)[0].checked){
                    $('input[name="start_time"]').prop('disabled', 'disabled');
                    $('input[name="end_time"]').prop('disabled', 'disabled');
                }else{
                    $('input[name="start_time"]').prop('disabled', false);
                    $('input[name="end_time"]').prop('disabled', false);
                }
            })

        })();
    </script>
@endpush
