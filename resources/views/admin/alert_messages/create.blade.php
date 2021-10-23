@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/backend/send-alert-messages')}}" method="post">
        @csrf
        <div class="container" style="min-height: 50vh">
            @include('errors.error_layout')
            <div class="row justify-content-start">
                <div class="col-md-2 col-xs-12 text-center">
                    <label class="type-label">Alert type</label>
                    <div class="clearfix"></div>
                    <select name="type" id="" class="custom-select alert-type">
                        <option value="" disabled selected>Select Alert type</option>
                        <option value="phone">SMS</option>
                        <option value="email">Email</option>
                        <option value="notification">App Notification</option>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12 text-center">
                    <label class="type-label">Select target</label>
                    <div class="clearfix"></div>
                    <select name="target" id="" class="custom-select target">
                        <option value="" disabled selected>Select target</option>
                        <option value="1">User</option>
                        <option value="2">All Users</option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4 text-center choose-users hide">
                    <label class="messages-label">Select users</label>
                    <select id="users_list" name="users[]" multiple class="custom-select example-getting-started" disabled>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4 text-center send-sms hide">
                    <label class="messages-label">Send sms</label>
                    <input type="text" name="sms" max="159" class="input-for-messages">
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4 text-center send-email hide">
                    <label class="messages-label">Title Email</label>
                    <input type="text" name="email_title"  class="input-for-messages">
                    <label class="messages-label">Email message</label>
                    <input type="text" name="email_message" class="input-for-messages">
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4 text-center send-notification hide">
                    <label class="messages-label">Title Notification</label>
                    <input type="text" name="notification_title" class="input-for-messages">
                    <label class="messages-label">Notification message</label>
                    <input type="text" name="notification_message" class="input-for-messages">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ url()->previous() }}" type="submit" class="btn btn-primary">Go back</a>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        let get_users = ()=>{
            let alert_type = $('.alert-type').val();
            let custom_select = $('.target').val();
            $.ajax({
                url: '/backend/get-data-for-alert-message',
                type: "POST",
                data:{
                    '_token':$('input[name=_token]').last().val(),
                    'alert_type':alert_type,
                    'custom_select':custom_select}
            }).done(function (success) {
                if(success.data){
                    let option='';
                    for (let i in success.data){
                        option += `<option value="${success.data[i].id}">${success.data[i].name}(${success.data[i].phone})</option>`;
                    }
                    $('.choose-users select').html(option);
                    $('#users_list').select2('rebuild');

                }
                // $('#ajaxproducts').html(data);
            }).fail(function (data) {
                // alert('Products could not be loaded.');
                console.log(data.responseJSON.message,2)
            });
            // console.log($('input[name=_token]').last().val());
        },
        show_filed = ()=>{
            let alert_type = $('.alert-type').val();
            change_phone_filed(alert_type);
            change_email_filed(alert_type);
            change_notification_filed(alert_type);
        },

        change_phone_filed = (value)=>{
            if(value == 'phone'){
                return $('.send-sms').removeClass('hide');
            }
            if($('.send-sms').hasClass('hide')){
                return ;
            }
            $('.send-sms').addClass('hide');
        },
        change_email_filed = (value)=>{
            if(value == 'email'){
                return $('.send-email').removeClass('hide');
            }
            if($('.send-email').hasClass('hide')){
                return ;
            }
            $('.send-email').addClass('hide');
        },
        change_notification_filed = (value)=>{
            if(value == 'notification'){
                return $('.send-notification').removeClass('hide');
            }
            if($('.send-notification').hasClass('hide')){
                return ;
            }
            $('.send-notification').addClass('hide');
        }

        {{--let values_select = {!! json_encode($users) !!};--}}
        {{--let selected_values = {!! json_encode($block_list) !!};--}}
        {{--let options='';--}}
        {{--let newArray=[];--}}
        {{--for(let u in values_select){--}}
        {{--    let flag=false;--}}
        {{--    for(let i in selected_values){--}}
        {{--        if(selected_values[i] == values_select[u].id){--}}
        {{--            flag=true;--}}
        {{--        }--}}
        {{--    }--}}
        {{--    if (!flag){--}}
        {{--        newArray[u]=values_select[u];--}}
        {{--    }--}}
        {{--}--}}
        // newArray.map((value,index)=>{
        //     options+='<option value="'+value.id+'">'+value.name+'('+value.phone+')'+'</option>'
        // });
        // $('.add-blocklist').on('click',function () {
        //     if($('.block_list').hasClass('hide')){
        //         $('.block_list').removeClass('hide');
        //         $('.block_list select').prop('disabled',false);
        //         $('.block_list select').html(options);
        //         $('button .multiselect.dropdown-toggle ').prop('disabled',false);
        //     }else{
        //         $('.block_list').addClass('hide');
        //         $('.block_list select').prop('disabled','disabled');
        //     }
            $('#block_list').select2('rebuild');
        // });

        $(function () {
            $('.alert-type').on('change',function () {
                show_filed();
            });
            $('.target').on('change',function () {
                if($(this).val() == 1){
                    $('.choose-users').removeClass('hide');
                    $('.choose-users select').prop('disabled',false);
                    get_users();
                    // $('button .multiselect.dropdown-toggle ').prop('disabled',false);
                }else{
                    $('.choose-users').addClass('hide');
                    $('.choose-users select').prop('disabled','disabled');
                }
                $('#users_list').select2('rebuild');
            });

            $('#users_list').select2();
        })
    </script>
@endpush

