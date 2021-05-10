 @extends('frontend.layouts.customer.customer_app')
 @section('css')
     <link rel="stylesheet" href="{{ asset('frontend/bower_components/select2/css/select2.min.css') }}" />
     <!-- Multi Select css -->
     <link rel="stylesheet" type="text/css"
         href="{{ asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
     <link rel="stylesheet" type="text/css"
         href="{{ asset('frontend/bower_components/multiselect/css/multi-select.css') }}">
 @endsection
 @section('conten')


     <form name="payForm" action="https://ipay.bangkokbank.com/b2c/eng/dPayment/payComp.jsp" method="post" id="form1">
         @csrf
         <input type="hidden" name="merchantId" value="6845">
         <input type="hidden" name="amount" value="1">
         <input type="hidden" name="orderRef" value="OR-0001">
         <input type="hidden" name="currCode" value="764">
         <input type="hidden" name="pMethod" value="VISA">
         <input type="hidden" name="cardNo" value="4918914107195005">
         <input type="hidden" name="securityCode" value="123">
         <input type="hidden" name="cardHolder" value="CardHolder">
         <input type="hidden" name="epMonth" value="07">
         <input type="hidden" name="epYear" value="2021">
         <input type="hidden" name="payType" value="N">
         <input type="hidden" name="successUrl" value="{{ route('payment.success') }}">
         <input type="hidden" name="failUrl" value="{{ route('payment.fail') }}">
         <input type="hidden" name="errorUrl" value="{{ route('payment.fail') }}">
         <input type="hidden" name="lang" VALUE="T">
         <input type="hidden" name="remark" value="-">
     </form>

     <form name="payFormCcard" method="post" action="https://ipay.bangkokbank.com/b2c/eng/payment/payForm.jsp" id="form2">
         <input type="hidden" name="merchantId" value="6845">
         <input type="hidden" name="amount" value="100">
         <input type="hidden" name="orderRef" value="20010207">
         <input type="hidden" name="currCode" value="764">
         <input type="hidden" name="successUrl" value="{{ route('payment.success') }}">
         <input type="hidden" name="failUrl" value="{{ route('payment.fail') }}">
         <input type="hidden" name="errorUrl" value="{{ route('payment.fail') }}">
         <input type="hidden" name="payType" value="N">
         <input type="hidden" name="lang" value="E">
         <input type="hidden" name="remark" value="-">

         <!-- พารามิเตอร์เสริม (ตัวเลือก)
    <input type="hidden" name="redirect" value="30">
    <input type="hidden" name="orderRef1" value="add-ref-00001">
    <input type="hidden" name="orderRef2" value="add-ref-00002">
    <input type="hidden" name="orderRef3" value="add-ref-00003">
    <input type="hidden" name="orderRef4" value="add-ref-00004">
    <input type="hidden" name="orderRef5" value="add-ref-00005">
    -->
     </form>


     <div class="row">
         <div class="col-md-8 col-sm-12">
             <form action="{{ route('payment_submit') }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 <input type="hidden" id="url_check_user" name="url_check_user" value="{{ route('check_customer_id') }}">
                 <input type="hidden" name="shipping_premium" id="shipping_premium" value="">
                 <input type="hidden" name="type" value="{{ $bill['type'] }}">
                 <input type="hidden" name="vat" value="{{ $bill['vat'] }}">
                 {{-- <input type="hidden" name="shipping" id="input_shipping" value="{{ $bill['shipping'] }}"> --}}
                 <input type="hidden" name="price_vat" value="{{ $bill['price_vat'] }}">
                 <input type="hidden" name="price" value="{{ $bill['price'] }}">
                 <input type="hidden" name="p_vat" value="{{ $bill['p_vat'] }}">
                 <input type="hidden" name="pv_total" value="{{ $bill['pv_total'] }}">

                 <input type="hidden" name="price_total" id="price_total" value="{{ $bill['price_total'] }}">
                 <input type="hidden" name="price_total_type5" id="price_total_type5"
                     value="{{ $bill['price_total_type5'] }}">
                 <input type="hidden" name="gift_voucher_price" id="gift_voucher_price"
                     value="{{ $bill['gift_voucher_price'] }}">

                 <!-- Choose Your Payment Method start -->
                 <div class="card card-border-success">
                     <div class="card-header p-3">
                         @if ($bill['type'] == 1)
                             <h5>รายการสั่งซื้อเพื่อทำคุณสมบัติ</h5>
                         @elseif($bill['type'] == 2)
                             <h5>รายการสั่งซื้อเพื่อรักษาคุณสมบัติรายเดือน</h5>
                         @elseif($bill['type'] == 3)
                             <h5>รายการสั่งซื้อเพื่อรักษาคุณสมบัติท่องเที่ยว</h5>
                         @elseif($bill['type'] == 4)
                             <h5>รายการสั่งซื้อเพื่อเติม Ai-Stockist</h5>
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
                             @if ($bill['type'] == 6)
                                 <li class="nav-item">
                                     <a class="nav-link active" data-toggle="tab" id="nav_card" href="#credit-card"
                                         role="tab">ชำระเงิน</a>
                                     <div class="slide"></div>
                                 </li>
                             @else
                                 <li class="nav-item">
                                     <a class="nav-link active" id="nav_address" data-toggle="tab" href="#address"
                                         role="tab">ที่อยู่การจัดส่ง</a>
                                     <div class="slide"></div>
                                 </li>
                                 <li class="nav-item">
                                     <a class="nav-link" id="nav_card">ชำระเงิน</a>
                                     <div class="slide"></div>
                                 </li>
                             @endif



                             {{-- <li class="nav-item">
 						<a class="nav-link" data-toggle="tab" href="#debit-card" role="tab">Debit Card</a>
 						<div class="slide"></div>
 					</li> --}}


                         </ul>
                         <div class="tab-content m-t-15">


                             @if ($bill['type'] == 6)
                                 <div class="tab-pane" id="address" role="tabpanel">
                                 @else
                                     <div class="tab-pane active" id="address" role="tabpanel">
                             @endif

                             <div class="card-block p-b-0" style="padding:10px">

                                 <div class="row">
                                     <div class="col-sm-12 col-md-12 col-xl-12 m-b-30">
                                         <h4 class="sub-title" style="font-size: 17px">รูปแบบการจัดส่ง</h4>
                                         <div class="form-radio">
                                             <div class="radio radio-inline">
                                                 <label>
                                                     <input type="radio" name="sent_type_to_customer"
                                                         value="sent_type_customer" id="sent_type_customer"
                                                         onclick="sent_type('customer')" checked="checked">
                                                     <i class="helper"></i><b>จัดส่งให้ตัวเอง</b>
                                                 </label>
                                             </div>
                                             <div class="radio radio-inline">
                                                 <label>
                                                     <input type="radio" name="sent_type_to_customer"
                                                         value="sent_type_other" id="sent_type_other"
                                                         onclick="sent_type('other')">
                                                     <i class="helper"></i><b>จัดส่งให้ลูกทีม</b>
                                                 </label>
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="row" id="check_user" style="display: none">
                                     <div class="col-md-12">
                                         <div class="row">
                                             <div class="col-md-5 col-lg-5 col-xl-5">
                                                 <div class="card">
                                                     <div class="card-block" style="padding: 10px;">
                                                         <h6>รหัสของลูกทีมที่ต้องการส่งสินค้า</h6>
                                                         <div class="form-group row">
                                                             <div class="col-md-12">
                                                                 <div class="input-group input-group-button">
                                                                     <input type="text" id="username" class="form-control"
                                                                         placeholder="รหัสสมาชิก">
                                                                     <span class="input-group-addon btn btn-primary"
                                                                         onclick="check()">
                                                                         <span class="">ทำรายการ</span>
                                                                     </span>
                                                                 </div>
                                                                 <span
                                                                     style="font-size: 12px">*คะแนนการสั่งซื้อจะถูกใช้ให้กับลูกทีมที่เลือก</span>
                                                             </div>

                                                         </div>

                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="col-md-7 col-lg-7 col-xl-7" id="data_direct" style="display: none">
                                                 <div class="card bg-c-yellow order-card m-b-0">
                                                     <div class="card-block" style="padding: 10px;">
                                                         <div class="row">
                                                             <div class="col-md-12">
                                                                 <h5 id="c_text_username"
                                                                     style="color: #000;font-size: 16px"></h5>
                                                                 <h6 class="m-b-0" id="c_name" style="color: #000"></h6>
                                                                 <input type="hidden" name="address_sent_id_fk"
                                                                     id="address_sent_id_fk" value="">

                                                             </div>

                                                         </div>
                                                         <div class="row">
                                                             <div class="col-md-12">
                                                                 <h6 class="m-b-0" style="color: #000"><i
                                                                         class="fa fa-star p-2 m-b-0"></i>
                                                                     <b id="c_qualification_name"></b>
                                                                 </h6>
                                                             </div>
                                                         </div>
                                                         <hr class="m-b-5 m-t-5">
                                                         <div class="row">
                                                             <div class="col-md-6">
                                                                 <h5 class="m-b-0" style="color: #000">คะแนนสะสม</h5>
                                                             </div>
                                                             <div class="col-md-6">
                                                                 <h5 class="m-b-0 text-right" id="c_text_pv"
                                                                     style="color: #000"></h5>
                                                             </div>
                                                         </div>
                                                         <hr class="m-b-5 m-t-5">
                                                         <div class="row">
                                                             <div class="col-md-6">
                                                                 <p class="m-b-0" style="color: #000">
                                                                     <b>สถานะรักษาคุณสมบัติรายเดือน</b>
                                                                 </p>
                                                             </div>
                                                             <div class="col-md-6 text-right">
                                                                 <div id="c_pv_tv_active"></div>
                                                             </div>
                                                         </div>

                                                         <hr class="m-b-5 m-t-5">
                                                         <div class="row">
                                                             <div class="col-md-6">
                                                                 <p class="m-b-0" style="color: #000">
                                                                     <b>สถานะรักษาคุณสมบัติท่องเที่ยว</b>
                                                                 </p>
                                                             </div>
                                                             <div class="col-md-6 text-right">
                                                                 <div id="c_pv_mt_active"></div>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="row">
                                     <div class="col-sm-12 col-md-12 col-xl-12">
                                         <h4 class="sub-title" style="font-size: 17px">ที่อยู่ผู้รับสินค้า</h4>
                                         <div class="form-radio">
                                             <div class="radio radio-inline" id="i_sent_address">
                                                 <label>
                                                     <input type="radio" onchange="sent_address('sent_address')"
                                                         id="sent_address_check" name="receive" value="sent_address"
                                                         checked="checked">
                                                     <i class="helper"></i><b>จัดส่ง</b>
                                                 </label>
                                             </div>
                                             <div class="radio radio-inline" id="i_sent_address_card">
                                                 <label>
                                                     <input type="radio" id="sent_address_card_check"
                                                         onchange="sent_address('sent_address_card',{{ $address_card->provinces_id }})"
                                                         name="receive" value="sent_address_card">
                                                     <i class="helper"></i><b>ตามบัตรประชาชน</b>
                                                 </label>
                                             </div>

                                             <div class="radio radio-inline">
                                                 <label>
                                                     <input type="radio" id="sent_office_check"
                                                         onchange="sent_address('sent_office')" name="receive"
                                                         value="sent_office">
                                                     <i class="helper"></i><b>รับที่สาขา</b>
                                                 </label>
                                             </div>

                                             <div class="radio radio-inline">
                                                 <label>
                                                     <input type="radio" id="sent_other"
                                                         onchange="sent_address('sent_other')" name="receive"
                                                         value="sent_address_other">
                                                     <i class="helper"></i><b>อื่นๆ</b>
                                                 </label>
                                             </div>
                                         </div>

                                     </div>
                                 </div>

                                 <div id="sent_address">
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="ชื่อผู้รับ" name="name"
                                                 value="{{ $customer->prefix_name }} {{ $customer->first_name }} {{ $customer->last_name }}"
                                                 readonly="">
                                         </div>
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="เบอร์โทรศัพท์" name="tel_mobile"
                                                 value="{{ $address->tel_mobile }}" readonly="">
                                         </div>
                                     </div>
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>Email </label>
                                             <input type="email" class="form-control form-control-bold" placeholder="Email"
                                                 name="email" value="{{ $customer->email }}" readonly="">
                                         </div>

                                     </div>

                                     <div class="row m-t-5">
                                         <div class="col-md-3 col-sm-4 col-4">
                                             <label>บ้านเลขที่ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="บ้านเลขที่" name="house_no"
                                                 value="{{ $address->house_no }}" readonly="">
                                         </div>
                                         <div class="col-md-5 col-sm-8 col-8">
                                             <label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="หมู่บ้าน/อาคาร" name="house_name"
                                                 value="{{ $address->house_name }}" readonly="">
                                         </div>
                                         <div class="col-md-3 col-sm-12 col-12">
                                             <label>หมู่ที่ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold" placeholder="หมู่ที่"
                                                 name="moo" value="{{ $address->moo }}" readonly="">
                                         </div>

                                     </div>
                                     <div class="row m-t-5">
                                         <div class="col-sm-4">
                                             <label>ถนน</label>
                                             <input type="text" class="form-control form-control-bold" placeholder="ถนน"
                                                 name="road" value="{{ $address->road }}" readonly="">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>ตรอก/ซอย </label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="ตรอก/ซอย" name="soi" value="{{ $address->soi }}"
                                                 readonly="">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>จังหวัด <b class="text-danger">*</b></label>
                                             <input type="text" id="provinces_address"
                                                 class="form-control form-control-bold" placeholder="จังหวัด"
                                                 value="{{ $address->provinces_name }}" readonly="">
                                             <input type="hidden" name="province" value="{{ $address->province_id_fk }}">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>เขต/อำเภอ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="เขต/อำเภอ" value="{{ $address->amphures_name }}"
                                                 readonly="">
                                             <input type="hidden" name="amphures" value="{{ $address->amphures_id_fk }}">
                                         </div>
                                         <div class="col-sm-4">
                                             <label>แขวง/ตำบล <b class="text-danger">*</b> </label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="แขวง/ตำบล" value="{{ $address->district_name }}"
                                                 readonly="">
                                             <input type="hidden" name="district" value="{{ $address->district_id_fk }}">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>รหัสไปษณีย์ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="รหัสไปษณีย์" name="zipcode" value="{{ $address->zipcode }}"
                                                 readonly="">
                                         </div>
                                     </div>

                                 </div>

                                 <div id="sent_address_card" style="display: none;">
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="ชื่อผู้รับ" name="card_name"
                                                 value="{{ $customer->prefix_name }} {{ $customer->first_name }} {{ $customer->last_name }}"
                                                 readonly="">
                                         </div>
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="เบอร์โทรศัพท์" name="card_tel_mobile"
                                                 value="{{ $address->tel_mobile }}" readonly="">
                                         </div>
                                     </div>
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>Email</label>
                                             <input type="email" class="form-control form-control-bold" placeholder="Email"
                                                 name="card_email" value="{{ $customer->email }}" readonly="">
                                         </div>

                                     </div>

                                     <div class="row m-t-5">
                                         <div class="col-md-3 col-sm-4 col-4">
                                             <label>บ้านเลขที่ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="บ้านเลขที่" name="card_house_no"
                                                 value="{{ $address_card->card_house_no }}" readonly="">
                                         </div>
                                         <div class="col-md-5 col-sm-8 col-8">
                                             <label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="หมู่บ้าน/อาคาร" name="card_house_name"
                                                 value="{{ $address_card->card_house_name }}" readonly="">
                                         </div>
                                         <div class="col-md-3 col-sm-12 col-12">
                                             <label>หมู่ที่ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold" placeholder="หมู่ที่"
                                                 name="card_moo" value="{{ $address_card->card_moo }}" readonly="">
                                         </div>

                                     </div>
                                     <div class="row m-t-5">
                                         <div class="col-sm-4">
                                             <label>ถนน</label>
                                             <input type="text" class="form-control form-control-bold" placeholder="ถนน"
                                                 name="card_road" value="{{ $address_card->card_road }}" readonly="">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>ตรอก/ซอย </label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="ตรอก/ซอย" name="card_soi"
                                                 value="{{ $address_card->card_soi }}" readonly="">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>จังหวัด <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold" placeholder="จังหวัด"
                                                 value="{{ $address_card->card_provinces_name }}" readonly="">
                                             <input type="hidden" name="card_province"
                                                 value="{{ $address_card->card_province_id_fk }}">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>เขต/อำเภอ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="เขต/อำเภอ" name="card_amphures"
                                                 value="{{ $address_card->card_amphures_name }}" readonly="">
                                             <input type="hidden" name="card_amphures"
                                                 value="{{ $address_card->card_amphures_id_fk }}">
                                         </div>
                                         <div class="col-sm-4">
                                             <label>แขวง/ตำบล <b class="text-danger">*</b> </label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="แขวง/ตำบล" value="{{ $address_card->card_district_name }}"
                                                 readonly="">
                                             <input type="hidden" name="card_district"
                                                 value="{{ $address_card->card_district_id_fk }}">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>รหัสไปษณีย์ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="รหัสไปษณีย์" name="card_zipcode"
                                                 value="{{ $address_card->card_zipcode }}" readonly="">
                                         </div>
                                     </div>

                                 </div>

                                 <div id="sent_office" style="display: none;">
                                     <div class="row m-t-5">
                                         <div class="col-sm-12">
                                             <select name="receive_location" class="form-control" readonly="">

                                                 @foreach ($location as $value)

                                                     <option value="{{ $value->id }}">{{ $value->b_name }}
                                                         ({{ $value->b_details }})</option>
                                                 @endforeach
                                             </select>
                                         </div>

                                     </div>
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="ชื่อผู้รับ" name="office_name" id="office_name" value="">
                                         </div>
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="เบอร์โทรศัพท์" id="office_tel_mobile"
                                                 name="receive_tel_mobile" value="">
                                         </div>
                                     </div>

                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>Email </label>
                                             <input type="email" class="form-control form-control-bold" placeholder="Email"
                                                 name="office_email" value="{{ $customer->email }}">
                                         </div>

                                     </div>
                                 </div>

                                 <div id="sent_address_other" style="display: none;">
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>ชื่อผู้รับ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold sent_address_other"
                                                 placeholder="ชื่อผู้รับ" name="other_name" id="other_name">

                                         </div>
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>เบอร์โทรศัพท์ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold sent_address_other"
                                                 placeholder="เบอร์โทรศัพท์" name="other_tel_mobile" id="other_tel_mobile">
                                         </div>
                                     </div>
                                     <div class="row m-t-5">
                                         <div class="col-md-6 col-sm-6 col-6">
                                             <label>Email </label>
                                             <input type="email" class="form-control form-control-bold" placeholder="Email"
                                                 name="other_email" id="other_email">
                                         </div>

                                     </div>

                                     <div class="row m-t-5">
                                         <div class="col-md-3 col-sm-4 col-4">
                                             <label>บ้านเลขที่ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold sent_address_other"
                                                 placeholder="บ้านเลขที่" name="other_house_no" id="other_house_no">
                                         </div>
                                         <div class="col-md-5 col-sm-8 col-8">
                                             <label>หมู่บ้าน/อาคาร <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold sent_address_other"
                                                 placeholder="หมู่บ้าน/อาคาร" name="other_house_name" id="other_house_name">
                                         </div>
                                         <div class="col-md-3 col-sm-12 col-12">
                                             <label>หมู่ที่ <b class="text-danger">*</b></label>
                                             <input type="text" class="form-control form-control-bold sent_address_other"
                                                 placeholder="หมู่ที่" name="other_moo" id="other_moo">
                                         </div>
                                     </div>

                                     <div class="row m-t-5">
                                         <div class="col-sm-4">
                                             <label>ถนน</label>
                                             <input type="text" class="form-control form-control-bold" placeholder="ถนน"
                                                 name="other_road">
                                         </div>

                                         <div class="col-sm-4">
                                             <label>ตรอก/ซอย </label>
                                             <input type="text" class="form-control form-control-bold"
                                                 placeholder="ตรอก/ซอย" name="other_soi">
                                         </div>
                                         <div class="col-sm-4">
                                             <label>จังหวัด <font class="text-danger">*</font></label>
                                             <select class="js-example-basic-single col-sm-12 sent_address_other"
                                                 id="province" name="other_province">
                                                 <option value=""> Select </option>
                                                 @foreach ($provinces as $value_provinces)
                                                     <option value="{{ $value_provinces->id }}">
                                                         {{ $value_provinces->name_th }}</option>
                                                 @endforeach
                                             </select>
                                         </div>


                                     </div>

                                     <div class="form-group row m-t-5">

                                         <div class="col-sm-4">
                                             <label>เขต/อำเภอ <font class="text-danger">*</font></label>
                                             <select class="js-example-basic-single col-sm-12 sent_address_other"
                                                 name="other_amphures" id="amphures">
                                                 <option value=""> Select </option>
                                             </select>
                                         </div>

                                         <div class="col-sm-4">
                                             <label>แขวง/ตำบล <font class="text-danger">*</font></label>
                                             <select class="js-example-basic-single col-sm-12 sent_address_other"
                                                 name="other_district" id="district">
                                                 <option value=""> Select </option>

                                             </select>
                                         </div>
                                         <div class="col-sm-4">
                                             <label>รหัสไปษณีย์</label>
                                             <input type="text" class="form-control sent_address_other"
                                                 placeholder="รหัสไปษณีย์" id="other_zipcode" name="other_zipcode" value="">
                                         </div>

                                     </div>

                                 </div>

                                 <div class="row m-t-5">
                                     <div class="col-sm-12">

                                         {{-- <a href="{{ route('product-list',['type'=>$bill['type']]) }}" class="btn btn-warning">
               <font style="color: #000">เลือกสินค้าเพิ่มเติม</font>
             </a> --}}

                                         <a type="button" href="{{ route('product-list', ['type' => $bill['type']]) }}"
                                             class="btn btn-warning waves-effect waves-light m-t-20"><i
                                                 class="fa fa-shopping-cart"></i> เลือกสินค้าเพิ่มเติม</a>



                                         <button type="button" onclick="next()"
                                             class="btn btn-primary waves-effect waves-light m-t-20 f-right"><i
                                                 class="fa fa-credit-card-alt"></i> ชำระเงิน</button>

                                     </div>
                                 </div>
                             </div>
                         </div>

                         @if ($bill['type'] == 6)
                             <div class="tab-pane active" id="credit-card" role="tabpanel">
                             @else
                                 <div class="tab-pane" id="credit-card" role="tabpanel">
                         @endif


                         @if ($bill['price_total_type5'] == 0 and $bill['type'] == 5)
                             <div class="row">
                                 <div class="col-md-12 col-xl-12">
                                     <div class="card bg-c-green order-card m-b-0">
                                         <div class="card-block">
                                             <div class="row">
                                                 <div class="col-md-8 col-sx-8 col-8">
                                                     <h6 class="m-b-10" style="font-size: 16px">Gift Voucher </h6>
                                                 </div>
                                                 <div class="col-md-4 col-sx-4 col-4">
                                                     <?php $gv =
                                                     \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
                                                     ?>
                                                     <h3 class="text-right">
                                                         {{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format($gv->sum_gv) }}
                                                         </span></h3>
                                                 </div>
                                             </div>


                                             <hr>

                                             <div class="row">
                                                 <div class="col-md-8 col-sx-8 col-8">
                                                     <h6 class="m-b-10" style="font-size: 16px">ยอดรวมที่ใช้ </h6>

                                                 </div>
                                                 <div class="col-md-4 col-sx-4 col-4">

                                                     <h3 class="text-right"> <span class="price_total">
                                                             {{ number_format($bill['price_total']) }} </span></h3>
                                                 </div>
                                             </div>

                                             <hr>
                                             <div class="row">
                                                 <div class="col-md-8 col-sx-8 col-8">
                                                     <h6 style="font-size: 16px">Gift Voucher คงเหลือ </h6>

                                                 </div>
                                                 <div class="col-md-4 col-sx-4 col-4">

                                                     <h3 class="text-right"> <span class="gv_remove_price">
                                                             {{ number_format($bill['gv_total'], 2) }} </span></h3>
                                                 </div>
                                             </div>
                                         </div>

                                     </div>

                                     <div class="row m-t-5">
                                         <div class="col-sm-6">
                                         </div>
                                         <div class="col-sm-6 text-right">
                                             <button class="btn btn-success btn-block" type="submit" name="submit"
                                                 value="gift_voucher">ชำระเงิน</button>
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
                                                     <input type="radio" id="bank" onchange="open_input(1)" name="pay_type"
                                                         value="1" checked="checked">
                                                     <i class="helper"></i><b>โอนชำระ</b>
                                                 </label>
                                             </div>
                                             <div class="radio radio-inline">
                                                 <label>
                                                     <input type="radio" onchange="open_input(2)" id="credit_cart"
                                                         name="pay_type" value="2">
                                                     <i class="helper"></i><b>บัตรเครดิต</b>
                                                 </label>
                                             </div>

                                             <div class="radio radio-inline">
                                                 <label>
                                                     <input type="radio" onchange="open_input(3)" id="ai_cash"
                                                         name="pay_type" value="3">
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

                                 <div class="row" id="cart_pament_tranfer">

                                     <div class="form-group row">
                                         <div class="col-sm-12">

                                             <div class="form-group row">
                                                 <div class="col-sm-6">
                                                     <label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">( JPG,PNG )</b>
                                                     </label>
                                                     <input type="file" id="upload" name="file_slip" class="form-control">
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="row">
                                             <div class="col-xs-6 p-1">
                                                 <button class="btn btn-success btn-block" type="submit" name="submit"
                                                     id="submit_upload" value="upload">อัพโหลดหลักฐานการชำระเงิน</button>
                                             </div>

                                             <div class="col-xs-6 p-1">
                                                 <button class="btn btn-primary btn-block" type="submit" name="submit"
                                                     value="not_upload">อัพโหลดหลักฐานการชำระเงินภายหลัง</button>
                                             </div>
                                         </div>
                                     </div>

                                 </div>

                                 <div class="row" id="cart_pament_credit_card" style="display: none">

                                     <div class="col-sm-12 col-md-12">
                                         <div class="card card-border-success">

                                             <div class="card-block" style="padding: 10px">
                                                 <div class="col-md-12">

                                                     <div class="row mt-2">
                                                         <div class="col-md-8 col-sx-8 col-8">
                                                             <h4 class="m-b-10"> ยอดที่ต้องชำระ </h4>
                                                         </div>

                                                         <div class="col-md-4 col-sx-4 col-4">
                                                             <h3 class="text-right">
                                                                 <u><span class="price_total">  </span></u>
                                                             </h3>
                                                         </div>
                                                     </div>
                                                     <hr>
                                                 </div>
                                             </div>
                                             <div class="card-footer">
                                                 <div class="text-right">
                                                     <button class="btn btn-success" name="submit"
                                                         value="credit_card" type="submit">ชำระเงินด้วยบัตรเครดิต</button>
                                                 </div>

                                                 <!-- end of card-footer -->
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="row" id="cart_pament_aicash" style="display: none;">
                                     <div class="col-sm-12 col-md-12">
                                         <div class="card card-border-success">

                                             <div class="card-block" style="padding: 10px">
                                                 <div class="col-md-12">
                                                     <div class="row">
                                                         <div class="col-md-8 col-sx-8 col-8">
                                                             <h4 class="m-b-10">Ai-Cash</h4>
                                                         </div>
                                                         <div class="col-md-4 col-sx-4 col-4">
                                                             <h3 class="text-right">
                                                                 <span id="ai_cash_p"> {{ number_format(Auth::guard('c_user')->user()->ai_cash) }} </span>
                                                             </h3>
                                                         </div>
                                                     </div>
                                                     <hr>
                                                     <div class="row">
                                                         <div class="col-md-8 col-sx-8 col-8">
                                                             <h4 class="m-b-10"> ยอดรวมที่ใช้ </h4>
                                                         </div>
                                                         <div class="col-md-4 col-sx-4 col-4">
                                                             <h3 class="text-right">
                                                                 <u><span class="price_total"> </span></u>
                                                             </h3>
                                                         </div>
                                                     </div>
                                                     <hr>
                                                 </div>
                                             </div>
                                             <div class="card-footer">
                                               <div id="error_aicash" class="text-right"></div>
                                                 <div class="text-right">
                                                     <button class="btn btn-success" name="submit" id="ai_cash_submit" value="ai_cash"
                                                         type="submit">ชำระเงินด้วย Ai-Cash</button>
                                                 </div>
                                             </div>
                                             <!-- end of card-footer -->
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
                 <h4>สรุปรายการสั่งซื้อ</h4>
                 {{-- <span class="label label-default f-right"> 28 January, 2015 </span> --}}
             </div>
             <div class="card-block" style="padding: 10px">
                 <div class="col-md-12">
                     <table class="table table-responsive m-b-0">
                         <tr>
                             <td><strong id="quantity_bill">มูลค่าสินค้า ({{ $bill['quantity'] }}) ชิ้น</strong></td>
                             <td align="right"><strong id="price"> {{ number_format($bill['price_vat'], 2) }} </strong>
                             </td>
                         </tr>
                         <tr>
                             <td><strong>Vat({{ $bill['vat'] }}%)</strong></td>
                             <td align="right"><strong> {{ number_format($bill['p_vat'], 2) }}</strong></td>
                         </tr>
                         <tr>
                             <td><strong>มูลค่าสินค้า + Vat</strong></td>
                             <td align="right"><strong> {{ number_format($bill['price'], 2) }}</strong></td>
                         </tr>

                         @if ($bill['type'] != 6)

                             <tr>
                                 <td><strong>ค่าจัดส่ง</strong></td>
                                 <td align="right"><strong
                                         id="shipping">{{ number_format($bill['shipping'], 2) }}</strong> </td>
                             </tr>
                             <tr>
                                 <td colspan="2">
                                     <label class="label label-inverse-warning">
                                         <font id="shipping_detail" style="color: #000"></font>
                                     </label>

                             </tr>

                         @endif
                         <tr>
                             <td><strong>คะแนนที่ได้รับ</strong></td>
                             <td align="right"><strong class="text-success" id="pv">{{ $bill['pv_total'] }} PV</strong>
                             </td>
                         </tr>
                         @if ($bill['type'] == 5)
                             <?php $gv =
                             \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name); ?>
                             <tr>
                                 <td><strong>ยอดรวม</strong></td>
                                 <td align="right"><strong class="price_total">
                                         {{ number_format($bill['price_total'], 2) }}</strong>
                                 </td>
                             </tr>
                             <tr>
                                 <td><strong class="text-primary">Gift Voucher</strong></td>
                                 <td align="right"><strong class="text-primary"> {{ $gv->sum_gv }}</strong>
                                 </td>
                             </tr>
                             <tr>
                                 <td><strong class="text-primary" style="font-size: 13px">Gift Voucher คงเหลือ</strong></td>
                                 <td align="right"><strong class="text-primary gv_remove_price">
                                         {{ $bill['gv_total'] }}</strong>
                                 </td>
                             </tr>

                             <tr>
                                 <td><strong>ยอดที่ต้องชำระเพิ่ม</strong></td>
                                 <td align="right"><strong class="price_total_type5">
                                         {{ number_format($bill['price_total_type5'], 2) }}</strong>

                                 </td>
                             </tr>
                         @elseif($bill['type'] == 6)
                             <tr>
                                 <td><strong>ยอดที่ต้องชำระ</strong></td>
                                 <td align="right"><strong> {{ $bill['price'] }} </strong>
                                 </td>
                             </tr>

                         @else
                             <tr>
                                 <td><strong>ยอดที่ต้องชำระ</strong></td>
                                 <td align="right"><u><strong class="price_total"></strong></u>
                                 </td>
                             </tr>
                         @endif


                     </table>
                     <hr m-t-2>
                     @if ($check_giveaway['status'] == 'success' and $check_giveaway['s_data'])
                         <h4 class="text-danger">Promotion Free </h4>


                         <table class="table table-responsive m-b-0">
                             @foreach ($check_giveaway['s_data'] as $giveaway_value)
                                 <?php
                                 //dd($giveaway_value);
                                 ?>
                                 <tr class="table-danger">
                                     <td><strong>{{ $giveaway_value['name'] }} x
                                             [{{ $giveaway_value['count_free'] }}]</strong></td>
                                     <td>รวม</td>
                                 </tr>
                                 @if ($giveaway_value['type'] == 1)
                                     {{-- Product --}}
                                     @foreach ($giveaway_value['product'] as $product_value)
                                         <tr>
                                             <td><strong style="font-size: 12px">{{ $product_value->product_name }}
                                                     [{{ $product_value->product_amt }}] x
                                                     [{{ $giveaway_value['count_free'] }}]</strong></td>
                                             <td align="right"><strong>
                                                     {{ $product_value->product_amt * $giveaway_value['count_free'] }}
                                                     {{ $product_value->product_unit }}</strong></td>
                                         </tr>
                                     @endforeach
                                 @else
                                     {{-- GiftVoucher --}}
                                     <tr>
                                         <td><strong style="font-size: 12px">GiftVoucher {{ $giveaway_value['gv'] }} x
                                                 [{{ $giveaway_value['count_free'] }}]</strong></td>
                                         <?php $gv_total = $giveaway_value['gv'] *
                                         $giveaway_value['count_free']; ?>
                                         <td align="right"><strong> {{ number_format($gv_total) }} GV</strong></td>
                                     </tr>
                                 @endif
                             @endforeach

                         </table>
                     @endif
                     @if($bill['type'] != 6)
                     <div class="row" align="center">
                      <div class="form-control bootstrap-tagsinput" id="html_shipping_premium">
                          <div class="checkbox-color checkbox-success">
                              <input id="checkbox13" type="checkbox" onchange="check_premium()" value="true">
                              <label for="checkbox13"> ส่งแบบพิเศษ / Premium
                              </label>
                          </div>
                      </div>

                  </div>
                  @endif


                 </div>

             </div>
             <div class="card-footer">
             </div>
             <!-- end of card-footer -->
         </div>
     </div>
     </div>


     <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-md" role="document">

             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">กรุณาเช็คข้อมูลก่อนยืนยัน</h4>
                 </div>

                 <div class="modal-body">

                     <div class="col-md-12 col-xl-12">
                         <div class="card bg-c-yellow order-card m-b-0">
                             <div class="card-block">
                                 <div class="row">
                                     <div class="col-md-12">
                                         <h5 id="modal_text_username" style="color: #000"></h5>
                                         <h6 class="m-b-0" id="modal_name" style="color: #000"></h6>

                                     </div>

                                 </div>
                                 <div class="row">
                                     <div class="col-md-12">
                                         <h6 class="m-b-0" style="color: #000"><i class="fa fa-star p-2 m-b-0"></i>
                                             <b id="m_qualification_name"></b>
                                         </h6>

                                     </div>

                                 </div>
                                 <hr class="m-b-5 m-t-5">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <h5 class="m-b-0" style="color: #000">คะแนนสะสม</h5>
                                     </div>
                                     <div class="col-md-6">
                                         <h5 class="m-b-0 text-right" id="modal_text_pv" style="color: #000"></h5>
                                     </div>
                                 </div>
                                 <hr class="m-b-5 m-t-5">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <p class="m-b-0" style="color: #000"><b>สถานะรักษาคุณสมบัติรายเดือน</b></p>
                                     </div>
                                     <div class="col-md-6 text-right">
                                         <div id="modal_pv_tv_active"></div>
                                     </div>
                                 </div>

                                 <hr class="m-b-5 m-t-5">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <p class="m-b-0" style="color: #000"><b>สถานะรักษาคุณสมบัติท่องเที่ยว</b></p>
                                     </div>
                                     <div class="col-md-6 text-right">
                                         <div id="modal_pv_mt_active"></div>
                                     </div>
                                 </div>

                             </div>
                         </div>
                     </div>
                     <br>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                     <div id="confirm_pay_sent_customer"></div>

                 </div>
             </div>
         </div>
     </div>


 @endsection
 @section('js')
     <!-- Select 2 js -->
     <script src="{{ asset('frontend/bower_components/select2/js/select2.full.min.js') }}"></script>
     <!-- Multiselect js -->
     <script src="{{ asset('frontend/bower_components/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
     </script>
     <script src="{{ asset('frontend/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
     <script src="{{ asset('frontend/assets/js/jquery.quicksearch.js') }}"></script>
     <!-- Custom js -->
     <script src="{{ asset('frontend/assets/pages/advance-elements/select2-custom.js') }}"></script>

     @if ($bill['price_total_type5'] > 0 and $bill['type'] == 5)
         <script type="text/javascript">
             document.getElementById("submit_upload").disabled = true;
             document.getElementById("submit_upload").className = "btn btn-inverse btn-block";

         </script>
     @elseif($bill['type'] != 5)
         <script type="text/javascript">
             document.getElementById("submit_upload").disabled = true;
             document.getElementById("submit_upload").className = "btn btn-inverse btn-block";

         </script>
     @endif

     <script type="text/javascript">
         var address_provinces_id = {{ $address->provinces_id }};
         check_shipping({{ $address->provinces_id }});
         var type = '{{ $bill['type'] }}';
         if( type == 6){
          document.getElementById('shipping_premium').value = false;
         }else{
          var premium = document.getElementById('checkbox13').checked;
              if (premium) {
                  document.getElementById('shipping_premium').value = true;
              } else {
                document.getElementById('shipping_premium').value = false;
              }
         }


         //console.log(data_1);
         function check_premium() {
             var sent_address = document.getElementById('sent_address_check').checked;
             var sent_address_card = document.getElementById('sent_address_card_check').checked;
             var sent_other = document.getElementById('sent_other').checked;
             var premium = document.getElementById('checkbox13').checked;

             if (premium) {
                 document.getElementById('shipping_premium').value = true;
             } else {
                 document.getElementById('shipping_premium').value = false;
             }

             if (sent_address) {
                 check_shipping({{ $address->provinces_id }});
             }
             if (sent_address_card) {
                 var sent_address = document.getElementById('sent_address_check').checked;
                 check_shipping({{ $address_card->provinces_id }});
             }

             if (sent_other) {
                 var sent_other = document.getElementById('province').value;
                 if (sent_other) {
                     check_shipping(sent_other);
                 }
             }
         }

         function check_shipping(provinces_id, type_sent = '') {
             var location_id = '{{ $bill['location_id'] }}';
             var price = '{{ $bill['price'] }}';

             var type = '{{ $bill['type'] }}';
             if (type != 6) {
             var shipping_premium = document.getElementById('checkbox13').checked;
             }

             if (type == 5) {
                 var sum_gv = '{{ @$gv->sum_gv }}';
             } else {
                 var sum_gv = null;
             }

             $.ajax({
                 type: "POST",
                 url: "{{ route('check_shipping_cos') }}",
                 data: {
                     _token: '{{ csrf_token() }}',
                     location_id: location_id,
                     provinces_id: provinces_id,
                     price: price,
                     shipping_premium: shipping_premium,
                     type: type,
                     sum_gv: sum_gv,
                     type_sent: type_sent,
                 },
                 success: function(data) {
                  if (type != 6) {
                     document.getElementById('shipping_detail').textContent = data['data']['shipping_name'];
                  }
                     var shipping_cost = data['shipping_cost'];
                     var price_total = data['price_total'];

                     if (type == 5) {
                         var price_total_type5 = numberWithCommas(data['price_total_type5']);
                         $('.price_total_type5').html(price_total_type5);

                         $('#price_total_type5').val(data['price_total_type5']);
                     }

                     if (type != 6) {
                     document.getElementById('shipping').textContent = shipping_cost;
                     }

                     var price_total_view = numberWithCommas(price_total);

                     $('.price_total').html(price_total_view);

                     $('#price_total').val(price_total);

                     $('#gift_voucher_price').val(data['gift_voucher_price']);
                     $('.gv_remove_price').html(data['gv_remove_price']);

                     //document.getElementsByClassName('.price_total').textContent = price_total;
                     //console.log(data);
                 }
             });
         }

         function sent_address(type_sent, provinces_id) {

             if (type_sent == 'sent_address') {

                 check_shipping({{ $address->provinces_id }});
                 document.getElementById("sent_address").style.display = 'block';
                 document.getElementById("sent_address_card").style.display = 'none';
                 document.getElementById("sent_address_other").style.display = 'none';
                 document.getElementById("sent_office").style.display = 'none';
                 $('.sent_address_other').prop('required', false);
                 document.getElementById("html_shipping_premium").style.display = 'block';
                 document.getElementById("sent_address_check").checked = true;

             } else if (type_sent == 'sent_address_card') {

                 check_shipping(provinces_id);
                 document.getElementById("sent_address").style.display = 'none';
                 document.getElementById("sent_address_card").style.display = 'block';
                 document.getElementById("sent_address_other").style.display = 'none';
                 document.getElementById("sent_office").style.display = 'none';
                 $('.sent_address_other').prop('required', false);
                 document.getElementById("html_shipping_premium").style.display = 'block';

             } else if (type_sent == 'sent_office') {
                 check_shipping(provinces_id = '', type_sent);
                 var price = numberWithCommas('{{ $bill['price'] }}');
                 document.getElementById("sent_address").style.display = 'none';
                 document.getElementById("sent_address_card").style.display = 'none';
                 document.getElementById("sent_address_other").style.display = 'none';
                 document.getElementById("sent_office").style.display = 'block';
                 $('.sent_address_other').prop('required', false);

                 document.getElementById('shipping').textContent = 0;
                 document.getElementById("html_shipping_premium").style.display = 'none';
                 document.getElementById("checkbox13").checked = false;

             } else if (type_sent == 'sent_other') {
                 document.getElementById("sent_address").style.display = 'none';
                 document.getElementById("sent_address_card").style.display = 'none';
                 document.getElementById("sent_address_other").style.display = 'block';
                 document.getElementById("sent_office").style.display = 'none';

                 document.getElementById('shipping').textContent = 0;
                 document.getElementById("html_shipping_premium").style.display = 'block';
                 $('.sent_address_other').prop('required', true);
                 document.getElementById("checkbox13").checked = false;
                 document.getElementById("sent_other").checked = true;

             } else {

                 Swal.fire({
                     icon: 'error',
                     title: 'เลือกที่อยู่ไม่ถูกต้อง',
                 })

                 alert('เลือกที่อยู่ไม่ถูกต้อง');
             }


         }

         function open_input(data) {
             var conten_4 = '<button class="btn btn-success btn-block" type="submit">ชำระเงิน</button>';

             if (data == '1') {

                 check_shipping({{ $address->provinces_id }});
                 document.getElementById("cart_pament_tranfer").style.display = "block";
                 document.getElementById("cart_pament_credit_card").style.display = "none";
                 document.getElementById("cart_pament_aicash").style.display = "none";


                 document.getElementById("submit_upload").disabled = true;
                 document.getElementById("submit_upload").className = "btn btn-success";
                 $('#upload').change(function() {
                     var fileExtension = ['jpg', 'png'];
                     if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                         alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
                         this.value = '';
                         return false;
                     } else {
                         document.getElementById("submit_upload").disabled = false;
                     }
                 });
             } else if (data == '2') {
                 document.getElementById("cart_pament_tranfer").style.display = "none";
                 document.getElementById("cart_pament_credit_card").style.display = "block";
                 document.getElementById("cart_pament_aicash").style.display = "none";
             } else if (data == '3') {


               var ai_cash = {{ Auth::guard('c_user')->user()->ai_cash }};
               var price_total = $('#price_total').val();

               if(ai_cash < price_total){
                document.getElementById("ai_cash_submit").style.display = "none";
                $('#error_aicash').html('<label class="label label-inverse-danger text-right">Ai-Cash ไม่พอสำหรับการชำระเงิน</label>');
               }

                 document.getElementById("cart_pament_tranfer").style.display = "none";
                 document.getElementById("cart_pament_credit_card").style.display = "none";
                 document.getElementById("cart_pament_aicash").style.display = "block";

             } else if (data == '4') {
                 document.getElementById("cart_pament").innerHTML = (conten_4);
             } else {
                 document.getElementById("cart_pament").innerHTML = (conten_1);
             }
         }

     </script>

     <script type="text/javascript">
         $('#province').change(function() {
             var province = $(this).val();
             check_shipping(province);
             $.ajax({
                 async: false,
                 type: "get",
                 url: "{{ route('location') }}",
                 data: {
                     id: province,
                     function: 'provinces'
                 },
                 success: function(data) {
                     $('#amphures').html(data);
                     $('#district').val('');
                     // $('#zipcode').val('');
                 }
             });

         });


         $('#amphures').change(function() {
             var amphures = $(this).val();
             $('#province').change(function() {
                 var province = $(this).val();

                 $.ajax({
                     async: false,
                     type: "get",
                     url: "{{ route('location') }}",
                     data: {
                         id: province,
                         function: 'provinces'
                     },
                     success: function(data) {
                         $('#amphures').html(data);
                         $('#district').val('');
                         // $('#zipcode').val('');
                     }
                 });

             });
             $.ajax({
                 async: false,
                 type: "get",
                 url: "{{ route('location') }}",
                 data: {
                     id: amphures,
                     function: 'amphures'
                 },
                 success: function(data) {
                     $('#district').html(data);
                 }
             });
         });

         $('#district').change(function() {
             var district = $(this).val();
             $.ajax({
                 type: "get",
                 url: "{{ route('location') }}",
                 data: {
                     id: district,
                     function: 'district'
                 },
                 success: function(data) {
                     $('#other_zipcode').val(data);
                 }
             });

         });


         function check() {
             //var url = '{{ route('cart_delete') }}';
             var username = $('#username').val();
             $.ajax({
                     url: '{{ route('check_customer_id') }}',
                     type: 'POST',
                     data: {
                         _token: '{{ csrf_token() }}',
                         'user_name': username
                     }
                 })
                 .done(function(data) {
                     console.log(data['data']);
                     if (data['status'] == 'success') {
                         document.getElementById("modal_text_username").innerHTML = data['data']['data'][
                                 'business_name'
                             ] +
                             ' (' + data['data']['data']['user_name'] + ')';

                         document.getElementById("modal_name").innerHTML = data['data']['data']['prefix_name'] + ' ' +
                             data[
                                 'data']['data']['first_name'] + ' ' + data['data']['data']['last_name'];

                         document.getElementById("modal_text_pv").innerHTML = data['data']['data']['pv'] + ' PV';
                         $("#input_username").val(data['data']['data']['user_name']);

                         document.getElementById("modal_pv_tv_active").innerHTML = data['pv_tv_active'];
                         document.getElementById("modal_pv_mt_active").innerHTML = data['pv_mt_active'];

                         document.getElementById("m_qualification_name").innerHTML = data['data']['data'][
                             'qualification_name'
                         ];

                         var sent_to_customer_username = data['data']['data']['user_name'];
                         document.getElementById("confirm_pay_sent_customer").innerHTML =
                             '<button  class="btn btn-success" onclick="data_direct_confirm(\'' +
                             sent_to_customer_username + '\')">Confirm</button>';

                         $("#large-Modal").modal();
                         //alert(data['status']);

                     } else {
                         console.log(data);
                         Swal.fire({
                             title: data['data']['message'],
                             // text: "You won't be able to revert this!",
                             icon: 'warning',
                             showConfirmButton: false,
                             showCancelButton: true,
                             confirmButtonColor: '#3085d6',
                             cancelButtonColor: '#d33',
                             confirmButtonText: 'Yes, delete it!'
                         }).then((result) => {
                             if (result.isConfirmed) {
                                 //$( "#cart_delete" ).attr('action',url);
                                 // $('#data_id').val(item_id);
                                 //$( "#cart_delete" ).submit();
                                 Swal.fire(
                                     'Deleted!',
                                     'Your file has been deleted.',
                                     'success'
                                 )

                             }
                         })
                     }
                 })
                 .fail(function() {
                     console.log("error");
                 })
         }

     </script>
     <script src="{{ asset('frontend/custom/cart_payment/other.js') }}"></script>{{-- js อื่นๆ --}}
     <script src="{{ asset('frontend/custom/cart_payment/sent_type.js') }}"></script>{{-- ส่งให้ตัวเอง หรือส่งให้คนอื่น --}}

     {{-- <script src="{{ asset('frontend/assets/pages/payment-card/card.js') }}"></script>
     <script src="{{ asset('frontend/assets/pages/payment-card/jquery.payform.min.js') }}" charset="utf-8"></script>
     <script src="{{ asset('frontend/assets/pages/payment-card/e-payment.js') }}"></script> --}}



 @endsection
