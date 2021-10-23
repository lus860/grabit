@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="ssj-form-wrapper">
        <div class="col-lg-12 col-md-12 form-wrapper">
            <div class="row">
                <div class="col-lg-7 col-md-7">
                    <h3>Vendor information</h3>
                    <div class="row form-row">
                        <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=info"
                           class="btn btn-primary edit-button">Edit</a>
                        <div class="row display-row">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Name</label>
                                <p class="item-display">{{$restaurant->name}}</p>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Company Name</label>
                                <p class="item-display">{{$restaurant->company_name}}</p>
                            </div>
                        </div>

                        <div class="row display-row">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Contact Name</label>
                                <p class="item-display">{{$restaurant->contact_name}}</p>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Contact Email</label>
                                <p class="item-display">{{$restaurant->email}}</p>
                            </div>
                        </div>
                        <div class="row display-row">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Contact Phone</label>
                                <p class="item-display">{{$restaurant->phone}}</p>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Website Address</label>
                                <p class="item-display">{{$restaurant->website}}</p>
                            </div>
                        </div>

                        <div class="row" style="padding-bottom: 0 !important;">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Order notification email(s)</label>
                                <p class="item-display">
                                    @if(!empty($restaurant->restaurantEmail))
                                        @foreach($restaurant->restaurantEmail as $notf_email)
                                            {{$notf_email->email}}<br/>
                                        @endforeach
                                    @else
                                        There are no emails specified
                                    @endif
                                </p>
                                <div class="other-emails"></div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>Vendor type</label>
                                <p class="item-display">
                                    {{$restaurant->vendor_type?$restaurant->vendor_type->vendor_name:'N/A'}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 text-center">
                    <img src="{{ $restaurant->qr_url }}" alt="">
                    <p>QR code - {{ $restaurant->qr_code }}</p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=logins"
               class="btn btn-primary edit-button">Change Password</a>
            <h3>Login information</h3>
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Username</label>
                        <p class="item-display">
                            {{!empty($restaurant->user)?$restaurant->user->email: 'N/A'}}
                        </p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Password</label>
                        <p class="item-display"><i>Hidden</i></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=cuisines"
               class="btn btn-primary edit-button">Edit</a>
            <h3>Cuisines</h3>
            <div class="row form-row">

                <div class="row">
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Select all that apply.</label>
                        <div class="clearfix"></div>
                        <div style="padding: 6px; background: #fff; border: 1px solid #eee;">
                            @if(!empty($restaurant->restaurantCuisine))
                                @foreach($restaurant->restaurantCuisine as $key=>$cuisine)
                                    @if($key+1==count($restaurant->restaurantCuisine))
                                        <label class="cuisine-item">{{$cuisine->cuisine->name}}</label>
                                    @else
                                        <label class="cuisine-item">{{$cuisine->cuisine->name}}, </label>
                                    @endif
                                @endforeach
                            @else
                                There are no cuisine specified
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Preparation time</label>
                        <p class="item-display">
                            {{$restaurant->preparation_time}}
                        </p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Cost for two</label>
                        <p class="item-display">
                            {{$restaurant->cost_for_two}}
                        </p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Minimum order</label>
                        <p class="item-display">
                            {{$restaurant->minimum_order}}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Offering</label>
                        <div class="clearfix"></div>
                        <div style="padding: 6px; background: #fff; border: 1px solid #eee;">
                            @if(!empty($restaurant->restaurantOffering))
                                @foreach($restaurant->restaurantOffering as $key=>$offering)
                                    @if($key+1==count($restaurant->restaurantOffering))
                                        <label class="cuisine-item">{{$offering->offering->title}}</label>
                                    @else
                                        <label class="cuisine-item">{{$offering->offering->title}}, </label>
                                    @endif
                                @endforeach
                            @else
                                There are no offering specified
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=location"
               class="btn btn-primary edit-button">Edit</a>
            <h3>Location &amp; address</h3>
            <div class="row form-row">
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Country</label>
                        <p class="item-display">{{$restaurant->country->name}}</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>City</label>
                        <p class="item-display">{{$restaurant->city->name}}</p>
                    </div>
                </div>
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Latitude</label>
                        <p class="item-display">{{$restaurant->latitude}}</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Longitude</label>
                        <p class="item-display">{{$restaurant->longitude}}</p>
                    </div>
                </div>
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Address 1</label>
                        <p class="item-display">{{$restaurant->address1}}</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Address 2</label>
                        <p class="item-display">{{$restaurant->address2}}</p>
                    </div>
                </div>
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Area</label>
                        <p class="item-display">{{$restaurant->area->name}}</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Service Area</label>
                        <p class="item-display">
                            @php
                                if (is_array($restaurant->getServiceAre())){
                                    foreach ($restaurant->getServiceAre() as $key=>$area){
                                        if (count($restaurant->getServiceAre()) == $key+1){
                                            echo $area['name'];
                                        }else{
                                            echo $area['name']?$area['name'].', ':null;
                                        }
                                    }
                                    }else{
                                     $restaurant->getServiceAre();
                                    }
                            @endphp
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Delivery Commission</label>
                        <p class="item-display">{{$restaurant->delivery_commission}} %</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Collection Commission</label>
                        <p class="item-display">{{$restaurant->collection_commission}} %</p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Dine in Commission</label>
                        <p class="item-display">{{$restaurant->dine_commission}} %</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Status</label>
                        <p class="item-display">{{$restaurant->status == 1?'Active':'In Active'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Contact number for customers</label>
                        <p class="item-display">{{$restaurant->number_for_customers}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=times"
               class="btn btn-primary edit-button">Edit</a>
            <h3>Opening Times</h3>
            <div class="row form-row">
                @if(!empty($restaurant->openingTimes))

                    <div class="row" style="border-bottom: 1px solid #eee; padding-top: 10px;">
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Day</label>
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>From</label>
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>To</label>
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Opening Status</label>
                        </div>
                    </div>

                    @foreach($restaurant->openingTimes as $opening_time)
                        <div class="row" style="border-bottom: 1px solid #eee; padding-top: 10px;">
                            <div class="col-lg-3 col-md-3" style="margin-bottom: 0 !important;">
                                <p class="item-display">{{$opening_days[$opening_time->day]}}</p>
                            </div>
                            <div class="col-lg-3 col-md-3" style="margin-bottom: 0 !important;">
                                <p class="item-display">{{$opening_time->opening_time}}</p>
                            </div>
                            <div class="col-lg-3 col-md-3" style="margin-bottom: 0 !important;">
                                <p class="item-display">{{$opening_time->closing_time}}</p>
                            </div>
                            <div class="col-lg-3 col-md-3" style="margin-bottom: 0 !important;">
                                <p class="item-display">{{$opening_time->open_status == '1'?'Open':'Closed'}}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="item-display">No times defined</p>
                @endif
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=break_time"
               class="btn btn-primary edit-button">Edit</a>
            <h3>Break Days and Times</h3>
            <div class="row form-row">
                @if(isset($break_times) && !empty($break_times))
                    <div class="row" style="border-bottom: 1px solid #eee; padding-top: 10px;">
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Day</label>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>From</label>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>To</label>
                        </div>
                    </div>
                    @foreach($break_times as $key=>$break_time)
                        <div class="row" id="original-break-days" style="">
                            <div class="form-group col-lg-4 col-md-4">
                                <p class="item-display">{{$opening_days[$break_time['day']] }}</p>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <p class="item-display">{{$break_time['time_from'] }}</p>
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                <p class="item-display">{{$break_time['time_to'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div id="more-break-days"></div>


                {{--                <a id="add-break-day" class="add-more-options btn btn-success"><i class="fa fa-plus"></i> Add break times</a>--}}
                {{--                <script type="text/javascript">--}}
                {{--                    $(function(){--}}
                {{--                        $("#add-break-day").click(function(){--}}
                {{--                            var content = $('#original-break-days').html();--}}
                {{--                            $('#more-break-days').append('<div class="row day-item">'+content+' <i class="fa fa-times remove-icon remove-day-item"></i></div>');--}}

                {{--                            $(".remove-day-item").click(function(){--}}
                {{--                                $(this).closest('.day-item').remove();--}}
                {{--                            });--}}
                {{--                            return false;--}}
                {{--                        });--}}
                {{--                    });--}}
                {{--                </script>--}}
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=bank_details"
               class="btn btn-primary edit-button">Edit</a>
            <h3>Banking &amp; payments</h3>
            <div class="row form-row">
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Bank Name</label>
                        <p class="item-display">{{$restaurant->bank_name}} %</p>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Beneficiary Name</label>
                        <p class="item-display">{{$restaurant->beneficiary_name}}</p>
                    </div>
                </div>
                <div class="row display-row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Bank Account Number</label>
                        <p class="item-display">{{$restaurant->account_number}}</p>
                    </div>
                    {{--                    <div class="form-group col-lg-6 col-md-6">--}}
                    {{--                        <label>PayTZ Number</label>--}}
                    {{--                        <p class="item-display">{{$restaurant->paytz_number}}</p>--}}
                    {{--                    </div>--}}
                </div>

                {{--                <div class="row">--}}
                {{--                    <div class="form-group col-lg-6 col-md-6">--}}
                {{--                        <label>Payment Frequency</label>--}}
                {{--                        <p class="item-display">--}}
                {{--                            {{(!empty($restaurant->paymentFrequency))?--}}
                {{--                                $restaurant->paymentFrequency->title:'No Payment Frequency'}}</p>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-7 col-md-7 form-wrapper">
            <a href="{{url('/backend/vendors')}}/{{$restaurant->id}}/edit?type=attachments"
               class="btn btn-primary edit-button">Edit</a>
            <h3>Attachments</h3>
            {{--            <div class="row form-row">--}}
            {{--                <div class="row display-row">--}}
            {{--                    <div class="form-group col-lg-6 col-md-6">--}}
            {{--                        <label>Registration Certificate</label>--}}
            {{--                        <p class="item-display"><a href="{{$restaurant->registration_certificate}}" class="btn btn-primary"><i class="fa fa-attachment"></i> View attachment</a></p>--}}
            {{--                    </div>--}}
            {{--                    <div class="form-group col-lg-6 col-md-6">--}}
            {{--                        <label>TIN Certificate</label>--}}
            {{--                        <p class="item-display"><a href="{{$restaurant->tin_certificate}}" class="btn btn-primary"><i class="fa fa-attachment"></i> View attachment</a></p>--}}
            {{--                    </div>--}}
            {{--                </div>--}}

            {{--                <div class="row display-row">--}}
            {{--                    <div class="form-group col-lg-6 col-md-6">--}}
            {{--                        <label>Business License</label>--}}
            {{--                        <p class="item-display"><a href="{{$restaurant->business_license}}" class="btn btn-primary"><i class="fa fa-attachment"></i> View attachment</a></p>--}}
            {{--                    </div>--}}
            {{--                    <div class="form-group col-lg-6 col-md-6">--}}
            {{--                        <label>ID of the Director</label>--}}
            {{--                        <p class="item-display"><a href="{{$restaurant->director_id}}" class="btn btn-primary"><i class="fa fa-attachment"></i> View attachment</a></p>--}}
            {{--                    </div>--}}
            {{--                </div>--}}

            {{--                <div class="row">--}}
            {{--                    <div class="form-group col-lg-6 col-md-6">--}}
            {{--                        <label>Agreement</label>--}}
            {{--                        <p class="item-display"><a href="{{$restaurant->agreement}}" class="btn btn-primary"><i class="fa fa-attachment"></i> View attachment</a></p>--}}
            {{--                    </div>--}}
            {{--                </div>--}}

            {{--            </div>--}}
        </div>
        <div class="clearfix"></div>
        {{--    <div class="row">--}}
        {{--        <div class="col-lg-6 col-md-6">--}}
        {{--            <div class="form-group">--}}
        {{--                <a href="{{url('/backend/vendors')}}" type="submit" class="btn btn-primary">Back to list</a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
    </div>
@stop
