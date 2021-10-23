var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;
let inputName = '';
$("body").on("change", ".banner_image", function(e) {
    check_files(e);
    inputName='banner_saved_image';
});

$("body").on("change", ".display_image", function(e){
    check_files(e);
    inputName='display_saved_image';
});
let check_files = (e) =>{
    let files = e.target.files;
    console.log(files);

    let reader;
    let file;
    let url;

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
}
let done = function (url) {
    image.src = url;
    $modal.modal('show');
};

$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
        viewMode: 3,
        preview: '.preview',
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
            $('.'+inputName).val(data.name);
        }
        $modal.modal('hide');
        $('.message-for-upload').removeClass('hide');
        setTimeout(function () {
            $('.message-for-upload').addClass('hide');
        },3000);
    }
});