 @extends('frontend.layouts.customer.customer_app')
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
 				<div class="col-sm-3">
 					<label>บ้านเลขที่ <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="บ้านเลขที่" name="house_no" value="{{$customer->house_no}}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="หมู่บ้าน/อาคาร" name="house_name" value="{{ $customer->house_name }}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>หมู่ที่ <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="หมู่ที่" name="moo" value="{{ $customer->moo }}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>ตรอก/ซอย </label>
 					<input type="text" class="form-control" placeholder="ตรอก/ซอย" name="soi" value="{{ $customer->soi }}" >
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>เขต/อำเภอ <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="เขต/อำเภอ" name="district" value="{{ $customer->district }}" required="">
 				</div>
 				<div class="col-sm-3">
 					<label>แขวง/ตำบล <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="แขวง/ตำบล" name="district_sub" value="{{ $customer->district_sub }}" required="">
 				</div>
 				<div class="col-sm-3">
 					<label>ถนน</label>
 					<input type="text" class="form-control" placeholder="ถนน" name="road" value="{{ $customer->road }}">
 				</div>

 				<div class="col-sm-3">
 					<label>จังหวัด <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="จังหวัด" name="province" value="{{ $customer->province }}" required="">
 				</div>
 			</div>

 			<div class="form-group row">

 				<div class="col-sm-3">
 					<label>รหัสไปษณีย์ <b class="text-danger">*</b></label> 
 					<input type="text" class="form-control form-control-bold" placeholder="รหัสไปษณีย์" name="zipcode" value="{{ $customer->zipcode }}" required="">
 				</div>

 			</div>

 			<div class="form-group row text-right">
 				<label class="col-sm-2"></label>
 				<div class="col-sm-10">
 					<button type="submit" class="btn btn-primary m-b-0">บันทึก</button>
 				</div>
 			</div>
 		</form> 
 	</div>
 	
 	
 </div>
</div>
@endsection

