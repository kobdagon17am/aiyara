<?php
use App\Helpers\Frontend;
$customer_data = Frontend::get_customer(Auth::guard('c_user')->user()->user_name);
$check_kyc = Frontend::check_kyc(Auth::guard('c_user')->user()->user_name);

?>

@extends('frontend.layouts.customer.customer_app')
@section('conten')

@section('css')
<style>
  .icons-alert:before {top: 11px;};
  .usre-image:before {
    border: 2px solid #18c160;
  }
</style>

@endsection
<div class="row">
  <div class="col-sm-12">
    <!-- Panel card start -->
    <div class="card">
      <div class="card-header">
        @if($check_kyc['status'] == 'fail')

        {!! $check_kyc['html'] !!}
      @endif

        <h4><i class="fa fa-user"></i> @lang('message.PERSONALINFORMATION ') </h4>

      </div>
      <div class="card-block panels-wells">
        <div class="row">
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
            <div class="panel panel-success">
              <div class="panel-heading bg-success">
                <b>ทำคุณสมบัติ</b>
              </div>
              <div class="panel-body">
                <h5 class="m-b-10 text-" style="color: #000">คะแนนสะสม</h5>
                <h4 style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv) }} PV</span></h4>
              </div>
              <div class="panel-footer text-success ">
                <h6> {{ $customer_data->qualification_name }} </h6>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
            <div class="panel panel-info">
              <div class="panel-heading bg-info">
                <b>รักษาคุณสมบัติรายเดือน</b>
              </div>
              <div class="panel-body">
                <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>
                <h4 style="color: #000">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_mt) }} PV</span></h4>
              </div>
              <div class="panel-footer text-info">
               @if(empty(Auth::guard('c_user')->user()->pv_mt_active) || (strtotime(Auth::guard('c_user')->user()->pv_mt_active) < strtotime(date('Ymd')) ))
               <p class="m-b-0"><span class="label label-danger"  data-toggle="tooltip" data-placement="right" data-original-title="{{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_mt_active)) }}"  style="font-size: 14px">Not Active </span>  </p>
               @else

               <p class="m-b-0"><span class="label label-info" style="font-size: 14px">Active ถึง {{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_mt_active)) }} </span>  </p>
               @endif
             </div>
           </div>
         </div>


         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
          <div class="panel panel-primary">
            <div class="panel-heading bg-primary">
              <b>รักษาคุณสมบัติท่องเที่ยว</b>
            </div>
            <div class="panel-body">
             <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>
             <h4 style="color: #000">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_tv) }} PV</span></h4>

           </div>
           <div class="panel-footer text-primary">
            <?php
            $pv_tv_active = Auth::guard('c_user')->user()->pv_tv_active;
            if(!empty($pv_tv_active)){
              $pv_tv_active = date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_tv_active));
            }else{
              $pv_tv_active = '';

            }

            ?>

            @if(empty(Auth::guard('c_user')->user()->pv_tv_active) || (strtotime(Auth::guard('c_user')->user()->pv_tv_active) < strtotime(date('Ymd')) ))
            <p class="m-b-0"><span class="label label-danger" data-toggle="tooltip" data-placement="right" data-original-title="{{ $pv_tv_active }}" style="font-size: 14px">Not Active </span>  </p>
            @else

            <p class="m-b-0"><span class="label label-primary" style="font-size: 14px">Active ถึง {{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_tv_active)) }} </span>  </p>
            @endif
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
        <div class="panel panel-warning">
          <div class="panel-heading bg-warning">
            <b>Ai-Stockist</b>
          </div>
          <div class="panel-body">
            <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>
            <h4 style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_aistockist) }} PV</span></h4>
          </div>
          <div class="panel-footer text-warning">
            {{-- Panel Footer --}}
          </div>
        </div>
      </div>



  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
    <div class="panel panel-danger" >
      <div class="panel-heading bg-danger">
       <b> Ai Voucher</b>
     </div>
     <div class="panel-body">
      <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>
      <?php $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name); ?>
      <div class="row">
        <div class="col-md-6 col-5"><h4><span>{{ number_format($gv->sum_gv) }} </span></h4></div>
        <?php $count_expli = Frontend::check_count_expri_giv(Auth::guard('c_user')->user()->user_name); ?>
        <div class="col-md-6 col-7 text-right">
          @if($count_expli>0)
          <a href="{{ route('giftvoucher_history') }}"> <span class="label label-warning" style="font-size: 14px;"><font style="color: rgb(29, 26, 26)"> มี {{ $count_expli }} รายการจะหมดอายุ </font></span> </a>
          @endif
        </div>
      </div>

    </div>
    <div class="panel-footer text-danger">
      {{-- Panel Footer --}}
    </div>
  </div>
</div>

<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
  <div class="panel panel-default">
    <div class="panel-heading bg-default txt-white" style="background-color:#6c6c6c">
     <b>Ai-Cash</b>
   </div>
   <div class="panel-body">
    <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>

    <h4 style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->ai_cash) }} </span></h4>
  </div>
  <div class="panel-footer">
    {{-- Panel Footer --}}
  </div>
</div>
</div>


<!-- end of row -->
</div>
</div>
</div>
<!-- Panel card end -->
</div>
</div>

<?php //dd($data); ?>
<div class="row">
  <div class="col-lg-6 col-md-6">
    <div class="card user-card">
        <div class="card-header pb-0">
            <h5>Profile</h5>
        </div>
        <div class="card-block">
            <div class="usre-image">
              @if(Auth::guard('c_user')->user()->profile_img)
              <img class="img-radius" width="100" src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" alt="User-Profile-Image">
              @else
              <img class="img-radius" width="100" src="{{asset('local/public/images/ex.png')}}" alt="User-Profile-Image">
              @endif

            </div>

            @if(@$data->business_name)
              <h6 class="f-w-600 m-t-25 m-b-10">{{$data->business_name}} ({{$data->user_name}})</h4>
            @else
              <h6 class="f-w-600 m-t-25 m-b-10">{{$data->prefix_name.' '.$data->first_name.' '.$data->last_name }} ({{$data->user_name}}) </h6>
            @endif

            <hr>

            <div class="row f-14 text-left">
                <div class="col-sm-6">
                  ทำคุณสมบัติ : {{ $data->pv }} PV <br>
                  รักษาคุณสมบัติรายเดือน : {{ $data->pv_mt }} PV <br>
                  รักษาคุณสมบัติท่องเที่ยว : {{ $data->pv_tv }} PV
                </div>
                <div class="col-sm-6">

                  Ai-Stockist : {{ $data->aistockist_status ? 'เป็น' : 'ไม่เป็น' }}<br>
                  ตำแหน่ง : {{ $data->q_name }}<br>
                  Package : {{ $data->dt_package }}
                </div>
            </div>
            <!-- <p class="text-muted">PV {{number_format($data->pv)}} | {{$data->q_name}} </p>
            <hr>
            <p class="text-muted m-t-15">Package : {{$data->dt_package}} </p> -->
            <hr>

            <div class="text-left">
              <p class="f-w-600 mb-2">ข้อมูลพื้นฐาน</p>
              <div class="row f-14">
                <div class="col-sm-6">
                  <span>รหัสสมาชิก : {{ $data->user_name }}</span>
                </div>
                <div class="col-sm-6">
                  <span>ชื่อ-นามสกุล : {{ Str::of($data->prefix_name)->append($data->first_name .' '. $data->last_name) }}</span>
                </div>
                <div class="col-sm-6">
                  <span>วันเดือนปีเกิด : {{ date('d/m/Y', strtotime($data->birth_day)) }}</span>
                </div>
                <div class="col-sm-6">
                  <span>เลขบัตรปชช : {{ $data->id_card }}</span>
                </div>
                <div class="col-sm-6">
                  <span>โทรศัพท์มือถือ : {{ $details->tel_mobile  }}</span>
                </div>
                <div class="col-sm-6">
                  <span>อีเมลล์ : {{ $data->email }}</span>
                </div>
              </div>
              <hr>

              <p class="f-w-600 mb-2">บัญชีธนาคาร</p>
              <div class="row f-14">
                <div class="col-sm-6">
                  <span>ธนาคาร : {{ $details->bank_name }}</span>
                </div>
                <div class="col-sm-6">
                  <span>สาขา : {{ $details->bank_branch }}</span>
                </div>
                <div class="col-sm-6">
                  <span>ชื่อบัญชี : {{ $details->bank_account }}</span>
                </div>
                <div class="col-sm-6">
                  <span>เลขบัญชี : {{ $details->bank_no }}</span>
                </div>
                <div class="col-sm-6">
                  <span>ชนิดบัญชี : {{ $details->bank_type }}</span>
                </div>
              </div>
              <hr>

              <p class="f-w-600 mb-2">ที่อยู่สำหรับการจัดส่งเอกสาร / สินค้า</p>
              <div class="row f-14">
                <div class="col-sm-6">
                  <span>เลขที่ : {{ $details->house_no }}</span>
                </div>
                <div class="col-sm-6">
                  <span>อาคาร : {{ $details->house_name }}</span>
                </div>
                <div class="col-sm-6">
                  <span>หมู่ : {{ $details->moo }}</span>
                </div>
                <div class="col-sm-6">
                  <span>ซอย : {{ $details->soi }}</span>
                </div>
                <div class="col-sm-6">
                  <span>ถนน : {{ $details->road }}</span>
                </div>
                <div class="col-sm-6">
                  <span>แขวง/ตำบล : {{ $details->district }}</span>
                </div>
                <div class="col-sm-6">
                  <span>เขต/อำเภอ : {{ $details->amphure }}</span>
                </div>
                <div class="col-sm-6">
                  <span>จังหวัด : {{ $details->province }}</span>
                </div>
                <div class="col-sm-6">
                  <span>รหัสไปรษณีย์ : {{ $details->zipcode }}</span>
                </div>
              </div>
              <hr>

              <p class="f-w-600 mb-2">การสืบทอดผลประโยชน์</p>
              <div class="row f-14">
                <div class="col-sm-6">
                  <span>ผู้สืบทอดผลประโยชน์ : {{ $details->benefit_name }}</span>
                </div>
                <div class="col-sm-6">
                  <span>เลขบัตรปชช : {{ $details->benefit_id_card }}</span>
                </div>
                <div class="col-sm-6">
                  <span>ความสัมพันธ์ : {{ $details->benefit_relation }}</span>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
  <div class="col-md-6">

    <div class="card">

      <div class="card-header">
       <h4>Coupon Code</h4>
        <div class="row">
          <div class="col-md-4">
           <select class="form-control" id="status" >

              <option value="1">ใช้งานได้</option>
              <option value="2">ถูกใช้แล้ว</option>
              <option value="expiry_date">หมดอายุ</option>
              {{-- <option value="">ทั้งหมด</option> --}}
            </select>
          </div>

        </div>
      </div>
      <div class="card-block">
        <div class="table-responsive dt-responsive">

          <table id="dt_coupon_code" class="table table-striped table-bordered nowrap">
            {{-- <thead>
              <tr>



              </tr>
            </thead> --}}

          </table>
        </div>

 <span class="text-danger">*รหัสโปรโมชั่นละ 1 ชุด สามารถส่งต่อให้สมาชิกท่านอื่นๆได้ / ไม่สามารถใช้สิทธิ์กับรายการส่งเสริมการขายอื่นๆ รวมถึงการเติม Ai-Stockist</span>
      </div>


    </div>
  </div>
</div>
@endsection
@section('js')
<!-- data-table js -->

<script src="{{asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/jszip.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/pdfmake.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/vfs_fonts.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/data-table/js/data-table-custom.js')}}"></script>


<script type="text/javascript">

  var oTable;
  $(function() {
      oTable = $('#dt_coupon_code').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
          ajax: {
              url: '{{ route("dt_coupon_code") }}',
              data: function(d) {
                  d.status = $('#status').val();

              },
              method: 'get'
          },


          columns: [

            {
                        data: 'date',
                        title: '<center>Valida From</center>',
                        className: 'text-center'
                    },

                    {
                        data: 'expiry_date',
                        title: '<center>Valida To</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'code',
                        title: '<center>CODE</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'detail',
                        title: '<center>Description / Promotion</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'used',
                        title: '<center>ผู้ใช้งาน</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        title: '<center>Status</center>',
                        className: 'text-center'
                    },

          ]
      });
      $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
          oTable.draw();
      });

      $('#status').on('change', function(e) {
          oTable.draw();
          e.preventDefault();
      });
  });


</script>
@endsection


