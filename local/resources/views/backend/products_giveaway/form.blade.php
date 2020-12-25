@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ตั้งค่าการแถมสินค้า </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.products_giveaway.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.products_giveaway.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">สถานที่ตั้งธุรกิจ : * </label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="" name="txt_desc" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">ชื่อการแถม : * </label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="" name="txt_desc" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="start_date" class="col-md-3 col-form-label">วันเริ่มต้น : * </label>
                    <div class="col-md-3">
                        <input class="form-control start_date"  autocomplete="off" placeholder="" required=""  />
                        <input type="hidden" id="start_date" name="start_date"   />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="end_date" class="col-md-3 col-form-label">วันสิ้นสุด : * </label>
                    <div class="col-md-3">
                        <input class="form-control end_date"  autocomplete="off" placeholder="" required=""  />
                        <input type="hidden" id="end_date" name="end_date"   />
                    </div>
                </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ประเภทการซื้อ : * </label>
                    <div class="col-md-9">
                      <select name="" class="form-control select2-templating " >
                        <option value="">Select</option>
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">แถมสมาชิกแบบ : * </label>
                    <div class="col-md-9">
                      <select name="" class="form-control select2-templating " >
                        <option value="">Select</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">จำนวนการแถมในบิลนั้น : * </label>
                    <div class="col-md-9">
                      <select name="" class="form-control select2-templating " >
                        <option value="">Select</option>
                      </select>
                    </div>
                  </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">PV การซื้อขั้นต่ำ : * </label>
                    <div class="col-md-3">
                        <input class="form-control "  autocomplete="off" placeholder="" required=""  />
                    </div>
                </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ตัวเลือกการแถม : * </label>
                    <div class="col-md-9">
                      <select name="" class="form-control select2-templating " >
                        <option value="">Select</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">Voucher ที่แถม :  </label>
                    <div class="col-md-3">
                        <input class="form-control "  autocomplete="off" placeholder=""  />
                    </div>
                </div>


<!--                 <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label"> :</label>
                  <div class="col-md-9">
                    <select name="lang_id" class="form-control select2-templating " >
                      <option value="0">Select</option>
                      @if(@$sLang)
                        @foreach(@$sLang AS $r)
                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->lang_id)?'selected':'' }} >{{$r->txt_desc}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div> -->

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-9 mt-2">
                      <div class="custom-control custom-switch">
                      	@if( empty($sRow) )
                      		<input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                      	@else
                      		<input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
						            @endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน</label>
                      </div>
                    </div>
                </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products_giveaway") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')

        <script type="text/javascript">


                function showPreview_01(ele)
                    {
                        $('#image').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


        </script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

 <script>
      $('.start_date').datetimepicker({
          value: '',
          rtl: false,
          // format: 'd/m/Y H:i',
          format: 'd/m/Y',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: false,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });

      $('.start_date').change(function(event) {
        var d = $(this).val();
        var t = d.substring(d.length - 5);
        var d = d.substring(0, 10);
        var d = d.split("/").reverse().join("-");
        $('#start_date').val(d);
      });


      $('.end_date').datetimepicker({
          value: '',
          rtl: false,
          // format: 'd/m/Y H:i',
          format: 'd/m/Y',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: false,
          datepicker: true,
          weeks: false,
          minDate: 0 ,
          // minDate: function () {
          //   return $('.start_date').val();
          // }
      });

      $('.end_date').change(function(event) {
        var ds = $('#start_date').val();
        var de = $('#end_date').val();
        var d = $(this).val();
        
        var t = d.substring(d.length - 5);
        var d = d.substring(0, 10);
        var d = d.split("/").reverse().join("-");
        $('#end_date').val(d);

        // alert(ds+" : "+de);
        // if(de<ds){
        //   alert("! วันสิ้นสุด ควรมีค่ามากกว่าหรือเท่ากับ วันเริ่มต้น");
        //   $(this).val('');
        //   $('#end_date').val('');
        //   return false;
        // }

      });


</script>




@endsection
