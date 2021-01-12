 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 @endsection
 @section('conten')
 <?php //dd($customer); ?>
 
 <div class="row"> 
 	<div class="col-md-8 col-sm-12">
 		<form action="{{route('payment_submit')}}" method="POST" enctype="multipart/form-data">
 			@csrf
 			<input type="hidden" name="type" value="{{ $bill['type'] }}">
 			<input type="hidden" name="vat" value="{{ $bill['vat'] }}">
 			<input type="hidden" name="shipping" value="{{ $bill['shipping'] }}">
 			<input type="hidden" name="price_vat" value="{{ $bill['price_vat'] }}">
 			<input type="hidden" name="price" value="{{ $bill['price'] }}">
 			<input type="hidden" name="p_vat" value="{{ $bill['p_vat'] }}">
 			<input type="hidden" name="price_total" value="{{ $bill['price_total'] }}">
 			<input type="hidden" name="pv_total" value="{{ $bill['pv_total'] }}">
 			<input type="hidden" name="price_total_type5" value="{{$bill['price_total_type5'] }}">
 			
 			<!-- Choose Your Payment Method start -->
 			<div class="card card-border-success">
 				<div class="card-header p-3">
 					@if($bill['type'] == 1)
 					<h5>รายการสั่งซื้อเพื่อทำคุณสมบัติ</h5>
 					@elseif($bill['type'] == 2)
 					<h5>รายการสั่งซื้อเพื่อรักษาคุณสมบัติรายเดือน</h5>
 					@elseif($bill['type'] == 3)
 					<h5>รายการสั่งซื้อเพื่อรักษาคุณสมบัติท่องเที่ยว</h5>
 					@elseif($bill['type'] == 4)
 					<h5>รายการสั่งซื้อเพื่อเติม AiPocket</h5>
 					@elseif($bill['type'] == 5)
 					<h5>Gift Voucher</h5>
 					@elseif($bill['type'] == 6)
 					<h5>คอร์สอบรม</h5>
 					@else
 					<h5 class="text-danger">ไม่ทราบจุดประสงค์การสั่งซื้อ</h5>
 					@endif  
 					{{-- <div class="card-header-right"></div> --}}
 				</div>
 				<div class="card-block payment-tabs">
 					<ul class="nav nav-tabs md-tabs" role="tablist">
 						@if($bill['type'] == 6)
 						<li class="nav-item">
 							<a class="nav-link active" data-toggle="tab" id="nav_card" href="#credit-card" role="tab">ชำระเงิน</a>
 							<div class="slide"></div>
 						</li>
 						@else
 						<li class="nav-item">
 							<a class="nav-link active" id="nav_address" data-toggle="tab" href="#address" role="tab">ที่อยู่การจัดส่ง</a>
 							<div class="slide"></div>
 						</li>
 						<li class="nav-item">
 							<a class="nav-link" data-toggle="tab" id="nav_card" href="#credit-card" role="tab">ชำระเงิน</a>
 							<div class="slide"></div>
 						</li>
 						@endif

 						

 					{{-- <li class="nav-item">
 						<a class="nav-link" data-toggle="tab" href="#debit-card" role="tab">Debit Card</a>
 						<div class="slide"></div>
 					</li> --}}


 				</ul>
 				<div class="tab-content m-t-15">

 					@if($bill['type'] == 6)
 					<div class="tab-pane" id="address" role="tabpanel">
 					@else
 					<div class="tab-pane active" id="address" role="tabpanel">
 					@endif
 					
 						<div class="card-block p-b-0" style="padding:10px">

 							<div class="form-radio">
 								<div class="row">
 									<div class="radio radio-inline">
 										<label>
 											<input type="radio" onchange="sent_address()" name="receive" value="sent_address" checked="checked">
 											<i class="helper"></i><b>จัดส่ง</b> 
 										</label>
 									</div>
 									<div class="radio radio-inline">
 										<label>
 											<input type="radio" onchange="receive_office()" name="receive" value="receive_office">
 											<i class="helper"></i><b>รับที่สาขา</b>
 										</label>
 									</div>

 								</div>
 							</div>
 							
 							<div id="sent">
 								<div class="row m-t-5">
 									<div class="col-md-6 col-sm-6 col-6">
 										<label>ชื่อผู้รับ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="บ้านเลขที่" name="name" value="{{$customer->prefix_name}} {{ $customer->first_name }} {{ $customer->last_name }}" required="">
 									</div>
 									<div class="col-md-6 col-sm-6 col-6">
 										<label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="เบอร์โทรศัพท์" name="tel_mobile" value="{{ $customer->tel_mobile }}" required="">
 									</div>
 								</div>
 								<div class="row m-t-5">
 									<div class="col-md-6 col-sm-6 col-6">
 										<label>Email <b class="text-danger">*</b></label>
 										<input type="email" class="form-control form-control-bold" placeholder="Email" name="email" value="{{$customer->email}}" {{-- required="" --}}>
 									</div>

 								</div>

 								<div class="row m-t-5">
 									<div class="col-md-3 col-sm-4 col-4">
 										<label>บ้านเลขที่ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="บ้านเลขที่" name="house_no" value="{{$customer->house_no}}" required="">
 									</div>
 									<div class="col-md-5 col-sm-8 col-8">
 										<label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="หมู่บ้าน/อาคาร" name="house_name" value="{{ $customer->house_name }}" required="">
 									</div>
 									<div class="col-md-3 col-sm-12 col-12">
 										<label>หมู่ที่ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="หมู่ที่" name="moo" value="{{ $customer->moo }}" required="">
 									</div>

 								</div>
 								<div class="row m-t-5">
 									<div class="col-sm-4">
 										<label>ตรอก/ซอย </label>
 										<input type="text" class="form-control" placeholder="ตรอก/ซอย" name="soi" value="{{ $customer->soi }}" >
 									</div>

 									<div class="col-sm-4">
 										<label>เขต/อำเภอ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="เขต/อำเภอ" name="district" value="{{ $customer->district }}" required="">
 									</div>
 									<div class="col-sm-4">
 										<label>แขวง/ตำบล <b class="text-danger">*</b> </label>
 										<input type="text" class="form-control form-control-bold" placeholder="แขวง/ตำบล" name="district_sub" value="{{ $customer->district_sub }}" required="">
 									</div>
 								</div>
 								<div class="row m-t-5">
 									<div class="col-sm-4">
 										<label>ถนน</label>
 										<input type="text" class="form-control" placeholder="ถนน" name="road" value="{{ $customer->road }}">
 									</div>

 									<div class="col-sm-4">
 										<label>จังหวัด <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="จังหวัด" name="province" value="{{ $customer->province }}" required="">
 									</div>

 									<div class="col-sm-4">
 										<label>รหัสไปษณีย์ <b class="text-danger">*</b></label> 
 										<input type="text" class="form-control form-control-bold" placeholder="รหัสไปษณีย์" name="zipcode" value="{{ $customer->zipcode }}" required="">
 									</div>
 								</div>
 							</div>

 							<div id="receive" style="display: none;">
 								<div class="row m-t-5">
 									<div class="col-sm-12">
 										<select name="receive_location" class="form-control" required="">
 											@foreach($location as $value)
 											<option value="{{$value->id}}">{{$value->name}}</option>
 											@endforeach
 										</select>
 									</div>

 								</div>
 								<div class="row m-t-5">
 									<div class="col-md-6 col-sm-6 col-6">
 										<label>ชื่อผู้รับ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="บ้านเลขที่" name="receive_name" value="{{$customer->prefix_name}} {{ $customer->first_name }} {{ $customer->last_name }}" required="">
 									</div>
 									<div class="col-md-6 col-sm-6 col-6">
 										<label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
 										<input type="text" class="form-control form-control-bold" placeholder="เบอร์โทรศัพท์" name="receive_tel_mobile" value="{{ $customer->tel_mobile }}" required="">
 									</div>
 								</div>

 								<div class="row m-t-5">
 									<div class="col-md-6 col-sm-6 col-6">
 										<label>Email <b class="text-danger">*</b></label>
 										<input type="email" class="form-control form-control-bold" placeholder="Email" name="email" value="{{$customer->email}}">
 									</div>

 								</div>
 							</div>

 							<div class="row m-t-5">
 								<div class="col-sm-12 text-right">
 									<button type="button" onclick="next()" class="btn btn-primary waves-effect waves-light m-t-20">ถัดไป</button>
 								</div>
 							</div>
 						</div>
 					</div>

 					@if($bill['type'] == 6)
 					<div class="tab-pane active" id="credit-card" role="tabpanel">
 					@else
 					<div class="tab-pane" id="credit-card" role="tabpanel">
 					@endif

 					
 						@if($bill['price_total_type5'] == 0 and $bill['type'] == 5) 
 						<div class="row">
 							<div class="col-md-12 col-xl-12">
 								<div class="card bg-c-pink order-card m-b-0">
 									<div class="card-block">
 										<div class="row">
 											<div class="col-md-8 col-sx-8 col-8">
 												<h6 class="m-b-10" style="font-size: 16px">Gift Voucher </h6>

 											</div>
 											<div class="col-md-4 col-sx-4 col-4">
 												<?php  
 												$gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
 												?>
 												<h3 class="text-right">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format($gv->sum_gv) }} </span></h3>
 											</div>
 										</div>

 										{{-- <p class="m-b-0">จำนวน Gift Voucher คงเหลือ</p> --}}
 										<hr>

 										<div class="row">
 											<div class="col-md-8 col-sx-8 col-8">
 												<h6 class="m-b-10" style="font-size: 16px">ยอดรวมที่ใช้ </h6>

 											</div>
 											<div class="col-md-4 col-sx-4 col-4">

 												<h3 class="text-right"> <span> {{ $bill['price_total'] }} </span></h3>
 											</div>
 										</div>

 										<hr>
 										<div class="row">
 											<div class="col-md-8 col-sx-8 col-8">
 												<h6 style="font-size: 16px">Gift Voucher คงเหลือ </h6>

 											</div>
 											<div class="col-md-4 col-sx-4 col-4">

 												<h3 class="text-right"> <span> {{ $bill['gv_total'] }} </span></h3>
 											</div>
 										</div>
 									</div>

 								</div>

 								<div class="row m-t-5">
 									<div class="col-sm-6">
 									</div>
 									<div class="col-sm-6 text-right">
 										<button class="btn btn-success btn-block" type="submit" name="submit" value="gift_voucher">ชำระเงิน</button>
 									</div>
 								</div>

 								

 							</div>
 							
 						</div>
 						
 						
 						
 						@else

 						<div class="demo-container card-block">

 							<div class="row">
 								<div class="col-sm-12 col-md-12 col-xl-12 m-b-30">

 									<div class="form-radio">
 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" id="bank" onchange="open_input(1)" name="pay_type" value="1" checked="checked">
 												<i class="helper"></i><b>โอนชำระ</b>
 											</label>
 										</div>
 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" onchange="open_input(2)" id="credit_cart" name="pay_type" value="2">
 												<i class="helper"></i><b>บัตรเครดิต</b>
 											</label>
 										</div>
 									 
 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" onchange="open_input(3)" id="ai_cast" name="pay_type" value="3">
 												<i class="helper"></i><b>Ai-Cash</b>
 											</label>
 										</div> 
 										 

 										{{-- <div class="radio radio-inline">
 											<label>
 												<input type="radio" onchange="open_input(4)" id="voucher" name="pay_type" value="Voucher">
 												<i class="helper"></i><b>Gift Voucher</b>
 											</label>
 										</div> --}}
 									</div>
 								</div>
 							</div>

 							<div class="row" id="cart_pament">

 								<div class="form-group row">
 									<div class="col-sm-12">

 										<div class="form-group row">
 											<div class="col-sm-6">
 												<label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">( JPG,PNG )</b> </label>
 												<input type="file" id="upload" name="file_slip" class="form-control">
 											</div>
 										</div>
 									</div>
 									<div class="row">
 										<div class="col-xs-6 p-1">
 											<button class="btn btn-success btn-block" type="submit" name="submit" id="submit_upload" value="upload" >อัพโหลดหลักฐานการชำระเงิน</button>
 										</div>
 										<div class="col-xs-6 p-1">
 											<button class="btn btn-primary btn-block" type="" name="submit" value="not_upload">อัพโหลดหลักฐานการชำระเงินภายหลัง</button>
 										</div>
 										
 										
 									</div>
 								</div>
 								
 							</div>

 						</div>
 						@endif

 					</div>

 				</div>
 			</div>
 		</div>
 	</form>
 </div>
 <div class="col-md-4">
 	<div class="card card-border-success">
 		<div class="card-header">
 			<h3>สรุปรายการสั่งซื้อ</h3>
 			{{--  <span class="label label-default f-right"> 28 January, 2015 </span> --}}
 		</div>
 		<div class="card-block">
 			<div class="col-md-12">
 				<table class="table table-responsive" >
 					<tr>

 						<td><strong id="quantity_bill">มูลค่าสินค้า ({{ $bill['quantity'] }}) ชิ้น</strong></td>
 						<td align="right"><strong id="price"> {{ $bill['price_vat'] }} </strong></td>
 					</tr>
 					<tr>
 						<td><strong>Vat({{ $bill['vat'] }}%)</strong></td>
 						<td align="right"><strong > {{ $bill['p_vat'] }}</strong></td>
 					</tr>
 					<tr>
 						<td><strong>มูลค่าสินค้า + Vat</strong></td>
 						<td align="right"><strong > {{ number_format($bill['price'],2) }}</strong></td>
 					</tr>

 					@if($bill['type'] != 6)
 					
 					<tr>
 						<td><strong>ค่าจัดส่ง</strong></td>
 						<td align="right"><strong > {{  number_format($bill['shipping'],2) }}</strong></td>
 					</tr>

 					@endif
 					@if($bill['type'] == 5)
 					<?php  
 					$gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
 					?>
 					<tr>
 						<td><strong>ยอดรวม</strong></td>
 						<td align="right"><strong> {{ number_format($bill['price_total'],2) }}</strong>
 						</td>
 					</tr>
 					<tr>
 						<td><strong class="text-primary">Gift Voucher</strong></td>
 						<td align="right"><strong class="text-primary"> {{ $gv->sum_gv }}</strong>
 						</td>
 					</tr>
 					<tr>
 						<td><strong class="text-primary" style="font-size: 13px">Gift Voucher คงเหลือ</strong></td>
 						<td align="right"><strong  class="text-primary"> {{  $bill['gv_total'] }}</strong>
 						</td>
 					</tr>

 					<tr>
 						<td><strong>ยอดที่ต้องชำระเพิ่ม</strong></td>
 						<td align="right"><strong> {{  number_format($bill['price_total_type5'],2) }}</strong>

 						</td>
 					</tr>
 					@elseif($bill['type'] == 6)
 					<tr>
 						<td><strong>ยอดที่ต้องชำระ</strong></td>
 						<td align="right"><strong > {{ $bill['price'] }}</strong>
 						</td>
 					</tr>

 					@else
 					<tr>
 						<td><strong>ยอดที่ต้องชำระ</strong></td>
 						<td align="right"><strong > {{ $bill['price_total'] }}</strong>
 						</td>
 					</tr>
 					@endif
 					
 					<tr>
 						<td><strong>คะแนนที่ได้รับ</strong></td>
 						<td align="right"><strong class="text-success" id="pv">{{ $bill['pv_total'] }} PV</strong></td>
 					</tr>

 				</table>
 				<div class="row" align="center">
 					<a href="{{ route('product-list',['type'=>$bill['type']]) }}" class="btn btn-warning btn-block" ><font style="color: #000">เลือกสินค้าเพิ่มเติม</font> </a>
 				</div>

 			</div>

 		</div>
 		<div class="card-footer">
 		</div>
 		<!-- end of card-footer -->
 	</div>
 </div>
</div>

@endsection
@section('js')
<script>

	document.getElementById("submit_upload").disabled = true;

	$('#upload').change( function () {
		var fileExtension = ['jpg','png'];
		if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
			this.value = '';
			return false;
		}else {
			document.getElementById("submit_upload").disabled = false;
		}
	});

	
	function next(){
		document.getElementById("address").classList.remove('active');
		document.getElementById("nav_address").classList.remove('active');

		document.getElementById("credit-card").classList.add('active');
		document.getElementById("nav_card").classList.add('active');
	}
	function sent_address(){
		document.getElementById("sent").style.display = 'block';
		document.getElementById("receive").style.display = 'none'; 
	}

	function receive_office(){
		document.getElementById("sent").style.display = 'none';
		document.getElementById("receive").style.display = 'block'; 
	}

	function open_input(data){
		var conten_1 = '<div class="form-group row">'+
		'<div class="col-sm-12">'+
		'<div class="form-group row">'+
		'<div class="col-sm-6">'+
		'<label>อัพโหลดหลักฐานการชำระเงิน</label>'+
		'<input type="file" id="upload" name="file_slip" class="form-control">'+
		'</div>'+
		'</div>'+
		'</div>'+
		'<div class="row">'+
		'<div class="col-xs-6 p-1">'+
		'<button class="btn btn-success btn-block" type="submit" name="submit" id="submit_upload" value="upload" >อัพโหลดหลักฐานการชำระเงิน</button>'+
		'</div>'+
		'<div class="col-xs-6 p-1">'+
		'<button class="btn btn-primary btn-block" type="" name="submit" value="not_upload">อัพโหลดหลักฐานการชำระเงินภายหลัง</button>'+
		'</div>'+
		'</div>'+
		'</div>';

		var conten_2 = '<div class="col-sm-6">'+
		'<div class="form-group">'+
		'<input type="text" class="form-control" placeholder="Type your Full Name">'+
		'</div>'+
		'<div class="form-group CVV">'+
		'<input type="text" class="form-control" id="cvv" placeholder="CVV">'+
		'</div>'+
		'<div class="form-group" id="card-number-field">'+
		'<input type="text" name="name" class="form-control" id="cardNumber" placeholder="Card Number">'+
		'</div>'+
		'</div>'+
		'<div class="col-sm-6">'+
		'<div class="form-group" id="expiration-date">'+
		'<label>Expiration Date</label>'+
		'<div class="row">'+
		'<div class="col-sm-6">'+
		'<select class="form-control m-b-10">'+
		'<option>Select Month</option>'+
		'<option value="01">01</option>'+
		'<option value="02">02 </option>'+
		'<option value="03">03</option>'+
		'<option value="04">04</option>'+
		'<option value="05">05</option>'+
		'<option value="06">06</option>'+
		'<option value="07">07</option>'+
		'<option value="08">08</option>'+
		'<option value="09">09</option>'+
		'<option value="10">10</option>'+
		'<option value="11">11</option>'+
		'<option value="12">12</option>'+
		'</select>'+
		'</div>'+
		'<div class="col-sm-6">'+
		'<select class="form-control m-b-10">'+
		'<option><b>Select Year</b></option>'+
		'<option value="16"> 2016</option>'+
		'<option value="17"> 2017</option>'+
		'<option value="18"> 2018</option>'+
		'<option value="19"> 2019</option>'+
		'<option value="20"> 2020</option>'+
		'<option value="21"> 2021</option>'+
		'</select>'+
		'</div>'+
		'</div>'+
		'</div>'+
		'<div class="form-group" id="debit-cards">'+
		'<img src="{{ asset('frontend/assets/images/e-payment/card/visa.jpg') }}" id="visa" alt="visa.jpg">'+
		'<img src="{{ asset('frontend/assets/images/e-payment/card/mastercard.jpg') }}" id="mastercard" alt="mastercard.jpg">'+
		'</div>'+
		'</div>'+
		'<div class="col-sm-12 text-right">'+
		'<button class="btn btn-success btn-block" name="submit" value="credit_card"  type="submit">ชำระเงิน</button>'+
		'</div>';
		var conten_3 = '<button class="btn btn-success btn-block" type="submit" name="submit" value="ai_cash">ชำระเงิน</button>';
		var conten_4 = '<button class="btn btn-success btn-block" type="submit">ชำระเงิน</button>';

		if(data == '1'){
			document.getElementById("cart_pament").innerHTML=(conten_1);
			document.getElementById("submit_upload").disabled = true;
			$('#upload').change( function () {
				var fileExtension = ['jpg','png'];
				if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
					alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
					this.value = '';
					return false;
				}else {
					document.getElementById("submit_upload").disabled = false;
				}
			});
		}else if (data == '2') {
			document.getElementById("cart_pament").innerHTML=(conten_2);
		}else if (data == '3') {
			document.getElementById("cart_pament").innerHTML=(conten_3);
		}else if (data == '4') {
			document.getElementById("cart_pament").innerHTML=(conten_4);
		}else{
			document.getElementById("cart_pament").innerHTML=(conten_1);
		}
	}
</script>

<script src="{{asset('frontend/assets/pages/payment-card/card.js')}}"></script>
<script src="{{asset('frontend/assets/pages/payment-card/jquery.payform.min.js')}}" charset="utf-8"></script>
<script  src="{{asset('frontend/assets/pages/payment-card/e-payment.js')}}"></script>

@endsection

