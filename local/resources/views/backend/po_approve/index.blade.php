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
@endsection

@section('content')
    <div class="myloading"></div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> ใบสั่งซื้อรออนุมัติ </h4>
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



                    @if (@\Auth::user()->permission == 1)

                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group row">
                                    <label for="business_location_id_fk" class="col-md-3 col-form-label">Business Location :
                                    </label>
                                    <div class="col-md-9">
                                        <select id="business_location_id_fk" name="business_location_id_fk"
                                            class="form-control select2-templating " required="">
                                            <option value="">-Business Location-</option>
                                            @if (@$sBusiness_location)
                                                @foreach (@$sBusiness_location as $r)
                                                    <option value="{{ @$r->id }}">{{ $r->txt_desc }}</option>
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

                                        <select id="branch_id_fk" name="branch_id_fk"
                                            class="form-control select2-templating ">
                                            <option disabled selected value="">กรุณาเลือก Business Location ก่อน
                                            </option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @ELSE
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group row">
                                    <label for="business_location_id_fk" class="col-md-3 col-form-label">Business Location :
                                    </label>
                                    <div class="col-md-9">
                                        <select class="form-control select2-templating " disabled="">
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

                                        <select class="form-control select2-templating " disabled="">
                                            @if (@$sBranchs)
                                                @foreach (@$sBranchs as $r)
                                                    <option value="{{ @$r->id }}"
                                                        {{ @$r->id == \Auth::user()->branch_id_fk ? 'selected' : '' }}>
                                                        {{ $r->b_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                    @ENDIF


                    <div class="row">
                        <div class="col-md-6 " style="display:none;">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> ประเภทบิล : </label>
                                <div class="col-md-9">
                                    <select id="bill_type" name="bill_type" class="form-control select2-templating "
                                        required>
                                        <option value="">-Select-</option>
                                        <option value="1" selected>ขายสินค้า</option>
                                        <!-- <option value="2" >เติม Ai-cash </option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> เลขที่เอกสาร : </label>
                                <div class="col-md-9">
                                    <select id="doc_id" name="doc_id" class="form-control select2-templating ">
                                        <option value="">-Select-</option>
                                        @if (@$code_order)
                                            @foreach (@$code_order as $r)
                                                <option value="{{ @$r->code_order }}">{{ $r->code_order }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> เลขที่เอกสาร : </label>
                                <div class="col-md-9">
                                    <select id="customer_id" name="customer_id" class="form-control select2-templating "
                                        required="">
                                        <option value=""> รหัส-ชื่อลูกค้า </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 " style="display:none;">
                            <div class="form-group row">
                                <label for="customer_id_fk" class="col-md-3 col-form-label"> ผู้อนุมัติ : </label>
                                <div class="col-md-9">
                                    <select id="transfer_amount_approver" name="transfer_amount_approver"
                                        class="form-control select2-templating ">
                                        <option value="">-Select-</option>
                                        @if (@$sApprover)
                                            @foreach (@$sApprover as $r)
                                                <option value="{{ $r->id }}">
                                                    {{ $r->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 " style="display:none;">
                            <div class="form-group row">
                                <label for="transfer_bill_status" class="col-md-3 col-form-label"> สถานะ : </label>
                                <div class="col-md-9">
                                    <select id="transfer_bill_status" name="transfer_bill_status"
                                        class="form-control select2-templating ">
                                        <option value="">-Status-</option>
                                        <option value="1"> รออนุมัติ </option>
                                        <option value="2"> อนุมัติ </option>
                                        <option value="3"> ไม่อนุมัติ </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row" style="">
                        <div class="col-md-6 ">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> วันที่สั่งซื้อ : </label>
                                <div class="col-md-9 d-flex">
                                    <?php
                                    $first_day = date('Y-m-d', strtotime('-3 day'));
                                    $last_day = date('Y-m-d');
                                    ?>
                                    <!-- <=
                                             $first_day
                                             $last_day
                                             ?> -->
                                    <input id="bill_sdate" autocomplete="off" placeholder="Begin Date"
                                        style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black"
                                        value="" />
                                    <input id="bill_edate" autocomplete="off" placeholder="End Date"
                                        style="border: 1px solid grey;font-weight: bold;color: black" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 " style="display: none;">
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> วันที่อนุมัติ : </label>
                                <div class="col-md-9 d-flex">
                                    <input id="transfer_bill_approve_sdate" autocomplete="off" placeholder="Begin Date"
                                        style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                                    <input id="transfer_bill_approve_edate" autocomplete="off" placeholder="End Date"
                                        style="border: 1px solid grey;font-weight: bold;color: black" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-6 " style="margin-top: -1% !important;">
                        </div>

                        <div class="col-md-6 " style="margin-top: -0.5% !important;">
                            <div class="form-group row">
                                <label for="branch_id_fk" class="col-md-3 col-form-label"> </label>
                                <div class="col-md-9">

                                    <!-- บิลขายสินค้า -->
                                    <a class="btn btn-primary btn-sm btnSearch01 " href="javascript: void(0);"
                                        style="font-size: 14px !important;margin-left: 0.8%;">
                                        <i class="bx bx-search align-middle "></i> SEARCH
                                    </a>

                                    <!-- บิลเติม Ai-cash -->
                                    <!--     <a class="btn btn-info btn-sm btnSearch02 " href="#" style="font-size: 14px !important;margin-left: 0.8%;" >
                                            <i class="bx bx-search align-middle "></i> SEARCH
                                          </a> -->


                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                </div> <!-- end row -->

                <div class="row div_datatable_01">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                    </div>
                                </div>

                                <table id="data-table" class="table table-bordered" style="width: 100%;">
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->


                <div class="row div_datatable_02">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                    </div>
                                </div>

                                <table id="data-table-02" class="table table-bordered " style="width: 100%;"></table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

            @endsection

            @section('script')
                <script>
                    $(document).ready(function() {
                        $("#customer_id").select2({
                            minimumInputLength: 2,
                            allowClear: true,
                            placeholder: '- รหัส-ชื่อลูกค้า -',
                            ajax: {
                                url: " {{ url('backend/ajaxGetCustomerForFrontstore') }} ",
                                type: 'POST',
                                dataType: 'json',
                                delay: 250,
                                cache: false,
                                data: function(params) {
                                    console.log(params);
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
                    });

                    var oTable;
                    $(function() {
                        oTable = $('#data-table').DataTable({
                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                            processing: true,
                            serverSide: true,
                            // scroller: true,
                            // scrollCollapse: true,
                            "info": false,
                            scrollX: true,
                            ordering: false,
                            scrollY: '' + ($(window).height() - 370) + 'px',
                            iDisplayLength: 25,

                            ajax: {
                                url: '{{ route('backend.po_approve.datatable') }}',
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

                            columns: [{
                                    data: 'id',
                                    title: 'ID',
                                    className: 'text-center w50'
                                },
                                {
                                    data: 'created_at',
                                    title: '<center>วันที่สั่งซื้อ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'customer_name',
                                    title: '<center>รหัส:ชื่อลูกค้า </center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'customer_bank',
                                    title: '<center>ธนาคารที่โอนชำระ</center>',
                                    className: 'text-left  '
                                },
                                {
                                    data: 'code_order',
                                    title: '<center>เลขใบสั่งซื้อ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'price',
                                    title: '<center>ยอดชำระ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'approval_amount_transfer',
                                    title: '<center>ยอดโอน</center>',
                                    className: 'text-center w80',
                                    render: function(d) {
                                        if (d) {
                                            return d;
                                        } else {
                                            return '-';
                                        }
                                    }
                                },
                                {
                                    data: 'approval_amount_transfer_over',
                                    title: '<center>ยอดเกิน</center>',
                                    className: 'text-center w80',
                                    render: function(d) {
                                        if (d) {
                                            return d;
                                        } else {
                                            return '-';
                                        }
                                    }
                                },
                                {
                                    data: 'transfer_bill_date',
                                    title: '<center>วันเวลาที่โอน </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'pay_with_other_bill_note',
                                    title: '<center>ชำระร่วม</center>',
                                    className: 'text-center',
                                    render: function(d) {
                                        if (d) {
                                            return d;
                                        } else {
                                            return '-';
                                        }
                                    }
                                },

                                {
                                    data: 'transfer_bill_status',
                                    title: '<center>สถานะ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'transfer_amount_approver',
                                    title: '<center>ผู้อนุมัติ</center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'status_slip',
                                    title: '<center>Status Slip</center>',
                                    className: 'text-center',
                                    render: function(d) {
                                        if (d == 'true') {
                                            return '<span class="badge badge-pill badge-soft-success font-size-16">T</span>';
                                        } else {
                                            return '<span class="badge badge-pill badge-soft-danger font-size-16">F</span>';
                                        }
                                    }
                                },
                                {
                                    data: 'id',
                                    title: 'Tool',
                                    className: 'text-center w100'
                                },

                            ],

                            rowCallback: function(nRow, aData, start, end, display) {

                                if (aData['branch_id_fk'] === 11) {
                                    $(nRow).addClass('table-warning')
                                } else if (aData['branch_id_fk'] === 12) {
                                    $(nRow).addClass('table-success')
                                }

                                if (aData['pay_with_other_bill'] != 1) {
                                    $('td:last-child', nRow).html('' +
                                        '<a href="{{ route('backend.po_approve.index') }}/' + aData['id'] +
                                        '/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                                        ''
                                    ).addClass('input');

                                } else {
                                    $('td:last-child', nRow).html('' + '').addClass('input');
                                }


                                if (aData['price'] <= 0) {
                                    $('td:last-child', nRow).html('-');
                                }

                            }

                        });

                    });




                    var oTable2;
                    $(function() {
                        oTable2 = $('#data-table-02').DataTable({
                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                            processing: true,
                            serverSide: true,
                            scroller: true,
                            scrollCollapse: true,
                            scrollX: true,
                            ordering: false,
                            // scrollY: ''+($(window).height()-370)+'px',
                            iDisplayLength: 25,
                            ajax: {
                                url: '{{ route('backend.add_ai_cash_02.datatable') }}',
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

                            columns: [{
                                    data: 'id',
                                    title: 'ID',
                                    className: 'text-center w50'
                                },
                                {
                                    data: 'created_at',
                                    title: '<center>วันที่สั่งซื้อ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'customer_name',
                                    title: '<center>รหัส:ชื่อลูกค้า </center>',
                                    className: 'text-left w100 '
                                },
                                {
                                    data: 'code_order',
                                    title: '<center>เลขใบสั่งซื้อ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'aicash_remain',
                                    title: '<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'aicash_amt',
                                    title: '<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'action_user',
                                    title: '<center>พนักงาน <br> ที่ดำเนินการ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'pay_type_id_fk',
                                    title: '<center>รูปแบบการชำระเงิน </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'total_amt',
                                    title: '<center>ยอดชำระเงิน </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'other_bill',
                                    title: '<center>ชำระร่วมบิล </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'status',
                                    title: '<center>สถานะ </center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'approver',
                                    title: '<center>ผู้อนุมัติ</center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'approve_date',
                                    title: '<center>วันที่อนุมัติ</center>',
                                    className: 'text-center'
                                },
                                {
                                    data: 'id',
                                    title: 'Tools',
                                    className: 'text-center w60'
                                },
                            ],
                            rowCallback: function(nRow, aData, dataIndex) {

                                if (aData['transfer_bill_status'] == 5) {
                                    for (var i = 0; i < 6; i++) {
                                        $('td:eq( ' + i + ')', nRow).html(aData[i]).css({
                                            'color': '#d9d9d9',
                                            'text-decoration': 'line-through',
                                            'font-style': 'italic'
                                        });
                                    }
                                    // $('td:last-child', nRow).html('-ยกเลิก-');
                                    $('td:last-child', nRow).html('-');

                                } else {


                                    var sPermission = "<?= \Auth::user()->permission ?>";
                                    var sU = sessionStorage.getItem("sU");
                                    var sD = sessionStorage.getItem("sD");
                                    if (sPermission == 1) {
                                        sU = 1;
                                        sD = 1;
                                    }
                                    var str_U = '';
                                    if (sU == '1' && aData['pay_with_other_bill_select'] != 1) {
                                        str_U = '<a href="{{ URL('backend/po_approve/form_aicash') }}/' + aData[
                                                'id'] +
                                            '" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                    }
                                    var str_D = '';
                                    // if(sD=='1'){
                                    //   str_D = ' <a href="javascript: void(0);" data-url="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDeleteX cDelete " customer_id_fk="'+aData['customer_id_fk']+'"  data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';
                                    // }
                                    if (sU != '1' && sD != '1') {
                                        $('td:last-child', nRow).html('-');
                                    } else {
                                        $('td:last-child', nRow).html(str_U).addClass('input');
                                    }


                                }

                            }
                        });


                    });
                </script>


                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
                    integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
                    crossorigin="anonymous">
                <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
                <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

                <script>
                    $(document).ready(function() {
                        setTimeout(function() {
                            //  $(".myloading").hide();
                            location.reload();
                        }, 135000);
                    });
                </script>

                <script>
                    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
                    $('#bill_sdate').datepicker({
                        // format: 'dd/mm/yyyy',
                        format: 'yyyy-mm-dd',
                        uiLibrary: 'bootstrap4',
                        iconsLibrary: 'fontawesome',
                        minDate: function() {
                            // return today;
                        }
                    });

                    $('#bill_edate').datepicker({
                        // format: 'dd/mm/yyyy',
                        format: 'yyyy-mm-dd',
                        uiLibrary: 'bootstrap4',
                        iconsLibrary: 'fontawesome',
                        minDate: function() {
                            return $('#bill_sdate').val();
                        }
                    });

                    $('#bill_sdate').change(function(event) {

                        if ($('#bill_edate').val() > $(this).val()) {} else {
                            $('#bill_edate').val($(this).val());
                        }

                    });
                </script>



                <script>
                    $('#transfer_bill_approve_sdate').datepicker({
                        // format: 'dd/mm/yyyy',
                        format: 'yyyy-mm-dd',
                        uiLibrary: 'bootstrap4',
                        iconsLibrary: 'fontawesome',
                    });

                    $('#transfer_bill_approve_edate').datepicker({
                        // format: 'dd/mm/yyyy',
                        format: 'yyyy-mm-dd',
                        uiLibrary: 'bootstrap4',
                        iconsLibrary: 'fontawesome',
                        minDate: function() {
                            return $('#transfer_bill_approve_sdate').val();
                        }
                    });

                    $('#transfer_bill_approve_sdate').change(function(event) {

                        if ($('#transfer_bill_approve_edate').val() > $(this).val()) {} else {
                            $('#transfer_bill_approve_edate').val($(this).val());
                        }

                    });
                </script>



                <script>
                    $(document).ready(function() {

                        $(document).on('click', '.btnSearch01', function(event) {
                            event.preventDefault();

                            $('#data-table').DataTable().clear();
                            $('#data-table-02').DataTable().clear();

                            $(".myloading").show();
                            $(".div_datatable_01").show();
                            $(".div_datatable_02").show();

                            var business_location_id_fk = $('#business_location_id_fk').val();
                            var branch_id_fk = $('#branch_id_fk').val();
                            var bill_type = $('#bill_type').val();
                            // alert(bill_type);
                            var doc_id = $('#doc_id').val();
                            // var customer_id_fk = $('#customer_id_fk').val();
                            var bill_sdate = $('#bill_sdate').val();
                            var bill_edate = $('#bill_edate').val();
                            var transfer_amount_approver = $('#transfer_amount_approver').val();
                            var transfer_bill_status = $('#transfer_bill_status').val();
                            var transfer_bill_approve_sdate = $('#transfer_bill_approve_sdate').val();
                            var transfer_bill_approve_edate = $('#transfer_bill_approve_edate').val();
                            // var approve_sdate = $('#approve_sdate').val();
                            // var approve_edate = $('#approve_edate').val();

                            if (business_location_id_fk == '') {
                                $('#business_location_id_fk').select2('open');
                                $(".myloading").hide();
                                return false;
                            }
                            // alert(branch_id_fk);
                            // if(branch_id_fk=='' || branch_id_fk === null ){
                            //   $('#branch_id_fk').select2('open');
                            //   $(".myloading").hide();
                            //   return false;
                            // }

                            if (bill_type == '' || bill_type === null) {
                                $('#bill_type').select2('open');
                                $(".myloading").hide();
                                return false;
                            }

                            var customer_id = $('#customer_id').val();

                            // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                            var oTable01;
                            $(function() {
                                $.fn.dataTable.ext.errMode = 'throw';

                                oTable01 = $('#data-table').DataTable({
                                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    destroy: true,
                                    ordering: false,
                                    "info": false,
                                    iDisplayLength: 25,
                                    scrollY: '' + ($(window).height() - 370) + 'px',
                                    ajax: {
                                        url: '{{ route('backend.po_approve.datatable') }}',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            business_location_id_fk: business_location_id_fk,
                                            branch_id_fk: branch_id_fk,
                                            doc_id: doc_id,
                                            bill_sdate: bill_sdate,
                                            bill_edate: bill_edate,
                                            transfer_amount_approver: transfer_amount_approver,
                                            transfer_bill_status: transfer_bill_status,
                                            transfer_bill_approve_sdate: transfer_bill_approve_sdate,
                                            transfer_bill_approve_edate: transfer_bill_approve_edate,
                                            customer_id: customer_id,
                                            // approve_sdate:approve_sdate,
                                            // approve_edate:approve_edate,
                                        },
                                        method: 'POST',
                                    },

                                    columns: [{
                                            data: 'id',
                                            title: 'ID',
                                            className: 'text-center w50'
                                        },
                                        {
                                            data: 'created_at',
                                            title: '<center>วันที่สั่งซื้อ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'customer_name',
                                            title: '<center>รหัส:ชื่อลูกค้า </center>',
                                            className: 'text-left w100 '
                                        },
                                        {
                                            data: 'code_order',
                                            title: '<center>เลขใบสั่งซื้อ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'price',
                                            title: '<center>ยอดชำระ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approval_amount_transfer',
                                            title: '<center>ยอดโอน</center>',
                                            className: 'text-center w80',
                                            render: function(d) {
                                                if (d) {
                                                    return d;
                                                } else {
                                                    return '-';
                                                }
                                            }
                                        },
                                        {
                                            data: 'approval_amount_transfer_over',
                                            title: '<center>ยอดเกิน</center>',
                                            className: 'text-center w80',
                                            render: function(d) {
                                                if (d) {
                                                    return d;
                                                } else {
                                                    return '-';
                                                }
                                            }
                                        },
                                        {
                                            data: 'updated_at',
                                            title: '<center>วันเวลาที่โอน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'pay_with_other_bill_note',
                                            title: '<center>ชำระร่วม</center>',
                                            className: 'text-center',
                                            render: function(d) {
                                                if (d) {
                                                    return d;
                                                } else {
                                                    return '-';
                                                }
                                            }
                                        },

                                        {
                                            data: 'transfer_bill_status',
                                            title: '<center>สถานะ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'transfer_amount_approver',
                                            title: '<center>ผู้อนุมัติ</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'status_slip',
                                            title: '<center>Status Slip</center>',
                                            className: 'text-center',
                                            render: function(d) {
                                                if (d == 'true') {
                                                    return '<span class="badge badge-pill badge-soft-success font-size-16">T</span>';
                                                } else {
                                                    return '<span class="badge badge-pill badge-soft-danger font-size-16">F</span>';
                                                }
                                            }
                                        },
                                        {
                                            data: 'id',
                                            title: 'Tool',
                                            className: 'text-center w100'
                                        },

                                    ],

                                    rowCallback: function(nRow, aData, start, end, display) {
                                        if (aData['pay_with_other_bill'] != 1) {
                                            $('td:last-child', nRow).html('' +
                                                '<a href="{{ route('backend.po_approve.index') }}/' +
                                                aData['id'] +
                                                '/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                                                ''
                                            ).addClass('input');
                                        } else {
                                            $('td:last-child', nRow).html('' + '').addClass(
                                                'input');
                                        }
                                    },
                                });
                            });

                            var oTable2;
                            $(function() {
                                $.fn.dataTable.ext.errMode = 'throw';

                                oTable2 = $('#data-table-02').DataTable({
                                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    destroy: true,
                                    ordering: false,
                                    "info": false,
                                    scrollY: '' + ($(window).height() - 370) + 'px',
                                    // scrollY: ''+($(window).height()-370)+'px',
                                    iDisplayLength: 25,
                                    ajax: {
                                        url: '{{ route('backend.add_ai_cash_02.datatable') }}',

                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            business_location_id_fk: business_location_id_fk,
                                            branch_id_fk: branch_id_fk,
                                            doc_id: doc_id,
                                            bill_sdate: bill_sdate,
                                            bill_edate: bill_edate,
                                            transfer_amount_approver: transfer_amount_approver,
                                            transfer_bill_status: transfer_bill_status,
                                            transfer_bill_approve_sdate: transfer_bill_approve_sdate,
                                            transfer_bill_approve_edate: transfer_bill_approve_edate,
                                            // approve_sdate:approve_sdate,
                                            // approve_edate:approve_edate,
                                        },

                                        // data: function ( d ) {
                                        //   d.Where={};
                                        //   $('.myWhere').each(function() {
                                        //     if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                                        //       d.Where[$(this).attr('name')] = $.trim($(this).val());
                                        //     }
                                        //   });
                                        //   d.Like={};
                                        //   $('.myLike').each(function() {
                                        //     if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                                        //       d.Like[$(this).attr('name')] = $.trim($(this).val());
                                        //     }
                                        //   });
                                        //   d.Custom={};
                                        //   $('.myCustom').each(function() {
                                        //     if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                                        //       d.Custom[$(this).attr('name')] = $.trim($(this).val());
                                        //     }
                                        //   });
                                        //   oData = d;
                                        // },
                                        method: 'POST'
                                    },

                                    columns: [{
                                            data: 'id',
                                            title: 'ID',
                                            className: 'text-center w50'
                                        },
                                        {
                                            data: 'created_at',
                                            title: '<center>วันที่สั่งซื้อ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'customer_name',
                                            title: '<center>รหัส:ชื่อลูกค้า </center>',
                                            className: 'text-left w100 '
                                        },
                                        {
                                            data: 'code_order',
                                            title: '<center>เลขใบสั่งซื้อ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'aicash_remain',
                                            title: '<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'aicash_amt',
                                            title: '<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'action_user',
                                            title: '<center>พนักงาน <br> ที่ดำเนินการ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'pay_type_id_fk',
                                            title: '<center>รูปแบบการชำระเงิน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'total_amt',
                                            title: '<center>ยอดชำระเงิน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'other_bill',
                                            title: '<center>ชำระร่วม </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'status',
                                            title: '<center>สถานะ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approver',
                                            title: '<center>ผู้อนุมัติ</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approve_date',
                                            title: '<center>วันที่อนุมัติ</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'id',
                                            title: 'Tools',
                                            className: 'text-center w60'
                                        },
                                    ],
                                    rowCallback: function(nRow, aData, dataIndex) {
                                        console.log(aData);
                                        if (aData['transfer_bill_status'] == 5) {
                                            for (var i = 0; i < 6; i++) {
                                                $('td:eq( ' + i + ')', nRow).html(aData[i]).css({
                                                    'color': '#d9d9d9',
                                                    'text-decoration': 'line-through',
                                                    'font-style': 'italic'
                                                });
                                            }
                                            // $('td:last-child', nRow).html('-ยกเลิก-');
                                            $('td:last-child', nRow).html('-');

                                        } else {


                                            var sPermission = "<?= \Auth::user()->permission ?>";
                                            var sU = sessionStorage.getItem("sU");
                                            var sD = sessionStorage.getItem("sD");
                                            if (sPermission == 1) {
                                                sU = 1;
                                                sD = 1;
                                            }
                                            var str_U = '';
                                            if (sU == '1' && aData['pay_with_other_bill_select'] !=
                                                1) {
                                                str_U =
                                                    '<a href="{{ URL('backend/po_approve/form_aicash') }}/' +
                                                    aData['id'] +
                                                    '" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                            }
                                            var str_D = '';
                                            // if(sD=='1'){
                                            //   str_D = ' <a href="javascript: void(0);" data-url="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDeleteX cDelete " customer_id_fk="'+aData['customer_id_fk']+'"  data-id="'+aData['id']+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';
                                            // }
                                            if (sU != '1' && sD != '1') {
                                                $('td:last-child', nRow).html('-');
                                            } else {
                                                $('td:last-child', nRow).html(str_U).addClass(
                                                    'input');
                                            }


                                        }

                                    }
                                });

                            });


                            setTimeout(function() {
                                $(".myloading").hide();
                            }, 1500);

                        });
                    });
                </script>

                <script>
                    $(document).ready(function() {

                        $(document).on('click', '.btnSearch02', function(event) {
                            event.preventDefault();

                            $('#data-table-02').DataTable().clear();

                            $(".myloading").show();

                            $(".div_datatable_02").show();
                            $(".div_datatable_01").hide();

                            var business_location_id_fk = $('#business_location_id_fk').val();
                            var branch_id_fk = $('#branch_id_fk').val();
                            var bill_type = $('#bill_type').val();
                            var doc_id = $('#doc_id').val();
                            // var customer_id_fk = $('#customer_id_fk').val();
                            var bill_sdate = $('#bill_sdate').val();
                            var bill_edate = $('#bill_edate').val();
                            // var bill_status = $('#bill_status').val();
                            var transfer_amount_approver = $('#transfer_amount_approver').val();
                            var transfer_bill_status = $('#transfer_bill_status').val();
                            var approve_sdate = $('#approve_sdate').val();
                            var approve_edate = $('#approve_edate').val();

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

                            if (bill_type == '' || bill_type === null) {
                                $('#bill_type').select2('open');
                                $(".myloading").hide();
                                return false;
                            }

                            // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                            var oTable02;
                            $(function() {
                                $.fn.dataTable.ext.errMode = 'throw';

                                oTable02 = $('#data-table-02').DataTable({
                                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    destroy: true,
                                    ordering: false,
                                    scrollY: '' + ($(window).height() - 370) + 'px',
                                    ajax: {
                                        url: '{{ route('backend.add_ai_cash_02.datatable') }}',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            business_location_id_fk: business_location_id_fk,
                                            branch_id_fk: branch_id_fk,
                                            doc_id: doc_id,
                                            bill_sdate: bill_sdate,
                                            bill_edate: bill_edate,
                                            transfer_amount_approver: transfer_amount_approver,
                                            transfer_bill_status: transfer_bill_status,
                                            approve_sdate: approve_sdate,
                                            approve_edate: approve_edate,
                                        },
                                        method: 'POST',
                                    },
                                    columns: [{
                                            data: 'id',
                                            title: 'ID',
                                            className: 'text-center w50'
                                        },
                                        {
                                            data: 'created_at',
                                            title: '<center>วันที่สั่งซื้อ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'customer_name',
                                            title: '<center>ลูกค้า </center>',
                                            className: 'text-left w100 '
                                        },
                                        {
                                            data: 'aicash_remain',
                                            title: '<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'aicash_amt',
                                            title: '<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'action_user',
                                            title: '<center>พนักงาน <br> ที่ดำเนินการ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'pay_type_id_fk',
                                            title: '<center>รูปแบบการชำระเงิน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'total_amt',
                                            title: '<center>ยอดชำระเงิน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'other_bill',
                                            title: '<center>ชำระร่วม </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'status',
                                            title: '<center>สถานะ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approver',
                                            title: '<center>ผู้อนุมัติ</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approve_date',
                                            title: '<center>วันที่อนุมัติ</center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'id',
                                            title: 'Tools',
                                            className: 'text-center w60'
                                        },
                                    ],
                                    rowCallback: function(nRow, aData, dataIndex) {

                                        if (aData['transfer_bill_status'] == 5) {
                                            for (var i = 0; i < 6; i++) {
                                                $('td:eq( ' + i + ')', nRow).html(aData[i]).css({
                                                    'color': '#d9d9d9',
                                                    'text-decoration': 'line-through',
                                                    'font-style': 'italic'
                                                });
                                            }
                                            // $('td:last-child', nRow).html('-ยกเลิก-');
                                            $('td:last-child', nRow).html('-');

                                        } else {

                                            var sPermission = "<?= \Auth::user()->permission ?>";
                                            var sU = sessionStorage.getItem("sU");
                                            var sD = sessionStorage.getItem("sD");
                                            if (sPermission == 1) {
                                                sU = 1;
                                                sD = 1;
                                            }
                                            var str_U = '';
                                            if (sU == '1') {
                                                str_U =
                                                    '<a href="{{ route('backend.add_ai_cash.index') }}/' +
                                                    aData['id'] +
                                                    '/edit" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                            }
                                            var str_D = '';
                                            if (sD == '1' && aData['pay_with_other_bill_select'] !=
                                                1) {
                                                str_D =
                                                    ' <a href="javascript: void(0);" data-url="{{ route('backend.add_ai_cash.index') }}/' +
                                                    aData['id'] +
                                                    '" class="btn btn-sm btn-danger cDeleteX cDelete " customer_id_fk="' +
                                                    aData['customer_id_fk'] + '"  data-id="' +
                                                    aData['id'] +
                                                    '"  ><i class="bx bx-trash font-size-16 align-middle"></i></a> ';
                                            }
                                            if (sU != '1' && sD != '1') {
                                                $('td:last-child', nRow).html('-');
                                            } else {
                                                $('td:last-child', nRow).html(str_U + str_D)
                                                    .addClass('input');
                                            }


                                        }

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
                </script>


                <!-- <audio autoplay>
                      <source src="http://freesound.org/data/previews/263/263133_2064400-lq.mp3">
                    </audio>
                     -->

                <script type="text/javascript">
                    $(document).ready(function() {

                        var sApprove = "<?= $sA ?>";
                        // alert(sApprove);
                        // setInterval(function(){
                        //   $.ajax({
                        //      type:'POST',
                        //      url: " {{ url('backend/ajaxCheckAddAiCashStatus') }} ",
                        //       success:function(data){
                        //              // console.log(data);
                        //              if(data>0){
                        //                 $('.toast').toast('show');
                        //              }
                        //         },

                        //   });

                        // }, 3000);

                        // setInterval(function(){
                        // alert("Oooo Yeaaa!");
                        if (sApprove == 1) {
                            $.ajax({
                                type: 'POST',
                                url: " {{ url('backend/ajaxCheckAddAiCashStatus') }} ",
                                success: function(data) {
                                    // console.log(data);
                                    if (data > 0) {
                                        // alert("! พบรายการ Ai-Cash ที่ยังรอการชำระ ");
                                        // return false;
                                        Swal.fire({
                                            type: 'warning',
                                            title: '! พบรายการ Ai-Cash ที่ยังรอการชำระ ',
                                            showConfirmButton: false,
                                            timer: 3500
                                        })

                                    }
                                },

                            });

                        }

                        // }, 5000);

                    });
                </script>

                <script type="text/javascript">
                    $(document).ready(function() {

                        var sApprove = "<?= $sA ?>";

                        if (sApprove == 1) {

                            $(document).idle({
                                onIdle: function() {
                                    // alert('You did nothing for 5 seconds');
                                    $.ajax({
                                        type: 'POST',
                                        url: " {{ url('backend/ajaxCheckAddAiCashStatus') }} ",
                                        success: function(data) {
                                            // console.log(data);
                                            if (data > 0) {
                                                // alert("! พบรายการ Ai-Cash ที่ยังรอการชำระ ");
                                                // return false;
                                                Swal.fire({
                                                    type: 'warning',
                                                    title: '! พบรายการ Ai-Cash ที่ยังรอการชำระ ',
                                                    showConfirmButton: false,
                                                    timer: 3500
                                                })

                                            }
                                        },

                                    });

                                },
                                idle: 3000
                            });

                        }


                    });
                </script>
                <script type="text/javascript">
                    $(document).ready(function() {

                        $("#customer_id_fk").select2({
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

                    });
                </script>



                <script>
                    // Clear data in View page
                    $(document).ready(function() {

                        $("#bill_type").on('change', function() {

                            var v = $(this).val();
                            if (v == '1') {
                                $(".btnSearch01").show();
                                $(".btnSearch02").hide();
                            } else if (v == '2') {
                                $(".btnSearch02").show();
                                $(".btnSearch01").hide();
                            } else {
                                // $(".btnSearch01").hide();
                                // $(".btnSearch02").hide();
                            }



                        });

                    });
                </script>
            @endsection
