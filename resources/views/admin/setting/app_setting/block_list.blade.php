@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="/backend/app-settings/blok-list/remove" enctype="multipart/form-data" method="post">
        @csrf
        <div class="ssj-form-wrapper">
            @if($errors->any())
                <h3 style="color: red;">Error: </h3>
                <p style="color: red;">{{$errors->first()}}</p>
            @endif
            @if(isset($users) && count($users))
                <div class="row">
                    @foreach($users as $key=>$user)
                        <div class="item-option-container col-md-12">
                            <div class="form-group col-lg-6 col-md-6">
                                <p>{{$user->name.'('.$user->phone.')'}}</p>
                                <p>Message - {{$user->message}}</p>
                                <input name="users[{{$key}}][id]" value="{{$user->id}}" type="hidden" >
                                <input name="users[{{$key}}][message]" value="{{$user->message}}" type="hidden" >
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <div class="group-option-delete item-number" onclick="delete_item(this)"
                                     style="display:inline-block; vertical-align: middle; cursor:pointer">
                                    <i class="fa fa-trash-o"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{url('/backend/app-settings')}}" class="btn btn-primary">Go back</a>

                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('errors.error_layout')
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        let add_item=()=>{
            let added_form = document.getElementById('example');
            let original_content = document.getElementById('original-item-content');
            let a = document.createElement('div');

            a.setAttribute('class','row item-option-container');
            a.innerHTML=added_form.innerHTML
            original_content.appendChild(a);
        };

        let delete_item =(event)=>{
            event.closest('.item-option-container').remove();
        };

    </script>
@endpush
