@extends('backend.layouts.master')

@section('title')
    Aiyara Planet
@endsection

@section('css')
@endsection

@section('content')
    <div class="myloading"></div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> นำสินค้าออก </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <?php
    $sPermission = \Auth::user()->permission;
    $menu_id = @$_REQUEST['menu_id'];
    $role_group_id = @$_REQUEST['role_group_id'];
    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
        $sA = '';
    } else {
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sA = @$menu_permit->can_answer == 1 ? '' : 'display:none;';
    }

    //   echo $sPermission;
    // echo $role_group_id;
    // echo $menu_id;

    ?>
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-body">
                    @if (empty(@$sRow))
                        <form action="{{ route('backend.general_takeout.store') }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                        @else
                            <form action="{{ route('backend.general_takeout.update', @$sRow->id) }}" method="POST"
                                enctype="multipart/form-data" autocomplete="off">
                                <input name="_method" type="hidden" value="PUT">
                    @endif
                    {{ csrf_field() }}

                    <?php //echo \Auth::user()->business_location_id_fk ;
                    ?>
                    <?php //dd(@$sBusiness_location);
                    ?>

                    <div class="myBorder">


                        @if (!empty(@$sRow))
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> Ref. code : </label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$sRow->ref_doc }}" readonly="">
                                </div>
                            </div>
                        @endif

                        @if (@\Auth::user()->permission == 1)

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                                <div class="col-md-8">
                                    <select id="business_location_id_fk" name="business_location_id_fk"
                                        class="form-control select2-templating " required="">
                                        <option value="">-Business Location-</option>

                                        @if (@$sBusiness_location)
                                            @foreach (@$sBusiness_location as $r)
                                                @if (!empty(@$sRow->business_location_id_fk))
                                                    <option value="{{ @$r->id }}"
                                                        {{ @$r->id == @$sRow->business_location_id_fk ? 'selected' : '' }}>
                                                        {{ $r->txt_desc }}</option>
                                                @ELSE
                                                    <option value="{{ @$r->id }}">{{ $r->txt_desc }}</option>
                                                @endif
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> สาขา : * </label>
                                <div class="col-md-8">
                                    <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating ">
                                        @if (!empty(@$sRow->branch_id_fk))
                                            @if (@$sBranchs)
                                                @foreach (@$sBranchs as $r)
                                                    <option value="{{ @$r->id }}"
                                                        {{ @$r->id == @$sRow->branch_id_fk ? 'selected' : '' }}>
                                                        {{ $r->b_name }}</option>
                                                @endforeach
                                            @endif
                                        @ELSE
                                            <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                        @endif
                                    </select>

                                </div>
                            </div>
                        @ELSE
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                                <div class="col-md-8">
                                    <select class="form-control select2-templating " disabled="">
                                        @if (@$sBusiness_location)
                                            @foreach (@$sBusiness_location as $r)
                                                <option value="{{ @$r->id }}"
                                                    {{ @$r->id == @\Auth::user()->business_location_id_fk ? 'selected' : '' }}>
                                                    {{ $r->txt_desc }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="business_location_id_fk"
                                        value="{{ @\Auth::user()->business_location_id_fk }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> สาขา : * </label>
                                <div class="col-md-8">
                                    <select class="form-control select2-templating " disabled="">
                                        @if (@$sBranchs)
                                            @foreach (@$sBranchs as $r)
                                                <option value="{{ @$r->id }}"
                                                    {{ @$r->id == @\Auth::user()->branch_id_fk ? 'selected' : '' }}>
                                                    {{ $r->b_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="branch_id_fk" value="{{ @\Auth::user()->branch_id_fk }}">
                                </div>
                            </div>

                        @ENDIF


                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> สาเหตุที่นำออก : * </label>
                            <div class="col-md-8">
                                <select name="product_out_cause_id_fk" class="form-control select2-templating " id='export'
                                    required onchange="g_export('export')">
                                    <option value="">Select</option>
                                    @if (@$Product_out_cause)
                                        @foreach (@$Product_out_cause as $r)
                                            <option value="{{ $r->id }}"
                                                {{ @$r->id == @$sRow->product_out_cause_id_fk ? 'selected' : '' }}>
                                                {{ $r->txt_desc }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        {{-- <div class="form-group row @if (@$sRow->product_in_cause_id_fk != '5') d-none @endif" id="what_export">
                            <label for="" class="col-md-3 col-form-label"> หมายเหตุ : * </label>
                            <div class="col-md-8">
                                <textarea name="description" class='form-control' cols="30" rows="10">{{ @$sRow->description }}</textarea>
                            </div>
                        </div> --}}

                        <div class="form-group row" id="what_export">
                          <label for="" class="col-md-3 col-form-label"> หมายเหตุ : * </label>
                          <div class="col-md-8">
                              <textarea name="description" class='form-control' cols="30" rows="10">{{ @$sRow->description }}</textarea>
                          </div>
                      </div>

                        {{-- @if (!empty(@$sRow->description))
                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label"> </label>
                                <div class="col-md-8">
                                    <textarea class='form-control' cols="30" rows="10">{{ @$sRow->description }}aaaaaaaaaa</textarea>
                                </div>
                            </div>
                        @ENDIF --}}

                        <div class="form-group row">
                            <label for="receive_person" class="col-md-3 col-form-label">ผู้รับ (นำออกไปให้ใคร) : *</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" value="{{ @$sRow->receive_person }}"
                                    name="receive_person" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> รหัสสินค้า : ชื่อสินค้า : * </label>
                            <div class="col-md-8">

                                <select id="product_id_fk" name="product_id_fk" class="form-control select2-templating "
                                    required>

                                    <option value="">Select</option>
                                    @if (@$Products)
                                        @foreach (@$Products as $r)
                                            <option value="{{ @$r->product_id }}"
                                                {{ @$r->product_id == @$sRow->product_id_fk ? 'selected' : '' }}>
                                                {{ @$r->product_code . ' : ' . @$r->product_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="lot_number" class="col-md-3 col-form-label">Lot Number : * </label>
                            <div class="col-md-8">

                                @if (empty(@$sRow))
                                    <select id="lot_number" class="form-control select2-templating ">
                                        <option disabled selected>กรุณาเลือกสินค้าก่อน</option>
                                    </select>
                                @else
                                    <select id="lot_number" class="form-control select2-templating ">

                                        @if (@$Check_stock)
                                            @foreach (@$Check_stock as $r)
                                                <option value="{{ $r->lot_number }}"
                                                    {{ @$r->id == @$sRow->stocks_id_fk ? 'selected' : '' }}>
                                                    {{ $r->lot_number }}
                                                    [{{ $r->w_name }}]
                                                    [Expired : {{ $r->lot_expired_date }}]

                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                @endif

                                <input type="hidden" name="stocks_id_fk" id="stocks_id_fk"
                                    value="{{ @$sRow->stocks_id_fk }}">
                                <input type="hidden" name="lot_number" id="lot_number_txt"
                                    value="{{ @$sRow->lot_number }}">
                                <input type="hidden" name="lot_expired_date" id="lot_expired_date"
                                    value="{{ @$sRow->lot_expired_date }}">

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Product details : </label>
                            <div class="col-md-8">
                                <div id="div_product_details"></div>

                                <input type="hidden" name="product_unit_id_fk" id="product_unit_id_fk"
                                    value="{{ @$sRow->product_unit_id_fk }}">
                                <input type="hidden" name="warehouse_id_fk" id="warehouse_id_fk"
                                    value="{{ @$sRow->warehouse_id_fk }}">
                                <input type="hidden" name="zone_id_fk" id="zone_id_fk" value="{{ @$sRow->zone_id_fk }}">
                                <input type="hidden" name="shelf_id_fk" id="shelf_id_fk"
                                    value="{{ @$sRow->shelf_id_fk }}">
                                <input type="hidden" name="shelf_floor" id="shelf_floor"
                                    value="{{ @$sRow->shelf_floor }}">


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amt" class="col-md-3 col-form-label">จำนวนคงคลัง : </label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" id="amt_in_stock" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amt" class="col-md-3 col-form-label">จำนวนที่นำออก : * </label>
                            <div class="col-md-3">
                                <input class="form-control" type="number" value="{{ @$sRow->amt }}" id="amt" name="amt"
                                    min="1" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amt" class="col-md-3 col-form-label">บันทึกลงตาราง : </label>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="button" id="add_p_list">+ รายการลงตาราง</button>
                            </div>
                        </div>

                        <br>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center"><b>รายการสินค้าที่นำออก</b></th>
                                </tr>
                                <tr>
                                    <th>รหัสสินค้า : ชื่อสินค้า</th>
                                    <th>Lot Number</th>
                                    <th>Product details</th>
                                    {{-- <th>จำนวนคงคลัง</th> --}}
                                    <th>จำนวนที่นำออก</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table_list">
                                @if (isset($sRow))
                                    <?php
                                    $items = DB::table('db_general_takeout_item')
                                        ->where('general_takeout_id', $sRow->id)
                                        ->get();
                                    ?>

                                    @foreach ($items as $item)
                                        <tr>
                                            <input type="hidden" name="pro_name[]" class="pro_name"
                                                value="{{ $item->pro_name }}">
                                            <input type="hidden" name="lot_name[]" class="lot_name"
                                                value="{{ $item->lot_name }}">
                                            <input type="hidden" name="pro_detail[]" class="pro_detail"
                                                value="{{ $item->pro_detail }}">
                                            <td>
                                                <label>{{ $item->pro_name }}</label>
                                                <input name="product_id_fk2[]" class="product_id_fk2" type="hidden"
                                                    value="{{ $item->product_id_fk }}">
                                            </td>
                                            <td>
                                                <label>{{ $item->lot_name }}</label>
                                                <input type="hidden" name="stocks_id_fk2[]" class="stocks_id_fk2"
                                                    value="{{ $item->stocks_id_fk }}">
                                                <input type="hidden" name="lot_number2[]" class="lot_number2"
                                                    value="{{ $item->lot_number }}">
                                                <input type="hidden" name="lot_expired_date2[]" class="lot_expired_date2"
                                                    value="{{ $item->lot_expired_date }}">
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $item->pro_detail }}
                                                </div>
                                                <input type="hidden" name="product_unit_id_fk2[]"
                                                    class="product_unit_id_fk2" value="{{ $item->product_unit_id_fk }}">
                                                <input type="hidden" name="warehouse_id_fk2[]" class="warehouse_id_fk2"
                                                    value="{{ $item->warehouse_id_fk }}">
                                                <input type="hidden" name="zone_id_fk2[]" class="zone_id_fk2"
                                                    value="{{ $item->zone_id_fk }}">
                                                <input type="hidden" name="shelf_id_fk2[]" class="shelf_id_fk2"
                                                    value="{{ $item->shelf_id_fk }}">
                                                <input type="hidden" name="shelf_floor2[]" class="shelf_floor2"
                                                    value="{{ $item->shelf_floor }}">

                                            </td>
                                            <td class="text-right">
                                                <label>{{ $item->amt }}</label>
                                                <input class="form-control amt2" type="hidden" value="{{ $item->amt }}"
                                                    name="amt2[]" min="1" readonly>
                                            </td>
                                            <td>
                                                @if (@$sRow->approve_status != 1)
                                                    <button class="btn btn-danger btn-sm remove_list"
                                                        type="button">Remove</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif


                            </tbody>
                        </table>


                        <br>


                        <div class="form-group row">
                            @if (empty(@$sRow))
                                <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ (User Login) :</label>
                            @else
                                <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ :</label>
                            @endif
                            <div class="col-md-8">
                                @if (empty(@$sRow))
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly
                                        style="background-color: #f2f2f2;">
                                    <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}"
                                        name="recipient">
                                @else
                                    <input class="form-control" type="text" value="{{ @$Recipient[0]->name }}" readonly
                                        style="background-color: #f2f2f2;">
                                    <input class="form-control" type="hidden" value="{{ @$sRow->recipient }}"
                                        name="recipient">
                                @endif

                            </div>
                        </div>

                        <div class="myBorder div_confirm_general_takeout ">

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                                <div class="col-md-8">
                                    @if (empty(@$sRow))
                                        <input class="form-control" type="text" value="{{ \Auth::user()->name }}"
                                            readonly style="background-color: #f2f2f2;">
                                        <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}"
                                            name="approver">
                                    @else
                                        <input class="form-control" type="text" value="{{ \Auth::user()->name }}"
                                            readonly style="background-color: #f2f2f2;">
                                        <input class="form-control" type="hidden" value="{{ @$sRow->approver }}"
                                            name="approver">
                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">สถานะการอนุมัติ :</label>
                                <div class="col-md-8 mt-2">
                                    <div class="custom-control custom-switch">
                                        @if (empty($sRow))
                                            <input type="checkbox" class="custom-control-input" id="customSwitch"
                                                name="approve_status" value="1">
                                        @else
                                            <input type="checkbox" class="custom-control-input" id="customSwitch"
                                                name="approve_status" value="1"
                                                {{ @$sRow->approve_status == '1' ? 'checked' : '' }}>
                                        @endif
                                        <label class="custom-control-label" for="customSwitch">อนุมัติ / Aproved</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group mb-0 row">
                            <div class="col-md-6">
                                <a class="btn btn-secondary btn-sm waves-effect"
                                    href="{{ url('backend/general_takeout') }}">
                                    <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                </a>
                            </div>
                            <div class="col-md-6 text-right">

                                <input type="hidden" name="role_group_id" value="{{ @$_REQUEST['role_group_id'] }}">
                                <input type="hidden" name="menu_id" value="{{ @$_REQUEST['menu_id'] }}">

                                @if (@$sRow->approve_status != '1')
                                    <button type="submit" class="btn btn-primary btn-sm waves-effect">
                                        <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                                    </button>
                                @endif

                            </div>
                        </div>

                        </form>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // $(document).on('click', '.add_list', function() {
            //     var tr = $(this).closest('tr');
            //     $(this).find('').select2('destroy');
            //     var data = tr.clone();
            //     $(tr).after(data);
            // });

            $(document).on('click', '#add_p_list', function() {
                $(".myloading").show();
                var product_id_fk2 = $('#product_id_fk').val();
                var stocks_id_fk2 = $('#stocks_id_fk').val();
                var lot_number2 = $('#lot_number_txt').val();
                var lot_expired_date2 = $('#lot_expired_date').val();
                var product_unit_id_fk2 = $('#product_unit_id_fk').val();
                var warehouse_id_fk2 = $('#warehouse_id_fk').val();
                var zone_id_fk2 = $('#zone_id_fk').val();
                var shelf_id_fk2 = $('#shelf_id_fk').val();
                var shelf_floor2 = $('#shelf_floor').val();
                var amt2 = $('#amt').val();
                var div_product_details = $('#div_product_details').text();
                var pro = $("#product_id_fk option:selected").text();
                var lot = $("#lot_number option:selected").text();
                var detail = div_product_details;

                if (amt2 == 0 || amt2 == '') {
                    $(".myloading").hide();
                    return false;
                }

                // $.ajax({
                //     url: " {{ url('backend/ajaxGetProductDetail') }} ",
                //     method: "post",
                //     data: {
                //         product_id_fk: product_id_fk2,
                //         "_token": "{{ csrf_token() }}",
                //     },
                //     success: function(data) {

                //         $(".myloading").hide();

                //     }
                // })

                var data = '<tr>' +
                    '<input type="hidden" name="pro_name[]" class="pro_name" value="' +
                    pro + '">' +
                    '<input type="hidden" name="lot_name[]" class="lot_name" value="' +
                    lot + '">' +
                    '<input type="hidden" name="pro_detail[]" class="pro_detail" value="' +
                    detail + '">' +
                    '<td>' +
                    '<label>' + pro + '</label>' +
                    '<input name="product_id_fk2[]" class="product_id_fk2" type="hidden" value="' +
                    product_id_fk2 + '">' +
                    '</td>' +
                    '<td>' +
                    '<label>' + lot + '</label>' +
                    '<input type="hidden" name="stocks_id_fk2[]" class="stocks_id_fk2" value="' +
                    stocks_id_fk2 + '">' +
                    '<input type="hidden" name="lot_number2[]" class="lot_number2" value="' +
                    lot_number2 + '">' +
                    '<input type="hidden" name="lot_expired_date2[]" class="lot_expired_date2" value="' +
                    lot_expired_date2 + '">' +
                    '</td>' +
                    '<td><div>' +
                    div_product_details +
                    '</div>' +
                    '<input type="hidden" name="product_unit_id_fk2[]" class="product_unit_id_fk2"' +
                    'value="' +
                    product_unit_id_fk2 + '">' +
                    '<input type="hidden" name="warehouse_id_fk2[]" class="warehouse_id_fk2" value="' +
                    warehouse_id_fk2 + '">' +
                    '<input type="hidden" name="zone_id_fk2[]" class="zone_id_fk2" value="' +
                    zone_id_fk2 + '">' +
                    '<input type="hidden" name="shelf_id_fk2[]" class="shelf_id_fk2" value="' +
                    shelf_id_fk2 + '">' +
                    '<input type="hidden" name="shelf_floor2[]" class="shelf_floor2" value="' +
                    shelf_floor2 + '">' +
                    '</td>' +
                    '<td class="text-right">' +
                    '<label>' + amt2 + '</label>' +
                    '<input class="form-control amt2" type="hidden" value="' +
                    amt2 + '" name="amt2[]" min="1"' +
                    'readonly>' +
                    '</td>' +
                    '<td>' +
                    '<button class="btn btn-danger btn-sm remove_list" type="button">Remove</button>' +
                    '</td>' +
                    '</tr>';

                $("#table_list").append(data);
                $(".myloading").hide();
            });

        });

        $(document).on('click', '.remove_list', function() {
            $(this).closest('tr').remove();
        });

        function g_export(id) {

            // var a = document.getElementById(id).value; // alert(a);
            // if (a == 5) {
            //     $('#what_export').removeClass('d-none');
            // } else {
            //     $('#what_export').addClass('d-none');
            // }
        }

        $('#business_location_id_fk').change(function() {

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
                        if (data == '') {
                            alert('ไม่พบข้อมูลสาขา !!.');
                        } else {
                            var layout = '<option value="" selected>- เลือกสาขา -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.b_name +
                                    '</option>';
                            });
                            $('#branch_id_fk').html(layout);
                            $('#warehouse_id_fk').html(
                                '<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                            $('#zone_id_fk').html(
                                '<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                            $('#shelf_id_fk').html(
                                '<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                        }
                    }
                })
            }

        });



        $('#branch_id_fk').change(function() {

            var branch_id_fk = this.value;
            // alert(branch_id_fk);

            if (branch_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetWarehouse') }} ",
                    method: "post",
                    data: {
                        branch_id_fk: branch_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data == '') {
                            alert('ไม่พบข้อมูลคลัง !!.');
                        } else {
                            var layout = '<option value="" selected>- เลือกคลัง -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.w_name +
                                    '</option>';
                            });
                            $('#warehouse_id_fk').html(layout);
                            $('#zone_id_fk').html(
                                '<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                            $('#shelf_id_fk').html(
                                '<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                            $('#shelf_floor').val(1);
                        }
                    }
                })
            } else {
                $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                $('#shelf_floor').val(1);
            }

        });


        $('#warehouse_id_fk').change(function() {

            var warehouse_id_fk = this.value;
            // alert(warehouse_id_fk);

            if (warehouse_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetZone') }} ",
                    method: "post",
                    data: {
                        warehouse_id_fk: warehouse_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data == '') {
                            alert('ไม่พบข้อมูล Zone !!.');
                        } else {
                            var layout = '<option value="" selected>- เลือก Zone -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.z_name +
                                    '</option>';
                            });
                            $('#zone_id_fk').html(layout);
                            $('#shelf_id_fk').html('กรุณาเลือกโซนก่อน');
                        }
                    }
                })
            }

        });



        $('#zone_id_fk').change(function() {

            var zone_id_fk = this.value;
            // alert(zone_id_fk);

            if (zone_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetShelf') }} ",
                    method: "post",
                    data: {
                        zone_id_fk: zone_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data == '') {
                            alert('ไม่พบข้อมูล Shelf !!.');
                        } else {
                            var layout = '<option value="" selected>- เลือก Shelf -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.s_name +
                                    '</option>';
                            });
                            $('#shelf_id_fk').html(layout);
                        }
                    }
                })
            }

        });


        $('#product_id_fk').change(function() {

            $(".myloading").show();

            var product_id_fk = this.value;
            var business_location_id_fk = $("#business_location_id_fk").val();
            var branch_id_fk = $("#branch_id_fk").val();
            // alert(business_location_id_fk);
            // alert(branch_id_fk);
            $('#amt').val('');
            $('#amt_in_stock').val('');
            $('#lot_number_txt').val('');
            $('#div_product_details').html('');


            if (product_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetLotnumber') }} ",
                    method: "post",
                    data: {
                        business_location_id_fk: business_location_id_fk,
                        branch_id_fk: branch_id_fk,
                        product_id_fk: product_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data == '') {
                            alert('ไม่พบข้อมูล Lot number !!.');
                            var layout = '<option value="" selected>- เลือก Lot number -</option>';
                            $('#lot_number').html(layout);
                        } else {
                            var layout = '<option value="" selected>- เลือก Lot number -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.lot_number +
                                    ' [' + value.w_name + '] [Expired:' + value
                                    .lot_expired_date + ']</option>';
                                // $('#lot_number_txt').val(value.lot_number);
                            });
                            $('#lot_number').html(layout);
                        }

                        $(".myloading").hide();


                    }
                })
            } else {
                var layout = '<option value="" selected>- เลือก Lot number -</option>';
                $('#lot_number').html(layout);
                $(".myloading").hide();
            }

        });


        $('#lot_number').change(function() {

            $(".myloading").show();

            var id = this.value;
            var product_id_fk = $('#product_id_fk').val();
            // alert(id+":"+product_id_fk);

            $('#stocks_id_fk').val(id);

            if (id == '') {
                $(".myloading").hide();
                $('#div_product_details').html('');
                return false;
            }
            if (product_id_fk == '') {
                alert("กรุณาเลือกสินค้า ?");
                $('#product_id_fk').select2('open');
                $(".myloading").hide();
                $('#div_product_details').html('');
                return false;
            }

            $('#amt').val('');
            $('#amt_in_stock').val('');

            if (product_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetAmtInStock') }} ",
                    method: "post",
                    data: {
                        id: id,
                        product_id_fk: product_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {

                        $.each(data, function(key, value) {
                            $('#lot_number_txt').val(value.lot_number);
                            $('#amt_in_stock').val(value.amt);
                            if (value.amt == 0) {
                                alert("! สินค้าในคลังไม่มี");
                                $('#amt').val(0);
                                $('#amt').prop('disabled', true);
                            } else {
                                $('#amt').prop('disabled', false);
                                $('#amt').focus();
                            }
                            $('#div_product_details').html(
                                "วันล๊อตหมดอายุ : " + value.lot_expired_date + '<br/>' +
                                "หน่วยนับ : " + value.product_unit +
                                '<br/>' + "Business Location : " + value.business_location +
                                '<br/>' + "สาขา : " + value.branch +
                                '<br/>' + "คลัง : " + value.w_name +
                                '<br/>' + "โซน : " + value.z_name +
                                '<br/>' + "Shelf : " + value.s_name +
                                '<br/>' + "ชั้น : " + value.shelf_floor
                            );

                            $('#lot_expired_date').val(value.lot_expired_date);
                            $('#product_unit_id_fk').val(value.product_unit_id_fk);
                            $('#warehouse_id_fk').val(value.warehouse_id_fk);
                            $('#zone_id_fk').val(value.zone_id_fk);
                            $('#shelf_id_fk').val(value.shelf_id_fk);
                            $('#shelf_floor').val(value.shelf_floor);
                            $('#lot_number_txt').val(value.lot_number);

                        });

                        $(".myloading").hide();

                    }
                })
            } else {
                $('#amt_in_stock').val(0);
                $(".myloading").hide();
            }

        });

        // if(localStorage.getItem('amt_in_stock')){
        //     $('#amt_in_stock').val(localStorage.getItem('amt_in_stock'));
        // }



        $('#amt').change(function() {

            var amt = parseInt(this.value);
            var amt_in_stock = parseInt($('#amt_in_stock').val());
            if (amt > amt_in_stock) {
                alert("กรุณาตรวจสอบ จำนวนนำออก มีมากกว่า จำนวนคงคลัง ?");
                $('#amt').val('');
                $('#amt').focus();
                return false;
            }


        });
    </script>

    <script>
        $(document).ready(function() {
            var id = $('#stocks_id_fk').val();
            var product_id_fk = $('#product_id_fk').val();
            // alert(id+":"+product_id_fk);

            if (lot_number != '' && product_id_fk != '') {
                $.ajax({
                    url: " {{ url('backend/ajaxGetAmtInStock') }} ",
                    method: "post",
                    data: {
                        id: id,
                        product_id_fk: product_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {

                        $.each(data, function(key, value) {
                            $('#amt_in_stock').val(value.amt);
                            $('#div_product_details').html(
                                "วันล๊อตหมดอายุ : " + value.lot_expired_date + '<br/>' +
                                "หน่วยนับ : " + value.product_unit +
                                '<br/>' + "Business Location : " + value.business_location +
                                '<br/>' + "สาขา : " + value.branch +
                                '<br/>' + "คลัง : " + value.w_name +
                                '<br/>' + "โซน : " + value.z_name +
                                '<br/>' + "Shelf : " + value.s_name +
                                '<br/>' + "ชั้น : " + value.shelf_floor
                            );
                        });

                        $(".myloading").hide();

                    }
                })
            }
        });
    </script>
@endsection
