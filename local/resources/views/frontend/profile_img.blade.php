@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/css/croppie.css')}}">
@endsection
<div class="row simple-cards users-card">
    <div class="col-md-5 col-xl-5">
     <form action="{{route('update_img_profile')}}" method="POST" enctype="multipart/form-data" id="addemployee">
        @csrf
        <div class="card user-card">
           

            <div class="card-header-img">
              {{-- <div class="col-md-3 boxContainer" id="div-image"></div> --}}
              @if(Auth::guard('c_user')->user()->profile_img)
         
              <img class="img-fluid img-radius input-image" width="300" id="div-image" src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" alt="card-img">
              @else
              
               <img class="img-fluid img-radius input-image" width="300" id="div-image" src="{{asset('local/public/images/ex.png')}}" alt="card-img">
              @endif

              <input type="file" name="img" class="input-image" id="img" style="display: none;">
              <input type="hidden" name="imgBase64" value="">
              <h5>ฮัพโหลดรูปโปรไฟล์</h5>
                <hr>
             {{--    <h5>abc123@domain.com</h5>
                <h6>Systems Administrator</h6> --}}
            </div>

            <div>
                <button type="button" onclick="document.getElementById('img').click()" class="btn btn-primary waves-effect waves-light m-r-15"><i class="icofont icofont-plus m-r-5"></i> เลือกรูปภาพ</button>
                <button type="submit" id="upload" class="btn btn-success waves-effect waves-light"><i class="fa fa-upload m-r-5"></i> อัพโหลดรูปภาพ</button>

            </div>
        </div>
    </form>
</div>
<div class="col-md-6 col-xl-6" >
    <div class="card user-card" style="display: none;" id="div-crop">
        <div class="card-header-img">
            <div id="image_demo" class="col-md-12"></div>

        </div>

        <div class="row">
            <div class="col-md-12 m-10">
               <button type="button" class="btn btn-primary waves-effect waves-light crop_image btn-block"><i class="fa fa-image m-r-5"></i> ยืนยัน</button>
           </div>

       </div>
   </div>
</div>

</div>

@endsection
@section('js')
<script  src="{{asset('frontend/assets/js/croppie.js')}}"></script>
<script>
    // $( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
    // Add Img Profile preview
    document.getElementById("upload").disabled = true;

    function addImgProfilePreview() {
        var $preview = $('#addpreviewProfile').empty();
        if (this.files) $.each(this.files, readAndPreview);
        function readAndPreview(i, file) {
            if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
                return alert(file.name +" is not an image");
            }
            var reader = new FileReader();
            $(reader).on("load", function() {
                $preview.append($("<img>", {src:this.result, height:150}));
            });
            reader.readAsDataURL(file);
        }
    }
    $('#addimgprofile').on("change", addImgProfilePreview);

    // crop รูปภาพ
    $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
            width: 300,
            height: 300,
            type: 'circle' //circle
        },
        boundary: {
            width: 300,
            height:300
        }
    });
    $(document).on('change', '.input-image', function () {
        var reader = new FileReader();
        reader.onload = function (event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#div-crop').show();
    });

    $('.crop_image').click(function (event) {
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (response) {
            //$('#div-image').css('background-image','url("' + response + '")');
            $('#div-image').attr('src',response);
            $('input[name="imgBase64"]').val(response);
            $('#div-crop').hide();
            document.getElementById("upload").disabled = false;
        })
    });
</script>
@endsection


