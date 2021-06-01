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
            <h4 class="mb-0 font-size-18 test_clear_data "> เบิกจ่ายสินค้าจากคลัง </h4>
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

            <div class="myBorder" style="" >
              <div class="col-12">
                
                <div class="form-group row  " >
                  <div class="col-md-12 ">
                  <table id="data-table-0000" class="table table-bordered dt-responsive" style="width: 100%;" ></table>
                  <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;margin-bottom: 0%;" ></table>

                  <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 100%;"> </table>

              </div>
            </div>
            </div>
             <div class="col-12">
                        <div class="form-group row  " >
                          <div class="col-md-12 ">
                            <div class="form-group row div_btn_save " style="display: none;" >
                              <div class="col-md-12 text-center ">
                                <br>
                                <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSave ">
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
</div> <!-- end row -->



@endsection

@section('script')

<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>


<script>

          var packing_id = "{{$packing_id}}";// alert(packing_id);
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
                        url: '{{ route('backend.pick_pack_packing_code.datatable') }}',
                        data :{
                              packing_id:packing_id,
                            },
                          method: 'POST',
                        },
                  columns: [
                      {data: 'packing_code_02', title :'<center>รหัสใบเบิก </center>', className: 'text-center'},
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
                  rowCallback: function(nRow, aData, dataIndex){

                  }
              });


          });

</script>


    <script>
 // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
          var packing_id = "{{$packing_id}}";// alert(packing_id);
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
                            url: '{{ route('backend.pick_warehouse_tb_0001.datatable') }}',
                            type: "POST",
                            data:{ _token: '{{csrf_token()}}',picking_id:packing_id},
                        },
                  columns: [
                      {data: 'column_001', title :'<span style="vertical-align: middle;"> รหัสใบเบิก </span> ', className: 'text-center w150'},
                      {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> รายการสินค้าตามใบเบิก</span> ', className: 'text-left',render: function(d) {
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
            
            var packing_id = "{{$packing_id}}";// alert(packing_id);

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



   <script type="text/javascript">
     
    $(document).ready(function() {

           $(document).on('click', '.cDelete2', function(){

            var time_pay = $(this).data("time_pay");
            var packing_id = "{{$packing_id}}";// alert(packing_id);
            // alert(packing_id+":"+time_pay);
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
                                  pick_pack_packing_code_id_fk:packing_id,
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
      DB::select("TRUNCATE `db_pay_requisition_001`;");
      DB::select("TRUNCATE `db_pay_requisition_002`;");
      DB::select("TRUNCATE `db_pay_requisition_002_cancel_log`;");
      DB::select("TRUNCATE `db_pay_requisition_002_pay_history`;");
      DB::select(" DELETE FROM `db_stocks_return` WHERE pick_pack_packing_code_id_fk is not null ;");
      ?>
          <script>
          location.replace( "{{ url('backend/pick_warehouse') }}");
          </script>
          <?php
    }
?>

@endsection


