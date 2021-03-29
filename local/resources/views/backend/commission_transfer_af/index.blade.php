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
                <h4 class="mb-0 font-size-18">  รายงานการโอนค่าคอมมิชชั่น AF ประจำรอบ </h4>
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
                                        <input id="startDate" autocomplete="off" placeholder="วันเริ่ม" />
                                        <input id="endDate" autocomplete="off" placeholder="วันสิ้นสุด" />
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

                        {{-- <div class="col-4 text-right" style="{{ @$sC }}">
                            <!--    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.check_money_daily.create') }}">
                          <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                        </a> -->
                        </div> --}}

                    </div>

                    <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                    </table>
                    <div id="view_transfer"></div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection

@section('script')

    <script>
        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var oTable;
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
                        title: '<center>วันที่ทำรายการ</center>',
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
                        data: 'destination_bank',
                        title: '<center>ธนาคารปลายทาง </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'transferee_bank_no',
                        title: '<center>เลขที่บัญชี</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'fee',
                        title: '<center>ค่าธรรมเนียม</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'amount',
                        title: '<center>จำนวนเงิน</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'transfer_result',
                        title: '<center>ผลการโอน</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'note',
                        title: '<center>หมายเหตุ</center>',
                        className: 'text-center'
                    },
                ],
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

        $('#startDate').change(function(event) {
            $('#endDate').val($(this).val());
        });



    </script>

@endsection
