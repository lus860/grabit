@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    {!! Form::open(['method' => 'POST', 'route'=>['rider-update', $user->id]]) !!}
    @csrf
    <div class="ssj-form-wrapper">
        <div class="col-lg-7 col-md-7 form-wrapper">
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Rider Name</label>
                        <input  name="name" type="text" value="{{old('name',$user->name)}}" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Plate number</label>
                        <input name="plate" type="text" value="{{old('plate',$user->plate)}}" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Phone number</label>
                        <input name="phone" type="number" value="{{old('phone',$user->phone)}}" class="form-control" />
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Username</label>
                        <input name="username" type="text" value="{{old('username',$user->username)}}" data-user="{{$user->username}}" class="form-control username" />
                    </div>
                    <div class="form-group col-xs-12 text-for-error hide">
                        <p class="text-danger text-right">
                            This username is used, please use other username
                        </p>
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Password</label>
                        <input name="password" type="password" class="form-control" />
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Online/Offline</label>
                        <input name="status" type="checkbox" @if($user->status) checked @endif style="margin: 10px 5px 5px 5px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/backend/manage/riders" class="btn btn-primary">Go back</a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @include('errors.error_layout')
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        $(function () {
            $('.username').on('change',function () {
                let value = $(this).val();
                value = value.replace(/\s/g, '');
                if(value){
                    let old_user_name = $(this).data('user');
                    if(value !== old_user_name){
                        checkUserName(value);
                    }
                }
            })

            const checkUserName = (username) =>{
                let token = $('input[name=_token]').val();
                $.ajax({
                    url: '/backend/manage/riders/check-user-name',
                    type:'post',
                    data:{username:username,_token:token}
                }).then(function (response) {
                    if (!$('.text-for-error').hasClass('hide')){
                        $('.text-for-error').addClass('hide')
                    }
                    if ($('.username').hasClass('error-input-border')){
                        $('.username').addClass('success-input-border')
                        $('.username').removeClass('error-input-border')
                    }else{
                        $('.username').addClass('success-input-border')
                    }
                    $('.username').addClass('success-input-border')
                }).catch(function (response) {
                    $('.username').addClass('error-input-border')
                    if ($('.text-for-error').hasClass('hide')){
                        $('.text-for-error').removeClass('hide')
                    }
                    if ($('.username').hasClass('success-input-border')){
                        $('.username').removeClass('success-input-border')
                        $('.username').addClass('error-input-border')
                    }else{
                        $('.username').addClass('error-input-border')
                    }
                })
            };
        })
    </script>
@endpush
