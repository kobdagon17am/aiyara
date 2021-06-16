@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  table.minimalistBlack {
    border: 2px solid #000000;
    width: 100%;
    text-align: left;
    border-collapse: collapse;
  }
  table.minimalistBlack td, table.minimalistBlack th {
    border: 1px solid #000000;
    padding: 5px 4px;
  }
  table.minimalistBlack tbody td {
    font-size: 13px;
  }
  table.minimalistBlack thead {
    background: #CFCFCF;
    background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
    border-bottom: 2px solid #000000;
  }
  table.minimalistBlack thead th {
    font-size: 15px;
    font-weight: bold;
    color: #000000;
    text-align: center;
  }
  table.minimalistBlack tfoot td {
    font-size: 14px;
  }


</style>
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 1200px !important; /* New width for default modal */
        }
    }

    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

  .tooltip_packing {
    position: relative ;
  }
  .tooltip_packing:hover::after {
    content: "Packing List" ;
    position: absolute ;
    /*top: 0.5em ;*/
    left: -4em ;
    min-width: 80px ;
    border: 1px #808080 solid ;
    padding: 1px ;
    color: black ;
    background-color: #cfc ;
    z-index: 9999 ;
  } 


.divTable{
    display: table;
    width: 100%;
    
  }
  .divTableRow {
    display: table-row;
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
  }
  .divTableCell, .divTableHead {
    border: 1px solid white;
    display: table-cell;
    padding: 3px 6px;
    word-break: break-all; 
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
    font-weight: bold;
  }
  .divTableFoot {
    background-color: #EEE;
    display: table-footer-group;
    font-weight: bold;
  }
  .divTableBody {
    display: table-row-group;
  }
  .divTH {text-align: right;}



</style>
@endsection

@section('content')

<div class="myloading"></div>


  <?php 
      $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
        $can_packing_list = '1';
        $can_payproduct = '1';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
        $can_packing_list = @$menu_permit->can_packing_list==1?'1':'0';
        $can_payproduct = @$menu_permit->can_payproduct==1?'1':'0';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
      // echo  @$menu_permit->can_packing_list;     
      // echo  @$menu_permit->can_payproduct;     
      // echo $can_packing_list."xxxxxxxxxxxxxxxxxxxxxxxxxxx";     
   ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">

          <div class="col-6">
            <h4 class="font-size-18 test_clear_data "> เบิกจ่ายสินค้าจากคลัง  </h4>
          </div>
          
          <div class="col-4" style="padding: 5px;text-align: center;font-weight: bold;margin-left: 5%;">
            <span >
              <?php $role = DB::select("SELECT * FROM `role_group` where id=".(\Auth::user()->role_group_id_fk)." "); ?>
              <?php $branchs = DB::select("SELECT * FROM `branchs` where id=".(\Auth::user()->branch_id_fk)." "); ?>
              <?php $warehouse = DB::select("SELECT * FROM `warehouse` where id=".($branchs[0]->warehouse_id_fk)."  "); ?>
              {{ \Auth::user()->name }} / {{ "สาขา > ".$branchs[0]->b_name }}  / {{ $warehouse[0]->w_name }} </span>
            </div>

           <div class="col-2" style="margin-left: 3%;">
            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pay_requisition_001") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>
          </div>

        </div>
    </div>
    
</div>



<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="myBorder" style="" >
          <div class="col-12">
            <div class="form-group row  " >
              <div class="col-md-12 ">
                <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;margin-bottom: 0%;" ></table>
                <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;margin-bottom: 0%;" ></table>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="form-group row  " >
              <div class="col-md-12 ">
              <!--   <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลรอเบิก </span> -->
              <table id="warehouse_address_sent" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
              <center><input type='button' class="btn btn-primary btnExportElsx " value='ส่งออกไฟล์ Excel (.xlsx) ให้ KERRY' >
            </div>
          </div>
        </div>



          <div class="col-12">
            <div class="form-group row  " >
              <div class="col-md-12 ">
                <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลรอจ่ายสินค้า (Import จาก KERRY) </span>

                <form class="form-horizontal" method="POST" action="backend/uploadFileXLSConsignments" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="form-group row">

                    <input type="hidden" name="requisition_code" value="{{@$requisition_code}}">
                    
                    <label for="receipt" class="col-md-3 col-form-label">นำเข้าไฟล์ Excel (.xlsx) จาก KERRY :</label>
                    <div class="col-md-3">
                      <input type="file" accept=".xlsx" class="form-control" name="fileXLS" required>
                    </div>
                    <div class="col-md-3" style="" >
                      <input type='submit' name="submit" class="btn btn-primary btnImXlsx " value='IMPORT'>
                      &nbsp;
                      &nbsp;
                      &nbsp;
                      <input type='button' class="btn btn-danger btnClearImport " value='Clear data Import' >
                    </div>
                    
                  </div>
                  
                  @if(Session::has('message'))
                  <div class="form-group row ">
                    <label for="receipt" class="col-md-2 col-form-label"></label>
                    <div class="col-md-6 ">
                      <p style="color:green;font-weight:bold;font-size: 16px;" >{{ Session::get('message') }}</p>
                    </div>
                  </div>
                  @endif
                  
                </form>
              <table id="data-table-import" class="table table-bordered dt-responsive" style="width: 100%;background-color: white;"></table>
              <center><input type='button' class="btn btn-primary btnMapConsignments " value='Map Consignments Code' >
            </div>
          </div>
        </div>

       <div class="form-group row">
              <div class="col-md-12">
              <table id="data-table-map-consignments" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
            </div>
            </div>


       <div class="form-group row">
              <div class="col-md-12">
                  <center>
                
                            @IF($can_cancel_packing_sent==1 || $sPermission==1 )

                <input style="color: black;" type='button' class="btn btn-warning btnCancelStatusPacking font-size-16 " value="ยกเลิกสถานะ การ Packing / จัดส่ง " >
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                 @ENDIF

                @IF($sUser[0]->status_sent==5)
                @ELSE
                    
                    @IF($sUser[0]->status_sent==4)
                    <input type='button' class="btn btn-primary btnShippingFinished font-size-16 " value="บริษัทขนส่งเข้ารับสินค้าแล้ว" >
                    @ELSE
                    <input type='button' class="btn btn-primary btnPackingFinished font-size-16 " value="Packing เรียบร้อยแล้ว" >
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='button' class="btn btn-primary btnShippingFinished font-size-16 " value="บริษัทขนส่งเข้ารับสินค้าแล้ว" disabled="">
                    @ENDIF

                @ENDIF

    

                 </center>


            </div>
            </div>

  </div>
</div>
</div>
</div> <!-- end col -->
</div> <!-- end row -->


@endsection
@section('script')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

<script>
 // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
          var id = "{{$id}}"; //alert(id);
          var packing_id = "{{$packing_id}}"; //alert(packing_id);
          var oTable0001;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable0001 = $('#data-table-0001').DataTable({
               "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                  serverSide: true,
                  scroller: false,
                  deferRender: true,
                  scrollCollapse: false,
                  scrollX: false,
                  ordering: false,
                  ordering: false,
                  info:     false,
                  paging:   false,
                        ajax: {
                            url: '{{ route('backend.warehouse_qr_0001.datatable') }}',
                            method: "POST",
                            data:{ _token: '{{csrf_token()}}',
                            id:id,
                            picking_id:packing_id,
                          },
                        },
                  columns: [
                      {data: 'column_001', title :'<span style="vertical-align: middle;"> รหัสใบเบิก </span> ', className: 'text-center w150'},
                      {data: 'column_002', title :'<span style="vertical-align: middle;"> รหัส Packing List ในใบเบิก</span> ', className: 'text-center w200'},
                      {data: 'column_003', title :'<span style="vertical-align: middle;"> รหัสใบเสร็จในใบเบิก</span> ', className: 'text-center w350'},
                      // {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> รายการ Packing List </span> ', className: 'text-left',render: function(d) {
                      //   return d;
                      // }},
                  ],
                  rowCallback: function(nRow, aData, dataIndex){
                     $(".myloading").hide();
                  }
              });
         
         });
    // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>


<script>
 // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
          var id = "{{$id}}";
          var packing_id = "{{$packing_id}}";// alert(packing_id);
          var oTable0002;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable0002 = $('#data-table-0002').DataTable({
               "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                  serverSide: true,
                  deferRender: true,
                  scroller: false,
                  scrollCollapse: false,
                  scrollX: false,
                  ordering: false,
                  ordering: false,
                  info:     false,
                  paging:   false,
                        ajax: {
                            url: '{{ route('backend.warehouse_qr_0002.datatable') }}',
                            method: "POST",
                            data:{ _token: '{{csrf_token()}}',picking_id:packing_id,id:id},
                        },
                  columns: [
                      {data: 'column_001', title :'<span style="vertical-align: middle;"> รหัส Packing List  </span> ', className: 'text-center w150'},
                      {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> รายการสินค้า </span> ', className: 'text-left',render: function(d) {
                        return d;
                      }},
                  ],
                  rowCallback: function(nRow, aData, dataIndex){
                     $(".myloading").hide();
                  }
              });
         
         });
    // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>

  <script type="text/javascript">
       var oTableMap;
         var requisition_code = "{{$requisition_code}}"; 

         if(requisition_code){

            // console.log(requisition_code);
            $(function() {
              $.fn.dataTable.ext.errMode = 'throw';
                oTableMap = $('#data-table-map-consignments').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    destroy: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    // scrollY: ''+($(window).height()-370)+'px',
                    // iDisplayLength: 10,
                    stateSave: true,
                     ajax: {
                          url: '{{ route('backend.consignments_map.datatable') }}',
                          method: "POST",
                          data:{ _token: '{{csrf_token()}}',
                          requisition_code:requisition_code,
                        },
                      },
                    columns: [
                        {data: 'consignment_no', title :'<center>Consignment No </center>', className: 'text-center'},
                        {data: 'recipient_code', title :'<center>Recipient Code <br>(รหัสผู้รับ)</center>', className: 'text-center'},
                        {data: 'recipient_name', title :'<center>Recipient Name <br>(ชื่อผู้รับ)</center>', className: 'text-center'},
                        {data: 'address', title :'<center>Address<br>(ที่อยู่ผู้รับ)</center>', className: 'text-center'},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
             
                    }
                  });
           
                });

    }


  </script>
<script>
 // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
          var id = "{{$id}}";
          var packing_id = "{{$packing_id}}"; //alert(packing_id);
          var requisition_code = "{{$requisition_code}}"; //alert(requisition_code);
          var oTable0005;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable0005 = $('#warehouse_address_sent').DataTable({
               "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                  serverSide: true,
                  deferRender: true,
                  scroller: false,
                  scrollCollapse: false,
                  scrollX: false,
                  ordering: false,
                  ordering: false,
                  info:     false,
                  paging:   false,
                        ajax: {
                            url: '{{ route('backend.warehouse_address_sent.datatable') }}',
                            method: "POST",
                            data:{ _token: '{{csrf_token()}}',packing_id:packing_id,id:id},
                        },
                  columns: [
                      {data: 'recipient_code', title :'<span style="vertical-align: middle;"> Recipient Code <br> (รหัสผู้รับ)  </span> ', className: 'text-center w150'},
                      {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> Recipient Name (ชื่อผู้รับ) <br> Address (ที่อยู่ผู้รับ) </span> ', className: 'text-left',render: function(d) {
                        return d;
                      }},
                      {data: 'column_003',   title :'<span style="vertical-align: top;"><center>ใบปะหน้ากล่อง & ใบเสร็จ ', className: 'text-left',render: function(d) {
                        return '<center> <a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a> '
                          + ' <a href="{{ URL::to('backend/frontstore/print_receipt_02') }}/'+d+'" target=_blank > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
                      }},
                  ],
                  rowCallback: function(nRow, aData, dataIndex){
                     // $(".myloading").hide();
                  }
              });
         
         });
    // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>

  <script type="text/javascript">
    $(document).ready(function() {

      $(document).on('change', '.qr_scan', function(e) {

          var v = $(this).val();
          var item_id = $(this).data('item_id');
          var packing_code = $(this).data('packing_code');
          var product_id_fk = $(this).data('product_id_fk');
          // alert(v+":"+invoice_code+":"+product_id_fk);
          if($(this).val()!=''){
            $(this).css({ 'background-color' : '', 'opacity' : '' });
          }

             $(".myloading").show();

             $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxScanQrcodeProductPacking') }} ",
                 data:{ _token: '{{csrf_token()}}',item_id:item_id,qr_code:v,packing_code:packing_code,product_id_fk:product_id_fk },
                  success:function(data){
                       // console.log(data);
                       $.each(data,function(key,value){
                       });
                       $(".myloading").hide();
                    },
                  error: function(jqXHR, textStatus, errorThrown) {
                      $(".myloading").hide();
                  }
              });

        
      });


      $(document).on('click', '.btnDeleteQrcodeProduct', function(e) {

          var item_id = $(this).data('item_id');
          var packing_code = $(this).data('packing_code');
          var product_id_fk = $(this).data('product_id_fk');
          // alert(item_id+":"+packing_code+":"+product_id_fk);

             $(".myloading").show();
             $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxDeleteQrcodeProductPacking') }} ",
                 data:{ _token: '{{csrf_token()}}',item_id:item_id,packing_code:packing_code,product_id_fk:product_id_fk },
                  success:function(data){
                       // console.log(data);
                       $.each(data,function(key,value){
                       });

                       $(".myloading").hide();
                    },
                  error: function(jqXHR, textStatus, errorThrown) {
                      $(".myloading").hide();
                  }
              });


          $(this).prev().css({ 'background-color' : 'blanchedalmond'});
          $(this).prev().val('');

        
      });



    });

  </script>

  <script>
      $(document).ready(function() {
        
                $(".btnGentoExport").click(function(event) {
                    /* Act on the event */
                    $(".myloading").show();
                    $.ajax({

                           type:'POST',
                           url: " {{ url('backend/ajaxGentoExportConsignments') }} ", 
                           data:{ _token: '{{csrf_token()}}' },
                            success:function(data){
                                 console.log(data); 
                                 location.reload();
                                 $(".myloading").hide();
                              },
                            error: function(jqXHR, textStatus, errorThrown) { 
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                $(".myloading").hide();
                            }
                        });
                });


                $(".btnExportElsx").click(function(event) {
                    /* Act on the event */
                    $(".myloading").show();
                    $.ajax({

                           type:'POST',
                           url: " {{ url('backend/excelExportConsignment') }} ", 
                           data:{ _token: '{{csrf_token()}}' },
                            success:function(data){
                                 console.log(data); 
                                 // location.reload();
                                 setTimeout(function(){
                                    var url='local/public/excel_files/consignments.xlsx';
                                    window.open(url, 'Download');  
                                    $(".myloading").hide();
                                },3000);

                              },
                            error: function(jqXHR, textStatus, errorThrown) { 
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                $(".myloading").hide();
                            }
                        });
                });



               $(".btnImXlsx").click(function(event) {
                      /* Act on the event */
                      var v = $("input[name=fileXLS]").val();
                      if(v!=''){
                        $(".myloading").show();
                      }
                      
                });



              $(".btnClearImport").click(function(event) {
                  /* Act on the event */
                  $(".myloading").show();

                  $.ajax({

                         type:'POST',
                         url: " {{ url('backend/ajaxClearConsignment') }} ", 
                         data:{ _token: '{{csrf_token()}}' },
                          success:function(data){
                               console.log(data); 
                               location.reload();
                            },
                          error: function(jqXHR, textStatus, errorThrown) { 
                              console.log(JSON.stringify(jqXHR));
                              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              $(".myloading").hide();
                          }
                      });
              });



            $(".btnMapConsignments").click(function(event) {
                  /* Act on the event */
                  $(".myloading").show();

                  $.ajax({

                         type:'POST',
                         url: " {{ url('backend/ajaxMapConsignments') }} ", 
                         data:{ _token: '{{csrf_token()}}' },
                          success:function(data){
                               console.log(data); 
                               // location.reload();
                               // $('#data-table-map-consignments').DataTable().draw();

                              // setTimeout(function(){

                                    var oTableMap;
                                    var requisition_code = "{{$requisition_code}}"; 
                                    // console.log(requisition_code);
                                    $(function() {
                                      $.fn.dataTable.ext.errMode = 'throw';
                                        oTableMap = $('#data-table-map-consignments').DataTable({
                                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                            processing: true,
                                            serverSide: true,
                                            deferRender: true,
                                            destroy: true,
                                            scroller: true,
                                            scrollCollapse: true,
                                            scrollX: true,
                                            ordering: false,
                                            // scrollY: ''+($(window).height()-370)+'px',
                                            // iDisplayLength: 10,
                                            stateSave: true,
                                             ajax: {
                                                  url: '{{ route('backend.consignments_map.datatable') }}',
                                                  method: "POST",
                                                  data:{ _token: '{{csrf_token()}}',
                                                  requisition_code:requisition_code,
                                                },
                                              },
                                            columns: [
                                                {data: 'consignment_no', title :'<center>Consignment No </center>', className: 'text-center'},
                                                {data: 'recipient_code', title :'<center>Recipient Code <br>(รหัสผู้รับ)</center>', className: 'text-center'},
                                                {data: 'recipient_name', title :'<center>Recipient Name <br>(ชื่อผู้รับ)</center>', className: 'text-center'},
                                                {data: 'address', title :'<center>Address<br>(ที่อยู่ผู้รับ)</center>', className: 'text-center'},
                                            ],
                                            rowCallback: function(nRow, aData, dataIndex){
                                     
                                            }
                                          });
                                   
                                        });

                              // }, 3000); 
                               $(".myloading").hide();
                            },
                          error: function(jqXHR, textStatus, errorThrown) { 
                              console.log(JSON.stringify(jqXHR));
                              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              $(".myloading").hide();
                          }
                  });
                  
              });


               $(".btnPackingFinished").click(function(event) {
                  $(".myloading").show();

                  var id = "{{$id}}"; 

                  Swal.fire({
                      title: 'ยืนยัน ! Packing เรียบร้อยแล้ว ',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {
                                
                              $(".myloading").show();
                          
                              $.ajax({
                                     method:'POST',
                                     url: " {{ url('backend/ajaxPackingFinished') }} ", 
                                     data:{ _token: '{{csrf_token()}}',id:id },
                                      success:function(data){
                                           console.log(data); 
                                           location.replace('{{url("backend/pay_requisition_001")}}');
                                        },
                                      error: function(jqXHR, textStatus, errorThrown) { 
                                          console.log(JSON.stringify(jqXHR));
                                          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                          $(".myloading").hide();
                                      }
                                  });

                          }else{
                            $(".myloading").hide();
                          }
                  });


              });


               $(".btnShippingFinished").click(function(event) {
                  $(".myloading").show();

                  var id = "{{$id}}"; 

                  Swal.fire({
                      title: 'ยืนยัน ! บริษัทขนส่งเข้ารับสินค้า เรียบร้อยแล้ว ',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {
                                
                              $(".myloading").show();
                          
                              $.ajax({
                                     method:'POST',
                                     url: " {{ url('backend/ajaxShippingFinished') }} ", 
                                     data:{ _token: '{{csrf_token()}}',id:id },
                                      success:function(data){
                                           console.log(data); 
                                           location.replace('{{url("backend/pay_requisition_001")}}');
                                        },
                                      error: function(jqXHR, textStatus, errorThrown) { 
                                          console.log(JSON.stringify(jqXHR));
                                          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                          $(".myloading").hide();
                                      }
                                  });

                          }else{
                            $(".myloading").hide();
                          }
                  });


              });



      });


      var sU = "{{@$sU}}"; //alert(sU);
      var sD = "{{@$sD}}"; //alert(sD);
      var oTableIm;
      $(function() {
        $.fn.dataTable.ext.errMode = 'throw';
          oTableIm = $('#data-table-import').DataTable({
          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
              processing: true,
              serverSide: true,
              deferRender: true,
              scroller: true,
              scrollCollapse: true,
              scrollX: true,
              ordering: false,
              scrollY: ''+($(window).height()-370)+'px',
              iDisplayLength: 10,
              stateSave: true,
              destroy:true,
              ajax: {
                url: '{{ route('backend.consignments_import.datatable') }}',
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
                  {data: 'consignment_no', title :'<center>Consignment No. <br> (หมายเลขพัสดุ) </center>', className: 'text-center'},
                  {data: 'recipient_code', title :'<center>Recipient Code <br>(รหัสผู้รับ)</center>', className: 'text-center'},
                  {data: 'recipient_name', title :'<center>Recipient Name <br>(ชื่อผู้รับ)</center>', className: 'text-center'},
                  {data: 'address', title :'<center>Address<br>(ที่อยู่ผู้รับ)</center>', className: 'text-center'},
              ],
              rowCallback: function(nRow, aData, dataIndex){
       
              }
            });
     
          });



    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/scannerdetection/1.2.0/jquery.scannerdetection.min.js" integrity="sha512-ZmglXekGlaYU2nhamWrS8oGQDJQ1UFpLvZxNGHwLfT0H17gXEqEk6oQBgAB75bKYnHVsKqLR3peLVqMDVJWQyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
      $(document).ready(function() {

        $(document).on('keypress', '.in-tx', function(e) {

             if (e.keyCode == 13) {
                  // alert("13");
                 var index = $('.in-tx').index(this) + 1;
                 $('.in-tx').eq(index).focus();
                  return false; // block form from being submitted yet
             }



        });

        // $(document).on('keyup', '.in-tx', function(e) {

        //          var index = $('.in-tx').index(this) + 1;
        //          $('.in-tx').eq(index).focus();
        //           return false;

        // });

                     $(window).scannerDetection();

              $(window)
                .bind('scannerDetectionComplete', function(e, data) {
          // element where your barcode is to be entered even if its not focused
                  $("#barcode").val(data.string);
                  console.log("Barcode Scanned" + data.string);
                           $('.in-tx').next().focus();
                            return false; // block form from being submitted yet

                })
                .bind('scannerDetectionError', function(e, data) {

                  console.log("Error in Barcode Scanning");
                           $('.in-tx').next().focus();
                            return false; // block form from being submitted yet
                })
                .bind('scannerDetectionReceive', function(e, data) {
                  console.log("recieving data:" + data);
                           $('.in-tx').next().focus();
                            return false; // block form from being submitted yet

                })

      });

    </script>



   <script type="text/javascript">
     
    $(document).ready(function() {

           $(document).on('click', '.btnCancelStatusPacking', function(){

            $(".myloading").show();

            var id = "{{$id}}"; 
            // alert(id);
            // return false;

                    Swal.fire({
                      title: 'ยืนยัน ! ยกเลิก สถานะการจัดส่ง ',
                      // text: 'You clicked the button!',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {

                             $.ajax({
                                url: " {{ url('backend/cancel-status-packing-sent') }} ", 
                                method: "post",
                                data: {
                                  id:id,
                                  "_token": "{{ csrf_token() }}", 
                                },
                                success:function(data)
                                { 
                                  // console.log(data);
                                  // return false;
                                      // Swal.fire({
                                      //   type: 'success',
                                      //   title: 'ทำการยกเลิกการจ่ายครั้งนี้เรียบร้อยแล้ว',
                                      //   showConfirmButton: false,
                                      //   timer: 2000
                                      // });

                                      setTimeout(function () {
                                          // $('#data-table-0002').DataTable().clear().draw();
                                          location.reload();
                                      }, 1000);
                                }
                              })
                            
                          }else{
                            $(".myloading").hide();
                          }
                    });

             });   

    });
   </script>

    <?php 


    //DB::select(" INSERT INTO `db_consignments` (`consignment_no`, `requisition_code`) VALUES ('xxxxxxxxxxxx', 'P300001') ");
    
    if(isset($_REQUEST['test_clear_data'])){

      DB::select("TRUNCATE db_pay_product_receipt_001;");
      DB::select("TRUNCATE db_pay_product_receipt_002;");
      DB::select("TRUNCATE db_pay_product_receipt_002_pay_history;");
      DB::select("TRUNCATE db_pay_product_receipt_002_cancel_log;");

      DB::select("TRUNCATE `db_pay_requisition_001`;");
      DB::select("TRUNCATE `db_pay_requisition_002`;");
      DB::select("TRUNCATE `db_pay_requisition_002_cancel_log`;");
      DB::select("TRUNCATE `db_pay_requisition_002_pay_history`;");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");
      
      DB::select("TRUNCATE `db_pick_pack_requisition_code`;");

      DB::select("TRUNCATE db_pick_warehouse_qrcode;");
      DB::select("TRUNCATE db_stocks_return;");
      DB::select("TRUNCATE db_stock_card;");
      DB::select("TRUNCATE db_stock_card_tmp;");

      DB::select("TRUNCATE db_consignments;");
          
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id; 
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id; 
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id; 

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");

      DB::select(" UPDATE db_stocks SET amt='100' ; ");

      ?>
          <script>
          location.replace( "{{ url('backend/pick_warehouse') }}");
          </script>
          <?php
      }
    ?>
@endsection



