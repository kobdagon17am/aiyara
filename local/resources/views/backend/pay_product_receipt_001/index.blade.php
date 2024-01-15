@extends('backend.layouts.master')

@section('title')
    Aiyara Planet
@endsection

@section('css')
    <style>
        .select2-selection {
            height: 34px !important;
            margin-left: 3px;
        }

        .border-left-0 {
            height: 67%;
        }

        .form-group {
            margin-bottom: 0rem !important;
        }

        .btn-outline-secondary {
            margin-bottom: 36% !important;
        }



        .divTable {
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

        .divTableCell,
        .divTableHead {
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

        .divTH {
            text-align: right;
        }

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
                <h4 class="mb-0 font-size-18  "> จ่ายสินค้าตามใบเสร็จ </h4>
                <!-- test_clear_data -->
            </div>
        </div>
    </div>
    <!-- end page title -->
    <?php

    // print_r(\Auth::user()->business_location_id_fk);
    // print_r(\Auth::user()->branch_id_fk);
    $can_cancel_bill = 0;
    $can_cancel_bill_across_day = 0;

    $sPermission = \Auth::user()->permission;
    // print_r($sPermission);
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    // print_r($menu_id);
    // echo $menu_id;

    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
        $can_cancel_bill = 1;
    } else {
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        // $menu_permit = DB::select(" select * from role_permit where role_group_id_fk=2 AND menu_id_fk=34 ");
        // print_r($menu_permit);
        // print_r($role_group_id);
        // print_r($menu_id);
        // print_r($menu_permit);
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
        $can_cancel_bill = @$menu_permit->can_cancel_bill;
        $can_cancel_bill_across_day = @$menu_permit->can_cancel_bill_across_day;
    }

    // echo $role_group_id;

    ?>



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">Business Location : </label>
                                <div class="col-md-9">
                                    <select id="business_location_id_fk" name="business_location_id_fk"
                                        class="form-control select2-templating " required="">
                                        <option value="">-Business Location-</option>
                                        @if (@$sBusiness_location)
                                            @foreach (@$sBusiness_location as $r)
                                                <option value="{{ @$r->id }}"
                                                    {{ @$r->id == \Auth::user()->business_location_id_fk ? 'selected' : '' }}>
                                                    {{ $r->txt_desc }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="branch_id_fk" class="col-md-3 col-form-label"> สาขาที่ดำเนินการ : </label>
                                <div class="col-md-9">

                                    <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating ">
                                        <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                        @if (@$sBranchs)
                                            @foreach (@$sBranchs as $r)
                                                @if ($sPermission == 1)
                                                    @if ($r->business_location_id_fk == \Auth::user()->business_location_id_fk)
                                                        <option value="{{ @$r->id }}"
                                                            {{ @$r->id == \Auth::user()->branch_id_fk ? 'selected' : '' }}>
                                                            {{ $r->b_name }}</option>
                                                    @endif
                                                @else
                                                    @if ($r->business_location_id_fk == \Auth::user()->business_location_id_fk)
                                                        <option value="{{ @$r->id }}"
                                                            {{ @$r->id == \Auth::user()->branch_id_fk ? 'selected' : '' }}>
                                                            {{ $r->b_name }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> รหัส/ชื่อสมาชิก : </label>
                                <div class="col-md-9">
                                    <select id="customer_id_fk" name="customer_id_fk"
                                        class="form-control select2-templating ">
                                        <option value="">-Customer-</option>
                                        @if (@$customer)
                                            @foreach (@$customer as $r)
                                                <option value="{{ $r->customer_id_fk }}">
                                                    <!-- {{ $r->customer_id_fk }} -->
                                                    {{ $r->cus_code }} :
                                                    {{ $r->prefix_name }}{{ $r->first_name }} {{ $r->last_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="branch_id_fk" class="col-md-3 col-form-label"> สถานะ : </label>
                                <div class="col-md-9">
                                    <select id="status_sent" name="status_sent" class="form-control select2-templating ">
                                        <option value="">-Status-</option>
                                        @if (@$sPay_product_status)
                                            @foreach (@$sPay_product_status as $r)
                                                <option value="{{ $r->id }}">
                                                    {{ $r->txt_desc }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> วันที่ออกใบเสร็จ : </label>
                                <div class="col-md-9 d-flex">
                                    <input id="startDate" autocomplete="off" placeholder="Begin Date"
                                        style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                                    <input id="endDate" autocomplete="off" placeholder="End Date"
                                        style="border: 1px solid grey;font-weight: bold;color: black" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="branch_id_fk" class="col-md-3 col-form-label"> วันที่จ่ายสินค้า : </label>
                                <div class="col-md-9 d-flex">
                                    <input id="startPayDate" autocomplete="off" placeholder="Begin Date"
                                        style="margin-left: 1%;border: 1px solid grey;font-weight: bold;color: black" />
                                    <input id="endPayDate" autocomplete="off" placeholder="End Date"
                                        style="border: 1px solid grey;font-weight: bold;color: black" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 " style="margin-top: -1% !important;">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> พนักงาน : </label>
                                <div class="col-md-9">
                                    <select id="action_user" name="action_user" class="form-control select2-templating ">
                                        <option value="">-Select-</option>
                                        @if (@$sAdmin)
                                            @foreach (@$sAdmin as $r)
                                                <option value="{{ $r->id }}">
                                                    {{ $r->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="branch_id_fk" class="col-md-3 col-form-label"> </label>
                                <div class="col-md-9 d-flex ">
                                    <a class="btn btn-info btn-sm btnSearch01 " href="#"
                                        style="font-size: 14px !important;margin-left: 0.8%;">
                                        <i class="bx bx-search align-middle "></i> SEARCH
                                    </a>
                                    <a class="btn btn-primary btn-sm btnSearch02 " href="#"
                                        style="font-size: 14px !important;margin-left: 4%;">
                                        รายการจ่ายวันนี้
                                    </a>
                                    <a class="btn btn-primary btn-sm btnSearch03 " href="#"
                                        style="font-size: 14px !important;margin-left: 4%;">
                                        รายการรอจ่าย 30 วันย้อนหลัง
                                    </a>
                                    <input type="hidden" id="btnSearch03" value="0">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="row" style="margin-top:-1%;">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="display: none;">
                        <li class="nav-item">
                            <!--      <a class="nav-link tab_a active " data-toggle="tab" href="#home" role="tab">
                      <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                      <span class="d-none d-sm-block">ใบเสร็จ</span>
                    </a> -->
                        </li>
                        <li class="nav-item">
                            <!--      <a class="nav-link tab_b  " data-toggle="tab" href="#profile" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">สินค้า</span>
                    </a> -->
                        </li>
                        <li class="nav-item">
                            <!--      <a class="nav-link tab_c  " data-toggle="tab" href="#inv" role="tab">
                      <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                      <span class="d-none d-sm-block">INVOICE DETAILS</span>
                    </a> -->
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane  active " id="home" role="tabpanel">
                            <p class="mb-0">
                            <div class="row">
                                <div class="col-8">
                                    <!-- <input type="text" class="form-control float-left text-center w200 myLike1 " placeholder="ค้น > รหัสใบเสร็จ" name="txtSearch_001"> -->
                                </div>
                                <!-- @$sC -->
                                <div class="col-4 text-right" style="">
                                    <a class="btn btn-info btn-sm mt-1 font-size-18 "
                                        href="{{ url('backend/pay_product_receipt') }}">
                                        <i class="bx bx-plus font-size-18 align-middle mr-1"></i>บันทึกการจ่ายสินค้า
                                    </a>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>รายการบิลบันทึกการจ่ายแล้ว</label>
                                </div>
                            </div>

                            <table id="data-table-001" class="table table-bordered " style="width: 100%;">
                            </table>
                            </p>
                        </div>
                        <div class="tab-pane  " id="profile" role="tabpanel">
                            <p class="mb-0">
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" class="form-control float-left text-center w200 myLike2 "
                                        placeholder="ค้น > รหัส:ชื่อสินค้า" name="txtSearch_002">
                                </div>

                                <div class="col-4 text-right" style="{{ @$sC }}">
                                    <a class="btn btn-info btn-sm mt-1 font-size-18 "
                                        href="{{ url('backend/pay_product_receipt') }}">
                                        <i class="bx bx-plus font-size-18 align-middle mr-1"></i>บันทึกการจ่ายสินค้า
                                    </a>
                                </div>

                            </div>

                            <table id="data-table-002" class="table table-bordered " style="width: 100%;">
                            </table>

                            </p>
                        </div>

                        <div class="tab-pane " id="inv" role="tabpanel">
                            <p class="mb-0">
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" class="form-control float-left text-center w200 myLike3 "
                                        placeholder="ค้น > เลขที่ใบเสร็จ" name="txtSearch_003">
                                </div>

                                <div class="col-4 text-right" style="{{ @$sC }}">
                                    <a class="btn btn-info btn-sm mt-1 font-size-18 "
                                        href="{{ url('backend/pay_product_receipt') }}">
                                        <i class="bx bx-plus font-size-18 align-middle mr-1"></i>บันทึกการจ่ายสินค้า
                                    </a>
                                </div>

                            </div>

                            <table id="data-table-003" class="table table-bordered  " style="width: 100%;">
                            </table>
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label>รายการบิลรอบันทึกการจ่ายสินค้า</label>
                            </div>
                        </div>

                        <table id="data-table-wait_orders" class="table table-bordered " style="width: 100%;">
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        $(function() {
            // $.fn.dataTable.ext.errMode = 'throw';
            oTable_wait_orders = $('#data-table-wait_orders').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                destroy: true,
                ordering: false,
                iDisplayLength: 50,
                ajax: {
                    url: '{{ url('backend/pay_product_receipt_tb1/wait_orders') }}',
                    method: 'POST',
                },
                columns: [{
                        data: 'id',
                        title: 'ID',
                        className: 'text-center w50'
                    },
                    {
                        data: 'invoice_code',
                        title: '<center>ใบเสร็จ</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'created_at',
                        title: '<center>วันที่ออกใบเสร็จ</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'customer',
                        title: '<center>รหัส:ชื่อสมาชิก</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'action_user',
                        title: '<center>ผู้สร้าง</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'status',
                        title: '<center>สถานะ</center>',
                        className: 'text-center'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {}
            });
        });
    </script>



    <script>
        // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var can_cancel_bill = "{{ @$can_cancel_bill }}"; //alert(can_cancel_bill);
        var can_cancel_bill_across_day = "{{ @$can_cancel_bill_across_day }}"; //alert(can_cancel_bill);

        var oTable_001;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTable_001 = $('#data-table-001').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                destroy: true,
                ordering: false,
                iDisplayLength: 50,
                ajax: {
                    url: '{{ route('backend.pay_product_receipt_tb1.datatable') }}',
                    method: 'POST',
                },
                columns: [{
                        data: 'id',
                        title: 'ID',
                        className: 'text-center w50'
                    },
                    {
                        data: 'invoice_code',
                        title: '<center>ใบเสร็จ</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'bill_date',
                        title: '<center>วันที่ออกใบเสร็จ</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'customer',
                        title: '<center>รหัส:ชื่อสมาชิก</center>',
                        className: 'text-center w180 '
                    },
                    {
                        data: 'status_sent',
                        title: '<center>สถานะ</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'pay_user',
                        title: '<center>ผู้จ่ายสินค้า <br> วันที่จ่ายสินค้า</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'action_user',
                        title: '<center>ผู้ยกเลิกการจ่าย<br>วันที่ดำเนินการ</center>',
                        className: 'text-center'
                    },
                    // {data: 'branch', title :'<center>สาขาที่ดำเนินการ</center>', className: 'text-center'},
                    {
                        data: 'address_send_type',
                        title: '<center>รับที่</center>',
                        className: 'text-center w150'
                    },
                    {
                        data: 'id',
                        title: 'Tools',
                        className: 'text-center w60'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    var info = $(this).DataTable().page.info();
                    $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                    if (sU != '' && sD != '') {
                        $('td:last-child', nRow).html('-');
                    } else {

                        // console.log(aData['status_sent_2']);
                        // console.log(aData['status_cancel_all']);
                        // console.log(aData['status_cancel_some']);
                        // เปลี่ยนใหม่ ขอเพียงแค่มีการยกเลิกบางรายการ จะปิดปุ่มยกเลิกทั้งหมด เพราะมันซับซ้อนเกินไป
                        if (aData['status_sent_2'] == 4 || aData['status_cancel_some'] == 1) {

                            $('td:last-child', nRow).html('' +
                                '<a href="{{ url('backend/pay_product_receipt') }}/' + aData[
                                'id'] + '/edit" class="btn btn-sm btn-primary" style="' + sU +
                                '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

                            ).addClass('input');

                            // $('td:eq(4)', nRow).html('-');

                        } else {



                        }

                    }


                    // console.log(can_cancel_bill);
                    // console.log(can_cancel_bill_across_day);
                    // console.log(aData['status_sent_2']);
                    console.log('can_cancel_bill: ' + can_cancel_bill + ' / ' + aData['invoice_code']);
                    console.log('status_sent_2: ' + aData['status_sent_2']);

                    if (can_cancel_bill == '1' && aData['status_sent_2'] == 3) {

                        $('td:last-child', nRow).html('' +
                            '<a href="{{ url('backend/pay_product_receipt') }}/' + aData['id'] +
                            '/edit" class="btn btn-sm btn-primary" style="' + sU +
                            '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                            '<a href="javascript: void(0);" class="btn btn-sm btn-danger cDelete2 " data-invoice_code="' +
                            aData['invoice_code_2'] + '"  data-id="' + aData['id'] +
                            '"  data-toggle="tooltip" data-placement="top" title="ยกเลิกรายการจ่ายสินค้าบิลนี้" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                        ).addClass('input');



                    } else if (aData['status_sent_2'] == 4) {
                        $('td:last-child', nRow).html('' +
                            ''
                        ).addClass('input');
                    } else {
                        $('td:last-child', nRow).html('' +
                            '<a href="{{ url('backend/pay_product_receipt') }}/' + aData['id'] +
                            '/edit" class="btn btn-sm btn-primary" style="' + sU +
                            '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

                        ).addClass('input');
                    }

                    if (aData['status_sent_2'] == 3) {
                        $('td:eq(6)', nRow).html('');
                    }
                    if (aData['status_sent_2'] == 2) {
                        $('td:eq(6)', nRow).html('');
                    }
                    if (aData['status_sent_2'] == 1) {
                        $('td:eq(5)', nRow).html('');
                        $('td:eq(6)', nRow).html('');
                    }


                }
            });

            oTable_001.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

        });
        // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
    </script>
    <script>
        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var oTable_002;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTable_002 = $('#data-table-002').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                ordering: false,
                // iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.pay_product_receipt_tb2.datatable') }}',
                    method: 'POST',
                },
                columns: [{
                        data: 'column_001',
                        title: 'รหัส:ชื่อสินค้า',
                        className: 'text-left w200 '
                    },
                    {
                        data: 'column_002',
                        title: '<center>รายการสินค้า</center>',
                        className: 'text-left '
                    },
                    {
                        data: 'column_003',
                        title: '<center>เลขที่ใบเสร็จ : ชื่อผู้จ่าย </center>',
                        className: 'text-left w150 '
                    },
                    {
                        data: 'column_004',
                        title: '<center>สาขา</center>',
                        className: 'text-left '
                    },
                    {
                        data: 'column_005',
                        title: '<center>วันเวลาที่ดำเนินการ</center>',
                        className: 'text-center '
                    },

                ],
                rowCallback: function(nRow, aData, dataIndex) {

                }
            });

        });
    </script>
    <!--

      <script>
          $(document).ready(function() {

              $(document).on('change', '.myLike1', function(event) {
                  event.preventDefault();
                  $('#data-table-001').DataTable().clear().draw();

                  $(".myloading").show();

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var customer_id_fk = $('#customer_id_fk').val();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var status_sent = $('#status_sent').val();

                  var txtSearch_001 = $("input[name=txtSearch_001]").val();
                  // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                  var sU = "{{ @$sU }}";
                  var sD = "{{ @$sD }}";
                  var oTable_001;
                  $(function() {
                      $.fn.dataTable.ext.errMode = 'throw';
                      oTable_001 = $('#data-table-001').DataTable({
                          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                          processing: true,
                          serverSide: true,
                          scroller: true,
                          destroy: true,
                          ordering: false,
                          iDisplayLength: 50,
                          ajax: {
                              url: '{{ route('backend.pay_product_receipt_tb1.datatable') }}',
                              data: {
                                  _token: '{{ csrf_token() }}',
                                  business_location_id_fk: business_location_id_fk,
                                  branch_id_fk: branch_id_fk,
                                  customer_id_fk: customer_id_fk,
                                  startDate: startDate,
                                  endDate: endDate,
                                  status_sent: status_sent,
                                  txtSearch_001: txtSearch_001,
                              },
                              method: 'POST',
                          },
                          columns: [{
                                  data: 'id',
                                  title: 'ID',
                                  className: 'text-center w50'
                              },
                              {
                                  data: 'invoice_code',
                                  title: '<center>ใบเสร็จ</center>',
                                  className: 'text-center'
                              },
                              {
                                  data: 'bill_date',
                                  title: '<center>วันที่ออกใบเสร็จ</center>',
                                  className: 'text-center'
                              },
                              {
                                  data: 'customer',
                                  title: '<center>รหัส:ชื่อสมาชิก</center>',
                                  className: 'text-center w180 '
                              },
                              {
                                  data: 'status_sent',
                                  title: '<center>สถานะ</center>',
                                  className: 'text-center'
                              },
                              {
                                  data: 'pay_user',
                                  title: '<center>ผู้จ่ายสินค้า <br> วันที่จ่ายสินค้า</center>',
                                  className: 'text-center'
                              },
                              {
                                  data: 'action_user',
                                  title: '<center>ผู้ยกเลิกการจ่าย<br>วันที่ดำเนินการ</center>',
                                  className: 'text-center'
                              },

                              // {data: 'branch', title :'<center>สาขาที่ดำเนินการ</center>', className: 'text-center'},
                              {
                                  data: 'address_send_type',
                                  title: '<center>รับที่</center>',
                                  className: 'text-center w150'
                              },
                              {
                                  data: 'id',
                                  title: 'Tools',
                                  className: 'text-center w60'
                              },
                          ],
                          rowCallback: function(nRow, aData, dataIndex) {

                              var info = $(this).DataTable().page.info();
                              $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                              if (sU != '' && sD != '') {
                                  $('td:last-child', nRow).html('-');
                              } else {

                                  // console.log(aData['status_sent_2']);

                                  if (aData['status_sent_2'] == 4) { // ยกเลิกบิล

                                      $('td:last-child', nRow).html('' +
                                          '<a href="{{ url('backend/pay_product_receipt') }}/' +
                                          aData['id'] +
                                          '/edit" class="btn btn-sm btn-primary" style="' +
                                          sU +
                                          '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

                                      ).addClass('input');

                                  } else {

                                      if (sD != "") {
                                          $('td:last-child', nRow).html('' +
                                              '<a href="{{ url('backend/pay_product_receipt') }}/' +
                                              aData['id'] +
                                              '/edit" class="btn btn-sm btn-primary" style="' +
                                              sU +
                                              '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                          ).addClass('input');
                                      } else {
                                          $('td:last-child', nRow).html('' +
                                              '<a href="{{ url('backend/pay_product_receipt') }}/' +
                                              aData['id'] +
                                              '/edit" class="btn btn-sm btn-primary" style="' +
                                              sU +
                                              '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                                              '<a href="javascript: void(0);" data-url="{{ route('backend.pay_product_receipt_001.index') }}/' +
                                              aData['id'] +
                                              '" class="btn btn-sm btn-danger cDelete2 " data-invoice_code="' +
                                              aData['invoice_code_2'] +
                                              '"  data-id="' + aData['id'] +
                                              '" style="' + sD +
                                              '" data-toggle="tooltip" data-placement="top" title="ยกเลิกรายการจ่ายสินค้าบิลนี้" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                          ).addClass('input');
                                      }




                                  }


                              }
                          }
                      });

                      oTable_001.on('draw', function() {
                          $('[data-toggle="tooltip"]').tooltip();
                      });

                  });
                  // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                  setTimeout(function() {
                      $(".myloading").hide();
                  }, 1500);


              });

          });
      </script>
     -->


    <script>
        $(document).ready(function() {

            $(document).on('change', '.myLike2', function(event) {
                event.preventDefault();

                $(".myloading").show();

                var business_location_id_fk = $('#business_location_id_fk').val();
                var branch_id_fk = $('#branch_id_fk').val();
                var customer_id_fk = $('#customer_id_fk').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var status_sent = $('#status_sent').val();

                var txtSearch_002 = $("input[name=txtSearch_002]").val();

                // console.log(business_location_id_fk);
                // console.log(branch_id_fk);
                // console.log(customer_id_fk);
                // console.log(startDate);
                // console.log(endDate);
                // console.log(status_sent);
                // console.log(txtSearch_002);

                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable_002;
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    oTable_002 = $('#data-table-002').DataTable({
                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                        processing: true,
                        serverSide: true,
                        scroller: true,
                        destroy: true,
                        ordering: false,
                        // iDisplayLength: 25,
                        ajax: {
                            url: '{{ route('backend.pay_product_receipt_tb2.datatable') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                business_location_id_fk: business_location_id_fk,
                                branch_id_fk: branch_id_fk,
                                customer_id_fk: customer_id_fk,
                                startDate: startDate,
                                endDate: endDate,
                                status_sent: status_sent,
                                txtSearch: txtSearch_002,
                            },
                            method: 'POST',
                        },
                        columns: [{
                                data: 'column_001',
                                title: 'รหัส:ชื่อสินค้า',
                                className: 'text-left w200 '
                            },
                            {
                                data: 'column_002',
                                title: '<center>รายการสินค้า</center>',
                                className: 'text-left '
                            },
                            {
                                data: 'column_003',
                                title: '<center>เลขที่ใบเสร็จ : ชื่อผู้จ่าย </center>',
                                className: 'text-left w150 '
                            },
                            {
                                data: 'column_004',
                                title: '<center>สาขา</center>',
                                className: 'text-left '
                            },
                            {
                                data: 'column_005',
                                title: '<center>วันเวลาที่ดำเนินการ</center>',
                                className: 'text-center '
                            },
                        ],
                        rowCallback: function(nRow, aData, dataIndex) {

                        }
                    });

                });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });

        });
    </script>



    <script>
        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var oTable_003;
        // $(function() {
        // 	$.fn.dataTable.ext.errMode = 'throw';
        //     oTable_003 = $('#data-table-003').DataTable({
        //     "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        //         processing: true,
        //         serverSide: true,
        //         scroller: true,
        //         destroy:true,
        //         ordering: false,
        //          ajax: {
        // 	        url: '{{ route('backend.pay_product_receipt_tb3.datatable') }}',
        // 	          data :{
        //             	_token: '{{ csrf_token() }}',
        //                   },
        //               method: 'POST',
        //             },
        //         columns: [
        //                 {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w100'},
        //                 {data: 'column_002', title :'<center><span style="vertical-align: middle;"> รายการสินค้า </span></center> ', className: 'text-left w600 '},
        //                 {data: 'column_003', title :'<center><span style="vertical-align: middle;"> รหัส:ชื่อสมาชิก </span></center> ', className: 'text-left '},
        //                 {data: 'column_004', title :'<center><span style="vertical-align: middle;"> สาขา </span></center> ', className: 'text-left '},
        //             ],
        //             rowCallback: function(nRow, aData, dataIndex){
        //             }
        //     });


        // });
    </script>



    <script>
        $(document).ready(function() {

            $(document).on('change', '.myLike3', function(event) {
                event.preventDefault();

                $(".myloading").show();

                var txtSearch_003 = $(this).val();
                var business_location_id_fk = $('#business_location_id_fk').val();
                var branch_id_fk = $('#branch_id_fk').val();
                var customer_id_fk = $('#customer_id_fk').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var status_sent = $('#status_sent').val();
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable_003;
                // $(function() {
                // 	$.fn.dataTable.ext.errMode = 'throw';
                //     oTable_003 = $('#data-table-003').DataTable({
                //     "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                //         processing: true,
                //         serverSide: true,
                //         scroller: true,
                //         destroy:true,
                //         ordering: false,
                //         ajax: {
                //                   url: '{{ route('backend.pay_product_receipt_tb3.datatable') }}',
                //                   data :{
                //                   	_token: '{{ csrf_token() }}',
                //                         business_location_id_fk:business_location_id_fk,
                //                         branch_id_fk:branch_id_fk,
                //                         customer_id_fk:customer_id_fk,
                //                         startDate:startDate,
                //                         endDate:endDate,
                //                         status_sent:status_sent,
                //                         txtSearch_003:txtSearch_003,
                //                       },
                //                     method: 'POST',
                //                   },
                //         columns: [
                //                 {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w100'},
                //                 {data: 'column_002', title :'<center><span style="vertical-align: middle;"> รายการสินค้า </span></center> ', className: 'text-left w600 '},
                //                 {data: 'column_003', title :'<center><span style="vertical-align: middle;"> รหัส:ชื่อสมาชิก </span></center> ', className: 'text-left '},
                //                 {data: 'column_004', title :'<center><span style="vertical-align: middle;"> สาขา </span></center> ', className: 'text-left '},
                //             ],
                //             rowCallback: function(nRow, aData, dataIndex){
                //             }
                //     });

                // });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });

        });
    </script>


    <script>
        $('#business_location_id_fk').change(function() {

            $(".myloading").show();
            var business_location_id_fk = this.value;
            // alert(warehouse_id_fk);

            if (business_location_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetBranch') }} ",
                    method: "post",
                    data: {
                        business_location_id_fk: business_location_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $(".myloading").hide();
                        if (data == '') {
                            alert('ไม่พบข้อมูลสาขา !!.');
                        } else {
                            var layout = '<option value="" selected>- เลือกสาขา -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.b_name +
                                    '</option>';
                            });
                            $('#branch_id_fk').html(layout);
                        }
                    }
                })
            } else {
                $(".myloading").hide();
            }

        });

        $(document).ready(function() {

            $(document).on('click', '.invoice_code', function() {

                $(".myloading").show();

                var txtSearch_003 = $(this).data('invoice_code');
                // alert(invoice_code);
                $("input[name='txtSearch_003']").val(txtSearch_003);
                $('.tab_c').trigger('click');
                // $("input[name='invoice_code']").focus();
                var business_location_id_fk = $('#business_location_id_fk').val();
                var branch_id_fk = $('#branch_id_fk').val();
                var customer_id_fk = $('#customer_id_fk').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var status_sent = $('#status_sent').val();
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable_003;
                // $(function() {
                // 	$.fn.dataTable.ext.errMode = 'throw';
                //     oTable_003 = $('#data-table-003').DataTable({
                //     "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                //         processing: true,
                //         serverSide: true,
                //         scroller: true,
                //         destroy:true,
                //         ordering: false,
                //         ajax: {
                //                   url: '{{ route('backend.pay_product_receipt_tb3.datatable') }}',
                //                   data :{
                //                   	_token: '{{ csrf_token() }}',
                //                         business_location_id_fk:business_location_id_fk,
                //                         branch_id_fk:branch_id_fk,
                //                         customer_id_fk:customer_id_fk,
                //                         startDate:startDate,
                //                         endDate:endDate,
                //                         status_sent:status_sent,
                //                         txtSearch_003:txtSearch_003,
                //                       },
                //                     method: 'POST',
                //                   },
                //         columns: [
                //                 {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w100'},
                //                 {data: 'column_002', title :'<center><span style="vertical-align: middle;"> รายการสินค้า </span></center> ', className: 'text-left w600 '},
                //                 {data: 'column_003', title :'<center><span style="vertical-align: middle;"> รหัส:ชื่อสมาชิก </span></center> ', className: 'text-left '},
                //                 {data: 'column_004', title :'<center><span style="vertical-align: middle;"> สาขา </span></center> ', className: 'text-left '},
                //             ],
                //             rowCallback: function(nRow, aData, dataIndex){
                //             }
                //     });

                // });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);

            });

        });




        $(document).ready(function() {

            $(document).on('click', '.cDelete2', function() {

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
                }).then(function(result) {
                    if (result.value) {
                      $(".myloading").show();

                        $.ajax({
                            url: " {{ url('backend/cancel-pay_product_receipt_001') }} ",
                            method: "post",
                            data: {
                                id: id,
                                invoice_code: invoice_code,
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(data) {
                                // console.log(data);
                                // return false;
                                $(".myloading").hide();
                                console.log(data.status);
                                console.log(data.message);
                                if (data.status == 1) {

                                  $('#data-table-001').DataTable().clear().draw();

                                    Swal.fire({
                                        type: 'success',
                                        title: data.message,
                                        showConfirmButton: true,
                                    });

                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: data.message,
                                        showConfirmButton: true,
                                    });
                                }
                            }
                        })

                    }
                });

            });

        });


        $(document).ready(function() {

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
                var startPayDate = $('#startPayDate').val(today);
                var endPayDate = $('#endPayDate').val(today);
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
                var myCurrentDate = new Date();
                var myPastDate = new Date(myCurrentDate);
                myPastDate.setDate(myPastDate.getDate() - 30);
                const today = new Date().toISOString().slice(0, 10);
                const start_day = new Date(myPastDate).toISOString().slice(0, 10);
                // console.log(today);
                var startDate = $('#startDate').val(start_day);
                var endDate = $('#endDate').val(today);
                $('#startPayDate').val('');
                $('#endPayDate').val('');
                $('#btnSearch03').val('1');

                $(".btnSearch01").trigger('click');
            });

            $(document).on('click', '.btnSearch01', function(event) {
                event.preventDefault();
                $('#data-table-001').DataTable().clear();

                $(".myloading").show();

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

                // console.log(business_location_id_fk);
                // console.log(branch_id_fk);
                // console.log(customer_id_fk);
                // console.log(startDate);
                // console.log(endDate);
                // console.log(status_sent);

                // return false;

                if (business_location_id_fk == '') {
                    $('#business_location_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                }
                // alert(branch_id_fk);
                if (branch_id_fk == '' || branch_id_fk === null) {
                    $('#branch_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                }

                // $('#data-table-001').DataTable().clear().draw();

                // wut add
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    oTable_wait_orders = $('#data-table-wait_orders').DataTable({
                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                        processing: true,
                        serverSide: true,
                        scroller: true,
                        destroy: true,
                        ordering: false,
                        iDisplayLength: 50,
                        ajax: {
                            url: '{{ url('backend/pay_product_receipt_tb1/wait_orders') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                business_location_id_fk: business_location_id_fk,
                                branch_id_fk: branch_id_fk,
                                customer_id_fk: customer_id_fk,
                                startDate: startDate,
                                startPayDate: startPayDate,
                                endDate: endDate,
                                endPayDate: endPayDate,
                                status_sent: status_sent,
                                txtSearch_001: txtSearch_001,
                                btnSearch03: btnSearch03,
                                action_user: action_user,
                            },
                            method: 'POST',
                        },
                        columns: [{
                                data: 'id',
                                title: 'ID',
                                className: 'text-center w50'
                            },
                            {
                                data: 'invoice_code',
                                title: '<center>ใบเสร็จ</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'created_at',
                                title: '<center>วันที่ออกใบเสร็จ</center>',
                                className: 'text-left'
                            },
                            {
                                data: 'customer',
                                title: '<center>รหัส:ชื่อสมาชิก</center>',
                                className: 'text-left'
                            },
                            {
                                data: 'action_user',
                                title: '<center>ผู้สร้าง</center>',
                                className: 'text-left'
                            },
                            {
                                data: 'status',
                                title: '<center>สถานะ</center>',
                                className: 'text-center'
                            },
                        ],
                        rowCallback: function(nRow, aData, dataIndex) {}
                    });
                });


                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var txtSearch_001 = $("input[name=txtSearch_001]").val();
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable_001;
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    oTable_001 = $('#data-table-001').DataTable({
                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                        processing: true,
                        serverSide: true,
                        scroller: true,
                        destroy: true,
                        ordering: false,
                        iDisplayLength: 50,
                        ajax: {
                            url: '{{ route('backend.pay_product_receipt_tb1.datatable') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                business_location_id_fk: business_location_id_fk,
                                branch_id_fk: branch_id_fk,
                                customer_id_fk: customer_id_fk,
                                startDate: startDate,
                                startPayDate: startPayDate,
                                endDate: endDate,
                                endPayDate: endPayDate,
                                status_sent: status_sent,
                                txtSearch_001: txtSearch_001,
                                btnSearch03: btnSearch03,
                                action_user: action_user,
                            },
                            method: 'POST',
                        },
                        columns: [{
                                data: 'id',
                                title: 'ID',
                                className: 'text-center w50'
                            },
                            {
                                data: 'invoice_code',
                                title: '<center>ใบเสร็จ</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'bill_date',
                                title: '<center>วันที่ออกใบเสร็จ</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'customer',
                                title: '<center>รหัส:ชื่อสมาชิก</center>',
                                className: 'text-center w180 '
                            },
                            {
                                data: 'status_sent',
                                title: '<center>สถานะ</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'pay_user',
                                title: '<center>ผู้จ่ายสินค้า <br> วันที่จ่ายสินค้า</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'action_user',
                                title: '<center>ผู้ยกเลิกการจ่าย<br>วันที่ดำเนินการ</center>',
                                className: 'text-center'
                            },

                            // {data: 'branch', title :'<center>สาขาที่ดำเนินการ</center>', className: 'text-center'},
                            {
                                data: 'address_send_type',
                                title: '<center>รับที่</center>',
                                className: 'text-center w150'
                            },
                            {
                                data: 'id',
                                title: 'Tools',
                                className: 'text-center w60'
                            },
                        ],
                        rowCallback: function(nRow, aData, dataIndex) {

                            var info = $(this).DataTable().page.info();
                            $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                            if (sU != '' && sD != '') {
                                $('td:last-child', nRow).html('-');
                            } else {

                                // console.log(aData['status_sent_2']);
                                // console.log(aData['status_cancel_all']);
                                // console.log(aData['status_cancel_some']);
                                // เปลี่ยนใหม่ ขอเพียงแค่มีการยกเลิกบางรายการ จะปิดปุ่มยกเลิกทั้งหมด เพราะมันซับซ้อนเกินไป
                                if (aData['status_sent_2'] == 4 || aData[
                                        'status_cancel_some'] == 1) {

                                    $('td:last-child', nRow).html('' +
                                        '<a href="{{ url('backend/pay_product_receipt') }}/' +
                                        aData['id'] +
                                        '/edit" class="btn btn-sm btn-primary" style="' +
                                        sU +
                                        '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

                                    ).addClass('input');

                                    // $('td:eq(4)', nRow).html('-');

                                } else {



                                }

                            }


                            // console.log(can_cancel_bill);
                            // console.log(can_cancel_bill_across_day);
                            // console.log(aData['status_sent_2']);

                            if (can_cancel_bill == '1' && aData['status_sent_2'] == 3) {

                                $('td:last-child', nRow).html('' +
                                    '<a href="{{ url('backend/pay_product_receipt') }}/' +
                                    aData['id'] +
                                    '/edit" class="btn btn-sm btn-primary" style="' +
                                    sU +
                                    '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                                    '<a href="javascript: void(0);" data-url="{{ route('backend.pay_product_receipt_001.index') }}/' +
                                    aData['id'] +
                                    '" class="btn btn-sm btn-danger cDelete2 " data-invoice_code="' +
                                    aData['invoice_code_2'] + '"  data-id="' +
                                    aData['id'] +
                                    '"  data-toggle="tooltip" data-placement="top" title="ยกเลิกรายการจ่ายสินค้าบิลนี้" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                ).addClass('input');



                            } else if (aData['status_sent_2'] == 4) {
                                $('td:last-child', nRow).html('' +
                                    ''
                                ).addClass('input');
                            } else {
                                $('td:last-child', nRow).html('' +
                                    '<a href="{{ url('backend/pay_product_receipt') }}/' +
                                    aData['id'] +
                                    '/edit" class="btn btn-sm btn-primary" style="' +
                                    sU +
                                    '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

                                ).addClass('input');
                            }

                            if (aData['status_sent_2'] == 3) {
                                $('td:eq(6)', nRow).html('');
                            }
                            if (aData['status_sent_2'] == 2) {
                                $('td:eq(6)', nRow).html('');
                            }
                            if (aData['status_sent_2'] == 1) {
                                $('td:eq(5)', nRow).html('');
                                $('td:eq(6)', nRow).html('');
                            }


                        }
                    });

                    oTable_001.on('draw', function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    });
                });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var txtSearch_002 = $("input[name=txtSearch_002]").val();
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable_002;
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    oTable_002 = $('#data-table-002').DataTable({
                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                        processing: true,
                        serverSide: true,
                        scroller: true,
                        destroy: true,
                        ordering: false,
                        // iDisplayLength: 25,
                        ajax: {
                            url: '{{ route('backend.pay_product_receipt_tb2.datatable') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                business_location_id_fk: business_location_id_fk,
                                branch_id_fk: branch_id_fk,
                                customer_id_fk: customer_id_fk,
                                startDate: startDate,
                                endDate: endDate,
                                status_sent: status_sent,
                                txtSearch_002: txtSearch_002,
                            },
                            method: 'POST',
                        },
                        columns: [{
                                data: 'column_001',
                                title: 'รหัส:ชื่อสินค้า',
                                className: 'text-left w200 '
                            },
                            {
                                data: 'column_002',
                                title: '<center>รายการสินค้า</center>',
                                className: 'text-left '
                            },
                            {
                                data: 'column_003',
                                title: '<center>เลขที่ใบเสร็จ : ชื่อผู้จ่าย </center>',
                                className: 'text-left w150 '
                            },
                            {
                                data: 'column_004',
                                title: '<center>สาขา</center>',
                                className: 'text-left '
                            },
                            {
                                data: 'column_005',
                                title: '<center>วันเวลาที่ดำเนินการ</center>',
                                className: 'text-center '
                            },

                        ],
                        rowCallback: function(nRow, aData, dataIndex) {

                        }
                    });

                });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var txtSearch_003 = $("input[name=txtSearch_003]").val();
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var oTable_003;
                // $(function() {
                // 	$.fn.dataTable.ext.errMode = 'throw';
                //     oTable_003 = $('#data-table-003').DataTable({
                //     "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                //         processing: true,
                //         serverSide: true,
                //         scroller: true,
                //         destroy:true,
                //         ordering: false,
                //         ajax: {
                //                   url: '{{ route('backend.pay_product_receipt_tb3.datatable') }}',
                //                   data :{
                //                   	_token: '{{ csrf_token() }}',
                //                         business_location_id_fk:business_location_id_fk,
                //                         branch_id_fk:branch_id_fk,
                //                         customer_id_fk:customer_id_fk,
                //                         startDate:startDate,
                //                         endDate:endDate,
                //                         status_sent:status_sent,
                //                         txtSearch_003:txtSearch_003,
                //                       },
                //                     method: 'POST',
                //                   },
                //         columns: [
                //                 {data: 'column_001', title :'<span style="vertical-align: middle;"> เลขที่ใบเสร็จ </span> ', className: 'text-center w100'},
                //                 {data: 'column_002', title :'<center><span style="vertical-align: middle;"> รายการสินค้า </span></center> ', className: 'text-left w600 '},
                //                 {data: 'column_003', title :'<center><span style="vertical-align: middle;"> รหัส:ชื่อสมาชิก </span></center> ', className: 'text-left '},
                //                 {data: 'column_004', title :'<center><span style="vertical-align: middle;"> สาขา </span></center> ', className: 'text-left '},
                //             ],
                //             rowCallback: function(nRow, aData, dataIndex){
                //             }
                //     });

                // });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@


                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });

        });
    </script>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
            minDate: function() {
                return $('#startDate').val();
            }
        });

        $('#startDate').change(function(event) {

            if ($('#endDate').val() > $(this).val()) {} else {
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
            minDate: function() {
                return $('#startPayDate').val();
            }
        });

        $('#startPayDate').change(function(event) {

            if ($('#endPayDate').val() > $(this).val()) {} else {
                $('#endPayDate').val($(this).val());
            }

        });
    </script>

    <script>
        $(document).ready(function() {
            $(".test_clear_data").on('click', function() {


                if (!confirm(
                        "โปรดระวัง ยืนยัน ! เพื่อล้างข้อมูลรายการสั่งซื้อทั้งหมดเพื่อเริ่มต้นคีย์ใหม่ ? "
                        )) {
                    return false;
                } else {

                    location.replace(window.location.href + "?test_clear_data=test_clear_data ");
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
      $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id;
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check002 ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare002 ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");


      ?>
    <script>
        location.replace("{{ url('backend/pay_product_receipt_001') }}");
    </script>
    <?php
      }
    ?>
@endsection
