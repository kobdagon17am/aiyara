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
            <h4 class="font-size-18  "> จ่ายสินค้าตามใบเสร็จ  </h4>
            <!-- test_clear_data -->
          </div>
          
           <div class="col-4" style="padding: 5px;text-align: center;font-weight: bold;margin-left: 5%;">
            <span >
              <?php $role = DB::select("SELECT * FROM `role_group` where id=".(\Auth::user()->role_group_id_fk)." "); ?>
              <?php $branchs = DB::select("SELECT * FROM `branchs` where id=".(\Auth::user()->branch_id_fk)." "); ?>
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
                       <!--  <label class="col-4  " ><span class="a_label_search">ค้น : เลขที่ใบเสร็จ </span>/ <span class="b_label_search">Scan QR-code :</span> </label> -->
                        <label class="col-4  " ><span class="a_label_search">ค้น : เลขที่ใบเสร็จ </span>  <span class="b_label_search"></span> </label>

                        <div class="col-md-4">
                          <input type="text" class="form-control" id="txtSearch" name="txtSearch" style="font-size: 18px !important;color: blue;" autofocus autocomplete="on" >  
                        </div>

                       <!--  <div class="col-md-2">
                         <span style="color:red;font-size: 18px;">*** อยู่ระหว่างปรับปรุง </span>
                        </div>
 -->
                        <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;padding: 0.7%;display: none;" >
                          <i class="bx bx-search align-middle "></i> SEARCH
                        </a>
                        <input type="hidden" id="id">
                      </div>
                    </div>
                  </div>

      
                    <div class="form-group row ">
                      <div class="col-md-12">
                        
                        <div class=" div_customer " style="display: none;" >
                          <div class=" div_customer " style="padding-top: 10px;">
                            <div class="row">
                              <div class="col-lg-2">
                                <div class="text-lg-center">
                                  <img src="backend/images/users/user.png" class=" rounded-circle mb-1 float-left float-lg-none" alt="img" style="width:18%;height: 15%;" />
                                  <h2 class="font-size-15 text-truncate user_code " data-toggle="tooltip" data-placement="top" title="รหัสสมาชิก"></h2>
                                </div>
                              </div>
                              <div class="col-lg-10" style="text-align: left;" >
                                  <h5 class="font-size-20 text-truncate mb-lg-2  " ><b data-toggle="tooltip" data-placement="top" title="ชื่อสมาชิก" class="user_name" ></b></h5>
                                  <ul class="list-inline mb-0" style="text-align: left;">

                                    <li class="list-inline-item ">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="รับสินค้าที่"><i class="bx bx-home mr-1 text-primary" ></i>รับสินค้าที่ : <b><span class="user_address"></span></b></h5>
                                    </li>
                                    
                                  <span id="operate">
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="วันที่ดำเนินการ"><i class="bx bx-calendar mr-1 text-primary"></i>วันที่ดำเนินการ : <b><span class="action_date"></span></b></h5>
                                    </li>

                                    <li class="list-inline-item">
                                      <h5 class="font-size-14 " data-toggle="tooltip" data-placement="top" title="ผู้ดำเนินการ"><i class="bx bx-user-check font-size-18 mr-1 text-primary"></i>ผู้ดำเนินการ : <b><span class="user_action"></span></b></h5>
                                    </li>
                                  </span> 
                                                                
                                    <li class="list-inline-item ">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="สถานะ"><i class="bx bx-task mr-1 text-primary" ></i> STATUS : <span style="color:red;font-weight: bold;font-size: 16px;" class="bill_status" >  </span></h5>
                                    </li>

                                   <span id="pay" style="display: none;">
                                    <li class="list-inline-item">
                                      <h5 class="font-size-14  " data-toggle="tooltip" data-placement="top" title="วันเวลาที่จ่าย"><i class="bx bx-calendar mr-1 text-primary"></i>วันเวลาที่จ่าย : <b><span class="pay_date"></span></b></h5>
                                    </li>

                                    <li class="list-inline-item">
                                      <h5 class="font-size-14 " data-toggle="tooltip" data-placement="top" title="ผู้จ่าย"><i class="bx bx-user-check font-size-18 mr-1 text-primary"></i>ผู้จ่าย : <b><span class="pay_user"></span></b></h5>
                                    </li>
                                  </span>  

                                  </ul>
                              </div>
                            </div>
                          </div>
                        </div>

                    <div class="div_datatables " style="display: none;" >
                       <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                       <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                    </div>

                      <div class="form-group row div_btn_save " style="display: none;" >
                        <div class="col-md-12 text-center ">
                          <br>
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave ">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกการจ่ายสินค้า
                          </button>

                        </div>
                      </div>



               <!--       <div class="form-group row div_btn_save_02_desc " style="text-align: center;display: none;">
                        <div class="col-md-12">หมายเหตุ
                          <span style="font-weight: bold;padding-right: 10px;color: red;"><i class="bx bx-play"></i> เนื่องจาก สินค้าบางรายการไม่มีในคลัง ระบบจะไม่ให้ บันทึกใบเบิกได้ กรุณาดำเนินการ นำสินค้าเข้าคลังให้ครบก่อน  </span>
                        </div>
                      </div>
 -->
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


        $(document).ready(function() {

           $('#data-table-0001').hide();
           $('#data-table-0002').hide();
            
            // $(document).on('click change', '#txtSearch', function(event) {
            $(document).on('change', '#txtSearch', function(event) {
              $(".myloading").show();
              
              var txtSearch = $("input[name='txtSearch']").val();

               $.ajax({
	                  url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
	                  method: "post",
	                  data: {
	                    txtSearch:txtSearch,
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
	                    	// $(".myloading").hide();
	                    	// return false;
                           setTimeout(function(){
                                location.reload();
                           }, 1500);

	                    }else{


	                    	// check ดูก่อนว่า บิลนี้ ถูกเลือกไปแล้ว
						              $.ajax({
						                 type:'POST',
						                 url: " {{ url('backend/ajaxCHECKPay_product_receipt') }} ",
						                 data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
						                  success:function(data){
						                       // console.log(data);
						                       if(data>0){
						                       	  location.replace('{{ url("backend/pay_product_receipt") }}/'+data+'/edit');
						                       }else{
						                       	  
												              $(".btnSearch").trigger('click');

						                       }
						                    },
						                  error: function(jqXHR, textStatus, errorThrown) {
						                      $(".myloading").hide();
						                  }
						              });

	                          // $('#data-table-0001').hide();
	                          // $('#data-table-0002').hide();
	                     }

	                  }
	                });


            });
          
            $(document).on('click', '.btnSearch', function(event) {

                  event.preventDefault();

                  $('#data-table-0001').hide();
                  $('#data-table-0002').hide();
                  // $(".div_btn_save").hide();

                  $(".div_customer").hide();
                  $(".div_customer").css('background-color','white');

                  $(".myloading").show();

                  var txtSearch = $("input[name='txtSearch']").val();
                  // alert(txtSearch);
                  if(txtSearch==""){
                    $("input[name='txtSearch']").focus();
                    $(".myloading").hide();
                    return false;
                  }

                  $(".div_datatables").show();
     
                  // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
                            var txtSearch = $("input[name='txtSearch']").val();
                            $.fn.dataTable.ext.errMode = 'throw';
                            var oTable0001;
                            $(function() {
                                oTable0001 = $('#data-table-0001').DataTable({
                                 "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    ordering: false,
                                    "info":   false,
                                    "paging": false,
                                    destroy:true,
                                          ajax: {
                                              url: '{{ route('backend.pay_product_receipt_tb4.datatable') }}',
                                              type: "POST",
                                              data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                                          },
                                    columns: [
                                        {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w150'},
                                        {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> รายการสินค้าตามใบเสร็จ </span> ', className: 'text-left',render: function(d) {
                                          return d;
                                        }},
                                    ],
                                    rowCallback: function(nRow, aData, dataIndex){
                                    }
                                });
                           
                            });
                    // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
				            
				            $.fn.dataTable.ext.errMode = 'throw';
				            var oTable0002;
				            $(function() {
				                oTable0002 = $('#data-table-0002').DataTable({
				                 "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
				                    processing: true,
				                    serverSide: true,
				                    scroller: true,
				                    // scrollCollapse: true,
				                    // scrollX: true,
				                    ordering: false,
				                    "info":     false,
				                    "paging":   false,
				                     destroy:true,
				                    ajax: {
				                        url: '{{ route('backend.pay_product_receipt_tb5.datatable') }}',
				                        type: "POST",
				                        data: { _token : "{{ csrf_token() }}", invoice_code:txtSearch },
				                    },
				                    columns: [

				                        {data: 'column_001', title :'<center> รายการครั้งที่จ่ายสินค้า </center> ', className: 'text-center w60'},
				                        {data: 'column_002', title :'<center> รายการสินค้า </center> ', className: ''},
				                                             
				                    ],
				                    rowCallback: function(nRow, aData, dataIndex){

                                  $(".myloading").hide();
                                  console.log(aData['ch_amt_lot_wh']);
                                  setTimeout(function(){
                                      if(aData['ch_amt_lot_wh']==0){
                                        $(".div_btn_save").hide();
                                      }else{
                                          $(".div_btn_save").show();

                                             console.log(aData['check_product_instock']);

                                            // if(aData['check_product_instock']=="N"){
                                            //   $(".div_btn_save").hide();
                                            //   $(".div_btn_save_02_desc").show();
                                            // }else{
                                            //   $(".div_btn_save").show();
                                            //   $(".div_btn_save_02_desc").hide();

                                            // }


                                      }
                                  }, 1500);


                               

                            }
				                });
				           
				            });
                    // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
                     setTimeout(function(){
                       
                        var count = $('#data-table-0001').dataTable().fnSettings().aoData.length;
                        // alert(count);
                        if (count == 0)
                        {
                 
                           $(".div_customer").hide();
                           // $(".myloading").hide();
                           setTimeout(function(){
                              $('#data-table-0001').show();
                              $(".myloading").hide();
                            }, 500);

                        }else{

                          var txtSearch = $("#txtSearch").val(); //alert(txtSearch);

                          $.ajax({
                               type:'POST',
                               url: " {{ url('backend/ajaxGetCusToPayReceiptForSearch') }} ",
                               data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                                success:function(data){
                                     // console.log(data);
                                     $.each(data,function(key,value){
                                        $("#id").val(value.id);
                                        $(".user_code").html(value.user_code);
                                        $(".user_name").html(value.user_name);
                                        if(value.address_send_type=="1"){
                                          $(".user_address").html("มารับด้วยตนเองที่สาขา");
                                        }else{
                                          if(value.address_send_type==""){
                                            $(".user_address").html("-ไม่ระบุ-");
                                          }else{
                                            $(".user_address").html(value.user_address);
                                          }
                                        }
                                        
                                        $(".user_action").html(value.user_action);
                                        $(".action_date").html(value.bill_date);
                                        // $(".bill_status").html(value.bill_status+value.sum_amt_lot);
                                        $(".bill_status").html(value.bill_status);
                                        $(".pay_user").html(value.pay_user);
                                        $(".pay_date").html(value.pay_date);

                                        if(value.status_sent==3){
                                          $("#pay").show();
                                          $("#operate").hide();
                                        }else{
                                          $("#operate").show();
                                          $("#pay").hide();
                                        }
                                    
                                        if(value.bill_status!=3){
                                           $(".div_customer").css('background-color','#ffeecc');
                                         }else{
                                           $(".div_customer").css('background-color','#ffe6ff');
                                         }

                                     });

                                      $('#data-table-0001').show();
                                  },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $(".myloading").hide();
                                }
                            });

                            $(".div_customer").show();

                            // setTimeout(function(){
                              $(".myloading").hide();
                            // }, 1500);

                        }
                      
                     }, 1000);


                      setTimeout(function(){
                        $('#data-table-0002').DataTable().clear().draw();
                      }, 1500);

                      setTimeout(function(){
                        $('#data-table-0002').show()
                      }, 2000);


            });


        }); 
    </script>

    <script>

       $(document).on('click', '.btnSave', function(e) {

                $(".myloading").show();
                var id = $("#id").val();  
                var txtSearch = $("input[name='txtSearch']").val();
                // alert(id);
                // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                 $.ajax({

                      url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
                      method: "post",
                      data: {
                        txtSearch:txtSearch,
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
                            $('#data-table-0002').DataTable().clear().draw();

                             setTimeout(function(){
                                    
                                      $.ajax({
                                           type:'POST',
                                           url: " {{ url('backend/ajaxSearch_bill_db_orders002') }} ",
                                           data:{ _token: '{{csrf_token()}}' },
                                            success:function(data){
                                                 console.log(data);
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

                                                                     var txtSearch = $("input[name='txtSearch']").val();

                                                                      $.ajax({
                                                                         type:'POST',
                                                                         url: " {{ url('backend/ajaxSavePay_product_receipt') }} ",
                                                                         data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                                                                          success:function(d2){
                                                                               console.log(d2);
                                                                       
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
                                                       $.ajax({
                                                              url: " {{ url('backend/ajaxSearch_bill_db_orders') }} ", 
                                                              method: "post",
                                                              data: {
                                                                txtSearch:txtSearch,
                                                                "_token": "{{ csrf_token() }}", 
                                                              },
                                                              success:function(data)
                                                              { 
                                                                // console.log(data);
                                                                $(".myloading").hide();
                                                              }
                                                       });

                                                 } // ปิด if(data=="No_changed"){

                                              }, // ปิด success:function(data){
                                        
                                        }); // ปิด $.ajax({

                                  }, 500); // ปิด setTimeout(function(){
                                          

                          } // ปิด if(data==0){

                      } // ปิด success:function(data)
                
                }); // ปิด $.ajax({

         }); // ปิด $(document).on('click', '.btnSave'

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

      // $(document).ready(function() {
      //   $(".a_label_search").on('click',function(){
      //       $("#txtSearch").focus();
      //       $("#txtSearch").val("P1210500002");
      //   });
      //   $(".b_label_search").on('click',function(){
      //       $("#txtSearch").focus();
      //       $("#txtSearch").val("P1210500001");
      //   });        
      // });




</script>


    <script>

      $(document).ready(function() {
            $(".test_clear_data").on('click',function(){
              
                  if (!confirm("โปรดระวัง ยืนยัน ! เพื่อล้างข้อมูลรายการสั่งซื้อทั้งหมดเพื่อเริ่มต้นคีย์ใหม่ ? ")){
                      return false;
                  }else{
                  
                      location.replace( window.location.href+"?test_clear_data=test_clear_data ");
                  }
   
       
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
      $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id; 
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id; 
      $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id; 
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id; 

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check002 ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare002 ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");


      ?>
          <script>
          location.replace( "{{ url('backend/pay_product_receipt') }}");
          </script>
          <?php
      }
    ?>
@endsection



