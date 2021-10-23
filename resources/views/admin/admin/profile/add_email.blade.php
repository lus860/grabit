@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/')}}/backend/admin/add-emails" enctype="multipart/form-data" method="post">
        @csrf
        <div class="ssj-form-wrapper">
            @if($errors->any())
                <h3 style="color: red;">Error: </h3>
                <p style="color: red;">{{$errors->first()}}</p>
            @endif
            @if(isset($data) && count($data))
                <div class="row">
                    @foreach($data as $email)
                        <div class="item-option-container col-md-12">
                            <div class="form-group col-lg-6 col-md-6">
                                <input placeholder="Enter email" name="email[]" value="{{$email}}" type="text" class="form-control" />
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
            <div class="col-lg-7 col-md-7 form-wrapper">
                <h3>Add new email</h3>
                <div class="row form-row the-menu-items">
                    <div id="original-item-content">
                        <div class="row item-option-container">
                            <div class="form-group col-lg-8 col-md-8">
                                <input placeholder="Enter email" name="email[]" type="text" class="form-control" />
                            </div>
                            <div class="form-group col-lg-3 col-md-3">
                                <div class="add-button add-category-name" onclick="add_item()"
                                     style="display:inline-block; vertical-align: middle; cursor:pointer">
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="hidden">
        <div id="example">
            <div class="form-group col-lg-8 col-md-8">
                <input placeholder="Enter email" name="email[]" type="text" class="form-control" />
            </div>
            <div class="form-group col-lg-3 col-md-3">
                <div class="add-button add-category-name" onclick="add_item()"
                     style="display:inline-block; vertical-align: middle; cursor:pointer">
                    <i class="fa fa-plus"></i>
                </div>
                <div class="group-option-delete item-number" onclick="delete_item(this)"
                     style="display:inline-block; vertical-align: middle; cursor:pointer">
                    <i class="fa fa-trash-o"></i>
                </div>
            </div>
        </div>
    </div>
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
