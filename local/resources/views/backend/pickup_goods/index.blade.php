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

	.tooltip_packing {
	  position: relative ;
	}
	.tooltip_packing:hover::after {
	  content: "Packing List" ;
	  position: absolute ;
	  top: 1.1em ;
	  left: -4em ;
	  min-width: 80px ;
	  border: 1px #808080 solid ;
	  padding: 8px ;
	  color: black ;
	  background-color: #cfc ;
	  z-index: 1 ;
	} 
	.tooltip_invoice {
	  position: relative ;
	}
	.tooltip_invoice:hover::after {
	  content: "Invoice" ;
	  position: absolute ;
	  top: 1.1em ;
	  left: -4em ;
	  min-width: 80px ;
	  border: 1px #808080 solid ;
	  padding: 8px ;
	  color: black ;
	  background-color: #cfc ;
	  z-index: 1 ;
	}

  .dt-checkboxes-select-all{display: none;}

</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> รายการเบิกสินค้า  </h4>
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
        $can_approve = 1;
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
        $can_approve = @$menu_permit->can_approve==1?1:0;
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
      // print_r($menu_permit);
   ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                  <div class="myBorder">

                    <form id="frm-example" action="{{ route('backend.pickup_goods.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                      <input type="hidden" name="save_to_set" value="1" >
                      {{ csrf_field() }}
                      
                      <div class="row">
                        <div class="col-8">
                          <div class="form-group row">
                            <div class="col-md-12">
                              <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการรอจัดเบิก (รายบิล) </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                      </table>
                      <div class="col-md-6 text-right divBtnSave " style="display: none;">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                        <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกรายการจัดเบิก
                        </button>
                      </div>
                      <div id="last_form"></div>
                    </form>
                  </div>

                <div class="myBorder">
                  <div style="">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการรอเบิกจากคลัง  </span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-12">
                        <table id="data-table-packing" class="table table-bordered dt-responsive" style="width: 100%;">
                        </table>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="myBorder">
                  <div class="form-group row ">
                    <div class="col-md-10 d-flex  ">
                      <label class="col-5" ><i class="bx bx-play"></i> ค้นสินค้า > ค้นด้วย : QR-CODE : </label>
                      <div class="col-md-5">
                        <input type="text" class="form-control" name="txtSearch" style="font-size: 18px !important;color: blue;" autofocus >
                      </div>
                      <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;padding: 0.7%" >
                        <i class="bx bx-search align-middle "></i> &nbsp;&nbsp;&nbsp;SEARCH&nbsp;&nbsp;&nbsp;
                      </a>
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
var can_approve = "{{@$can_approve}}"; //alert(sD);
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
        "searching": false,
        scrollY: ''+($(window).height()-370)+'px',
        // iDisplayLength: 10,
        // stateSave: true,
        ajax: {
          url: '{{ route('backend.pickup_goods.datatable') }}',
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
                  {data: 'id', title :'ID', className: 'text-center '},
                  {data: 'id', title :'เลือก', className: 'text-center'},
                  {data: 'delivery_date', title :'<center>วันเวลาที่ออกบิล </center>', className: 'text-center w100 '},
                  // {data: 'receipt', title :'ใบเสร็จ', className: 'text-center'},
                  {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                  {data: 'customer_name',   title :'ชื่อลูกค้า', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                  {data: 'province_name', title :'สาขา', className: 'text-center'},
                  {data: 'billing_employee', title :'พนักงานที่ออกบิล', className: 'text-center'},
                  {data: 'status_pack',   title :'Type', className: 'text-center ',render: function(d) {
                  		if(d==1){
                  			return '<span class="badge badge-pill badge-soft-warning font-size-16"><i class="fab fa-medium-p"><span class="tooltip_packing" >P</span></i></span>';
                  		}else{
                  			return '<span class="badge badge-pill badge-soft-info font-size-16"><i class="fab fa-medium-i"><span class="tooltip_invoice" >I</span></i></span>';
                  		}
                      
                  }},
             
              ],
              'columnDefs': [
               {
                  'targets': 0,
                  'checkboxes': {'selectRow': true },
               }
            ],
            'select': {
               'style': 'multi'
            },

              rowCallback: function(nRow, aData, dataIndex){

                    $("td:eq(1)", nRow).hide();

              }
            });
      
          });


            $('#data-table').on( 'click', 'tr', function () {

  					     setTimeout(function(){
  	            		if($('.select-info').text()!=''){
  	            			$('.divBtnSave').show();
  		            	}else{
  		            		$('.divBtnSave').hide();
  		            	}
  		            }, 500);

              } );

           // Handle form submission event 
           $('#frm-example').on('submit', function(e){
              var form = this;

              console.log(form);
              
              var rows_selected = oTable.column(0).checkboxes.selected();

              console.log(rows_selected);

              // Iterate over all selected checkboxes
              $.each(rows_selected, function(index, rowId){

                console.log(rowId);
                // $("#last_form").after("<input type='text' name='row_id[]' value='"+rowId+"' >");
                 // Create a hidden element 
                $('#last_form').after(
                     $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'row_id[]')
                        .val(rowId)
                 );
              });

              // FOR DEMONSTRATION ONLY
              // The code below is not needed in production
              
              // Output form data to a console     
              // $('#example-console-rows').text(rows_selected.join(","));
              
              // Output form data to a console     
              // $('#example-console-form').text($(form).serialize());
               
              // Remove added elements
              // $('input[name="id\[\]"]', form).remove();
               
              // Prevent actual form submission
              // e.preventDefault();

               });



          var oTable3;
          $(function() {
              oTable3 = $('#data-table-packing').DataTable({
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
                    url: '{{ route('backend.pickup_goods_set.datatable') }}',
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
                      {data: 'delivery_date', title :'วันเวลาที่ออกบิล', className: 'text-center'},
                      // {data: 'receipt', title :'ใบเสร็จ', className: 'text-center w250 '},
                      {data: 'receipt',   title :'<center>ใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      // {data: 'customer_name', title :'ชื่อลูกค้า', className: 'text-center'},
                      {data: 'customer_name',   title :'<center>ชื่อลูกค้า</center>', className: 'text-center ',render: function(d) {
                          if(d){
                            return d.replace(/ *, */g, '<br>');
                          }else{
                            return '-';
                          }
                      }},
                      {data: 'billing_employee', title :'พนักงานที่ออกบิล', className: 'text-center'},
                      // {data: 'id',   title :'พิมพ์ใบเบิก', className: 'text-center ',render: function(d) {
                      //     return '<a href="{{ URL::to('backend/pickup_goods/print_receipt') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a>';
                      // }},
                      {data: 'print',   title :'พิมพ์ใบเบิก', className: 'text-center ',render: function(d) {
                           var str = d.split(":");
                           // return str[0];
                           if(str[0]==0){
                              return '<a href="{{ URL::to('backend/pickup_goods/print_receipt') }}/'+str[1]+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a>';
                           }else{
                              return '<a href="{{ URL::to('backend/pickup_goods/print_receipt_pack') }}/'+str[1]+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a>';
                           }
                      }},
                      {data: 'approved',   title :'อนุมัติเบิก', className: 'text-center ',render: function(d) {
                          var v = d.split(":");
                          if(v[0]==1){
                              return '<input type="checkbox" class="approved" checked value="'+v[1]+'" style="transform: scale(2);">';
                           }else{
                              return '<input type="checkbox" class="approved" value="'+v[1]+'" style="transform: scale(2);">';
                           }

                          
                          // return '<i class="bx bxs-check-circle" style="font-size:18px;color:green;"></i>';
                      }},
                      {data: 'id', title :'Tools', className: 'text-center w80'}, 
                  ],
             
                  rowCallback: function(nRow, aData, dataIndex){

                      if(can_approve!=1){
                          $("td:eq(6)", nRow).html('-');
                      }

                      if(aData['approver']==0){


                        if(sU!=''&&sD!=''){
                            $('td:last-child', nRow).html('-');
                        }else{ 

                            $('td:last-child', nRow).html(''
                                + '<a href="backend/pickup_goods/" data-url="{{ route('backend.pickup_goods.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                              ).addClass('input');

                        }

                      }else{


                        $('td:last-child', nRow).html('');

                        $('td', nRow).css('background-color', '#ffd9b3');
                        // $("td:eq(4)", nRow).html('');
                        // $("td:eq(5)", nRow).html('');
                        $("td:eq(6)", nRow).html('<i class="bx bx-check-square" style="font-size:30px;color:blue;"></i>');
                        var i;
                        for (i = 0; i < 10 ; i++) {
                           $("td:eq("+i+")", nRow).prop('disabled',true); 
                        } 



                      }


                  }
              });

              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                oTable3.draw();
              });
          });


          $(document).ready(function() {

          		$('input[type=checkbox]').click(function(event) {
          	
                 setTimeout(function(){
                    if($('.select-info').text()!=''){
                      $('.divBtnSave').show();
                    }else{
                      $('.divBtnSave').hide();
                    }
                  }, 500);

                }); 

                $(document).on('click', '.dt-checkboxes', function(event) {

                   setTimeout(function(){
                      if($('.select-info').text()!=''){
                        $('.divBtnSave').show();
                      }else{
                        $('.divBtnSave').hide();
                      }
                    }, 500);

                }); 

                $(document).on('click', '.approved', function(event) {

                    var id = $(this).val();

                      if (!confirm("ยืนยัน อนุมัติเบิก ? ")){
                          return false;
                      }else{

                          $.ajax({
                               type:'POST',
                               url: " {{ url('backend/ajaxApprovePickupGoods') }} ", 
                               data:{ _token: '{{csrf_token()}}',id:id },
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

                      }

                }); 

                
                $(document).on('click', '.btnEditAddr', function(event) {
                	event.preventDefault();
                	var id = $(this).data('id');
                	$.ajax({
		               type:'POST',
		               url: " {{ url('backend/ajaxSelectAddrEdit') }} ", 
		               data:{ _token: '{{csrf_token()}}',v:id },
		                success:function(data){
		                     // console.log(data); 
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


          });


    // $(window).on('load',function(){
    	var v = "<?=@$_REQUEST['select_addr']?>";
    	// alert(v);
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
        
    // });
</script>


<script type="text/javascript">
//  sessionStorage.setItem("role_group_id", role_group_id);
//  var role_group_id = sessionStorage.getItem("role_group_id");
//  var menu_id = sessionStorage.getItem("menu_id");
//    window.onload = function() {
//    if(!window.location.hash) {
//       window.location = window.location + '?role_group_id=' + role_group_id + '&menu_id=' + menu_id + '#menu_id=' + menu_id ;
//    }
//  }
</script>

@endsection

