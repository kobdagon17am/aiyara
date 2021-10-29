@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

    .form-group {
        margin-bottom: 0rem  !important; 
     }

    .btn-outline-secondary {
        margin-bottom: 36% !important;
    }
</style>

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
          max-width: 600px !important; /* New width for default modal */
        }
    }

    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

</style>


@endsection

@section('content')
<div class="myloading"></div>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18  "> สินค้ารอจัดส่ง  </h4>
             <!-- <span style="font-weight: bold;padding-right: 10px;"> ใบเสร็จรอจัดเบิก (รายบิล) </span> -->
            <!-- test_clear_data -->
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
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
      // echo  @$menu_permit->can_packing_list;     
      // echo  @$menu_permit->can_payproduct;     
      // echo $can_packing_list."xxxxxxxxxxxxxxxxxxxxxxxxxxx";     
   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">



        <div class="myBorder" >

             <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="business_location_id_fk" class="col-md-3 col-form-label">Business Location : </label>
                        <div class="col-md-9">
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                  <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->business_location_id_fk))?'selected':'' }} >{{$r->txt_desc}}</option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-3 col-form-label"> สาขาที่ดำเนินการ : </label>
                            <div class="col-md-9">

                              <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  >
                                 <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                 @if(@$sBranchs)
                                  @foreach(@$sBranchs AS $r)
                                   @if($sPermission==1)
                                    @if($r->business_location_id_fk==(\Auth::user()->business_location_id_fk)) 
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @else 
                                     @if($r->business_location_id_fk==(\Auth::user()->business_location_id_fk)) 
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @endif
                                  @endforeach
                                @endif
                              </select>

                            </div>
                          </div>
                    </div>

               </div>

               <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="receipt" class="col-md-3 col-form-label"> รหัสใบเสร็จ : </label>
                    <div class="col-md-9">
                        <select id="receipt" name="receipt" class="form-control select2-templating " required >
                        <option value="">-Select-</option>

                         @if(@$receipt)
                          @foreach(@$receipt AS $r)
                            <option value="{{$r->receipt}}" >
                              {{$r->receipt}} 
                            </option>
                          @endforeach
                        @endif

                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                   <!--  <label for="" class="col-md-3 col-form-label"> รหัส Packing :  </label>
                    <div class="col-md-9">
                      <select id="" name="" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                      </select>
                    </div> -->

                    <label for="customer_id_fk" class="col-md-3 col-form-label"> รหัส-ชื่อลูกค้า : </label>
                    <div class="col-md-9">
                       <select id="customer_id_fk" name="customer_id_fk" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                      </select>
                    </div>

                  </div>
                </div>
              </div> 


            <!--  <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="customer_id_fk" class="col-md-3 col-form-label"> รหัส:ชื่อลูกค้า : </label>
                    <div class="col-md-9">
                       <select id="transfer_amount_approver" name="transfer_amount_approver" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="delivery_status" class="col-md-3 col-form-label"> สถานะ :  </label>
                    <div class="col-md-9">
                      <select id="delivery_status" name="delivery_status" class="form-control select2-templating " >
                        <option value="">-Status-</option>
                        <option value="1" > รอเบิกสินค้าจากคลัง </option>
                        <option value="2" > จัดส่งแล้ว </option>
                        <option value="3" > อยู่ระหว่างการจัดเบิก </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>  -->


              <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label"> ช่วงวันที่ออกบิล : </label>
                     <div class="col-md-9 d-flex">
                      <input id="bill_sdate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" value="" />
                      <input id="bill_edate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" value="" />
                    </div> 
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">  </label>
                    <div class="col-md-9 d-flex">
                      <a class="btn btn-primary btn-sm btnSearch01 " href="javascript: void(0);" style="font-size: 14px !important;margin-left: 0.8%;" >
                        <i class="bx bx-search align-middle "></i> SEARCH
                      </a>
                    </div>
                  </div>
                </div>
              </div>


          <form  action="{{ route('backend.delivery.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_to_packing" value="1" >

            {{ csrf_field() }}
         
                 <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;" ></table>

                 <div class=" divBtnSave " style="display: none;">
                 	<center>
                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-18  ">
                    <i class="bx bx-save font-size-18 align-middle mr-1"></i> สร้าง Packing List
                    </button>
               		</center>
                  </div>


              <div id="last_form"></div>

              </form>

 </div>

              <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการ Packing List </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">


              <?php if($can_payproduct=='1'){ ?>

                    <table id="data-table-packing" class="table table-bordered dt-responsive" style="width: 100%;"></table>

              <?php }?>

							<center>
							<a class="btn btn-primary btn-sm waves-effect font-size-18 " href="{{ url("backend/pick_pack") }}">ไปหน้า สร้างใบเบิก ></a>
							</center>

                          </div>
                        </div>
                      </div>
                   
                    </div>



            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->





<div class="modal fade" id="modalDelivery" tabindex="-1" role="dialog" aria-labelledby="modalDeliveryTitle" aria-hidden="true" data-backdrop="static" >
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeliveryTitle"><b><i class="bx bx-play"></i>ที่อยู่การจัดส่ง (กำหนดเอง) </b></h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> 
      </div>

      <div class="modal-body">


        <form  action="{{ route('backend.delivery.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
          <input name="update_delivery_custom" type="hidden" value="1">
          <input id="customers_addr_frontstore_id" name="customers_addr_frontstore_id"  type="hidden" value="">
          <input id="customer_id" name="customer_id"  type="hidden" value="">

          {{ csrf_field() }}

          <div class="col-12">

                 <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ชื่อ-นามสกุล (ผู้รับ) : </label>
                  <div class="col-md-7">
                    <input type="text" id="delivery_cusname" name="delivery_cusname" class="form-control" value="" required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ที่อยู่ : </label>
                  <div class="col-md-7">
                    <textarea id="delivery_addr" name="delivery_addr" class="form-control" rows="3"  required ></textarea>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> จังหวัด : </label>
                  <div class="col-md-7">
                    <select id="delivery_province" name="delivery_province" class="form-control select2-templating " >
                       <option value="">Select</option>
                       @if(@$sProvince)
                        @foreach(@$sProvince AS $r)
                        <option value="{{$r->id}}" >
                          {{$r->province_name}}
                        </option>
                        @endforeach
                        @endif
                    </select>

                  </div>
                </div>


                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> อำเภอ/เขต : </label>
                  <div class="col-md-7">
                    <select id="delivery_amphur" name="delivery_amphur" class="form-control select2-templating " required >
                      @if(@$CusAddrFrontstore[0]->amphur_code)
                           <option value="">Select</option>
                           @if(@$sAmphures)
                            @foreach(@$sAmphures AS $r)
                            <option value="{{$r->id}}" >
                              {{$r->amphur_name}}
                            </option>
                            @endforeach
                            @endif
                      @else
                        <option disabled selected>กรุณาเลือกจังหวัดก่อน</option>
                      @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ตำบล/แขวง : </label>
                  <div class="col-md-7">
                    <select id="delivery_tambon" name="delivery_tambon" class="form-control select2-templating " required >
                      @if(@$CusAddrFrontstore[0]->tambon_code)
                           <option value="">Select</option>
                           @if(@$sTambons)
                            @foreach(@$sTambons AS $r)
                            <option value="{{$r->id}}" >
                              {{$r->tambon_name}}
                            </option>
                            @endforeach
                            @endif
                      @else
                        <option disabled selected>กรุณาเลือกอำเภอก่อน</option>
                      @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> รหัสไปรษณีย์ : </label>
                  <div class="col-md-7">
                    <input type="text" id="delivery_zipcode" name="delivery_zipcode" class="form-control" value="" required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> เบอร์มือถือ : </label>
                  <div class="col-md-7">
                    <input type="text" id="delivery_tel" name="delivery_tel" class="form-control" value=""  required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> เบอร์บ้าน : </label>
                  <div class="col-md-7">
                    <input type="text" id="delivery_tel_home" name="delivery_tel_home" class="form-control" value=""  >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> </label>
                  <div class="col-md-7">
                    <button type="submit" class="btn btn-primary float-right "><i class="bx bx-save font-size-16 align-middle "></i> Save</button>
                    <!-- <button type="button" class="btn btn-secondary float-right " data-dismiss="modal">Close</button> -->
                  </div>
                </div>

          </div>


        </form>


      </div>


    </div>
  </div>
</div>





@endsection

@section('script')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script>

var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
  
  $.fn.dataTable.ext.errMode = 'throw';

    oTable = $('#data-table').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 10,
        // stateSave: true, // ไม่ได้ ถ้าเปิดใช้งาน จะทำให้ ค้างรายการที่เคยเลือกก่อนหน้านี้ไว้ตลอด
        ajax: {
          url: '{{ route('backend.delivery.datatable') }}',
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
                  {data: 'id2', title :'ID', className: 'text-center'},
                  {data: 'id', title :'เลือก', className: 'text-center '},
                  {data: 'shipping_price', title :'<center> </center>', className: 'text-center '},
                  {data: 'delivery_date', title :'<center>วันเวลาที่ออกบิล </center>', className: 'text-center w100 '},
                  {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center'},
                  {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-left'},
                  {data: 'billing_employee', title :'<center>พนักงานที่ออกบิล </center>', className: 'text-center'},
                  {data: 'business_location', title :'<center>Business location</center>', className: 'text-center'},
                  {data: 'orders_id_fk',   title :'ใบเสร็จ', className: 'text-center w50 ',render: function(d) {
                      return '<center>'
                      + ' <a href="javascript: void(0);" target=_blank data-id="'+d+'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
                  }},
                  {data: 'total_price', title :'<center>รวมเงิน</center>', className: 'text-center'},
                  {data: 'shipping_price',   title :'<center>ค่าขนส่ง</center>', className: 'text-center ',render: function(d) {
                        return d>0?d:'';
                  }},
                  {data: 'status_delivery',   title :'<center>สถานะ</center>', className: 'text-center ',render: function(d) {
                  	if(d=='1'){
                        return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
                  	}else{
                  		  return '-รอเบิกสินค้า-';
                  	}
                  }},
                  // {data: 'id', title :'Tools', className: 'text-center w80'}, 
              ],
              'columnDefs': [
               {
                  'targets': 0,
                  'checkboxes': {
                     'selectRow': true
                  }
               }
              ],
              'select': {
                 'style': 'multi'
              },

              rowCallback: function(nRow, aData, dataIndex){

                  // $('td:last-child', nRow).html(''
                  //         + '<a href="{{ route('backend.delivery.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                  //     ).addClass('input');

                if(aData['shipping_price'] > 0 ){ // 1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน
                      $('td:eq(2)', nRow).html(
                        '<span class=" badge badge-info font-size-14" data-toggle="tooltip" data-placement="right" title="Shipping"  >S</span>');
                 }else{
                      $("td:eq(2)", nRow).html('');
                 }
                

                 $("td:eq(1)", nRow).hide();
                 // `list_type` int(1) DEFAULT '0' COMMENT '1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน',

                 // if(aData['list_type'] == "1"){ // 1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน
                 //      $('td:eq(8)', nRow).html(''
                 //        + '<center><a href="{{ URL::to('backend/delivery/print_receipt01') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                 //      ).addClass('input');
                 // }
                 // else{ //2=db_orders จากการขายหลังบ้าน
                 //      $('td:eq(8)', nRow).html(''
                 //        + '<center><a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                 //      ).addClass('input');
                 // }

             //  	 if (aData['status_delivery'] == "1") {
          			//         $('td', nRow).css('background-color', '#ffd9b3');
          			//         $("td:eq(0)", nRow).html('');
          			//         $("td:eq(7)", nRow).html('');
          			//         $("td:eq(6)", nRow).html('');
          			//         $("td:eq(10)", nRow).html('');
          			//         var i;
             //  					for (i = 0; i < 10 ; i++) {
             //  					   $("td:eq("+i+")", nRow).prop('disabled',true); 
             //  					} 

    			      // }else{
      			    //   	$("td:eq(7)", nRow).prop('disabled',true); 
      			    //   	$("td:eq(8)", nRow).prop('disabled',true); 
      			    //   	$("td:eq(10)", nRow).prop('disabled',true); 
    			      // } 


                    // if(aData['status_pick_pack']=='1'){
                    //     $('td', nRow).css('background-color', '#ffd9b3');
                    //     var i;
                    //     for (i = 0; i < 10 ; i++) {
                    //        $("td:eq("+i+")", nRow).prop('disabled',true); 
                    //     }
                    //     $('td:last-child', nRow).html('-');
                    //     $('td:eq(8)', nRow).html(''
                    //        + '<center><i class="bx bx-printer " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></center> '
                    //      ).addClass('input');

                    // }else{
                    
                          if(sU!=''&&sD!=''){
                              $('td:last-child', nRow).html('-');
                          }else{ 

                            if (aData['status_delivery'] != "1") {

                                // $('td:last-child', nRow).html(''
                                //   + '<a href="{{ route('backend.delivery.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                  
                                // ).addClass('input');
                              }

                              // + '<a href="javascript: void(0);" data-url="{{ route('backend.delivery.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'

                          }

                    // }



              }
          });
              oTable.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
              });
          });


            $('#data-table').on( 'click', 'tr', function () {

                 $(".myloading").show();

                  $("input[name^=row_id]").remove();

                  setTimeout(function(){

                          var shipping_price = 0;
                          var total_price = 0;

                          var rows_selected = $('#data-table').DataTable().column(0).checkboxes.selected();
                          $.each(rows_selected, function(index, rowId){

                            var v = rowId.split(':');

                            $('#last_form').after(
                                 $('<input>')
                                    .attr('type', 'hidden')
                                    .attr('name', 'row_id[]')
                                    .attr('id', 'row_id'+v[0])
                                    .val(v[0])
                             );

                            total_price+=parseFloat(v[1]); 
                            shipping_price+=parseFloat(v[2]); 

                          });

                          console.log('total_price='+total_price); 
                          console.log('shipping_price='+shipping_price); 

                          // var ids = rows_selected.rows( { selected: true } ).data().pluck( 'id' ).toArray();
                          // var shipping_price = rows_selected.rows( { selected: true } ).data().pluck( 'shipping_price' ).toArray();
                          // var total_price = rows_selected.rows( { selected: true } ).data().pluck( 'total_price' ).toArray();

                          // console.log(ids); 
                          // console.log(shipping_price); 
                          // console.log(total_price); 
                          // $(".myloading").hide();
                  

            					     setTimeout(function(){
            	            		if($('.select-info').text()!=''){
            	            			var str = $('.select-info').text();
                  							var str = str.split(" ");
                  							if(parseInt(str[0])>1){

                                      if(shipping_price>0){
                                             $('.divBtnSave').show();
                                        }else{
                                          $('.divBtnSave').hide();
                                          var shipping_cost = "{{@$shipping_cost}}";
                                          if(total_price>=shipping_cost){
                                            $('.divBtnSave').show();
                                          }else{
                                            $('.divBtnSave').hide();
                                          }
                                        }
                                
                  								
                  							}else{
                  								$('.divBtnSave').hide();
                  							}
            		            	}else{
            		            		$('.divBtnSave').hide();
            		            	}
                              $(".myloading").hide();
            		            }, 500);



                  }, 500);

            } );

     

          var oTable2;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable2 = $('#data-table-packing').DataTable({
              "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                  processing: true,
                  serverSide: true,
                  scroller: true,
                  scrollCollapse: true,
                  scrollX: true,
                  ordering: false,
                  scrollY: ''+($(window).height()-370)+'px',
                  iDisplayLength: 10,
                  // stateSave: true, // ไม่ได้ ถ้าเปิดใช้งาน จะทำให้ ค้างรายการที่เคยเลือกก่อนหน้านี้ไว้ตลอด
                  ajax: {
                    url: '{{ route('backend.delivery_packing_code.datatable') }}',
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
                      {data: 'packing_code_desc', title :'<center>รหัสนำส่ง </center>', className: 'text-center'},
                      {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'customer_name',   title :'<center>ชื่อลูกค้าตามใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'addr_to_send',   title :'<center>ที่อยู่จัดส่ง</center>', className: 'text-center ',render: function(d) {
                          return d ;
                      }},
                      {data: 'status_delivery',   title :'<center>สถานะ. </center>', className: 'text-center ',render: function(d) {
  	                  	if(d=='1'){
  	                        return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
  	                  	}else{
  	                  		return '-รอเบิกสินค้าจากคลัง-';
  	                  	}
	                    }},
                      {data: 'id', title :'Tools', className: 'text-center w80'}, 
                  ],
                  rowCallback: function(nRow, aData, dataIndex){

                //   	if (aData['status_delivery'] == "1") {

        				    //     $('td', nRow).css('background-color', '#ffd9b3');
        				    //     $("td:eq(4)", nRow).html('');
        				    //     $("td:eq(6)", nRow).html('');
        				    //     var i;
            				// 		for (i = 0; i < 10 ; i++) {
            				// 		   $("td:eq("+i+")", nRow).prop('disabled',true); 
            				// 		} 
			      	      // }

                    // if(aData['status_pick_pack']=='1'){
                    //     $('td', nRow).css('background-color', '#ffd9b3');
                    //     var i;
                    //     for (i = 0; i < 10 ; i++) {
                    //        $("td:eq("+i+")", nRow).prop('disabled',true); 
                    //     }
                    //     $('td:last-child', nRow).html('-');
                        // $('td:eq(4)', nRow).html(''
                        //    + '<center><i class="bx bx-printer " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></center> '
                        //  ).addClass('input');

                    // }else{

                        if(sU!=''&&sD!=''){
                            $('td:last-child', nRow).html('-');
                        }else{ 

                        	if (aData['status_delivery'] != "1") {
                        		$('td:last-child', nRow).html(''
                                // + '<a href="{{ route('backend.delivery.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
    	                          // + '<a href="backend/delivery/" data-url="{{ route('backend.delivery_packing_code.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'

                                 + '<a href="javascript: void(0);" data-url="{{ route('backend.delivery_packing_code.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cCancel " data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'


    	                        ).addClass('input');
                    		  }
                        }
                    // }


                  }
              });

          });


</script>

  <script> 

        

          $(document).ready(function() {

          		$('input[type=checkbox]').click(function(event) {
          	
                 setTimeout(function(){
                    if($('.select-info').text()!=''){
                      var str = $('.select-info').text();
                      var str = str.split(" ");
                      if(parseInt(str[0])>1){
                        $('.divBtnSave').show();
                      }else{
                        $('.divBtnSave').hide();
                      }
                    }else{
                      $('.divBtnSave').hide();
                    }
                  }, 500);

                }); 

                $(document).on('click', '.dt-checkboxes', function(event) {

                   setTimeout(function(){
                      if($('.select-info').text()!=''){
                        var str = $('.select-info').text();
                        var str = str.split(" ");
                        if(parseInt(str[0])>1){
                          $('.divBtnSave').show();
                        }else{
                          $('.divBtnSave').hide();
                        }
                      }else{
                        $('.divBtnSave').hide();
                      }
                    }, 500);

                }); 

                
                $(document).on('click', '.btnEditAddr', function(event) {
                	event.preventDefault();
                	var id = $(this).data('id');
                  var receipt_no = $(this).data('receipt_no');
                  console.log(id+":"+receipt_no); 
                	$.ajax({
		               type:'POST',
		               url: " {{ url('backend/ajaxSelectAddrEdit') }} ", 
		               data:{ _token: '{{csrf_token()}}',id:id,receipt_no:receipt_no },
		                success:function(data){
		                     console.log(data); 
		                     // location.reload();
		                     $('#select_addr_result_edit').html(data);

		                  },
		                error: function(jqXHR, textStatus, errorThrown) { 
		                    console.log(JSON.stringify(jqXHR));
		                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
		                    $(".myloading").hide();
		                }
		            });
                });



		       $('#branch_id_search').change(function(){

		          var branch_id_fk = this.value;
		          // alert(warehouse_id_fk);

		           if(branch_id_fk != ''){
		             $.ajax({
		                   url: " {{ url('backend/ajaxGetWarehouse') }} ", 
		                  method: "post",
		                  data: {
		                    branch_id_fk:branch_id_fk,
		                    "_token": "{{ csrf_token() }}", 
		                  },
		                  success:function(data)
		                  { 
		                   if(data == ''){
		                       alert('ไม่พบข้อมูลคลัง !!.'); 
		                       $("#warehouse_id_search").val('').trigger('change'); 
		                       $('#warehouse_id_search').html('<option disabled selected >(คลัง) กรุณาเลือกสาขาก่อน</option>');
		                   }else{
		                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
		                       $.each(data,function(key,value){
		                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
		                       });
		                       $('#warehouse_id_search').html(layout);
		                   }
		                  }
		                })
		           }
		 
		      });




          });
    </script> 



      <script>
      $(document).ready(function() {


          $(document).on('click', '.cCancel', function(event) {

            var id = $(this).data('id');
         
              if (!confirm("ยืนยัน ? เพื่อยกลบ ")){
                  return false;
              }else{
              $.ajax({
                  url: " {{ url('backend/ajaxDelDelivery') }} ", 
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}", id:id,
                  },
                  success:function(data)
                  { 
                    // console.log(data);
                    // return false;
                        Swal.fire({
                          type: 'success',
                          title: 'ทำการลบรายชื่อเรียบร้อยแล้ว',
                          showConfirmButton: false,
                          timer: 2000
                        });

                        setTimeout(function () {
                          location.reload();
                        }, 1500);
                  }
                });

            }

              
            });
                
      });

    </script>



    <script type="text/javascript">


    $(document).ready(function() {

        $(document).on('click', '.btnSearch01', function(event) {
                  event.preventDefault();
                  $('#data-table').DataTable().clear();

                  $(".myloading").show();

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var receipt = $('#receipt').val();
                  // alert(receipt);
                  var customer_id_fk = $('#customer_id_fk').val();
                  var bill_sdate = $('#bill_sdate').val();
                  var bill_edate = $('#bill_edate').val();
                  // var status_sent = $('#status_sent').val();

                  // var startPayDate = $('#startPayDate').val();
                  // var endPayDate = $('#endPayDate').val();
                  // var btnSearch03 = $('#btnSearch03').val();
                  // var action_user = $('#action_user').val();

                  // console.log(business_location_id_fk);
                  // console.log(branch_id_fk);
                  // console.log(customer_id_fk);
                  // console.log(startDate);
                  // console.log(endDate);
                  // console.log(status_sent);

                  // return false;

                  if(business_location_id_fk==''){
                    $('#business_location_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                  }
                  // alert(branch_id_fk);
                  if(branch_id_fk=='' || branch_id_fk === null ){
                    $('#branch_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                  }

                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                    var sU = "{{@$sU}}"; 
                    var sD = "{{@$sD}}";
                    var oTable_001;
                    $(function() {
                      $.fn.dataTable.ext.errMode = 'throw';
                        oTable_001 = $('#data-table').DataTable({
                          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                            processing: true,
                            serverSide: true,
                            scroller: true,
                            scrollCollapse: true,
                            scrollX: true,
                            ordering: false,
                            scrollY: ''+($(window).height()-370)+'px',
                            iDisplayLength: 10,
                            destroy:true,
                            ajax: {
                                url: '{{ route('backend.delivery.datatable') }}',
                                data :{
                                    _token: '{{csrf_token()}}',
                                      business_location_id_fk:business_location_id_fk,
                                      branch_id_fk:branch_id_fk,
                                      receipt:receipt,
                                      customer_id_fk:customer_id_fk,
                                      bill_sdate:bill_sdate,
                                      bill_edate:bill_edate,
                                      // startPayDate:startPayDate,
                                      // endDate:endDate,
                                      // endPayDate:endPayDate,
                                      // status_sent:status_sent,                                 
                                      // txtSearch_001:txtSearch_001,                                  
                                      // btnSearch03:btnSearch03,                                  
                                      // action_user:action_user,                                  
                                    },
                                  method: 'POST',
                                },
                            columns: [
                                    {data: 'id', title :'ID', className: 'text-center'},
                                    {data: 'id', title :'เลือก', className: 'text-center '},
                                    {data: 'shipping_price', title :'<center> </center>', className: 'text-center '},
                                    {data: 'delivery_date', title :'<center>วันเวลาที่ออกบิล </center>', className: 'text-center w100 '},
                                    {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center'},
                                    {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-center'},
                                    {data: 'billing_employee', title :'<center>พนักงานที่ออกบิล </center>', className: 'text-center'},
                                    {data: 'business_location', title :'<center>Business location</center>', className: 'text-center'},
                                    {data: 'orders_id_fk',   title :'ใบเสร็จ', className: 'text-center w50 ',render: function(d) {
                                        return '<center>'
                                        + ' <a href="javascript: void(0);" target=_blank data-id="'+d+'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
                                    }},
                                    {data: 'total_price', title :'<center>รวมเงิน</center>', className: 'text-center'},
                                    {data: 'shipping_price',   title :'<center>ค่าขนส่ง</center>', className: 'text-center ',render: function(d) {
                                          return d>0?d:'';
                                    }},
                                    {data: 'status_delivery',   title :'<center>สถานะ</center>', className: 'text-center ',render: function(d) {
                                      if(d=='1'){
                                          return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
                                      }else{
                                          return '-รอเบิกสินค้า-';
                                      }
                                    }},
                                    // {data: 'id', title :'Tools', className: 'text-center w80'}, 
                                ],
                                'columnDefs': [
                                 {
                                    'targets': 0,
                                    'checkboxes': {
                                       'selectRow': true
                                    }
                                 }
                                ],
                                'select': {
                                   'style': 'multi'
                                },

                                rowCallback: function(nRow, aData, dataIndex){

                                    // $('td:last-child', nRow).html(''
                                    //         + '<a href="{{ route('backend.delivery.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                    //     ).addClass('input');

                                  if(aData['shipping_price'] > 0 ){ // 1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน
                                        $('td:eq(2)', nRow).html(
                                          '<span class=" badge badge-info font-size-14" data-toggle="tooltip" data-placement="right" title="Shipping"  >S</span>');
                                   }else{
                                        $("td:eq(2)", nRow).html('');
                                   }
                                  

                                   $("td:eq(1)", nRow).hide();
                                   // `list_type` int(1) DEFAULT '0' COMMENT '1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน',

                                   // if(aData['list_type'] == "1"){ // 1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน
                                   //      $('td:eq(8)', nRow).html(''
                                   //        + '<center><a href="{{ URL::to('backend/delivery/print_receipt01') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                                   //      ).addClass('input');
                                   // }
                                   // else{ //2=db_orders จากการขายหลังบ้าน
                                   //      $('td:eq(8)', nRow).html(''
                                   //        + '<center><a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                                   //      ).addClass('input');
                                   // }

                                  //  if (aData['status_delivery'] == "1") {
                                  //         $('td', nRow).css('background-color', '#ffd9b3');
                                  //         $("td:eq(0)", nRow).html('');
                                  //         $("td:eq(7)", nRow).html('');
                                  //         $("td:eq(6)", nRow).html('');
                                  //         $("td:eq(10)", nRow).html('');
                                  //         var i;
                                  //         for (i = 0; i < 10 ; i++) {
                                  //            $("td:eq("+i+")", nRow).prop('disabled',true); 
                                  //         } 

                                  // }else{
                                  //     $("td:eq(7)", nRow).prop('disabled',true); 
                                  //     $("td:eq(8)", nRow).prop('disabled',true); 
                                  //     $("td:eq(10)", nRow).prop('disabled',true); 
                                  // } 


                                  //     if(aData['status_pick_pack']=='1'){
                                  //         $('td', nRow).css('background-color', '#ffd9b3');
                                  //         var i;
                                  //         for (i = 0; i < 10 ; i++) {
                                  //            $("td:eq("+i+")", nRow).prop('disabled',true); 
                                  //         }
                                  //         $('td:last-child', nRow).html('-');
                                  //         $('td:eq(8)', nRow).html(''
                                  //            + '<center><i class="bx bx-printer " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></center> '
                                  //          ).addClass('input');

                                  //     }else{
                                      
                                            if(sU!=''&&sD!=''){
                                                $('td:last-child', nRow).html('-');
                                            }else{ 

                                              if (aData['status_delivery'] != "1") {

                                                  // $('td:last-child', nRow).html(''
                                                  //   + '<a href="{{ route('backend.delivery.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                                    
                                                  // ).addClass('input');
                                                }

                                                // + '<a href="javascript: void(0);" data-url="{{ route('backend.delivery.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'

                                            }

                                      // }



                                }
                            });
                                oTable.on( 'draw', function () {
                                  $('[data-toggle="tooltip"]').tooltip();
                                });
                            });
                   
                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                                  
                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
               

                setTimeout(function(){
                   $(".myloading").hide();
                }, 1500);

               
            });

        }); 



   $('#business_location_id_fk').change(function(){

          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ", 
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                       $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
           }
 
      });


</script>
        <script type="text/javascript">
  
   $(document).ready(function(){   

      $("#customer_id_fk").select2({
          minimumInputLength: 2,
          allowClear: true,
          placeholder: '-Select-',
          ajax: {
          url: " {{ url('backend/ajaxGetCustomerDelivery') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
            console.log(params);
           return {          
            term: params.term  || '',   // search term
            page: params.page  || 1
           };
          },
          processResults: function (data, params) {
           return {
            results: data
           };
          }
         }
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

         
        
              // $.ajax({
              //     url: " {{ url('backend/ajaxCancelOrderBackend') }} ", 
              //     method: "post",
              //     data: {
              //       "_token": "{{ csrf_token() }}", id:id,
              //     },
              //     success:function(data)
              //     { 
              //       // console.log(data);
              //       return false;
              //     }
              //   });

              
            });
                
      });


      // ที่อยู่การจัดส่ง (กำหนดเอง)
       $(document).on('click', '.class_add_address', function(event) {
              var id = $(this).data('id');
              // console.log(id);
              if(id){
                // ส่ง ajax ไปเอา orders_id_fk
                $.ajax({
                        url: " {{ url('backend/ajaxGetOrdersIDtoDeliveryAddr') }} ",
                        method: "post",
                        dataType: "json", 
                        data: {
                          id:id,
                          "_token": "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {
                            console.log(data);
                            if(data=='0'){
                              alert("Invalid ! รายการข้อมูลไม่ถูกต้อง กรุณาติดต่อ Admin");
                              return false;
                            }
                            $.each(data, function( index, value ) {
                                  $('#customers_addr_frontstore_id').val(value.frontstore_id_fk);
                                  $('#customer_id').val(value.customer_id);

                                  $('#delivery_cusname').val(value.recipient_name);
                                  $('#delivery_addr').val(value.addr_no);
                                  $('#delivery_province').val(value.province_id_fk).select2();
                                  // $('#delivery_amphur').val(value.amphur_code).select2();
                                  // $('#delivery_tambon').val(value.tambon_code).select2();
                                  $('#delivery_zipcode').val(value.zip_code);
                                  $('#delivery_tel').val(value.tel);
                                  $('#delivery_tel_home').val(value.tel_home);

                                   if(value.province_id_fk != '' && typeof value.province_id_fk !== "undefined" ){
                                         $.ajax({
                                               url: " {{ url('backend/ajaxGetAmphur') }} ",
                                              method: "post",
                                              data: {
                                                province_id:value.province_id_fk,
                                                "_token": "{{ csrf_token() }}",
                                              },
                                              success:function(data2)
                                              {
                                                // console.log(value.amphur_code);
                                                // console.log(data2);
                                                $.each(data2, function( index2, value2 ) {
                                                  if(value2.id==value.amphur_code){
                                                       $('#delivery_amphur').html('<option value='+value.id+' selected >'+value2.amphur_name+'</option>');
                                                  }
                                                  
                                                });
                                              }
                                            })
                                       }

                                      // alert(value.amphur_code);

                                     if(value.amphur_code != '' &&  typeof value.amphur_code !== "undefined" ){
                                           $.ajax({
                                                 url: " {{ url('backend/ajaxGetTambon') }} ",
                                                method: "post",
                                                data: {
                                                  amphur_id:value.amphur_code,
                                                  "_token": "{{ csrf_token() }}",
                                                },
                                                success:function(data2)
                                                {

                                                 // console.log(value.tambon_code);
                                                 // console.log(data2);

                                                 $.each(data2, function( index2, value2 ) {
                                                  if(value2.id==value.tambon_code){
                                                       $('#delivery_tambon').html('<option value='+value.id+' selected >'+value2.tambon_name+'</option>');
                                                  }
                                                  
                                                });

                                                }
                                              })
                                         }

                                 
                            });

                             $('#modalDelivery').modal('show');


                        }
                      })

                   
              }
        });




             $('#delivery_province').change(function(){

                var province_id = this.value;
                // alert(province_id);

                 if(province_id != ''){
                   $.ajax({
                         url: " {{ url('backend/ajaxGetAmphur') }} ",
                        method: "post",
                        data: {
                          province_id:province_id,
                          "_token": "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {
                         if(data == ''){
                             alert('ไม่พบข้อมูลอำเภอ !!.');
                         }else{
                             var layout = '<option value="" selected>- เลือกอำเภอ -</option>';
                             $.each(data,function(key,value){
                                layout += '<option value='+value.id+'>'+value.amphur_name+'</option>';
                             });
                             $('#delivery_amphur').html(layout);
                             $('#delivery_tambon').html('<option value="" selected >กรุณาเลือกอำเภอก่อน</option>');
                         }
                        }
                      })
                 }

            });


             $('#delivery_amphur').change(function(){

                var amphur_id = this.value;
                // alert(amphur_id);

                 if(amphur_id != ''){
                   $.ajax({
                         url: " {{ url('backend/ajaxGetTambon') }} ",
                        method: "post",
                        data: {
                          amphur_id:amphur_id,
                          "_token": "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {

                         $('#delivery_zipcode').val('');

                         if(data == ''){
                             alert('ไม่พบข้อมูลตำบล !!.');
                         }else{
                             var layout = '<option value="" selected>- เลือกตำบล -</option>';
                             $.each(data,function(key,value){
                                layout += '<option value='+value.id+'>'+value.tambon_name+'</option>';
                             });
                             $('#delivery_tambon').html(layout);
                         }
                        }
                      })
                 }

            });


             $('#delivery_tambon').change(function(){

                var tambon_id = this.value;
                // alert(tambon_id);

                 if(tambon_id != ''){
                   $.ajax({
                         url: " {{ url('backend/ajaxGetZipcode') }} ",
                        method: "post",
                        data: {
                          tambon_id:tambon_id,
                          "_token": "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {
                          //  console.log(data);
                         if(data == ''){
                             alert('ไม่พบข้อมูลรหัส ปณ. !!.');
                         }else{
                             $.each(data,function(key,value){
                                $('#delivery_zipcode').val(value.zip_code);
                             });

                         }
                        }
                      })
                 }

            });


    </script>
   

	</script>


   <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <script>
      var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
      $('#bill_sdate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
                // return today;
          }
      });

      $('#bill_edate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#bill_sdate').val();
          }
      });

      $('#bill_sdate').change(function(event) {

        if($('#bill_edate').val()>$(this).val()){
        }else{
          $('#bill_edate').val($(this).val());
        }

      });        

    </script>


        <script>

      $(document).ready(function() {
            $(".test_clear_data").on('click',function(){
              
                  if (!confirm("โปรดระวัง ยืนยัน ! เพื่อล้างข้อมูลรายการสำหรับเมนูนี้ ? ")){
                      return false;
                  }else{
                  
                      location.replace( window.location.href+"?test_clear_data=test_clear_data ");
       
                  }
              
            });
                
      });

    </script>
   
    <?php 
    if(isset($_REQUEST['test_clear_data'])){
      DB::select("TRUNCATE `db_delivery` ;");
      DB::select("TRUNCATE `db_delivery_packing` ;");
      DB::select("TRUNCATE `db_delivery_packing_code` ;");
      DB::select("TRUNCATE `db_pick_warehouse_packing_code` ;");
      DB::select("TRUNCATE db_consignments;");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");

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

      // DB::select(" UPDATE db_stocks SET amt='100' ; ");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");
      DB::select("TRUNCATE `db_consignments`;");
      DB::select("UPDATE `db_delivery` SET `status_pick_pack`='0' ;");

      ?>
          <script>
          location.replace( "{{ url('backend/delivery') }}");
          </script>
          <?php
      }
    ?>
@endsection

