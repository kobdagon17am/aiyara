@extends('frontend.layouts.customer.customer_app')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

@endsection
@section('conten')

<div class="row">
  <div class="col-md-12">
      <div class="card">

          <div class="card-header">
            <div class="row">
              <?php
              $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);

              ?>
              <h4 class="m-b-10">รายการ Ai Voucher [ คงเหลือ <b class="text-danger">{{ number_format($gv->sum_gv) }}</b> ]</h4>
            </div>

              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" id="status" >
                      <option value="">ทั้งหมด</option>
                      <option value="not_expiry_date">ยังไม่หมดอายุ</option>
                      <option value="expiry_date">หมดอายุ</option>
                    </select>
                  </div>

                    <div class="col-lg-3 col-md-3">
                        <input class="form-control" type="date" id="s_date">
                    </div>

                    <div class="col-lg-3 col-md-3 ">
                        <input class="form-control" type="date" id="e_date">
                    </div>
                    <div class="col-lg-3 col-md-2 ">
                        <button id="search-form" class="btn btn-primary btn-block"> Search </button>
                    </div>
                </div>
            </div>
          </div>

          <div class="card-block">
              <div class="dt-responsive table-responsive">
                  <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                  </table>
              </div>

          </div>

      </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
      <div class="card">

          <div class="card-header">
            <div class="row">

              <h4 class="m-b-10">ประวัติความเคลื่อนไหว Ai Voucher </h4>
            </div>

              <div class="col-md-12">
                <div class="row">
                  {{-- <div class="col-md-3">
                    <select class="form-control" id="status" >
                      <option value="">ทั้งหมด</option>
                      <option value="not_expiry_date">ยังไม่หมดอายุ</option>
                      <option value="expiry_date">หมดอายุ</option>
                    </select>
                  </div> --}}

                    <div class="col-lg-3 col-md-3">
                        <input class="form-control" type="date" id="s_date_2">
                    </div>

                    <div class="col-lg-3 col-md-3 ">
                        <input class="form-control" type="date" id="e_date_2">
                    </div>
                    <div class="col-lg-3 col-md-2 ">
                        <button id="search-form" class="btn btn-primary btn-block"> Search </button>
                    </div>
                </div>
            </div>
          </div>

          <div class="card-block">
              <div class="dt-responsive table-responsive">
                  <table id="log_gv" class="table table-striped table-bordered nowrap">
                  </table>
              </div>

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

<script type="text/javascript">

  var oTable = $('#multi-colum-dt').DataTable({
      processing: true,
      serverSide: true,
      searching: true,
      ajax: {
          url: "{!! route('dt_giftvoucher_history') !!}",
          type:'GET',
          data: function(d) {
              d.status = $('#status').val();
              d.s_date = $('#s_date').val();
              d.e_date = $('#e_date').val();
          }
          },
      // type: "POST",

      columns: [{
                        data: 'pro_sdate',
                        title: '<center>วันที่ได้รับ</center>',
                        className: 'text-center'
                    },

                    {
                        data: 'pro_edate',
                        title: 'วันหมดอายุ',
                        className: 'text-center'
                    },

                    {
                        data: 'expri',
                        title: 'วัน',
                        className: 'text-center'
                    },

                    {
                        data: 'detail',
                        title: '<center>รายละเอียด</center>',
                        className: 'text-right'
                    },

                    {
                        data: 'giftvoucher_value',
                        title: '<center> Ai Voucher</center>',
                        className: 'text-right'
                    },

                    {
                        data: 'banlance',
                        title: '<center>คงเหลือ</center>',
                        className: 'text-right'
                    },
          ],

  });

  $('#search-form').on('click', function(e) {
      oTable.draw();
      e.preventDefault();
  });

</script>

<script type="text/javascript">

  var oTable = $('#log_gv').DataTable({
      processing: true,
      serverSide: true,
      searching: true,
      ajax: {
          url: "{!! route('dt_gift_order_history') !!}",
          type:'GET',
          data: function(d) {
              d.s_date = $('#s_date_2').val();
              d.e_date = $('#e_date_2').val();
          }
        },
      columns: [{
                        data: 'date',
                        title: '<center>Date</center>',
                        className: 'text-center'
                    },

                    {
                        data: 'code_order',
                        title: 'OrderCode',
                        className: 'text-center'
                    },

                    {
                        data: 'detail',
                        title: '<center>Detail</center>',
                        className: 'text-center'
                    },

                    {
                        data: 'giftvoucher_value_old',
                        title: '<center>Old Voucher</center>',
                        className: 'text-right'
                    },

                    {
                        data: 'giftvoucher_value_use',
                        title: '<center>Movement </center>',
                        className: 'text-right'
                    },

                    {
                        data: 'giftvoucher_value_banlance',
                        title: '<center>Balance</center>',
                        className: 'text-right'
                    },

                    // {
                    //     data: 'type',
                    //     title: '<center>Type</center>',
                    //     className: 'text-center'
                    // },

                    {
                        data: 'status',
                        title: '<center>Stattus</center>',
                        className: 'text-center'
                    },
          ],order:[[0,'DESC']],

  });

  $('#search-form').on('click', function(e) {
      oTable.draw();
      e.preventDefault();
  });

</script>

@endsection


