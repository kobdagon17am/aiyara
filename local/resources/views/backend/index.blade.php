@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

@php ($page = "firstpage")

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
    <?php

            // $PromotionCode = \App\Models\Backend\PromotionCode::get();
            // if(@$PromotionCode){
                // foreach ($PromotionCode as $key => $value) {

                //     if($value->pro_edate < date("Y-m-d")){
                //         // echo $value->pro_edate;
                //         DB::select(" UPDATE `db_promotion_code` SET `status`='0' WHERE ( `pro_edate` < CURDATE() )  ");
                //         DB::select(" UPDATE
                //         db_promotion_cus
                //         Inner Join db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
                //         SET
                //         db_promotion_cus.pro_status=3
                //         WHERE
                //         date(db_promotion_code.pro_edate) < curdate()  ");
                //     }
                // }
            // }

    ?>
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
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-15">ฝ่ายขาย</span></p>
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
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-15">ฝ่ายบัญชี</span></p>
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
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-15">บริการลูกค้า</span></p>
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
                                                        <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-15">คลังสินค้า</span></p>
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
                                        <a href="{{ route('backend.requisition_between_branch.index') }}">
                                            <div class="card mini-stats-wid">
                                                <div class="card-body">
                                                    <div class="media">
                                                        <div class="media-body">
                                                            <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-warning font-size-15">ใบเบิกรออนุมัติ</span></p>
                                                            <!-- <h4 class="mb-0"> 35,555 </h4> -->
                                                        </div>

                                                        <div class="avatar-sm rounded-circle bg-warning align-self-center mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-warning">
                                                                <span class="font-size-16">{{ $wait_approve_requisition }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-md-4">
                                        <a href="{{ route('backend.member_regis.index') }}">
                                            <div class="card mini-stats-wid">
                                                <div class="card-body">
                                                    <div class="media">
                                                        <div class="media-body">
                                                            <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-warning font-size-15">เอกสารลูกค้า รออนุมัติ</span></p>
                                                            <!-- <h4 class="mb-0"> 35,555 </h4> -->
                                                        </div>

                                                        <div class="avatar-sm rounded-circle bg-warning align-self-center mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-warning">
                                                                <span class="font-size-16">{{ $wait_approve_customer_doc }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-md-4">
                                      <a href="{{ url('backend/transfer_branch') }}">
                                      <div class="card mini-stats-wid">
                                          <div class="card-body">
                                              <div class="media">
                                                  <div class="media-body">
                                                      <p class="text-muted font-weight-medium"><span class="badge badge-pill badge-soft-success font-size-15">ใบเบิกระหว่างสาขา</span></p>
                                                      <!-- <h4 class="mb-0"> 10,000 </h4> -->
                                                  </div>

                                                  <div class="avatar-sm rounded-circle bg-success align-self-center mini-stat-icon">
                                                      <span class="avatar-title rounded-circle bg-success">
                                                              {{-- <i class="bx bx-purchase-tag-alt font-size-24"></i> --}}
                                                              <span class="font-size-16">{{$wait_approve_requisition_transfer}}</span>
                                                          </span>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      </a>
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
                            <h4 class="card-title mb-4"> @lang('message.inventory_notify') </h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
                                        <div class="col-12 text-right" >
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('backend.stock_notify.index') }}">
                                                @lang('message.view_details')
                                            </a>
                                          </div>
                                    </thead>
                                    <tbody>

                                        <table id="data-table-notify" class="table table-bordered " style="width: 100%;">
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
                            <h4 class="card-title mb-4">@lang('message.crm_transaction')</h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
                                        <div class="col-12 text-right" >
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('backend.crm.index') }}">
                                                @lang('message.view_details')
                                            </a>
                                          </div>
                                    </thead>
                                    <tbody>

                                        <table id="data-table-crm" class="table table-bordered " style="width: 100%;">
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
                            <h4 class="card-title mb-4">@lang('message.pm_transaction')</h4>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
                                         <div class="col-12 text-right" >
                                            <a class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" href="{{ route('backend.pm.index') }}">
                                                @lang('message.view_details')
                                            </a>
                                          </div>
                                    </thead>
                                    <tbody>

                                        <table id="data-table-pm" class="table table-bordered " style="width: 100%;">
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
                    {data: 'product_name', title :'<center>{{ __("message.product_code_name") }}</center>', className: 'text-left w240 '},
                    {data: 'warehouses', title :'<center>{{ __("message.inventory") }}</center>', className: 'text-center'},
                    {data: 'amt', title :'<center>{{ __("message.inventory_latest_list") }}</center>', className: 'text-center w100 '},
                    {data: 'amt_less', title :'<center>{{ __("message.least_piece") }}</center>', className: 'text-center w100 ',render: function(d) {
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
                    {data: 'lot_expired_date', title :'<center>{{ __("message.expiration_date") }}</center>', className: 'text-center'},
                    {data: 'amt_day_before_expired', title :'<center>{{ __("message.warning_before_expiration_date") }}</center>', className: 'text-center w100 ',render: function(d) {
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
            {data: 'role_name', title :'<center>{{ __("message.department") }}</center>', className: 'text-center'},
            // {data: 'subject_receipt_number', title :'<center>เลขใบรับเรื่อง </center>', className: 'text-left'},
            {data: 'receipt_date', title :'<center>{{ __("message.complaint_time_date") }}</center>', className: 'text-center'},
            {data: 'topics_reported', title :'<center>{{ __("message.customer_topic") }}</center>', className: 'text-center'},
            {data: 'recipient_name', title :'<center>{{ __("message.reciever") }}</center>', className: 'text-center'},
            {data: 'operator_name', title :'<center>{{ __("message.operator") }}</center>', className: 'text-center'},
            {data: 'last_update', title :'<center>{{ __("message.update_list") }}</center>', className: 'text-center'},
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
            {data: 'role_name', title :'<center>{{ __("message.department") }}</center>', className: 'text-left'},
            {data: 'receipt_date', title :'<center>{{ __("message.complaint_date") }}</center>', className: 'text-left'},
            {data: 'topics_question', title :'<center>{{ __("message.customer_topic2") }}</center>', className: 'text-left'},
            // {data: 'details_question', title :'<center>รายละเอียดคำถาม </center>', className: 'text-left'},
            {data: 'txt_answers',   title :'<center>{{ __("message.feedback") }}</center>', className: 'text-center font-size-16 th_ ',render: function(d) {
                           return '<span class="badge badge-pill badge-primary font-size-14">'+d+'</span>';
                        }},
            {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
                           return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
            }},

            {data: 'recipient_name', title :'<center>{{ __("message.reciever") }}</center>', className: 'text-left'},
            {data: 'operator_name', title :'<center>{{ __("message.operator") }}</center>', className: 'text-left'},
            {data: 'last_update', title :'<center>{{ __("message.update_list") }}</center>', className: 'text-left'},
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



