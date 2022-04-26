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
            <h4 class="mb-0 font-size-18"> Stock Card <i class="bx bx-play"></i> {{ @$p_name }} 
             </h4>
               <a class="btn btn-secondary btn-sm waves-effect float-right " href="{{ url("backend/check_stock") }}">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
              </a>
        </div>

    </div>
</div>
<!-- end page title -->

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

                    
                    <div class="col-md-4">
                      <!-- <div class="amt_remain" style="float: right;font-size: 18px !important;font-weight: bold;"> -->
                      <div style="float: right;font-size: 18px !important;font-weight: bold;">
                        ยอดคงเหลือ = {{number_format(@$sBalance,0)}}
                      </div>
                    </div>


                    </div>
                  </div>

                <table id="data-table" class="table table-bordered " style="width: 100%;"></table>

         <!--        <div class="row" >
                  <div class="col-11 "></div>
                  <div class="col-1 ">
                    <a class="btn btn-info btn-sm btnPrint " href="{{ URL::to('backend/check_stock/print') }}/{{@$Products[0]->product_id}}/{{@$lot_number}}" style="font-size: 14px !important;display: none;margin-right: 5%;margin-top: 2%;" target="_blank" >
                      <i class="bx bx-printer align-middle "></i> Print
                    </a>
                  </div>
                </div> -->


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

      function formatNumber(num) {
          return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
      }

    
     
      $(document).on('click', '.btnProcess', function(event) {

             $(".myloading").show();
           
              var business_location_id_fk =  "{{@$business_location_id_fk}}"; //alert(business_location_id_fk);
              var branch_id_fk =  "{{@$branch_id_fk}}"; //alert(branch_id_fk);
              var product_id_fk =  "{{@$product_id_fk}}"; //
              var lot_number =  "{{@$lot_number}}"; //alert(lot_number);

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

              console.log(business_location_id_fk);   
              console.log(branch_id_fk);   
              console.log(product_id_fk);   
              // console.log(lot_number);   
              console.log(start_date);   
              console.log(end_date);   

                 // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
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
                               "searching": false,
                              // scrollY: ''+($(window).height()-370)+'px',
                              // iDisplayLength: 25,
                              paging: false,
                              iDisplayLength: -1,
                              ajax: {
                                url: '{{ route('backend.stock_card.datatable') }}',
                                data :{
                                  _token: '{{csrf_token()}}',
                                    business_location_id_fk:business_location_id_fk,
                                    branch_id_fk:branch_id_fk,
                                    product_id_fk:product_id_fk,
                                    start_date:start_date,
                                    end_date:end_date,
                                    lot_number:lot_number,
                                    },
                                  method: 'POST',
                                },

                              dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    title: 'STOCK CARD'
                                },
                           
                            ],
                              columns: [
                                  {data: 'id', title :'Row(s)', className: 'text-center w50'},
                                  {data: 'action_date', title :'<center>Date : Processing  </center>', className: 'text-left'},
                                  {data: 'details', title :'<center>Item Type  </center>', className: 'text-left'},
                                  {data: 'ref_inv', title :'<center>Reference code  </center>', className: 'text-center'},
                                  {data: 'action_user', title :'<center>Operator  </center>', className: 'text-center'},
                                  {data: 'approver', title :'<center>Approval  </center>', className: 'text-center'},
                                  {data: 'amt_in',title :'<center>รับเข้า</center>', className: 'text-center',render: function(d) {
                                        return d>0?formatNumber(parseFloat(d).toFixed(0)):'';
                                  }},
                                  {data: 'amt_out',title :'<center>จ่ายออก</center>', className: 'text-center',render: function(d) {
                                        return d>0?formatNumber(parseFloat(d).toFixed(0)):'';
                                  }},
                           
                                  {data: 'remain', title :'<center>ยอดคงเหลือ</center>', className: 'text-center'},
                              ],
                              rowCallback: function(nRow, aData, dataIndex){
                                var info = $(this).DataTable().page.info();
                                $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                if(aData['id']=='1'){
                                  $("td:eq(6)", nRow).html('');
                                  $("td:eq(7)", nRow).html('');
                                  // $("td:eq(8)", nRow).html(aData['amt_in']);
                                }

                                // if(aData['remain']!=0){
                                  $('td:last-child', nRow).html(formatNumber(parseFloat(aData['remain']).toFixed(0)));
                                // }else{
                                //   $('td:last-child', nRow).html('');
                                //   $(".buttons-html5").hide();
                                // }

                                $(".myloading").hide();

                                 // console.log(aData['id']);
                                 // console.log(aData['remain']);


                              }
                          });
                      });
              // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

              // var count = $('#data-table').dataTable().fnSettings().aoData.length;
              // console.log(count);
              // if (count == 0)
              // {
              //    $(".buttons-html5").hide();
              //    $(".myloading").hide();
              // }

      });

});



</script>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
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
            if($('#end_date').val()>$(this).val()){
            }else{
              $('#end_date').val($(this).val());
            }
        });

    </script>   


@endsection

