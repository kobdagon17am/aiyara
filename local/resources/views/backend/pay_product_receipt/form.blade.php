@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> การจัดส่งสินค้า </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = Session::get('session_menu_id');
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }
   ?>
   
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
             
              <form action="{{ route('backend.pay_product_receipt.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">

                {{ csrf_field() }}


                      <div class="myBorder">

                        <div class="form-group row">
                            <label for="recipient_name" class="col-md-2 col-form-label">ชื่อลูกค้า :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->recipient_name }}" name="recipient_name" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="recipient_code" class="col-md-2 col-form-label">เลขที่ใบเสร็จ :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->recipient_code }}" name="recipient_code" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="mobile" class="col-md-2 col-form-label">เบอร์โทรติดต่อลูกค้า :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->mobile }}" name="mobile"  required >
                            </div>
                          </div>

                      <!--     <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> จังหวัด : * </label>
                            <div class="col-md-10">
                              <select name="province_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Province)
                                    @foreach(@$Province AS $r)
                                      <option value="{{$r->code}}" {{ (@$r->code==@$sRow->province_id_fk)?'selected':'' }} >
                                        {{$r->name_th}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div> -->


                          <div class="form-group row">
                            <label for="sent_date" class="col-md-2 col-form-label">วันที่จัดส่ง : * </label>
                            <div class="col-md-3">

                              <input class="form-control sent_date"  autocomplete="off" placeholder="วันที่จัดส่ง" value="{{ @$sRow->sent_date }}" required=""  />
                             <input type="hidden" id="sent_date" name="sent_date"  value="{{ @$sRow->sent_date }}"  />


                            </div>
                          </div>



                          <div class="form-group row">
                            <label for="status_sent" class="col-md-2 col-form-label">ยืนยันการจัดส่ง :</label>
                            <div class="col-md-8 mt-2">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_sent" value="1" {{ ( @$sRow->status_sent=='1')?'checked':'' }}>
                              <label class="custom-control-label" for="customSwitch"> Confirm ส่งสินค้าแล้ว </label>
                            </div>
                          </div>
                          </div>

           

                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pay_product_receipt") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">
                  
                  @IF(@$sRow->status_sent!=1)
                    <button type="submit" class="btn btn-primary btn-sm waves-effect">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                    </button>
                  @ENDIF 

                  </div>
                </div>

              </form>
              </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

 <script>
      $('.sent_date').datetimepicker({
          value: '',
          rtl: false,
          format: 'd/m/Y H:i',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });

      $('.sent_date').change(function(event) {
        var d = $(this).val();
        var t = d.substring(d.length - 5);
        var d = d.substring(0, 10);
        var d = d.split("/").reverse().join("-");

        $('#sent_date').val(d+' '+t);
      });
</script>

@endsection

