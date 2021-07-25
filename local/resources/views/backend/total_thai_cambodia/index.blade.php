@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

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
                                    <div class="col-md-4 ">
                                        <div class="form-group row">
                                            <select id="business_location" name="business_location"
                                                class="form-control select2-templating ">
                                                <option value="">Business Location</option>
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

                                    {{-- <div class="col-md-2">
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
                              </div> --}}
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
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2 mb-0">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between"
                                style="padding-bottom: 0px;">
                                <h4 class="mb-0 font-size-18"> <u>รายวัน</u></h4>
                            </div>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table id="thai_cambodia" class="table table-bordered dt-responsive thai_cambodia"
                            style="width: 100%;">
                            <tfoot>
                                <tr>
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

                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row">


        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2 mb-0">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between"
                                style="padding-bottom: 0px;">
                                <h4 class="font-size-18"> <u>ยอดรวมการขาย</u></h4>
                            </div>
                        </div>
                    </div>

                    <table id="data-table-thai" class="table table-bordered dt-responsive mt-0"
                        style="width: 100%;">
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <table id="data-table-cambodia" class="table table-bordered dt-responsive mt-0"
                        style="width: 100%;">
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script>

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '.00';
        }

        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var oTable;
        $(function() {
            oTable = $('#thai_cambodia').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.total_thai_cambodia.datatable') }}',
                    data: function(d) {
                        d.business_location = $('#business_location').val();
                        d.status_search = $('#status_search').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                    method: 'POST'
                },
                columns: [{
                        data: 'txt_desc',
                        title: '<center>Business Location</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'branchs',
                        title: '<center>Branch</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'action_date',
                        title: '<center>วันที่ทำรายการ</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'total_balance',
                        title :'<center>จำนวน</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'total_price',
                        title: '<center>เงินสดรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_transfer',
                        title: '<center>เงินโอนรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_credit_card',
                        title: '<center>เครดิตรวม</center>',
                        className: 'text-right',
                        footer: 'Id',
                    },
                    {
                        data: 'total_aicash',
                        title: '<center>Ai-Cash</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_add_aicash',
                        title: '<center>เติม Ai-Cash</center>',
                        'footer': 'Id',
                        className: 'text-right table-warning'
                    },
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    total_balance = api
                        .column( 3, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_price = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_transfer = api
                        .column( 5, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_credit_card = api
                        .column( 6, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_aicash = api
                        .column( 7, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_add_aicash = api
                        .column( 8, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Update footer
                    $(api.column( 0 ).footer()).html('Total');
                    $(api.column( 3 ).footer()).html(numberWithCommas(total_balance));
                    $(api.column( 4 ).footer()).html(numberWithCommas(total_price));
                    $(api.column( 5 ).footer()).html(numberWithCommas(total_transfer));
                    $(api.column( 6 ).footer()).html(numberWithCommas(total_credit_card));
                    $(api.column( 7 ).footer()).html(numberWithCommas(total_aicash));
                    $(api.column( 8 ).footer()).html(numberWithCommas(total_add_aicash));
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

    <script>

        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var oTable_total_thai;
        var oTable_total_cambodia;
        $(function() {
            oTable_total_thai = $('#data-table-thai').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.total_thai_cambodia.datatable_total_thai') }}',
                    data: function(d) {
                        d.business_location = $('#business_location').val();
                        d.status_search = $('#status_search').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                    method: 'POST'
                },
                columns: [{
                        data: 'txt_desc',
                        title: '<center>Business Location</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'branchs',
                        title: '<center>Branch</center>',
                        className: 'text-left'
                    },
                    // {
                    //     data: 'action_date',
                    //     title: '<center>วันที่ทำรายการ</center>',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'total_balance',
                        title: '<center>จำนวนรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_price',
                        title: '<center>จำนวนเงินสดรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_transfer',
                        title: '<center>จำนวนเงินโอนรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_credit_card',
                        title: '<center>จำนวนเงินเครดิตรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_aicash',
                        title: '<center>Ai-Cash</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_add_aicash',
                        title: '<center>เติม Ai-Cash</center>',
                        className: 'text-right table-warning'
                    },
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    total_balance = api
                        .column( 2, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_price = api
                        .column( 3, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_transfer = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_credit_card = api
                        .column( 5, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_aicash = api
                        .column( 6, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_add_aicash = api
                        .column( 7, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Update footer
                    $(api.column( 0 ).footer()).html('Total');
                    $(api.column( 2 ).footer()).html(numberWithCommas(total_balance));
                    $(api.column( 3 ).footer()).html(numberWithCommas(total_price));
                    $(api.column( 4 ).footer()).html(numberWithCommas(total_transfer));
                    $(api.column( 5 ).footer()).html(numberWithCommas(total_credit_card));
                    $(api.column( 6 ).footer()).html(numberWithCommas(total_aicash));
                    $(api.column( 7 ).footer()).html(numberWithCommas(total_add_aicash));
                }
                // initComplete: function() {
                //     this.api().columns().every(function() {
                //         var column = this;
                //         var input = document.createElement("input");
                //         $(input).appendTo($(column.footer()).empty())
                //             .on('change', function() {
                //                 column.search($(this).val(), false, false, true).draw();
                //             });
                //     });
                // }
            });

            oTable_total_cambodia = $('#data-table-cambodia').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.total_thai_cambodia.datatable_total_cambodia') }}',
                    data: function(d) {
                        d.business_location = $('#business_location').val();
                        d.status_search = $('#status_search').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                    method: 'POST'
                },
                columns: [{
                        data: 'txt_desc',
                        title: '<center>Business Location</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'branchs',
                        title: '<center>Branch</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'total_balance',
                        title: '<center>จำนวนรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_price',
                        title: '<center>จำนวนเงินสดรวม</center>',
                        className: 'text-right '
                    },
                    {
                        data: 'total_transfer',
                        title: '<center>จำนวนเงินโอนรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_credit_card',
                        title: '<center>จำนวนเงินเครดิตรวม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_aicash',
                        title: '<center>Ai-Cash</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'total_add_aicash',
                        title: '<center>เติม Ai-Cash</center>',
                        className: 'text-right table-warning'
                    },
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    total_balance = api
                        .column( 2, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_price = api
                        .column( 3, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_transfer = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_credit_card = api
                        .column( 5, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_aicash = api
                        .column( 6, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    total_add_aicash = api
                        .column( 7, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Update footer
                    $(api.column( 0 ).footer()).html('Total');
                    $(api.column( 2 ).footer()).html(numberWithCommas(total_balance));
                    $(api.column( 3 ).footer()).html(numberWithCommas(total_price));
                    $(api.column( 4 ).footer()).html(numberWithCommas(total_transfer));
                    $(api.column( 5 ).footer()).html(numberWithCommas(total_credit_card));
                    $(api.column( 6 ).footer()).html(numberWithCommas(total_aicash));
                    $(api.column( 7 ).footer()).html(numberWithCommas(total_add_aicash));
                }
                // initComplete: function() {
                //     this.api().columns().every(function() {
                //         var column = this;
                //         var input = document.createElement("input");
                //         $(input).appendTo($(column.footer()).empty())
                //             .on('change', function() {
                //                 column.search($(this).val(), false, false, true).draw();
                //             });
                //     });
                // }
            });

            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                oTable_total_thai.draw();
                oTable_total_cambodia.draw();
            });

            $('#search-form').on('click', function(e) {

                oTable_total_thai.draw();
                oTable_total_cambodia.draw();
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

        //  $('#startDate').change(function(event) {
        //    $('#endDate').val($(this).val());
        //  });



    </script>



@endsection
