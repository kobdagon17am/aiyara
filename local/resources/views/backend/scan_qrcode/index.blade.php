@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  .sorting_disabled {background-color: #cccccc !important;font-weight: bold;}
</style>
@endsection

@section('content')


<div class="row">
    <div class="col-md-12" style="">
        <div id="spinner_frame"
            style="display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            z-index: 9999;
            "><p align="center">
                <img src="{{ asset('backend/images/preloader_big.gif') }}">
            </p></div>
        </div>
    </div>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">ค้นใบสั่งซื้อ </h4>
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


                <div class="row" >

                  <div class="col-12">
                        <div class="form-group row ">

                            <div class="col-md-10 d-flex  ">
                              <label class="col-4" >ค้นด้วย : QR-CODE / เลขที่ใบเสร็จ / รหัสลูกค้า : </label>
                              <div class="col-md-4">
                              <input type="text" class="form-control" name="txtSearch" style="font-size: 18px !important;color: blue;" autofocus >
                            </div>
                               <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;padding: 0.7%" >
                                <i class="bx bx-search align-middle "></i> SEARCH
                              </a>
                            </div>

                      </div>
                  </div>

                </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endsection

@section('script')

<script type="text/javascript">

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
          url: '{{ route('backend.scan_qrcode.datatable') }}',
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
            {data: 'qrcode', title :'<center>รหัส QR-CODE </center>', className: 'text-left'},
            {data: 'receipt', title :'<center>เลขที่ใบเสร็จ </center>', className: 'text-left'},
            {data: 'customer_username', title :'<center>รหัสลูกค้า </center>', className: 'text-center'},
        ],

    });

});


</script>


  <script>


        $(document).ready(function() {

            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  var txtSearch = $("input[name='txtSearch']").val();
                  // alert(txtSearch);
                  if(txtSearch==""){
                    $("input[name='txtSearch']").focus();
                    return false;
                  }

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
                                destroy: true,
                                scrollY: ''+($(window).height()-370)+'px',
                                iDisplayLength: 25,

                                 ajax: {
                                      url: '{{ route('backend.scan_qrcode.datatable') }}',
                                      type: "POST",
                                      data: { txtSearch: txtSearch },
                                  },

                                   columns: [
                                      {data: 'id', title :'ID', className: 'text-center w50'},
                                      {data: 'qrcode', title :'<center>รหัส QR-CODE </center>', className: 'text-left'},
                                      {data: 'receipt', title :'<center>เลขที่ใบเสร็จ </center>', className: 'text-left'},
                                      {data: 'customer_username', title :'<center>รหัสลูกค้า </center>', className: 'text-center'},
                                  ],

                            });

                        });

            });

        });
    </script>


@endsection

