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

                                            <form action="{{ route('register_new_member') }}"
                                                id="register_new_member" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label><b>Uplind ID</b></label>
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
                                                        <select name="business_location" class="form-control">
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
                                                        <input type="text" class="form-control" placeholder="ชื่อ"
                                                            name="name_first" value="{{ old('name_first') }}"
                                                            required="">

                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>นามสกุล <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="นามสกุล"
                                                            name="name_last" value="{{ old('name_last') }}"
                                                            required="">
                                                    </div>


                                                    <div class="col-sm-4">
                                                        <label>ชื่อที่ใช้ในธุรกิจ <font class="text-danger">*</font>
                                                            </label>
                                                        <input type="text" class="form-control"
                                                            placeholder="ชื่อที่ใช้ในธุรกิจ" name="name_business"
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
                                                        <input class="form-control" type="text"
                                                            placeholder="เลขที่บัตรประชาชน" name="id_card"
                                                            value="{{ old('id_card') }}" required="">
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
                                                        <input type="text" class="form-control"
                                                            placeholder="โทรศัพท์มือถือ" name="tel_mobile"
                                                            value="{{ old('tel_mobile') }}" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>โทรศัพท์บ้าน</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="โทรศัพท์บ้าน" name="tel_home"
                                                            value="{{ old('tel_home') }}">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>Email <font class="text-danger">*</font></label>
                                                        <input type="text" class="form-control" placeholder="Email"
                                                            name="email" value="{{ old('email') }}" required="">
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
                                                            <input type="checkbox" id="copy_card_address"
                                                                onchange="copy_address()" name="copy_card_address">
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
                                                            value="{{ old('bank_no') }}">
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label>ธนาคาร</label>
                                                        <input type="text" class="form-control" placeholder="ธนาคาร"
                                                            name="bank_name" value="{{ old('bank_name') }}">
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
                                                        <label>บัตรประชาชนเลขที่ </label>
                                                        <input type="text" class="form-control"
                                                            placeholder="บัตรประชาชนเลขที่" name="benefit_id"
                                                            value="{{ old('benefit_id') }}">
                                                    </div>

                                                </div>

                                                <div class="form-group row">
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

                                                    <div class="row">

                                                      <div class="col-md-12 text-center">
                                                        <button type="submit"
                                                                class="btn btn-success m-b-0">Register</button>
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

    <script type="text/javascript">
        function copy_address() {
            var ckeck_address = document.getElementById("copy_card_address");

            if (ckeck_address.checked == true) {

                card_house_no = $('#card_house_no').val();
                card_house_name = $('#card_house_name').val();
                card_moo = $('#card_moo').val();
                card_soi = $('#card_soi').val();
                card_amphures = $('#card_amphures').val();
                card_district = $('#card_district').val();
                card_province = $('#card_province').val();
                card_road = $('#card_road').val();
                card_zipcode = $('#card_zipcode').val();

                house_no = $('#house_no').val(card_house_no);
                house_name = $('#house_name').val(card_house_name);
                moo = $('#moo').val(card_moo);
                soi = $('#soi').val(card_soi);
                road = $('#road').val(card_road);
                zipcode = $('#zipcode').val(card_zipcode);

                // amphures = $('#amphures').val(amphures);
                // district = $('#district').val(card_district);
                // province = $('#province').val(card_province);


                var card_province = $('#card_province').val();
                $('#province').val(card_province).change();

            } else {
                house_no = $('#house_no').val('');
                house_name = $('#house_name').val('');
                moo = $('#moo').val('');
                soi = $('#soi').val('');
                amphures = $('#amphures').val('');
                district = $('#district').val('');
                province = $('#province').val('');
                road = $('#road').val('');
                zipcode = $('#zipcode').val('');
            }
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

    <script type="text/javascript">
        $('#card_province').change(function() {
            var id_province = $(this).val();
            $.ajax({
                type: "get",
                url: "{{ route('location') }}",
                data: {
                    id: id_province,
                    function: 'provinces'
                },
                success: function(data) {
                    $('#card_amphures').html(data);
                    $('#card_district').val('');
                    $('#card_zipcode').val('');
                }
            });
        });

        $('#card_amphures').change(function() {
            var amphures = $(this).val();
            $.ajax({
                type: "get",
                url: "{{ route('location') }}",
                data: {
                    id: amphures,
                    function: 'amphures'
                },
                success: function(data) {
                    $('#card_district').html(data);

                }
            });
        });


        $('#card_district').change(function() {
            var district = $(this).val();
            $.ajax({
                type: "get",
                url: "{{ route('location') }}",
                data: {
                    id: district,
                    function: 'district'
                },
                success: function(data) {
                    $('#card_zipcode').val(data);
                }
            });

        });

        $('#province').change(function() {
            var id_province = $(this).val();
            var ckeck_address = document.getElementById("copy_card_address");
            $.ajax({
                async: false,
                type: "get",
                url: "{{ route('location') }}",
                data: {
                    id: id_province,
                    function: 'provinces'
                },
                success: function(data) {
                    $('#amphures').html(data);
                    $('#district').val('');
                    // $('#zipcode').val('');
                }
            });
            if (ckeck_address.checked == true) {
                var card_amphures = $('#card_amphures').val();
                $('#amphures').val(card_amphures).change();
            }
        });

        $('#amphures').change(function() {
            var amphures = $(this).val();
            var ckeck_address = document.getElementById("copy_card_address");
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

            if (ckeck_address.checked == true) {
                var card_district = $('#card_district').val();
                $('#district').val(card_district).change();
            }
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
                    $('#zipcode').val(data);
                }
            });

        });

        getOldAddress()

        function getOldAddress() {
            const province = "{{ old('card_province') }}"
            const amphures = "{{ old('card_amphures') }}"
            const district = "{{ old('card_district') }}"
            const copyAddress = "{{ old('copy_card_address') }}"

            if (province) {
                $.ajax({
                    type: "get",
                    url: "{{ route('location') }}",
                    data: {
                        id: province,
                        function: 'provinces',
                        amphuresId: amphures
                    },
                    success: function(data) {
                        $('#card_amphures').html(data)
                    }
                })
            }

            if (amphures) {
                $.ajax({
                    type: "get",
                    url: "{{ route('location') }}",
                    data: {
                        id: amphures,
                        function: 'amphures',
                        districtId: district
                    },
                    success: function(data) {
                        $('#card_district').html(data)
                    }
                })
            }

            if (district) {
                $.ajax({
                    type: "get",
                    url: "{{ route('location') }}",
                    data: {
                        id: district,
                        function: 'district'
                    },
                    success: function(data) {
                        $('#card_zipcode').val(data);
                    }
                });
            }

            if (copyAddress) {
                $('#copy_card_address').attr('checked', true)
            }
        }
    </script>

</body>

</html>
