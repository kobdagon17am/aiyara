@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> จ่ายสินค้าออก </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php 
      $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
   ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="background-color: #ccffff;"  >
                  <div class="col-8" style="display: flex;flex-direction: row;" >

                    <h4 class="mb-0 font-size-18" style="padding: 1%;"> รายการจ่ายออกรออนุมัติ </h4>

                    <input type="text" class="form-control float-left text-center w125 myLike" placeholder="หมายเลขใบจ่าย" name="po_number" style="margin-left: 1%;margin-top: 2px;">

                    <input type="text" class="form-control float-left text-center w125 myLike" placeholder="รหัสสินค้า" name="products_id_fk" style="margin-left: 1%;margin-top: 2px;">

                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}">
                    <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.products_out_approve.create') }}?role_group_id={{$role_group_id}}&menu_id={{$menu_id}}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD 
                    </a>
                  </div>

                </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="background-color: #99ff99;"  >
                  <div class="col-8" style="display: flex;flex-direction: row;" >

                    <h4 class="mb-0 font-size-18" style="padding: 1%;"> รายการจ่ายออกอนุมัติแล้ว </h4>

                    <input type="text" class="form-control float-left text-center w125 myLike" placeholder="Invoice No" name="po_number" style="margin-left: 1%;margin-top: 2px;">

                    <input type="text" class="form-control float-left text-center w125 myLike" placeholder="รหัสสินค้า" name="products_id_fk" style="margin-left: 1%;margin-top: 2px;">

                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}">
                    <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.products_out_approve.create') }}?role_group_id={{$role_group_id}}&menu_id={{$menu_id}}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD 
                    </a>
                  </div>

                </div>

                <table id="data-table-2" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="background-color: #ffffb3;"  >
                  <div class="col-8" style="display: flex;flex-direction: row;" >

                    <h4 class="mb-0 font-size-18" style="padding: 1%;"> รายการจ่ายออกที่ไม่อนุมัติ </h4>

                    <input type="text" class="form-control float-left text-center w125 myLike" placeholder="Invoice No" name="po_number" style="margin-left: 1%;margin-top: 2px;">

                    <input type="text" class="form-control float-left text-center w125 myLike" placeholder="รหัสสินค้า" name="products_id_fk" style="margin-left: 1%;margin-top: 2px;">

                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}">
                    <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.products_out_approve.create') }}?role_group_id={{$role_group_id}}&menu_id={{$menu_id}}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD 
                    </a>
                  </div>

                </div>

                <table id="data-table-3" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endsection

@section('script')

<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.products_out_approve.datatable') }}',
          data: function ( d ) {
            d.Where={};
            $('.myWhere').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Where[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Like={};
            $('.myLike').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Like[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Custom={};
            $('.myCustom').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Custom[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            oData = d;
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'invoice_no', title :'<center>หมายเลขใบจ่าย  </center>', className: 'text-center'},
            // {data: 'product_code', title :'<center>รหัสสินค้า </center>', className: 'text-center'},
            // {data: 'product_name', title :'<center>ชื่อสินค้า </center>', className: 'text-left'},
            {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
            {data: 'save_date', title :'<center>วันที่บันทึก </center>', className: 'text-center'},
            {data: 'save_time', title :'<center>เวลาที่บันทึก </center>', className: 'text-center'},

            {data: 'subwarehouse', title :'<center>คลังย่อย </center>', className: 'text-center'},
            {data: 'zone', title :'<center>zone </center>', className: 'text-center'},
            {data: 'shelf', title :'<center>shelf </center>', className: 'text-center'},

            {data: 'receptionist_name', title :'<center>พนักงานที่ทำรายการ </center>', className: 'text-center'},
            // {data: 'qrcode',   title :'<center>Qr-code</center>', className: 'text-center w100 ',render: function(d) {
            //     return '<center><img  src="{{ asset('asset/qrcode/temp/qr.png') }}" style="width: 40%;" ></center>';
            // }},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.products_out_approve.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.products_out_approve.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');

          }
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-2').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.products_out_approve.datatable') }}',
          data: function ( d ) {
            d.Where={};
            $('.myWhere').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Where[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Like={};
            $('.myLike').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Like[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Custom={};
            $('.myCustom').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Custom[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            oData = d;
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'invoice_no', title :'<center>หมายเลขใบจ่าย  </center>', className: 'text-center'},
            // {data: 'product_code', title :'<center>รหัสสินค้า </center>', className: 'text-center'},
            // {data: 'product_name', title :'<center>ชื่อสินค้า </center>', className: 'text-left'},
            {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
            {data: 'save_date', title :'<center>วันที่บันทึก </center>', className: 'text-center'},
            {data: 'save_time', title :'<center>เวลาที่บันทึก </center>', className: 'text-center'},

            {data: 'subwarehouse', title :'<center>คลังย่อย </center>', className: 'text-center'},
            {data: 'zone', title :'<center>zone </center>', className: 'text-center'},
            {data: 'shelf', title :'<center>shelf </center>', className: 'text-center'},


            {data: 'receptionist_name', title :'<center>พนักงานที่ทำรายการ </center>', className: 'text-center'},
            // {data: 'qrcode',   title :'<center>Qr-code</center>', className: 'text-center w100 ',render: function(d) {
            //     return '<center><img  src="{{ asset('asset/qrcode/temp/qr.png') }}" style="width: 40%;" ></center>';
            // }},
            {data: 'approve_date', title :'<center>วันที่อนุมัติ </center>', className: 'text-center'},
            {data: 'approve_time', title :'<center>เวลาที่อนุมัติ </center>', className: 'text-center'},
            {data: 'approver_name', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},

            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.products_out_approve.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.products_out_approve.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');

          }
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-3').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.products_out_approve.datatable') }}',
          data: function ( d ) {
            d.Where={};
            $('.myWhere').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Where[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Like={};
            $('.myLike').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Like[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            d.Custom={};
            $('.myCustom').each(function() {
              if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                d.Custom[$(this).attr('name')] = $.trim($(this).val());
              }
            });
            oData = d;
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'invoice_no', title :'<center>หมายเลขใบจ่าย  </center>', className: 'text-center'},
            // {data: 'product_code', title :'<center>รหัสสินค้า </center>', className: 'text-center'},
            // {data: 'product_name', title :'<center>ชื่อสินค้า </center>', className: 'text-left'},
            {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
            {data: 'save_date', title :'<center>วันที่บันทึก </center>', className: 'text-center'},
            {data: 'save_time', title :'<center>เวลาที่บันทึก </center>', className: 'text-center'},

            {data: 'subwarehouse', title :'<center>คลังย่อย </center>', className: 'text-center'},
            {data: 'zone', title :'<center>zone </center>', className: 'text-center'},
            {data: 'shelf', title :'<center>shelf </center>', className: 'text-center'},

            {data: 'receptionist_name', title :'<center>พนักงานที่ทำรายการ </center>', className: 'text-center'},
            // {data: 'qrcode',   title :'<center>Qr-code</center>', className: 'text-center w100 ',render: function(d) {
            //     return '<center><img  src="{{ asset('asset/qrcode/temp/qr.png') }}" style="width: 40%;" ></center>';
            // }},
            {data: 'approver_name', title :'<center>ผู้ไม่อนุมัติ </center>', className: 'text-center'},
            {data: 'reason_no_approve', title :'<center>เหตุผล </center>', className: 'text-center'},
            {data: 'approve_date', title :'<center>วันที่ </center>', className: 'text-center'},
            {data: 'approve_time', title :'<center>เวลา </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.products_out_approve.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.products_out_approve.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');

          }
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


<script type="text/javascript">
/*
  sessionStorage.setItem("role_group_id", role_group_id);
  var role_group_id = sessionStorage.getItem("role_group_id");
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?role_group_id=' + role_group_id + '&menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }
  */
</script>

@endsection

