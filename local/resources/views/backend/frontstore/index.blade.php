@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">

  .border-left-0 {height: 95%;}
  label { font-size: 14px;font-weight: bold !important; }

</style>

<style type="text/css">
  /* DivTable.com */
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
  padding: 3px 10px;
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


  .tooltip_cost {
    position: relative ;
    cursor: pointer;

  }

.tooltip_cost:hover::after {
    cursor: pointer;
    /*content: "เงินสด : 9,999.00 , เงินโอน : 9,999.00 + ค่าธรรมเนียม : 100 ";*/
    position: absolute;
    top: 0.0em;
    left: 5em;
    min-width: 100px;
    border-radius: 5%;
    border: 1px #808080 solid;
    padding: 3px;
    color: black;
    background-color: #cfc;
    /*z-index: 9999;*/
}


</style>
@endsection

@section('content')
@include('popper::assets')

<div class="myloading"></div>
<!-- start page title -->

<?php
   $sPermission = \Auth::user()->permission ;
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18  "> {{ __('message.shop_selling') }}  ({{\Auth::user()->position_level==1?'Supervisor/Manager':'CS'}}) </h4>
            <!-- <input type="text" class="get_menu_id">   test_clear_data   -->
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
              <div class="divTH">
                <label for="startDate" >{{ __('message.create_date') }} : </label>
              </div>
              <?php

                $sd = date('Y-m-d');

                // $date=date_create("2021-09-27");
                // date_sub($date,date_interval_create_from_date_string("3 days"));
                // echo date_format($date,"Y-m-d");

                // $sd = date_format($date,"Y-m-d");

               ?>

              <div class="divTableCell">
                <input id="startDate" class="form-control" autocomplete="off" value="{{ @$sd }}" />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub btnSearchDate" data-attr="startDate" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="endDate" >{{ __('message.end_date') }} : </label>
              </div>
              <div class="divTableCell">
                <input id="endDate" class="form-control" autocomplete="off" value="{{ @$sd }}" />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub btnSearchDate " data-attr="endDate" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >{{ __('message.order_type') }} : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select id="purchase_type_id_fk" name="purchase_type_id_fk" class="form-control select2-templating " required  >
                    <option value="">Select</option>
                    @if(@$sPurchase_type)
                      @foreach(@$sPurchase_type AS $r)
                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->purchase_type_id_fk)?'selected':'' }} >
                          {{$r->orders_type}}
                        </option>
                      @endforeach
                    @endif
                  </select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="purchase_type_id_fk" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            </div>
            <div class="divTableRow">
              <div class="divTH">
                <label for="" >{{ __('message.customer_id') }} : </label>
              </div>
              <div class="divTableCell">

                 <select id="customer_username" name="customer_username" class="form-control" ></select>

              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="customer_username" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >{{ __('message.customer_name') }} : </label>
              </div>
              <div class="divTableCell">
                <select id="customer_name" name="customer_name" class="form-control" ></select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="customer_name" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >{{ __('message.receipt_no') }} : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                     @if(@$r_invoice_code)
                     <select id="invoice_code" name="invoice_code" class="form-control select2-templating " >
                         <option value="">Select</option>
                          @foreach(@$r_invoice_code AS $r)
                          <option value="{{$r->code_order}}" >
                            {{$r->code_order}}
                          </option>
                          @endforeach
                     </select>
                     @else
                     <select class="form-control select2-templating " >
                         <option value="">Select</option>
                     </select>
                    @endif
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="invoice_code" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            </div>


            <div class="divTableRow">
<!--
                  <div class="divTH">
                <label for="" >เลขที่ใบสั่งซื้อ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <input class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div> -->

              <div class="divTH">
                <label for="" >{{ __('message.creator') }} : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select id="action_user" name="action_user" class="form-control select2-templating "  >
                  <option value="">Select</option>
                  @if(@$sUser)
                    @foreach(@$sUser AS $r)
                      <option value="{{$r->id}}"  >
                        {{$r->name}}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="action_user" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>

              <div class="divTH">
                <label for="" >{{ __('message.money_send') }} : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
              <select id="status_sent_money" class="form-control select2-templating "  >
                  <option value="">Select</option>
                      <option value="0"> - (รอดำเนินการ)</option>
                      <option value="1"> In Process </option>
                      <option value="2"> Success </option>
                </select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="status_sent_money" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>

              <div class="divTH">
                <label for="" >{{ __('message.status') }} : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select id="approve_status" name="approve_status" class="form-control select2-templating "  >
                  <option value="">Select</option>
                  @if(@$sApproveStatus)
                    @foreach(@$sApproveStatus AS $r)
                      <option value="{{$r->id}}"  >
                        {{$r->txt_desc}}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary btnSearchSub " data-attr="approve_status" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>

            </div>




            <div class="divTableRow">

              <div class="divTH">
                <!-- <label for="" >การส่งเงิน : </label> -->
              </div>
              <div class="divTableCell" style="width: 15%">
             <!--  	   <select name="" class="form-control select2-templating "  >
                  <option value="">Select</option>
                      <option value="0"> - </option>
                      <option value="1"> In Process </option>
                      <option value="1"> Success </option>
                </select> -->
              </div>
              <div class="divTableCell">
                <!-- <button type="button" class="btn btn-primary" style="padding: 6%;"><i class="bx bx-search font-size-18 align-middle "></i></button> -->
              </div>
              <div class="divTH">
                <label for="" > </label>
              </div>
              <div class="divTableCell" style="width: 15%">
              </div>
              <div class="divTableCell">
              </div>
              <div class="divTH">
                <label for="" >  </label>
              </div>
              <div class="divTableCell" style="width: 15%;text-align:right;">
                  <button type="button" class="btn btn-warning btnSearchTotal " style="color:black;"><i class="bx bx-search font-size-18 align-middle "></i> {{ __('message.search') }}</button>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-info btnRefresh " style="padding: 9%;"><i class="fa fa-refresh font-size-18 align-middle "></i></button>
              </div>

            </div>
          </div>
        </div>
<!--       </div>
    </div>
  </div>
</div>



<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body"> -->
      	<hr>
        <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
<!-- btnViewCondition -->
              <div class="divTableCell" style="text-align: right;width: 40%;" >&nbsp; </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success btnViewCondition " data-id="ViewAll" ><i class="bx bx-search font-size-18 align-middle"></i> {{ __('message.see_all') }}</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success btnViewCondition " data-id="ViewBuyNormal" ><i class="bx bx-search font-size-18 align-middle"></i> {{ __('message.general_buy') }}</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success btnViewCondition " data-id="ViewBuyVoucher" ><i class="bx bx-search font-size-18 align-middle"></i> {{ __('message.only_buy_ai_gift') }} </button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                    <a  href="{{ route('backend.frontstore.create') }}">
                <button type="button" class="btn btn-success btnAdd  " ><i class="fa fa-plus font-size-18 align-middle "></i> {{ __('message.add') }}</button>
                <!-- class_btn_add -->
                 </a>
              </div>
            </div>
          </div>
        </div>
        <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
        </table>
        <b>หมายเหตุ</b> รายการ <font color=red>* รอดำเนินการต่อ </font> หมายถึง รายการบิล ที่ยังไม่ทำการกดปุ่ม save ขั้นตอนการชำระเงิน สำหรับบิลนี้สามารถทำการยกเลิกได้หรือเข้าไปทำการบันทึกอีกครั้งได้
        <div class="row">
          <div class="col-lg-5">
            <div class="card">
              <div class="card-body">

<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
                <div class="div_PV_Amount"></div>
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->

              </div>
            </div>
          </div>

<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
            <div class="col-lg-7">
              <div class="card">
                <div class="card-body">

                  <div class="div_SumCostActionUser"></div>

                </div>
              </div>
            </div>
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->

        </div>
      </div>
    </div>
    </div> <!-- end col -->
    </div> <!-- end row -->
  </div>
</div>
</div>
</div>



<div class="modal fade" id="modalOne" tabindex="-1" role="dialog" aria-labelledby="modalOneTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOneTitle"><b><i class="bx bx-play"></i>รายการใบเสร็จ</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <div class="modal-body invoice_list " style="margin-left:5%;font-size: 16px;width: 80% !important;">

       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

    </div>
  </div>
</div>



@endsection

@section('script')


<script>

$(function() {
      $('.ttt').hide();
      $(document).on('mouseover', '.tooltip_cost', function(event) {
            // var this_rec = $(this).attr('id');
            // var this_rec = "เงินสด : "+this_rec+" , เงินโอน : "+this_rec+" + ค่าธรรมเนียม : "+this_rec+" ";
            $(this).next('.ttt').toggle().show();
      });

      $(document).on('mouseout', '.tooltip_cost', function(event) {
            $('.ttt').hide();

      });

});

</script>

<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script>

var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: true,
        paging:   true,
        searching: false,
        bLengthChange: false ,
        iDisplayLength: 25,
        // iDisplayLength: 35,
        ajax: {
          url: '{{ route('backend.frontstore.datatable') }}',
          data: function ( d ) {
            oData = d;
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w15'},
            {data: 'created_at', title :'<center>{{ __("message.create_date") }}</center>', className: 'text-center w60'},
            {data: 'action_user', title :'<center>{{ __("message.creator") }} </center>', className: 'text-center w80'},
            {data: 'purchase_type_id_fk',   title :'<center>{{ __("message.order_type") }}</center>', className: 'text-center w80 ',render: function(d) {
              if(d==1){
                return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="ทำคุณสมบัติ"> <i class="fa fa-shopping-basket"></i> </span>';
              }else if(d==2){
                return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="รักษาคุณสมบัติรายเดือน"> <i class="fa fa-calendar-check-o"></i> </span>';
              }else if(d==3){
                return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="รักษาคุณสมบัติท่องเที่ยว"> <i class="fa fa-bus"></i> </span>';
              }else if(d==4){
                return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="เติม Ai-Stockist"> <i class="fas fa-wallet"></i> </span>';
              }else if(d==5){
                return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="Gift Voucher"> <i class="fa fa-gift"></i> </span>';
              }else if(d==6){
                return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="คอร์สอบรม" > <i class="mdi mdi-account-tie"></i> </span>';
              }else{
                return '';
              }
            }},
            {data: 'customer_name', title :'<center>{{ __("message.customer") }}</center>', className: 'text-center'},
            {data: 'total_price',   title :'{{ __("message.total_baht") }}', className: 'text-right ',render: function(d) {
               if(d){
                return '<span style="font-size:16px;font-weight:bold;">'+d+'</span>';
               }else{
                return '';
               }
            }},
            {data: 'code_order',   title :'<center>{{ __("message.receipt_code_no") }}</center>', className: 'text-center ',render: function(d) {
               if(d){
                return '<span class="badge badge-pill badge-soft-primary font-size-16">'+d+'</span>';
               }else{
                return '';
               }
            }},
            {data: 'pay_type', title :'<center>{{ __("message.payment_type") }} </center>', className: 'text-center'},
            {data: 'shipping_price',   title :'<center>{{ __("message.transportor_fee") }}</center>', className: 'text-center',render: function(d) {
              if(d>0){
                return d;
              }else{
                return '';
              }
            }},
            {data: 'status',   title :'<center>{{ __("message.status") }}</center>', className: 'text-center w80 ',render: function(d) {
                  return '<span class=" font-size-14 " style="color:darkred">'+d+'</span>';
            }},
            {data: 'status_sent_money',   title :'<center>{{ __("message.money_send_status") }}</center>', className: 'text-center w80 ',render: function(d) {
              if(d==2){
                  return '<span style="color:green;">Success</span>';
              }else if(d==1){
                  return '<span style="color:black;">In Process</span>';
              }else{
              	 return '-';
              }
            }},

            {data: 'id',   title :'{{ __("message.receipt") }}', className: 'text-center w50 ',render: function(d) {
                return '<center>'
                + ' <a href="javascript: void(0);" target=_blank data-id="'+d+'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
            }},

            {data: 'id', title :'Tools', className: 'text-center w80'},
        ],
           "columnDefs": [ {
              // "targets": [0,2,6,7,8,9] ,
              "targets": [0,11,12] ,
              "orderable": false
          } ],
       rowCallback: function(nRow, aData, dataIndex){

           // var info = $(this).DataTable().page.info();
           // $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

           // console.log(aData['status']);

            if(aData['total_price']){
              $("td:eq(5)", nRow).html('<span class="tooltip_cost" style="font-size:14px;font-weight:bold;" >'+aData['total_price']+'</span> <span class="ttt" style="z-index: 99999 !important;position: absolute;background-color: beige;display:none;padding:5px;color:black;">'+aData['tooltip_price']+'</span>');
            }

           if(aData['type']!='0'){
              $("td:eq(3)", nRow).html('<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="เติม Ai-Stockist"> <i class="fas fa-wallet"></i> </span>');
              // $("td:eq(6)", nRow).html('');
              $("td:eq(8)", nRow).html('');
            }

            if(aData['pay_type']=='ai_cash'){
              $("td:eq(7)", nRow).html('เติม Ai-Cash');
            }

            $("td:eq(4)", nRow).html(aData['customer_name']);



            if(aData['approve_status']==5){

              $('td:last-child', nRow).html('-');

            }else{

                    var sPermission = "<?=\Auth::user()->permission?>";
                    var sU = sessionStorage.getItem("sU");
                    var sD = sessionStorage.getItem("sD");
                    var can_cancel_bill = sessionStorage.getItem("can_cancel_bill");
                    var can_cancel_bill_across_day = sessionStorage.getItem("can_cancel_bill_across_day");

                    if(sPermission==1){
                      sU = 1;
                      sD = 1;
                      can_cancel_bill = 1;
                      can_cancel_bill_across_day = 1;
                    }

                    if(sU!='1'&&sD!='1'){
                        $('td:last-child', nRow).html('-');
                    }else{

                      if(aData['type']!='0'){ // เติม Ai-Cash
                      }else{

                          var str_V = '';

                          str_V = '<a href="{{ URL('backend/frontstore/viewdata') }}/'+aData['id']+'" class="btn btn-sm btn-primary"  ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> ';

                          var str_U = '';
                          if(sU=='1'){
                            str_U = '<a href="{{ route('backend.frontstore.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                          }
                          var str_D = '';
                          if(sD=='1'){
                            str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cCancel ccc " data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
                          }

                          var str_V2 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="เบิกสินค้าจากคลัง" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                          var str_V3 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="รวมในบิล Packing List แล้ว" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                          var str_V4 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="เบิกจ่ายสินค้าแล้ว" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                          if(sU!='1' && sD!='1'){
                             $('td:last-child', nRow).html('-');
                          }else{

                            console.log(aData['status_pay_product_receipt']);

                            // console.log(aData['code_order']+" : "+aData['status_delivery_packing']);
                            console.log(aData['code_order']+" : "+aData['status_delivery_02']);

                            if(aData['status_delivery_02']==1){

                                 $('td:last-child', nRow).html( str_U + str_V2).addClass('input');

                            }else{

                              if(aData['status_delivery_packing']==1){
                                $('td:last-child', nRow).html( str_U + str_V3).addClass('input');
                              }else if(aData['status_pay_product_receipt']==1){
                                $('td:last-child', nRow).html( str_U + str_V4).addClass('input');
                              }else{
                                $('td:last-child', nRow).html( str_U + str_D).addClass('input');
                              }

                                    // var st2 = '<a href="javascript: void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="อยู่ระหว่างการเบิกสินค้าจากคลัง" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';

                                      // console.log(aData['status_sent_product']+" : "+aData['code_order']);
                                    // if(aData['status_sent_product']!="" && aData['status_sent_product']>=2){
                                    //  if(aData['status_sent_product']==aData['code_order']){
                                    //    $('td:last-child', nRow).html(str_U + st2);
                                    //    $("td:eq(9)", nRow).html(aData['status_sent_desc']);
                                    //    if(aData['status_sent_product']==6){
                                    //     $('td:last-child', nRow).html(str_U + str_D);
                                    //    }
                                    // }

                            }

                          }



                    }


                    if(aData['purchase_type_id_fk']==6 && aData['approve_status']>=4){
                       $("td:eq(9)", nRow).html('Success');
                    }
               }

            }

              if(aData['approve_status']==0){
                $("td:eq(11)", nRow).html('-');
              }

              if(aData['approve_status']==5){
                $("td:eq(9)", nRow).html('<span class=" font-size-14 " style="color:red;font-weight:bold;">บิลยกเลิก</span>');
                $("td:eq(10)", nRow).html('-');
              }
              if(aData['type']=="เติม Ai-Cash"){
                $("td:eq(11)", nRow).html('-');
                $("td:eq(12)", nRow).html('-');
              }

        }
    });
    oTable.on( 'draw', function () {
      $('[data-toggle="tooltip"]').tooltip();
    });


});
</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // setDate: today,
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });

        $('#endDate').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                // return $('#start_date').val();
            }
        });

         $('#startDate').change(function(event) {
         	if($('#endDate').val()<$(this).val()){
           		$('#endDate').val($(this).val());
       		}
         });

         $('#endDate').change(function(event) {
         	if($('#startDate').val()>$(this).val()){
           		$('#startDate').val($(this).val());
       		}
         });

        $(document).ready(function() {

          localStorage.clear();

            // $(document).on('click', '.btnAdd', function(event) {
            //   localStorage.clear();
            // });

        });


</script>


<script>
$(document).ready(function() {

       $(document).on('click', '.btnSentMoney', function(e) {

                 Swal.fire({
                      title: 'ยืนยัน ! การส่งเงิน ',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {

                             $.ajax({
                                url: " {{ url('backend/ajaxSentMoneyDaily') }} ",
                                method: "post",
                                data: {
                                  "_token": "{{ csrf_token() }}",
                                },
                                success:function(data)
                                {
                                  // // console.log(data);
                                  // return false;

                                      Swal.fire({
                                        type: 'success',
                                        title: 'ทำการส่งเงินเรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 2000
                                      });

                                      setTimeout(function () {
                  											// $("#tb_sent_money").load(location.href + " #tb_sent_money");
                  											// $('#data-table').DataTable().clear().draw();
                                        location.reload();
                                      }, 1000);
                                }
                              })
                          }else{
                          	 $(".myloading").hide();
                          }
                    });


			     }); // ปิด $(document).on('click', '.btnSave'

			});

    </script>



<script>
$(document).ready(function() {

       $(document).on('click', '.btnCancelSentMoney', function(e) {

       	var id = $(this).data('id');

                 Swal.fire({
                      title: 'ยืนยัน ! ยกเลิกการส่งเงิน ',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {

                             $.ajax({
                                url: " {{ url('backend/ajaxCancelSentMoney') }} ",
                                method: "post",
                                data: {
                                  "_token": "{{ csrf_token() }}", id:id
                                },
                                success:function(data)
                                {
                                  // // // // console.log(data);
                                  // return false;
                                      Swal.fire({
                                        type: 'success',
                                        title: 'ทำการยกเลิกการส่งเงินเรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 2000
                                      });

                                      setTimeout(function () {
                    											// $("#tb_sent_money").load(location.href + " #tb_sent_money");
                    											// $('#data-table').DataTable().clear().draw();
                                          location.reload();
                                      }, 1000);
                                }
                              })
                          }else{
                          	 $(".myloading").hide();
                          }
                    });


			     }); // ปิด $(document).on('click', '.btnSave'

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

           $(document).on('click','.btnRefresh',function(event){
          		$("input").val('');
          		$("select").select2('destroy').val("").select2();
              $("select").select2({
              placeholder: "Select"
              });
      				var today = new Date();
      				var dd = String(today.getDate()).padStart(2, '0');
      				var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      				var yyyy = today.getFullYear();
      				today = yyyy + '-' + mm + '-' + dd ;
          		$('#startDate').val(today);
          		$('#endDate').val(today);
          		$('.btnSearchTotal').trigger('click');
            });

     });
    </script>


 <script>

   $(document).ready(function() {

            $(document).on('click', '.btnViewCondition', function(event) {
                  event.preventDefault();
                  $('#data-table').DataTable().clear();
                  $(".myloading").show();

                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var purchase_type_id_fk = $('#purchase_type_id_fk').val();
                  var customer_username = $('#customer_username').val();
                  var customer_name = $('#customer_name').val();
                  var invoice_code = $('#invoice_code').val();
                  var action_user = $('#action_user').val();
                  var status_sent_money = $('#status_sent_money').val();
                  var approve_status = $('#approve_status').val();


                  var viewcondition = $(this).data('id');
                  // // console.log(viewcondition);

                  if(viewcondition=="ViewAll"){

                    $('.btnSearchTotal').trigger('click');

                  }else{
              // ###################################

              //viewcondition == ViewBuyNormal || ViewBuyVoucher

                                $('#data-table').DataTable().clear();
                                $(".myloading").show();


                                  // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                                          var oTable;
                                          $(function() {
                                              oTable = $('#data-table').DataTable({
                                                  processing: true,
                                                  serverSide: true,
                                                  scroller: true,
                                                  scrollCollapse: true,
                                                  scrollX: true,
                                                  ordering: true,
                                                  paging:   true,
                                                  searching: false,
                                                  bLengthChange: false ,
                                                  destroy: true,
                                                  iDisplayLength: 25,
                                                  // iDisplayLength: 35,
                                                  ajax: {
                                                      url: '{{ route('backend.frontstore.datatable') }}',
                                                      data :{
                                                            startDate:startDate,
                                                            endDate:endDate,
                                                            purchase_type_id_fk:purchase_type_id_fk,
                                                            customer_username:customer_username,
                                                            customer_name:customer_name,
                                                            invoice_code:invoice_code,
                                                            action_user:action_user,
                                                            status_sent_money:status_sent_money,
                                                            approve_status:approve_status,
                                                            viewcondition:viewcondition,
                                                          },
                                                        method: 'POST',
                                                      },
                                                    columns: [
                                                    {data: 'id', title :'ID', className: 'text-center w15'},
                                                    {data: 'created_at', title :'<center>วันสร้าง </center>', className: 'text-center w60'},
                                                    {data: 'action_user', title :'<center>ผู้สร้าง </center>', className: 'text-center w80'},
                                                    {data: 'purchase_type_id_fk',   title :'<center>ประเภท <br> การสั่งซื้อ</center>', className: 'text-center w80 ',render: function(d) {
                                                      if(d==1){
                                                        return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="ทำคุณสมบัติ"> <i class="fa fa-shopping-basket"></i> </span>';
                                                      }else if(d==2){
                                                        return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="รักษาคุณสมบัติรายเดือน"> <i class="fa fa-calendar-check-o"></i> </span>';
                                                      }else if(d==3){
                                                        return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="รักษาคุณสมบัติท่องเที่ยว"> <i class="fa fa-bus"></i> </span>';
                                                      }else if(d==4){
                                                        return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="เติม Ai-Stockist"> <i class="fas fa-wallet"></i> </span>';
                                                      }else if(d==5){
                                                        return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="Gift Voucher"> <i class="fa fa-gift"></i> </span>';
                                                      }else if(d==6){
                                                        return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="คอร์สอบรม" > <i class="mdi mdi-account-tie"></i> </span>';
                                                      }else{
                                                        return '';
                                                      }
                                                    }},
                                                    {data: 'customer_name', title :'<center>ลูกค้า</center>', className: 'text-center'},
                                                    {data: 'total_price',   title :'รวม (บาท)', className: 'text-right ',render: function(d) {
                                                       if(d){
                                                        return '<span style="font-size:16px;font-weight:bold;">'+d+'</span>';
                                                       }else{
                                                        return '';
                                                       }
                                                    }},
                                                    {data: 'code_order',   title :'<center>รหัสใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                                                       if(d){
                                                        return '<span class="badge badge-pill badge-soft-primary font-size-16">'+d+'</span>';
                                                       }else{
                                                        return '';
                                                       }
                                                    }},
                                                    {data: 'pay_type', title :'<center>ประเภท <br> การชำระเงิน </center>', className: 'text-center'},
                                                    {data: 'shipping_price',   title :'<center>ค่าขนส่ง</center>', className: 'text-center',render: function(d) {

                                                      if(d>0){
                                                        return d;
                                                      }else{
                                                        return '';
                                                      }

                                                    }},

                                                    {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w80 ',render: function(d) {
                                                          return '<span class=" font-size-14 " style="color:darkred">'+d+'</span>';
                                                    }},
                                                    {data: 'status_sent_money',   title :'<center>สถานะ<br>การส่งเงิน</center>', className: 'text-center w80 ',render: function(d) {
                                                      if(d==2){
                                                          return '<span style="color:green;">Success</span>';
                                                      }else if(d==1){
                                                          return '<span style="color:black;">In Process</span>';
                                                      }else{
                                                         return '-';
                                                      }
                                                    }},

                                                    {data: 'id',   title :'ใบเสร็จ', className: 'text-center w50 ',render: function(d) {
                                                        return '<center>'
                                                        + ' <a href="javascript: void(0);" target=_blank data-id="'+d+'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
                                                    }},

                                                    {data: 'id', title :'Tools', className: 'text-center w80'},
                                                ],
                                                   "columnDefs": [ {
                                                      // "targets": [0,2,6,7,8,9] ,
                                                      "targets": [0,11,12] ,
                                                      "orderable": false
                                                  } ],
                                                rowCallback: function(nRow, aData, dataIndex){

                                                       // var info = $(this).DataTable().page.info();
                                                       // $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                                       // console.log(aData['status']);

                                                        if(aData['total_price']){
                                                          $("td:eq(5)", nRow).html('<span class="tooltip_cost" style="font-size:14px;font-weight:bold;" >'+aData['total_price']+'</span> <span class="ttt" style="z-index: 99999 !important;position: absolute;background-color: beige;display:none;padding:5px;color:black;">'+aData['tooltip_price']+'</span>');
                                                        }

                                                       if(aData['type']!='0'){
                                                          $("td:eq(3)", nRow).html('<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="เติม Ai-Stockist"> <i class="fas fa-wallet"></i> </span>');
                                                          // $("td:eq(6)", nRow).html('');
                                                          $("td:eq(8)", nRow).html('');
                                                        }

                                                        if(aData['pay_type']=='ai_cash'){
                                                          $("td:eq(7)", nRow).html('เติม Ai-Cash');
                                                        }

                                                        $("td:eq(4)", nRow).html(aData['customer_name']);



                                                        if(aData['approve_status']==5){

                                                          $('td:last-child', nRow).html('-');

                                                        }else{

                                                                var sPermission = "<?=\Auth::user()->permission?>";
                                                                var sU = sessionStorage.getItem("sU");
                                                                var sD = sessionStorage.getItem("sD");
                                                                var can_cancel_bill = sessionStorage.getItem("can_cancel_bill");
                                                                var can_cancel_bill_across_day = sessionStorage.getItem("can_cancel_bill_across_day");

                                                                if(sPermission==1){
                                                                  sU = 1;
                                                                  sD = 1;
                                                                  can_cancel_bill = 1;
                                                                  can_cancel_bill_across_day = 1;
                                                                }

                                                                if(sU!='1'&&sD!='1'){
                                                                    $('td:last-child', nRow).html('-');
                                                                }else{

                                                                  if(aData['type']!='0'){ // เติม Ai-Cash
                                                                  }else{

                                                                      var str_V = '';

                                                                      str_V = '<a href="{{ URL('backend/frontstore/viewdata') }}/'+aData['id']+'" class="btn btn-sm btn-primary"  ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> ';

                                                                      var str_U = '';
                                                                      if(sU=='1'){
                                                                        str_U = '<a href="{{ route('backend.frontstore.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                                                      }
                                                                      var str_D = '';
                                                                      if(sD=='1'){
                                                                        str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cCancel ccc " data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
                                                                      }

                                                                      var str_V2 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="เบิกสินค้าจากคลัง" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                                                                      var str_V3 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="รวมในบิล Packing List แล้ว" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                                                                      if(sU!='1' && sD!='1'){
                                                                         $('td:last-child', nRow).html('-');
                                                                      }else{

                                                                        // console.log(aData['code_order']+" : "+aData['status_delivery_packing']);
                                                                        // console.log(aData['code_order']+" : "+aData['status_delivery_02']);

                                                                        if(aData['status_delivery_02']==1){

                                                                             $('td:last-child', nRow).html( str_U + str_V2).addClass('input');

                                                                        }else{

                                                                          if(aData['status_delivery_packing']==1){
                                                                            $('td:last-child', nRow).html( str_U + str_V3).addClass('input');
                                                                          }else{
                                                                            $('td:last-child', nRow).html( str_U + str_D).addClass('input');
                                                                          }

                                                                                // var st2 = '<a href="javascript: void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="อยู่ระหว่างการเบิกสินค้าจากคลัง" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';

                                                                                  // console.log(aData['status_sent_product']+" : "+aData['code_order']);
                                                                                // if(aData['status_sent_product']!="" && aData['status_sent_product']>=2){
                                                                                //  if(aData['status_sent_product']==aData['code_order']){
                                                                                //    $('td:last-child', nRow).html(str_U + st2);
                                                                                //    $("td:eq(9)", nRow).html(aData['status_sent_desc']);
                                                                                //    if(aData['status_sent_product']==6){
                                                                                //     $('td:last-child', nRow).html(str_U + str_D);
                                                                                //    }
                                                                                // }

                                                                        }

                                                                      }



                                                                }


                                                                if(aData['purchase_type_id_fk']==6 && aData['approve_status']>=4){
                                                                   $("td:eq(9)", nRow).html('Success');
                                                                }
                                                           }

                                                        }

                                                          if(aData['approve_status']==0){
                                                            $("td:eq(11)", nRow).html('-');
                                                          }

                                                          if(aData['approve_status']==5){
                                                            $("td:eq(9)", nRow).html('<span class=" font-size-14 " style="color:red;font-weight:bold;">บิลยกเลิก</span>');
                                                            $("td:eq(10)", nRow).html('-');
                                                          }
                                                          if(aData['type']=="เติม Ai-Cash"){
                                                            $("td:eq(11)", nRow).html('-');
                                                            $("td:eq(12)", nRow).html('-');
                                                          }

                                                    }
                                                });
                                                oTable.on( 'draw', function () {
                                                  $('[data-toggle="tooltip"]').tooltip();
                                                });

                                  // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                                        $.ajax({
                                          url: " {{ url('backend/getSumCostActionUser') }} ",
                                          method: "post",
                                          data: {
                                            "_token": "{{ csrf_token() }}",
                                            startDate:startDate,
                                            endDate:endDate,
                                            purchase_type_id_fk:purchase_type_id_fk,
                                            customer_username:customer_username,
                                            customer_name:customer_name,
                                            invoice_code:invoice_code,
                                            action_user:action_user,
                                            status_sent_money:status_sent_money,
                                            approve_status:approve_status,
                                            viewcondition:viewcondition,
                                          },
                                          success:function(data)
                                          {
                                            // // // console.log(data);
                                            $(".div_SumCostActionUser").html(data);

                                          }
                                        });
                               // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                                        $.ajax({
                                          url: " {{ url('backend/getPV_Amount') }} ",
                                          method: "post",
                                          data: {
                                            "_token": "{{ csrf_token() }}",
                                            startDate:startDate,
                                            endDate:endDate,
                                            purchase_type_id_fk:purchase_type_id_fk,
                                            customer_username:customer_username,
                                            customer_name:customer_name,
                                            invoice_code:invoice_code,
                                            action_user:action_user,
                                            status_sent_money:status_sent_money,
                                            approve_status:approve_status,
                                            viewcondition:viewcondition,
                                          },
                                          success:function(data)
                                          {
                                            // // console.log(data);
                                            $(".div_PV_Amount").html(data);

                                          }
                                        });
                               // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                              setTimeout(function(){
                                 $(".myloading").hide();
                              }, 1500);
                          });


// ###################################
                  }

        });
    });


</script>

 <script>

   $(document).ready(function() {

           $(document).on('click', '.btnSearchSub', function(event) {
                var d = $(this).data('attr');
                var v = $("#"+d).val();
                $('.btnSearchTotal').trigger('click');

           });


           $(document).on('change', '#approve_status', function(event) {
                var v = $(this).val();
                if(v==7){
                     $("#startDate").val('');
                     $("#endDate").val('');
                }
                console.log(v);

           });

            $(document).on('click', '.btnSearchTotal', function(event) {
                  event.preventDefault();
                  $('#data-table').DataTable().clear();
                  $(".myloading").show();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var purchase_type_id_fk = $('#purchase_type_id_fk').val();
                  var customer_username = $('#customer_username').val();
                  var customer_name = $('#customer_name').val();
                  var invoice_code = $('#invoice_code').val();
                  var action_user = $('#action_user').val();
                  var status_sent_money = $('#status_sent_money').val();
                  var approve_status = $('#approve_status').val();

                  console.log(startDate+" : "+endDate);
                  console.log(purchase_type_id_fk);
                  console.log(customer_username);
                  console.log(customer_name);
                  console.log(invoice_code);
                  console.log(action_user);
                  console.log(status_sent_money);
                  console.log(approve_status);
                  // return false;
                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
          					var oTable;
          					$(function() {
          					    oTable = $('#data-table').DataTable({
          					        processing: true,
          					        serverSide: true,
          					        scroller: true,
          					        scrollCollapse: true,
          					        scrollX: true,
          					        ordering: true,
          					        paging:   true,
          					        searching: false,
          					        bLengthChange: false ,
          					        destroy: true,
                            iDisplayLength: 25,
                            // iDisplayLength: 35,
          					        ajax: {
          		                        url: '{{ route('backend.frontstore.datatable') }}',
          		                        data :{
          		                              startDate:startDate,
          		                              endDate:endDate,
          		                              purchase_type_id_fk:purchase_type_id_fk,
          		                              customer_username:customer_username,
          		                              customer_name:customer_name,
          		                              invoice_code:invoice_code,
          		                              action_user:action_user,
          		                              status_sent_money:status_sent_money,
          		                              approve_status:approve_status,
          		                            },
          		                          method: 'POST',
          		                        },
          					          columns: [
                                      {data: 'id', title :'ID', className: 'text-center w15'},
                                      {data: 'created_at', title :'<center>วันสร้าง </center>', className: 'text-center w60'},
                                      {data: 'action_user', title :'<center>ผู้สร้าง </center>', className: 'text-center w80'},
                                      {data: 'purchase_type_id_fk',   title :'<center>ประเภท <br> การสั่งซื้อ</center>', className: 'text-center w80 ',render: function(d) {
                                        if(d==1){
                                          return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="ทำคุณสมบัติ"> <i class="fa fa-shopping-basket"></i> </span>';
                                        }else if(d==2){
                                          return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="รักษาคุณสมบัติรายเดือน"> <i class="fa fa-calendar-check-o"></i> </span>';
                                        }else if(d==3){
                                          return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="รักษาคุณสมบัติท่องเที่ยว"> <i class="fa fa-bus"></i> </span>';
                                        }else if(d==4){
                                          return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="เติม Ai-Stockist"> <i class="fas fa-wallet"></i> </span>';
                                        }else if(d==5){
                                          return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="Gift Voucher"> <i class="fa fa-gift"></i> </span>';
                                        }else if(d==6){
                                          return '<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="คอร์สอบรม" > <i class="mdi mdi-account-tie"></i> </span>';
                                        }else{
                                          return '';
                                        }
                                      }},
                                      {data: 'customer_name', title :'<center>ลูกค้า</center>', className: 'text-center'},
                                      {data: 'total_price',   title :'รวม (บาท)', className: 'text-right ',render: function(d) {
                                         if(d){
                                          return '<span style="font-size:16px;font-weight:bold;">'+d+'</span>';
                                         }else{
                                          return '';
                                         }
                                      }},
                                      {data: 'code_order',   title :'<center>รหัสใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                                         if(d){
                                          return '<span class="badge badge-pill badge-soft-primary font-size-16">'+d+'</span>';
                                         }else{
                                          return '';
                                         }
                                      }},
                                      {data: 'pay_type', title :'<center>ประเภท <br> การชำระเงิน </center>', className: 'text-center'},
                                      {data: 'shipping_price',   title :'<center>ค่าขนส่ง</center>', className: 'text-center',render: function(d) {

                                        if(d>0){
                                          return d;
                                        }else{
                                          return '';
                                        }

                                      }},

                                      {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w80 ',render: function(d) {
                                            return '<span class=" font-size-14 " style="color:darkred">'+d+'</span>';
                                      }},
                                      {data: 'status_sent_money',   title :'<center>สถานะ<br>การส่งเงิน</center>', className: 'text-center w80 ',render: function(d) {
                                        if(d==2){
                                            return '<span style="color:green;">Success</span>';
                                        }else if(d==1){
                                            return '<span style="color:black;">In Process</span>';
                                        }else{
                                           return '-';
                                        }
                                      }},

                                      {data: 'id',   title :'ใบเสร็จ', className: 'text-center w50 ',render: function(d) {
                                          return '<center>'
                                          + ' <a href="javascript: void(0);" target=_blank data-id="'+d+'" class="print02" > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
                                      }},

                                      {data: 'id', title :'Tools', className: 'text-center w80'},
                                  ],
                                     "columnDefs": [ {
                                        // "targets": [0,2,6,7,8,9] ,
                                        "targets": [0,11,12] ,
                                        "orderable": false
                                    } ],
                                 rowCallback: function(nRow, aData, dataIndex){

                                                       // var info = $(this).DataTable().page.info();
                                                       // $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                                       // console.log(aData['status']);

                                                        if(aData['total_price']){
                                                          $("td:eq(5)", nRow).html('<span class="tooltip_cost" style="font-size:14px;font-weight:bold;" >'+aData['total_price']+'</span> <span class="ttt" style="z-index: 99999 !important;position: absolute;background-color: beige;display:none;padding:5px;color:black;">'+aData['tooltip_price']+'</span>');
                                                        }

                                                       if(aData['type']!='0'){
                                                          $("td:eq(3)", nRow).html('<span class="badge badge-pill badge-soft-success font-size-16" data-toggle="tooltip" data-placement="right" title="เติม Ai-Stockist"> <i class="fas fa-wallet"></i> </span>');
                                                          // $("td:eq(6)", nRow).html('');
                                                          $("td:eq(8)", nRow).html('');
                                                        }

                                                        if(aData['pay_type']=='ai_cash'){
                                                          $("td:eq(7)", nRow).html('เติม Ai-Cash');
                                                        }

                                                        $("td:eq(4)", nRow).html(aData['customer_name']);



                                                        if(aData['approve_status']==5){

                                                          $('td:last-child', nRow).html('-');

                                                        }else{

                                                                var sPermission = "<?=\Auth::user()->permission?>";
                                                                var sU = sessionStorage.getItem("sU");
                                                                var sD = sessionStorage.getItem("sD");
                                                                var can_cancel_bill = sessionStorage.getItem("can_cancel_bill");
                                                                var can_cancel_bill_across_day = sessionStorage.getItem("can_cancel_bill_across_day");

                                                                if(sPermission==1){
                                                                  sU = 1;
                                                                  sD = 1;
                                                                  can_cancel_bill = 1;
                                                                  can_cancel_bill_across_day = 1;
                                                                }

                                                                if(sU!='1'&&sD!='1'){
                                                                    $('td:last-child', nRow).html('-');
                                                                }else{

                                                                  if(aData['type']!='0'){ // เติม Ai-Cash
                                                                  }else{

                                                                      var str_V = '';

                                                                      str_V = '<a href="{{ URL('backend/frontstore/viewdata') }}/'+aData['id']+'" class="btn btn-sm btn-primary"  ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> ';

                                                                      var str_U = '';
                                                                      if(sU=='1'){
                                                                        str_U = '<a href="{{ route('backend.frontstore.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                                                      }
                                                                      var str_D = '';
                                                                      if(sD=='1'){
                                                                        str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cCancel ccc " data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
                                                                      }

                                                                      var str_V2 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="เบิกสินค้าจากคลัง" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                                                                      var str_V3 = '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="รวมในบิล Packing List แล้ว" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';

                                                                      if(sU!='1' && sD!='1'){
                                                                         $('td:last-child', nRow).html('-');
                                                                      }else{

                                                                        // console.log(aData['code_order']+" : "+aData['status_delivery_packing']);
                                                                        // console.log(aData['code_order']+" : "+aData['status_delivery_02']);

                                                                        if(aData['status_delivery_02']==1){

                                                                             $('td:last-child', nRow).html( str_U + str_V2).addClass('input');

                                                                        }else{

                                                                          if(aData['status_delivery_packing']==1){
                                                                            $('td:last-child', nRow).html( str_U + str_V3).addClass('input');
                                                                          }else{
                                                                            $('td:last-child', nRow).html( str_U + str_D).addClass('input');
                                                                          }

                                                                                // var st2 = '<a href="javascript: void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="อยู่ระหว่างการเบิกสินค้าจากคลัง" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';

                                                                                  // console.log(aData['status_sent_product']+" : "+aData['code_order']);
                                                                                // if(aData['status_sent_product']!="" && aData['status_sent_product']>=2){
                                                                                //  if(aData['status_sent_product']==aData['code_order']){
                                                                                //    $('td:last-child', nRow).html(str_U + st2);
                                                                                //    $("td:eq(9)", nRow).html(aData['status_sent_desc']);
                                                                                //    if(aData['status_sent_product']==6){
                                                                                //     $('td:last-child', nRow).html(str_U + str_D);
                                                                                //    }
                                                                                // }

                                                                        }

                                                                      }



                                                                }


                                                                if(aData['purchase_type_id_fk']==6 && aData['approve_status']>=4){
                                                                   $("td:eq(9)", nRow).html('Success');
                                                                }
                                                           }

                                                        }

                                                          if(aData['approve_status']==0){
                                                            $("td:eq(11)", nRow).html('-');
                                                          }

                                                          if(aData['approve_status']==5){
                                                            $("td:eq(9)", nRow).html('<span class=" font-size-14 " style="color:red;font-weight:bold;">บิลยกเลิก</span>');
                                                            $("td:eq(10)", nRow).html('-');
                                                          }
                                                          if(aData['type']=="เติม Ai-Cash"){
                                                            $("td:eq(11)", nRow).html('-');
                                                            $("td:eq(12)", nRow).html('-');
                                                          }

                                                    }
                                                });
                                                oTable.on( 'draw', function () {
                                                  $('[data-toggle="tooltip"]').tooltip();
                                                });

                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                          $.ajax({
                            url: " {{ url('backend/getSumCostActionUser') }} ",
                            method: "post",
                            data: {
                              "_token": "{{ csrf_token() }}",
                              startDate:startDate,
                              endDate:endDate,
                              purchase_type_id_fk:purchase_type_id_fk,
                              customer_username:customer_username,
                              customer_name:customer_name,
                              invoice_code:invoice_code,
                              action_user:action_user,
                              status_sent_money:status_sent_money,
                              approve_status:approve_status,
                            },
                            success:function(data)
                            {
                              // // // console.log(data);
                              $(".div_SumCostActionUser").html(data);

                            }
                          });
                 // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                          $.ajax({
                            url: " {{ url('backend/getPV_Amount') }} ",
                            method: "post",
                            data: {
                              "_token": "{{ csrf_token() }}",
                              startDate:startDate,
                              endDate:endDate,
                              purchase_type_id_fk:purchase_type_id_fk,
                              customer_username:customer_username,
                              customer_name:customer_name,
                              invoice_code:invoice_code,
                              action_user:action_user,
                              status_sent_money:status_sent_money,
                              approve_status:approve_status,
                            },
                            success:function(data)
                            {
                            //   // console.log(data);
                              $(".div_PV_Amount").html(data);

                            }
                          });
                 // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function(){
                   $(".myloading").hide();
                }, 1500);


            });

        });
        });


</script>

      <script>

      $(document).ready(function() {

              $.ajax({
                  url: " {{ url('backend/getSumCostActionUser') }} ",
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}",

                  },
                  success:function(data)
                  {
                    // // // console.log(data);
                    $(".div_SumCostActionUser").html(data);

                  }
                });


             // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                  $.ajax({
                    url: " {{ url('backend/getPV_Amount') }} ",
                    method: "post",
                    data: {
                      "_token": "{{ csrf_token() }}",
                    },
                    success:function(data)
                    {
                      // console.log(data);
                      $(".div_PV_Amount").html(data);
                    }
                  });
             // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@



         });


    </script>





      <script>
// Clear data in View page
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

      DB::select("TRUNCATE `db_pay_product_receipt_list_cus`;");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");

      DB::select("TRUNCATE `db_pick_pack_requisition_code`;");

      DB::select("TRUNCATE db_pick_warehouse_qrcode;");
      DB::select("TRUNCATE db_stocks_return;");
      DB::select("TRUNCATE db_stock_card;");
      DB::select("TRUNCATE db_stock_card_tmp;");

      DB::select("TRUNCATE customers_addr_sent;");

      // $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      // $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id;
      // $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      // $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id;
      // $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

      // DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      // DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      // DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check002 ; ");
      // DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      // DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare002 ; ");
      // DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");


      DB::select("TRUNCATE `db_orders`;");
      DB::select("TRUNCATE `db_orders_tmp`;");
      DB::select("TRUNCATE `db_order_products_list`;");
      DB::select("TRUNCATE `db_order_products_list_tmp`;");

      DB::select("TRUNCATE `db_order_products_list_giveaway`;");

      DB::select("TRUNCATE `db_delivery` ;");
      DB::select("TRUNCATE `db_delivery_packing` ;");
      DB::select("TRUNCATE `db_delivery_packing_code` ;");
      DB::select("TRUNCATE `db_pick_warehouse_packing_code` ;");
      DB::select("TRUNCATE  db_consignments;");

      DB::select("TRUNCATE `db_sent_money_daily` ;");

      DB::select("TRUNCATE `db_add_ai_cash` ;");
      DB::select("TRUNCATE `payment_slip` ;");

      // เพิ่มเติม
// DB::select(" TRUNCATE temp_cus_ai_cash ; ");
// DB::select(" TRUNCATE temp_db_pay_product_receipt_fifo ; ");
// DB::select(" TRUNCATE temp_db_stocks1 ; ");
// DB::select(" TRUNCATE temp_db_stocks2 ; ");
// DB::select(" TRUNCATE temp_db_stocks5 ; ");
// DB::select(" TRUNCATE temp_db_stocks_amt_template ; ");
// DB::select(" TRUNCATE temp_db_stocks_amt_template_02 ; ");
// DB::select(" TRUNCATE temp_db_stocks_check002_template ; ");
// DB::select(" TRUNCATE temp_db_stocks_check2 ; ");
// DB::select(" TRUNCATE temp_db_stocks_check5 ; ");
// DB::select(" TRUNCATE temp_db_stocks_check_template ; ");
// DB::select(" TRUNCATE temp_db_stocks_compare002_template ; ");
// DB::select(" TRUNCATE temp_db_stocks_compare2 ; ");
// DB::select(" TRUNCATE temp_db_stocks_compare5 ; ");
// DB::select(" TRUNCATE temp_db_stocks_compare_template ; ");
// DB::select(" TRUNCATE temp_db_stocks_from_return1 ; ");
// DB::select(" TRUNCATE temp_db_stocks_from_return5 ; ");
// DB::select(" TRUNCATE temp_ppp_0011 ; ");
// DB::select(" TRUNCATE temp_ppp_0021 ; ");
// DB::select(" TRUNCATE temp_ppp_00221 ; ");
// DB::select(" TRUNCATE temp_ppp_002_template ; ");
// DB::select(" TRUNCATE temp_ppp_0031 ; ");
// DB::select(" TRUNCATE temp_ppp_003_template ; ");
// DB::select(" TRUNCATE temp_ppp_0041 ; ");
// DB::select(" TRUNCATE temp_ppp_004_template ; ");
// DB::select(" TRUNCATE temp_ppp_006_template ; ");
// DB::select(" TRUNCATE temp_ppr_0011 ; ");
// DB::select(" TRUNCATE temp_ppr_0012 ; ");
// DB::select(" TRUNCATE temp_ppr_0015 ; ");
// DB::select(" TRUNCATE temp_ppr_0021 ; ");
// DB::select(" TRUNCATE temp_ppr_0022 ; ");
// DB::select(" TRUNCATE temp_ppr_0025 ; ");
// DB::select(" TRUNCATE temp_ppr_0031 ; ");
// DB::select(" TRUNCATE temp_ppr_0032 ; ");
// DB::select(" TRUNCATE temp_ppr_0035 ; ");
// DB::select(" TRUNCATE temp_ppr_003_template ; ");
// DB::select(" TRUNCATE temp_ppr_0041 ; ");
// DB::select(" TRUNCATE temp_ppr_0042 ; ");
// DB::select(" TRUNCATE temp_ppr_0045 ; ");
// DB::select(" TRUNCATE temp_ppr_004_template ; ");
// DB::select(" TRUNCATE temp_ppr_006_template ; ");

DB::select(" TRUNCATE db_consignments ; ");
DB::select(" TRUNCATE db_consignments_import ; ");

// DB::select(" TRUNCATE db_promotion_code ; ");
// DB::select(" TRUNCATE db_promotion_cus ; ");
// DB::select(" TRUNCATE db_promotion_cus_products ; ");


      // DB::select("TRUNCATE `db_check_money_daily` ;"); // ไม่ได้ใช้แล้ว

      ?>
          <script>
          location.replace( "{{ url('backend/frontstore') }}");
          </script>
          <?php
      }
    ?>


      <script>
// Clear data in View page
      $(document).ready(function() {

      	 $(document).on('click', '.test_clear_sent_money', function(event) {
              location.replace( window.location.href+"?test_clear_sent_money=test_clear_sent_money ");
            });

          $(document).on('click', '.cCancel', function(event) {

            var id = $(this).data('id');
            var approve_status = "{{@$sRow->approve_status}}";
            // alert(approve_status);
            // return false;

              if (!confirm("ยืนยัน ? เพื่อยกเลิกรายการสั่งซื้อที่ระบุ ")){
                  return false;
              }else{
              $.ajax({
                  url: " {{ url('backend/ajaxCancelOrderBackend') }} ",
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
                          title: 'ทำการยกเลิกรายการสั่งซื้อที่ระบุเรียบร้อยแล้ว',
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

    </script>


      <script>
      $(document).ready(function() {

          $(document).on('click', '.print02', function(event) {

              event.preventDefault();

              $(".myloading").show();

              var id = $(this).data('id');

              // // console.log(id);

              setTimeout(function(){
                 window.open("{{ url('backend/frontstore/print_receipt_022') }}"+"/"+id);
                 $(".myloading").hide();
              }, 500);


            });

      });

    </script>


    <?php
    if(isset($_REQUEST['test_clear_sent_money'])){

      DB::select("UPDATE `db_orders` SET `status_sent_money`='0',`sent_money_daily_id_fk`='0' WHERE date(updated_at)=CURDATE();");
      DB::select("TRUNCATE `db_sent_money_daily`;");

      ?>
          <script>
          location.replace( "{{ url('backend/frontstore') }}");
          </script>
          <?php
      }
    ?>


<script type="text/javascript">

   $(document).ready(function(){

      $("#customer_username").select2({
          minimumInputLength: 3,
          allowClear: true,
          placeholder: 'Select',
          ajax: {
          url: " {{ url('backend/ajaxGetCustomerCodeOnly') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
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


<script type="text/javascript">

   $(document).ready(function(){

      $("#customer_name").select2({
          minimumInputLength: 3,
          allowClear: true,
          placeholder: 'Select',
          ajax: {
          url: " {{ url('backend/ajaxGetCustomerNameOnly') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
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


@endsection

