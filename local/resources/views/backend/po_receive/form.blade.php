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
                <h4 class="mb-0 font-size-18"> รับสินค้าตาม PO </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <?php

    // print_r(\Auth::user()->business_location_id_fk);
    // print_r(\Auth::user()->branch_id_fk);

    $sPermission = \Auth::user()->permission;
    // print_r($sPermission);
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    // print_r($menu_id);

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
        // $menu_permit = DB::select(" select * from role_permit where role_group_id_fk=2 AND menu_id_fk=34 ");
        // print_r($menu_permit);
        // print_r($role_group_id);
        // print_r($menu_id);
        // print_r($menu_permit);
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
    }
    ?>

    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-body">

                    <div class="myBorder">

                        @if (empty($sRow))
                            <form action="{{ route('backend.po_supplier.store') }}" method="POST"
                                enctype="multipart/form-data" autocomplete="off">
                            @else
                                <form action="{{ route('backend.po_supplier.update', @$sRow->id) }}" method="POST"
                                    enctype="multipart/form-data" autocomplete="off">
                                    <input name="_method" type="hidden" value="PUT">
                        @endif
                        {{ csrf_field() }}


                        <div class="form-group row">
                            <label for="po_number" class="col-md-3 col-form-label">รหัสใบ PO :</label>
                            <div class="col-md-6">
                                @if (empty($sRow))
                                    <input class="form-control" type="text" name="po_number" value="{{ @$po_runno }}"
                                        readonly="">
                                @else
                                    <input class="form-control" type="text" value="{{ @$sRow->po_number }}"
                                        readonly="">
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                            <div class="col-md-6">
                                <select id="business_location_id_fk" name="business_location_id_fk"
                                    class="form-control select2-templating " disabled="">
                                    <option value="">-Business Location-</option>
                                    @if (@$sBusiness_location)
                                        @foreach (@$sBusiness_location as $r)
                                            <option value="{{ $r->id }}"
                                                {{ @$r->id == @$sRow->business_location_id_fk ? 'selected' : '' }}>
                                                {{ $r->txt_desc }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> สาขา : * </label>
                            <div class="col-md-6">

                                @if (empty(@$sRow))
                                    <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating "
                                        disabled>
                                        <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                                    </select>
                                @else
                                    <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating "
                                        disabled>
                                        @if (@$sBranchs)
                                            @foreach (@$sBranchs as $r)
                                                <option value="{{ $r->id }}"
                                                    {{ @$r->id == @$sRow->branch_id_fk ? 'selected' : '' }}>
                                                    {{ $r->b_name }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                @endif


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label"> Supplier : * </label>
                            <div class="col-md-6">
                                <select name="supplier_id_fk" id="supplier_id_fk" class="form-control select2-templating "
                                    disabled>
                                    <option value="">Select</option>
                                    @if (@$Supplier)
                                        @foreach (@$Supplier as $r)
                                            <option value="{{ $r->id }}"
                                                {{ @$r->id == @$sRow->supplier_id_fk ? 'selected' : '' }}>
                                                {{ $r->txt_desc }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="po_code_other" class="col-md-3 col-form-label">เลข PO (อื่นๆ ถ้ามี) :</label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="po_code_other"
                                    value="{{ @$sRow->po_code_other }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="note" class="col-md-3 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="3" id="note" name="note" disabled>{{ @$sRow->note }}</textarea>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="created_at" class="col-md-3 col-form-label">วันที่สร้างใบ PO : * </label>
                            <div class="col-md-2">
                                <input class="form-control" autocomplete="off" id="created_at" name="created_at"
                                    value="{{ @$sRow->created_at }}" disabled />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ(User Login):</label>
                            <div class="col-md-6">
                                @if (empty($sRow))
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly
                                        style="background-color: #f2f2f2;">
                                    <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}"
                                        name="action_user" readonly>
                                @else
                                    <input class="form-control" type="text" value="{{ @$action_user }}" readonly>
                                    <input class="form-control" type="hidden" value="{{ @$sRow->action_user }}"
                                        name="action_user">
                                @endif
                            </div>
                        </div>


                        <!--                 <div class="form-group mb-0 row">
                                                    <div class="col-md-6">
                                                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url('backend/po_supplier') }}">
                                                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                                                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                                                        </button>
                                                    </div>
                                                </div> -->

                        </form>
                    </div>


                    @if (!empty($sRow))
                        <div class="myBorder">
                            <div style="">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i>
                                            รายการรับสินค้าตามใบ PO </span>
                                        <!--
                                              <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.po_supplier_products.create') }}/{{ @$sRow->id }}" style="float: right;" >
                                                <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                                              </a> -->

                                        <a href="{{ URL::to('backend/po_supplier_products/print_receipt') }}/{{ @$sRow->id }}"
                                            target=_blank><i class="bx bx-printer grow "
                                                style="font-size:26px;cursor:pointer;color:#0099cc;float: right;padding: 1%;margin-right: 1%;"></i>
                                        </a>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table id="data-table" class="table table-bordered " style="width: 100%;">
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0 row">
                                <div class="col-md-6">

                                    <a class="btn btn-secondary btn-sm waves-effect"
                                        href="{{ url('backend/po_receive') }}">
                                        <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                    </a>

                                </div>
                            </div>

                        </div>



                        <div class="myBorder">


                            <div style="">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i>
                                            ประวัติการรับสินค้า </span>
                                        <!--   <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.po_receive_products_get.create') }}/{{ @$sRow->id }}" style="float: right;" >
                                                <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">บันทึกประวัติการรับสินค้า</span>
                                              </a> -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table id="data-table-history" class="table table-bordered "
                                            style="width: 100%;">
                                        </table>
                                    </div>
                                </div>

                                <form id="frm-main" action="{{ url('backend/po_receive_update_note3') }}"
                                    method="POST" enctype="multipart/form-data" autocomplete="off">
                                    <input name="id" type="hidden" value="{{ @$sRow->id }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <div class="form-group row">
                                                <label for="zone_id_fk_c" class="col-md-3 col-form-label"> หมายเหตุ :
                                                </label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="note3"
                                                        value="{{ @$sRow->note3 }}">
                                                    <button type="submit" class="btn btn-sm btn-primary">บันทึก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="form-group mb-0 row">
                                <div class="col-md-6">
                                    <a class="btn btn-secondary btn-sm waves-effect"
                                        href="{{ url('backend/po_receive') }}">
                                        <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endif



                    @if ($sPermission == 1 || @$menu_permit->can_approve == 1)
                        <div class="myBorder" style="">

                            <form id="frm-main" action="{{ route('backend.po_receive.update', @$sRow->id) }}"
                                method="POST" enctype="multipart/form-data" autocomplete="off">
                                <input name="_method" type="hidden" value="PUT">
                                <input name="id" type="hidden" value="{{ @$sRow->id }}">
                                <input name="approved" type="hidden" value="1">
                                {{ csrf_field() }}

                                <div class="form-group row">
                                    <label for="" class="col-md-4 col-form-label">ผู้อนุมัติ (Admin Login)
                                        :</label>
                                    <div class="col-md-6">
                                        @if (empty(@$sRow->id))
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
                                    <label class="col-md-4 col-form-label">สถานะการอนุมัติ :</label>
                                    <div class="col-md-3 mt-2">
                                        <div class=" ">

                                            <input type="radio" class="" id="customSwitch1"
                                                name="approve_status" value="1"
                                                {{ @$sRow->approve_status == '1' ? 'checked' : '' }} required>
                                            <label for="customSwitch1">อนุมัติ / Aproved</label>

                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <div class=" ">

                                            <input type="radio" class="" id="customSwitch2"
                                                name="approve_status" value="5"
                                                {{ @$sRow->approve_status == '5' ? 'checked' : '' }} required>
                                            <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>

                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label for="note" class="col-md-4 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" rows="3" id="note2" name="note2">{{ @$sRow->note2 }}</textarea>
                                    </div>
                                </div>


                                <div class="form-group mb-0 row">
                                    <div class="col-md-6">
                                        <a class="btn btn-secondary btn-sm waves-effect"
                                            href="{{ url('backend/po_receive') }}">
                                            <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-right">

                                        @if (@$sRow->approve_status > 0)
                                        @ELSE
                                            <button type="submit"
                                                class="btn btn-primary btn-sm waves-effect font-size-16 ">
                                                <i class="bx bx-save font-size-16 align-middle mr-1"></i> อนุมัติ
                                            </button>
                                        @ENDIF



                                    </div>
                                </div>

                            </form>

                        </div>
                    @ENDIF
                </div>
            </div> <!-- end col -->
        </div>
    </div>
    <!-- end row -->

    <div class="modal fade" id="setToWarehouseModal" tabindex="-1" role="dialog"
        aria-labelledby="setToWarehouseModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 1000px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setToWarehouseModalTitle"><b><i
                                class="bx bx-play"></i>บันทึกการรับสินค้าและระบุคลังจัดเก็บสินค้า </b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('backend.po_receive_products_get.store') }}" method="POST"
                    enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="save_set_to_warehouse" value="1">
                    <input type="hidden" id="po_supplier_products_id_fk" name="po_supplier_products_id_fk">
                    <input type="hidden" id="po_supplier_id_fk" name="po_supplier_id_fk" value="{{ @$sRow->id }}">
                    <input type="hidden" id="product_id_fk" name="product_id_fk">
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">


                                        <div class="row">
                                            <div class="col-md-5 ">
                                                <div class="form-group row">
                                                    <label for="" class="col-md-3 col-form-label"> ชื่อสินค้า :
                                                    </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="product_name"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="form-group row">
                                                    <label for="" class="col-md-3 col-form-label"> จำนวนที่ได้รับ
                                                        : </label>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control NumberOnly "
                                                            name="amt_get" id="amt_get" required>
                                                    </div>

                                                    <label for="" class="col-md-2 col-form-label">หน่วย : *
                                                    </label>

                                                    <div class="col-md-4">
                                                        <select name="product_unit_id_fk"
                                                            class="form-control select2-templating " required>
                                                            <option value="">Select</option>
                                                            @if (@$sProductUnit)
                                                                @foreach (@$sProductUnit as $r)
                                                                    <option value="{{ $r->id }}"
                                                                        {{ @$r->id == @$sRow->product_unit_id_fk ? 'selected' : '' }}>
                                                                        {{ $r->product_unit }}</option>
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
                                                    <label for="" class="col-md-3 col-form-label"> Lot Number :
                                                    </label>
                                                    <div class="col-md-7">
                                                        <input type="text" class="form-control lot_number_auto "
                                                            id="lot_number_auto" name="lot_number" required
                                                            value="{{ @$sRow->lot_number }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 ">
                                                <div class="form-group row">
                                                    <label for="" class="col-md-3 col-form-label"> วันหมดอายุ :
                                                    </label>
                                                    <div class="col-md-5">
                                                        @if (!empty(@$sRow->lot_expired_date))
                                                            <input class="form-control" type="text"
                                                                value="{{ @$sRow->lot_expired_date }}"
                                                                name="lot_expired_date" id="lot_expired_date"
                                                                pattern="(?:19|20)\[0-9\]{2}-(?:(?:0\[1-9\]|1\[0-2\])/(?:0\[1-9\]|1\[0-9\]|2\[0-9\])|(?:(?!02)(?:0\[1-9\]|1\[0-2\])/(?:30))|(?:(?:0\[13578\]|1\[02\])-31))"
                                                                readonly>
                                                        @ELSE
                                                            <input class="form-control" type="date"
                                                                value="{{ @$sRow->lot_expired_date }}"
                                                                name="lot_expired_date" id="lot_expired_date" required>
                                                        @ENDIF
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <div class="form-group row">
                                                    <label for="branch_id_fk_c" class="col-md-3 col-form-label"> สาขา :
                                                    </label>
                                                    <div class="col-md-7">
                                                        <select id="branch_id_fk_c" name="branch_id_fk_c"
                                                            class="form-control select2-templating " required>
                                                            <option value="">Select</option>
                                                            @if (@$sBranchs)
                                                                @foreach (@$sBranchs as $r)
                                                                    <option value="{{ $r->id }}">
                                                                        {{ $r->b_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 ">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-md-3 col-form-label"> คลัง
                                                        : </label>
                                                    <div class="col-md-5">
                                                        <select id="warehouse_id_fk_c" name="warehouse_id_fk_c"
                                                            class="form-control select2-templating " required>
                                                            <option value="">เลือกคลัง</option>
                                                            @if (@$Subwarehouse)
                                                                @foreach (@$Subwarehouse as $r)
                                                                    <option value="{{ $r->id }}">
                                                                        {{ $r->w_name }}
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
                                                    <label for="zone_id_fk_c" class="col-md-3 col-form-label"> Zone :
                                                    </label>
                                                    <div class="col-md-7">
                                                        <select id="zone_id_fk_c" name="zone_id_fk_c"
                                                            class="form-control select2-templating " required>
                                                            <option disabled selected>กรุณาเลือกคลังก่อน</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 ">
                                                <div class="form-group row">
                                                    <label for="shelf_id_fk_c" class="col-md-3 col-form-label"> Shelf :
                                                    </label>
                                                    <div class="col-md-5">
                                                        <select id="shelf_id_fk_c" name="shelf_id_fk_c"
                                                            class="form-control select2-templating " required>
                                                            <option disabled selected>กรุณาเลือกโซนก่อน</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control NumberOnly "
                                                            id="shelf_floor_c" name="shelf_floor_c"
                                                            placeholder="เก็บไว้ที่ชั้น" required>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <div class="form-group row">
                                                    <label for="zone_id_fk_c" class="col-md-3 col-form-label"> หมายเหตุ :
                                                    </label>
                                                    <div class="col-md-7">
                                                        <input type="text" class="form-control" name="remark"
                                                            value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center  ">
                                <button type="submit" class="btn btn-primary" style="width: 10%;"
                                    onclick="return confirm('ยืนยันการทำรายการ');">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    style="margin-left: 1%;">Close</button>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                    </div>

                </form>

            </div>
        </div>
    </div>



@endsection

@section('script')
    <script type="text/javascript">
        function showPreview_01(ele) {
            $('#image').attr('src', ele.value); // for IE
            if (ele.files && ele.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgAvatar_01').show();
                    $('#imgAvatar_01').attr('src', e.target.result);
                }
                reader.readAsDataURL(ele.files[0]);
            }
        }
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
        integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
        crossorigin="anonymous"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
        crossorigin="anonymous" />

    <script>
        $('#created_at').datetimepicker({
            value: '',
            rtl: false,
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d',
            monthChangeSpinner: true,
            closeOnTimeSelect: true,
            closeOnWithoutClick: true,
            closeOnInputClick: true,
            openOnFocus: true,
            timepicker: true,
            datepicker: true,
            weeks: false,
            minDate: 0,
        });
    </script>


    <script>
        var po_supplier_id_fk = "{{ @$sRow->id ? @$sRow->id : 0 }}";
        var oTable;

        $(function() {
            oTable = $('#data-table').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 5,
                ajax: {
                    url: '{{ route('backend.po_supplier_products.datatable') }}',
                    data: function(d) {
                        d.Where = {};
                        d.Where['po_supplier_id_fk'] = po_supplier_id_fk;
                        oData = d;
                    },
                    method: 'POST',
                },

                columns: [{
                        data: 'id',
                        title: 'ID',
                        className: 'text-center w50'
                    },
                    {
                        data: 'product_name',
                        title: 'รหัส : ชื่อสินค้า',
                        className: 'text-left'
                    },
                    {
                        data: 'product_amt',
                        title: 'จำนวนที่สั่งซื้อ',
                        className: 'text-center'
                    },
                    {
                        data: 'product_amt_receive',
                        title: 'จำนวนที่รับมาแล้ว',
                        className: 'text-center'
                    },
                    {
                        data: 'product_unit_desc',
                        title: 'หน่วยนับ',
                        className: 'text-center'
                    },
                    {
                        data: 'get_status',
                        title: 'สถานะ',
                        className: 'text-center'
                    },
                    {
                        data: 'id',
                        title: 'Tools',
                        className: 'text-center w80'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    // console.log(aData['get_status_2']);
                    // console.log(aData['product_amt']);
                    // console.log(aData['product_amt_receive']);

                    // if(aData['get_status_2']==2){


                    // if(aData['product_amt']>aData['product_amt_receive']){
                    if (aData['approve_status'] != 1 && aData['approve_status'] != 5) {

                        $('td:last-child', nRow).html('' +
                            '<a href="#" class="btn btn-sm btn-primary btnSetToWarehouse " data-id="' +
                            aData['id'] + '" product_name="' + aData['product_name'] +
                            '" product_id_fk="' + aData['product_id_fk'] +
                            '"  ><i class="bx bx-plus font-size-16 align-middle"></i> เพิ่มการรับ </a> '
                        ).addClass('input');

                    } else {
                        $('td:last-child', nRow).html('');
                    }


                }
            });
            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                oTable.draw();
            });
        });
    </script>


    <script>
        var po_supplier_id_fk = "{{ @$sRow->id ? @$sRow->id : 0 }}";
        var oTable2;

        $(function() {
            oTable2 = $('#data-table-history').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 5,
                ajax: {
                    url: '{{ route('backend.po_supplier_products_receive.datatable') }}',
                    data: {
                        po_supplier_id_fk: po_supplier_id_fk,
                    },
                    method: 'POST',
                },
                columns: [{
                        data: 'id',
                        title: '<center>ID',
                        className: 'text-center w50'
                    },
                    {
                        data: 'action_date',
                        title: '<center>วันที่ได้รับสินค้า',
                        className: 'text-center'
                    },
                    {
                        data: 'product_name',
                        title: '<center>ชื่อสินค้า',
                        className: 'text-center'
                    },
                    {
                        data: 'amt_get',
                        title: '<center>จำนวนที่ได้รับ',
                        className: 'text-center'
                    },
                    {
                        data: 'product_unit_desc',
                        title: 'หน่วยนับ',
                        className: 'text-center'
                    },
                    {
                        data: 'warehouses',
                        title: 'สินค้าอยู่ที่',
                        className: 'text-center'
                    },
                    {
                        data: 'lot_detail',
                        title: 'Lot',
                        className: 'text-center'
                    },
                    {
                        data: 'remark',
                        title: 'หมายเหตุ',
                        className: 'text-center'
                    },
                    {
                        data: 'id',
                        title: '<center>Tools',
                        className: 'text-center w80'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {
                    // $('td:last-child', nRow).html(''
                    //   + '<a href="{{ route('backend.po_receive_products_get.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                    //   + '<a href="javascript: void(0);" data-url="{{ route('backend.po_receive_products_get.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    // ).addClass('input');

                    if (aData['approve_status'] == 0) {
                        var a = '<a href="{{ url('backend/po_receive_products_get_approve') }}/' +
                            aData['id'] +
                            '" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-success">อนุมัติ</a> &nbsp;';
                    } else {
                        var a = '';
                    }

                    $('td:last-child', nRow).html(''

                        +
                        a
                        // + '<a href="javascript: void(0);" data-url="{{ route('backend.po_receive_products_get.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                        +
                        '<a href="javascript: void(0);" data_url="{{ url('backend/destroy_po_supplier_products_receive') }}/' +
                        aData['id'] +
                        '" class="btn btn-sm btn-danger cDelete_new"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    ).addClass('input');

                }
            });

        });


        $(document).on('click', '.cDelete_new', function() {
            if (confirm("ยืนยันการลบ?") == true) {
                $('.myloading').show();
                var data_url = $(this).attr('data_url');
                $.ajax({
                    url: data_url,
                    method: "get",
                    // data: {
                    //     business_location_id_fk: business_location_id_fk,
                    //     "_token": "{{ csrf_token() }}",
                    // },
                    success: function(data) {
                        $('.myloading').hide();
                        location.reload();
                    }
                })
            } else {

            }
        });
    </script>


    <script type="text/javascript">
        $('#business_location_id_fk').change(function() {

            $('.myloading').show();

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
                        }
                        $('.myloading').hide();
                    }
                })
            }

        });


        $(document).on('click', '.btnSetToWarehouse', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            var product_name = $(this).attr('product_name');
            var product_id_fk = $(this).attr('product_id_fk');
            // console.log(product_id_fk);
            // console.log(product_name);
            // var branch_id_fk = $("#branch_id_fk").val();
            $('#product_name').val(product_name);
            $('#product_id_fk').val(product_id_fk);
            $('#po_supplier_products_id_fk').val(id);

            setTimeout(function() {
                $('#amt_get').focus();
            }, 500);

            $('#setToWarehouseModal').modal('show');

        });



        $('#branch_id_fk_c').change(function() {

            var branch_id_fk = this.value;
            // alert(warehouse_id_fk);
            if (branch_id_fk != '') {
                $('.myloading').show();
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
                            $('.myloading').hide();
                        } else {
                            var layout = '<option value="" selected>- เลือกคลัง -</option>';
                            $.each(data, function(key, value) {
                                layout += '<option value=' + value.id + '>' + value.w_name +
                                    '</option>';
                            });
                            $('#warehouse_id_fk_c').html(layout);
                            $('#zone_id_fk_c').html(
                                '<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                            $('#shelf_id_fk_c').html(
                                '<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                            $('.myloading').hide();
                        }
                    }
                })
            }

        });


        $('#warehouse_id_fk_c').change(function() {
            $('.myloading').show();
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
                            $('#zone_id_fk_c').html(layout);
                            $('#shelf_id_fk_c').html('กรุณาเลือกโซนก่อน');
                            $('.myloading').hide();
                        }
                    }
                })
            }

        });


        $('#zone_id_fk_c').change(function() {
            $('.myloading').show();
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
                            $('#shelf_id_fk_c').html(layout);
                            $('.myloading').hide();
                        }
                    }
                })
            }

        });



        $('#shelf_id_fk_c').change(function() {
            setTimeout(function() {
                $('#shelf_floor_c').focus();
            })
        });
    </script>

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(function() {

            // Single Select
            $("#lot_number_auto").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    var product_id_fk = $('#product_id_fk').val();
                    $.ajax({
                        url: " {{ url('backend/ajaxGetLotnumber2') }} ",
                        method: "post",
                        dataType: "json",
                        data: {
                            product_id_fk: product_id_fk,
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            // console.log(data);
                            response(data);
                        }
                    });
                },
                // select: function (event, ui) {
                //    console.log(ui.item);
                //    $('#lot_number_auto').val(ui.item.value);
                //    return false;
                // },
                // focus: function(event, ui){
                //    $( "#lot_number_auto" ).val( ui.item.value );
                //    return false;
                //  },
            });


            $(document).on('change', '#lot_number_auto', function(event) {
                var this_v = $(this).val();
                // alert(this_v);
                var product_id_fk = $('#product_id_fk').val();
                $.ajax({
                    url: " {{ url('backend/ajaxGetLotnumber2') }} ",
                    method: "post",
                    dataType: "json",
                    data: {
                        product_id_fk: product_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        // console.log(data);
                        $.each(data, function(index, value) {
                            if (this_v == value.value) {
                                $('#lot_expired_date').val(value.lot_expired_date);
                                $('#lot_expired_date').prop('readonly', true);
                                $('#lot_expired_date').prop('type', 'text');
                            } else {
                                $('#lot_expired_date').val('');
                                $('#lot_expired_date').prop('readonly', false);
                                $('#lot_expired_date').prop('required', true);
                                $('#lot_expired_date').prop('type', 'date');
                            }
                        });
                    }
                });
            });



        });



        $(document).ready(function() {

            // amt_get เช็คว่ากรอกเกินจำนวนที่สั่งซื้อหรือไม่ โดยหักออกจากครั้งก่อนๆ ที่ได้รับก่อนหน้าแล้วด้วยนะ
            $(document).on('change', '#amt_get', function(event) {

                $(".myloading").show();

                var amt_get = $(this).val();
                var po_supplier_products_id_fk = $('#po_supplier_products_id_fk').val();
                var product_id_fk = $('#product_id_fk').val();
                // console.log(amt_get);
                // console.log(po_supplier_products_id_fk);
                // console.log(product_id_fk);
                // return false;
                $.ajax({
                    url: " {{ url('backend/ajaxCheckAmt_get_po_product') }} ",
                    method: "post",
                    data: {
                        amt_get: amt_get,
                        po_supplier_products_id_fk: po_supplier_products_id_fk,
                        product_id_fk: product_id_fk,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 1) {
                            alert("! กรอกข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง");
                            $('#amt_get').val('');
                            $('#amt_get').focus();
                            $(".myloading").hide();
                            return false;
                        } else {
                            $(".myloading").hide();
                        }
                    }

                });

            });


            $(document).on('click', '.cDelete', function(event) {

                setTimeout(function() {
                    $('#data-table').DataTable().draw();
                }, 100);
                setTimeout(function() {
                    $('#data-table-history').DataTable().draw();
                }, 100);


            });


        });
    </script>
@endsection
