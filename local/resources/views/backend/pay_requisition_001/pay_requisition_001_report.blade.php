@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

    .form-group {
        margin-bottom: 0rem  !important;
     }

    .btn-outline-secondary {
        margin-bottom: 36% !important;
    }



    .divTable{
        display: table;
        width: 100%;

      }
      .divTableRow {
        display: table-row;
        margin-bottom: 5%;
      }
      .divTableHeading {
        background-color: #EEE;
        display: table-header-group;
      }
      .divTableCell, .divTableHead {
        /*border-bottom: 1px solid #f2f2f2;*/
        display: table-cell;
        padding: 3px 6px;
      }

      table tbody tr.even {
        background-color: #F5F6F8;
      }
      table tbody tr.odd {
        background-color: white;
      }

      table tbody tr.odd:hover {
        background-color: #e6e6e6;
      }
      table tbody tr.even:hover {
        background-color: #e6e6e6;
      }

/*
      table#data-table-001.dataTable tbody tr.even {
        background-color: #F5F6F8;
      }
      table#data-table-001.dataTable tbody tr.odd {
        background-color: white;
      }

      table#data-table-001.dataTable tbody tr.odd:hover {
        background-color: #e6e6e6;
      }
      table#data-table-001.dataTable tbody tr.even:hover {
        background-color: #e6e6e6;
      }

*/

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

      .invoice_code:hover {
        color: red;
        /*cursor: pointer;*/
      }
/*
      div.divTableBody>div:nth-of-type(odd) {
        background: #f2f2f2;
      }
      div.divTableBody>div:nth-of-type(even) {
        background: white;
      }
            div.divTableBody>div:nth-of-type(odd):hover {
        background-color: #e6e6e6;
        cursor: pointer;
      }
      div.divTableBody>div:nth-of-type(even):hover {
        background-color: #e6e6e6;
        cursor: pointer;
      }
*/
      div.divTableBody>div:hover {
        background: white;
      }
</style>

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18  "> รายงานการจัดส่งสินค้า </h4>
            <!-- test_clear_data -->
        </div>
    </div>
</div>
<!-- end page title -->
  <?php

    // print_r(\Auth::user()->business_location_id_fk);
    // print_r(\Auth::user()->branch_id_fk);

    $sPermission = \Auth::user()->permission ;
    // print_r($sPermission);
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
      // print_r($menu_id);

    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      // $menu_permit = DB::select(" select * from role_permit where role_group_id_fk=2 AND menu_id_fk=34 ");
      // print_r($menu_permit);
      // print_r($role_group_id);
      // print_r($menu_id);
      // print_r($menu_permit);
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
    }
   ?>



<div class="row" >
    <div class="col-12">
        <div class="card">
            <div class="card-body">

              <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Business Location : </label>
                        <div class="col-md-9">
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                  <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->business_location_id_fk))?'selected':'' }} >{{$r->txt_desc}}</option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>
                    </div>

                    {{-- <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-3 col-form-label"> สาขาที่ดำเนินการ : </label>
                            <div class="col-md-9">

                              <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  >
                                 <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                 @if(@$sBranchs)
                                  @foreach(@$sBranchs AS $r)
                                   @if($sPermission==1)
                                    @if($r->business_location_id_fk==(\Auth::user()->business_location_id_fk))
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @else
                                     @if($r->business_location_id_fk==(\Auth::user()->business_location_id_fk))
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @endif
                                  @endforeach
                                @endif
                              </select>

                            </div>
                          </div>
                    </div> --}}

               </div>

              <div class="row" style="display: none;" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                  <!--   <label for="" class="col-md-3 col-form-label"> รหัส-ชื่อสมาชิก : </label>
                    <div class="col-md-9">
                      <select id="customer_id_fk" name="customer_id_fk" class="form-control select2-templating " >
                        <option value="">-Customer-</option>
                        @if(@$customer)
                        @foreach(@$customer AS $r)
                        <option value="{{$r->customer_id_fk}}" >
                          {{$r->customer_id_fk}}
                          {{$r->cus_code}} :
                          {{$r->prefix_name}}{{$r->first_name}} {{$r->last_name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div> -->
                        <label for="" class="col-md-3 col-form-label"> พนักงาน : </label>
                    <div class="col-md-9">
                     <select id="action_user" name="action_user" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$sAdmin)
                        @foreach(@$sAdmin AS $r)
                        <option value="{{$r->id}}" >
                           {{$r->name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="branch_id_fk" class="col-md-3 col-form-label"> สถานะ :  </label>
                    <div class="col-md-9">
                      <select id="status_sent" name="status_sent" class="form-control select2-templating " >
                        <option value="">-Status-</option>
                        @if(@$sPay_requisition_status)
                        @foreach(@$sPay_requisition_status AS $r)
                        <option value="{{$r->id}}" >
                          {{$r->txt_desc}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
              </div>

            <div class="row" >
                <div class="col-md-6 " style="display: none;">
                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label"> วันที่สร้างใบเบิก : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="branch_id_fk" class="col-md-3 col-form-label"> วันที่จ่ายสินค้า : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startPayDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endPayDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
              </div>


            <div class="row" >
                {{-- <div class="col-md-6 " style="margin-top: -1% !important;" >
                  <div class="form-group row">
                <!--     <label for="" class="col-md-3 col-form-label"> พนักงาน : </label>
                    <div class="col-md-9">
                     <select id="action_user" name="action_user" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$sAdmin)
                        @foreach(@$sAdmin AS $r)
                        <option value="{{$r->id}}" >
                           {{$r->name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div> -->
                  </div>
                </div> --}}


                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="branch_id_fk" class="col-md-3 col-form-label">  </label>
                     <div class="col-md-9 d-flex " >
                     <a class="btn btn-info btn-sm btnSearch01 " href="#" style="font-size: 14px !important;margin-left: 0.8%;" >
                        <i class="bx bx-search align-middle "></i> SEARCH
                      </a>
                      {{-- <a class="btn btn-primary btn-sm btnSearch02 " href="#" style="font-size: 14px !important;margin-left: 4%;" >
                        รายการจ่ายวันนี้
                      </a>
                      <a class="btn btn-primary btn-sm btnSearch03 " href="#" style="font-size: 14px !important;margin-left: 4%;" >
                        รายการรอจ่าย 30 วันย้อนหลัง
                      </a> --}}
                      <input type="hidden" id="btnSearch03" value="0">
                    </div>
                  </div>
                </div>
              </div>


        </div>
      </div>
    </div>
  </div>


    <div class="row" style="margin-top:-2%;">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
             <!--  <li class="nav-item">
                <a class="nav-link tab_a active " data-toggle="tab" href="#home" role="tab">
                  <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                  <span class="d-none d-sm-block">ใบเบิก</span>
                </a>
              </li> -->
              <li class="nav-item" style="display: none;" >
                <a class="nav-link tab_b  " data-toggle="tab" href="#profile" role="tab">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">สินค้า</span>
                </a>
              </li>
              <li class="nav-item" style="display: none;" >
                <a class="nav-link tab_c  " data-toggle="tab" href="#inv" role="tab">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">รายการในใบเบิก</span>
                </a>
              </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content p-3 text-muted">
              <div class="tab-pane  active " id="home" role="tabpanel">
                <p class="mb-0">
                  <div class="row">
                  <div class="col-8">
                  </div>

                </div>

                 <table id="data-table-packing" class="table table-bordered " style="width: 100%;" ></table>

                </p>
              </div>
              <div class="tab-pane  " id="profile" role="tabpanel">
                <p class="mb-0">
                  <div class="row">
                  <div class="col-8">
                    <input type="text" class="form-control float-left text-center w200 myLike2 " placeholder="ค้น > รหัส:ชื่อสินค้า" name="txtSearch_002">
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}" >
                    <a class="btn btn-info btn-sm mt-1 font-size-18 " href="{{ url('backend/pick_warehouse') }}">
                      <i class="bx bx-plus font-size-18 align-middle mr-1"></i>บันทึกการเบิกจ่ายสินค้า
                    </a>
                  </div>

                </div>
<!--
                <table id="data-table-002" class="table table-bordered " style="width: 100%;">
                </table> -->

                </p>
              </div>

              <div class="tab-pane " id="inv" role="tabpanel">
                <p class="mb-0">
                  <div class="row">
                  <div class="col-8">
                    <input type="text" class="form-control float-left text-center w200 myLike3 " placeholder="ค้น > เลขที่ใบเบิก" name="txtSearch_003">
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}" >
                    <a class="btn btn-info btn-sm mt-1 font-size-18 " href="{{ url('backend/pick_warehouse') }}">
                      <i class="bx bx-plus font-size-18 align-middle mr-1"></i>บันทึกการเบิกจ่ายสินค้า
                    </a>
                  </div>

                </div>

                <!-- <table id="data-table-003" class="table table-bordered  " style="width: 100%;"></table> -->
                </p>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" tabindex="-1" id="modal_con_arr" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">หมายเลขพัสดุ (Consignment number)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="modal_con_arr_data">

            </div>
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
            $(".myloading").hide();
           }

      });


    $(document).ready(function() {


      $(document).on('change','.tracking_remark',function(){
          var remark = $(this).val();
          var con_id = $(this).attr('con_id');

          $(".myloading").show();
             $.ajax({
                 type:'POST',
                 url: " {{ url('backend/pay_requisition_001_report/consignments_remark') }} ",
                 data:{ _token: '{{csrf_token()}}',
                 con_id:con_id,
                 remark:remark
                },
                  success:function(data){
                       // console.log(data);
                       $(".myloading").hide();
                    },
                  error: function(jqXHR, textStatus, errorThrown) {
                      $(".myloading").hide();
                  }
              });

      });


      $(document).on('click','.con_arr_data_show',function(){
        var arr = $(this).attr('con_arr');
        arr = arr.split(",");
        var arr_str = '';
        for(var i=0; i<arr.length;i++){
          arr_str+=arr[i]+'<br>';
        }
        $('.modal_con_arr_data').html(arr_str);
        $('#modal_con_arr').modal('show');
      });


           $(document).on('click', '.cDelete2', function(){

            // var url = $(this).data('url');
            // var url = " {{ url('backend/cancel-pay_product_receipt') }} ";
            // console.log(url);
            var id = $(this).data("id");
            var invoice_code = $(this).data("invoice_code");
            // alert(id);

                    Swal.fire({
                      title: 'ยืนยัน ! ยกเลิกการจ่าย',
                      // text: 'You clicked the button!',
                      type: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#556ee6',
                      cancelButtonColor: "#f46a6a"
                      }).then(function (result) {
                          if (result.value) {

                             $.ajax({
                                url: " {{ url('backend/cancel-pay_product_receipt_001') }} ",
                                method: "post",
                                data: {
                                  id:id,
                                  invoice_code:invoice_code,
                                  "_token": "{{ csrf_token() }}",
                                },
                                success:function(data)
                                {
                                  console.log(data);
                                  // return false;

                                      Swal.fire({
                                        type: 'success',
                                        title: 'ทำการยกเลิกการจ่ายเรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 2000
                                      });

                                      setTimeout(function () {
                                          $('#data-table-001').DataTable().clear().draw();
                                      }, 2000);
                                }
                              })




                          }
                    });

             });

    });


        $(document).ready(function() {

          var oTable;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable = $('#data-table-packing').DataTable({
              "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                  processing: true,
                  serverSide: true,
                  scroller: true,
                  scrollCollapse: true,
                  scrollX: true,
                  ordering: false,
                  // scrollY: ''+($(window).height()-370)+'px',
                  iDisplayLength: 10,
                  // stateSave: true,
                  destroy:true,

                  ajax: {
                                 url: '{{ route('backend.packing_list_for_fifo.datatable_report') }}',
                        // data :{
                        //     _token: '{{csrf_token()}}',
                        //       business_location_id_fk:business_location_id_fk,
                        //       branch_id_fk:branch_id_fk,
                        //       startDate:startDate,
                        //       startPayDate:startPayDate,
                        //       endDate:endDate,
                        //       endPayDate:endPayDate,
                        //       status_sent:status_sent,
                        //       btnSearch03:btnSearch03,
                        //       action_user:action_user,
                        //     },
                          method: 'POST',
                        },

                      columns: [
                      {data: 'column_001', title :'<span style="vertical-align: middle;"> Recipient Code <br> (รหัสผู้รับ)  </span> ', className: 'text-center w80'},
                      {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> Recipient Name (ชื่อผู้รับ) / Address (ที่อยู่ผู้รับ) </span> ', className: 'text-left',render: function(d) {
                        return d;
                      }},
                      // {data: 'column_003',   title :'<span style="vertical-align: middle;"><center> ส่งรายการให้ Kerry <br> เพื่อขอเลขพัสดุ </span> ', className: 'text-center',render: function(d) {
                      //   return d ;
                      // }},
                      {data: 'column_004',   title :'<span style="vertical-align: middle;"><center> หมายเลขพัสดุ <br> (Consignment number) </span> ', className: 'text-center w150 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'column_007',   title :'<span style="vertical-align: middle;"><center> รายละเอียดลูกค้า </span> ', className: 'text-center w80 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'column_008',   title :'<span style="vertical-align: middle;"><center> ใบเสร็จ </span> ', className: 'text-center w80 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'column_006',   title :'<span style="vertical-align: middle;"><center> จำนวนกล่อง  </span> ', className: 'text-center w100 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'tracking_status',   title :'<span style="vertical-align: middle;"><center> สถานะการจัดส่ง  </span> ', className: 'text-center w100 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'tracking_remark',   title :'<span style="vertical-align: middle;"><center> หมายเหตุ  </span> ', className: 'text-center w200 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'tracking_approve',   title :'<span style="vertical-align: middle;"><center> ยืนยันสถานะ  </span> ', className: 'text-center w100 ',render: function(d) {
                        return d ;
                      }},




                      // {data: 'column_005',   title :'<span style="vertical-align: middle;"><center> ใบปะหน้ากล่อง </span> ', className: 'text-center w80 ',render: function(d) {
                      //   return d ;
                      // }},

                  ],
                  rowCallback: function(nRow, aData, dataIndex){
                     // $(".myloading").hide();
                  }
              });

               oTable.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
                });

          });


           $(document).on('click', '.btnSearch02', function(event) {
                  event.preventDefault();
                  // $("#status_sent").select2("val", "3");
                  $("#status_sent").select2('destroy').val("").select2();
                  $("#action_user").select2('destroy').val("").select2();
                  $("#customer_id_fk").select2('destroy').val("").select2();
                  // var today = new Date();
                  // var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                  const today = new Date().toISOString().slice(0, 10);
                  // console.log(today);
                  $('#startPayDate').val(today);
                  $('#endPayDate').val(today);

                  var startPayDate = $('#startPayDate').val();
                  var endPayDate = $('#endPayDate').val();

                  $('#startDate').val('');
                  $('#endDate').val('');
                  $('#btnSearch03').val('0');

                  $(".btnSearch01").trigger('click');
            });

            $(document).on('click', '.btnSearch03', function(event) {
                 event.preventDefault();
                 $("#status_sent").select2("val", "1");
                 $("#action_user").select2('destroy').val("").select2();
                 $("#customer_id_fk").select2('destroy').val("").select2();
                  var myCurrentDate=new Date();
                  var myPastDate=new Date(myCurrentDate);
                      myPastDate.setDate(myPastDate.getDate() - 30);
                  const today = new Date().toISOString().slice(0, 10);
                  const start_day = new Date(myPastDate).toISOString().slice(0, 10);
                  // console.log(today);
                  $('#startDate').val(start_day);
                  $('#endDate').val(today);

                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();

                   $('#startPayDate').val('');
                   $('#endPayDate').val('');
                   $('#btnSearch03').val('1');

                  $(".btnSearch01").trigger('click');
            });


            $(document).on('click', '.btnSearch01', function(event) {
                  event.preventDefault();
                  $('#data-table-001').DataTable().clear();

                  $(".myloading").show();

                  // return false;

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var customer_id_fk = $('#customer_id_fk').val();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var status_sent = $('#status_sent').val();

                  var startPayDate = $('#startPayDate').val();
                  var endPayDate = $('#endPayDate').val();
                  var btnSearch03 = $('#btnSearch03').val();
                  var action_user = $('#action_user').val();

                  // return false;

                  if(business_location_id_fk==''){
                    $('#business_location_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                  }
                  // alert(branch_id_fk);
                  if(branch_id_fk=='' || branch_id_fk === null ){
                    $('#branch_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                  }

          // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

        var oTable;
          $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
              oTable = $('#data-table-packing').DataTable({
              "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                  processing: true,
                  serverSide: true,
                  scroller: true,
                  scrollCollapse: true,
                  scrollX: true,
                  ordering: false,
                  // scrollY: ''+($(window).height()-370)+'px',
                  iDisplayLength: 10,
                  // stateSave: true,
                  destroy:true,

                  ajax: {
                                 url: '{{ route('backend.packing_list_for_fifo.datatable_report') }}',
                        data :{
                            _token: '{{csrf_token()}}',
                              business_location_id_fk:business_location_id_fk,
                              branch_id_fk:branch_id_fk,
                              startDate:startDate,
                              startPayDate:startPayDate,
                              endDate:endDate,
                              endPayDate:endPayDate,
                              status_sent:status_sent,
                              btnSearch03:btnSearch03,
                              action_user:action_user,
                            },
                          method: 'POST',
                        },

                      columns: [
                      {data: 'column_001', title :'<span style="vertical-align: middle;"> Recipient Code <br> (รหัสผู้รับ)  </span> ', className: 'text-center w80'},
                      {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> Recipient Name (ชื่อผู้รับ) / Address (ที่อยู่ผู้รับ) </span> ', className: 'text-left',render: function(d) {
                        return d;
                      }},
                      // {data: 'column_003',   title :'<span style="vertical-align: middle;"><center> ส่งรายการให้ Kerry <br> เพื่อขอเลขพัสดุ </span> ', className: 'text-center',render: function(d) {
                      //   return d ;
                      // }},
                      {data: 'column_004',   title :'<span style="vertical-align: middle;"><center> หมายเลขพัสดุ <br> (Consignment number) </span> ', className: 'text-center w150 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'column_007',   title :'<span style="vertical-align: middle;"><center> รายละเอียดลูกค้า </span> ', className: 'text-center w80 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'column_008',   title :'<span style="vertical-align: middle;"><center> ใบเสร็จ </span> ', className: 'text-center w80 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'column_006',   title :'<span style="vertical-align: middle;"><center> จำนวนกล่อง  </span> ', className: 'text-center w100 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'tracking_status',   title :'<span style="vertical-align: middle;"><center> สถานะการจัดส่ง  </span> ', className: 'text-center w100 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'tracking_remark',   title :'<span style="vertical-align: middle;"><center> หมายเหตุ  </span> ', className: 'text-center w200 ',render: function(d) {
                        return d ;
                      }},

                      {data: 'tracking_approve',   title :'<span style="vertical-align: middle;"><center> ยืนยันสถานะ  </span> ', className: 'text-center w100 ',render: function(d) {
                        return d ;
                      }},




                      // {data: 'column_005',   title :'<span style="vertical-align: middle;"><center> ใบปะหน้ากล่อง </span> ', className: 'text-center w80 ',render: function(d) {
                      //   return d ;
                      // }},

                  ],
                  rowCallback: function(nRow, aData, dataIndex){
                     // $(".myloading").hide();
                  }
              });

               oTable.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
                });

          });
          // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function(){
                   $(".myloading").hide();
                }, 1500);


            });

        });


</script>

   <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <script>

      var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
      $('#startDate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
      });

      $('#endDate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#startDate').val();
          }
      });

      $('#startDate').change(function(event) {

        if($('#endDate').val()>$(this).val()){
        }else{
          $('#endDate').val($(this).val());
        }
        $('#startPayDate').val('');
        $('#endPayDate').val('');
        $('#btnSearch03').val('0');

      });


      $('#endDate').change(function(event) {
        $('#btnSearch03').val('0');
      });


    </script>
    <script>

      $('#startPayDate').datepicker({
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
      });

      $('#endPayDate').datepicker({
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#startPayDate').val();
          }
      });

      $('#startPayDate').change(function(event) {

        if($('#endPayDate').val()>$(this).val()){
        }else{
          $('#endPayDate').val($(this).val());
        }

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
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");
      DB::select("TRUNCATE `db_consignments`;");
      DB::select("UPDATE `db_delivery` SET `status_pick_pack`='0' ;");

      ?>
          <script>
          // location.replace( "{{ url('backend/pick_pack') }}");
          // location.replace( "{{ url('backend/delivery') }}");
          location.replace( "{{ url('backend/pay_requisition_001') }}");
          </script>
          <?php
      }
    ?>
@endsection

