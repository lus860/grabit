@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    {{--    @dd(old('cuisine'))--}}
    <div class="message-for-upload hide">
        <div class="alert alert-success">
            File is uploaded
        </div>
    </div>
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/vendors" enctype="multipart/form-data" method="post">
        @csrf
        <div class="ssj-form-wrapper">
            @include('errors.error_layout')
            @if(isset($vendor_type) && $vendor_type)
                <div class="dropdown float-left" data-which-item="0" style="margin-bottom: 5px">
                    <input type="hidden" name="vendor_id" class="vendor-type" value="">
                    <button style="width: 160px" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select vendor type
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach($vendor_type as $value)
                        <li>
                            <a href="javascript:void(0)" class="add-vendor-type" data-type="{{$value->id}}">{{$value->vendor_name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Vendor information</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Name</label>
                            <input  name="name" type="text" class="form-control" @if(old('name'))value="{{old('name')}}"@endif />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Company Name</label>
                            <input  name="company_name" type="text" class="form-control" @if(old('company_name'))value="{{old('company_name')}}"@endif/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Contact Name</label>
                            <input  name="contact_name" type="text" class="form-control" @if(old('contact_name'))value="{{old('contact_name')}}"@endif />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Contact Email</label>
                            <input  name="email" type="email" class="form-control" @if(old('email'))value="{{old('email')}}"@endif />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Contact Phone</label>
                            <input  name="phone" type="tel" class="form-control" @if(old('phone'))value="{{old('phone')}}"@endif/>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Website Address</label>
                            <input name="website" type="url" class="form-control"  @if(old('website'))value="{{old('website')}}"@endif/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Banner Image</label>
                            <input  accept=".png" name="banner_image" type="file"   class="form-control banner_image" />
                            <input   name="banner_saved_image" @if(old('banner_saved_image')) value="{{old('banner_saved_image')}}" @endif type="hidden" class="form-control banner_saved_image" />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Display Image</label>
                            <input  accept=".png" name="display_image" type="file"  class="form-control display_image" />
                            <input  name="display_saved_image" @if(old('display_saved_image')) value="{{old('display_saved_image')}}" @endif type="hidden" class="form-control display_saved_image" />
                        </div>
                    </div>

                    <div class="row" style="padding-bottom: 0 !important;">
                        <div class="form-group col-lg-12 col-md-12">
                            <label>Order notification email(s)</label>
                            <p>Add up to 4 email addresses to receive order notification.</p>
                            {{--<input name="notification_email[]" placeholder="Enter email address" type="email" class="form-control" />--}}
                            <div class="other-emails"></div>
                            <a id="add-other-emails" href="#" class="btn btn-primary"><i class="fa fa-plus"></i> Add email</a>
                            <script>
                                $("#add-other-emails").click(function(){
                                    var _html = '<div class="other-email-item"><input name="notification_email[]" placeholder="Enter email address" type="email" class="form-control" /><i class="fa fa-times remove-other-email remove-icon"></i></div>';

                                    var count_emails = $(".other-email-item").length;
                                    console.log(count_emails);
                                    if(count_emails<4) {
                                        $(".other-emails").append(_html);
                                    }else{
                                        alert("You can enter maximum of 4 emails");
                                    }
                                    $(".remove-other-email").click(function(){
                                        $(this).parent().remove();
                                    });
                                    return false;
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Login information</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Login Email</label>
                            <input  name="login_email" id="login_email" type="email" class="form-control" @if(old('login_email'))value="{{old('login_email')}}"@endif/>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Password</label>
                            <input  name="password" type="password" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Cuisines</h3>
                <div class="row form-row">

                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12">
                            <label>Select all that apply.</label>
                            <div class="clearfix"></div>
                            <div style="padding: 6px; background: #fff; border: 1px solid #eee;">
                                @foreach($cuisines as $cuisine)
                                    <label class="cuisine-item"><input name="cuisine[]" type="checkbox" value="{{$cuisine->id}}" @if(old('cuisine') && in_array($cuisine->id,old('cuisine')))checked @endif> {{$cuisine->name}}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Preparation time</label>
                            <input  name="cuisine_prep_time" placeholder="Minutes" type="number" min="5" step="5" @if(old('cuisine_prep_time'))value="{{old('cuisine_prep_time')}}"@endif class="form-control" />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Cost for two</label>
                            <input  name="cuisine_cost_for_two" placeholder="" type="number" min="0" class="form-control" @if(old('cuisine_cost_for_two'))value="{{old('cuisine_cost_for_two')}}"@endif />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Minimum order</label>
                            <input  name="minimum_order" placeholder="Minimum order" type="number" min="0" class="form-control" @if(old('minimum_order'))value="{{old('minimum_order')}}"@endif />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12">
                            <label>Offering</label>
                            <div class="clearfix"></div>
                            <div style="padding: 6px; background: #fff; border: 1px solid #eee;">
                                @foreach($cuisine_offering as $offering)
                                    <label class="cuisine-item"><input type="checkbox" value="{{$offering->id}}" name="cuisine_offering[]" @if($offering->id == 1 && !old('cuisine_offering')) checked @endif @if(old('cuisine_offering') && in_array($offering->id,old('cuisine_offering')))checked @endif /> {{$offering->title}} </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Location &amp; address</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Country</label>
                            <select  name="country" id="country" class="form-control">
                                <option value="">Select</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" >{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>City</label>
                            <select  name="city" id="city" class="form-control" ></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Area</label>
                            <select  name="area" id="area" class="form-control"></select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
{{--                            <label>Service Area</label>--}}
{{--                            <select  name="service_area[]" id="service_area" class="form-control example-getting-started" multiple="multiple"></select>--}}
{{--                            <div class="other-service-area"></div>--}}
{{--                            <a style="display: none;" id="add-service-area" class="add-more-options btn btn-success"><i class="fa fa-plus"></i> Add service area</a>--}}
                            <label>Service Area</label>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" id="option_service_area">
                                        <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu" id="all_service_area"></ul>
                                </div>
                                <input type="text" class="form-control example-getting-started" id="service_area"  style="margin-top: 5px">
                                <input type="hidden" class="form-control example-getting-started" name="service_area[]" id="service_area_hidden">
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $(function(){
                            // $('#service_area').multiselect();
                            $('#city').change(function(){
                                var val = $(this).val();
                                if(val !== ''){
                                    $('#add-service-area').show();
                                }else{
                                    $('#add-service-area').hide();
                                }
                            });
                            // $('#add-service-area').click(function(){
                            //     var service_areas = $('#service_area').html();
                            //     $(".other-service-area").append('<select class="form-control" name="service_area[]">'+service_areas+'</select>');
                            // });
                            {{--$("#country").change(function() {--}}
                            {{--    if($(this).val() !== '') {--}}
                            {{--        $.ajax({--}}
                            {{--            url: '{!! url('/backend/get-cities') !!}',--}}
                            {{--            type: 'GET',--}}
                            {{--            data: {--}}
                            {{--                'id': $(this).val(),--}}
                            {{--                '_token': '{!! csrf_token() !!}'--}}
                            {{--            },--}}
                            {{--            beforeSend: function () {--}}

                            {{--            },--}}
                            {{--            success: function (data) {--}}
                            {{--                var item = [], obj = '<option>Select City</option>';--}}
                            {{--                $.each(data.data.cities, function (i, val) {--}}
                            {{--                    window.localStorage.setItem(val.city_name, JSON.stringify(val.areas));--}}
                            {{--                    obj += '<option value="' + val.city_id + '">' + val.city_name + '</option>';--}}
                            {{--                });--}}
                            {{--                item.push(obj);--}}
                            {{--                $('#city').html(item.join(''));--}}

                            {{--                $("#city").change(function(){--}}
                            {{--                    var areas = JSON.parse(window.localStorage.getItem($(this).find('option:selected').text()));--}}
                            {{--                    var item2 = [], obj2 = '';--}}
                            {{--                    if(areas.length>0){--}}
                            {{--                        $.each(areas, function(i, val2){--}}
                            {{--                            obj2 += '<option value="'+val2.id+'" selected>'+val2.name+'</option>';--}}
                            {{--                        });--}}
                            {{--                        item2.push(obj2);--}}
                            {{--                        $("#area, #service_area").html(item2.join(''));--}}
                            {{--                        $('#service_area').multiselect('rebuild');--}}
                            {{--                    }--}}
                            {{--                });--}}
                            {{--            },--}}
                            {{--            datType: 'json'--}}
                            {{--        });--}}
                            {{--    }--}}
                            {{--});--}}
                            $("#country").change(function() {
                                console.log($(this).val());
                                if($(this).val() !== '') {
                                    $.ajax({
                                        url: '{!! url('/backend/get-cities') !!}',
                                        type: 'GET',
                                        data: {
                                            'id': $(this).val(),
                                            '_token': '{!! csrf_token() !!}'
                                        },
                                        beforeSend: function () {

                                        },
                                        success: function (data) {
                                            var item = [], obj = '<option>Select City</option>';
                                            $.each(data.data.cities, function (i, val) {
                                                window.localStorage.setItem(val.city_name, JSON.stringify(val.areas));
                                                obj += '<option value="' + val.city_id + '">' + val.city_name + '</option>';
                                            });
                                            item.push(obj);
                                            $('#city').html(item.join(''));
                                            $("#city").change(function(){
                                                var areas = JSON.parse(window.localStorage.getItem($(this).find('option:selected').text()));
                                                var item2 = [], obj2 = '';
                                                var item3 = [], obj3 = '';
                                                var service_areas = [];
                                                var line = "";
                                                if(areas.length>0){
                                                    $.each(areas, function(i, val2){
                                                        obj2 += '<option value="'+val2.id+'" selected>'+val2.name+'</option>';
                                                        obj3 += '<li><input type="checkbox" class="option_service_area" value="'+ val2.id +'"  style="margin-left: 5px" checked>';
                                                        obj3 += '<span class="lbl">'+ val2.name +'</span></li>';
                                                        service_areas.push(val2.id);
                                                        line += val2.name + ";";
                                                    });
                                                   // obj3 += '<ul role="menu" class="dropdown-menu" id="all_service_area">'+obj3+'</ul>'
                                                    item2.push(obj2);
                                                    item3.push(obj3);
                                                   // $(item3).insertBefore( "#option_service_area" );

                                                   $('#all_service_area').empty().append(item3);
                                                    $('#all_service_area').css( 'height', '400px');
                                                    $("#service_area_hidden").val(service_areas);
                                                    $("#service_area").val(line);
                                                    // $("#area, #service_area").html(item2.join(''));
                                                    // $('#service_area').multiselect('rebuild');
                                                    $("#area").html(item2.join(''));
                                                }
                                            });
                                        },
                                        datType: 'json'
                                    });
                                }
                            });

                            $("#option_service_area").click(function() {
                                $(".option_service_area").each(function() {
                                    $(this).change(function() {
                                        var line = "";
                                        var service_areas = [];
                                        $(".option_service_area").each(function() {
                                            if($(this).is(":checked")) {
                                                if($(this).val() != ''){
                                                    line += $("+ span", this).text() + ";";
                                                    service_areas.push($(this).val());
                                                }
                                            }
                                        });
                                        $("#service_area").val(line);
                                        $("#service_area_hidden").val(service_areas);
                                    });
                                });
                            });

                            $("#login_email").blur(function() {
                                if($(this).val() !== '') {
                                    $.ajax({
                                        url: '{!! url('/backend/check-existing-restaurant-user') !!}',
                                        type: 'GET',
                                        data: {
                                            'email': $(this).val(),
                                            '_token': '{!! csrf_token() !!}'
                                        },
                                        beforeSend: function () {

                                        },
                                        success: function (data) {
                                            if(data.exists === 1){
                                                alert("Login email already exists");
                                            }
                                        },
                                        datType: 'json'
                                    });
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <label>Coordinates</label>
                        <div style="clear:both"></div>
                        <input  name="latitude" placeholder="Latitude" style="width: 48%; float: left;" type="text" class="form-control" @if(old('latitude'))value="{{old('latitude')}}" @endif/>
                        <input  name="longitude" placeholder="Longitude" style="width: 48%; float: right;" type="text" class="form-control" @if(old('longitude'))value="{{old('longitude')}}" @endif/>
                        <div style="clear:both"></div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Address 1</label>
                            <input  name="address1" type="text" class="form-control" @if(old('address1'))value="{{old('address1')}}" @endif/>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Address 2</label>
                            <input  name="address2" type="text" class="form-control" @if(old('address2'))value="{{old('address2')}}" @endif/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Delivery Commission (%)</label>
                            <input  name="delivery_commission" type="number" class="form-control" @if(old('delivery_commission'))value="{{old('delivery_commission')}}" @endif/>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Collection Commission (%)</label>
                            <input  name="collection_commission" type="number" class="form-control" @if(old('collection_commission'))value="{{old('collection_commission')}}" @endif/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Dine in Commission (%)</label>
                            <input  name="dine_in_commission" type="number" class="form-control" @if(old('dine_in_commission'))value="{{old('dine_in_commission')}}" @endif/>
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Status</label>
                            <select name="status"  class="form-control">
                                <option value="">Select</option>
                                <option value="1" @if(old('status') == 1)selected @endif>Active</option>
                                <option value="2" @if(old('status') == 2)selected @endif>In Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Contact number for customers</label>
                            <input  name="number_for_customers" type="number" class="form-control" @if(old('number_for_customers'))value="{{old('number_for_customers')}}" @endif/>
                        </div>
                        {{--                    <div class="form-group col-lg-6 col-md-6">--}}
                        {{--                        <label>Status</label>--}}
                        {{--                        <select name="status"  class="form-control">--}}
                        {{--                            <option value="">Select</option>--}}
                        {{--                            <option value="1">Active</option>--}}
                        {{--                            <option value="2">In Active</option>--}}
                        {{--                        </select>--}}
                        {{--                    </div>--}}
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Opening Times</h3>
                <div class="row form-row">
                    @foreach($opening_days as $key=>$val)
                        <div id="open-days" class="row">
                            <div class="form-group col-lg-3 col-md-3">
                                <label>Day</label>
                                <select name="opening_day[]" class="form-control select-days"  readonly>
                                    @foreach($opening_days as $key2=>$val2)
                                        @if($key == $key2)
                                            <option selected value="{{$key2}}">{{$val2}}</option>
                                        @endif
                                        {{--<option value="{{$key2}}">{{$val2}}</option>--}}
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-3">
                                <label>From</label>
                                <select name="opening_time[]" class="form-control select-days" >
                                    <option value=""></option>
                                    @foreach($opening_times as $time)
                                        <option value="{{$time}}" @if(old('opening_time.'.$key) == $time)selected @endif>{{$time}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-3">
                                <label>To</label>
                                <select name="closing_time[]" class="form-control select-days" >
                                    <option value=""></option>
                                    @foreach($opening_times as $time)
                                        <option value="{{$time}}" @if(old('closing_time.'.$key) == $time)selected @endif>{{$time}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-3">
                                <label>Opening Status</label>
                                <select name="opening_status[]" class="form-control opening-status" >
                                    <option class="open-restaurant" value="1" @if(old('opening_status.'.$key) == 1)selected @endif>Open</option>
                                    <option class="close-restaurant" value="2" @if(old('opening_status.'.$key) == 2)selected @endif>Closed</option>
                                </select>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>





            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Break Days and Times</h3>
                <div class="row form-row">
                    <div class="row" id="original-break-days" style="display: none">
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Day</label>
                            <select name="break_day[]" class="form-control">
                                <option value="">Select</option>
                                @foreach($opening_days as $key3=>$val3)
                                    <option selected value="{{$key3}}">{{$val3}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>From</label>
                            <select name="break_start_time[]" class="form-control">
                                <option value=""></option>
                                @foreach($opening_times as $time)
                                    <option value="{{$time}}">{{$time}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>To</label>
                            <select name="break_end_time[]" class="form-control">
                                <option value=""></option>
                                @foreach($opening_times as $time)
                                    <option value="{{$time}}">{{$time}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="more-break-days"></div>


                    <a id="add-break-day" class="add-more-options btn btn-primary"><i class="fa fa-plus"></i> Add break times</a>
                    <script type="text/javascript">
                        $(function(){
                            $("#add-break-day").click(function(){
                                var content = $('#original-break-days').html();
                                $('#more-break-days').append('<div class="row day-item">'+content+' <i class="fa fa-times remove-icon remove-day-item"></i></div>');

                                $(".remove-day-item").click(function(){
                                    $(this).closest('.day-item').remove();
                                });
                                return false;
                            });
                        });
                    </script>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Banking &amp; payments</h3>
                <div class="row form-row">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <select name="bank_name" class="form-control">
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{$bank}}"@if(old('bank_name') == $bank) selected @endif>{{$bank}}</option>
                            @endforeach
                        </select>
                        {{--<input  name="bank_name" type="text" class="form-control" />--}}
                    </div>
                    <div class="form-group">
                        <label>Beneficiary Name</label>
                        <input name="beneficiary_name" type="text" class="form-control" @if(old('beneficiary_name'))value="{{old('beneficiary_name')}}" @endif/>
                    </div>
                    <div class="form-group">
                        <label>Bank Account Number</label>
                        <input name="account_number" type="text" class="form-control" @if(old('account_number'))value="{{old('account_number')}}" @endif/>
                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="form-group col-lg-6 col-md-6">--}}
{{--                            <label>PayTZ Number</label>--}}
{{--                            <input name="paytz_number" type="text" class="form-control" @if(old('paytz_number'))value="{{old('paytz_number')}}" @endif/>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-lg-6 col-md-6">--}}
{{--                            <label>Payment Frequency</label>--}}
{{--                            <select  name="payment_frequent" class="form-control">--}}
{{--                                <option>Select</option>--}}
{{--                                @foreach($payment_frequencies as $frequency)--}}
{{--                                    <option value="{{$frequency->id}}"@if(old('payment_frequent') == $frequency->id) selected @endif>{{strtoupper($frequency->title)}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Attachments</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Registration Certificate</label>
                            <input accept=".jpg,.pdf,.doc" name="registration_certificate" type="file" class="form-control" />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>TIN Certificate</label>
                            <input accept=".jpg,.pdf,.doc" name="tin_certificate" type="file" class="form-control" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Business License</label>
                            <input accept=".jpg,.pdf,.doc" name="business_license" type="file" class="form-control" />
                        </div>
                        <div class="form-group col-lg-6 col-md-6">
                            <label>ID of the Director</label>
                            <input accept=".jpg,.pdf,.doc" name="director_id" type="file" class="form-control" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Agreement</label>
                            <input accept=".jpg,.pdf,.doc" name="agreement" type="file" class="form-control" />
                        </div>
                    </div>

                </div>
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

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" style="z-index: 999999" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
    <style type="text/css">
        img {
            display: block;
            max-width: 90%;
        }
        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg{
            max-width: 1000px !important;
        }

        #all_service_area {
            overflow-y: scroll;
        }

    </style>
@endpush

@push('js')
    <script>
        $(function () {
            $('.opening-status').change(function () {
                let status = $(this).val();
                if (status == 2){
                    $(this).closest('#open-days').find('select').map(function( val, i) {
                        if ($(this).closest('#open-days').find('select').length != val+1 && val !=0){
                            $(i).prop('disabled', 'disabled');
                            $(i).val('null');
                        }
                    });
                }else{
                    $(this).closest('#open-days').find('select').map(function( val, i) {
                        if ($(this).closest('#open-days').find('select').length != val+1){
                            $(i).prop('disabled', false);;
                        }
                    });
                }
            });
            $('.add-vendor-type').on('click',function () {
                $('.vendor-type').val($(this).data('type'));
            });

        })
    </script>
    <script src="{{asset('admin/js/image_cropper.js')}}"></script>
@endpush
