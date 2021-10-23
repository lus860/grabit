@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    @include('errors.error_layout')
    @php
        $select_courier=false;
        if(isset($courier) && $loyalty->id==$courier->id){
        $select_courier = true;
        }
    @endphp
    <div class="message-for-upload hide">
        <div class="alert alert-success">
            File is uploaded
        </div>
    </div>
    {!! Form::open(['method' => 'POST', 'route'=>['loyalty-update', $loyalty->id]]) !!}
        @csrf
        <div class="ssj-form-wrapper">
            <div class="col-lg-7 col-md-7 form-wrapper">
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Business name</label>
                            <select id="vendor_list" name="vendor_id"  class="form-control">
                                <option selected disabled>Select Business name</option>
                                @if((isset($courier) && $loyalty->id==$courier->id) || (!isset($courier) && !$courier))<option value="courier" @if($select_courier) selected @endif>Courier</option> @endif
                            @if($vendors->count())
                                    @foreach($vendors as $vendor)
                                        <option value="{{$vendor->id}}" @if($vendor->id == $loyalty->vendor_id) selected @endif>{{$vendor->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Total spend</label>
                            <input placeholder="Write Total spend" value="{{$loyalty->spend}}" name="spend" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Redemption amount</label>
                            <input placeholder="Write Redemption amount" value="{{$loyalty->redemption}}" name="redemption" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Status</label><br>
                            <input placeholder="Write Status" @if($loyalty->status == 1) checked @endif name="status" type="checkbox" style="width: 20px;height: 20px;"/>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Image</label>
                        <input name="image" type="file" class="form-control image" />
                        <input type="hidden" class="saved-image" name="saved_image" value="">
                    </div>
                    @if($loyalty->image != null)
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Current Image</label>
                            <img src="{{$loyalty->image}}" width="120" />
                        </div>
                    @endif
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
    {!! Form::close() !!}
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
    </style>
@endpush
@push('js')
    <script>

        $(function () {
            $('#vendor_list').select2();
            $('#vendor_list').select2('rebuild');
        })

        var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;

        $("body").on("change", ".image", function(e){
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                console.log(12313)
                $modal.modal('show');
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                viewMode: 3,
                preview: '.preview',
                crop(event) {
                }
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        $("#crop").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    sendAjax(base64data);
                }
            });
        })

        let sendAjax = (base64data)=>$.ajax({
            type: "post",
            dataType: "json",
            url: '/image-cropper/upload',
            data: {'_token': $('input[name="_token"]').val(), 'image': base64data},
            success: function(data){
                if(data.success){
                    $('.saved-image').val(data.name);
                }
                $modal.modal('hide');
                $('.message-for-upload').removeClass('hide');
                setTimeout(function () {
                    $('.message-for-upload').addClass('hide');
                },3000);
            }
        });

    </script>
@endpush
