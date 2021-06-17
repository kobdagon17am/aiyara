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
<div class="myloading"></div>
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
                  <div class="col-11">
                  <div class="row">
                    <div class="col-12 d-flex ">
                      
                      <div class="col-md-2 ">
                        <div class="form-group row">
                          <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " >
                            <option value="">Business Location</option>
                             @if(@$sBusiness_location)
                            @foreach(@$sBusiness_location AS $r)
                            <option value="{{$r->id}}">{{$r->txt_desc}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>


                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " >
                            <option value="">สาขา</option>
                          
                          </select>
                        </div>
                      </div>


                      <div class="col-md-2 ">
                        <div class="form-group row">
                          <select id="seller" name="seller" class="form-control select2-templating " >
                            <option value="">พนักงานขาย</option>
                             @if(@$sSeller)
                            @foreach(@$sSeller AS $r)
                            <option value="{{$r->id}}">{{$r->seller_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>

               
                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="status_search" name="status_search" class="form-control select2-templating " >
                            <option value="" >สถานะ</option>
                            <option value="0" >-</option>
                            <option value="1" >In Process</option>
                            <option value="3" >Success</option>
                            <option value="4" >สถานะบิลยกเลิก</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4 d-flex  ">
                         <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม"  />
                         <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด"  />
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;cursor: pointer;" >
                          <i class="bx bx-search font-size-16 align-middle mr-1" style="cursor: pointer;" ></i> ค้น
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

                  <div class="myBorder">
                    <span style="font-weight: bold;"> <i class="bx bx-play"></i> รายการส่งเงินรายวัน </span>
                    <table id="data-table-0001" class="table table-bordered dt-responsive" style="width: 100%;">
                    </table>
                  </div>
                  <!--       <span style="font-weight: bold;"> <i class="bx bx-play"></i> รายการ เติม Ai-Cash </span>
                  <table id="data-table-ai_cash" class="table table-bordered dt-responsive" style="width: 100%;">
                  </table>
                  -->
                  <div class="myBorder">
                    <span style="font-weight: bold;"> <i class="bx bx-play"></i> สรุปยอดขาย </span>
                    <table id="data-table-0002" class="table table-bordered dt-responsive" style="width: 90%;">
                    </table>
                  </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



<div class="modal fade" id="modalOne" tabindex="-1" role="dialog" aria-labelledby="modalOneTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOneTitle"><b><i class="bx bx-play"></i>รายการใบเสร็จ</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <center>
       <div class="modal-body invoice_list " style="font-size: 20px;width: 50% !important;">

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


// @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        // var txtSearch = $("input[name='txtSearch']").val();
        $.fn.dataTable.ext.errMode = 'throw';
        var oTable0002;
        $(function() {
            oTable0002 = $('#data-table-0002').DataTable({
             "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                ordering: false,
                "info":   false,
                "paging": false,
                destroy:true,
                      ajax: {
                          url: '{{ route('backend.check_money_daily02.datatable') }}',
                          type: "POST",
                          // data:{ _token: '{{csrf_token()}}',txtSearch:txtSearch },
                      },
                columns: [
                    {data: 'created_at', title :'<span style="vertical-align: middle;"> วันที่ขาย </span> ', className: 'text-center'},
                    {data: 'business_location', title :'<span style="vertical-align: middle;"> Business Location </span> ', className: 'text-center'},
                    {data: 'branch_name', title :'<span style="vertical-align: middle;"> Branch </span> ', className: 'text-center'},
                    {data: 'action_user', title :'<span style="vertical-align: middle;"> พนักงานขาย </span> ', className: 'text-center'},
                    {data: 'total_money', title :'<span style="vertical-align: middle;"> ยอดขาย </span> ', className: 'text-right'},
                  
                ],
                rowCallback: function(nRow, aData, dataIndex){

                  if(aData['remark']==2){
                    for (var i = 0; i < 3; i++) {
                          $('td:eq( '+i+')', nRow).html("");
                    }
                    $('td:eq(3)', nRow).html('<span style="color:black;font-size:16px;font-weight:bold;background-color:#e6ffe6;text-align:right;">รวมทั้งสิ้น</span>');
                    $('td:eq(4)', nRow).html('<span style="color:black;font-size:16px;font-weight:bold;background-color:#e6ffe6;text-align:right;">'+aData['total_money']+'</span>');
                  }

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
       $('#business_location_id_fk').change(function(){

          $(".myloading").show();
          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ", 
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                    $(".myloading").hide();
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                   }
                  }
                })
           }else{
            var layout = '<option value="" selected>สาขา</option>';
            $('#branch_id_fk').html(layout);
            $(".myloading").hide();
           }
 
      });
    </script>

@endsection

