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
                <h4 class="mb-0 font-size-18"> ยอดรวมการขายที่เกิดขึ้นจริงทั้งของไทย และ กัมพูชา </h4>
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

                                    <div class="col-md-2 " style="display:none;">
                                        <div class="form-group row">
                                            <select id="business_location"  name="business_location"
                                                class="form-control select2-templating ">
                                                {{-- <option value="">Business Location</option> --}}
                                                @if (@$business_location)
                                                    @foreach (@$business_location as $r)
                                                        <option value="{{ $r->id }}">
                                                            {{ $r->txt_desc }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 " style="display:none;">
                                        <div class="form-group row">
                                            <select id="action_user"  name="action_user"
                                                class="form-control select2-templating ">
                                                <option value="">ผู้ทำรายการ</option>
                                                <option value="v3">
                                                    V3
                                                </option>
                                                @if (@$sUser)
                                                    @foreach (@$sUser as $r)
                                                        <option value="{{ $r->id }}">
                                                            {{ $r->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                            {{-- <button type="button" id="print_btn" class="btn btn-warning btn-sm"
                                                style="font-size: 14px !important;">
                                                <i class="fa fa-print"></i> Print
                                            </button> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">


                    <!-- Tabs navs -->
                    {{-- <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
  <li class="nav-item " role="presentation">
    <a
      class="nav-link tablinks active"
      data-mdb-toggle="tab"
      href="javascript:;"
      role="tab"
      aria-controls="ex1-tabs-1"
      aria-selected="false"
      onclick="openCity(event, 'sale')"
      >ยอดขาย</a
    >
  </li>
  <li class="nav-item " role="presentation">
    <a
      class="nav-link tablinks"
      data-mdb-toggle="tab"
      href="javascript:;"
      role="tab"
      aria-controls="ex1-tabs-2"
      aria-selected="false"
      onclick="openCity(event, 'aicash')"
      >ยอดเติม AI Cash</a
    >
  </li>
</ul> --}}
                    <!-- Tabs navs -->

                    <div class="" id="aicash">
                        {{--  --}}
                        <div class="row mt-2 mb-0">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between"
                                    style="padding-bottom: 0px;">
                                    <h4 class="mb-0 font-size-18"> <u>รายวัน</u></h4>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="thai_cambodia_aicash" class="table table-bordered  thai_cambodia"
                                style="width: 100%;">
                            </table>
                        </div>
                        <br>
                        {{-- <div class="row mt-2 mb-0">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between"
                                    style="padding-bottom: 0px;">
                                    <h4 class="font-size-18"> <u>รายเดือน</u></h4>
                                </div>
                            </div>
                        </div>

                        <table id="data-table-thai_aicash" class="table table-bordered  mt-0" style="width: 100%;"> </table>
                        <br> --}}

                        {{--  --}}
                    </div>
                    <!-- Tabs content -->



                </div>
            </div>
        </div>
    </div>


    <form id="action_form" action="{{ url('backend/total_thai_cambodia_pdf') }}" method="POST" target="_blank"
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
                        <input type="hidden" name="action_user" id="modal_action_user">

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
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00';
        }

        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var total_thai_cambodia_aicash;
        $(function() {
            total_thai_cambodia_aicash = $('#thai_cambodia_aicash').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                // scroller: true,
                // scrollCollapse: true,
                // scrollX: true,
                // ordering: false,
                // scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.total_thai_cambodia_aicash_full.datatable') }}',
                    data: function(d) {
                        d.business_location = $('#business_location').val();
                        d.status_search = $('#status_search').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.action_user = $('#action_user').val();
                        d.report_type = 'day';
                    },
                    method: 'POST'
                },
                columns: [{
                        data: 'customer_name',
                        title: '<center>ลูกค้า</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'action_date',
                        title: '<center>วันที่ทำรายการ</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'invoice',
                        title: '<center>Invoice</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'got',
                        title: '<center>ยอดเติม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'lost',
                        title: '<center>ยอดจ่าย</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_balance',
                        title: '<center>ยอดคงเหลือ</center>',
                        className: 'text-right'
                    },
                ],
                "footerCallback": function(row, data, start, end, display) {}
            });

            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                total_thai_cambodia_aicash.draw();
            });

        });

        // var sU = "{{ @$sU }}";
        // var sD = "{{ @$sD }}";
        // var total_thai_cambodia_month_aicash;
        // $(function() {
        //     total_thai_cambodia_month_aicash = $('#data-table-thai_aicash').DataTable({
        //       "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-12'i><'col-sm-12'p>>",
        //         processing: true,
        //         serverSide: true,
        //           // scroller: true,
        //           // scrollCollapse: true,
        //           // scrollX: true,
        //           // ordering: false,
        //           // scrollY: '' + ($(window).height() - 370) + 'px',
        //         iDisplayLength: 25,
        //         ajax: {
        //             url: '{{ route('backend.total_thai_cambodia_aicash_full.datatable') }}',
        //             data: function(d) {
        //                 d.business_location = $('#business_location').val();
        //                 d.status_search = $('#status_search').val();
        //                 d.startDate = $('#startDate').val();
        //                 d.endDate = $('#endDate').val();
        //                 d.action_user = $('#action_user').val();
        //                 d.report_type = 'month';
        //             },
        //             method: 'POST'
        //         },
        //         columns: [
        //           {
        //                 data: 'customer_name',
        //                 title: '<center>ลูกค้า</center>',
        //                 className: 'text-left'
        //             },
        //             {
        //                 data: 'action_date',
        //                 title: '<center>วันที่ทำรายการ</center>',
        //                 className: 'text-center'
        //             },
        //             {
        //                 data: 'invoice',
        //                 title: '<center>Invoice</center>',
        //                 className: 'text-left'
        //             },
        //             {
        //                 data: 'got',
        //                 title: '<center>ยอดเติม</center>',
        //                 className: 'text-right'
        //             },
        //             {
        //                 data: 'lost',
        //                 title: '<center>ยอดจ่าย</center>',
        //                 className: 'text-right'
        //             },
        //             {
        //                 data: 'total_balance',
        //                 title: '<center>ยอดคงเหลือ</center>',
        //                 className: 'text-right'
        //             },
        //         ],
        //         "footerCallback": function(row, data, start, end, display) {

        //         }
        //     });

        //     $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
        //       total_thai_cambodia_month_aicash.draw();
        //     });

        // });
    </script>

    <script>
        $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
            // total_thai_cambodia.draw();
            // total_thai_cambodia_month.draw();
            total_thai_cambodia_aicash.draw();
            // total_thai_cambodia_month_aicash.draw();
        });

        $('#search-form').on('click', function(e) {

            // total_thai_cambodia.draw();
            // total_thai_cambodia_month.draw();

            total_thai_cambodia_aicash.draw();
            // total_thai_cambodia_month_aicash.draw();
            e.preventDefault();
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

        //  $('#startDate').change(function(event) {
        //    $('#endDate').val($(this).val());
        //  });


        $(document).ready(function() {
            $('#modal_business_location').val($('#business_location').val());
            $('#modal_startDate').val($('#startDate').val());
            $('#modal_endDate').val($('#endDate').val());
            $('#modal_action_user').val($('#action_user').val());
            $(document).on('click', '#print_btn', function(event) {
                $('#modal_business_location').val($('#business_location').val());
                $('#modal_startDate').val($('#startDate').val());
                $('#modal_endDate').val($('#endDate').val());
                $('#modal_action_user').val($('#action_user').val());
                $('#print_modal').modal('show');
            });
            $(document).on('change', '#business_location,#startDate,#endDate,#action_user', function(
                event) {
                $('#modal_business_location').val($('#business_location').val());
                $('#modal_startDate').val($('#startDate').val());
                $('#modal_endDate').val($('#endDate').val());
                $('#modal_action_user').val($('#action_user').val());
            });

        });
    </script>
@endsection
