@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}


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

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ตรวจสอบรับเงินรายวัน </h4>
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
      $can_sentmoney = '1';
      $can_getmoney = '1';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $can_sentmoney = @$menu_permit->can_sentmoney==1?'1':'0';
      $can_getmoney = @$menu_permit->can_getmoney==1?'1':'0';
    }
   ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-8">
                  <div class="row">
                    <div class="col-12 d-flex ">
                      <div class="col-md-3 ">
                        <div class="form-group row">
                          <select id="branch_id_search" name="branch_id_search" class="form-control select2-templating " >
                            <option value="">สาขา</option>
                            @if(@$sBranchs)
                            @foreach(@$sBranchs AS $r)
                            <option value="{{$r->id}}"  >
                              {{$r->b_name}}
                            </option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="warehouse_id_search" name="warehouse_id_search" class="form-control select2-templating "  >
                            <option disabled selected >(คลัง) กรุณาเลือกสาขาก่อน</option>
                          </select>
                        </div>
                      </div>
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
                      <div class="col-md-4 d-flex  ">
                         <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม"  />
                         <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด"  />
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;" >
                          <i class="bx bx-search font-size-16 align-middle mr-1"></i> ค้น
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>


                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}" >
                 <!--    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.check_money_daily.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                    </a> -->
                  </div>

                </div>

                
                <span style="font-weight: bold;"> <i class="bx bx-play"></i> รายการส่งเงินรายวัน </span>
                <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>


          <!--       <span style="font-weight: bold;"> <i class="bx bx-play"></i> รายการ เติม Ai-Cash </span>
                <table id="data-table-ai_cash" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>
 -->

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script>

  function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
}

// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        // var txtSearch = $("input[name='txtSearch']").val();
        $.fn.dataTable.ext.errMode = 'throw';
        var oTable0001;
        $(function() {
            oTable0001 = $('#data-table-0001').DataTable({
             "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                ordering: false,
                "info":   false,
                "paging": false,
                destroy:true,
                      ajax: {
                          url: '{{ route('backend.check_money_daily.datatable') }}',
                          type: "POST",
                          // data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                      },
                columns: [
                    {data: 'column_001', title :'<span style="vertical-align: middle;"> ผู้ส่ง </span> ', className: 'text-center'},
                    {data: 'column_002', title :'<span style="vertical-align: middle;"> ครั้งที่ส่ง </span> ', className: 'text-center'},
                    {data: 'column_003', title :'<span style="vertical-align: middle;"> รายการใบเสร็จ </span> ', className: 'text-center'},
                    {data: 'column_004', title :'<span style="vertical-align: middle;"> วันเวลาที่ส่ง </span> ', className: 'text-center'},
                    {data: 'column_005', title :'<span style="vertical-align: middle;">รวมรายการชำระค่าสินค้า </span> ', className: 'text-center'},
                   {data: 'column_007', title :'<span style="vertical-align: middle;">สถานะ </span> ', className: 'text-center'},
                   {data: 'column_006',   title :'<center>Tools</center>', className: 'text-center w100 ',render: function(d) {
                        return '<a style="'+d+'" href="{{ route('backend.check_money_daily.index') }}/'+d+'/edit?fromFrontstore" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                    }},
                ],
                rowCallback: function(nRow, aData, dataIndex){



                }
            });
       
        });
// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@


var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";
var oTable;
$(function() {
    oTable = $('#data-table-002').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.check_money_daily02.datatable') }}',
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
            {data: 'action_user_name', title :'<center>พนักงานขาย </center>', className: 'text-left'},
            {data: 'cash_pay', title :'<center>เงินสด </center>', className: 'text-center'},
            {data: 'aicash_price', title :'<center>Ai-cash </center>', className: 'text-center'},
            {data: 'transfer_price', title :'<center>เงินโอน </center>', className: 'text-center'},
            {data: 'credit_price', title :'<center>เครดิต </center>', className: 'text-center'},
            {data: 'fee_amt', title :'<center>ค่าธรรมเนียม </center>', className: 'text-center'},
            {data: 'shipping_price', title :'<center>ค่าขนส่ง </center>', className: 'text-center'},
            {data: 'total_price', title :'<center>รวมทั้งสิ้น </center>', className: 'text-center'},
            {data: 'approve_status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
              if(d=="รออนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
              }else if(d=="ไม่อนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">'+d+'</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
              }
            }},
            {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        "columnDefs": [
            {
                "render": function (data, type, row) {
                     return commaSeparateNumber(data);
                },
                "targets": [2,3,4,5,6,7,8]
            },
        ],
        rowCallback: function(nRow, aData, dataIndex){

          var info = $(this).DataTable().page.info();
          $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

            $('td:last-child', nRow).html('-');

          $('td:last-child', nRow).html(''
            + '<a href="{{ route('backend.check_money_daily.index') }}/'+aData['id']+'/edit?fromFrontstore" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
          
          ).addClass('input');
//   + '<a href="javascript: void(0);" data-url="{{ route('backend.check_money_daily.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});



// var sU = "{{@$sU}}"; 
// var sD = "{{@$sD}}";  
// var oTable;
// $(function() {
//     oTable = $('#data-table-ai_cash').DataTable({
//     "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
//         processing: true,
//         serverSide: true,
//         scroller: true,
//         scrollCollapse: true,
//         scrollX: true,
//         ordering: false,
//         // scrollY: ''+($(window).height()-370)+'px',
//         iDisplayLength: 25,
//         ajax: {
//           url: '{{ route('backend.add_ai_cash.datatable') }}',
//           data: function ( d ) {
//             d.Where={};
//             $('.myWhere').each(function() {
//               if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
//                 d.Where[$(this).attr('name')] = $.trim($(this).val());
//               }
//             });
//             d.Like={};
//             $('.myLike').each(function() {
//               if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
//                 d.Like[$(this).attr('name')] = $.trim($(this).val());
//               }
//             });
//             d.Custom={};
//             $('.myCustom').each(function() {
//               if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
//                 d.Custom[$(this).attr('name')] = $.trim($(this).val());
//               }
//             });
//             oData = d;
//           },
//           method: 'POST'
//         },

//         columns: [
//             {data: 'id', title :'ID', className: 'text-center w50'},
//             {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left'},
//             {data: 'aicash_remain', title :'<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>', className: 'text-center'},
//             {data: 'aicash_amt', title :'<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>', className: 'text-center'},
//             {data: 'action_user', title :'<center>พนักงาน <br> ที่ดำเนินการ </center>', className: 'text-center'},
//             {data: 'pay_type_id_fk', title :'<center>รูปแบบการชำระเงิน </center>', className: 'text-center'},
//             {data: 'total_amt', title :'<center>ยอดชำระเงิน </center>', className: 'text-center'},
//              {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
//               if(d=="รออนุมัติ"){
//                   return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
//               }else if(d=="ไม่อนุมัติ"){
//                   return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">'+d+'</span>';
//               }else{
//                   return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
//               }
//             }},
//             {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
//             // {data: 'aicash_amt',   title :'ยอด Ai-Cash ', className: 'text-center ',render: function(d) {
//             //     return (parseFloat(d)>0)?d:'-';
//             // }},
//             {data: 'id', title :'Tools', className: 'text-center w60'}, 
//         ],
//         rowCallback: function(nRow, aData, dataIndex){

//           var info = $(this).DataTable().page.info();
//           $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

//           if(aData['approve_status']==4){
//             for (var i = 0; i < 6; i++) {
//               $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#d9d9d9','text-decoration':'line-through','font-style':'italic'});
//             }

//             $('td:last-child', nRow).html('-ยกเลิก-');

//           }else{

//                  if(sU!=''&&sD!=''){
//                           $('td:last-child', nRow).html('-');
//                       }else{ 

//                       $('td:last-child', nRow).html(''
//                         + '<a href="{{ route('backend.check_money_daily.index') }}/'+aData['id']+'/edit?fromAicash" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
//                       ).addClass('input');

//                     }

//           }

     

//         }
//     });
//     $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
//       oTable.draw();
//     });
// });




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

