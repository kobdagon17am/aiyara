@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

                       <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">@lang('message.DASHBOARD')</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item active" style="color:black;font-size: 16px;font-weight: bold;">@lang('message.COMPANY')</li>
                                        </ol>
                                    </div>

                                </div>
                                
                                
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card overflow-hidden">
                                    <div class="bg-soft-primary">
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="text-primary p-3">
                                                    <h5 class="text-primary">Welcome To</h5>
                                                    <p>AiYara Dashboard</p>
                                                </div>
                                            </div>
                                            <div class="col-5 align-self-end">
                                                <img src="backend/images/profile-img.png" alt="" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="avatar-md profile-user-wid mb-4">
                                                     <img src="backend/images/users/ex.png" alt="" class="img-thumbnail rounded-circle"> 
                                                </div>
                                            </div>

                                            <div class="col-sm-8">
                                                <div class="pt-4">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
             
                            </div>

                            <div class="col-xl-8">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-18">ฝ่ายขาย</span></p>
                                                        <!-- <h4 class="mb-0">1,235</h4> -->
                                                    </div>

                                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                        <span class="avatar-title">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-18">ฝ่ายบัญชี</span></p>
                                                        <!-- <h4 class="mb-0">35,723</h4> -->
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-archive-in font-size-24"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-18">บริการลูกค้า</span></p>
                                                        <!-- <h4 class="mb-0">16,000</h4> -->
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-18">คลังสินค้า</span></p>
                                                        <!-- <h4 class="mb-0"> 10,000 </h4> -->
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-18">Administrator</span></p>
                                                        <!-- <h4 class="mb-0"> 35,555 </h4> -->
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-18">Others</span></p>
                                                        <!-- <h4 class="mb-0"> 2,2234 </h4> -->
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- end row -->
                            </div>
                        </div>
                        <!-- end row -->

<div class="myBorder">   

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4"> @lang('message.CHECKNOTIFY') </h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
                                        <div class="col-12 text-right" >
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('backend.stock_notify.index') }}">
                                              View Details
                                            </a>
                                          </div>
                                    </thead>
                                    <tbody>
                                        
                                        <table id="data-table-notify" class="table table-bordered dt-responsive" style="width: 100%;">
                                        </table>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
</div>          


<div class="myBorder">   

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">CRM Transaction</h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
                                        <div class="col-12 text-right" >
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('backend.crm.index') }}">
                                              View Details
                                            </a>
                                          </div>
                                    </thead>
                                    <tbody>
                                        
                                        <table id="data-table-crm" class="table table-bordered dt-responsive" style="width: 100%;">
                                        </table>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
</div>          

    <div class="myBorder">   

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">PM Transaction</h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
                                         <div class="col-12 text-right" >
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('backend.pm.index') }}">
                                              View Details
                                            </a>
                                          </div>
                                    </thead>
                                    <tbody>
                                        
                                        <table id="data-table-pm" class="table table-bordered dt-responsive" style="width: 100%;">
                                        </table>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
</div>          
                   

@endsection

@section('script')

<script>

        var oTableNotify;
        $(function() {
            oTableNotify = $('#data-table-notify').DataTable({
            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: ''+($(window).height()-370)+'px',
                iDisplayLength: 15,
                ajax: {
                  url: '{{ route('backend.stock_notify_dashboard.datatable') }}',
                   data: function ( d ) {
                    oData = d;
                  },
                   method: 'POST',
                 },

                columns: [
                    {data: 'id', title :'ID', className: 'text-center w50'},
                    {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w240 '},
                    {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-center'},
                    {data: 'amt', title :'<center>จำนวนคงคลังล่าสุด</center>', className: 'text-center w100 '},
                    {data: 'amt_less', title :'<center>จำนวนไม่ต่ำกว่า (ชิ้น)</center>', className: 'text-center w100 ',render: function(d) {
                      // if(d==0){
                      //   return '* ยังไม่ได้กำหนด';
                      // }else{
                        return d;
                      // }
                    }},
                    {data: 'id', title :'<center>Note 1</center>', className: 'text-center ',render: function(d) {
                      // if(d==0){
                      //   return '* ยังไม่ได้กำหนด';
                      // }else{
                        return '';
                      // }
                    }},
                    {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                    {data: 'amt_day_before_expired', title :'<center>แจ้งเตือนก่อนวันหมดอายุ (วัน)</center>', className: 'text-center w100 ',render: function(d) {
                      // if(d==0){
                      //   return '* ยังไม่ได้กำหนด';
                      // }else{
                        return d;
                      // }
                    }},   
                    {data: 'id', title :'<center>Note 2</center>', className: 'text-center ',render: function(d) {
                      // if(d==0){
                      //   return '* ยังไม่ได้กำหนด';
                      // }else{
                        return '';
                      // }
                    }},         
                    // {data: 'updated_at', title :'<center>Last updated </center>', className: 'text-center w100 '},
                    // {data: 'diff_d', title :'<center>Last updated </center>', className: 'text-center '},
                ],
                rowCallback: function(nRow, aData, dataIndex){

                  var info = $(this).DataTable().page.info();
                  $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                  $('td:eq(6)', nRow).html(aData['lot_expired_date']+" <span style='color:blue;'> ("+aData['diff_d']+") </span> ");

                  if(aData['amt_less']){

                    $('td:eq(5)', nRow).html(aData['amt']-aData['amt_less']);

                    if( aData['amt']-aData['amt_less'] > 0){
                       $('td:eq(5)', nRow).html('<span class="badge badge-pill badge-soft-success font-size-16">'+(aData['amt']-aData['amt_less'])+'</span>');
                    }else if( aData['amt']-aData['amt_less'] == 0){
                        $('td:eq(5)', nRow).html('');
                    }else{
                       $('td:eq(5)', nRow).html(aData[5]).css({'color':'red'});
                    }


                  }
                  if(aData['amt_day_before_expired']){
                    // var start = new Date(aData['lot_expired_date']),  
                    //     end   = new Date(),
                    //     diff  = new Date(start - end),
                    //     days  = diff/1000/60/60/24;
                    //     dd  =  (days) - aData['amt_day_before_expired'];
                    $('td:eq(8)', nRow).html(Math.trunc(aData['diff_d02']));

                    if(aData['diff_d02']>0){
                       $('td:eq(8)', nRow).html('<span class="badge badge-pill badge-soft-success font-size-16">'+aData['diff_d02']+'</span>');
                    }else if(aData['diff_d02']==0){
                        $('td:eq(8)', nRow).html('');
                    }else{
                       $('td:eq(8)', nRow).html(aData[6]).css({'color':'red'});
                    }

                  }

             

                }
            });

        });

var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(role_group_id);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var oTable;
$(function() {
    oTable = $('#data-table-crm').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.crm.datatable') }}',
          data: function ( d ) {
            d.Where={};
            // $('.myWhere').each(function() {
              // if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
              //   d.Where[$(this).attr('name')] = $.trim($(this).val());
              // }
              d.Where['role_group_id_fk'] = role_group_id ;
            // });
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
            {data: 'role_name', title :'<center>แผนก </center>', className: 'text-center'},
            // {data: 'subject_receipt_number', title :'<center>เลขใบรับเรื่อง </center>', className: 'text-left'},
            {data: 'receipt_date', title :'<center>วันที่-เวลา รับเรื่อง </center>', className: 'text-center'},
            {data: 'topics_reported', title :'<center>หัวข้อที่ลูกค้าแจ้ง </center>', className: 'text-center'},
            {data: 'recipient_name', title :'<center>ผู้รับเรื่อง </center>', className: 'text-center'},
            {data: 'operator_name', title :'<center>ผู้ดำเนินการ </center>', className: 'text-center'},
            {data: 'last_update', title :'<center>วันที่-เวลา อัพเดตล่าสุด </center>', className: 'text-center'},
            {data: 'status_close_job',   title :'<center>Status</center>', className: 'text-center w150 ',render: function(d) {
               return d==1?'<span class="badge badge-pill badge-soft-success font-size-16">ปิดจ๊อบ</span>':'<span class="badge badge-pill badge-soft-danger font-size-16">Pending</span>';
            }},
        ],
        rowCallback: function(nRow, aData, dataIndex){
 
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>



<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var oTable;
$(function() {
    oTable = $('#data-table-pm').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.pm.datatable') }}',
          data: function ( d ) {
            d.Where={};
            // $('.myWhere').each(function() {
            //   if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
            //     d.Where[$(this).attr('name')] = $.trim($(this).val());
            //   }
            // });
            d.Where['role_group_id_fk'] = role_group_id ;
            
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
            {data: 'role_name', title :'<center>แผนก </center>', className: 'text-left'},
            {data: 'receipt_date', title :'<center>วันที่รับเรื่อง </center>', className: 'text-left'},
            {data: 'topics_question', title :'<center>หัวข้อที่ลูกค้าสอบถามเข้ามา </center>', className: 'text-left'},
            // {data: 'details_question', title :'<center>รายละเอียดคำถาม </center>', className: 'text-left'},
            {data: 'txt_answers',   title :'<center>คำตอบ</center>', className: 'text-center font-size-16 th_ ',render: function(d) {
                           return '<span class="badge badge-pill badge-primary font-size-14">'+d+'</span>';
                        }},
            {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
                           return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
            }},

            {data: 'recipient_name', title :'<center>ผู้รับเรื่อง </center>', className: 'text-left'},
            {data: 'operator_name', title :'<center>ผู้ดำเนินการ </center>', className: 'text-left'},
            {data: 'last_update', title :'<center>วันที่-เวลา อัพเดตล่าสุด </center>', className: 'text-left'},
            {data: 'status_close_job',   title :'<center>Status</center>', className: 'text-center w150 ',render: function(d) {
               return d==1?'<span class="badge badge-pill badge-soft-success font-size-16">ปิดจ๊อบ</span>':'<span class="badge badge-pill badge-soft-danger font-size-16">Pending</span>';
            }},
        ],
        rowCallback: function(nRow, aData, dataIndex){

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>



@endsection



