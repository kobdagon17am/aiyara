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
    </style>
@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> รายงานการโอนค่าคอมมิชชั่น AF ประจำรอบ </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <?php
    $sPermission = \Auth::user()->permission;
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
    } else {
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
    }
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12 d-flex ">
                                    <div class="col-md-4 ">
                                        <div class="form-group row">
                                            <select id="business_location" name="business_location"
                                                class="form-control select2-templating ">
                                                <option value="">Business Location</option>
                                                @if (@$business_location)
                                                    @foreach (@$business_location as $r)
                                                        <option value="{{ $r->country_id_fk }}">
                                                            {{ $r->txt_desc }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group row">
                                            <select id="status_search" name="status_search"
                                                class="form-control select2-templating ">
                                                <option value="">สถานะ</option>
                                                <option value="0">รออนุมัติ</option>
                                                <option value="1">โอนสำเร็จ</option>
                                                <option value="3">ไม่อนุมัติ</option>
                                                <option value="2">ยกเลิก</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex  ">
                                        <input id="startDate" autocomplete="off" value="{{ date('1/m/Y') }}"
                                            placeholder="วันเริ่ม" />
                                        <input id="endDate" autocomplete="off" value="{{ date('t/m/Y') }}"
                                            placeholder="วันสิ้นสุด" />
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group row"> &nbsp; &nbsp;
                                            <button type="button" id="search-form"
                                                class="btn btn-success btn-sm waves-effect btnSearchInList "
                                                style="font-size: 14px !important;">
                                                <i class="bx bx-search font-size-16 align-middle mr-1"></i> ค้น
                                            </button> &nbsp;
                                            <button type="button" id="print_btn" class="btn btn-warning btn-sm"
                                                style="font-size: 14px;">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        {{-- <div class="col-4 text-right" style="{{ @$sC }}">
                            <!--    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.check_money_daily.create') }}">
                          <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                        </a> -->
                        </div> --}}

                    </div>

                    <table id="data-table" class="table table-centered table-nowrap table-hover w-100">
                        <tfoot>
                            <tr>
                                <th colspan="4" style="text-align: left !important"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="view_transfer"></div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <form id="action_form" action="{{ url('backend/commission_transfer_af_pdf') }}" method="POST" target="_blank"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <div id="print_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">รายงาน</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="business_location" id="modal_business_location">
                        <input type="hidden" name="status_search" id="modal_status_search">
                        <input type="hidden" name="startDate" id="modal_startDate">
                        <input type="hidden" name="endDate" id="modal_endDate">

                        เลือกรูปแบบรายงาน
                        <select name="report_type" id="report_type" class="form-control">
                            <option value="day">รายงานรายวัน</option>
                            <option value="month">รายงานรายเดือน</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="print_submit">Print</button>
                    </div>
                </div>

            </div>
        </div>
    </form>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#modal_business_location').val($('#business_location').val());
            $('#modal_status_search').val($('#status_search').val());
            $('#modal_startDate').val($('#startDate').val());
            $('#modal_endDate').val($('#endDate').val());

            $(document).on('click', '#print_btn', function(event) {
                $('#modal_business_location').val($('#business_location').val());
                $('#modal_status_search').val($('#status_search').val());
                $('#modal_startDate').val($('#startDate').val());
                $('#modal_endDate').val($('#endDate').val());

                $('#print_modal').modal('show');
            });
            $(document).on('change', '#business_location,#startDate,#endDate,#action_user', function(
                event) {
                $('#modal_business_location').val($('#business_location').val());
                $('#modal_status_search').val($('#status_search').val());
                $('#modal_startDate').val($('#startDate').val());
                $('#modal_endDate').val($('#endDate').val());

            });
        });

        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var oTable;

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00';
        }

        $(function() {
            oTable = $('#data-table').DataTable({
                // "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.commission_af.datatable') }}',
                    data: function(d) {
                        d.business_location = $('#business_location').val();
                        d.status_search = $('#status_search').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                    method: 'POST'
                },

                columns: [{
                        data: 'action_date',
                        title: '<center>วันที่โอน</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'location',
                        title: 'Location',
                        className: 'text-center'
                    },
                    {
                        data: 'tax_number',
                        title: 'เลขประจำตัวผู้เสียภาษี',
                        className: 'text-center'
                    },
                    {
                        data: 'id',
                        title: 'UserName',
                        className: 'text-center'
                    },
                    {
                        data: 'cus_name',
                        title: '<center>ชื่อ-นามสกุล</center>',
                        className: 'text-left'
                    },

                    {
                        data: 'bonus_total',
                        title: '<center>Commission</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'tax_percent',
                        title: '<center>% หัก ณ ที่จ่าย</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'tax',
                        title: '<center>ภาษีหัก ณ ที่จ่าย</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'fee',
                        title: '<center>ค่าธรรมเนียม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'price_transfer_total',
                        title: '<center>ยอดโอนสุทธิ</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'bank_name',
                        title: '<center>ธนาคารปลายทาง </center>',
                        className: 'text-left'
                    },
                    {
                        data: 'bank_account',
                        title: '<center>เลขที่บัญชี</center>',
                        className: 'text-left'
                    },

                    {
                        data: 'transfer_result',
                        title: '<center>ผลการโอน</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'note',
                        title: '<center>หมายเหตุ</center>',
                        className: 'text-left'
                    },

                ],
                order: [
                    [0, 'DESC']
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    bonus_total = api
                        .column(4, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    tax = api
                        .column(6, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    fee = api
                        .column(7, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    price_transfer_total = api
                        .column(8, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // Update footer
                    $(api.column(0).footer()).html('Total');
                    $(api.column(4).footer()).html(numberWithCommas(bonus_total));
                    $(api.column(6).footer()).html(numberWithCommas(tax));
                    $(api.column(7).footer()).html(numberWithCommas(fee));
                    $(api.column(8).footer()).html(numberWithCommas(price_transfer_total));
                }
            });
            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                oTable.draw();
            });

            $('#search-form').on('click', function(e) {

                oTable.draw();
                e.preventDefault();
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
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
        });
        $('#endDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function() {
                return $('#startDate').val();
            }
        });

        // $('#startDate').change(function(event) {
        //     $('#endDate').val($(this).val());
        // });
    </script>
@endsection
