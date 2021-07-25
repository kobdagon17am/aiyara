 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 @endsection
 @section('conten')
 <?php //dd($customer); ?>

 <div class="row">
 	<div class="col-md-8 col-sm-12">
 		<form action="{{route('payment_submit')}}" method="POST" enctype="multipart/form-data">
 			@csrf
 			<input type="hidden" name="type" value="{{ $data['type'] }}">
 			<input type="hidden" name="price" value="{{ $data['price'] }}">

 			<!-- Choose Your Payment Method start -->
 			<div class="card card-border-success">
 				<div class="card-header p-3">
 					<h5>เติม Ai-Cash</h5>
 				</div>
 				<div class="card-block payment-tabs">
 					<ul class="nav nav-tabs md-tabs" role="tablist">
 						<li class="nav-item">
 							<a class="nav-link active" data-toggle="tab" id="nav_card" href="#credit-card" role="tab">ชำระเงิน</a>
 							<div class="slide"></div>
 						</li>

 					</ul>
 					<div class="tab-content m-t-15">
 						<div class="tab-pane active" id="credit-card" role="tabpanel">
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


 										</div>
 									</div>
 								</div>

 								<div class="row" id="cart_pament">

                  <div class="row col-md-12 col-lg-12">
                    <div class="col-md-6 col-lg-6">
                      <div class="card">
                          <div class="card-block text-center">
                              {{-- <i class="fa fa-envelope-open text-c-blue d-block f-40"></i> --}}
                              <img src="{{ asset('frontend/assets/images/scb.png') }}" class="img-fluid" alt="Responsive image" width="80">
                              <h5 class="m-t-20"><span class="text-c-blue">019-7-03027-3</span></h5>
                              <p class="m-b-2 m-t-5">ธนาคารไทยพาณิชย์ <br>Aiyara Planet </p>
                              {{-- <button class="btn btn-primary btn-sm btn-round">Manage List</button> --}}
                          </div>
                      </div>
                  </div>

                  </div>

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
                      @can('can-access')
 											<div class="col-xs-6 p-1">

 												<button class="btn btn-success btn-block" type="submit" name="submit" id="submit_upload" value="upload" >อัพโหลดหลักฐานการชำระเงิน</button>
 											</div>
 											<div class="col-xs-6 p-1">
 												<button class="btn btn-primary btn-block" type="" name="submit" value="not_upload">อัพโหลดหลักฐานการชำระเงินภายหลัง</button>
 											</div>
                       @endcan

 										</div>
 									</div>

 								</div>

 							</div>


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
 							<td><strong style="font-size: 20px">ยอดรวมที่ต้องชำระ</strong></td>
 							<td align="right"><strong class="text-primary" style="font-size: 20px"> {{ $data['price'] }} </strong>
 							</td>
 						</tr>


 					</table>
 				{{-- <div class="row" align="center">
 					<a href="{{ route('product-list') }}" class="btn btn-warning btn-block" ><font style="color: #000">เลือกสินค้าเพิ่มเติม</font> </a>
 				</div> --}}

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
		var conten_1 = `<div class="row col-md-12 col-lg-12">
                    <div class="col-md-6 col-lg-6">
                      <div class="card">
                          <div class="card-block text-center">
                              {{-- <i class="fa fa-envelope-open text-c-blue d-block f-40"></i> --}}
                              <img src="{{ asset('frontend/assets/images/scb.png') }}" class="img-fluid" alt="Responsive image" width="80">
                              <h5 class="m-t-20"><span class="text-c-blue">019-7-03027-3</span></h5>
                              <p class="m-b-2 m-t-5">ธนาคารไทยพาณิชย์ <br>Aiyara Planet </p>
                              {{-- <button class="btn btn-primary btn-sm btn-round">Manage List</button> --}}
                          </div>
                      </div>
                  </div>
                  </div>`+
    '<div class="form-group row">'+
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

