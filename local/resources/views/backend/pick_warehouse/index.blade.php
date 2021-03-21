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


</style>
@endsection

@section('content')

<div class="myloading"></div>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> จ่ายสินค้าตามใบเบิก  </h4>
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


        <div class="myBorder">

          <form id="frm-packing" action="{{ route('backend.pick_warehouse_packing_code.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_to_packing" value="1" >

            {{ csrf_field() }}
         
                <div class="row">
                  <div class="col-8">

                  	<div class="form-group row">
                          <div class="col-md-12">
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> ใบเสร็จรอจัดเบิกจากคลัง </span>
                          </div>
                        </div>
                  </div>

                </div>


                  <div class="row">
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
                  </div>



              <?php if($can_packing_list=='1'){ ?>

                    <table id="data-table-packing" class="table table-bordered dt-responsive" style="width: 100%;" ></table>

              <?php }else{ ?>

                    <!-- <table id="data-table-no-packing" class="table table-bordered dt-responsive" style="width: 100%;" ></table> -->
              
              <?php }?>

                 <div class="col-md-6 text-right divBtnSave " style="display: none;">
                    <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกรวมบิล Packing List
                    </button>
                  </div>

                 <div id="last_form"></div>

              </form>

 </div>

    <div class="myBorder" style="background-color: #ffffcc;" >
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-12">
                  <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการสินค้าที่รอหยิบออกจากคลังทั้งหมด (FIFO) </span>
                </div>
              </div>
            </div>
            <div style="">
              
              <div class="form-group row  " >
                <div class="col-md-12 ">
                  <table id="data-table-fifo" class="table table-bordered dt-responsive" style="width: 100%;">
                  </table>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

            <div class="" style="background-color: #ccffff;" >
                <div class="container">
                  
                  <div class="col-12">
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <div class="form-group row">
                          <label for="receipt" class="col-md-3 col-form-label">พิมพ์รายการใบเบิกที่เลือก:</label>
                          <div class="col-md-3">
                            <button type="submit" class="btn btn-primary  ">
                            <i class="bx bx-printer font-size-16 align-middle mr-1"></i> พิมพ์ใบเบิก
                            </button>
                          </div>
                          <label for="receipt" class="col-md-3 col-form-label">ส่งออกไฟล์ Excel (.xlsx) ให้ KERRY :</label>
                          <div class="col-md-3" style="" >
                            <input type='button' name="submit" class="btn btn-success btnExportElsx " value='&nbsp;&nbsp;&nbsp;EXPORT EXCEL&nbsp;&nbsp;&nbsp;'>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

      </div> <!-- end col -->


   <div class="myBorder" style="background-color: #ffffcc;" >
        <div class="container">
          
          <div class="col-12">
            <div class="panel panel-default">
              <div class="panel-body">
      
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

                <center><input type='button' class="btn btn-primary " value='Map Consignment Code' > 

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
        stateSave: true,
        ajax: {
          url: '{{ route('backend.pick_warehouse.datatable') }}',
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
                  {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                  {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-center'},
                  {data: 'billing_employee', title :'<center>พนักงานที่ออกบิล </center>', className: 'text-center'},
                  {data: 'business_location', title :'<center>Business location</center>', className: 'text-center'},
                  {data: 'id', title :'ใบเสร็จ', className: 'text-center '},
                  {data: 'status_delivery',   title :'<center>สถานะการเบิก</center>', className: 'text-center ',render: function(d) {
                  	if(d=='1'){
                        return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
                  	}else{
                  		  return '-รอจัดเบิก-';
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

                if(aData['status_pack'] == "1"){ // 1=orders จาก frontend,2=db_frontstore จากการขายหลังบ้าน
                      $('td:eq(2)', nRow).html(
                        '<span class="tooltip_packing badge badge-danger font-size-14">P</span>');
                 }else{
                      $("td:eq(2)", nRow).html('');
                 }

                 $("td:eq(1)", nRow).hide();
                 // `list_type` int(1) DEFAULT '0' COMMENT '1=orders จาก frontend,2=db_frontstore จากการขายหลังบ้าน',

                 if(aData['list_type'] == "1"){ // 1=orders จาก frontend,2=db_frontstore จากการขายหลังบ้าน
                      $('td:eq(8)', nRow).html(''
                        + '<center><a href="{{ URL::to('backend/delivery/print_receipt01') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                      ).addClass('input');
                 }
                 else{ //2=db_frontstore จากการขายหลังบ้าน

                      if(aData['status_pack']==1){
                          $('td:eq(8)', nRow).html(''
                        + '<center><a href="{{ URL::to('backend/frontstore/print_receipt_packing') }}/'+aData['packing_id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                      ).addClass('input');
                      }else{
                          $('td:eq(8)', nRow).html(''
                        + '<center><a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center> '
                      ).addClass('input');
                      }
                      
                 }

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
              }
          });
              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTable.draw();
              });
          });


var oTableNopacking;
$(function() {
    oTableNopacking = $('#data-table-no-packing').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 10,
        // stateSave: true,
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
                  {data: 'id', title :'ID', className: 'text-center w50 '},
                  {data: 'id', title :'ID', className: 'text-center w50 '},
                  {data: 'delivery_date', title :'<center>วันเวลา<br>ที่ออกบิล </center>', className: 'text-center'},
                  // {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
                  //     return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
                  // }},
                  {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                  {data: 'customer_name', title :'<center>ชื่อลูกค้า </center>', className: 'text-center'},
                  {data: 'billing_employee', title :'<center>พนักงานที่ออกบิล </center>', className: 'text-center'},
                  {data: 'province_name', title :'<center>สาขา </center>', className: 'text-center'},
                  // {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
                  //     return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
                  // }},
                  // {data: 'cover_sheet',   title :'<center>พิมพ์</center>', className: 'text-center ',render: function(d) {
                  //     return '<a href="backend/delivery?'+d+'">ใบประหน้า</a>';
                  // }},
                  {data: 'id',   title :'ใบจ่าหน้า<br>กล่องเบิก', className: 'text-center ',render: function(d) {
                      return '<center><a href="{{ URL::to('backend/delivery/pdf01') }}/'+d+'" target=_blank ><i class="bx bxs-file-pdf grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                  }},
                  {data: 'id',   title :'พิมพ์<br>ใบเสร็จ', className: 'text-center ',render: function(d) {
                      return '<center><a href="{{ URL::to('backend/delivery/print_receipt01') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                  }},
                  {data: 'status_delivery',   title :'<center>สถานะการเบิก</center>', className: 'text-center ',render: function(d) {
                    if(d=='1'){
                        return '<span style="color:red">อยู่ระหว่างการเบิกสินค้า</span>';
                    }else{
                        return '-รอจัดเบิก-';
                    }
                  }},
                  // {data: 'id', title :'Tools', className: 'text-center w80'}, 
              ],
              rowCallback: function(nRow, aData, dataIndex){
                  $("td:eq(1)", nRow).hide();
              }
          });
              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTableNopacking.draw();
              });
          });
            $('#data-table').on( 'click', 'tr', function () {

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
                  // stateSave: true,
                  ajax: {
                    url: '{{ route('backend.pick_pack_packing_code.datatable') }}',
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
                      {data: 'packing_code_02', title :'<center>รหัสใบเบิก </center>', className: 'text-center'},
                      // {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center'},
                      {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'customer_name',   title :'<center>ชื่อลูกค้า</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
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

                    $("td:eq(1)", nRow).hide();

                    if (aData['status_delivery'] == "1") {

                        $('td', nRow).css('background-color', '#ffd9b3');
                        $("td:eq(4)", nRow).html('');
                        $("td:eq(6)", nRow).html('');
                        var i;
                        for (i = 0; i < 10 ; i++) {
                           $("td:eq("+i+")", nRow).prop('disabled',true); 
                        } 
                    }

                        if(sU!=''&&sD!=''){
                            $('td:last-child', nRow).html('-');
                        }else{ 

                          if (aData['status_delivery'] != "1") {
                            $('td:last-child', nRow).html(''
                                + '<a href="{{ route('backend.pick_warehouse.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                            
                              ).addClass('input');
                        }

                    }



                  }
              });

              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTable2.draw();
              });
          });


            $('#data-table-packing').on( 'click', 'tr', function () {

              $(".myloading").show();

                 setTimeout(function(){
  
                      var rows_selected = oTable2.column(0).checkboxes.selected();
                        $.each(rows_selected, function(index, rowId){
                          // alert(rowId);
                          
                          $("#"+"row_id"+rowId).remove();

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

                       $.ajax({
                             type:'POST',
                             url: " {{ url('backend/pick_warehouse_fifo/fifo') }} ", 
                             data: $("#frm-packing").serialize()+"&picking_id="+ids,
                              success: function(response){ // What to do if we succeed
                                     console.log(response); 
                                     return false;

                              },
                              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                  console.log(JSON.stringify(jqXHR));
                                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              }
                          });



                      var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
                      var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
                      var sU = "{{@$sU}}"; //alert(sU);
                      var sD = "{{@$sD}}"; //alert(sD);
                      var oTablePickup;
                      $(function() {
                          oTablePickup = $('#data-table-fifo').DataTable({
                          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                              processing: true,
                              serverSide: true,
                              scroller: true,
                              scrollCollapse: true,
                              scrollX: true,
                              ordering: false,
                              destroy:true,
                              scrollY: ''+($(window).height()-370)+'px',
                              iDisplayLength: 25,
                              ajax: {
                                url: '{{ route('backend.pick_warehouse_fifo.datatable') }}',
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
                                    {data: 'id',   title :'รหัสใบเบิก', className: 'text-center',render: function(d) {
                                        return '';
                                    }},
                                    {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
                                    {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                                    {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                                    // {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
                                    {data: 'amt',
                                    defaultContent: "0",   title :'<center>จำนวนในคลัง</center>', className: 'text-center',render: function(d) {
                                      return d;
                                    }},

                                    {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
                                    {data: 'amt', title :'<center>จำนวนเบิก </center>', className: 'text-center'},
                                    // {data: 'amt_pick', title :'<center>จำนวนเบิก </center>', className: 'text-center'},
                                    // {data: 'id',   title :'จำนวนเบิก', className: 'text-center w80',render: function(d) {
                                    // return '<center><input type="text" style="width:95%"></center>';
                                    // }},
                                    {data: 'id', title :'Approved', className: 'text-center w100'},             
                              ],
                              order: [[1, 'asc']],
                              rowGroup: {
                                  startRender: null,
                                  endRender: function ( rows, group ) {
                                      var sTotal = rows
                                         .data()
                                         .pluck('amt')
                                         .reduce( function (a, b) {
                                             return a + b*1;
                                         }, 0);
                                          sTotal = $.fn.dataTable.render.number(',', '.', 0, '<span>&#3647;</span> ').display( sTotal );
                                      // sTotal = 2;
                       
                                      return $('<tr/ style=" background-color:#f2f2f2 !important;">')
                                          .append( '<td colspan="4" style="text-align:center;">Total for '+group+'</td>' )
                                          .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' )
                                          .append( '<td></td>' );
                                  },
                                  dataSrc: "product_name"
                              },

                               rowCallback: function(nRow, aData, dataIndex){

                                       $('td:last-child', nRow).html(''
                                              + '<a href="{{ route('backend.pick_warehouse.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                              
                                            ).addClass('input');

                                   
                                    }
                         
                          });

                      
                      });


      

                 $(".myloading").hide();
                  }, 1500);


            } );


          var oTableNoPayproduct;
          $(function() {
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
                  // stateSave: true,
                  ajax: {
                    url: '{{ route('backend.delivery_packing_code.datatable') }}',
                    data: function ( d ) {
                      d.Where={};
                      // d.Where['product_id_fk'] = product ;
                      oData = d;
                       $(".myloading").hide();
                    },
                    method: 'POST'
                  },
                  columns: [
                      // {data: 'id', title :'ID', className: 'text-center w50'},
                      {data: 'packing_code_desc', title :'<center>รหัสนำเบิก </center>', className: 'text-center'},
                      // {data: 'receipt', title :'<center>ใบเสร็จ </center>', className: 'text-center'},
                      
                      // {data: 'packing_code',   title :'<center>รหัสเบิก</center>', className: 'text-center ',render: function(d) {
                      //     return ;
                      // }},
                      {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'customer_name',   title :'<center>ชื่อลูกค้า</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'addr_to_send', title :'<center>ที่อยู่ในการจัดเบิก </center>', className: 'text-center'},
                    
                  ],
                  rowCallback: function(nRow, aData, dataIndex){

                   
                  }
              });

              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTableNoPayproduct.draw();
              });
          });


var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-import').DataTable({
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
                  // {data: 'id',title :'<center>Consignment No.<br>(หมายเลขพัสดุ)</center>',className: 'text-center ',render: function(d) {
                  //       return '';
                  // }},
                  // {data: 'id',title :'<center>Address<br>(ที่อยู่ผู้รับ)</center>',className: 'text-center ',render: function(d) {
                  //       return '';
                  // }},
                  // {data: 'id',title :'<center>Postcode<br>(รหัสไปรษณีย์ผู้รับ)</center>',className: 'text-center ',render: function(d) {
                  //       return '';
                  // }},
                  // {data: 'id',title :'<center>Mobile<br>(เบอร์มือถือผู้รับ)</center>',className: 'text-center ',render: function(d) {
                  //       return '';
                  // }},
              ],

              rowCallback: function(nRow, aData, dataIndex){
       
              }
            });
     
          });


</script>

<script type="text/javascript">


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
		                     $('#exampleModalCenterEdit').modal('show');

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



          });
    </script> 

    <script type="text/javascript">

    	
    	var v = "<?=@$_REQUEST['select_addr']?>";
    	if(v){

		        $.ajax({
	               type:'POST',
	               url: " {{ url('backend/ajaxSelectAddr') }} ", 
	               data:{ _token: '{{csrf_token()}}',v:v },
	                success:function(data){
	                     // console.log(data); 
	                     // location.reload();
	                     $('#select_addr_result').html(data);
	                     setTimeout(function(){
							$('#exampleModalCenter').modal('show');
						 }, 1500);
	                  },
	                error: function(jqXHR, textStatus, errorThrown) { 
	                    console.log(JSON.stringify(jqXHR));
	                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
	                    $(".myloading").hide();
	                }
	            });

    	}
    	
        
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

@endsection

