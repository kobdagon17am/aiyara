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
          max-width: 700 !important; /* New width for default modal */
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
            <h4 class="mb-0 font-size-18  "> รายการสร้างใบเบิก  </h4>    
            <!-- <span style="font-weight: bold;padding-right: 10px;"> ใบเสร็จรอจัดเบิก (รายบิล + packing list) </span> -->
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
      // echo $can_payproduct;
   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


        <div class="myBorder">

          <form id="frm-example" action="{{ route('backend.pick_pack.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_to_packing" value="1" >

            {{ csrf_field() }}
         
      <!--           <div class="row">
                  <div class="col-8">

                  	<div class="form-group row">
                          <div class="col-md-12">
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> ใบเสร็จรอจัดเบิก (รายบิล + packing list) </span>
                          </div>
                        </div>
                  </div>

                </div>
 -->

               <!--    <div class="row">
                    <div class="col-12 d-flex ">

     				           <div class="col-md-2">
                          <div class="form-group row">
                            <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " >
                              <option value="">Business Location</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" >
                                  {{$r->txt_desc}}
                                </option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                        </div>

                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="receipt_no" name="receipt_no" class="form-control select2-templating " >
                            <option value="">ใบเสร็จ</option>
                            @if(@$sDelivery)
                            @foreach(@$sDelivery AS $r)
                            <option value="{{$r->id}}" >
                              {{$r->receipt}}
                            </option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="packing_no" name="packing_no" class="form-control select2-templating " >
                            <option value="">รหัสแพ็คกิ้ง</option>
                            @if(@$sPacking)
                              @foreach(@$sPacking AS $r)
                              <option value="{{@$r->id}}" > {{"P".sprintf("%05d",@$r->id)}}
                              </option>
                              @endforeach
                            @endif
                          </select>
                        </div>
                      </div>   

                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="customers_id_fk" name="customers_id_fk" class="form-control select2-templating " >
                            <option value="">รหัส:ชื่อลูกค้า</option>
                            @if(@$Customer)
                            @foreach(@$Customer AS $r)
                            <option value="{{$r->id}}"  >
                             	{{$r->user_name}} : {{$r->first_name}}{{$r->last_name}}
                            </option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
             
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 d-flex ">

                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="status_search" name="status_search" class="form-control select2-templating " >
                            <option value="" >สถานะ</option>
                            <option value="0" >รออนุมัติ</option>
                            <option value="1" >อนุมัติ</option>
                            <option value="3" >ไม่อนุมัติ</option>
                            <option value="2" >ยกเลิก</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 d-flex  ">
                         <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม"  />
                         <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด"  />
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;" >
                          <i class="bx bx-search font-size-18 align-middle mr-1"></i> &nbsp; ค้น&nbsp; &nbsp;&nbsp; &nbsp;
                          </button>
                        </div>
                      </div>
                    </div>
                  </div> -->


              <?php //if($can_packing_list=='1'){ ?>

                    <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;" ></table>

              <?php //}else{ ?>

                    <!-- <table id="data-table-no-packing" class="table table-bordered dt-responsive" style="width: 100%;" ></table> -->
              
              <?php //}?>

                 <div class=" divBtnSave " style="display: none;">
                  <center>
                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-18 ">
                    <i class="bx bx-save font-size-18 align-middle mr-1"></i> สร้างใบเบิก
                    </button>
                  </center>
                  </div>

              <div id="last_form"></div>

              </form>


                  <div class=" divBtnSave " style="">
                   <b>หมายเหตุ</b> รายการที่ ที่อยู่จัดส่ง <b> * จัดส่งพร้อมบิลอื่น </b> ให้ไปทำการรวมบิลจัดจัดส่งที่หน้า สินค้ารอจัดส่ง 
                  </div>

 </div>

              <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการใบเบิก </span>
                          </div>
                        </div>
                        <div class="form-group row">

                          <div class="col-md-12">


                          <?php if($can_payproduct=='1'){ ?>

                                <table id="data-table-packing" class="table table-bordered dt-responsive" style="width: 100%;"></table>

                          <?php }else{ ?>

                                 <!-- <table id="data-table-no-payproduct" class="table table-bordered dt-responsive" style="width: 100%;"></table> -->
                          
                          <?php } ?>

                    <center>
                        <!-- <a class="btn btn-primary btn-sm waves-effect font-size-18 " href="{{ url("backend/pick_warehouse") }}"> -->
                        <a class="btn btn-primary btn-sm waves-effect font-size-18 " href="{{ url("backend/pay_requisition_001") }}">
                          ไปหน้า จ่ายสินค้าตามใบเบิก >
                        </a>
                    </center>


                          </div>
                        </div>
                      </div>
                   
                    </div>



            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<div class="modal fade" id="modalOne" tabindex="-1" role="dialog" aria-labelledby="modalOneTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOneTitle"><b><i class="bx bx-play"></i>รายการใบเสร็จ (เพิ่มเติม)</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center>
       <div class="modal-body invoice_list " style="font-size: 16px;width: 80% !important;">

       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>




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
          <input name="update_delivery_custom_from_pick_pack" type="hidden" value="1">
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

  $(".myloading").show();

     $.fn.dataTable.ext.errMode = 'throw';

    oTable = $('#data-table').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 20,
        // stateSave: true, // ไม่ได้ ถ้าเปิดใช้งาน จะทำให้ ค้างรายการที่เคยเลือกก่อนหน้านี้ไว้ตลอด
        ajax: {
          url: '{{ route('backend.pick_pack.datatable') }}',
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
                  {data: 'id', title :'ID', className: 'text-center'},
                  {data: 'id', title :'เลือก', className: 'text-center '},
                  {data: 'status_pack', title :'<center> </center>', className: 'text-center '},
                  {data: 'delivery_date', title :'<center>วันเวลาที่ออกบิล </center>', className: 'text-center w100 '},
                  {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center '},
                  {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-center'},
                  {data: 'addr_to_send',   title :'<center>ที่อยู่จัดส่ง</center>', className: 'text-center ',render: function(d) {
                          return d ;
                      }},
                  {data: 'billing_employee', title :'<center>พนักงานที่ออกบิล </center>', className: 'text-center'},
                  {data: 'business_location', title :'<center>Business location</center>', className: 'text-center'},
                  {data: 'status_delivery',   title :'<center>สถานะการเบิก</center>', className: 'text-center ',render: function(d) {
                    if(d=='1'){
                        return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
                    }else{
                        return '-รอจัดเบิก-';
                    }
                  }},
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
                 'style': 'multi',
                 // 'selector': 'td:not(:eq(5))',

              },

              rowCallback: function(nRow, aData, dataIndex){

                 

                 if(aData['status_pack'] == "1"){ // 1=orders จาก frontend,2=db_orders จากการขายหลังบ้าน
                      $('td:eq(2)', nRow).html(

                        '<span data-toggle="tooltip" data-placement="right" title="Packing (รวมบิลจากขั้นตอนที่ 1)" class=" badge badge-danger font-size-14">P</span>');

                      // if(aData['packing_code']){
                      //       $x= aData['packing_code'].replace(/ *, */g, '<br>');
                      //   }else{
                      //     $x='';
                      //   }
                      // $('td:eq(4)', nRow).html($x).addClass('input');

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

                 //      if(aData['status_pack']==1){
                 //          $('td:eq(8)', nRow).html(''
                 //        + '<center><a href="{{ URL::to('backend/frontstore/print_receipt_packing') }}/'+aData['packing_id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                 //      ).addClass('input');
                 //      }else{
                 //          $('td:eq(8)', nRow).html(''
                 //        + '<center><a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                 //      ).addClass('input');
                 //      }
                      
                 // }

                 if (aData['status_delivery'] == "1") {
                        $('td', nRow).css('background-color', '#ffd9b3');
                        $("td:eq(0)", nRow).html('');
                        $("td:eq(7)", nRow).html('');
                        $("td:eq(6)", nRow).html('');
                        $("td:eq(10)", nRow).html('');
                        var i;
                        for (i = 0; i < 10 ; i++) {
                           $("td:eq("+i+")", nRow).prop('disabled',true); 
                        } 

                }else{
                    $("td:eq(7)", nRow).prop('disabled',true); 
                    $("td:eq(8)", nRow).prop('disabled',true); 
                    $("td:eq(10)", nRow).prop('disabled',true); 
                } 

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

                console.log(aData['delivery_location']);
                if(aData['delivery_location']==4){
                   // alert("! กรณีจัดส่งพร้อมบิลอื่น ให้ไปทำการรวมบิลที่หน้า สินค้ารอจัดส่ง ");
                   $("td:eq(0)", nRow).prop('disabled',true); 
                   $("td:eq(1)", nRow).prop('disabled',true); 
                   $("td:eq(2)", nRow).prop('disabled',true); 
                   $("td:eq(3)", nRow).prop('disabled',true); 
                   $("td:eq(4)", nRow).prop('disabled',true); 
                   $("td:eq(5)", nRow).prop('disabled',true); 
                   $("td:eq(6)", nRow).prop('disabled',true); 
                   $("td:eq(7)", nRow).prop('disabled',true); 
                   $("td:eq(8)", nRow).prop('disabled',true); 
                   $("td:eq(9)", nRow).prop('disabled',true); 
                }

                $("td:eq(6)", nRow).prop('disabled',true); 

                $(".myloading").hide();

              }
          });
           oTable.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
              });
           
          });


              setTimeout(function(){
                 $(".myloading").hide();
              }, 3000);

            $('#data-table').on( 'click', 'tr', function () {

                  $(".myloading").show();

                  $("input[name^=row_id]").remove();

                  setTimeout(function(){

                          var rows_selected = $('#data-table').DataTable().column(0).checkboxes.selected();
                          $.each(rows_selected, function(index, rowId){

                            $('#last_form').after(
                                 $('<input>')
                                    .attr('type', 'hidden')
                                    .attr('name', 'row_id[]')
                                    .attr('id', 'row_id'+rowId)
                                    .val(rowId)
                             );

                          });

                          var ids = rows_selected.rows( { selected: true } ).data().pluck( 'id' ).toArray();

                          // console.log(ids); 

                          $(".myloading").hide();

                  }, 500);


  					     setTimeout(function(){
  	            		if($('.select-info').text()!=''){
  	            // 			var str = $('.select-info').text();
        							// var str = str.split(" ");
        								$('.divBtnSave').show();
               //           $(".myloading").hide();
        				
  		            	}else{
  		              		$('.divBtnSave').hide();
                       $('input[name*=row_id').remove();
  		            	}
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
                    url: '{{ route('backend.packing_list.datatable') }}',
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
                      {data: 'packing_code_02', title :'<center>รหัสใบเบิก</center>', className: 'text-center'},
                      {data: 'customer_name', title :'<center>ชื่อลูกค้า</center>', className: 'text-center'},

                      // {data: 'customer_name',   title :'<center>ชื่อลูกค้า</center>', className: 'text-center ',render: function(d) {
                      //     if(d){
                      //       return d.replace(/ *, */g, '<br>');
                      //     }else{
                      //       return '-';
                      //     }
                      // }},


                      {data: 'receipt02', title :'<center>ใบเสร็จ </center>', className: 'text-center '},
                      // {data: 'receipt02',   title :'<center>รหัสใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                      //     if(d){
                      //       return d.replace(/ *, */g, '<br>');
                      //     }else{
                      //       return '';
                      //     }
                      // }},
               
                      // {data: 'receipt02', title :'<center>รหัสใบเสร็จ </center>', className: 'text-center'},

                      {data: 'action_user_name', title :'<center>พนักงานที่ดำเนินการ </center>', className: 'text-center'},
                      {data: 'status_delivery',   title :'<center>สถานะการเบิก</center>', className: 'text-center ',render: function(d) {
	                  	if(d=='1'){
	                        return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
	                  	}else{
	                  		return '-รอจัดเบิก-';
	                  	}
	                  }},
                      {data: 'id', title :'Tools', className: 'text-center w80'}, 
                      // {data: 'packing_code_01', title :'test', className: 'text-center '}, 
                  ],
                  rowCallback: function(nRow, aData, dataIndex){

                    console.log(aData['status_delivery']);

                //   	if (aData['status_delivery'] == "1") {

        				    //     $('td', nRow).css('background-color', '#ffd9b3');
        				    //     $("td:eq(4)", nRow).html('');
        				    //     $("td:eq(6)", nRow).html('');
        				    //     var i;
            				// 		for (i = 0; i < 10 ; i++) {
            				// 		   $("td:eq("+i+")", nRow).prop('disabled',true); 
            				// 		} 
			      	      // }


                //     if (aData['status'] == "1") {

                //         $('td', nRow).css('background-color', '#ffd9b3');
                //         var i;
                //         for (i = 0; i < 10 ; i++) {
                //            $("td:eq("+i+")", nRow).prop('disabled',true); 
                //         } 
                //         $('td:last-child', nRow).html('-');
                //     }else{

                        if(sU!=''&&sD!=''){
                            $('td:last-child', nRow).html('-');
                        }else{ 

                           // console.log(aData['id']);

                        	if (aData['status_delivery'] != "1") {
                        		$('td:last-child', nRow).html(''
                                // + '<a href="{{ route('backend.pick_pack.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
    	                          
    	                          // + '<a href="backend/pick_pack/" data-url="{{ route('backend.pick_pack_packing_code.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'



                                 + '<a href="javascript: void(0);" data-url="{{ route('backend.pick_pack_packing_code.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cCancel " data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'


    	                        ).addClass('input');
                    		}

                        }
                  }



                  // }
              });

              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTable2.draw();
              });

                oTable2.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
                });

          });



          var oTableNoPayproduct;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTableNoPayproduct = $('#data-table-no-payproduct').DataTable({
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
                      // {data: 'id', title :'ID', className: 'text-center w50'},
                      {data: 'packing_code_desc', title :'<center>รหัส Packing List </center>', className: 'text-center'},
                      
                      // {data: 'packing_code',   title :'<center>รหัสส่ง</center>', className: 'text-center ',render: function(d) {
                      //     return ;
                      // }},
                      {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center '},
                  // {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                  //         if(d){
                  //           return d.replace(/ *, */g, '<br>');
                  //         }else{
                  //           return '-';
                  //         }
                  //     }},
                      {data: 'customer_name',   title :'<center>ชื่อลูกค้า</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'addr_to_send',   title :'<center>ที่อยู่ในการจัดส่ง</center>', className: 'text-center w250 ',render: function(d) {
                            if(d==''||d=='0'){
                                return '<span style="color:red;font-size:16px;">-ไม่ระบุ กรุณาตรวจสอบ-</span>';
                            }else{
                              return d ;
                            }
                      }},
                    
                  ],
                  rowCallback: function(nRow, aData, dataIndex){

                   
                  }
              });

              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTableNoPayproduct.draw();
              });
          });

</script>

  <script> 

        

          $(document).ready(function() {

            $('input[name*=row_id').remove();

          		$('input[type=checkbox]').click(function(event) {
          	
                 setTimeout(function(){
                    if($('.select-info').text()!=''){
                      // var str = $('.select-info').text();
                      // var str = str.split(" ");
                      // if(parseInt(str[0])>1){
                        $('.divBtnSave').show();
                      // }else{
                      //   $('.divBtnSave').hide();
                      // }
                    }else{
                      $('.divBtnSave').hide();
                    }
                  }, 500);

                }); 

                $(document).on('click', '.dt-checkboxes', function(event) {

                   setTimeout(function(){
                      if($('.select-info').text()!=''){
                        // var str = $('.select-info').text();
                        // var str = str.split(" ");
                        // if(parseInt(str[0])>1){
                          $('.divBtnSave').show();
                        // }else{
                        //   $('.divBtnSave').hide();
                        // }
                      }else{
                        $('.divBtnSave').hide();
                      }
                    }, 500);

                }); 

                
                $(document).on('click', '.btnEditAddr', function(event) {
                	event.preventDefault();
                	var id = $(this).data('id');
                  var receipt_no = $(this).data('receipt_no');
                  // console.log(id+":"+receipt_no); 
                	$.ajax({
		               type:'POST',
		               url: " {{ url('backend/ajaxSelectAddrEdit') }} ", 
		               data:{ _token: '{{csrf_token()}}',id:id,receipt_no:receipt_no },
		                success:function(data){
		                     // console.log(data); 
		                     // location.reload();
		                     $('#select_addr_result_edit').html(data);
		                     $('#exampleModalCenterEdit').modal('show');

		                  },
		                error: function(jqXHR, textStatus, errorThrown) { 
		                    // console.log(JSON.stringify(jqXHR));
		                    // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
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


                
                // $(document).on('click', '.cDelete', function(event) {
                //   event.preventDefault();
                // });

                     $(document).on('click', '.cDelete', function(event) {

                            $(".myloading").show();
                            setTimeout(function(){
                              location.reload();
                            }, 1500);
                      });




          });
    </script> 

    <script type="text/javascript">

    	/*
    	var v = "<?=@$_REQUEST['select_addr']?>";
    	if(v){

		        $.ajax({
	               type:'POST',
	               url: " {{ url('backend/ajaxSelectAddr') }} ", 
	               data:{ _token: '{{csrf_token()}}',v:v },
	                success:function(data){
	                     // // console.log(data); 
	                     // location.reload();
	                     $('#select_addr_result').html(data);
	                     setTimeout(function(){
							$('#exampleModalCenter').modal('show');
						 }, 1500);
	                  },
	                error: function(jqXHR, textStatus, errorThrown) { 
	                    // console.log(JSON.stringify(jqXHR));
	                    // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
	                    $(".myloading").hide();
	                }
	            });

    	}
      */
    	
        
	</script>




    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
             format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });
        $('#endDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#startDate').val();
            }
        });

         $('#startDate').change(function(event) {
           $('#endDate').val($(this).val());
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
                  url: " {{ url('backend/ajaxDelPickpack') }} ", 
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}", id:id,
                  },
                  success:function(data)
                  { 
                    // // console.log(data);
                    // return false;
                        Swal.fire({
                          type: 'success',
                          title: 'ทำการลบรายชื่อเรียบร้อยแล้ว',
                          showConfirmButton: false,
                          timer: 2000
                        });

                        setTimeout(function () {
                          // $('#data-table').DataTable().clear().draw();
                          location.reload();
                        }, 1500);
                  }
                });

            }

              
            });




                
      });



      // ที่อยู่การจัดส่ง (กำหนดเอง)
       $(document).on('click', '.class_add_address', function(event) {
              var id = $(this).data('id');
              // console.log(id);
              if(id){
                // ส่ง ajax ไปเอา orders_id_fk
                $.ajax({
                        url: " {{ url('backend/ajaxGetOrdersIDtoDeliveryAddr02') }} ",
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


    </script>

     <script>
      $(document).ready(function() {

           $(document).on('click','.invoice_code_list',function(event){
               var t = $(this).siblings('.arr_inv').val();
               var tt = t.split(",").join("\r\n");
               $('.invoice_list').html(tt);
               $('#modalOne').modal('show');
            });
                
     });
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
