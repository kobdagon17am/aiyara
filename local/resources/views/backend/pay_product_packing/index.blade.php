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
        cursor: pointer;
      }
      div.divTableBody>div:nth-of-type(even):hover {
        background-color: #e6e6e6;
        cursor: pointer;
      } 


</style>
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> จ่ายสินค้าตามใบเบิก  </h4>

            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pay_product_packing_list") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>

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

   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                  <div class="myBorder">

                  <div class="col-12">
                    <div class="form-group row ">
                      <div class="col-md-12 d-flex  ">
                        <label class="col-4" >ค้น : เลขที่ใบเสร็จ / Scan QR-code : </label>
                        <div class="col-md-4">
                          <input type="text" class="form-control" id="txtSearch" name="txtSearch" style="font-size: 18px !important;color: blue;" autofocus >
                        </div>
                        <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;padding: 0.7%" >
                          <i class="bx bx-search align-middle "></i> SEARCH
                        </a>
                      </div>
                    </div>
                  </div>

                <!--     <div class="form-group row">
                      <div class="col-md-12">
                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลรอจ่ายสินค้า </span>
                      </div>
                    </div> -->
                    <div class="form-group row div_data_table ">
                      <div class="col-md-12">

                       <div class=" div_customer " >
                          <div style="background-color: #ffeecc;padding-top: 10px;">
                            <div class="row">
                              <div class="col-lg-2">
                                <div class="text-lg-center">
                                  <img src="backend/images/users/user.png" class="avatar-sm rounded-circle mb-1 float-left float-lg-none" alt="img" />
                                  <h2 class="font-size-15 text-truncate user_code " data-toggle="tooltip" data-placement="top" title="รหัสสมาชิก">A00001</h2>
                                </div>
                              </div>
                              <div class="col-lg-8" style="text-align: left;" >
                                <div>
                                  <h5 class="font-size-20 text-truncate mb-lg-2 user_name " ><b data-toggle="tooltip" data-placement="top" title="ชื่อสมาชิก" >คุณชฎาพร1 พิกุล</b></h5>
                                  <ul class="list-inline mb-0" style="text-align: left;">
                                    <li class="list-inline-item ">
                                      <h5 class="font-size-14 user_address " data-toggle="tooltip" data-placement="top" title="ที่อยู่จัดส่ง"><i class="bx bx-home mr-1 text-primary" ></i><span style=""> 54/325 90/16 ( หลังตลาดเทวราช ) 10560 </span></h5>
                                    </li>
                                    
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14 user_date_action " data-toggle="tooltip" data-placement="top" title="วันที่ดำเนินการ"><i class="bx bx-calendar mr-1 text-primary"></i> 2021-04-19 16:00:00</h5>
                                    </li>
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14 user_action" data-toggle="tooltip" data-placement="top" title="ผู้ดำเนินการ"><i class="bx bx-user-check font-size-18 mr-1 text-primary"></i> ADMIN</h5>
                                    </li>
                                    <li class="list-inline-item ">
                                      <h5 class="font-size-14 bill_status " data-toggle="tooltip" data-placement="top" title="สถานะ"><i class="bx bx-task mr-1 text-primary" ></i>STATUS :<span style="color:green;font-weight: bold;font-size: 16px;"> จัดส่งแล้ว </span></h5>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        
                       <!-- <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;" ></table> -->
                       <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;" ></table>


                      <div class="form-group row div_btn_save " style="display: none;" >
                        <div class="col-md-12 text-center ">
                          <br>
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave ">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกสร้างใบเบิก
                          </button>

                        </div>
                      </div>



                    </div>
                  </div>
                  </div>


                  <div class="myBorder">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลรอเบิก </span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-12">
                      <table id="" class="table table-bordered dt-responsive" style="width: 100%;" ></table>

                      <!-- <center><input type='button' class="btn btn-primary btnGentoExport " value='แจงลงตาราง Export ให้ KERRY' >  -->

                        <center><input type='button' class="btn btn-primary btnExportElsx " value='ส่งออกไฟล์ Excel (.xlsx) ให้ KERRY' > 

                    </div>
                  </div>
                  </div>


            <div class="myBorder"  >
              <div class="form-group row">
                <div class="col-md-12">
                  <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลรอจ่ายสินค้า (Import จาก KERRY) </span>
                </div>
              </div>
              
              <div class="col-12">
                
                <form class="form-horizontal" method="POST" action="backend/uploadFileXLSConsignments" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="form-group row">
                    
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
            <div class="myBorder">
            <div class="form-group row">
              <div class="col-md-12">
                <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลเก็บข้อมูล Consignments </span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
              <table id="data-table-map-consignments" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
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

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
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
                        url: '{{ route('backend.pay_product_receipt_send.datatable') }}',
                        type: "POST",
                        data: { txtSearch: 'xxxxxxxxxxxxxxxxx' },
                    },
                    columns: [
                        {data: 'address', title :'<center>ชื่อและที่อยู่ผู้รับ</center>', className: 'text-center w350 '},
                        {data: 'status',   title :'<center>Status</center>', className: 'text-center  ',render: function(d) {
                          if(d==0){
                           return "<span style='color:red;font-weight:bold;'>รอส่ง</span>";
                          }else{
                            return "<span style='color:green;font-weight:bold;'>จัดส่งแล้ว</span>";
                          }
                        }},
                        {data: 'sent_date', title :'วันเวลาที่จ่ายสินค้า', className: 'text-center '},
                        {data: 'approver', title :'พนักงานที่จ่ายสินค้า', className: 'text-center '},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                    }
                });
           
            });
// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@


  </script>
<script type="text/javascript">

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
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
                    ajax: {
                        url: '{{ route('backend.pay_product_receipt_send.datatable') }}',
                        type: "POST",
                        data: { txtSearch: 'xxxxxxxxxxxxxxxxx' },
                    },
                    columns: [
                        {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w100'},
                        {data: 'product_name',   title :'<span style="vertical-align: middle;"><center> รายการสินค้า </span> <span style="vertical-align: middle;float: right;padding-right:10%;"> หยิบจากที่ไหน </span>  ', className: 'text-left',render: function(d) {
                          return d;
                        }},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                    }
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
                       console.log(data);
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
                       console.log(data);
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

         location.replace('{{ url("backend/pay_product_receipt_list") }}');

        
      });


    });

  </script>



  <script>


        $(document).ready(function() {

            $(document).on('change', '#txtSearch', function(event) {
                  $(".btnSearch").trigger('click');
            });
          
            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  $(".myloading").show();
                  $('#data-table-0001').DataTable().clear();
                  $('#data-table-0002').DataTable().clear();

                  var txtSearch = $("input[name='txtSearch']").val();
                  // alert(txtSearch);
                  if(txtSearch==""){
                    $("input[name='txtSearch']").focus();
                    $(".myloading").hide();
                    return false;
                  }

                // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
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
                                    // scrollY: ''+($(window).height()-370)+'px',
                                    // iDisplayLength: 5,
                                    destroy:true,
                                    ajax: {
                                          url: '{{ route('backend.pay_product_receipt_send.datatable') }}',
                                          type: "POST",
                                          data: { txtSearch: txtSearch },
                                      },
                                    columns: [
                                        {data: 'address', title :'<center>ชื่อและที่อยู่ผู้รับ</center>', className: 'text-center w350 '},
                                        {data: 'status',   title :'<center>Status</center>', className: 'text-center  ',render: function(d) {
                                          if(d==0){
                                           return "<span style='color:red;font-weight:bold;'>รอส่ง</span>";
                                          }else{
                                            return "<span style='color:green;font-weight:bold;'>จัดส่งแล้ว</span>";
                                          }
                                        }},
                                        {data: 'sent_date', title :'วันเวลาที่จ่ายสินค้า', className: 'text-center '},
                                        {data: 'approver', title :'พนักงานที่จ่ายสินค้า', className: 'text-center '},
                                    ],
                                    rowCallback: function(nRow, aData, dataIndex){

                                    }
                                });
                           
                            });
                  // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@


                  // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

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
                                              url: '{{ route('backend.pay_product_receipt_send.datatable') }}',
                                              type: "POST",
                                              data: { txtSearch: txtSearch },
                                          },
                                          columns: [
                                              {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w100'},
                                              {data: 'product_name',   title :'<span style="vertical-align: middle;"><center> รายการสินค้า </span> <span style="vertical-align: middle;float: right;padding-right:10%;"> หยิบจากที่ไหน </span>  ', className: 'text-left',render: function(d) {
                                                return d;
                                              }},
                                          ],
                                          rowCallback: function(nRow, aData, dataIndex){
                                            $(".div_customer").show();
                                          }
                                  });
                             
                              });

                     setTimeout(function(){
                       $(".div_btn_save").show();
                       $(".myloading").hide();
                     }, 2000);

            });

        }); 
    </script>
    <script>


      $.fn.dataTable.ext.errMode = 'throw';

      var sU = "{{@$sU}}"; //alert(sU);
      var sD = "{{@$sD}}"; //alert(sD);
      var oTableEx;
      $(function() {
          oTableEx = $('#data-table-list-bill-to-kerry').DataTable({
          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
              processing: true,
              serverSide: true,
              scroller: true,
              scrollCollapse: true,
              scrollX: true,
              ordering: false,
              scrollY: ''+($(window).height()-370)+'px',
              iDisplayLength: 10,
              stateSave: true,
              ajax: {
                url: '{{ route('backend.consignments.datatable') }}',
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
                  {data: 'recipient_code', title :'<center>Recipient Code <br>(รหัสผู้รับ)</center>', className: 'text-center'},
                  {data: 'recipient_name', title :'<center>Recipient Name <br>(ชื่อผู้รับ)</center>', className: 'text-center'},
                  {data: 'address', title :'<center>Address<br>(ที่อยู่ผู้รับ)</center>', className: 'text-center'},
              ],
              rowCallback: function(nRow, aData, dataIndex){
       
              }
            });
     
          });




      $.fn.dataTable.ext.errMode = 'throw';

      var sU = "{{@$sU}}"; //alert(sU);
      var sD = "{{@$sD}}"; //alert(sD);
      var oTableIm;
      $(function() {
          oTableIm = $('#data-table-import').DataTable({
          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
              processing: true,
              serverSide: true,
              scroller: true,
              scrollCollapse: true,
              scrollX: true,
              ordering: false,
              scrollY: ''+($(window).height()-370)+'px',
              iDisplayLength: 10,
              stateSave: true,
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



      var oTableMap;
      $(function() {
          oTableMap = $('#data-table-map-consignments').DataTable({
          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
              processing: true,
              serverSide: true,
              scroller: true,
              scrollCollapse: true,
              scrollX: true,
              ordering: false,
              scrollY: ''+($(window).height()-370)+'px',
              iDisplayLength: 10,
              stateSave: true,
              ajax: {
                url: '{{ route('backend.consignments_map.datatable') }}',
                data: function ( d ) {
                  oData = d;
                },
                method: 'POST'
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
                               $('#data-table-map-consignments').DataTable().draw();
                               $(".myloading").hide();
                            },
                          error: function(jqXHR, textStatus, errorThrown) { 
                              console.log(JSON.stringify(jqXHR));
                              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              $(".myloading").hide();
                          }
                  });
                  
              });

      });
    </script>



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


