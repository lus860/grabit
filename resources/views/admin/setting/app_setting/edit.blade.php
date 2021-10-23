@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <form action="{{url('/backend/app-settings/edit')}}" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        @include('errors.error_layout')
            @if(isset($app_settings) && $app_settings->count())
            <div class="col-lg-5 col-md-7 form-wrapper">
                <div class="row form-row">
                    @foreach($app_settings as $item)
                        @php
                            $min_ios = ($item->keyword =='min_ios') ? old('min_ios')??$item->description:'';
                            $min_android = ($item->keyword =='min_android') ? old('min_android')??$item->description:'';
                            $maintenance_mode = ($item->keyword =='maintenance_mode') ? old('maintenance_mode')??$item->description:'';
                        @endphp
                        @if($item->keyword =='min_ios')
                            <div class="form-group col-lg-5 col-md-5 ">
                                <label class="delivery-label-other">Minimum iOS App</label>
                            </div>
                            <div class="form-group col-lg-7 col-md-7 text-center">
                                <label for="">Version</label>
                                <input  name="min_ios" value="{{$min_ios}}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                        @if($item->keyword =='min_android')
                            <div class="form-group col-lg-5 col-md-5">
                                <label class="delivery-label-other">Minimum Android App</label>
                            </div>
                            <div class="form-group col-lg-7 col-md-7 text-center">
                                <label for="">Version</label>
                                <input  name="min_android" value="{{$min_android}}"
                                        type="text" class="form-control">
                            </div>
                        @endif
                        @if($item->keyword =='maintenance_mode')
                            <div class="form-group col-lg-4 col-md-4">
                                <label class="delivery-label-other">Maintenance Mode</label>
                            </div>
                            <div class="form-group col-lg-8 col-md-8 text-center">
                                <input  name="maintenance_mode" @if($maintenance_mode) checked @endif
                                        type="checkbox" class="form-control">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="form-group col-lg-12 col-md-12">
                    <button type="button" class="btn btn-primary add-blocklist">Add Block list</button>
                    <div class="clearfix"></div>
                    <div class="hide block_list">
                        <lebel>Message for blocked</lebel>
                        <input type="text" name="message" class="form-control">
                        <select  name="block_list" id="block_list" class="form-control example-getting-started"  disabled></select>
                    </div>
                </div>
            </div>
            @endif
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{url('/backend/app-settings')}}" type="submit" class="btn btn-primary">Go back</a>
                </div>
            </div>
        </div>
    </div>
    </form>
    @include('errors.error_layout')
@endsection
@push('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        let values_select = {!! json_encode($users) !!};
        let selected_values = {!! json_encode($block_list) !!};
        let options='';
        let newArray=[];
        for(let u in values_select){
            let flag=false;
            for(let i in selected_values){
                if(selected_values[i] == values_select[u].id){
                    flag=true;
                }
            }
            if (!flag){
                newArray[u]=values_select[u];
            }
        }
        newArray.map((value,index)=>{
                options+='<option value="'+value.id+'">'+value.name+'('+value.phone+')'+'</option>'
        });
        $('.add-blocklist').on('click',function () {
            if($('.block_list').hasClass('hide')){
                $('.block_list').removeClass('hide');
                $('.block_list select').prop('disabled',false);
                $('.block_list select').html(options);
                $('button .multiselect.dropdown-toggle ').prop('disabled',false);
            }else{
                $('.block_list').addClass('hide');
                $('.block_list select').prop('disabled','disabled');
            }
            $('#block_list').select2('rebuild');
        });
        $(function () {
            $('.example-getting-started').select2();
        })
    </script>
@endpush

