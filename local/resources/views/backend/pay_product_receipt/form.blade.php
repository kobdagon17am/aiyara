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
            <h4 class="font-size-18 test_clear_data "> จ่ายสินค้าตามใบเสร็จ  </h4>
          </div>
          
          <div class="col-4" style="padding: 5px;text-align: center;font-weight: bold;margin-left: 5%;">
            <span >
              <?php $role = DB::select("SELECT * FROM `role_group` where id=".(\Auth::user()->role_group_id_fk)." "); ?>
              <?php $branchs = DB::select("SELECT * FROM `branchs` where id=".(\Auth::user()->branch_id_fk)." "); ?>
              <?php $warehouse = DB::select("SELECT * FROM `warehouse` where id=".($branchs[0]->warehouse_id_fk)."  "); ?>
              {{ \Auth::user()->name }} / {{ "สาขา > ".$branchs[0]->b_name }}  / {{ $warehouse[0]->w_name }} </span>
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
                                  <img src="backend/images/users/user.png" class=" rounded-circle mb-1 float-left float-lg-none" alt="img" style="width:18%;height: 15%;" />
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


                       <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                       <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;" ></table>

                    <div class=" div_datatables_003 " style="display: none;" >
                       <table id="data-table-0003" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                    </div>     


                         {{-- @IF(@$sUser[0]->address_send_type==1 && @$sUser[0]->status_sent!=3) --}}
                        @IF(@$sUser[0]->status_sent!=3)
                          <div class="form-group row div_btn_save "  >
                        @ELSE 
                          <div class="form-group row div_btn_save " style="display: none;" >
                        @ENDIF 
                        <div class="col-md-12 text-center ">
                          <br>
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave " disabled="">
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

<script type="text/javascript">

 // check ว่ามีค้างจ่ายไหม ถ้ามี แสดง div_datatables_003 ถ้าไม่มีไม่ต้องแสดง

            $(document).ready(function() {
              var txtSearch = "{{@$sRow->invoice_code}}";
                   $.ajax({
                       type:'POST',
                       url: " {{ url('backend/ajaxCheckRemain_pay_product_receipt') }} ",
                       data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                        success:function(data){
                             // console.log(data);
                             if(data>0){
                                $(".div_datatables_003").show();
                             }else{
                              $(".div_datatables_003").hide();
                             }
                          },
                    });
            });


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
                        {data: 'column_002', title :'<center><span style="vertical-align: middle;"> รายการสินค้า </span></center> ', className: 'text-left w600 '},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                    }
                });

           
            });
// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@


  </script>


<script type="text/javascript">

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
// ตารางนี้เกิดจากการดึงข้อมูล FIFO 
            
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
                                             
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
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



      $(document).on('click', '.btnSave', function(e) {

            Swal.fire({
                title: 'ยืนยัน ! บันทึกการจ่ายสินค้า ',
                // text: 'You clicked the button!',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: "#f46a6a"
                }).then(function (result) {
                    if (result.value) {
                          
                        $(".myloading").show();
                         var id = "{{ @$sRow->id}}"; 
                         $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxApproveProductSent') }} ",
                             data:{ _token: '{{csrf_token()}}',id:id },
                              success:function(data){
                                   // console.log(data);
                                  location.replace('{{ url("backend/pay_product_receipt_001") }}');
                                },
                              error: function(jqXHR, textStatus, errorThrown) {
                                  $(".myloading").hide();
                              }
                          });

                    }
              });

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
                                              url: '{{ route('backend.pay_product_receipt_tb9FIFO.datatable') }}',
                                              type: "POST",
                                              data: { _token : "{{ csrf_token() }}", invoice_code:invoice_code },
                                          },
                                          columns: [

                                              {data: 'column_001', title :'<center>  </center> ', className: 'text-center '},
                                              {data: 'column_002', title :'<center>  </center> ', className: ''},
                                                                   
                                          ],
                                          rowCallback: function(nRow, aData, dataIndex){
                                            $(".myloading").hide();
                                          }
                                      });
                                 
                                  });
                      // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

                          } // ปิด if(data==0){

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({


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
      DB::select("TRUNCATE `db_pay_product_receipt_001`;");
      DB::select("TRUNCATE `db_pay_product_receipt_002`;");
      DB::select("TRUNCATE `db_pay_product_receipt_002_pay_history`;");
      DB::select("TRUNCATE `db_pick_warehouse_qrcode`;");
      ?>
          <script>
          location.replace( "{{ url('backend/pay_product_receipt') }}");
          </script>
          <?php
    }
?>
@endsection


