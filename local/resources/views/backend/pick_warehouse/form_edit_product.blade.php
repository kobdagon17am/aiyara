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


        table#data-table-0003 thead {
            display: none;
        }

        table#data-table-0004 thead {
            display: none;
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
    <div class="myloading"></div>

    <?php
    $sPermission = \Auth::user()->permission;
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
    ?>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">

                <div class="col-6">
                    <h4 class="font-size-18  "> เพิ่มสินค้าที่ขาดหายจากการเบิก </h4>
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
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url('backend/pay_requisition_001') }}">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>

                </div>

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="myBorder" style="">

                        <div class="col-12">
                            <form id="action_form" action="{{ url('backend/pick_warehouse_edit_product_store') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <input type="hidden" name="pick_pack_packing_code_id" value="{{$pick_pack_requisition_code_id_fk}}">

                                <div class="form-group row  ">
                                    <div class="col-md-12 ">

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">ลำดับ</th>
                                                    <th class="text-center">รายการสินค้า</th>
                                                    <th class="text-center">จำนวนที่ขาด</th>
                                                    <th class="text-center">คลังที่ตัดจ่าย</th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td class="text-center">
                                                        <select name="product_id_fk[]"
                                                            class="form-control select2-templating product_id_fk" required>
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                            @if (@$Products)
                                                                @foreach (@$Products as $r)
                                                                    <option value="{{ @$r->product_id }}">
                                                                        {{ @$r->product_code . ' : ' . @$r->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control text-right amt_need" name="amt_need[]"
                                                            type="number" min="0" required>
                                                    </td>
                                                    <td class="text-center">
                                                        <select name="wh[]" class="form-control select2-templating wh"
                                                            required>
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">2</td>
                                                    <td class="text-center">
                                                        <select name="product_id_fk[]"
                                                            class="form-control select2-templating product_id_fk">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                            @if (@$Products)
                                                                @foreach (@$Products as $r)
                                                                    <option value="{{ @$r->product_id }}">
                                                                        {{ @$r->product_code . ' : ' . @$r->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control text-right amt_need" name="amt_need[]"
                                                            type="number" min="0">
                                                    </td>
                                                    <td class="text-center">
                                                        <select name="wh[]" class="form-control select2-templating wh">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">3</td>
                                                    <td class="text-center">
                                                        <select name="product_id_fk[]"
                                                            class="form-control select2-templating product_id_fk">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                            @if (@$Products)
                                                                @foreach (@$Products as $r)
                                                                    <option value="{{ @$r->product_id }}">
                                                                        {{ @$r->product_code . ' : ' . @$r->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control text-right amt_need" name="amt_need[]"
                                                            type="number" min="0">
                                                    </td>
                                                    <td class="text-center">
                                                        <select name="wh[]" class="form-control select2-templating wh">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">4</td>
                                                    <td class="text-center">
                                                        <select name="product_id_fk[]"
                                                            class="form-control select2-templating product_id_fk">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                            @if (@$Products)
                                                                @foreach (@$Products as $r)
                                                                    <option value="{{ @$r->product_id }}">
                                                                        {{ @$r->product_code . ' : ' . @$r->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control text-right amt_need" name="amt_need[]"
                                                            type="number" min="0">
                                                    </td>
                                                    <td class="text-center">
                                                        <select name="wh[]" class="form-control select2-templating wh">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">5</td>
                                                    <td class="text-center">
                                                        <select name="product_id_fk[]"
                                                            class="form-control select2-templating product_id_fk">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                            @if (@$Products)
                                                                @foreach (@$Products as $r)
                                                                    <option value="{{ @$r->product_id }}">
                                                                        {{ @$r->product_code . ' : ' . @$r->product_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-control text-right amt_need" name="amt_need[]"
                                                            type="number" min="0">
                                                    </td>
                                                    <td class="text-center">
                                                        <select name="wh[]" class="form-control select2-templating wh">
                                                            <option value="">-ไม่ทำรายการ-</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>



                                    </div>

                                    <div class="col-md-12 text-right">
                                        <br>
                                        <button class="btn btn-primary" onclick="return confirm('ยืนยันการทำรายการ')"
                                            type="submit">
                                            ยืนยันทำรายการ
                                        </button>
                                    </div>
                                </div>
                            </form>
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
        $(document).on('change', '.product_id_fk', function(event) {
            var product_id_fk = $(this).closest('tr').find('.product_id_fk').val();
            var wh = $(this).closest('tr').find('.wh');
            wh.empty();
            $.ajax({
                type: "POST",
                url: " {{ url('backend/ajaxSelectWh') }} ",
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id_fk: product_id_fk
                },
                success: function(data) {
                    $('<option/>', {
                        value: '',
                        text: 'กรุณาเลือก'
                    }).appendTo(wh);
                    $.each(data.data, function(i, item) {
                        $('<option/>', {
                            value: item.id,
                            text: item.lot_number + ' [' + item.lot_expired_date +
                                '] / คลังจ่าย / จำนวนที่เหลือในคลัง : ' + item.amt,
                        }).appendTo(wh);
                    });
                }
            });


        });
    </script>
@endsection
