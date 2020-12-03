 @extends('frontend.layouts.customer.customer_app')
 @section('conten')

 <div class="card">
 	<div class="card-header">
 		<h4>กรอกข้อมูลลงทะเบียนสมาชิกใหม่</h4>
 	</div>
 	<div class="card-block">
 		<form action="{{route('register_new_member')}}" id="register_new_member" method="POST" enctype="multipart/form-data">
 			@csrf
 			<div class="form-group row">
 				<div class="col-sm-2">
 					<label>คำนำหน้าชื่อ </label>
 					<select class="form-control" name="name_prefix">
 						<option value="คุณ">คุณ</option>
 						<option value="นาย">นาย</option> 
 						<option value="นาง">นาง</option>
 						<option value="นางสาว">นางสาว</option>
 					</select>

 				</div>

 				<div class="col-sm-3">
 					<label>ชื่อ <font class="text-danger">*</font></label>
 					<input type="text" class="form-control" placeholder="ชื่อ" name="name_first" value="{{ old('name_first') }}" required="">

 				</div>

 				<div class="col-sm-3">
 					<label>นามสกุล <font class="text-danger">*</font></label>
 					<input type="text" class="form-control" placeholder="นามสกุล" name="name_last" value="{{ old('name_last') }}" required="">
 				</div>


 				<div class="col-sm-4">
 					<label>ชื่อที่ใช้ในธุรกิจ</label>
 					<input type="text" class="form-control" placeholder="ชื่อที่ใช้ในธุรกิจ" name="name_business" value="{{ old('name_business') }}">
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-2">
 					<label>สถานะภาพ</label>
 					<select class="form-control" name="family_status">
 						<option value="1">โสด</option>
 						<option value="2">สมรส</option>
 						<option value="3">หย่าร้าง</option>
 					</select>
 				</div>

 				<div class="col-sm-3">
 					<label>วัน/เดือน/ปีเกิด</label>
 					<input class="form-control" type="date" name="birth_day" value="{{ old('birth_day') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>เลขที่บัตรประชาชน</label>
 					<input class="form-control" type="text" placeholder="เลขที่บัตรประชาชน" name="id_card" value="{{ old('id_card') }}">
 				</div>
 			</div>


 			<div class="form-group row">
 				<div class="col-sm-12">
 					<h4 class="sub-title" >ที่อยู่ที่สะดวกสำหรับการติดต่อและจัดส่งผลิตภัณฑ์ถึงบ้าน (โปรดแจ้งให้บริษัทฯทราบทันทีหากมีการเปลี่ยนแปลงที่อยู่) </h4>
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>บ้านเลขที่</label>
 					<input type="text" class="form-control" placeholder="บ้านเลขที่" name="house_no" value="{{ old('house_no') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>หมู่บ้าน/อาคาร</label>
 					<input type="text" class="form-control" placeholder="หมู่บ้าน/อาคาร" name="house_name" value="{{ old('house_name') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>หมู่ที่</label>
 					<input type="text" class="form-control" placeholder="หมู่ที่" name="moo" value="{{ old('moo') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>ตรอก/ซอย</label>
 					<input type="text" class="form-control" placeholder="ตรอก/ซอย" name="soi" value="{{ old('soi') }}">
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>เขต/อำเภอ</label>
 					<input type="text" class="form-control" placeholder="เขต/อำเภอ" name="district" value="{{ old('district') }}">
 				</div>
 				<div class="col-sm-3">
 					<label>แขวง/ตำบล</label>
 					<input type="text" class="form-control" placeholder="แขวง/ตำบล" name="district_sub" value="{{ old('district_sub') }}">
 				</div>
 				<div class="col-sm-3">
 					<label>ถนน</label>
 					<input type="text" class="form-control" placeholder="ถนน" name="road" value="{{ old('road') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>จังหวัด</label>
 					<input type="text" class="form-control" placeholder="จังหวัด" name="province" value="{{ old('province') }}">
 				</div>
 			</div>

 			<div class="form-group row">

 				<div class="col-sm-3">
 					<label>รหัสไปษณีย์</label>
 					<input type="text" class="form-control" placeholder="รหัสไปษณีย์" name="zipcode" value="{{ old('zipcode') }}">
 				</div>

 			</div>


 			<div class="form-group row">
 				<div class="col-sm-12">
 					<h4 class="sub-title" >ข้อมูลธนาคาร</h4>
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>ชื่อบัญชี</label>
 					<input type="text" class="form-control" placeholder="ชื่อบัญชี" name="bank_account" value="{{ old('bank_account') }}">
 				</div>
 				<div class="col-sm-3">
 					<label>เลขที่บัญชี</label>
 					<input type="text" class="form-control" placeholder="เลขที่บัญชี" name="bank_no" value="{{ old('bank_no') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>ธนาคาร</label>
 					<input type="text" class="form-control" placeholder="ธนาคาร" name="bank_name" value="{{ old('bank_name') }}">
 				</div>

 				<div class="col-sm-3">
 					<label>สาขา</label>
 					<input type="text" class="form-control" placeholder="สาขา" name="bank_branch" value="{{ old('bank_branch') }}">
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>ประเภทบัญชี</label>
 					<br>
 					<div class="form-check form-check-inline">
 						<label class="form-check-label">
 							<input class="form-check-input" type="radio" name="bank_type" id="gender-1" value="ออมทรัพย์" checked="checked"> ออมทรัพย์
 						</label>
 					</div>
 					<div class="form-check form-check-inline">
 						<label class="form-check-label">
 							<input class="form-check-input" type="radio" name="bank_type" id="gender-2" value="กระแสรายวัน"> กระแสรายวัน
 						</label>
 					</div>
 					<span class="messages"></span>
 				</div>
 			</div>


 			<div class="form-group row">
 				<div class="col-sm-12">
 					<h5 class="sub-title" >การติดต่อ/ผู้รับผลประโยชน์</h5>
 				</div>
 			</div>

 			<div class="form-group row">

 				<div class="col-sm-3">
 					<label>โทรศัพท์มือถือ <b class="text-danger">*</b></label>
 					<input type="text" class="form-control" placeholder="โทรศัพท์มือถือ" name="tel_mobile" value="{{ old('tel_mobile') }}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>โทรศัพท์บ้าน</label>
 					<input type="text" class="form-control" placeholder="โทรศัพท์บ้าน" name="tel_home" value="{{ old('tel_home') }}" >
 				</div>

 				<div class="col-sm-3">
 					<label>Email <font class="text-danger">*</font></label>
 					<input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>ผู้สือทอดผลประโยชน์ ชื่อ-นามสกุล</label>
 					<input type="text" class="form-control"  placeholder="ผู้สือทอดผลประโยชน์ ชื่อ-นามสกุล" name="benefit_name" value="{{ old('benefit_name') }}" >
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>ความสัมพันธ์</label>
 					<input type="text" class="form-control"  placeholder="ความสัมพันธ์" name="benefit_relation" value="{{ old('benefit_relation') }}" >
 				</div>

 				<div class="col-sm-3">
 					<label>บัตรประชาชนเลขที่</label>
 					<input type="text" class="form-control"  placeholder="บัตรประชาชนเลขที่" name="benefit_id" value="{{ old('benefit_id') }}" >
 				</div>

 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>Uplind ID</label>

 					<input type="text" class="form-control"   placeholder="Upline ID" value="{{$data->user_name}}" disabled="">
 					<input type="hidden" name="upline_id" value="{{$data->id}}">
          <input type="hidden" name="head_line_id" value="{{$data->head_line_id}}">
        </div>

        <div class="col-sm-3">
          <label>รหัสผู้แนะนำ</label>

          <div class="input-group input-group-button">
            <input type="text" class="form-control" name="introduce" id="introduce" placeholder="รหัสผู้แนะนำ" value="{{ old('introduce') }}" >
            <span class="input-group-addon btn btn-primary" id="basic-addon10" onclick="check_user()">
              <span class=""><i class="icofont icofont-check-circled"></i> Check</span>
            </span>
          </div>
        </div>

        <div class="col-sm-3">
          <label>สายงาน</label>

          @if($data->line_type == 'A') 
          <input type="text" class="form-control"   placeholder="สายงาน" value="A" disabled="">
          <input type="hidden" name="upline_type" value="A">
          @endif

          @if($data->line_type == 'B')
          <input type="text" class="form-control"   placeholder="สายงาน" value="B" disabled="">
          <input type="hidden" name="upline_type" value="B">
          @endif

          @if($data->line_type == 'C')
          <input type="text" class="form-control"   placeholder="สายงาน" value="C" disabled="">
          <input type="hidden" name="upline_type" value="C">
          @endif
        </div>

      </div>

      <div class="form-group row">
       <div class="col-sm-12">
        <h5 class="sub-title" >เอกสารสำหรับการสมัคร</h5>

        <div class="form-group row">
         <div class="col-sm-6">
          <label>บัตรประชาชน</label>
          <input type="file" id="file_1" name="file_1" class="form-control">
        </div>
      </div>

      <div class="form-group row">
       <div class="col-sm-6">
         <label>หน้าบัญชีธนาคาร</label>
         <input type="file" id="file_2"  name="file_2" class="form-control">
       </div>
     </div>
     <div class="form-group row">
      <div class="col-sm-6"> 
       <label>ลายเซ็น</label>
       <input type="file" id="file_3" name="file_3" class="form-control">
     </div>
   </div>

 </div>


 <div class="form-group row text-right">
   <label class="col-sm-2"></label>
   <div class="col-sm-10"> 
    <button type="submit" class="btn btn-primary m-b-0">Submit</button>
  </div>
</div>
</form> 
</div>


</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
  function check_user(){
    var user_name = $('#introduce').val();
    if(user_name == '' || user_name == null){
      Swal.fire({
        icon: 'error',
        title: 'User is Null',
      })

    }else{
      $.ajax({
        url: '{{ route('check_user') }}',
        type: 'GET',
        data: {user_name:user_name},
      })
      .done(function(data) {

        if(data['status'] == 'fail'){
          
          Swal.fire({
            icon: 'error',
            title: 'UserName is Null',
          })

        }else {

            Swal.fire({
            icon: 'success',
            title: data['data']['business_name']+' ('+data['data']['user_name']+')',
          })

        }

        console.log(data);
      })  
      .fail(function() {
        console.log("error");
      })
      
    }
  }

  $('#file_1').change( function () {
    var fileExtension = ['jpg','png','pdf'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }
 });
  $('#file_2').change( function () {
    var fileExtension = ['jpg','png','pdf'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }
 });

  $('#file_3').change( function () {
    var fileExtension = ['jpg','png','pdf'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }
 });
</script>
@endsection