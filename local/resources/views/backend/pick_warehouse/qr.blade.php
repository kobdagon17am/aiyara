@extends('backend.layouts.master')

@section('title')
    Aiyara Planet
@endsection

@section('css')
    <style type="text/css">
        table.minimalistBlack {
            border: 2px solid #000000;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        table.minimalistBlack td,
        table.minimalistBlack th {
            border: 1px solid #000000;
            padding: 5px 4px;
        }

        table.minimalistBlack tbody td {
            font-size: 13px;
        }

        table.minimalistBlack thead {
            background: #CFCFCF;
            background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
            background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
            background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
            border-bottom: 2px solid #000000;
        }

        table.minimalistBlack thead th {
            font-size: 15px;
            font-weight: bold;
            color: #000000;
            text-align: center;
        }

        table.minimalistBlack tfoot td {
            font-size: 14px;
        }
    </style>
    <style>
        @media screen and (min-width: 676px) {
            .modal-dialog {
                max-width: 1200px !important;
                /* New width for default modal */
            }
        }

        .select2-selection {
            height: 34px !important;
            margin-left: 3px;
        }

        .border-left-0 {
            height: 67%;
        }

        .tooltip_packing {
            position: relative;
        }

        .tooltip_packing:hover::after {
            content: "Packing List";
            position: absolute;
            /*top: 0.5em ;*/
            left: -4em;
            min-width: 80px;
            border: 1px #808080 solid;
            padding: 1px;
            color: black;
            background-color: #cfc;
            z-index: 9999;
        }


        .divTable {
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

        .divTableCell,
        .divTableHead {
            border: 1px solid white;
            display: table-cell;
            padding: 3px 6px;
            word-break: break-all;
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

        .divTH {
            text-align: right;
        }
    </style>
@endsection

@section('content')

    <form class="form-horizontal" method="POST" action="backend/pick_warehouse_scan_save" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal" tabindex="-1" id="scan_modal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Scan Products</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="0" name="delivery_id" id="delivery_id_more">
                        <table id="data-table-scan" class="table table-bordered " style="width: 100%;margin-bottom: 0%;">
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('ยืนยันว่าคุณสแกนสินค้าครบทั้งหมดแล้ว พร้อมระบุขนาดกล่องที่จะจัดส่ง');">Save
                            Complete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form class="form-horizontal" method="POST" action="backend/pick_warehouse_scan_save" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal" tabindex="-1" id="scan_modal_single" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Scan Products</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="0" name="delivery_id" id="delivery_id_single">
                        <table id="data-table-scan-single" class="table table-bordered "
                            style="width: 100%;margin-bottom: 0%;"></table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('ยืนยันการทำรายการ');">Save
                            Complete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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

    <div class="myloading"></div>


    <?php
    $sPermission = \Auth::user()->permission;
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
        $can_packing_list = '1';
        $can_payproduct = '1';
    } else {
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
        $can_packing_list = @$menu_permit->can_packing_list == 1 ? '1' : '0';
        $can_payproduct = @$menu_permit->can_payproduct == 1 ? '1' : '0';
    }
    // echo $sPermission;
    // echo $role_group_id;
    // echo $menu_id;
    // echo  @$menu_permit->can_packing_list;
    // echo  @$menu_permit->can_payproduct;
    // echo $can_packing_list."xxxxxxxxxxxxxxxxxxxxxxxxxxx";
    ?>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">

                <div class="col-6">
                    <h4 class="font-size-18 "> เบิกจ่ายสินค้าจากคลัง </h4>
                    <!-- test_clear_data -->
                </div>

                <div class="col-4" style="padding: 5px;text-align: center;font-weight: bold;margin-left: 5%;">
                    <span>
                        <?php $role = DB::select('SELECT * FROM `role_group` where id=' . \Auth::user()->role_group_id_fk . ' '); ?>
                        <?php $branchs = DB::select('SELECT * FROM `branchs` where id=' . \Auth::user()->branch_id_fk . ' '); ?>
                        <?php $warehouse = DB::select('SELECT * FROM `warehouse` where id=' . $branchs[0]->warehouse_id_fk . '  '); ?>
                        {{ \Auth::user()->name }} / {{ 'สาขา > ' . $branchs[0]->b_name }} / {{ $warehouse[0]->w_name }}
                    </span>
                </div>

                <div class="col-2" style="margin-left: 3%;">
                    @if (isset($_GET['remain']))
                        <a class="btn btn-secondary btn-sm waves-effect"
                            href="{{ url('backend/pay_requisition_001_remain') }}">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    @else
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url('backend/pay_requisition_001') }}">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    @endif
                </div>

            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="col-12">
                        <div class="form-group row  ">

                            <div class="col-12">
                                <div class="form-group row  ">

                                    <div class="col-md-12 ">

                                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i>
                                            รายการเลขพัสดุบริษัทขนส่ง </span>

                                        <table id="warehouse_address_sent" class="table table-bordered "
                                            style="width: 100%;"></table>
                                        <br>

                                        <div class="col-md-12 ">
                                            @if (@$sRow->status != 5 || @\Auth::user()->id == 29 || @\Auth::user()->id == 1 || @\Auth::user()->id == 111)
                                                <span style="font-weight: bold;padding-right: 10px;"><i
                                                        class="bx bx-play"></i> นำเข้าเลขพัสดุจาก Kerry </span>
                                            @endif
                                            <form class="form-horizontal" method="POST"
                                                action="backend/uploadFileXLSConsignments" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="form-group row">

                                                    <input type="hidden" name="requisition_code"
                                                        value="{{ @$requisition_code }}">
                                                    <input type="hidden" name="id" value="{{ @$id }}">

                                                    @if (@$sRow->status != 5 || @\Auth::user()->id == 29 || @\Auth::user()->id == 1 || @\Auth::user()->id == 111)
                                                        <div class="col-md-2">
                                                            <input type="file" accept=".xlsx" class="form-control"
                                                                name="fileXLS" required>
                                                        </div>
                                                        <div class="col-md-2" style="">
                                                            <input type='submit' name="submit"
                                                                class="btn btn-primary btnImXlsx " value='IMPORT'>
                                                            &nbsp;
                                                            &nbsp;
                                                            &nbsp;
                                                            <!-- <input type='button' class="btn btn-primary btnMapConsignments " value='Map Consignments Code'  > -->

                                                            &nbsp;
                                                            &nbsp;
                                                            &nbsp;
                                                            <input type='button' data-id="{{ @$id }}"
                                                                class="btn btn-danger btnClearImport "
                                                                value='Clear เพื่อนำเข้าใหม่'>

                                                        </div>
                                                    @endif

                                                    <div class="col-md-2">
                                                        <a href="backend/pick_warehouse/print_requisition/{{ @$id }}"
                                                            class="btn btn-primary" target=_blank
                                                            title="พิมพ์ใบเบิกสินค้า"><i class="bx bx-printer"></i>
                                                            พิมพ์ใบเบิกสินค้า</a>
                                                    </div>

                                                </div>

                                                @if (Session::has('message'))
                                                    <div class="form-group row ">
                                                        <label for="receipt" class="col-md-2 col-form-label"></label>
                                                        <div class="col-md-6 ">
                                                            <p style="color:green;font-weight:bold;font-size: 16px;">
                                                                {{ Session::get('message') }}</p>
                                                        </div>
                                                    </div>
                                                @endif

                                            </form>

                                        </div>

                                        <br>
                                        <table id="data-table-0001" class="table table-bordered "
                                            style="width: 100%;margin-bottom: 0%;"></table>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="myBorder" style="">
                        <div class="col-12">
                            <div class="form-group row  ">
                                <div class="col-md-12 ">
                                    {{-- วุฒิปิดไว้ --}}
                                    {{-- <table id="data-table-0002" class="table table-bordered " style="width: 100%;margin-bottom: 0%;" ></table> --}}
                                    {{-- <table id="data-table-00022" class="table table-bordered " style="width: 100%;margin-bottom: 0%;" ></table> --}}
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-12">
            <div class="form-group row  " >
              <div class="col-md-12 ">
                <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการบิลรอเบิก </span>

                 <!-- <span style="font-weight: bold;color: red;">*** ตารางนี้อยู่ระหว่างการปรับปรุง *** </span>   -->

                <table id="order_wait_table" class="table table-bordered " style="width: 100%;" ></table>
              <input type='button' class="btn btn-primary btnExportElsx " data-id="{{$id}}" value='ส่งออกไฟล์ Excel (.xlsx) ให้ KERRY' >
            </div>
          </div>
        </div> --}}

                        <div class="col-12">
                            <div class="form-group row  ">
                                <div class="col-md-12 ">
                                    <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i>
                                        แจ้งสถานะการจัดส่ง </span>

                                    <!-- <table id="note" class="table table-bordered " style="width: 60%;" ></table> -->

                                </div>
                            </div>
                        </div>



                        <div class="form-group row">
                            <div class="col-md-12">
                                <?php // echo @$sUser[0]->status_sent;
                                ?>
                                <center>
                                    @if (@$sUser[0]->status_sent == 5)
                                        <span style="color:red;font-size:16px;">*** บ.ขนส่งเข้ามารับสินค้าแล้ว *** </span>
                                    @ELSE
                                        @if ($can_cancel_packing_sent == 1 || $sPermission == 1)
                                            @if (@$sUser[0]->status_sent == 4)
                                                <span style="color:red;font-size:16px;">*** Packing เรียบร้อยแล้ว ***
                                                </span>
                                                <!-- <input style="color: black;" type='button' class="btn btn-warning btnCancelStatusPacking font-size-16 " value="ยกเลิกสถานะการ Packing " > -->
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @ENDIF
                                        @ENDIF
                                    @ENDIF


                                    @if (@$sUser[0]->status_sent == 5)
                                    @ELSE
                                        @if (@$sUser[0]->status_sent == 4)
                                            <input type='button'
                                                class="btn btn-primary btnShippingFinished font-size-16 "
                                                value="แจ้งสถานะว่าบริษัทขนส่งเข้ารับสินค้าแล้ว">
                                        @ELSE
                                            <input type='button' class="btn btn-primary btnPackingFinished font-size-16 "
                                                value="แจ้งสถานะว่า Packing เรียบร้อยแล้ว">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type='button'
                                                class="btn btn-primary btnShippingFinished font-size-16 "
                                                value="บริษัทขนส่งเข้ารับสินค้าแล้ว" disabled="">
                                        @ENDIF
                                    @ENDIF



                                </center>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


@endsection
@section('script')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
        rel="stylesheet" />
    <script type="text/javascript"
        src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script>
        function myFunction() {
            confirm("ยืนยันการทำรายการ?");
        }

        $(document).on('click', '.con_arr_data_show', function() {
            var arr = $(this).attr('con_arr');
            arr = arr.split(",");
            var arr_str = '';
            for (var i = 0; i < arr.length; i++) {
                arr_str += arr[i] + '<br>';
            }
            $('.modal_con_arr_data').html(arr_str);
            $('#modal_con_arr').modal('show');
        });
    </script>
    <script>
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        var id = "{{ $id }}"; //alert(id);
        var oTable0001;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTable0001 = $('#data-table-0001').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: false,
                deferRender: true,
                scrollCollapse: false,
                scrollX: false,
                ordering: false,
                ordering: false,
                info: false,
                paging: false,
                ajax: {
                    url: '{{ route('backend.warehouse_qr_0001.datatable') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                },
                columns: [{
                        data: 'column_001',
                        title: 'รหัสใบเบิก',
                        className: 'text-center w231'
                    },
                    {
                        data: 'column_002',
                        title: 'รายการบิลในใบเบิก <u>(คลิกที่รายการเพื่อ Scan)</u>',
                        className: 'text-center '
                    },
                    // {data: 'p_weight', title :'จำนวนกล่อง', className: 'text-center '},
                    // {data: 'column_003', title :'รายละเอียดลูกค้า', className: 'text-center '},
                    // {data: 'column_002',   title :'<span style="vertical-align: middle;"><center> รายการ Packing List </span> ', className: 'text-left',render: function(d) {
                    //   return d;
                    // }},
                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    $(".myloading").hide();
                }
            });

        });
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>

    <script>
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        var id = "{{ $id }}"; //alert(id);
        var packing_id = "{{ $packing_id }}"; //alert(packing_id);
        var oTable0002;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTable0002 = $('#data-table-0002').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                deferRender: true,
                scroller: false,
                scrollCollapse: false,
                scrollX: false,
                ordering: false,
                ordering: false,
                info: false,
                paging: false,
                ajax: {
                    url: '{{ route('backend.warehouse_qr_0002.datatable') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        picking_id: packing_id,
                        id: id
                    },
                },
                columns: [
                    // {data: 'column_001', title :'<span style="vertical-align: middle;"> ชุดที่  </span> ', className: 'text-center w70'},
                    {
                        data: 'column_001',
                        title: '<span style="vertical-align: middle;"> รายการจัดส่งรวมแพ็ค (กรณี Packing list )  </span> ',
                        className: 'text-left '
                    },
                    {
                        data: 'column_002',
                        title: '<span style="vertical-align: middle;"><center> รายการสินค้า  </span> ',
                        className: 'text-left '
                    },
                    {
                        data: 'column_003',
                        title: '<span style="vertical-align: middle;"><center>   </span> ',
                        className: 'text-left '
                    },

                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    // $(".myloading").hide();
                    // var info = $(this).DataTable().page.info();
                    //  $("td:eq(0)", nRow).html("<b>" + (info.start + dataIndex + 1) + "</b>");
                },
                fnDrawCallback: function() {
                    if ($(this).find('.dataTables_empty').length == 1) {
                        $(this).parent().hide();
                    }
                }
            });

        });

        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>


    <script>
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        var id = "{{ $id }}"; //alert(id);
        var packing_id = "{{ $packing_id }}"; //alert(packing_id);
        var oTable0002;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTable0002 = $('#data-table-00022').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                deferRender: true,
                scroller: false,
                scrollCollapse: false,
                scrollX: false,
                ordering: false,
                ordering: false,
                info: false,
                paging: false,
                ajax: {
                    url: '{{ route('backend.warehouse_qr_00022.datatable') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        picking_id: packing_id,
                        id: id
                    },
                },
                columns: [
                    // {data: 'column_001', title :'<span style="vertical-align: middle;"> ชุดที่  </span> ', className: 'text-center w70'},
                    {
                        data: 'column_001',
                        title: '<span style="vertical-align: middle;"> รายการจัดส่งแยกแพ็ค (กรณีแยกบิล) </span> ',
                        className: 'text-left '
                    },
                    {
                        data: 'column_002',
                        title: '<span style="vertical-align: middle;"><center> รายการสินค้า  </span> ',
                        className: 'text-left '
                    },
                    {
                        data: 'column_003',
                        title: '<span style="vertical-align: middle;"><center>   </span> ',
                        className: 'text-left '
                    },

                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    // $(".myloading").hide();
                    // var info = $(this).DataTable().page.info();
                    //  $("td:eq(0)", nRow).html("<b>" + (info.start + dataIndex + 1) + "</b>");

                },
                fnDrawCallback: function() {
                    if ($(this).find('.dataTables_empty').length == 1) {
                        $(this).parent().hide();
                    }
                }
            });

        });

        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>

    <script>
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        // ส่ง ajax ไป insert data to db_consignments

        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        var id = "{{ $id }}"; //alert(id);
        var packing_id = "{{ $packing_id }}"; //alert(packing_id);
        var requisition_code = "{{ $requisition_code }}"; //alert(requisition_code);
        var oTable0005;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTable0005 = $('#warehouse_address_sent').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                deferRender: true,
                scroller: false,
                scrollCollapse: false,
                scrollX: false,
                ordering: false,
                ordering: false,
                info: false,
                paging: false,
                ajax: {
                    url: '{{ route('backend.warehouse_address_sent.datatable') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        packing_id: packing_id,
                        id: id
                    },
                },
                columns: [{
                        data: 'column_001',
                        title: '<span style="vertical-align: middle;"> Recipient Code <br> (รหัสผู้รับ)  </span> ',
                        className: 'text-center w80'
                    },
                    {
                        data: 'column_002',
                        title: '<span style="vertical-align: middle;"><center> Recipient Name (ชื่อผู้รับ) / Address (ที่อยู่ผู้รับ) </span> ',
                        className: 'text-left',
                        render: function(d) {
                            return d;
                        }
                    },
                    {
                        data: 'column_003',
                        title: '<span style="vertical-align: middle;"><center> ส่งรายการให้ Kerry <br> เพื่อขอเลขพัสดุ </span> ',
                        className: 'text-center',
                        render: function(d) {
                            return d;
                        }
                    },
                    {
                        data: 'column_004',
                        title: '<span style="vertical-align: middle;"><center> หมายเลขพัสดุ <br> (Consignment number) </span> ',
                        className: 'text-center w150 ',
                        render: function(d) {
                            return d;
                        }
                    },

                    {
                        data: 'column_007',
                        title: '<span style="vertical-align: middle;"><center> รายละเอียดลูกค้า </span> ',
                        className: 'text-center w80 ',
                        render: function(d) {
                            return d;
                        }
                    },

                    {
                        data: 'column_008',
                        title: '<span style="vertical-align: middle;"><center> ใบเสร็จ </span> ',
                        className: 'text-center w80 ',
                        render: function(d) {
                            return d;
                        }
                    },

                    {
                        data: 'column_006',
                        title: '<span style="vertical-align: middle;"><center> จำนวนกล่อง  </span> ',
                        className: 'text-center w100 ',
                        render: function(d) {
                            return d;
                        }
                    },


                    {
                        data: 'column_005',
                        title: '<span style="vertical-align: middle;"><center> ใบปะหน้ากล่อง </span> ',
                        className: 'text-center w80 ',
                        render: function(d) {
                            return d;
                        }
                    },

                    {
                        data: 'column_del',
                        title: '<span style="vertical-align: middle;"><center> ยกเลิกรายการ </span> ',
                        className: 'text-center',
                        render: function(d) {
                            return d;
                        }
                    },

                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    // $(".myloading").hide();
                }
            });

            oTable0005.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

        });
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>


    <script>
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
        var id = "{{ $id }}";
        var oTableNote;
        $(function() {
            $.fn.dataTable.ext.errMode = 'throw';
            oTableNote = $('#note').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: false,
                deferRender: true,
                scrollCollapse: false,
                scrollX: false,
                ordering: false,
                ordering: false,
                info: false,
                paging: false,
                ajax: {
                    url: '{{ route('backend.warehouse_qr_0001.datatable') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                },
                columns: [{
                        data: 'column_001',
                        title: '<span style="vertical-align: middle;"><center> แจ้งสถานะ </span> ',
                        className: 'text-left',
                        render: function(d) {
                            return '';
                        }
                    },
                    {
                        data: 'column_001',
                        title: '<span style="vertical-align: middle;"><center> สถานะ </span> ',
                        className: 'text-left',
                        render: function(d) {
                            return 'รอแจ้งสถานะ/แจ้งแล้ว';
                        }
                    },
                    {
                        data: 'column_001',
                        title: '<span style="vertical-align: middle;"><center> ยกเลิก </span> ',
                        className: 'text-left',
                        render: function(d) {
                            return '';
                        }
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {}
            });

        });
        // @@@@@@@@@@@@@@@@@@@@@@@@@ DataTable @@@@@@@@@@@@@@@@@@@@@@@
    </script>

    <script>
        $(document).ready(function() {

            $(document).on('click', '.print02', function(event) {

                event.preventDefault();
                $(".myloading").show();

                var id = $(this).data('id');
                var pick_pack_packing_code_id_fk = "{{ $id }}";
                // console.log(id);
                setTimeout(function() {
                    window.open("{{ url('backend/frontstore/print_receipt_023') }}" + "/" + id +
                        "/" + pick_pack_packing_code_id_fk);
                    $(".myloading").hide();
                }, 500);

            });

        });

        $(document).on('click', '.print_slip', function(event) {

            event.preventDefault();

            $(".myloading").show();

            var id = $(this).data('id');

            // // console.log(id);

            setTimeout(function() {
                window.open("{{ url('backend/frontstore/print_receipt_022') }}" + "/" + id);
                $(".myloading").hide();
            }, 500);


        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('change', '.p_size', function(e) {
                var p_size = $(this).val();
                var id = $(this).data('id');
                var box_id = $(this).attr('box-id');
                $(".myloading").show();
                $.ajax({
                    type: 'POST',
                    url: " {{ url('backend/ajaxProductPackingSize') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        p_size: p_size,
                        id: id,
                        box_id: box_id
                    },
                    success: function(data) {
                        // console.log(data);
                        $(".myloading").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });
            });

            $(document).on('change', '.p_weight', function(e) {
                var p_weight = $(this).val();
                var id = $(this).data('id');
                var box_id = $(this).attr('box-id');
                $(".myloading").show();
                $.ajax({
                    type: 'POST',
                    url: " {{ url('backend/ajaxProductPackingWeight') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        p_weight: p_weight,
                        id: id,
                        box_id: box_id
                    },
                    success: function(data) {
                        // console.log(data);
                        $(".myloading").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });
            });

            $(document).on('change', '.p_amt_box', function(e) {
                var p_amt_box = $(this).val();
                var id = $(this).data('id');
                var box_id = $(this).attr('box-id');
                $(".myloading").show();
                $.ajax({
                    type: 'POST',
                    url: " {{ url('backend/ajaxProductPackingAmtBox') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        p_amt_box: p_amt_box,
                        id: id,
                        box_id: box_id
                    },
                    success: function(data) {
                        // console.log(data);
                        $(".myloading").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });
            });

            $(document).on('change', '.qr_scan', function(e) {

                var v = $(this).val();
                var this_data = $(this);
                var packing_code = $(this).data('packing_code');
                var product_id_fk = $(this).data('product_id_fk');
                var invoice_code = $(this).attr('invoice_code');
                var packing_list = $(this).attr('packing_list');

                var p = '<tr class="qr_scaned_tr">' +
                    '<td>' + v + '</td>' +
                    '<input class="qr_scaned" type="hidden" name="qr_scaned[]" value="' + v + '">' +
                    '<td width="10%;"><i class="fa fa-trash qr_scanned_remove" style="color:red;"></i></td>' +
                    '</tr>';

                $('#table_qr_scaned').append(p);
                $('.btnDeleteQrcodeProduct').trigger('click');
                this_data.val('');

                // $(".myloading").show();
                // $.ajax({
                //     type: 'POST',
                //     url: " {{ url('backend/ajaxScanQrcodeProductPacking') }} ",
                //     data: {
                //         _token: '{{ csrf_token() }}',
                //         invoice_code: invoice_code,
                //         qr_code: v,
                //         packing_code: packing_code,
                //         packing_list: packing_list,
                //         product_id_fk: product_id_fk
                //     },
                //     success: function(data) {
                //         $(".myloading").hide();
                //         $('.btnDeleteQrcodeProduct').trigger('click');
                //         $('.last_scan').html(data);
                //         this_data.val('');
                //     },
                //     error: function(jqXHR, textStatus, errorThrown) {
                //         $(".myloading").hide();
                //     }
                // });
            });

            $(document).on('click', '.qr_scanned_remove', function(e) {
                $(this).closest('.qr_scaned_tr').remove();
            });

            $(document).on('click', '#qr_scaned_submit', function(e) {
              var packing_code = $(this).data('packing_code');
              var product_id_fk = $(this).data('product_id_fk');
              var invoice_code = $(this).attr('invoice_code');
              var packing_list = $(this).attr('packing_list');
              var v = '';
              $(".qr_scaned").each(function( index ) {
                console.log( index + ": " + $( this ).text() );
                v+= $( this ).val()+',';
              });
              // alert(v);
              if( $('.qr_scaned').length == 0){
                alert('กรุณาสแกน QR ก่อนบันทึก');
                return false;
              }else{
                $(".myloading").show();
                $.ajax({
                    type: 'POST',
                    url: " {{ url('backend/ajaxScanQrcodeProductPacking') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        invoice_code: invoice_code,
                        qr_code: v,
                        packing_code: packing_code,
                        packing_list: packing_list,
                        product_id_fk: product_id_fk
                    },
                    success: function(data) {
                      $(".qr_scaned_tr").each(function( index ) {
                        $(this).remove();
                      });
                        $(".myloading").hide();
                        alert('บันทึก QR สำเร็จ');
                        // $('.btnDeleteQrcodeProduct').trigger('click');
                        // $('.last_scan').html(data);
                        // this_data.val('');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });
              }
            });

            $(document).on('click', '.btnDeleteQrcodeProduct', function(e) {
                $(this).prev().val('');
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $(".btnGentoExport").click(function(event) {
                /* Act on the event */
                $(".myloading").show();
                $.ajax({

                    type: 'POST',
                    url: " {{ url('backend/ajaxGentoExportConsignments') }} ",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                        $(".myloading").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        $(".myloading").hide();
                    }
                });
            });


            // ปุ่ม export excel ก่อนเอาไปใส่ kerry
            $(document).on('click', '.btnExportElsx', function(event) {

                /* Act on the event */
                $(".myloading").show();
                var id = $(this).data('id');
                console.log(id);
                $.ajax({

                    type: 'POST',
                    url: " {{ url('backend/excelExportConsignment') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        // location.reload();
                        setTimeout(function() {
                            var url = 'local/public/excel_files/consignments.xlsx';
                            window.open(url, 'Download');
                            $(".myloading").hide();
                        }, 3000);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        $(".myloading").hide();
                    }
                });
            });


            $(document).on('click', '.btnImXlsx', function(event) {
                /* Act on the event */
                var v = $("input[name=fileXLS]").val();
                if (v != '') {
                    $(".myloading").show();


                    // setTimeout(function(){
                    //    $(".btnMapConsignments").trigger('click');
                    // }, 2500);

                }

            });



            $(".btnMapConsignments").click(function(event) {
                /* Act on the event */
                $(".myloading").show();

                $.ajax({

                    type: 'POST',
                    url: " {{ url('backend/ajaxMapConsignments') }} ",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);
                        $(".myloading").hide();
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        $(".myloading").hide();
                    }
                });

            });

            $(".btnClearImport").click(function(event) {
                /* Act on the event */
                $(".myloading").show();
                var id = $(this).data('id');

                $.ajax({

                    type: 'POST',
                    url: " {{ url('backend/ajaxClearConsignment') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        $(".myloading").hide();
                    }
                });
            });






            $(".btnPackingFinished").click(function(event) {
                // $(".myloading").show();

                var id = "{{ $id }}";

                Swal.fire({
                    title: 'ยืนยัน ! Packing เรียบร้อยแล้ว ',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: "#f46a6a"
                }).then(function(result) {
                    if (result.value) {

                        $(".myloading").show();

                        $.ajax({
                            method: 'POST',
                            url: " {{ url('backend/ajaxPackingFinished') }} ",
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(data) {
                                console.log(data);
                                location.replace(
                                    '{{ url('backend/pay_requisition_001') }}');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' +
                                    errorThrown);
                                $(".myloading").hide();
                            }
                        });

                    } else {
                        $(".myloading").hide();
                    }
                });


            });


            $(".btnShippingFinished").click(function(event) {
                // $(".myloading").show();

                var id = "{{ $id }}";

                Swal.fire({
                    title: 'ยืนยัน ! บริษัทขนส่งเข้ารับสินค้า เรียบร้อยแล้ว ',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: "#f46a6a"
                }).then(function(result) {
                    if (result.value) {

                        $(".myloading").show();

                        $.ajax({
                            method: 'POST',
                            url: " {{ url('backend/ajaxShippingFinished') }} ",
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(data) {
                                console.log(data);
                                location.replace(
                                    '{{ url('backend/pay_requisition_001') }}');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' +
                                    errorThrown);
                                $(".myloading").hide();
                            }
                        });

                    } else {
                        $(".myloading").hide();
                    }
                });


            });



        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/scannerdetection/1.2.0/jquery.scannerdetection.min.js"
        integrity="sha512-ZmglXekGlaYU2nhamWrS8oGQDJQ1UFpLvZxNGHwLfT0H17gXEqEk6oQBgAB75bKYnHVsKqLR3peLVqMDVJWQyA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // $(document).on('keypress', '.in-tx', function(e) {
            //      if (e.keyCode == 13) {
            //           // alert("13");
            //         //  var index = $('.in-tx').index(this) + 1;
            //         //  $('.in-tx').eq(index).focus();
            //         // $('.in-tx').eq($('.in-tx').index(this)).change();
            //           return false; // block form from being submitted yet
            //      }
            // });

            // $(document).on('keyup', '.in-tx', function(e) {
            //   if (this.value.length >= 10) {
            //     // $('.in-tx').eq($('.in-tx').index(this)).change();
            //     // $('.in-tx').index(this).focus();
            //         //  var index = $('.in-tx').index(this) + 1;
            //         //  $('.in-tx').eq(index).focus();
            //           return false;
            //         }
            // });

            $(window).scannerDetection();

            $(window)
                .bind('scannerDetectionComplete', function(e, data) {
                    // element where your barcode is to be entered even if its not focused
                    $("#barcode").val(data.string);
                    console.log("Barcode Scanned" + data.string);
                    $('.qr_scan').change();
                    //  $('.in-tx').next().focus();
                    //  $('.in-tx').eq($('.in-tx').index(this)).change();

                    return false; // block form from being submitted yet
                })
                .bind('scannerDetectionError', function(e, data) {
                    // console.log("Error in Barcode Scanning");
                    //  $('.in-tx').next().focus();
                    //  $('.in-tx').eq($('.in-tx').index(this)).change();
                    return false; // block form from being submitted yet
                })
                .bind('scannerDetectionReceive', function(e, data) {
                    console.log("recieving data:" + data);
                    //  $('.in-tx').next().focus();
                    //  $('.in-tx').eq($('.in-tx').index(this)).change();
                    return false; // block form from being submitted yet
                })
        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', '.btnCancelStatusPacking', function() {

                // $(".myloading").show();

                var id = "{{ $id }}";
                // alert(id);
                // return false;

                Swal.fire({
                    title: 'ยืนยัน ! ยกเลิก สถานะการจัดส่ง ',
                    // text: 'You clicked the button!',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: "#f46a6a"
                }).then(function(result) {
                    if (result.value) {

                        $.ajax({
                            url: " {{ url('backend/cancel-status-packing-sent') }} ",
                            method: "post",
                            data: {
                                id: id,
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(data) {
                                // console.log(data);
                                // return false;
                                // Swal.fire({
                                //   type: 'success',
                                //   title: 'ทำการยกเลิกการจ่ายครั้งนี้เรียบร้อยแล้ว',
                                //   showConfirmButton: false,
                                //   timer: 2000
                                // });

                                setTimeout(function() {
                                    // $('#data-table-0002').DataTable().clear().draw();
                                    location.replace(
                                        "{{ url('backend/pay_requisition_001') }}"
                                    );
                                }, 1000);
                            }
                        })

                    } else {
                        $(".myloading").hide();
                    }
                });

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

      DB::select("TRUNCATE db_consignments;");

      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id;

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");


      ?>
    <script>
        location.replace("{{ url('backend/pick_warehouse') }}");
    </script>
    <?php
      }
    ?>

    {{-- วุฒิเพิ่ม --}}
    <script>
        $(document).on('click', '.btn_0002_add', function() {
            var row = $(this).closest('.row_0002').clone();
            var div = $(this).closest('.row_0002');
            var id = $(this).closest('.row_0002').find('.p_size').attr('data-id');
            // insertAfter("div.row_0002:last");
            // เพิ่มข้อมูลลงฐานข้อมูล
            var p_size = $(this).val();
            $.ajax({
                type: 'POST',
                url: " {{ url('backend/ajaxProductPackingAddBox') }} ",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(data) {
                    $(".myloading").hide();
                    //  console.log('p_size '+ data.box_id);
                    row.find('.p_size').removeAttr('data-id');
                    row.find('.p_size').attr('data-id', id);
                    row.find('.p_size').removeAttr('box-id');
                    row.find('.p_size').attr('box-id', data.box_id);
                    row.find('.p_size').val('');
                    row.find('.p_weight').removeAttr('data-id');
                    row.find('.p_weight').attr('data-id', id);
                    row.find('.p_weight').removeAttr('box-id');
                    row.find('.p_weight').attr('box-id', data.box_id);
                    row.find('.p_weight').val('');
                    // console.log('p_amt_box '+ data.box_id);
                    // row.find('.p_amt_box').removeAttr('data-id');
                    // row.find('.p_amt_box').attr('data-id',id);
                    // row.find('.p_amt_box').removeAttr('box-id');
                    // row.find('.p_amt_box').attr('box-id',data.box_id);
                    // row.find('.p_amt_box').val('');
                    div.after(row);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $(".myloading").hide();
                }
            });
        });

        $(document).on('click', '.btn_0002_remove', function() {
            var l = $('.row_0002').length;
            var div = $(this).closest('.row_0002');
            var box_id = div.find('.p_size').attr('box-id');
            if (l > 1) {
                $.ajax({
                    type: 'POST',
                    url: " {{ url('backend/ajaxProductPackingRemoveBox') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        box_id: box_id
                    },
                    success: function(data) {
                        $(".myloading").hide();
                        div.remove();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $(".myloading").hide();
                    }
                });

            } else {
                alert('ไม่สามารถลบรายการสุดท้ายได้');
            }

        });
    </script>

    <script>
        $(document).on('click', '.pack_modal', function() {
            var id = "{{ $id }}"; //alert(id);
            var packing_id = "{{ $packing_id }}"; //alert(packing_id);
            var oTable0002;
            var delivery_id = $(this).attr('delivery_id');
            var data_id = $(this).attr('data_id');
            $('#delivery_id_more').val(delivery_id);
            $(function() {
                $.fn.dataTable.ext.errMode = 'throw';
                oTable0002 = $('#data-table-scan').DataTable({
                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    scroller: false,
                    scrollCollapse: false,
                    scrollX: false,
                    ordering: false,
                    ordering: false,
                    info: false,
                    paging: false,
                    destroy: true,
                    ajax: {
                        url: '{{ url('backend/warehouse_qr_0002/warehouse_qr_0002_pack_scan') }}',
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            data_id: data_id,
                            delivery_id: delivery_id,
                            picking_id: packing_id,
                            id: id
                        },
                    },
                    columns: [
                        // {data: 'column_001', title :'<span style="vertical-align: middle;"> ชุดที่  </span> ', className: 'text-center w70'},
                        {
                            data: 'column_001',
                            title: '<span style="vertical-align: middle;"> รายการจัดส่งรวมแพ็ค </span> ',
                            className: 'text-left '
                        },
                        {
                            data: 'column_002',
                            title: '<span style="vertical-align: middle;"><center> รายการสินค้า  </span> ',
                            className: 'text-left '
                        },
                        {
                            data: 'column_003',
                            title: '<span style="vertical-align: middle;"><center> ข้อมูลอื่นๆ  </span> ',
                            className: 'text-left '
                        },
                    ],
                    rowCallback: function(nRow, aData, dataIndex) {
                        // $(".myloading").hide();
                        // var info = $(this).DataTable().page.info();
                        //  $("td:eq(0)", nRow).html("<b>" + (info.start + dataIndex + 1) + "</b>");
                    },
                    fnDrawCallback: function() {
                        if ($(this).find('.dataTables_empty').length == 1) {
                            $(this).parent().hide();
                        }
                    }
                });

            });

            $('#scan_modal').modal('show');
        });
    </script>
@endsection
