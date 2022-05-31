 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 <link rel="stylesheet" href="{{asset('frontend/bower_components/select2/css/select2.min.css')}}" />
 <!-- Multi Select css -->
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/multiselect/css/multi-select.css')}}">
 @endsection
 @section('conten')
 <div class="card">
 	{{-- <div class="card-header">
 		<h5>ที่อยู่การจัดส่ง</h5>
 	</div> --}}
 	<div class="card-block">
 		<form action="{{route('edit_address')}}" method="POST" autocomplete="off">
 			@csrf
 		{{-- 	<div class="form-group row">
 				<div class="col-sm-12">
 					<h4 class="sub-title" >ที่อยู่ที่สะดวกสำหรับการติดต่อและจัดส่งผลิตภัณฑ์ถึงบ้าน (โปรดแจ้งให้บริษัทฯทราบทันทีหากมีการเปลี่ยนแปลงที่อยู่) </h4>
 				</div>
 			</div> --}}
       <h4 class="sub-title">@lang('message.PERSONALINFORMATION')</h4>

       <div class="form-group row">
        <div class="col-sm-3">
          <label>@lang('message.name_first')</label>
          <input type="text" class="form-control form-control-bold"  value="{{@$customer->first_name}}" disabled>
        </div>

        <div class="col-sm-3">
          <label> @lang('message.name_last') </label>
          <input type="text" class="form-control form-control-bold"  value="{{@$customer->last_name}}" disabled>
        </div>

        <div class="col-sm-3">
          <label> @lang('message.name_business') </label>
          <input type="text" class="form-control form-control-bold"  name="business_name" value="{{@$customer->business_name}}" >
        </div>

        <div class="col-sm-3">
					<label>@lang('message.Mobile_Phone') </label>
          <input type="text" placeholder="@lang('message.Mobile_Phone')" id="tel_mobile" name="tel_mobile"  class="form-control us_telephone" autocomplete="off" data-mask="999-999-9999" value="{{ @$customer->tel_mobile }}">
					{{-- <input type="" class="form-control" autocomplete="off" placeholder="เบอร์โทรศัพท์" id="tel_mobile" name="tel_mobile" value="{{ @$customer->tel_mobile }}"> --}}
				</div>

				<div class="col-sm-3">
					<label>Email</label>
					<input type="email" class="form-control" autocomplete="off" placeholder="อีเมลล์" id="email" name="email" value="{{ @$customer->email }}">
				</div>
 			</div>

       <h4 class="sub-title" >@lang('message.address') </h4>


 			<div class="form-group row">
 				<div class="col-sm-2">
 					<label>@lang('message.no') <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="@lang('message.no')" name="house_no" value="{{@$customer->house_no}}" required="">
 				</div>

 				<div class="col-sm-3">
 					<label>@lang('message.building') <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="@lang('message.building')" name="house_name" value="{{ @$customer->house_name }}" required="">
 				</div>

 				<div class="col-sm-2">
 					<label>@lang('message.villageno') <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="@lang('message.villageno')" name="moo" value="{{ @$customer->moo }}" required="">
 				</div>

 				<div class="col-sm-2">
 					<label>@lang('message.lane')</label>
 					<input type="text" class="form-control" placeholder="@lang('message.lane')" name="soi" value="{{ @$customer->soi }}" >
 				</div>


 				<div class="col-sm-3">
 					<label>@lang('message.road') </label>
 					<input type="text" class="form-control" placeholder="@lang('message.road')" name="road" value="{{ @$customer->road }}">
 				</div>
 			</div>

 			<div class="form-group row">
 				<div class="col-sm-3">
 					<label>@lang('message.province') <font class="text-danger">*</font></label>
 					<select class="js-example-basic-single col-sm-12" id="province" name="province" required="">
             @if(@$customer->provinces_id)
                <option value="{{ @$customer->provinces_id }}" >{{ @$customer->provinces_name }}</option>
             @else
             <option value="" > Select </option>
             @endif

 						@foreach($provinces as $value_provinces)
 						<option value="{{ $value_provinces->id }}">{{ $value_provinces->name_th }}</option>
 						@endforeach
 					</select>
 				</div>
 				<div class="col-sm-3">
 					<label>@lang('message.district/area') <font class="text-danger">*</font></label>
 					<select class="js-example-basic-single col-sm-12" name="amphures" id="amphures" required="">
            @if(@$customer->amphures_id_fk)
            <option value="{{ @$customer->amphures_id_fk}}" >{{ @$customer->amphures_name }}</option>
            @else
            <option value="" > @lang('message.district/area') </option>
            @endif

 					</select>
 					{{-- <input type="text" class="form-control" placeholder="เขต/อำเภอ" id="district" name="district" value="{{ old('district') }}"> --}}
 				</div>

 				<div class="col-sm-3">
 					<label>@lang('message.sub-district/sub-area')  <font class="text-danger">*</font></label>
 					<select class="js-example-basic-single col-sm-12" name="district" id="district" required="">
            @if(@$customer->district_name)
            <option value="{{ @$customer->district_name}}" >{{ @$customer->district_name }}</option>
            @else
            <option value="" >@lang('message.sub-district/sub-area') </option>
            @endif
 					</select>
 				</div>

 			{{-- 	<div class="col-sm-3">
 					<label>จังหวัด <b class="text-danger">*</b></label>
 					<input type="text" class="form-control form-control-bold" placeholder="จังหวัด" name="province" value="{{ @$customer->province }}" required="">
 				</div> --}}


 				<div class="col-sm-3">
 					<label>@lang('message.zipcode') </label>
 					<input type="text" class="form-control" autocomplete="off" placeholder="@lang('message.zipcode')" id="zipcode" name="zipcode" value="{{ @$customer->zipcode }}">
 				</div>

      </div>




 			<div class="form-group row text-center mt-2">

 				<div class="col-sm-12">
          @if($canAccess)
 					<button type="button" class="btn btn-success m-b-0" data-toggle="modal" data-target="#default-Modal">@lang('message.Edit')</button>
          @endif
 				</div>

 			</div>

 			<div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
 				<div class="modal-dialog" role="document">
 					<div class="modal-content">
 						<div class="modal-header">
 							<h4 class="modal-title">@lang('message.confirm_your_password_address')</h4>
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
 										<input type="password" name="password" class="form-control" autocomplete="off"  required="">
 									</div>
 								</div>
 								<div class="col-sm-2">
 								</div>
 							</div>
 						</div>
 						<div class="modal-footer">
              @if($canAccess)
 							<button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
 							<button type="submit" class="btn btn-primary waves-effect waves-light ">@lang('message.confirm_your_password')</button>
               @endif
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

  <!-- Masking js -->
  <script src="{{asset('frontend/assets/pages/form-masking/inputmask.js')}}"></script>
  <script src="{{asset('frontend/assets/pages/form-masking/jquery.inputmask.js')}}"></script>
  <script src="{{asset('frontend/assets/pages/form-masking/autoNumeric.js')}}"></script>
  <script src="{{asset('frontend/assets/pages/form-masking/form-mask.js')}}"></script>

<script type="text/javascript">
  $('#province').change(function() {
    var province = $(this).val();

    $.ajax({
     async : false,
     type: "get",
     url: "{{ route('location') }}",
     data: {id:province,function:'provinces'},
     success: function(data){
      $('#amphures').html(data);
      $('#district').val('');
        // $('#zipcode').val('');
      }
    });

  });

  $('#amphures').change(function() {
    var amphures = $(this).val();

    $.ajax({
      async : false,
      type: "get",
      url: "{{ route('location') }}",
      data: {id:amphures,function:'amphures'},
      success: function(data){
        $('#district').html(data);
      }
    });
  });

  $('#district').change(function() {
    var district = $(this).val();
    $.ajax({
      type: "get",
      url: "{{ route('location') }}",
      data: {id:district,function:'district'},
      success: function(data){
        $('#zipcode').val(data);
      }
    });

  });
</script>


@endsection

