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
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
  }
  .divTableCell, .divTableHead {
    border: 1px solid white;
    display: table-cell;
    padding: 3px 6px;
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

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18  "> เบิกจ่ายสินค้าจากคลัง </h4>
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

              
              <?php }?>

      
                <div class=" divCreatePackBill " style="display: none;">
                  <center>
                    <button type="button" class="btn btn-primary btn-sm waves-effect font-size-18 btnCreatePackBill ">
                    <i class="bx bx-save font-size-18 align-middle mr-1"></i> สร้างใบเบิก หรือ รวม Packing ใบเบิก
                    </button>
                  </center>
                  </div>

                <div id="last_form"></div>


              </form>

 </div>



<div class="myBorder" style="" >
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">

          <div class="form-group row">
            <div class="col-md-12">
              <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการสินค้าที่รอหยิบออกจากคลัง (FIFO) </span>
            </div>
          </div>

          <div class="form-group row  " >
            <div class="col-md-12 ">
              <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>


                      <div class="form-group row div_btn_save " style="display: none;" >
                        <div class="col-md-12 text-center ">
                          <br>
                          <button type="button" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave ">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกการเบิกสินค้า
                          </button>

                        </div>
                      </div>


            </div>
          </div>


        </div>

      </div>
    </div>
  </div>
  </div> <!-- end col -->





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
                    url: '{{ route('backend.packing_list_for_fifo.datatable') }}',
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
                      {data: 'packing_code_02', title :'<center>รหัส Packing List </center>', className: 'text-center'},
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
                            // $('td:last-child', nRow).html(''
                            //     + '<a href="{{ route('backend.pick_warehouse.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                            
                            //   ).addClass('input');
                        }

                    }

                  }
              });


          });


        $(document).ready(function() {
          
          
                  $('#data-table-packing').on( 'click', 'tr', function () {

                       $(".myloading").show();

                        $("input[name^=row_id]").remove();
                        $("#pick_pack_requisition_code_id_fk").val('');

                        setTimeout(function(){

                                var rows_selected = $('#data-table-packing').DataTable().column(0).checkboxes.selected();
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

                        }, 500);

                       setTimeout(function(){
                          if($('.select-info').text()!=''){
                            var str = $('.select-info').text();
                            var str = str.split(" ");
                              $('.divCreatePackBill').show();
                      
                          }else{
                            $('.divCreatePackBill').hide();
                             $('input[name*=row_id').remove();
                          }

                           $(".myloading").hide();

                        }, 500);



                  } );

        });
 
</script>

    <script>

       $(document).on('click', '.btnCreatePackBill', function(e) {

                $(".myloading").show();

                  var picking_id = new Array();
                  $("input[name*=row_id]").each(function() {
                     picking_id.push($(this).val());
                  });

                  // console.log(picking_id);
                  // return false;

                 // $('#data-table-0001').show();
                 $('#data-table-0002').show();

                 setTimeout(function(){
  
                        if(picking_id!=""){

                             $.ajax({
                                   type:'POST',
                                   url: " {{ url('backend/calFifo') }} ", 
                                   // data: $("#frm-packing").serialize()+"&picking_id="+picking_id,
                                   data:{picking_id:picking_id},
                                    success: function(response){ 
                                      console.log(response);
                                      // return false;

                                      $("#pick_pack_requisition_code_id_fk").val(response);
                                      var pick_pack_requisition_code_id_fk = $("#pick_pack_requisition_code_id_fk").val();
                                      // return false;
                                   
                                  // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

                                  // console.log(picking_id);
                    
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
                                                    url: '{{ route('backend.pick_warehouse_tb_0002.datatable') }}',
                                                    type: "POST",
                                                    data: { _token : "{{ csrf_token() }}", picking_id:picking_id },
                                                },
                                                columns: [

                                                  {data: 'column_001', title :'<center> รายการครั้งที่จ่ายสินค้า </center> ', className: 'text-center w250'},
                                                  {data: 'column_002', title :'<center> รายการสินค้า </center> ', className: ''},
                                                                         
                                                ],
                                                rowCallback: function(nRow, aData, dataIndex){

                                                      $(".myloading").hide();
                                                      // console.log("xxxxxxx");
                                                      // console.log(aData['ch_amt_lot_wh']);
                                                      setTimeout(function(){
                                                          if(aData['ch_amt_lot_wh']==0){
                                                            $(".div_btn_save").hide();
                                                          }else{
                                                              $(".div_btn_save").show();
                                                          }
                                                      }, 500);

                                                }
                                            });
                                       
                                        });
                                        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

                                        $(".myloading").hide();

                                    },
                                    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                       $(".myloading").hide();
                                        console.log(JSON.stringify(jqXHR));
                                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                    }
                                });
                        }else{
                           $(".myloading").hide();
                           // $('#data-table-0001').hide();
                           $('#data-table-0002').hide();
                           $(".div_btn_save").hide();
                           return false;
                        }

                     }, 1500);


         }); // ปิด $(document).on('click', '.btnSave'

    </script>
   


    <script>

       $(document).on('click', '.btnSave', function(e) {

                $(".myloading").show();


                  var picking_id = new Array();
                  var db_pick_pack_packing_code_id = new Array();
                  $("input[name*=row_id]").each(function() {
                     picking_id.push($(this).val());
                     db_pick_pack_packing_code_id.push($(this).val());
                  });
      
                // ก่อนบันทึก recheck อีกรอบ เผื่อมีสินค้าเข้ามาเติมเต็มแล้ว 
                  setTimeout(function(){
  
                        if(picking_id!=""){

                             $.ajax({
                                   type:'POST',
                                   url: " {{ url('backend/calFifo') }} ", 
                                   data: $("#frm-packing").serialize()+"&picking_id="+picking_id,
                                    success: function(response){ // What to do if we succeed
                                      // console.log(response);
                                       // return false;
                                      // console.log(ids[0]);

                                      $("#pick_pack_requisition_code_id_fk").val(response);
                                      var pick_pack_requisition_code_id_fk = $("#pick_pack_requisition_code_id_fk").val();
                                      // return false;

                                     // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

                                        $('#data-table-0002').DataTable().clear().draw();

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
                                                                           url: " {{ url('backend/ajaxSavePay_requisition') }} ",
                                                                           data:{ _token: '{{csrf_token()}}',picking_id:response,db_pick_pack_packing_code_id:db_pick_pack_packing_code_id },
                                                                            success:function(d2){
                                                                                 // console.log(d2);
                                                                                 // return false;
                                                                                // location.replace('{{ url("backend/pick_warehouse") }}/'+d2+'/edit');
                                                                                location.replace('{{ url("backend/pay_requisition_001") }}');
   
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

                                                      

                                                   } // ปิด if(data=="No_changed"){

                                                }, // ปิด success:function(data){
                                          
                                          }); // ปิด $.ajax({
                                      
                                        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@

                                    },
                                    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                        console.log(JSON.stringify(jqXHR));
                                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                    }
                                });
                        }else{
                           $(".myloading").hide();
                           // $('#data-table-0001').hide();
                           $('#data-table-0002').hide();
                           $(".div_btn_save").hide();
                           return false;
                        }

                     }, 1500);


         }); // ปิด $(document).on('click', '.btnSave'

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

      DB::select(" UPDATE db_stocks SET amt='100' ; ");

      ?>
          <script>
          location.replace( "{{ url('backend/pick_warehouse') }}");
          </script>
          <?php
      }
    ?>
@endsection


