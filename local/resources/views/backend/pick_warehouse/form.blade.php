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


  table#data-table-0003 thead { display: none; }
  table#data-table-0004 thead { display: none; }

</style>
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 1200px !important; /* New width for default modal */
        }
    }

    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}


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
            <!-- <table id="data-table-0000" class="table table-bordered dt-responsive" style="width: 100%;" ></table> -->
               
                    @IF(@$sUser[0]->status_sent!=3)
                          <div style="background-color:#ffeecc;padding-top: 10px;display: none;">
                        @ELSE 
                          <div style="background-color:#ffe6ff;padding-top: 10px;display: none;">
                        @ENDIF 

                            <div class="row">
                              <div class="col-lg-2">
                                <div class="text-lg-center">
                                  <img src="backend/images/users/user.png" class=" images_users rounded-circle mb-1 float-left float-lg-none" alt="img" style="width:18%;height: 15%;" />
                                  <h2 class="font-size-15 text-truncate user_code " data-toggle="tooltip" data-placement="top" title="รหัสสมาชิก">{{@$sUser[0]->user_code}}</h2>
                                </div>
                              </div>
                              <div class="col-lg-10" style="text-align: left;" >
                                  <h5 class="font-size-20 text-truncate mb-lg-2  " ><b data-toggle="tooltip" data-placement="top" title="ชื่อสมาชิก" class="user_name" >{{@$sUser[0]->user_name}}</b></h5>
                                  <ul class="list-inline mb-0" style="text-align: left;">

                                    <li class="list-inline-item ">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="ที่อยู่จัดส่ง"><i class="bx bx-home mr-1 text-primary" ></i>ที่อยู่จัดส่ง : <b><span class="user_address">{{@$user_address}}</span></b></h5>
                                    </li>
                                    
                                  @if(@$sUser[0]->status_sent!=3)
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="วันที่ดำเนินการ"><i class="bx bx-calendar mr-1 text-primary"></i>วันที่ดำเนินการ : <b><span class="pay_date">{{@$sUser[0]->pay_date}}</span></b></h5>
                                    </li>

                                    <li class="list-inline-item">
                                      <h5 class="font-size-14 " data-toggle="tooltip" data-placement="top" title="ผู้ดำเนินการ"><i class="bx bx-user-check font-size-18 mr-1 text-primary"></i>ผู้ดำเนินการ : <b><span class="user_action">{{@$sUser[0]->user_action}}</span></b></h5>
                                    </li>
                                    @ENDIF 
                                                                
                                    <li class="list-inline-item ">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="สถานะ"><i class="bx bx-task mr-1 text-primary" ></i> STATUS : <span style="color:red;font-weight: bold;font-size: 16px;" class="bill_status" >{{@$sUser[0]->bill_status}}</span></h5>
                                    </li>

                                  @if(@$sUser[0]->status_sent==3)
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="วันเวลาที่จ่าย"><i class="bx bx-calendar mr-1 text-primary"></i>วันเวลาที่จ่าย : <b><span class="pay_date">{{@$sUser[0]->pay_date}}</span></b></h5>
                                    </li>

                                    <li class="list-inline-item">
                                      <h5 class="font-size-14 " data-toggle="tooltip" data-placement="top" title="ผู้จ่าย"><i class="bx bx-user-check font-size-18 mr-1 text-primary"></i>ผู้จ่าย : <b><span class="pay_user">{{@$sUser[0]->pay_user}}</span></b></h5>
                                    </li>
                                  @ENDIF 

                                  </ul>
                              </div>
                          </div>
                        </div>

          <table id="data-table-0000" class="table table-bordered dt-responsive" style="width: 100%;margin-bottom: 0%;" ></table>
          <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;margin-bottom: 0%;" ></table>
          <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;"> </table>

                       <div class=" div_datatables_003 " style="" >
                           <table id="data-table-0003" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                        </div>     

                        <div class=" div_datatables_004 " style="" >
                           <table id="data-table-0004" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                        </div>  
                
                
                        <div class="col-md-12 text-center div_btn_save_004 " style="display: none;" >
                          <br>
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave004 " >
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกการจ่ายสินค้า
                          </button>
                        </div>
                        


          </div>
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
          var packing_id = "{{$pick_pack_requisition_code_id_fk}}"; //alert(packing_id);
          var oTable0000;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable0000 = $('#data-table-0000').DataTable({
               "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                  serverSide: true,
                  scroller: false,
                  scrollCollapse: false,
                  scrollX: false,
                  ordering: false,
                  ordering: false,
                  info:     false,
                  paging:   false,
                        ajax: {
                            url: '{{ route('backend.warehouse_tb_000.datatable') }}',
                            type: "POST",
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
          var packing_id = "{{$pick_pack_requisition_code_id_fk}}";// alert(packing_id);
          var oTable0001;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable0001 = $('#data-table-0001').DataTable({
               "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                  serverSide: true,
                  scroller: false,
                  scrollCollapse: false,
                  scrollX: false,
                  ordering: false,
                  ordering: false,
                  info:     false,
                  paging:   false,
                        ajax: {
                            url: '{{ route('backend.warehouse_tb_001.datatable') }}',
                            type: "POST",
                            data:{ _token: '{{csrf_token()}}',picking_id:packing_id,id:id},
                        },
                  columns: [
                      {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> รายการสินค้า </span> ', className: 'text-left w400',render: function(d) {
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

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
// ตารางนี้เกิดจากการดึงข้อมูล FIFO 
            
            var packing_id = "{{$pick_pack_requisition_code_id_fk}}"; //alert(packing_id);
            var oTable0002;
            $(function() {
              $.fn.dataTable.ext.errMode = 'throw';
                oTable0002 = $('#data-table-0002').DataTable({
                 "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    destroy:true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    "info":     false,
                    "paging":   false,
                    ajax: {
                        url: '{{ route('backend.pick_warehouse_tb_0002_edit.datatable') }}',
                        type: "POST",
                        data: { _token : "{{ csrf_token() }}", packing_id:packing_id },
                    },
                    columns: [

                        {data: 'column_001', title :'<center> รายการครั้งที่จ่ายสินค้า </center> ', className: 'text-center '},
                        {data: 'column_002', title :'<center> รายการสินค้า </center> ', className: ''},
                        {data: 'column_003', title :'<center> ยกเลิก </center> ', className: 'text-center'},
                                             
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                          // console.log("xxxxxxx");
                          // console.log(aData['ch_amt_lot_wh']);

                      if(aData['column_004']==aData['time_pay']){
                        $("td:eq(2)", nRow).html(aData['column_003']);
                      }else{
                        $("td:eq(2)", nRow).html("");
                      }
                      // console.log(aData['status_cancel']);
                      // console.log(aData['ch_amt_remain']);
                      if(aData['status_cancel']==1){

                        $("td:eq(2)", nRow).html("<br>ยกเลิกแล้ว").css({'color':'red'});

                        for (var i = 0; i < 2; i++) {
                          // 'color':'#d9d9d9','text-decoration':'line-through','font-style':'italic'
                          $('td:eq( '+i+')', nRow).html(aData[i]).css({'text-decoration':'line-through','font-style':'italic'});
                        }

                      }

                    }
                });

              oTable0002.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
              });
           
            });
// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
  </script>




  
  <script type="text/javascript">
    $(document).ready(function() {

      $(document).on('change', '.qr_scan', function(e) {

          var v = $(this).val();
          var item_id = $(this).data('item_id');
          var invoice_code = $(this).data('invoice_code');
          var product_id_fk = $(this).data('product_id_fk');
          // alert(v+":"+invoice_code+":"+product_id_fk);
          if($(this).val()!=''){
            $(this).css({ 'background-color' : '', 'opacity' : '' });
          }

             $(".myloading").show();

             $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxScanQrcodeProduct') }} ",
                 data:{ _token: '{{csrf_token()}}',item_id:item_id,qr_code:v,invoice_code:invoice_code,product_id_fk:product_id_fk },
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
          var invoice_code = $(this).data('invoice_code');
          var product_id_fk = $(this).data('product_id_fk');
          // alert(item_id+":"+invoice_code+":"+product_id_fk);

             $(".myloading").show();
             $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxDeleteQrcodeProduct') }} ",
                 data:{ _token: '{{csrf_token()}}',item_id:item_id,invoice_code:invoice_code,product_id_fk:product_id_fk },
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
          
          $(".myloading").show();

                var packing_id = "{{$pick_pack_requisition_code_id_fk}}"; //alert(packing_id);
                // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                 $.ajax({

                      url: " {{ url('backend/ajaxSearch_requisition_db_orders') }} ", 
                      method: "post",
                      data: {
                        packing_id:packing_id,
                        "_token": "{{ csrf_token() }}", 
                      },
                      success:function(data)
                      { 
                          // console.log(data);
                          // return false;

                          if(data==0){
                            Swal.fire({
                              position: 'top-end',
                              title: '! ค้นไม่พบใบเสร็จ',
                              showConfirmButton: false,
                              timer: 2500
                            })
                            $(".myloading").hide();
                            return false;

                          }else{

                      // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
                      // ตารางนี้เกิดจากการดึงข้อมูล FIFO 
                                  $.fn.dataTable.ext.errMode = 'throw';
                                  var oTable0003;
                                  $(function() {
                                      oTable0003 = $('#data-table-0003').DataTable({
                                          processing: true,
                                          serverSide: true,
                                          scroller: false,
                                          scrollCollapse: true,
                                          scrollX: false,
                                          ordering: false,
                                          "searching": false,
                                          "info":     false,
                                          "paging":   false,
                                          destroy: true,
                                          ajax: {
                                              url: '{{ route('backend.pick_warehouse_tb_0003.datatable') }}',
                                              type: "POST",
                                              data: { _token : "{{ csrf_token() }}", packing_id:packing_id },
                                          },
                                          columns: [

                                              {data: 'column_001', title :'<center> 3 </center> ', className: 'text-center '},
                                              {data: 'column_002', title :'<center>  </center> ', className: ''},
                                              {data: 'column_003', title :'<center>  </center> ', className: 'text-center'},
                                                                   
                                          ],
                                          rowCallback: function(nRow, aData, dataIndex){

                                            $(".myloading").hide();

                                              // console.log(aData['ch_amt_remain']);
                                              // console.log(aData['ch_amt_lot_wh']);
                                              // console.log(aData['status_sent']);
                                              // console.log(aData['status_cancel_all']);

                                              // aData['status_cancel_all']==1 > มีการยกเลิก
                                              // aData['status_cancel_all']==0 > ไม่มีการยกเลิก

                                              // ถ้าจ่ายครบแล้ว aData['status_sent']==3
                                              if(aData['status_sent']==3){

                                                    // $(".div_btn_save").hide();
                                                    $(".div_btn_save_004").hide();
                                                    $(".div_datatables_003").hide();
                                                    $(".div_datatables_004").hide();

                                              }else{

                                                  // ถ้ามีการยกเลิกเมื่อไร ให้แสดงตาราง 4 กรณีอื่นๆ แสดงตาราง 3 
                                                      if(aData['status_cancel_all']==1 ){ //มีการยกเลิก

                                                            $(".div_datatables_003").hide();
                                                            $(".div_datatables_004").show();

                                                            if(aData['ch_amt_lot_wh']==0){
                                                                $(".div_btn_save_004").hide();
                                                            }else{
                                                                $(".div_btn_save_004").show();
                                                            }

                                                      }else{  // ไม่มีการยกเลิก

                                                            $(".div_datatables_003").show();
                                                            $(".div_datatables_004").hide();

                                                            // เช็ค สต๊อก ว่ามีสินค้าหรือไม่
                                                            // ถ้าไม่มีสินค้าในคลังเลย
                                                            if(aData['ch_amt_lot_wh']==0){
                                                                $(".div_btn_save_004").hide();
                                                            }else{
                                                                $(".div_btn_save_004").show();
                                                            }

                                                      }

                                                }

                                          }
                                      });
                                 
                                  });
                      // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

                      // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
                      // ตารางนี้เกิดจากการดึงข้อมูล FIFO 
                                  $.fn.dataTable.ext.errMode = 'throw';
                                  var oTable0004;
                                  $(function() {
                                      oTable0004 = $('#data-table-0004').DataTable({
                                          processing: true,
                                          serverSide: true,
                                          scroller: false,
                                          scrollCollapse: true,
                                          scrollX: false,
                                          ordering: false,
                                          "searching": false,
                                          "info":     false,
                                          "paging":   false,
                                          destroy: true,
                                          ajax: {
                                              url: '{{ route('backend.pick_warehouse_tb_0004.datatable') }}',
                                              type: "POST",
                                              data: { _token : "{{ csrf_token() }}", packing_id:packing_id },
                                          },
                                          columns: [

                                              {data: 'column_001', title :'<center> 4 </center> ', className: 'text-center '},
                                              {data: 'column_002', title :'<center>  </center> ', className: ''},
                                              {data: 'column_003', title :'<center>  </center> ', className: 'text-center'},
                                                                   
                                          ],
                                          rowCallback: function(nRow, aData, dataIndex){

                                            $(".myloading").hide();

                                              // console.log(aData['ch_amt_remain']);
                                              // console.log(aData['ch_amt_lot_wh']);
                                              // console.log(aData['status_sent']);
                                              // console.log(aData['status_cancel_some']);

                                              // ถ้าจ่ายครบแล้ว aData['status_sent']==3
                                              // if(aData['status_sent']==3){
                                              //       // $(".div_btn_save").hide();
                                              //       $(".div_btn_save_004").hide();
                                              //       $(".div_datatables_003").hide();
                                              //       $(".div_datatables_004").hide();
                                              // }else{

                                              //         if(aData['status_cancel_some']==0 ){ //ไม่มีการยกเลิก

                                              //               $(".div_btn_save_004").hide();

                                              //         }else{  //มีการยกเลิก
                                              //               // เช็ค สต๊อก ว่ามีสินค้าหรือไม่
                                              //               // ถ้าไม่มีสินค้าในคลังเลย
                                              //               if(aData['ch_amt_lot_wh']==0){
                                              //                   $(".div_btn_save_004").hide();
                                              //               }else{
                                              //                   $(".div_btn_save_004").show();
                                              //               }

                                              //         }

                                              // }

                            

                                          }
                                      });
                                 
                                  });
                      // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@                      

                          } // ปิด if(data==0){

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({


 </script>
 
    <script>

       $(document).on('click', '.btnSave004', function(e) {

                $(".myloading").show();
                var requisition_code = "{{$pick_pack_requisition_code_id_fk}}"; 
                // var packing_id = packing_id.split('');
                // alert(packing_id);
                // return false;
                  // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                 $.ajax({

                        type:'POST',
                        url: " {{ url('backend/calFifo_edit') }} ", 
                        // data:{ _token: '{{csrf_token()}}',packing_id:packing_id},
                        data: $("#frm-packing").serialize()+"&requisition_code="+requisition_code,
                        success: function(response){ // What to do if we succeed
                          // console.log(response);
                          // return false;

                            // check ดูก่อนว่า บิลนี้ ถูกเลือกไปแล้ว
                            $('#data-table-0003').DataTable().clear().draw();
                            $('#data-table-0004').DataTable().clear().draw();

                             setTimeout(function(){
                                    
                                      $.ajax({
                                           type:'POST',
                                           url: " {{ url('backend/ajaxSearch_requisition_db_orders002') }} ",
                                           data:{ _token: '{{csrf_token()}}' },
                                            success:function(data){
                                                 // console.log(data);

                                                 // return false;
                                                 // $(".myloading").hide();
                                                 if(data=="No_changed"){
                                                      $(".myloading").hide();
                                                      Swal.fire({
                                                            title: 'ยืนยัน ! บันทึกการจ่ายสินค้า ',
                                                            type: 'question',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#556ee6',
                                                            cancelButtonColor: "#f46a6a"
                                                            }).then(function (result) {
                                                                if (result.value) {
                                                                      
                                                                    $(".myloading").show();

                                                                      $.ajax({
                                                                         type:'POST',
                                                                         url: " {{ url('backend/ajaxSavePay_requisition_edit') }} ",
                                                                         data:{ _token: '{{csrf_token()}}',requisition_code:requisition_code },
                                                                          success:function(d2){
                                                                               console.log(d2);
                                                                               location.reload();
                                                                               // return false;
                                                                       
                                                                              // location.replace('{{ url("backend/pick_warehouse") }}/'+d2+'/edit');
 
                                                                             $(".myloading").hide();
                                                                          },
                                                                        error: function(jqXHR, textStatus, errorThrown) {
                                                                            $(".myloading").hide();
                                                                        }
                                                                    });

                                                                }
                                                          });

                                                 }else{

                                                      Swal.fire({
                                                        position: 'top-end',
                                                        title: '! ข้อมูลการจ่ายสินค้ามีการเปลี่ยนแปลง โปรดตรวจสอบอีกครั้ง',
                                                        showConfirmButton: false,
                                                        background: '#ffd9b3',
                                                        timer: 4000
                                                      })

                                                      // var txtSearch = $("input[name='txtSearch']").val();
                                                      // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                                                       // $.ajax({
                                                       //        url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
                                                       //        method: "post",
                                                       //        data: {
                                                       //          txtSearch:txtSearch,
                                                       //          "_token": "{{ csrf_token() }}", 
                                                       //        },
                                                       //        success:function(data)
                                                       //        { 
                                                       //          console.log(data);
                                                       //          $(".myloading").hide();
                                                       //        }
                                                       // });

                                                 } // ปิด if(data=="No_changed"){

                                              }, // ปิด success:function(data){
                                        
                                        }); // ปิด $.ajax({

                                  }, 500); // ปิด setTimeout(function(){
                                          

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({

         }); // ปิด $(document).on('click', '.btnSave004'

    </script>

   <script type="text/javascript">
     
    $(document).ready(function() {

           $(document).on('click', '.cDelete2', function(){

            var time_pay = $(this).data("time_pay");
            var packing_id = "{{$pick_pack_requisition_code_id_fk}}";// alert(packing_id);
            // alert(time_pay+":"+packing_id);
            // return false;

                    Swal.fire({
                      title: 'ยืนยัน ! ยกเลิกการจ่ายครั้งนี้',
                      // text: 'You clicked the button!',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {

                             $.ajax({
                                url: " {{ url('backend/cancel-some-requisition_001') }} ", 
                                method: "post",
                                data: {
                                  pick_pack_requisition_code_id_fk:packing_id,
                                  time_pay:time_pay,
                                  "_token": "{{ csrf_token() }}", 
                                },
                                success:function(data)
                                { 
                                  // console.log(data);
                                  // return false;
                                      Swal.fire({
                                        type: 'success',
                                        title: 'ทำการยกเลิกการจ่ายครั้งนี้เรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 2000
                                      });

                                      setTimeout(function () {
                                          // $('#data-table-0002').DataTable().clear().draw();
                                          location.reload();
                                      }, 1000);
                                }
                              })
                            



                          }
                    });

             });   

    });
   </script>


        <script>

      $(document).ready(function() {
            $(".test_clear_data").on('click',function(){
              
              location.replace( window.location.href+"?test_clear_data=test_clear_data ");
       
            });
                
      });

    </script>
   
   
    <?php 
    
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
          
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id; 
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id; 
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id; 

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");

      DB::select(" UPDATE db_stocks SET amt='100' ; ");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");
      DB::select("TRUNCATE `db_consignments`;");
      DB::select("UPDATE `db_delivery` SET `status_pick_pack`='0' ;");
      
      ?>
          <script>
          location.replace( "{{ url('backend/pick_pack') }}");
          </script>
          <?php
      }
    ?>
@endsection


