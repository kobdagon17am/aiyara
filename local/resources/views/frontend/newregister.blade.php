 <?php

 use App\Helpers\Frontend;
 $check_kyc = Frontend::check_kyc(Auth::guard('c_user')->user()->user_name);
 ?>
 @extends('frontend.layouts.customer.customer_app')

 @section('css')
<style>
  .icons-alert:before {
      top: 11px;
  }

</style>
  <link rel="stylesheet" href="{{ asset('frontend/bower_components/select2/css/select2.min.css') }}" />
  <!-- Multi Select css -->
  <link rel="stylesheet" type="text/css"
      href="{{ asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
  <link rel="stylesheet" type="text/css"
      href="{{ asset('frontend/bower_components/multiselect/css/multi-select.css') }}">
 @endsection

 @section('conten')
     <div class="card">
         <div class="card-header">
             <h4>@lang('message.register')</h4>
         </div>

         <div class="card-block">
             @if ($check_kyc['status'] == 'fail')
                 <div class="col-md-8">
                     {!! $check_kyc['html'] !!}
                 </div>
             @endif
             <form action="{{ route('register_new_member') }}" id="register_new_member" method="POST"
                 enctype="multipart/form-data">
                 @csrf

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label><b>  @lang('message.under') </b></label>
                         <span class=" form-control pcoded-badge label label-success" style="font-size: 15px;padding: 9px 9px;">

                             <font style="color: #000;"> {{ @$data['data']->business_name }} (
                                 {{ $data['data']->user_name }} ) </font>
                         </span>
                         {{-- <input type="text" class="form-control"   placeholder="Upline ID" value="{{$data['data']->user_name}}" disabled=""> --}}
                         <input type="hidden" name="upline_id" value="{{ $data['data']->user_name }}">
                     </div>

                     <div class="col-sm-3">
                         <label><b>@lang('message.line_type')</b></label>
                         <span class=" form-control pcoded-badge label label-success"
                             style="font-size: 15px;padding: 9px 9px;">
                             <font style="color: #000;">{{ $data['line_type_back'] }} </font>
                         </span>

                         {{-- <input type="text" class="form-control"   placeholder="สายงาน" value="{{$data['line_type_back']}}" disabled=""> --}}
                         <input type="hidden" name="line_type_back" value="{{ $data['line_type_back'] }}">

                     </div>

                     <div class="col-sm-3">
                         <label><b>@lang('message.Referral_UserName')</b></label>

                         <div class="input-group input-group-button">
                             <input type="text" class="form-control" name="introduce" id="introduce"
                                 placeholder="@lang('message.Referral_UserName')" value="{{ Auth::guard('c_user')->user()->user_name }}">
                             <span class="input-group-addon btn btn-primary" id="basic-addon10" onclick="check_user()">
                                 <span class=""><i class=" icofont icofont-check-circled"></i> Check</span>
                             </span>
                         </div>
                     </div>
                     <div class="col-sm-3">
                         <label><b> Business Location </b></label>
                         <select name="business_location" id="business_location" class="form-control">
                             @foreach ($data['business_location'] as $business_location_value)
                                 <option value="{{ $business_location_value->id }}" @if ($business_location_value->id == $data['data']->business_location_id) selected="" @endif>
                                     {{ $business_location_value->txt_desc }}</option>
                             @endforeach
                         </select>
                     </div>

                 </div>
                 <div class="form-group row">
                     <div class="col-sm-2">
                         <label> @lang('message.name_prefix')  </label>
                         <select class="form-control" name="name_prefix">
                             <option value="@lang('message.mr')"> @lang('message.mr') </option>
                             <option value="@lang('message.mrs')"> @lang('message.mrs') </option>
                             <option value="@lang('message.ms')"> @lang('message.ms') </option>
                         </select>
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.name_first') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.name_first')" name="name_first"
                             value="{{ old('name_first') }}" required="">

                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.name_last') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.name_last')" name="name_last"
                             value="{{ old('name_last') }}" required="">
                     </div>


                     <div class="col-sm-4">
                         <label>@lang('message.name_business') <font class="text-danger">@lang('message.name_business_a')</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.name_business') " name="name_business"
                             value="{{ old('name_business') }}" required="">
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-2">
                         <label>@lang('message.marital_status')</label>
                         <select class="form-control" name="family_status">
                             <option value="1">@lang('message.Single')</option>
                             <option value="2">@lang('message.Married')</option>
                             <option value="3">@lang('message.Divorced')</option>
                         </select>
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.date_of_birth')</label>
                         <input class="form-control" type="date" name="birth_day" value="{{ old('birth_day') }}">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.ID_card_number') <font class="text-danger">*</font></label>
                         <input class="form-control" type="text" name="id_card" minlength="8" maxlength="14" value="{{ old('id_card') }}" required="">
                     </div>



                     <div class="col-sm-3">
                         <label>@lang('message.Nationality') <font class="text-danger">*</font></label>
                         <select class="js-example-basic-single col-sm-12" name="nation_id" required="">
                             <option value="">Select</option>
                             @foreach ($data['country'] as $country_value)
                                 <option value="{{ $country_value->nation_id }}" @if ($country_value->nation_id == $data['data']->nation_id || $country_value->nation_id == old('nation_id')) selected="" @endif>
                                     {{ $country_value->nation_name_en }}</option>
                             @endforeach
                         </select>
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.Mobile_Phone')  <b class="text-danger">*</b></label>
                         <input type="text" class="form-control us_telephone" autocomplete="off" data-mask="999-999-9999" name="tel_mobile"
                         value="{{ old('tel_mobile') }}" >
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.Home_Phone') </label>
                         <input type="text" class="form-control us_telephone" autocomplete="off"
                          data-mask="999-999-9999" name="tel_home" value="{{ old('tel_home') }}" >
                     </div>

                     <div class="col-sm-3">
                         <label>Email </label>
                         <input type="email" class="form-control" autocomplete="off" placeholder="Email@email.com" name="email"
                             value="{{ old('email') }}" >
                     </div>
                 </div>



                 <div class="form-group row">
                     <div class="col-sm-12">
                         <h3 class="sub-title m-t-0" style="color: #000;font-size: 16px">@lang('message.address_on_ID_card')</h3>
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.no') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.no')" id="card_house_no"
                             name="card_house_no" value="{{ old('card_house_no') }}" required="">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.building') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.building')" id="card_house_name"
                             name="card_house_name" value="{{ old('card_house_name') }}" required="">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.villageno') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.villageno')" id="card_moo" name="card_moo"
                             value="{{ old('card_moo') }}" required="">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.lane')</label>
                         <input type="text" class="form-control" placeholder="ตรอก/ซอย" id="card_soi" name="card_soi"
                             value="{{ old('card_soi') }}" >
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.road')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.road')" id="card_road" name="card_road"
                             value="{{ old('card_road') }}"  >
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.province') <font class="text-danger">*</font></label>
                         <select class="js-example-basic-single col-sm-12" id="card_province" name="card_province"
                             required="">
                             <option value="">Select</option>
                             @foreach ($data['provinces'] as $value_provinces)
                                 <option value="{{ $value_provinces->id }}" @if ($value_provinces->id == old('card_province')) selected @endif>
                                     {{ $value_provinces->name_th }}</option>
                             @endforeach
                         </select>

                         {{-- <input type="text" class="form-control" placeholder="จังหวัด" id="card_province" name="card_province" value="{{ old('card_province') }}"> --}}
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.district/area')<font class="text-danger">*</font></label>
                         <select class="js-example-basic-single col-sm-12" name="card_amphures" id="card_amphures"
                             required="">
                             <option value="">Select</option>
                         </select>
                         {{-- <input type="text" class="form-control" placeholder="เขต/อำเภอ" id="card_amphures" name="card_amphures" value="{{ old('card_amphures') }}"> --}}
                     </div>
                     <div class="col-sm-3">
                         <label>@lang('message.sub-district/sub-area') <font class="text-danger">*</font></label>
                         {{-- <input type="text" class="form-control" placeholder="แขวง/ตำบล" id="card_district" name="card_district" value="{{ old('district') }}"> --}}
                         <select class="js-example-basic-single col-sm-12" name="card_district" id="card_district"
                             required="">
                             <option value="">Select</option>
                         </select>
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.zipcode')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.zipcode')" id="card_zipcode"
                             name="card_zipcode" value="{{ old('card_zipcode') }}">
                     </div>
                 </div>


                 <div class="form-group row">
                     <div class="col-sm-12">
                         <h3 class="sub-title" style="color: #000;font-size: 16px">
                          @lang('message.Convenient_address') </h3>
                     </div>
                 </div>

                 <div class="col-sm-12 col-xl-6 m-b-30">
                     <div class="checkbox-fade fade-in-primary">
                         <label>
                             <input type="checkbox" id="copy_card_address"
                                 name="copy_card_address">
                             <span class="cr">
                                 <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                             </span>
                             <span> @lang('message.use_address_on_ID_card')</span>
                         </label>
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.no') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.no')" id="house_no" name="house_no"
                             value="{{ old('house_no') }}" required="">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.building') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.building')" id="house_name"
                             name="house_name" value="{{ old('house_name') }}" required="">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.villageno') <font class="text-danger">*</font></label>
                         <input type="text" class="form-control" placeholder="@lang('message.villageno')" id="moo" name="moo"
                             value="{{ old('moo') }}" required="">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.lane') </label>
                         <input type="text" class="form-control" placeholder="@lang('message.lane')" id="soi" name="soi"
                             value="{{ old('soi') }}">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.road')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.road')" id="road" name="road"
                             value="{{ old('road') }}">
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.province') <font class="text-danger">*</font></label>
                         <select class="js-example-basic-single col-sm-12" id="province" name="province" required="">
                             <option value="">Select</option>
                             @foreach ($data['provinces'] as $value_provinces)
                                 <option value="{{ $value_provinces->id }}">{{ $value_provinces->name_th }}</option>
                             @endforeach
                         </select>

                         {{-- <input type="text" class="form-control" placeholder="จังหวัด" id="province" name="province" value="{{ old('province') }}"> --}}
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.district/area') <font class="text-danger">*</font></label>
                         <select class="js-example-basic-single col-sm-12" name="amphures" id="amphures" required="">
                             <option value="">Select</option>
                         </select>
                         {{-- <input type="text" class="form-control" placeholder="เขต/อำเภอ" id="amphures" name="amphures" value="{{ old('amphures') }}"> --}}
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.sub-district/sub-area') <font class="text-danger">*</font></label>
                         <select class="js-example-basic-single col-sm-12" name="district" id="district" required="">
                             <option value="">Select</option>
                             {{-- <input type="text" class="form-control" placeholder="แขวง/ตำบล" id="district" name="district" value="{{ old('district') }}"> --}}
                         </select>
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.zipcode')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.zipcode')" id="zipcode" name="zipcode"
                             value="{{ old('zipcode') }}">
                     </div>

                 </div>


                 <div class="form-group row">
                     <div class="col-sm-12">
                         <h4 class="sub-title" style="color: #000;font-size: 16px">@lang('message.bank_information')</h4>
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.accountname')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.accountname')" name="bank_account"
                             value="{{ old('bank_account') }}">
                     </div>
                     <div class="col-sm-3">
                         <label> @lang('message.accountno')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.accountno')" name="bank_no"
                             value="{{ old('bank_no') }}" maxlength="10" minlength="9">
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
                         <label>@lang('message.branch')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.branch')" name="bank_branch"
                             value="{{ old('bank_branch') }}">
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.accounttype')</label>
                         <br>
                         <div class="form-check form-check-inline">
                             <label class="form-check-label">
                                 <input class="form-check-input" type="radio" name="bank_type" id="gender-1"
                                     value="@lang('message.Savings')" checked="checked"> @lang('message.Savings')
                             </label>
                         </div>
                         <div class="form-check form-check-inline">
                             <label class="form-check-label">
                                 <input class="form-check-input" type="radio" name="bank_type" id="gender-2"
                                     value="@lang('message.Current_account')"> @lang('message.Current_account')
                             </label>
                         </div>
                         <span class="messages"></span>
                     </div>
                 </div>


                 <div class="form-group row">
                     <div class="col-sm-12">
                         <h5 class="sub-title" style="color: #000;font-size: 16px">@lang('message.successor')</h5>
                     </div>
                 </div>

                 <div class="form-group row">

                     <div class="col-sm-3">
                         <label>@lang('message.Beneficiaries_name-surname')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.Beneficiaries_name-surname')"
                             name="benefit_name" value="{{ old('benefit_name') }}">
                     </div>
                 </div>

                 <div class="form-group row">
                     <div class="col-sm-3">
                         <label>@lang('message.relationship')</label>
                         <input type="text" class="form-control" placeholder="@lang('message.relationship')" name="benefit_relation"
                             value="{{ old('benefit_relation') }}">
                     </div>

                     <div class="col-sm-3">
                         <label>@lang('message.ID_card_number')</label>
                         <input type="text" class="form-control" minlength="8" maxlength="14"  name="benefit_id"
                             value="{{ old('benefit_id') }}">
                     </div>

                 </div>

                 <div class="form-group row">
                     <div class="col-sm-12">
                         <h5 class="sub-title" style="color: #000;font-size: 16px"> @lang('message.Documents_for_applying')</h5>
                     </div>


                     <div class="col-sm-6">

                         <div class="m-t-2 col-sm-12 text-center">
                             <img src="{{ asset('frontend/assets/images/piccardwatremark_.png') }}" id="preview_1"
                                 class="img-fluid" style="height: 180px">
                         </div>
                         <div class="form-group row">
                             <div class="col-sm-12">
                                 <label>@lang('message.ID_card_photo') <b class="text-danger">*</b></label>
                                 <input type="file" id="file_1" name="file_1" class="form-control" required>
                             </div>
                         </div>

                         <div class="m-t-2 col-sm-12 text-center">
                             <img src="{{ asset('frontend/assets/images/หน้าตรง.jpg') }}" id="preview_2"
                                 style="height: 180px" class="img-fluid">
                         </div>
                         <div class="form-group row">
                             <div class="col-sm-12">
                                 <label>@lang('message.straight_face_photo') <b class="text-danger">*</b></label>
                                 <input type="file" id="file_2" name="file_2" class="form-control" required>
                             </div>

                         </div>
                     </div>

                     <div class="col-sm-6">

                         <div class="m-t-2 col-sm-12 text-center">
                             <img src="{{ asset('frontend/assets/images/user_card_new.jpg') }}" id="preview_3"
                                 style="height: 180px" class="img-fluid">
                         </div>
                         <div class="form-group row">
                             <div class="col-sm-12">
                                 <label>@lang('message.holding_an_ID_card')   <b class="text-danger">*</b></label>
                                 <input type="file" id="file_3" name="file_3" class="form-control" required>
                             </div>
                         </div>

                         <div class="m-t-2 col-sm-12 text-center">
                             <img src="{{ asset('frontend/assets/images/BookBank-7.png') }}" id="preview_4"
                                 style="height: 180px" class="img-fluid">
                         </div>
                         <div class="form-group row">
                             <div class="col-sm-12">
                                 <label>@lang('message.bank_account_photo')</label>
                                 <input type="file" id="file_4" name="file_4" class="form-control">
                             </div>

                         </div>

                     </div>

                     @if ($check_kyc['status'] == 'success')
                     <div class="col-sm-12">
                         <div class="form-group row text-center">
                             <label class="col-sm-2"></label>
                             <div class="col-sm-12">
                                 <button type="submit" class="btn btn-primary m-b-0">@lang('message.register_submit')</button>
                             </div>
                         </div>
                     </div>
                     @endif

             </form>
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

        <!-- Masking js -->
    <script src="{{asset('frontend/assets/pages/form-masking/inputmask.js')}}"></script>
    <script src="{{asset('frontend/assets/pages/form-masking/jquery.inputmask.js')}}"></script>
    <script src="{{asset('frontend/assets/pages/form-masking/autoNumeric.js')}}"></script>
    <script src="{{asset('frontend/assets/pages/form-masking/form-mask.js')}}"></script>

     <script type="text/javascript">

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
            var fileExtension = ['jpg', 'png', 'pdf', 'jpeg'];
             if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                 alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
                 this.value = '';
                 return false;
             }
         });
     </script>
    <script>
        const routeGetLocation = "{{ route('get-location') }}";
        const token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('frontend/address.js') }}"></script>


 @endsection
