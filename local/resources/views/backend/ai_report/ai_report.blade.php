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
    </style>

    <style>
        #notification {
            position: absolute;
            top: 150;
            margin-left: 60%;
            /*margin-right: 5% ;*/
            /*float: right;*/
        }

        .toast-color {
            color: white;
            background-color: #33b5e5;
            border-radius: 5px;
        }

        h1 {
            color: green;
        }
    </style>
@endsection

@section('content')
    <div class="myloading"></div>
    <!-- start page title -->

    <div class="toast toast-color" id="notification" data-delay="3000">
        <div class="toast-body">
            ! พบรายการ Ai-Cash ที่ยังรอการชำระ
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18  "> {{ __('message.ai_report') }} </h4>
                <!-- test_clear_data -->
            </div>
        </div>
    </div>
    <!-- end page title -->
    <?php
    $sPermission = \Auth::user()->permission;
    $menu_id = Session::get('session_menu_id');
    // print_r($menu_id);
    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
        $sA = 1;
    } else {
        $role_group_id = \Auth::user()->role_group_id_fk;
        // print_r($role_group_id);
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        // print_r($menu_permit);
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
        $sA = @$menu_permit->can_approve;
    }

    // print_r($sD);

    ?>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="customer_id" class="col-md-3 col-form-label">
                                    {{ __('message.info_member_id') }} : </label>
                                <div class="col-md-9">
                                    <select id="customer_id" name="customer_id" class="form-control customer_id_fk">
                                        <option value="">-Select-</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="customer_name" class="col-md-3 col-form-label">
                                    {{ __('message.info_member_name') }} : </label>
                                <div class="col-md-9">
                                    <select id="customer_name" name="customer_name" class="form-control customer_name_fk">
                                        <option value="">-Select-</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="ai_list" class="col-md-3 col-form-label">
                                    AI List : </label>
                                <div class="col-md-9">
                                    <select id="ai_list" name="ai_list" class="form-control ai_list">
                                        <option value="1">AI Stockist</option>
                                        <option value="2">AI Cash</option>
                                        <option value="3">AI Voucher</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="startDate" class="col-md-3 col-form-label"> {{ __('message.date') }} : </label>
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
                                <label for="report_type" class="col-md-3 col-form-label">
                                    Report type : </label>
                                <div class="col-md-9">
                                    <select id="report_type" name="report_type" class="form-control report_type">
                                        <option value="1">Date</option>
                                        <option value="2">Month</option>
                                        <option value="3">Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6 " style="margin-top: -1% !important;">

                        </div>

                        <div class="col-md-6 " style="margin-top: -0.5% !important;">
                            <div class="form-group row">
                                <label for="branch_id_fk" class="col-md-3 col-form-label"> </label>
                                <div class="col-md-9">
                                    <a class="btn btn-info btn-sm btnSearch01 " href="#"
                                        style="font-size: 14px !important;margin-left: 0.8%;">
                                        <i class="bx bx-search align-middle "></i> SEARCH
                                    </a>



                                </div>
                            </div>
                        </div>
                    </div>
                    <br>


                    <div class="row">
                        <div class="col-8">
                            <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="รหัสย่อ" name="short_code"> -->
                        </div>


                    </div>

                    <table id="data-table" class="table table-bordered " style="width: 100%;">
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('script')
    <script>
        var oTable;
        $(function() {
            oTable = $('#data-table').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                destroy: true,
                ordering: false,
                // scrollY: ''+($(window).height()-370)+'px',
                iDisplayLength: 1000000,
                ajax: {
                    url: '{{ url('backend/ai_report/datatable') }}',
                    data: function(d) {
                        d.Where = {};
                        $('.myWhere').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Where[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Like = {};
                        $('.myLike').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Like[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Custom = {};
                        $('.myCustom').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Custom[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        oData = d;
                    },
                    method: 'POST'
                },

                columns: [
                  // {
                  //       data: 'id',
                  //       title: 'ID',
                  //       className: 'text-center w50'
                  //   },
                    {
                        data: 'action_date',
                        title: '<center>วันที่ทํารายการ</center>',
                        className: 'text-center w100 '
                    },
                    {
                        data: 'member_id',
                        title: '<center>รหัสสมาชิก</center>',
                        className: 'text-left w100 '
                    },
                    {
                        data: 'member_name',
                        title: '<center>ชื่อ</center>',
                        className: 'text-left w100 '
                    },
                    {
                        data: 'detail',
                        title: '<center>รายละเอียด</center>',
                        className: 'text-left w500 '
                    },

                ],
                rowCallback: function(nRow, aData, dataIndex) {}
            });


        });


        $(document).ready(function() {

            $(document).on('click', '.cDeleteX', function(event) {


                if (!confirm("Are you sure ? ")) {
                    return false;
                } else {


                    var id = $(this).data('id');
                    var customer_id_fk = $(this).attr('customer_id_fk');
                    // alert(id);
                    $.ajax({
                        type: 'POST',
                        url: " {{ url('backend/ajaxCheckAddAiCash') }} ",
                        data: {
                            id: id,
                            customer_id_fk: customer_id_fk
                        },
                        success: function(data) {
                            console.log(data);

                            if (data['status'] == "fail") {
                                Swal.fire({
                                    type: 'warning',
                                    title: data['message'],
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                            } else {
                                Swal.fire({
                                    type: 'success',
                                    title: data['message'],
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                //location.reload();
                            }

                            $('#data-table').DataTable().clear();
                            $('#data-table').DataTable().ajax.reload();
                            $(".myloading").hide();
                        },

                    });

                }

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

            $(".customer_id_fk").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: '-Select-',
                ajax: {
                    url: " {{ url('backend/ajaxGetCustomer') }} ",
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    cache: false,
                    data: function(params) {
                        return {
                            term: params.term || '', // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $(".customer_name_fk").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: '-Select-',
                ajax: {
                    url: " {{ url('backend/ajaxGetCustomer_name') }} ",
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    cache: false,
                    data: function(params) {
                        return {
                            term: params.term || '', // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        return {
                            results: data
                        };
                    }
                }
            });


            $(document).on('click', '.btnSearch01', function(event) {
                event.preventDefault();

                $('#data-table').DataTable().clear();
                $('#data-table').DataTable().destroy();

                $('#data-table').find('thead').remove();
                $(".myloading").show();

                var customer_id = $('#customer_id').val();
                var customer_name = $('#customer_name').val();
                var ai_list = $('#ai_list').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var report_type = $('#report_type').val();

                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var oTable;
                $(function() {
                    $.fn.dataTable.ext.errMode = 'throw';
                    if (ai_list == 1) {
                        oTable = $('#data-table').DataTable({
                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                            processing: true,
                            serverSide: true,
                            scroller: true,
                            destroy: true,
                            ordering: false,
                            iDisplayLength: 1000000,
                            ajax: {
                                url: '{{ url('backend/ai_report/datatable') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    customer_id: customer_id,
                                    customer_name: customer_name,
                                    ai_list: ai_list,
                                    startDate: startDate,
                                    endDate: endDate,
                                    report_type: report_type,
                                },
                                method: 'POST',
                            },
                            columns: [
                                {
                                    data: 'action_date',
                                    title: '<center>วันที่ทํารายการ</center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'member_id',
                                    title: '<center>รหัสสมาชิก</center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'member_name',
                                    title: '<center>ชื่อ</center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'detail',
                                    title: '<center>รายละเอียด</center>',
                                    className: 'text-left w500 '
                                },

                            ],
                            rowCallback: function(nRow, aData, dataIndex) {

                            }
                        });
                    }
                    if (ai_list == 2) {
                        oTable = $('#data-table').DataTable({
                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                            processing: true,
                            serverSide: true,
                            scroller: true,
                            destroy: true,
                            ordering: false,
                            ajax: {
                                url: '{{ url('backend/ai_report/datatable_ai_cash') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    customer_id: customer_id,
                                    customer_name: customer_name,
                                    ai_list: ai_list,
                                    startDate: startDate,
                                    endDate: endDate,
                                    report_type: report_type,
                                },
                                method: 'POST',
                            },
                            columns: [
                                {
                                    data: 'action_date',
                                    title: '<center>วันที่นำฝาก/วันที่ใช้ยอด</center>',
                                    className: 'text-center w100 '
                                },
                                {
                                    data: 'pay_type',
                                    title: '<center>ช่องทางนำฝากธนาคาร</center>',
                                    className: 'text-center w100 '
                                },
                                {
                                    data: 'pay_slip',
                                    title: '<center>หลักฐานการนำฝาก/ถอน</center>',
                                    className: 'text-center w100 '
                                },
                                {
                                    data: 'member_id',
                                    title: '<center>รหัสสมาชิก</center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'member_name',
                                    title: '<center>ชื่อ</center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'detail',
                                    title: '<center>รายละเอียด</center>',
                                    className: 'text-center w500 '
                                },
                            ],
                            rowCallback: function(nRow, aData, dataIndex) {

                            }
                        });
                    }
                    if (ai_list == 3) {
                        oTable = $('#data-table').DataTable({
                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                            processing: true,
                            serverSide: true,
                            scroller: true,
                            destroy: true,
                            ordering: false,
                            ajax: {
                                url: '{{ url('backend/ai_report/datatable_gv') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    customer_id: customer_id,
                                    customer_name: customer_name,
                                    ai_list: ai_list,
                                    startDate: startDate,
                                    endDate: endDate,
                                    report_type: report_type,
                                },
                                method: 'POST',
                            },
                            columns: [
                                {
                                    data: 'action_date',
                                    title: '<center>วันที่</center>',
                                    className: 'text-center w40 '
                                },
                                {
                                    data: 'member_id',
                                    title: '<center>รหัสสมาชิก</center>',
                                    className: 'text-left w40'
                                },
                                {
                                    data: 'member_name',
                                    title: '<center>ชื่อ</center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'detail',
                                    title: '<center>รายละเอียด</center>',
                                    className: 'text-center w500 '
                                },
                            ],
                            rowCallback: function(nRow, aData, dataIndex) {

                            }
                        });
                    }


                });

                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });
        });
    </script>
@endsection
