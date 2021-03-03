<?php
use App\Helpers\Frontend;
$customer_data = Frontend::get_customer(Auth::guard('c_user')->user()->id);

?>
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')

@endsection
<div class="row">
  <div class="col-sm-12">
    <!-- Panel card start -->
    <div class="card">
      <div class="card-header">
        <h4><i class="fa fa-user"></i> ข้อมูลส่วนตัว</h4>
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
            <h4 style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_aipocket) }} PV</span></h4>
          </div>
          <div class="panel-footer text-warning">
            {{-- Panel Footer --}}
          </div>
        </div>
      </div>



  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
    <div class="panel panel-danger" >
      <div class="panel-heading bg-danger">
       <b>Gift Voucher</b>
     </div>
     <div class="panel-body">
      <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>
      <?php
      $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
      ?>
      <h4>{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format($gv->sum_gv) }} </span></h4>
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

    <h4 style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->ai_cash) }} PV</span></h4>
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


<div class="row">
  <div class="col-md-12">

    <div class="card">

      <div class="card-header">
       <h4>Coupon Code</h4>
        <div class="row">
          <div class="col-md-4">
           <select class="form-control" id="status" >
              <option value="">ทั้งหมด</option>
              <option value="1">ใช้งานได้</option>
              <option value="2">ถูกใช้แล้ว</option>
              <option value="expiry_date">หมดอายุ</option>
            </select>
          </div>

        </div>
      </div>
      <div class="card-block">
        <div class="table-responsive dt-responsive">

          <table id="dt_coupon_code" class="table table-striped table-bordered nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>Start</th>
                <th>Expiry</th>
                <th>CODE</th>
                <th>Detail</th>
                <th>Status</th>

              </tr>
            </thead>

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
  $(document).ready(function() {
    fetch_data();

  });

  function fetch_data(status = '') {

    $('#dt_coupon_code').DataTable({
        // scrollX: true,
        // scrollCollapsed: true,
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
          url: "{{ route('dt_coupon_code') }}",
          dataType: "json",
          type: "get",
          data: {status:status}
        },

        columns:[
        {"data": "id"},
        {"data": "date"},
        {"data": "expiry_date"},
        {"data": "code"},
        {"data": "detail"},
        {"data": "status"},

        ],
        //order: [[ "0", "desc" ]],
      });
  }

  $('#status').on('change',function(){
    var status = $(this).val();
    $('#dt_coupon_code').DataTable().destroy();
    fetch_data(status);
  });


</script>
@endsection


