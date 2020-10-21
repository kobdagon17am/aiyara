 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 @endsection
 @section('conten')

 <div class="row">
 	<div class="col-md-8 col-sm-12">
 		<div class="card card-border-success">
 			<div class="card-header">
 				<h5>Form Basic Wizard</h5>
 				<span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>
 			</div>
 			<div class="card-block">
 				<div class="row">
 					<div class="col-md-12">
 						<div id="wizarda">
 							<section>
 								<form class="wizard-form" id="basic-forms" action="#">
 									<h3> ที่อยู่จัดส่งสินค้า </h3>
 									<fieldset>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="userName-2" class="block">User name *</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="userName-2a" name="userName" type="text" class=" form-control">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="email-2" class="block">Email *</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="email-2a" name="email" type="email" class=" form-control">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="password-2" class="block">Password *</label>
 											</div>
 											<div class="col-sm-8 col-lg-10">
 												<input id="password-2a" name="password" type="password" class="form-control ">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="confirm-2" class="block">Confirm Password *</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="confirm-2a" name="confirm" type="password" class="form-control ">
 											</div>
 										</div>
 									</fieldset>
 									<h3> ชำระเงิน </h3>
 									<fieldset>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="name-2" class="block">First name *</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="name-2a" name="name" type="text" class="form-control">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="surname-2" class="block">Last name *</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="surname-2a" name="surname" type="text" class="form-control">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="phone-2" class="block">Phone #</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="phone-2a" name="phone" type="number" class="form-control phone">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="date" class="block">Date Of Birth</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="datea" name="Date Of Birth" type="text" class="form-control date-control">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 											Select Country</div>
 											<div class="col-sm-12">
 												<select class="form-control required">
 													<option>Select State</option>
 													<option>Gujarat</option>
 													<option>Kerala</option>
 													<option>Manipur</option>
 													<option>Tripura</option>
 													<option>Sikkim</option>
 												</select>
 											</div>
 										</div>
 									</fieldset>
 									<h3> Education </h3>
 									<fieldset>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="University-2" class="block">University</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="University-2a" name="University" type="text" class="form-control required">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="Country-2" class="block">Country</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="Country-2a" name="Country" type="text" class="form-control required">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="Degreelevel-2" class="block">Degree level #</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="Degreelevel-2a" name="Degree level" type="text" class="form-control required phone">
 											</div>
 										</div>
 										<div class="form-group row">
 											<div class="col-sm-12">
 												<label for="datejoin" class="block">Date Join</label>
 											</div>
 											<div class="col-sm-12">
 												<input id="datejoina" name="Date Of Birth" type="text" class="form-control required">
 											</div>
 										</div>
 									</fieldset>
 								 
 								</form>
 							</section>
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

 <script src="{{asset('frontend/bower_components/jquery.cookie/js/jquery.cookie.js')}}"></script>
 <script src="{{asset('frontend/bower_components/jquery.steps/js/jquery.steps.js')}}"></script>
 <script src="{{asset('frontend/bower_components/jquery-validation/js/jquery.validate.js')}}"></script>
 <!-- Validation js -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js')}}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js')}}"></script>
 <script  src="{{asset('frontend/assets/pages/form-validation/validate.js')}}"></script>
 <!-- Custom js -->
 <script src="{{asset('frontend/assets/pages/forms-wizard-validation/form-wizard.js')}}"></script>
 
 @endsection

