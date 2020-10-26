 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 @endsection
 @section('conten')
 <div class="row">
 	<div class="col-md-8 col-sm-12">
 		<!-- Choose Your Payment Method start -->
 		<div class="card card-border-success">
 			<div class="card-header">
 				{{-- <h5>กรุณาตรวจสอบ</h5> --}}
 				{{-- <div class="card-header-right">
 				 
 				</div> --}}
 			</div>
 			<div class="card-block payment-tabs">
 				<ul class="nav nav-tabs md-tabs" role="tablist">
 					<li class="nav-item">
 						<a class="nav-link active" id="nav_address" data-toggle="tab" href="#address" role="tab">ที่อยู่การจัดส่ง</a>
 						<div class="slide"></div>
 					</li>
 					<li class="nav-item">
 						<a class="nav-link" data-toggle="tab" id="nav_card" href="#credit-card" role="tab">ชำระเงิน</a>
 						<div class="slide"></div>
 					</li>
 					{{-- <li class="nav-item">
 						<a class="nav-link" data-toggle="tab" href="#debit-card" role="tab">Debit Card</a>
 						<div class="slide"></div>
 					</li> --}}


 				</ul>
 				<div class="tab-content m-t-15">
 					<div class="tab-pane active" id="address" role="tabpanel">
 						<div class="card-block p-b-0">
 							<div class="form-radio">
 								<div class="radio radio-inline">
 									<label>
 										<input type="radio" name="receive" checked="checked">
 										<i class="helper"></i><b>จัดส่ง</b> 
 									</label>
 								</div>
 								<div class="radio radio-inline">
 									<label>
 										<input type="radio" name="receive">
 										<i class="helper"></i><b>รับที่สาขาไกล้บ้าน</b>
 									</label>
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

 							<div class="row m-t-5">
 								<div class="col-sm-12 text-right">
 									<button type="button" onclick="next()" class="btn btn-primary waves-effect waves-light m-t-20">ถัดไป</button>
 								</div>
 							</div>
 						</div>
 					</div>

 					<div class="tab-pane" id="credit-card" role="tabpanel">
 						<div class="demo-container card-block">

 							<div class="row">
 								<div class="col-sm-12 col-md-12 col-xl-12 m-b-30">

 									<div class="form-radio">
 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" name="radio" checked="checked">
 												<i class="helper"></i><b>เงินสด</b>
 											</label>
 										</div>
 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" name="radio">
 												<i class="helper"></i><b>บัตรเครดิต</b>
 											</label>
 										</div>
 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" name="radio">
 												<i class="helper"></i><b>Ai-Cast</b>
 											</label>
 										</div>

 										<div class="radio radio-inline">
 											<label>
 												<input type="radio" name="radio">
 												<i class="helper"></i><b>Gift Voucher</b>
 											</label>
 										</div>
 									</div>
 								</div>
 							</div>

 							<div class="row">
 								<div class="col-sm-6">
 									<div class="form-group">
 										<input type="text" class="form-control" placeholder="Type your Full Name">
 									</div>
 									<div class="form-group CVV">
 										<input type="text" class="form-control" id="cvv" placeholder="CVV">
 									</div>
 									<div class="form-group" id="card-number-field">
 										<input type="text" name="name" class="form-control" id="cardNumber" placeholder="Card Number">
 									</div>
 								</div>
 								<div class="col-sm-6">
 									<div class="form-group" id="expiration-date">
 										<label>Expiration Date</label>
 										<div class="row">
 											<div class="col-sm-6">
 												<select class="form-control m-b-10">
 													<option>Select Month</option>
 													<option value="01">01</option>
 													<option value="02">02 </option>
 													<option value="03">03</option>
 													<option value="04">04</option>
 													<option value="05">05</option>
 													<option value="06">06</option>
 													<option value="07">07</option>
 													<option value="08">08</option>
 													<option value="09">09</option>
 													<option value="10">10</option>
 													<option value="11">11</option>
 													<option value="12">12</option>
 												</select>
 											</div>
 											<div class="col-sm-6">
 												<select class="form-control m-b-10">
 													<option><b>Select Year</b></option>
 													<option value="16"> 2016</option>
 													<option value="17"> 2017</option>
 													<option value="18"> 2018</option>
 													<option value="19"> 2019</option>
 													<option value="20"> 2020</option>
 													<option value="21"> 2021</option>
 												</select>
 											</div>
 										</div>
 									</div>
 									<div class="form-group" id="debit-cards">
 										<img src="{{ asset('frontend/assets/images/e-payment/card/visa.jpg') }}" id="visa" alt="visa.jpg">
 										<img src="{{ asset('frontend/assets/images/e-payment/card/mastercard.jpg') }}" id="mastercard" alt="mastercard.jpg">
 										{{-- <img src="{{ asset('frontend/assets/images/e-payment/card/amex.jpg') }}" id="amex" alt="amex.jpg"> --}}
 									</div>
 								</div>
 								<div class="col-sm-12 text-right">
 									<a href="#!" class="btn btn-primary waves-effect waves-light m-t-20">ชำระเงิน</a>
 								</div>
 							</div>
 						</div>
 					</div>

 				</div>
 			</div>
 		</div>
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
 							<td><strong id="quantity_bill">ยอดรวมจำนวน ({{ $quantity }}) ชิ้น</strong></td>
 							<td align="right"><strong id="price"> ฿ {{ $price_total }} </strong></td>
 						</tr>
 						<tr>
 							<td><strong>ค่าจัดส่ง</strong></td>
 							<td align="right"><strong id="sent">฿ {{ $sent }}</strong></td>
 						</tr>
 						<tr>
 							<td><strong>ยอดรวมทั้งสิ้น</strong></td>
 							<td align="right"><strong id="price_total">฿ {{ $price_total_sent }}</strong>
 							</td>
 						</tr>
 						<tr>
 							<td><strong>คะแนนที่ได้รับ</strong></td>
 							<td align="right"><strong class="text-success" id="pv">{{ $pv_total }} PV</strong></td>
 						</tr>


 					</table>
 					<div class="row" align="center">
 						<button class="btn btn-success btn-block" type="">ชำระเงิน</button>
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
 	function next(){

 		document.getElementById("address").classList.remove('active');
 		document.getElementById("nav_address").classList.remove('active');

 		document.getElementById("credit-card").classList.add('active');
 		document.getElementById("nav_card").classList.add('active');

 	}
 </script>

 <script src="{{asset('frontend/assets/pages/payment-card/card.js')}}"></script>
 <script src="{{asset('frontend/assets/pages/payment-card/jquery.payform.min.js')}}" charset="utf-8"></script>
 <script  src="{{asset('frontend/assets/pages/payment-card/e-payment.js')}}"></script>

 @endsection

