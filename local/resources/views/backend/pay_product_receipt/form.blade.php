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

  .dt-checkboxes-select-all{display: none;}

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
        margin-bottom: 5%;
      }
      .divTableHeading {
        background-color: #EEE;
        display: table-header-group;
      }
      .divTableCell, .divTableHead {
        /*border-bottom: 1px solid #f2f2f2;*/
        display: table-cell;
        padding: 3px 6px;
      }
      div.divTableBody>div:nth-of-type(odd) {
        background: #f2f2f2;
      }
      div.divTableBody>div:nth-of-type(even) {
        background: white;
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


      div.divTableBody>div:nth-of-type(odd):hover {
        background-color: #e6e6e6;
        /*cursor: pointer;*/
      }
      div.divTableBody>div:nth-of-type(even):hover {
        background-color: #e6e6e6;
        /*cursor: pointer;*/
      } 


</style>
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">

          <div class="col-6">
            <h4 class="font-size-18 "> จ่ายสินค้าตามใบเสร็จ  </h4>
          </div>
          
          <div class="col-4" style="padding: 5px;text-align: center;font-weight: bold;margin-left: 5%;">
            <span >
              <?php $role = DB::select("SELECT * FROM `role_group` where id=".(@\Auth::user()->role_group_id_fk)." "); ?>
              <?php $branchs = DB::select("SELECT * FROM `branchs` where id=".(@\Auth::user()->branch_id_fk)." "); ?>
              <?php $warehouse = DB::select("SELECT * FROM `warehouse` where id=".(@$branchs[0]->warehouse_id_fk)."  "); ?>
              {{ \Auth::user()->name }} / {{ "สาขา > ".@$branchs[0]->b_name }}  / {{ @$warehouse[0]->w_name }} </span>
            </div>

            <div class="col-2" style="margin-left: 2%;">
            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pay_product_receipt_001") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>
          </div>

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
        $can_packing_list = '1';
        $can_payproduct = '1';
        $can_approve = '1';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        // print_r($menu_permit);
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
        $can_packing_list = @$menu_permit->can_packing_list==1?'1':'0';
        $can_payproduct = @$menu_permit->can_payproduct==1?'1':'0';
        $can_approve = @$menu_permit->can_approve==1?'1':'0';

      }

      // echo $can_approve;

   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                  <div class="myBorder">

                  <div class="col-12 div_txt_search " style="display: none;">
                    <div class="form-group row ">
                      <div class="col-md-12 d-flex  ">
                        <label class="col-4" >ค้น : เลขที่ใบเสร็จ / Scan QR-code : </label>
                        <div class="col-md-4">
                          <input type="text" class="form-control" id="txtSearch" name="txtSearch" style="font-size: 18px !important;color: blue;" value="{{ @$sRow->invoice_code}}"  >
                        </div>
                        <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;padding: 0.7%" >
                          <i class="bx bx-search align-middle "></i> SEARCH
                        </a>
                      </div>
                    </div>
                  </div>

         
                    <div class="form-group row div_data_table ">
                      <div class="col-md-12">

                        <?php //print_r($sUser[0]->address_send_type); ?>
                        <?php //print_r($sUser[0]->status_sent); ?>
                        
                    {{--     @IF(@$sUser[0]->address_send_type==1 && @$sUser[0]->status_sent!=3) --}}
                        @IF(@$sUser[0]->status_sent!=3)
                          <div style="background-color:#ffeecc;padding-top: 10px;">
                        @ELSE 
                          <div style="background-color:#ffe6ff;padding-top: 10px;">
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
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="รับสินค้าที่"><i class="bx bx-home mr-1 text-primary" ></i>รับสินค้าที่ : <b><span class="user_address">{{@$sUser[0]->user_address}}</span></b></h5>
                                    </li>
                                    
                                  @if(@$sUser[0]->status_sent!=3)
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="วันที่ดำเนินการ"><i class="bx bx-calendar mr-1 text-primary"></i>วันที่ดำเนินการ : <b><span class="action_date">{{@$sUser[0]->action_date}}</span></b></h5>
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


                   <table id="data-table-0001" class="table table-bordered " style="width: 100%;" ></table>
                   <table id="data-table-0002" class="table table-bordered " style="width: 100%;" ></table>

                        <div class=" div_datatables_003 " style="display: none;" >
                           <table id="data-table-0003" class="table table-bordered " style="width: 100%;" ></table>
                        </div>     

                        <div class=" div_datatables_004 " style="display: none;" >
                           <table id="data-table-0004" class="table table-bordered " style="width: 100%;" ></table>
                        </div>  
                
                        <div class="col-md-12 text-center div_btn_save " style="display: none;" >
                          <br>
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave " >
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกการจ่ายสินค้าอีกครั้ง
                          </button>
                        </div>

                
                        <div class="col-md-12 text-center div_btn_save_004 " style="display: none;" >
                          <br>
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave004 " >
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกการจ่ายสินค้า
                          </button>
                        </div>

                        <div class="col-md-12 text-center div_btn_save_cancel " style="display: none;" >
                          <br>
                          <a type="button" href="javascript:;"  data-invoice_code="{{@$sUser[0]->invoice_code}}"  data-id="{{@$sUser[0]->id}}" class="btn btn-primary btn-sm waves-effect font-size-16 btnCancel " >
                          <i class="bx bx-lock font-size-16 align-middle mr-1"></i> ปลดล็อคเพื่อจ่ายสินค้าอีกครั้ง
                          </a>
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

<script>
    $(document).ready(function() {

$(document).on('click', '.btnCancel', function(){

 // var url = $(this).data('url');
 // var url = " {{ url('backend/cancel-pay_product_receipt') }} ";
 // console.log(url);
 var id = $(this).data("id");
 var invoice_code = $(this).data("invoice_code");
 // alert(id);

         Swal.fire({
           title: 'ยืนยันการปลดล็อค',
           // text: 'You clicked the button!',
           type: 'question',
           showCancelButton: true,
           confirmButtonColor: '#556ee6',
           cancelButtonColor: "#f46a6a"
           }).then(function (result) {
               if (result.value) {

                  $.ajax({
                     url: " {{ url('backend/cancel-pay_product_receipt_001') }} ",
                     method: "post",
                     data: {
                       id:id,
                       invoice_code:invoice_code,
                       "_token": "{{ csrf_token() }}",
                     },
                     success:function(data)
                     {
                       location.replace('{{ url("backend/pay_product_receipt") }}/'+id+'/edit');
                     }
                   })




               }
         });

  });

});
</script>

<script type="text/javascript">

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
            // var txtSearch = $("input[name='txtSearch']").val();
            var txtSearch = "{{@$sRow->invoice_code}}";
            $.fn.dataTable.ext.errMode = 'throw';
            var oTable0001;
            $(function() {
                oTable0001 = $('#data-table-0001').DataTable({
                 "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    "info":     false,
                    "paging":   false,
                    destroy:true,
                          ajax: {
                              url: '{{ route('backend.pay_product_receipt_tb7.datatable') }}',
                              type: "POST",
                              data: { txtSearch: txtSearch },
                          },
                    columns: [
                        {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w150 '},
                        {data: 'column_002', title :'<center><span style="vertical-align: middle;"> รายการสินค้า. </span></center> ', className: 'text-left '},
                        {data: 'column_003', title :'<span style="vertical-align: middle;"> พิมพ์ใบเสร็จ </span> ', className: 'text-center w150 '},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                    }
                });

           
            });
// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@


  </script>


<script type="text/javascript">

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
            
            var txtSearch = "{{@$sRow->invoice_code}}"; //console.log(txtSearch);
            $.fn.dataTable.ext.errMode = 'throw';
            var oTable0002;
            $(function() {
                oTable0002 = $('#data-table-0002').DataTable({
                 "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    "info":     false,
                    "paging":   false,
                    ajax: {
                        url: '{{ route('backend.pay_product_receipt_tb8.datatable') }}',
                        type: "POST",
                        data: { _token : "{{ csrf_token() }}", txtSearch:txtSearch },
                    },
                    columns: [

                        {data: 'column_001', title :'<center> รายการครั้งที่จ่ายสินค้า </center> ', className: 'text-center '},
                        {data: 'column_002', title :'<center> รายการสินค้า </center> ', className: ''},
                        {data: 'column_003', title :'<center> ยกเลิก </center> ', className: 'text-center'},
                                             
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                      if(aData['column_004']==aData['time_pay']){
                        $("td:eq(2)", nRow).html(aData['column_003']);
                      }else{
                        $("td:eq(2)", nRow).html("");
                      }
                      // console.log(aData['status_cancel']);
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

      $(document).on('keypress', '.in-tx', function(e) {
         // alert("x");
           if (e.which === 13) {
               var index = $('.in-tx').index(this) + 1;
               $('.in-tx').eq(index).focus();
                return false;
           }
      });

        $(document).on('keyup', '.in-tx', function(e) {

          if (this.value.length >= 10) {

                 var index = $('.in-tx').index(this) + 1;
                 $('.in-tx').eq(index).focus();
                  return false;
                }

        });

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

                var invoice_code = "{{@$sRow->invoice_code}}"; //console.log(txtSearch);
                // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                 $.ajax({

                      url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
                      method: "post",
                      data: {
                        txtSearch:invoice_code,
                        "_token": "{{ csrf_token() }}", 
                      },
                      success:function(data)
                      { 
                          console.log(data);
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
                                              url: '{{ route('backend.pay_product_receipt_tb9FIFO.datatable') }}',
                                              type: "POST",
                                              data: { _token : "{{ csrf_token() }}", invoice_code:invoice_code },
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

// 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 3=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก
// 1=รอเบิก, 2=อนุมัติแล้วรอจัดกล่อง (มีค้างจ่ายบางรายการ), 3=อนุมัติแล้วรอจัดกล่อง (ไม่มีค้างจ่าย), 4=Packing กล่องแล้ว, 5=บ.ขนส่งเข้ามารับสินค้าแล้ว, 6=ยกเลิกใบเบิก

                                              // ถ้าจ่ายครบแล้ว aData['status_sent']==3
                                              if(aData['status_sent']==3 || aData['status_sent']==4){
                                                    $(".div_btn_save").hide();
                                                    $(".div_btn_save_004").hide();
                                                    $(".div_datatables_003").hide();
                                                    $(".div_datatables_004").hide();

                                              }else{

                                                  // ถ้ามีการยกเลิกเมื่อไร ให้แสดงตาราง 4 กรณีอื่นๆ แสดงตาราง 3 
                                                      if(aData['status_cancel_all']==1 ){ //มีการยกเลิก

                                                            $(".div_datatables_003").hide();
                                                            $(".div_datatables_004").show();

                                                      }else{  // ไม่มีการยกเลิก

                                                            $(".div_datatables_003").show();
                                                            $(".div_datatables_004").hide();

                                                            // เช็ค สต๊อก ว่ามีสินค้าหรือไม่
                                                            // ถ้าไม่มีสินค้าในคลังเลย
                                                            if(aData['ch_amt_lot_wh']<=0){
                                                                $(".div_btn_save").hide();
                                                            }else{
                                                                $(".div_btn_save").show();
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
                                              url: '{{ route('backend.pay_product_receipt_tb10FIFO.datatable') }}',
                                              type: "POST",
                                              data: { _token : "{{ csrf_token() }}", invoice_code:invoice_code },
                                          },
                                          columns: [

                                              {data: 'column_001', title :'<center> 4 </center> ', className: 'text-center '},
                                              {data: 'column_002', title :'<center>  </center> ', className: ''},
                                              {data: 'column_003', title :'<center>  </center> ', className: 'text-center'},
                                                                   
                                          ],
                                          rowCallback: function(nRow, aData, dataIndex){

                                            $(".myloading").hide();

                                              // console.log(aData);
                                              // console.log(aData['ch_amt_lot_wh']);
                                              // console.log(aData['status_sent']);
                                              // console.log(aData['status_cancel_some']);

                                              // ถ้าจ่ายครบแล้ว aData['status_sent']==3
                                              if(aData['status_sent']==3){
                                                    $(".div_btn_save").hide();
                                                    $(".div_btn_save_004").hide();
                                                    $(".div_datatables_003").hide();
                                                    $(".div_datatables_004").hide();
                                              }else{

                                                      if(aData['status_cancel_some']==0 ){ //ไม่มีการยกเลิก

                                                              // น้องวุฒิคนหล่อเพิ่มมาเองงับ เช็คว่าจ่ายสินค้าครบยัง
                                                              if(aData['status_sent']==2){
                                                                // $(".div_btn_save_cancel").show();
                                                                $(".div_btn_save_004").hide();
                                                              }else{
                                                                $(".div_btn_save_004").hide();
                                                                $(".div_btn_save_cancel").hide();
                                                              }
                                                          
                                                      }else{  //มีการยกเลิก
                                                            // เช็ค สต๊อก ว่ามีสินค้าหรือไม่
                                                            // ถ้าไม่มีสินค้าในคลังเลย
                                                            // console.log('ch_amt_lot_wh : '+aData['ch_amt_lot_wh']);
                                                            // console.log('status_sent : '+aData['status_sent']);
                                                            // console.log('status_cancel_some : '+aData['status_cancel_some']);
                                                            if(aData['ch_amt_lot_wh']==0){
                                                                $(".div_btn_save_004").hide();
                                                                $(".div_btn_save_cancel").hide();
                                                            }else{
                                                              $(".div_btn_save_cancel").hide();
                                                              if(aData['status_sent']!=4){
                                                                $(".div_btn_save_004").show();
                                                              }
                                                               
                                                            }

                                                      }

                                              }

                            

                                          }
                                      });
                                 
                                  });
                      // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@                      

                          } // ปิด if(data==0){

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({


 </script>
 
    <script>

       $(document).on('click', '.btnSave', function(e) {

                $(".myloading").show();
                var invoice_code = "{{@$sRow->invoice_code}}";
                // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                 $.ajax({

                      url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
                      method: "post",
                      data: {
                        txtSearch:invoice_code,
                        "_token": "{{ csrf_token() }}", 
                      },
                      success:function(data)
                      { 
                          // console.log(data);
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


                            // check ดูก่อนว่า บิลนี้ ถูกเลือกไปแล้ว
                            $('#data-table-0003').DataTable().clear().draw();

                             setTimeout(function(){
                                    
                                      $.ajax({
                                           type:'POST',
                                           url: " {{ url('backend/ajaxSearch_bill_db_orders002') }} ",
                                           data:{ _token: '{{csrf_token()}}' },
                                            success:function(data){
                                                 // console.log(data);

                                                 // return false;
                                                 // $(".myloading").hide();
                                                 if(data=="No_changed"){
                                                      $(".myloading").hide();
                                                      Swal.fire({
                                                            title: 'ยืนยัน ! บันทึกการจ่ายสินค้าอีกครั้ง ',
                                                            type: 'question',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#556ee6',
                                                            cancelButtonColor: "#f46a6a"
                                                            }).then(function (result) {
                                                                if (result.value) {
                                                                      
                                                                    $(".myloading").show();

                                                                     var txtSearch = "{{@$sRow->invoice_code}}";

                                                                      $.ajax({
                                                                         type:'POST',
                                                                         url: " {{ url('backend/ajaxSavePay_product_receipt') }} ",
                                                                         data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                                                                          success:function(d2){
                                                                               console.log(d2);
                                                                               // return false;
                                                                       
                                                                          location.replace('{{ url("backend/pay_product_receipt") }}/'+d2+'/edit');

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

                                                      var txtSearch = $("input[name='txtSearch']").val();
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
                                          

                          } // ปิด if(data==0){

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({

         }); // ปิด $(document).on('click', '.btnSave'

    </script>


    <script>

       $(document).on('click', '.btnSave004', function(e) {

                $(".myloading").show();
                var invoice_code = "{{@$sRow->invoice_code}}";
                // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                 $.ajax({

                      url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
                      method: "post",
                      data: {
                        txtSearch:invoice_code,
                        "_token": "{{ csrf_token() }}", 
                      },
                      success:function(data)
                      { 
                          // console.log(data);
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


                            // check ดูก่อนว่า บิลนี้ ถูกเลือกไปแล้ว
                            $('#data-table-0004').DataTable().clear().draw();

                             setTimeout(function(){
                                    
                                      $.ajax({
                                           type:'POST',
                                           url: " {{ url('backend/ajaxSearch_bill_db_orders002') }} ",
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

                                                                     var txtSearch = "{{@$sRow->invoice_code}}";

                                                                      $.ajax({
                                                                         type:'POST',
                                                                         url: " {{ url('backend/ajaxSavePay_product_receipt') }} ",
                                                                         data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                                                                          success:function(d2){
                                                                               console.log(d2);
                                                                               // return false;
                                                                       
                                                                          location.replace('{{ url("backend/pay_product_receipt") }}/'+d2+'/edit');

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

                                                      var txtSearch = $("input[name='txtSearch']").val();
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
                                          

                          } // ปิด if(data==0){

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({

         }); // ปิด $(document).on('click', '.btnSave'

    </script>

   <script type="text/javascript">
     
    $(document).ready(function() {

           $(document).on('click', '.cDelete2', function(){

            var time_pay = $(this).data("time_pay");
            var invoice_code = "{{@$sRow->invoice_code}}";
            // alert(time_pay+":"+invoice_code);
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
                                url: " {{ url('backend/cancel-some-pay_product_receipt_001') }} ", 
                                method: "post",
                                data: {
                                  invoice_code:invoice_code,
                                  time_pay:time_pay,
                                  "_token": "{{ csrf_token() }}", 
                                },
                                success:function(data)
                                { 
                                  // console.log(data);
                                      Swal.fire({
                                        type: 'success',
                                        title: 'ทำการยกเลิกการจ่ายครั้งนี้เรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 2000
                                      });

                                      setTimeout(function () {
                                          // $('#data-table-0002').DataTable().clear().draw();

                                          if(time_pay==1){

                                            location.replace('{{ url("backend/pay_product_receipt_001") }}');

                                          }else{
                                            location.reload();
                                          }
                                          
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

          $(document).on('click', '.print02', function(event) {

              event.preventDefault();
              $(".myloading").show();

              var id = $(this).data('id');
              // console.log(id);
              setTimeout(function(){
                 window.open("{{ url('backend/frontstore/print_receipt_022') }}"+"/"+id);
                 $(".myloading").hide();
              }, 500);
              
            });
                
      });

    </script>

@endsection


