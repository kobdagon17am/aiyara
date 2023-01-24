<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register AIYARA</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {{-- <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="codedthemes" /> --}}
    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('frontend/assets/icon/logo_icon.png')}}" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/bootstrap/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/icon/themify-icons/themify-icons.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/icon/icofont/css/icofont.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/assets/icon/font-awesome/css/font-awesome.min.css') }}">
    <!-- Syntax highlighter Prism css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/pages/prism/prism.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/pcoded-horizontal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/bower_components/select2/css/select2.min.css') }}" />
    <!-- Multi Select css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/multiselect/css/multi-select.css') }}">

        <style>
          .icons-alert:before {
              top: 11px;
          }

          .pcoded-main-container {
            margin-top: 0px !important;
          }

          .pcoded .pcoded-header[header-theme="theme5"] {
             background: linear-gradient(to right, #18c160, #18c160);
            }

      </style>

</head>
<!-- Menu horizontal icon fixed -->

<body class="horizontal-icon-fixed">

    <div id="pcoded" class="pcoded">

        <div class="pcoded-container">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">


                        <img class="img-fluid" src="{{ asset('frontend/assets/images/logo.png') }}" width="140"
                            alt="Theme-Logo" />


                    </div>


                </div>
            </nav>

            <div class="pcoded-main-container">

                <div class="pcoded-wrapper ">
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">

                                    <div class="card">
                                        <div class="card-header">
                                            <h4>กรอกข้อมูลลงทะเบียนสมาชิกใหม่</h4>
                                        </div>

                                        <div class="card-block ">

                                            <form action="{{ route('register_member_salepage') }}"
                                                id="register_member_salepage" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label><b>รหัสผู้แนะนำ</b></label>
                                                        <span class=" form-control pcoded-badge label label-success"
                                                            style="font-size: 15px;padding: 9px 9px;">
                                                            <font style="color: #000;">
                                                                {{ $data['data']->business_name }} (
                                                                {{ $data['data']->user_name }} ) </font>
                                                        </span>
                                                        {{-- <input type="text" class="form-control"   placeholder="Upline ID" value="{{$data['data']->user_name}}" disabled=""> --}}
                                                        <input type="hidden" name="upline_id"
                                                            value="{{ $data['data']->user_name }}">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label><b>สายงาน</b></label>
                                                        <span class=" form-control pcoded-badge label label-success"
                                                            style="font-size: 15px;padding: 9px 9px;">
                                                            <font style="color: #000;">{{ $data['line_type_back'] }}
                                                            </font>
                                                        </span>

                                                        {{-- <input type="text" class="form-control"   placeholder="สายงาน" value="{{$data['line_type_back']}}" disabled=""> --}}
                                                        <input type="hidden" name="line_type_back"
                                                            value="{{ $data['line_type_back'] }}">

                                                    </div>

                                                    {{-- <div class="col-sm-3">
                                                        <label><b>รหัสผู้แนะนำ</b></label>

                                                        <div class="input-group input-group-button">
                                                            <input type="text" class="form-control" name="introduce"
                                                                id="introduce" placeholder="รหัสผู้แนะนำ"
                                                                value="{{ Auth::guard('c_user')->user()->user_name }}">
                                                            <span class="input-group-addon btn btn-primary"
                                                                id="basic-addon10" onclick="check_user()">
                                                                <span class=""><i
                                                                        class=" icofont icofont-check-circled"></i>
                                                                    Check</span>
                                                            </span>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-sm-3">
                                                        <label><b> Business Location </b></label>
                                                        <select name="business_location" id="business_location" class="form-control">
                                                            @foreach ($data['business_location'] as $business_location_value)
                                                                <option value="{{ $business_location_value->id }}"
                                                                    @if ($business_location_value->id == $data['data']->business_location_id) selected="" @endif>
                                                                    {{ $business_location_value->txt_desc }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-2">
                                                      <label> @lang('message.name_prefix')  </label>
                                                      <select class="form-control" name="name_prefix">
                                                          <option value="@lang('message.mr')">@lang('message.mr')</option>
                                                          <option value="@lang('message.mrs')">@lang('message.mrs')</option>
                                                          <option value="@lang('message.ms')">@lang('message.ms')</option>
                                                      </select>

                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>@lang('message.name_first') <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="@lang('message.name_first')"
                                                            name="name_first" value="{{ old('name_first') }}"
                                                            required="">

                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>@lang('message.name_last') <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="@lang('message.name_last')"
                                                            name="name_last" value="{{ old('name_last') }}"
                                                            required="">
                                                    </div>


                                                    <div class="col-sm-4">
                                                        <label>@lang('message.name_business') <font class="text-danger">@lang('message.name_business_a')</font>
                                                            </label>
                                                        <input type="text" class="form-control"
                                                            placeholder="@lang('message.name_business')" name="name_business"
                                                            value="{{ old('name_business') }}" required="">
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
                                                        <input class="form-control" type="date" name="birth_day"
                                                            value="{{ old('birth_day') }}">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>เลขที่บัตรประชาชน <font class="text-danger">*</font>
                                                            </label>
                                                        <input class="form-control" id="id_card" type="text" name="id_card"
                                                            value="{{ old('id_card') }}"   required="">
                                                            <span class="error text-danger"></span>
                                                    </div>



                                                    <div class="col-sm-3">
                                                        <label>สัญชาติ <font class="text-danger">*</font></label>
                                                        <select class="js-example-basic-single col-sm-12"
                                                            name="nation_id" required="">
                                                            <option value="">Select</option>
                                                            @foreach ($data['country'] as $country_value)
                                                                <option value="{{ $country_value->nation_id }}"
                                                                    @if ($country_value->nation_id == $data['data']->nation_id || $country_value->nation_id == old('nation_id')) selected="" @endif>
                                                                    {{ $country_value->nation_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>โทรศัพท์มือถือ <b class="text-danger">*</b></label>
                                                        <input type="text" class="form-control  us_telephone" autocomplete="off"
                                                        data-mask="999-999-9999" placeholder="โทรศัพท์มือถือ" name="tel_mobile"
                                                            value="{{ old('tel_mobile') }}"  required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>โทรศัพท์บ้าน</label>
                                                        <input type="text" class="form-control  us_telephone" autocomplete="off" data-mask="999-999-9999"
                                                            placeholder="โทรศัพท์บ้าน" name="tel_home" value="{{ old('tel_home') }}">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>Email </label>
                                                        <input type="email" class="form-control" placeholder="Email@email.com" name="email" value="{{ old('email') }}" >
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <h3 class="sub-title m-t-0" style="color: #000;font-size: 16px">
                                                            ที่อยู่ตามบัตรประชาชน</h3>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>บ้านเลขที่ <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control"
                                                            placeholder="บ้านเลขที่" id="card_house_no"
                                                            name="card_house_no" value="{{ old('card_house_no') }}"
                                                            required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>หมู่บ้าน/อาคาร <font class="text-danger">*</font>
                                                            </label>
                                                        <input type="text" class="form-control"
                                                            placeholder="หมู่บ้าน/อาคาร" id="card_house_name"
                                                            name="card_house_name"
                                                            value="{{ old('card_house_name') }}" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>หมู่ที่ <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="หมู่ที่"
                                                            id="card_moo" name="card_moo"
                                                            value="{{ old('card_moo') }}" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>ตรอก/ซอย <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="ตรอก/ซอย"
                                                            id="card_soi" name="card_soi"
                                                            value="{{ old('card_soi') }}" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>ถนน <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="ถนน"
                                                            id="card_road" name="card_road"
                                                            value="{{ old('card_road') }}" required="">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>จังหวัด <font class="text-danger">*</font></label>
                                                        <select class="js-example-basic-single col-sm-12"
                                                            id="card_province" name="card_province" required="">
                                                            <option value="">Select</option>
                                                            @foreach ($data['provinces'] as $value_provinces)
                                                                <option value="{{ $value_provinces->id }}"
                                                                    @if ($value_provinces->id == old('card_province')) selected @endif>
                                                                    {{ $value_provinces->name_th }}</option>
                                                            @endforeach
                                                        </select>

                                                        {{-- <input type="text" class="form-control" placeholder="จังหวัด" id="card_province" name="card_province" value="{{ old('card_province') }}"> --}}
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>เขต/อำเภอ <font class="text-danger">*</font></label>
                                                        <select class="js-example-basic-single col-sm-12"
                                                            name="card_amphures" id="card_amphures" required="">
                                                            <option value="">Select</option>
                                                        </select>
                                                        {{-- <input type="text" class="form-control" placeholder="เขต/อำเภอ" id="card_amphures" name="card_amphures" value="{{ old('card_amphures') }}"> --}}
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>แขวง/ตำบล <font class="text-danger">*</font></label>
                                                        {{-- <input type="text" class="form-control" placeholder="แขวง/ตำบล" id="card_district" name="card_district" value="{{ old('district') }}"> --}}
                                                        <select class="js-example-basic-single col-sm-12"
                                                            name="card_district" id="card_district" required="">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>รหัสไปรษณีย์</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="รหัสไปรษณีย์" id="card_zipcode"
                                                            name="card_zipcode" value="{{ old('card_zipcode') }}">
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <h3 class="sub-title" style="color: #000;font-size: 16px">
                                                            ที่อยู่ที่สะดวกสำหรับการติดต่อและจัดส่งผลิตภัณฑ์ถึงบ้าน
                                                        </h3>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-xl-6 m-b-30">
                                                    <div class="checkbox-fade fade-in-primary">
                                                        <label>
                                                            <input type="checkbox" id="copy_card_address" name="copy_card_address">
                                                            <span class="cr">
                                                                <i
                                                                    class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                            </span>
                                                            <span>ใช้ที่อยู่ตามบัตรประชาชน</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>บ้านเลขที่ <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control"
                                                            placeholder="บ้านเลขที่" id="house_no" name="house_no"
                                                            value="{{ old('house_no') }}" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>หมู่บ้าน/อาคาร <font class="text-danger">*</font>
                                                            </label>
                                                        <input type="text" class="form-control"
                                                            placeholder="หมู่บ้าน/อาคาร" id="house_name"
                                                            name="house_name" value="{{ old('house_name') }}"
                                                            required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>หมู่ที่ <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="หมู่ที่"
                                                            id="moo" name="moo" value="{{ old('moo') }}"
                                                            required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>ตรอก/ซอย <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control"
                                                            placeholder="ตรอก/ซอย" id="soi" name="soi"
                                                            value="{{ old('soi') }}" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>ถนน <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="ถนน"
                                                            id="road" name="road" value="{{ old('road') }}"
                                                            required="">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>จังหวัด <font class="text-danger">*</font></label>
                                                        <select class="js-example-basic-single col-sm-12" id="province"
                                                            name="province" required="">
                                                            <option value="">Select</option>
                                                            @foreach ($data['provinces'] as $value_provinces)
                                                                <option value="{{ $value_provinces->id }}">
                                                                    {{ $value_provinces->name_th }}</option>
                                                            @endforeach
                                                        </select>

                                                        {{-- <input type="text" class="form-control" placeholder="จังหวัด" id="province" name="province" value="{{ old('province') }}"> --}}
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>เขต/อำเภอ <font class="text-danger">*</font></label>
                                                        <select class="js-example-basic-single col-sm-12"
                                                            name="amphures" id="amphures" required="">
                                                            <option value="">Select</option>
                                                        </select>
                                                        {{-- <input type="text" class="form-control" placeholder="เขต/อำเภอ" id="amphures" name="amphures" value="{{ old('amphures') }}"> --}}
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>แขวง/ตำบล <font class="text-danger">*</font></label>
                                                        <select class="js-example-basic-single col-sm-12"
                                                            name="district" id="district" required="">
                                                            <option value="">Select</option>
                                                            {{-- <input type="text" class="form-control" placeholder="แขวง/ตำบล" id="district" name="district" value="{{ old('district') }}"> --}}
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>รหัสไปษณีย์</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="รหัสไปษณีย์" id="zipcode" name="zipcode"
                                                            value="{{ old('zipcode') }}">
                                                    </div>

                                                </div>


                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <h4 class="sub-title" style="color: #000;font-size: 16px">
                                                            ข้อมูลธนาคาร</h4>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>ชื่อบัญชี</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="ชื่อบัญชี" name="bank_account"
                                                            value="{{ old('bank_account') }}">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>เลขที่บัญชี</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="เลขที่บัญชี" name="bank_no"
                                                            value="{{ old('bank_no') }}" >
                                                    </div>

                                                    <div class="col-sm-3">
                                                      <label>@lang('message.bank')</label>
                                                      <select class="form-control" id="bank_name_th" name="bank_name">
                                                          <option value=""> Select </option>
                                                          <option value="ธนาคารกรุงเทพ" @if ('ธนาคารกรุงเทพ' == old('bank_name')) selected="" @endif> ธนาคารกรุงเทพ </option>
                                                          <option value="ธนาคารกสิกรไทย" @if ('ธนาคารกสิกรไทย' == old('bank_name')) selected="" @endif> ธนาคารกสิกรไทย </option>
                                                          <option value="ธนาคารกรุงไทย" @if ('ธนาคารกรุงไทย' == old('bank_name')) selected="" @endif> ธนาคารกรุงไทย </option>
                                                          <option value="ธนาคารไทยพาณิชย์" @if ('ธนาคารไทยพาณิชย์' == old('bank_name')) selected="" @endif> ธนาคารไทยพาณิชย์ </option>
                                                          <option value="ธนาคารกรุงศรีอยุธยา" @if ('ธนาคารกรุงศรีอยุธยา' == old('bank_name')) selected="" @endif> ธนาคารกรุงศรีอยุธยา </option>
                                                          <option value="ธนาคารทหารไทยธนชาต" @if ('ธนาคารทหารไทยธนชาต' == old('bank_name')) selected="" @endif> ธนาคารทหารไทยธนชาต </option>
                                                          <option value="ธนาคารออมสิน" @if ('ธนาคารออมสิน' == old('bank_name')) selected="" @endif> ธนาคารออมสิน </option>
                                                          <option value="ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร ธ.ก.ส." @if ('ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร ธ.ก.ส.' == old('bank_name')) selected="" @endif>
                                                              ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร ธ.ก.ส. </option>
                                                          <option value="ธนาคารยูโอบี" @if ('ธนาคารยูโอบี' == old('bank_name')) selected="" @endif> ธนาคารยูโอบี </option>
                                                      </select>

                                                      <select class="form-control" id="bank_name_cam" name="bank_name">
                                                        <option value=""> Select </option>
                                                        <option value="ABA Bank" @if ('ABA Bank' == old('bank_name')) selected="" @endif> ABA Bank </option>
                                                        <option value="Acleda Bank" @if ('Acleda Bank' == old('bank_name')) selected="" @endif> Acleda Bank </option>
                                                    </select>

                                                  </div>



                                                    <div class="col-sm-3">
                                                        <label>สาขา</label>
                                                        <input type="text" class="form-control" placeholder="สาขา"
                                                            name="bank_branch" value="{{ old('bank_branch') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>ประเภทบัญชี</label>
                                                        <br>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="radio"
                                                                    name="bank_type" id="gender-1" value="ออมทรัพย์"
                                                                    checked="checked"> ออมทรัพย์
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="radio"
                                                                    name="bank_type" id="gender-2" value="กระแสรายวัน">
                                                                กระแสรายวัน
                                                            </label>
                                                        </div>
                                                        <span class="messages"></span>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <h5 class="sub-title" style="color: #000;font-size: 16px">
                                                            การติดต่อ/ผู้รับผลประโยชน์</h5>
                                                    </div>
                                                </div>

                                                <div class="form-group row">

                                                    <div class="col-sm-3">
                                                        <label>ผู้สือทอดผลประโยชน์ ชื่อ-นามสกุล</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="ผู้สือทอดผลประโยชน์ ชื่อ-นามสกุล"
                                                            name="benefit_name" value="{{ old('benefit_name') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>ความสัมพันธ์</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="ความสัมพันธ์" name="benefit_relation"
                                                            value="{{ old('benefit_relation') }}">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>เลขที่บัตรประชาชน  </label>
                                                        <input type="text" class="form-control"  minlength="8" maxlength="14"  name="benefit_id"
                                                            value="{{ old('benefit_id') }}">
                                                    </div>

                                                    <div class="col-sm-3">


                                                </div>

                                                <div class="form-group col-md-12 row">
                                                    <div class="col-sm-12">
                                                        <h5 class="sub-title" style="color: #000;font-size: 16px">
                                                            เอกสารสำหรับการสมัคร</h5>
                                                    </div>


                                                    <div class="col-sm-6">

                                                        <div class="m-t-2 col-sm-12 text-center">
                                                            <img src="{{ asset('frontend/assets/images/piccardwatremark_.png') }}"
                                                                id="preview_1" class="img-fluid"
                                                                style="height: 180px">
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label>ภาพถ่ายบัตรประชาชน <b
                                                                        class="text-danger">*</b></label>
                                                                <input type="file" id="file_1" name="file_1"
                                                                    class="form-control" required>
                                                            </div>
                                                        </div>

                                                        <div class="m-t-2 col-sm-12 text-center">
                                                            <img src="{{ asset('frontend/assets/images/หน้าตรง.jpg') }}"
                                                                id="preview_2" style="height: 180px"
                                                                class="img-fluid">
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label>ภายถ่ายหน้าตรง <b
                                                                        class="text-danger">*</b></label>
                                                                <input type="file" id="file_2" name="file_2"
                                                                    class="form-control" required>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">

                                                        <div class="m-t-2 col-sm-12 text-center">
                                                            <img src="{{ asset('frontend/assets/images/user_card_new.jpg') }}"
                                                                id="preview_3" style="height: 180px"
                                                                class="img-fluid">
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label>ภาพถ่ายหน้าตรงถือบัตรประชาชน <b
                                                                        class="text-danger">*</b></label>
                                                                <input type="file" id="file_3" name="file_3"
                                                                    class="form-control" required>
                                                            </div>
                                                        </div>

                                                        <div class="m-t-2 col-sm-12 text-center">
                                                            <img src="{{ asset('frontend/assets/images/BookBank-7.png') }}"
                                                                id="preview_4" style="height: 180px"
                                                                class="img-fluid">
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label>ภาพถ่ายหน้าบัญชีธนาคาร</label>
                                                                <input type="file" id="file_4" name="file_4"
                                                                    class="form-control">
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                  <div class="form-group row text-center">
                                                      <label class="col-sm-2"></label>
                                                      <div class="col-sm-12">
                                                          <button type="submit" class="btn btn-primary m-b-0">@lang('message.register_submit')</button>
                                                      </div>
                                                  </div>
                                              </div>


                                            </form>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="{{ asset('frontend/bower_components/jquery/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/popper.js/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script src="{{ asset('frontend/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>
    <!-- modernizr js -->
    <script src="{{ asset('frontend/bower_components/modernizr/js/modernizr.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/modernizr/js/css-scrollbars.js') }}"></script>

    <!-- Syntax highlighter prism js -->
    <script src="{{ asset('frontend/assets/pages/prism/custom-prism.js') }}"></script>
    <!-- i18next.min.js -->
    <script src="{{ asset('frontend/bower_components/i18next/js/i18next.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js') }}"></script>
    <script
        src="{{ asset('frontend/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/jquery-i18next/js/jquery-i18next.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vertical/menu/menu-hori-fixed.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>

    <script src="{{ asset('frontend/bower_components/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script src="{{ asset('frontend/bower_components/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ asset('frontend/assets/pages/advance-elements/select2-custom.js') }}"></script>


    <!-- Masking js -->
    <script src="{{asset('frontend/assets/pages/form-masking/inputmask.js')}}"></script>
    <script src="{{asset('frontend/assets/pages/form-masking/jquery.inputmask.js')}}"></script>
    <script src="{{asset('frontend/assets/pages/form-masking/autoNumeric.js')}}"></script>
    <script src="{{asset('frontend/assets/pages/form-masking/form-mask.js')}}"></script>



    <script type="text/javascript">

var business_location_id = '{{ $data['data']->business_location_id }}';
         if (business_location_id == 1 || business_location_id == '') {
          $('#id_card').attr('maxlength', '13');
             $('#id_card').attr('minlength', '13');
         } else {
          $('#id_card').attr('maxlength', '9');
             $('#id_card').attr('minlength', '9');
         }

         $('#business_location').change(function() {
             value = $(this).val();
             if (value != "1") {
              $('#id_card').attr('maxlength', '9');
             $('#id_card').attr('minlength', '9');
                 $('#id_card').val("");
             } else {
              $('#id_card').attr('maxlength', '13');
             $('#id_card').attr('minlength', '13');
                 $('#id_card').val("");
             }
         })


         $(document).ready(function() {
             $('#id_card').on('change', function() {
                 nation_id = $('#business_location').val();
                 if (nation_id == 1) {
                     if ($.trim($(this).val()) != '' && $(this).val().length == 13) {
                         id = $(this).val().replace(/-/g, "");
                         var result = Script_checkID(id);
                         id_card = $('#id_card').val();
                         if (result === false) {

                             $('span.error').removeClass('text-success').text('เลขบัตร ' + id_card +
                                 ' ไม่ถูกต้อง');
                             $('#id_card').val('');
                         } else {


                             $.ajax({
                                     url: '{{ route('check_id_card') }}',
                                     type: 'GET',
                                     data: {
                                      id_card: id_card
                                     },
                                 })
                                 .done(function(data) {
                                     if (data['status'] == 'success') {
                                         $('span.error').addClass('text-success').text('เลขบัตรถูกต้อง');
                                     } else {
                                      $('span.error').removeClass('text-success').text('มีเลขบัตรประชาชนในระบบแล้วไม่สามารถสมัครซ้ำได้');
                                      $('#id_card').val('');
                                        //  Swal.fire({
                                        //      icon: 'error',
                                        //      title: 'เลขบัตรประชาชนซ้ำ',
                                        //  })
                                     }
                                  })
                                }
                     } else {
                         $('span.error').removeClass('text-success').text('เลขบัตรไม่ครบ 13 หลัก');
                         $('#id_card').val('');

                     }

                 }

             })
         });

         function Script_checkID(id) {
             if (!IsNumeric(id)) return false;
             if (id.substring(0, 1) == 0) return false;
             if (id.length != 13) return false;
             for (i = 0, sum = 0; i < 12; i++)
                 sum += parseFloat(id.charAt(i)) * (13 - i);
             if ((11 - sum % 11) % 10 != parseFloat(id.charAt(12))) return false;
             return true;
         }

         function IsNumeric(input) {
             var RE = /^-?(0|INF|(0[1-7][0-7]*)|(0x[0-9a-fA-F]+)|((0|[1-9][0-9]*|(?=[\.,]))([\.,][0-9]+)?([eE]-?\d+)?))$/;
             return (RE.test(input));
         }



        function check_user() {
            var user_name = $('#introduce').val();
            if (user_name == '' || user_name == null) {
                Swal.fire({
                    icon: 'error',
                    title: 'User is Null',
                })

            } else {
                $.ajax({
                        url: '{{ route('check_user') }}',
                        type: 'GET',
                        data: {
                            user_name: user_name
                        },
                    })
                    .done(function(data) {

                        if (data['data']['status'] == 'fail') {

                            Swal.fire({
                                icon: 'error',
                                title: data['data']['message'],
                            })

                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: data['data']['data']['business_name'] + ' (' + data['data']['data'][
                                    'user_name'
                                ] + ')',
                                text: data['data']['message'],
                            })

                        }

                        console.log(data);
                    })
                    .fail(function() {
                        console.log("error");
                    })

            }
        }

        $('#file_1').change(function() {
            var fileExtension = ['jpg', 'png', 'pdf', 'jpeg'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
                this.value = '';
                return false;
            }
        });
        $('#file_2').change(function() {
            var fileExtension = ['jpg', 'png', 'pdf', 'jpeg'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
                this.value = '';
                return false;
            }
        });

        $('#file_3').change(function() {
            var fileExtension = ['jpg', 'png', 'pdf', 'jpeg'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
                this.value = '';
                return false;
            }
        });

        $('#file_4').change(function() {
            var fileExtension = ['jpg', 'png', 'jpeg'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
                this.value = '';
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // get loaded data and render thumbnail.
                    document.getElementById("preview").src = e.target.result;
                };
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            }

        });
    </script>
    <script>
        const routeGetLocation = "{{ route('get-location') }}";
        const token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('frontend/address.js?v=1000') }}"></script>
</body>

</html>
