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


<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> จำหน่ายสินค้าหน้าร้าน   </h4>
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
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
   ?>


<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
              <div class="divTH">
                <label for="startDate" >วันสร้างเริ่มต้น : </label>
              </div>
                <?php 
                         $sd = date('d/m/Y');
                         // echo $sd;
                      ?>
              <div class="divTableCell">
                <input id="startDate" class="form-control" autocomplete="off" value="{{ @$sd }}" />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="endDate" >วันสร้างสิ้นสุด : </label>
              </div>
              <div class="divTableCell">
                <input id="endDate" class="form-control" autocomplete="off" value="{{ @$sd }}" />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >ประเภทการสั่งซื้อ : </label>
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
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            </div>
            <div class="divTableRow">
              <div class="divTH">
                <label for="" >รหัสลูกค้า : </label>
              </div>
              <div class="divTableCell">
                <input class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >ชื่อลูกค้า : </label>
              </div>
              <div class="divTableCell">
                <input class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >เลขที่ใบสั่งซื้อ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <input class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            </div>
            <div class="divTableRow">

                  <div class="divTH">
                <label for="" >เลขที่ใบเสร็จ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <input class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>

              <div class="divTH">
                <label for="" >ผู้สร้าง : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select name="" class="form-control select2-templating "  >
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
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >สถานะ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select name="" class="form-control select2-templating "  >
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
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            
            </div>
            <div class="divTableRow">
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-warning" style="color:black;float: right;"><i class="bx bx-search font-size-18 align-middle "></i> ค้นหา</button>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-info" style="float: left;"><i class="fa fa-refresh font-size-18 align-middle "></i></button>
              </div>
            </div>
          </div>
        </div>
        <!-- DivTable.com -->
      </div>
    </div>
  </div>
</div>

   

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
              <div class="divTableCell" style="text-align: right;width: 50%;" >&nbsp; </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success" ><i class="bx bx-search font-size-18 align-middle "></i> ดูทั้งหมด</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success" ><i class="bx bx-search font-size-18 align-middle "></i> เฉพาะซื้อแบบปกติ</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success" ><i class="bx bx-search font-size-18 align-middle "></i> เฉพาะซื้อแบบใช้ Voucher</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                    <a  href="{{ route('backend.frontstore.create') }}">
                <button type="button" class="btn btn-success btnAdd " ><i class="fa fa-plus font-size-18 align-middle "></i> เพิ่ม</button>
                 </a>
              </div>
            </div>
          </div>
        </div>
        <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
        </table>
        <div class="row">
          <div class="col-lg-5">
            <div class="card">
              <div class="card-body">
                
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    
                    <thead>
                      <tr style="background-color: #f2f2f2;text-align: right;">
                        <th></th>
                        <th>รายการ</th>
                        <th>PV</th>
                        <th>จำนวนเงิน</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr style="color: red" >
                        <th scope="row">สถานะ > รอตรวจสอบ / รอชำระ /<br> รออนุมัติ</th>
                        <td style="text-align: right;">{{@$approve_status_2}}</td>
                        <td style="text-align: right;">{{@$pv_2}}</td>
                        <td style="text-align: right;">{{@$sum_price_2}}</td>
                      </tr>
                      <tr>
                        <th scope="row">สถานะ สำเร็จ</th>
                        <td style="text-align: right;">{{@$approve_status_9}}</td>
                        <td style="text-align: right;">{{@$pv_9}}</td>
                        <td style="text-align: right;">{{@$sum_price_9}}</td>
                      </tr>
                      <tr>
                        <th scope="row">สถานะ ยกเลิก</th>
                        <td style="text-align: right;">{{@$approve_status_4}}</td>
                        <td style="text-align: right;">{{@$pv_4}}</td>
                        <td style="text-align: right;">{{@$sum_price_4}}</td>
                      </tr>
                      <tr>
                        <th scope="row">สถานะ อื่นๆ <br>(ยกเว้น 3 สถานะข้างบน) </th>
                        <td style="text-align: right;">{{@$approve_status_88}}</td>
                        <td style="text-align: right;">{{@$pv_88}}</td>
                        <td style="text-align: right;">{{@$sum_price_88}}</td>
                      </tr>

                      <tr>
                        <th scope="row">รวมทั้งหมด</th>
                        <td style="text-align: right;">{{@$approve_status_total}}</td>
                        <td style="text-align: right;">{{@$pv_total}}</td>
                        <td style="text-align: right;">{{@$sum_price_total}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
              </div>
            </div>
          </div>


          <div class="col-lg-7">
            <div class="card">
              <div class="card-body">
                
                <div class="table-responsive">
                  <table class="table table-sm m-0">
                    <thead>
                      <tr style="background-color: #f2f2f2;"><th colspan="8">
                      <?php 
                         $sd = date('d/m/Y');
                         // echo $sd;
                      ?>
                        รวมรายการชำระค่าสินค้า {{$sd}} - {{$sd}} 
                      </th></tr>
                      <tr>
                        <th>พนักงานขาย</th>
                        <th class="text-right">เงินสด</th>
                        <th class="text-right">Ai-cash</th>
                        <th class="text-right">เงินโอน</th>
                        <th class="text-right">เครดิต</th>
                        <th class="text-right">ค่าธรรมเนียม</th>
                        <th class="text-right">ค่าขนส่ง</th>
                        <th class="text-right">รวมทั้งสิ้น</th>
                      </tr>
                    </thead>

                        <tbody>
                          
                          @IF(@$sDBFrontstoreSumCostActionUser)
                              @foreach(@$sDBFrontstoreSumCostActionUser AS $r)
                              @php
                              @$cnt_row1 += 1;
                              @$sum_cash_pay += $r->cash_pay;
                              @$sum_aicash_price += $r->aicash_price;
                              @$sum_transfer_price += $r->transfer_price;
                              @$sum_credit_price += $r->credit_price;
                              @$sum_shipping_price += $r->shipping_price;
                              @$sum_fee_amt += $r->fee_amt;
                              @$sum_total_price += $r->total_price;
                              @endphp
                              <tr>
                                <td>{{$r->action_user_name}}</td>
                                <td class="text-right"> {{number_format($r->cash_pay,2)}} </td>
                                <td class="text-right"> {{number_format($r->aicash_price,2)}} </td>
                                <td class="text-right"> {{number_format($r->transfer_price,2)}} </td>
                                <td class="text-right"> {{number_format($r->credit_price,2)}} </td>
                                <td class="text-right"> {{number_format($r->fee_amt,2)}} </td>
                                <td class="text-right"> {{number_format($r->shipping_price,2)}} </td>
                                <td class="text-right"> {{number_format($r->total_price,2)}} </td>
                              </tr>
                              @endforeach
                          @ENDIF
                          @IF(@$cnt_row1>1)
                          <tr>
                            <th>Total > </th>
                            <th class="text-right"> {{number_format(@$sum_cash_pay,2)}} </th>
                            <th class="text-right"> {{number_format(@$sum_aicash_price,2)}} </th>
                            <th class="text-right"> {{number_format(@$sum_transfer_price,2)}} </th>
                            <th class="text-right"> {{number_format(@$sum_credit_price,2)}} </th>
                            <th class="text-right"> {{number_format(@$sum_shipping_price,2)}} </th>
                            <th class="text-right"> {{number_format(@$sum_fee_amt,2)}} </th>
                            <th class="text-right"> {{number_format(@$sum_total_price,2)}} </th>
                          </tr>
                          @ENDIF
                          
                        </tbody>
                  </table>
                  
                </div>
                <br>

                 <div class="table-responsive">
                  <table class="table table-sm m-0">
                    <thead>
                      <tr style="background-color: #f2f2f2;"><th colspan="8">
                        รายการ เติม Ai-Cash {{$sd}} - {{$sd}} 
                      </th></tr>
                      <tr>
                        <th>พนักงานขาย</th>
                        <th class="text-right">รายการ</th>
                        <th class="text-right">รวมทั้งสิ้น</th>
                      </tr>
                    </thead>

                        <tbody>
                          
                          @IF(@$sDBFrontstoreUserAddAiCash)
                              @foreach(@$sDBFrontstoreUserAddAiCash AS $r)
                              @php
                                @$cnt_row2 += 1;
                                @$cnt_aicash_amt += 1;
                                @$sum_cnt += $r->cnt;
                                @$sum_sum += $r->sum;
                              @endphp
                              <tr>
                                <td>{{$r->name}}</td>
                                <td class="text-right"> {{@$r->cnt}} </td>
                                <td class="text-right"> {{number_format($r->sum,2)}} </td>
                              </tr>
                              @endforeach
                          @ENDIF
                          @IF(@$cnt_row2>1)
                          <tr>
                            <th>Total > </th>
                            <th class="text-right"> {{@$sum_cnt}} </th>
                            <th class="text-right"> {{number_format(@$sum_sum,2)}} </th>
                          </tr>
                          @ENDIF
                          
                        </tbody>
                  </table>
                  
                </div>


              </div>
            </div>
          </div>

 <?php 

      for ($i=1;$i<=5;$i++){
        $x = $i."test";
        ?> <span @popper({{$x}})> I'm a Span </span> <br><?php
      }

   ?>

        </div>
      </div>
    </div>
    </div> <!-- end col -->
    </div> <!-- end row -->
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

var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
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
        ajax: {
          url: '{{ route('backend.frontstore.datatable') }}',
          data: function ( d ) {
            oData = d;
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w15'},
            {data: 'action_date', title :'<center>วันสร้าง </center>', className: 'text-center'},
            // {data: 'purchase_type',   title :'<center>ประเภทการสั่งซื้อ</center>', className: 'text-center ',render: function(d) {
            //     return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
            // }},
/*
ทำคุณสมบัติ  <i class="fa fa-shopping-basket"></i>
รักษาคุณสมบัติรายเดือน  <i class="fa fa-calendar-check-o"></i>
รักษาคุณสมบัติท่องเที่ยว <i class="fa fa-bus"></i>
เติม Ai-Stockist <i class="ti-wallet "></i>
Gift Voucher  <i class="fa fa-gift"></i>
*/
            {data: 'purchase_type_id_fk',   title :'<center>ประเภท <br> การสั่งซื้อ</center>', className: 'text-center ',render: function(d) {
              if(d==1){
                return '<span class="badge badge-pill badge-soft-success font-size-16"> <i class="fa fa-shopping-basket"></i> </span>';
              }else if(d==2){
                return '<span class="badge badge-pill badge-soft-success font-size-16"> <i class="fa fa-calendar-check-o"></i> </span>';
              }else if(d==3){
                return '<span class="badge badge-pill badge-soft-success font-size-16"> <i class="fa fa-bus"></i> </span>';        
              }else if(d==4){
                return '<span class="badge badge-pill badge-soft-success font-size-16"> <i class="fas fa-wallet"></i> </span>';      
              }else if(d==5){
                return '<span class="badge badge-pill badge-soft-success font-size-16"> <i class="fa fa-gift"></i> </span>';                                    
              }else{ 
                return '';
              }
            }},
            {data: 'customers_id_fk', title :'<center>ลูกค้า </center>', className: 'text-center'},
            {data: 'total_price',   title :'<center>รวม (บาท) </center>', className: 'text-center ',render: function(d) {
                return d;
                // return '<span class="tooltip_cost badge badge-pill badge-info font-size-14">'+d+'</span> <span class="ttt" style="z-index: 99999 !important;position: absolute;background-color: beige;display:none;padding:5px;color:black;"> เงินสด : 9,999.00 , เงินโอน : 9,999.00 + ค่าธรรมเนียม : 100</span>' ;
            }},
            {data: 'invoice_code',   title :'<center>รหัสใบเสร็จ</center>', className: 'text-center ',render: function(d) {
               if(d){
                return '<span class="badge badge-pill badge-soft-primary font-size-16">'+d+'</span>';
               }else{
                return '';
               }
            }},
            {data: 'pay_type', title :'<center>ประเภท <br> การชำระเงิน </center>', className: 'text-center'},
            // {data: 'shipping_price', title :'<center>ค่าขนส่ง</center>', className: 'text-center'},

            {data: 'shipping_price',   title :'<center>ค่าขนส่ง</center>', className: 'text-center',render: function(d) {

              if(d>0){
                return d;
              }else{
                return '';
              }

            }},

            {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
              if(d=="รออนุมัติ"){
                  return '<span class=" badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
              }
            }},
            {data: 'id',   title :'ใบเสร็จ', className: 'text-center w80 ',render: function(d) {
                return '<center> <a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a> '
                + ' <a href="{{ URL::to('backend/frontstore/print_receipt_02') }}/'+d+'" target=_blank > <i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a> </center>';
            }},
     
            {data: 'id', title :'Tools', className: 'text-center w70'}, 
        ],
           // "order": [ [ 1, 'desc' ] ],
           "columnDefs": [ {
            // { targets: 'no-sort', orderable: false }
              "targets": [0,2,6,7,8,9] ,
              "orderable": false
          } ],
        rowCallback: function(nRow, aData, dataIndex){

            if(aData['total_price']){

              $("td:eq(4)", nRow).html('<span class="tooltip_cost badge badge-pill badge-info font-size-14">'+aData['total_price']+'</span> <span class="ttt" style="z-index: 99999 !important;position: absolute;background-color: beige;display:none;padding:5px;color:black;">'+aData['tooltip_price']+'</span>');
            }

           if(aData['type']!='0'){
              $("td:eq(2)", nRow).html('<span class="badge badge-pill badge-soft-success font-size-16"> <i class="fas fa-wallet"></i> </span>');
              $("td:eq(5)", nRow).html('');
              $("td:eq(7)", nRow).html('');
              $("td:eq(9)", nRow).html('<center><a href="{{ URL::to('backend/add_ai_cash/print_receipt') }}/'+aData['id']+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a></center>');
              $("td:eq(10)", nRow).html('');
            }

            if(aData['pay_type']=='ai_cash'){
              $("td:eq(6)", nRow).html('เติม Ai-Cash');
            }

		      	$("td:eq(3)", nRow).html(aData['customer_name']);

            var info = $(this).DataTable().page.info();
            $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

        if(aData['approve_status']==4){$('td:last-child', nRow).html('');}else{

	          if(sU!=''&&sD!=''){
	              $('td:last-child', nRow).html('-');
	          }else{ 

              if(aData['type']!='0'){ // เติม Ai-Cash

              }else{

                $('td:last-child', nRow).html(''
                  + '<a href="{{ route('backend.frontstore.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                  + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                  
                ).addClass('input');

            }

             // $('td:last-child', nRow).html(aData['approve_status']);

           
          }
          }



        }
    });

// + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'

});
</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // setDate: today,
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });

        // $("#startDate").datepicker().datepicker("setDate", new Date());

        $('#endDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#startDate').val();
            }
        });


        $(document).ready(function() {

          localStorage.clear();

            // $(document).on('click', '.btnAdd', function(event) {
            //   localStorage.clear();
            // });

        });


</script>


@endsection

