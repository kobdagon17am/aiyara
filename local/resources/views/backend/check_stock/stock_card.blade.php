@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 1200px !important; /* New width for default modal */
        }
    }

    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 74%;}
</style>
@endsection

@section('content')
<div class="myloading"></div>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Stock Card <i class="bx bx-play"></i> {{ @$Products[0]->product_code." : ".@$Products[0]->product_name }} : LOT NUMBER = {{@$lot_number}}  </h4>
               <a class="btn btn-secondary btn-sm waves-effect float-right " href="{{ url("backend/check_stock") }}">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
              </a>
        </div>

    </div>
</div>
<!-- end page title -->

  <?php 
    $sPermission = \Auth::user()->permission ;
    $menu_id = @$_REQUEST['menu_id'];
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
    }
   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

               <div class="row">
                    <div class="col-12 d-flex ">

                      <div class="col-md-4 d-flex  ">
                        <?php
                          $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
                          $last_day_this_month  = date('Y-m-t');
                         ?>
                         <input id="start_date"  autocomplete="off" placeholder="วันเริ่ม" value="<?=$first_day_this_month?>" />
                         <input id="end_date"  autocomplete="off" placeholder="วันสิ้นสุด" value="<?=$last_day_this_month?>" />
                      </div>

                      <div class="col-md-3">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-info btn-sm waves-effect btnProcess " style="font-size: 14px !important;" >
                          <i class="bx bx-cog font-size-16 align-middle mr-1"></i> ประมวลผล / Processing
                          </button>
                        </div>
                      </div>

                      <div class="col-md-5" >
                        <div class="form-group row" style="float: right;" > 
                          <!-- <button type="button" class="btn btn-info btn-sm waves-effect btnPrint " style="font-size: 14px !important;display: none;" >
                          <i class="bx bx-printer font-size-16 align-middle mr-1"></i> พิมพ์
                          </button> -->

                           <a class="btn btn-info btn-sm btnPrint " href="{{ URL::to('backend/check_stock/print') }}/{{@$Products[0]->product_id}}/{{@$lot_number}}" style="font-size: 14px !important;display: none;" target="_blank" >
                            <i class="bx bx-printer align-middle "></i> พิมพ์ / Print-Out
                          </a>

                        </div>
                      </div>


                    </div>
                  </div>


                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;"></table>

                <div class="row div_balance " style="float: right;font-size: 18px !important;margin-right: 5%;font-weight: bold;text-decoration: underline;text-decoration-style: double;display: none;margin-top: 1%;">
                  ยอดคงเหลือ = {{@$sBalance[0]->amt}}
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script>

</script>

<script>
$(document).ready(function() {



      $(document).on('click', '.btnProcess', function(event) {

           
              var product_id_fk =  "{{@$Products[0]->product_id}}";

              var start_date =  $('#start_date').val();
              if(start_date==''){
                $('#start_date').focus();
                return false;
              }        

              var end_date =  $('#end_date').val();
              if(end_date==''){
                $('#end_date').focus();
                return false;
              }        

              $(".myloading").show();

               setTimeout(function(){

                      $.ajax({
                           url: " {{ url('backend/ajaxProcessStockcard') }} ", 
                          method: "post",
                          data: {
                            product_id_fk:product_id_fk,
                            start_date:start_date,
                            end_date:end_date,
                            "_token": "{{ csrf_token() }}", 
                          },
                          success:function(data)
                          { 

                            console.log(data);
                      
                          
                                /* datatables */
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
                                          info:     false,
                                          destroy:true,
                                          // scrollY: ''+($(window).height()-370)+'px',
                                          // iDisplayLength: 25,
                                          paging: false,
                                          iDisplayLength: -1,
                                          ajax: {
                                            url: '{{ route('backend.stock_card.datatable') }}',
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
                                              // {data: 'action_date', title :'<center>วันที่ : เวลา ดำเนินการ </center>', className: 'text-left'},
                                              {data: 'action_date', title :'<center>Date-time : Processing  </center>', className: 'text-left'},
                                              // {data: 'details', title :'<center>ประเภทรายการ </center>', className: 'text-left'},
                                              {data: 'details', title :'<center>Item Type  </center>', className: 'text-left'},
                                              // {data: 'ref_inv', title :'<center>รหัสอ้างอิง </center>', className: 'text-center'},
                                              {data: 'ref_inv', title :'<center>Reference code  </center>', className: 'text-center'},
                                              // {data: 'action_user', title :'<center>ผู้ดำเนินการ </center>', className: 'text-center'},
                                              {data: 'action_user', title :'<center>Operator  </center>', className: 'text-center'},
                                              // {data: 'approver', title :'<center>ผู้อนมุัติ </center>', className: 'text-center'},
                                              {data: 'approver', title :'<center>Approval  </center>', className: 'text-center'},
                                              {data: 'id', title :'<center>รับเข้า/Import </center>', className: 'text-right'},
                                              {data: 'id', title :'<center>จ่ายออก/Export </center>', className: 'text-right'},
                                              {data: 'amt_remain', title :'<center>ยอดคงเหลือ/Balance </center>', className: 'text-right'},
                                          ],
                                          rowCallback: function(nRow, aData, dataIndex){
                                            var info = $(this).DataTable().page.info();
                                            $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                              var decreased = parseInt(aData['amt']);

                                              if(isNaN(decreased) || decreased > 0) {
                                                $("td:eq(6)", nRow).html(aData['amt']);
                                              }else{
                                                $("td:eq(6)", nRow).html('');
                                              }


                                              var increased = parseInt(aData['amt']);

                                              if(isNaN(increased) || increased <= 0) {
                                                $("td:eq(7)", nRow).html(aData['amt']);
                                              }else{
                                                $("td:eq(7)", nRow).html('');
                                              }

                                            $(".btnPrint").show();

                                            

                                          }
                                      });

                                  });
                                  /* datatables */

                                  $(".myloading").hide();


                          }
                        })

                setTimeout(function(){
                  $(".div_balance").show();
                }, 1000);

                $(".myloading").hide();

              }, 3000);


      });

});


</script>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        var firstday = new Date(new Date().getFullYear(), new Date().getMonth(),'01');
        $('#start_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#end_date').val();
            // }
        });
        $('#end_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#start_date').val();
            }
        });

         $('#start_date').change(function(event) {
           $('#end_date').val($(this).val());
         });

  </script>   


@endsection

