 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 <link rel="stylesheet" href="{{asset('frontend/bower_components/select2/css/select2.min.css')}}" />
 <!-- Multi Select css -->
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/multiselect/css/multi-select.css')}}">
 @endsection
 @section('conten')
 <div class="card">
 	<div class="card-header">
 		<h4>แก้ไขที่อยู่การจัดส่ง</h4>
 	</div>
 	<div class="card-block">
 		<form action="{{route('edit_address')}}" method="POST">
 			@csrf
 		{{-- 	<div class="form-group row">
 				<div class="col-sm-12">
 					<h4 class="sub-title" >ที่อยู่ที่สะดวกสำหรับการติดต่อและจัดส่งผลิตภัณฑ์ถึงบ้าน (โปรดแจ้งให้บริษัทฯทราบทันทีหากมีการเปลี่ยนแปลงที่อยู่) </h4>
 				</div>
 			</div> --}}

 			<div class="form-group row">
 				<div class="col-sm-2">
 					<label>บ้านเลขที่ <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="บ้านเลขที่" name="house_no" value="{{$customer->house_no}}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="หมู่บ้าน/อาคาร" name="house_name" value="{{ $customer->house_name }}" required="">
 				</div>

 				<div class="col-sm-2">
 					<label>หมู่ที่ <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="หมู่ที่" name="moo" value="{{ $customer->moo }}" required="">
 				</div>

 				<div class="col-sm-2">
 					<label>ตรอก/ซอย </label>
 					<input type="text" class="form-control" placeholder="ตรอก/ซอย" name="soi" value="{{ $customer->soi }}" >
 				</div>


 				<div class="col-sm-3">
 					<label>ถนน</label>
 					<input type="text" class="form-control" placeholder="ถนน" name="road" value="{{ $customer->road }}">
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>จังหวัด <font class="text-danger">*</font></label>
 					<select class="js-example-basic-single col-sm-12" id="province" name="province" required="">
 						<option value="{{ $customer->provinces_id }}" >{{ $customer->provinces_name }}</option> 
 						@foreach($provinces as $value_provinces)
 						<option value="{{ $value_provinces->id }}">{{ $value_provinces->name_th }}</option>
 						@endforeach
 					</select>
 				</div>
 				<div class="col-sm-3">
 					<label>เขต/อำเภอ <font class="text-danger">*</font></label>
 					<select class="js-example-basic-single col-sm-12" name="district" id="district" required="">
 						<option value="{{ $customer->amphures_id }}">{{ $customer->amphures_name }}</option>
 					</select>
 					{{-- <input type="text" class="form-control" placeholder="เขต/อำเภอ" id="district" name="district" value="{{ old('district') }}"> --}}
 				</div>

 				<div class="col-sm-3">
 					<label>แขวง/ตำบล <font class="text-danger">*</font></label>
 					<select class="js-example-basic-single col-sm-12" name="district_sub" id="district_sub" required=""> 
 						<option value="{{ $customer->district_name }}">{{ $customer->district_name }}</option>
 						{{-- <input type="text" class="form-control" placeholder="แขวง/ตำบล" id="district_sub" name="district_sub" value="{{ old('district_sub') }}"> --}}
 					</select>
 				</div>

 			{{-- 	<div class="col-sm-3">
 					<label>จังหวัด <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="จังหวัด" name="province" value="{{ $customer->province }}" required="">
 				</div> --}}


 				<div class="col-sm-3">
 					<label>รหัสไปษณีย์</label>
 					<input type="text" class="form-control" placeholder="รหัสไปษณีย์" id="zipcode" name="zipcode" value="{{ $customer->zipcode }}">
 				</div>
 			</div>

 			

 			<div class="form-group row text-right">
 				<label class="col-sm-2"></label>
 				<div class="col-sm-10">
 					<button type="button" class="btn btn-primary m-b-0" data-toggle="modal" data-target="#default-Modal">บันทึก</button>
 				</div>

 			</div>
 			<div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
 				<div class="modal-dialog" role="document">
 					<div class="modal-content">
 						<div class="modal-header">
 							<h4 class="modal-title">กรุณายืนยันรหัสผ่านเพื่อเปลี่ยนแปลงที่อยู่</h4>
 							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
 								<span aria-hidden="true">&times;</span>
 							</button>
 						</div>
 						<div class="modal-body">
 							<div class="row text-center">
 								<div class="col-sm-2"> 
 								</div>
 								<div class="col-sm-8">
 									<div class="input-group input-group-primary">
 										<span class="input-group-addon">
 											<i class="fa fa-lock"></i>
 										</span>
 										<input type="password" name="password" class="form-control" placeholder="Password" required="">
 									</div>
 								</div>
 								<div class="col-sm-2">
 								</div>
 							</div>
 						</div>
 						<div class="modal-footer">
 							<button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
 							<button type="submit" class="btn btn-primary waves-effect waves-light ">ยืนยันรหัสผ่าน</button>
 						</div>
 					</div>
 				</div>
 			</div>
 		</form> 
 	</div>
 	
 	
 </div>
</div>
@endsection
@section('js')
<!-- Select 2 js -->
<script  src="{{asset('frontend/bower_components/select2/js/select2.full.min.js')}}"></script>
<!-- Multiselect js -->
<script  src="{{asset('frontend/bower_components/bootstrap-multiselect/js/bootstrap-multiselect.js')}}">
</script>
<script  src="{{asset('frontend/bower_components/multiselect/js/jquery.multi-select.js')}}"></script>
<script  src="{{asset('frontend/assets/js/jquery.quicksearch.js')}}"></script>
<!-- Custom js -->
<script  src="{{asset('frontend/assets/pages/advance-elements/select2-custom.js')}}"></script>
<script type="text/javascript">
  $('#province').change(function() {
    var id_province = $(this).val();
     
    $.ajax({
     async : false,
     type: "get",
     url: "{{ route('location') }}",
     data: {id:id_province,function:'provinces'},
     success: function(data){
      $('#district').html(data); 
      $('#district_sub').val('');  
        // $('#zipcode').val(''); 
      }
    });

  });

  $('#district').change(function() {
    var id_district = $(this).val();
    
    $.ajax({
      async : false,
      type: "get",
      url: "{{ route('location') }}",
      data: {id:id_district,function:'district'},
      success: function(data){
        $('#district_sub').html(data);  
      }
    });
  });

  $('#district_sub').change(function() {
    var id_district_sub = $(this).val();
    $.ajax({
      type: "get",
      url: "{{ route('location') }}",
      data: {id:id_district_sub,function:'district_sub'},
      success: function(data){
        $('#zipcode').val(data);
      }
    });

  });
</script>
@endsection

