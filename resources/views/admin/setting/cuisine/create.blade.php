@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="message-for-upload hide">
        <div class="alert alert-success">
            File is uploaded
        </div>
    </div>
    <form action="{{url('/')}}/backend/cuisines" enctype="multipart/form-data" method="post">
        @csrf
    <div class="ssj-form-wrapper">
        <div class="col-lg-7 col-md-7 form-wrapper">
            <h3>Cuisine Detail</h3>
            <div class="row form-row">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Cuisine Name</label>
                        <input placeholder="Eg. Pizzas" value="" required name="name" type="text" class="form-control" />
                    </div>

                    <div class="form-group col-lg-4 col-md-6">
                        <label>Image</label>
                        <input required name="image" type="file" class="form-control image" />
                        <input type="hidden" class="saved-image" name="saved_image" value="">
                    </div>

                    <div class="form-group col-lg-2 col-md-6">
                        <label>Is Top</label>
                        <input name="top" type="checkbox" class="form-control" />
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
        var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;

        $("body").on("change", ".image", function(e){
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
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
                // aspectRatio: 1,
                viewMode: 3,
                preview: '.preview',
                // minContainerWidth:1017,
                crop(event) {
                    // $('.preview-width input').val(parseInt(event.detail.width));
                    // $('.preview-height input').val(parseInt(event.detail.height));
                    // correct = false;
                    // if(parseInt(event.detail.width)<1017){
                    //     correct = true;
                    // }
                    // console.log(event.detail.x);
                    // console.log(event.detail.y);
                    // console.log(event.detail.width);
                    // console.log(event.detail.height);
                    // console.log(event.detail.rotate);
                    // console.log(event.detail.scaleX);
                    // console.log(event.detail);
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
