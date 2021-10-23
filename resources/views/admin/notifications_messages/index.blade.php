@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="ssj-form-wrapper" style="min-height: 500px">
        <div class="alert alert-info">
            <p>Please use those keys if you want this piece of text to be dynamic, it will be changed from DB</p>
            <ul>
                <li>:restaurant_name for messages</li>
                <li>:transaction_id for messages</li>
                <li>:accept_message for messages</li>
                <li>:rider_name for messages</li>
                <li>:time is for “immediately” or “vendor prep time”</li>
                <li>:order is for “order received” or “scheduled order”</li>
            </ul>
        </div>
        @if(isset($messages) && is_array($messages) && count($messages))
            <form action="/backend/notification/update/{{$type}}/{{$name}}" enctype="multipart/form-data" class="col-xs-12" method="post">
            @csrf
            @foreach($messages as $key=>$value)
                    <div class="col-lg-6 col-md-6 form-wrapper">
                        <h3>{{ucfirst($key)}}</h3>
                        <div class="col-md-11 row form-row the-menu-items">
                            <div id="original-item-content">
                                <div class="row item-option-container">
                                    <div class="form-group col-lg-11 col-md-11">
                                        <label style="font-size: 20px">Title</label>
                                        @if(isset($value['text']))<p style="font-size: 10px">{{$value['text']}}</p>@endif
                                        <input placeholder="Enter title" required value="{{$value['title']}}" name="{{$key}}[title]" type="text" class="form-control" />
                                    </div>
                                    <div class="form-group col-lg-11 col-md-11">
                                        <p>Message</p>
                                        <textarea name="{{$key}}[message]"  placeholder="Enter message" style="width: 100%" rows="6">
                                                {{$value['message']}}
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
                @if(isset($statuses) && count($statuses))
                    @foreach($statuses as $value)
                        <div class="col-lg-6 col-md-6 form-wrapper hide" id="{{$value}}">
                            <h3>{{ucfirst($value)}}</h3>
                            <div class="col-md-11 row form-row the-menu-items">
                                <div id="original-item-content">
                                    <div class="row item-option-container">
                                        <div class="form-group col-lg-11 col-md-11">
                                            <label>Title</label>
                                            <input disabled placeholder="Enter title" required value="" name="{{$value}}[title]" type="text" class="form-control" />
                                        </div>
                                        <div class="form-group col-lg-11 col-md-11">
                                            <p>Message</p>
                                            <textarea disabled name="{{$value}}[message]"  placeholder="Enter message" style="width: 100%" rows="6">
                                        </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    @endif
    </div>
    @include('errors.error_layout')
@endsection
