@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('files/assets/css/croppie.css')}}">
<style type="text/css">
    .boxContainer {
        display: table;
        background-color: gray;
        width: 300px;
        height: 300px;
    }
    .box {
    display: table-cell;
    text-align: center;
    vertical-align: bottom;
}
.text-middle {
    vertical-align: middle !important;
}
</style>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="sub-title"><i class="fa fa-user"></i> ข้อมูลส่วนตัว</h4>
            </div>
            <div class="card-block">
               <form action="" method="POST" enctype="multipart/form-data" id="addemployee">
                @csrf
                <div class="modal-body">
                   
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">รูปประจำตัว</label>
                        <div class="col-sm-4">
                            <div class="col-md-3 boxContainer" id="div-image">
                                <input type="file" name="img" class="input-image" id="img" style="display: none;">
                                <div class="box text-middle">
                                    <button class="btn btn-outline-primary" style="color: #fff" type="button" onclick="document.getElementById('img').click()"><i class="fa fa-camera"></i></button>
                                    <input type="hidden" name="imgBase64" value="">
                                </div>
                            </div>
                        </div>
                        <div 
                        class="col-sm-6 row" style="display: none;" id="div-crop">
                        <div class="col-md-12" style="padding-bottom: 10px;">
                            <button class="btn btn-success crop_image form-control" type="button">ตกลง</button>
                        </div>
                        <div id="image_demo" class="col-md-12">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-6">
                                <input type="text" name="name" class="form-control" placeholder="ชื่อจริง...">
                            </div>
                            <div class="col-6">
                                <input type="text" name="lname" class="form-control" placeholder="นามสกุล...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" name="phone" class="form-control" placeholder="เบอร์โทรศัพท์...">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">แผนก</label>
                    <div class="col-sm-4">
                        <select name="department" class="form-control">
                            <option selected disabled>เลือกแผนก</option>
                            <optgroup label="ฝ่ายผู้บริหาร">
                                <option value="1">ประธานเจ้าหน้าที่บริหาร</option>
                                <option value="2">รองประธานเจ้าหน้าที่บริหาร</option>
                                <option value="3">ผู้อำนวยการฝ่ายรถขนส่งพนักงาน</option>
                            </optgroup>
                            <optgroup label="ฝ่ายบุคคล">
                                <option value="4">หัวหน้าแผนกสรรหา</option>
                                <option value="5">เจ้าหน้าที่สรรหา</option>
                                <option value="6">หัวหน้าแผนกอบรม</option>
                                <option value="7">เจ้าหน้าที่อบรม</option>
                            </optgroup>
                            <optgroup label="ฝ่ายจัดรถ">
                                <option value="8">หัวหน้าโซน</option>
                                <option value="9">สตาฟจัดรถ</option>
                                <option value="10">พนักงานขับรถ</option>
                            </optgroup>
                            <optgroup label="ฝ่ายลูกค้าสัมพันธ์/QC">
                                <option value="11">ผู้จัดการฝ่ายลูกค้าสัมพันธ์/QC</option>
                                <option value="12">เจ้าหน้าที่ฝ่ายลูกค้าสัมพันธ์/QC</option>
                            </optgroup>
                            <optgroup label="ฝ่ายเอกสารงานสัญญา">
                                <option value="13">ผู้จัดการฝ่ายเอกสารงานสัญญา</option>
                                <option value="14">เจ้าหน้าที่เอกสารงานสัญญา</option>
                            </optgroup>
                            <optgroup label="ฝ่ายธุรการงานขนส่ง">
                                <option value="15">ผู้จัดการฝ่ายธุรการงานขนส่ง</option>
                                <option value="16">หัวหน้าฝ่ายธุรการงานขนส่ง</option>
                                <option value="17">ผู้ช่วยฝ่ายธุรการงานขนส่ง</option>
                                <option value="18">เจ้าหน้าที่ฝ่ายธุรการงานขนส่ง</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">วัน/ เดือน / ปี เกิด</label>
                    <div class="col-sm-4">
                        <input type="text" name="birthday" class="form-control datepicker" placeholder="วัน/เดือน/ปี">
                    </div>
                    <label class="col-sm-2 col-form-label">วันเริ่มทำงาน</label>
                    <div class="col-sm-4">
                        <input type="text" name="startworkat" class="form-control datepicker" placeholder="วัน/เดือน/ปี">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">เอกสาร</label>
                
                </div>
                <br>
                <h4 class="sub-title">รหัสประจำตัวพนักงานและรหัสผ่าน</h4>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">รหัสประจำตัวพนักงาน<span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="number" name="employee_number" class="form-control" placeholder="รหัสประจำตัวพนักงาน...">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">รหัสผ่าน<span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน...">
                    </div>
                </div>
            </div>
       
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary" > Update </button>
    </div>
     </form>
</div>
</div>
</div>

@endsection
@section('js')
<script  src="{{asset('files/assets/js/croppie.js')}}"></script>
<script>
    $( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
    // Add Img Profile preview
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
            type: 'square' //circle
        },
        boundary: {
            width: 350,
            height:350
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
            $('#div-image').css('background-image','url("' + response + '")');
            $('input[name="imgBase64"]').val(response);
            $('#div-crop').hide();
        })
    });
</script>
@endsection


