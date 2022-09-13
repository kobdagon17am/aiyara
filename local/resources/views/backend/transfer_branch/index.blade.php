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
    </style>
@endsection

@section('content')
    <div class="myloading"></div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18  "> โอนระหว่างสาขา </h4>
                <!-- test_clear_data_transfer_branch -->

                <?php if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)==0){ ?>
                <button type="button" class="btn btn-primary btn-sm btnAddTransferItem " style="font-size: 14px !important;">
                    + เพิ่มรายการโอนสินค้า
                </button>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" style="">
            <div id="spinner_frame"
                style="display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            z-index: 9999;
            ">
                <p align="center">
                    <img src="{{ asset('backend/images/preloader_big.gif') }}">
                </p>
            </div>
        </div>
    </div>


    <!-- end page title -->

    <!-- แสดงใบเบิกระหว่างสาขา -->
    @include('backend.transfer_branch.partials.requisition')
    <!-- จบแสดงใบเบิกระหว่างสาขา -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">


                                    <?php if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)!=0){ ?>
                                    <div class="myBorder div_TransferList  " style="display: none;">
                                        <?php }else{ ?>
                                        <div class="myBorder div_TransferList ">
                                            <?php } ?>

                                            <div class="row div_TransferList ">

                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label for="business_location_id_fk"
                                                            class="col-md-3 col-form-label">Business Location</label>
                                                        <div class="col-md-9">
                                                            <select id="business_location_id_fk"
                                                                name="business_location_id_fk"
                                                                class="form-control select2-templating " required="">
                                                                <option value="">-Business Location-</option>
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
                                                        <label for="branch_id_fk" class="col-md-3 col-form-label">
                                                            สาขาที่ดำเนินการ : </label>
                                                        <div class="col-md-9">

                                                            <select id="from_branch_id_fk" name="from_branch_id_fk"
                                                                class="form-control select2-templating ">
                                                                <option disabled selected value="">กรุณาเลือก Business
                                                                    Location ก่อน</option>
                                                                @if (@$sBranchs)
                                                                    @foreach (@$sBranchs as $r)
                                                                        @if ($sPermission == 1)
                                                                            @if ($r->business_location_id_fk == \Auth::user()->business_location_id_fk)
                                                                                <option value="{{ @$r->id }}"
                                                                                    {{ @$r->id == \Auth::user()->branch_id_fk ? 'selected' : '' }}>
                                                                                    {{ $r->b_name }}</option>
                                                                            @endif
                                                                        @else
                                                                            @if ($r->business_location_id_fk == \Auth::user()->business_location_id_fk)
                                                                                <option value="{{ @$r->id }}"
                                                                                    {{ @$r->id == \Auth::user()->branch_id_fk ? 'selected' : '' }}>
                                                                                    {{ $r->b_name }}</option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row div_TransferList ">
                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label for="to_branch" class="col-md-3 col-form-label"> โอนไปให้สาขา
                                                            : </label>
                                                        <div class="col-md-9">
                                                            <select name="to_branch" id="to_branch"
                                                                class="form-control select2-templating " required>
                                                                <option value="">-Select-</option>
                                                                @if (@$toBranchs)
                                                                    @foreach (@$toBranchs as $r)
                                                                        <option value="{{ $r->id }}">
                                                                            {{ $r->b_name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label for="tr_status_from" class="col-md-3 col-form-label"> สถานะ
                                                            (ฝั่งส่ง) : </label>
                                                        <div class="col-md-9">
                                                            <select id="tr_status_from" name="tr_status_from"
                                                                class="form-control select2-templating ">
                                                                <option value="">-Select-</option>
                                                                @if (@$Transfer_branch_status)
                                                                    @foreach (@$Transfer_branch_status as $r)
                                                                        <option value="{{ $r->id }}">
                                                                            {{ $r->txt_from }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row div_TransferList ">
                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label for="startDate" class="col-md-3 col-form-label">
                                                            ช่วงวันที่สร้างใบโอน : </label>
                                                        <div class="col-md-9 d-flex">
                                                            <input id="startDate" autocomplete="off"
                                                                placeholder="Begin Date"
                                                                style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                                                            <input id="endDate" autocomplete="off" placeholder="End Date"
                                                                style="border: 1px solid grey;font-weight: bold;color: black" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label for="tr_number" class="col-md-3 col-form-label"> รหัสใบโอน :
                                                        </label>
                                                        <div class="col-md-9 ">
                                                            <select id="tr_number" name="tr_number"
                                                                class="form-control select2-templating ">
                                                                <option value="">-Select-</option>
                                                                @if (@$tr_number)
                                                                    @foreach (@$tr_number as $r)
                                                                        <option value="{{ $r->tr_number }}">
                                                                            {{ $r->tr_number }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row div_TransferList " style="margin-bottom: 2% !important;">
                                                <div class="col-md-6 " style="margin-top: -1% !important;">
                                                    <div class="form-group row">
                                                        <label for="action_user" class="col-md-3 col-form-label">
                                                            พนักงานที่ทำการโอน : </label>
                                                        <div class="col-md-9">
                                                            <select id="action_user" name="action_user"
                                                                class="form-control select2-templating ">
                                                                <option value="">-Select-</option>
                                                                @if (@$sAction_user)
                                                                    @foreach (@$sAction_user as $r)
                                                                        <option value="{{ $r->id }}">
                                                                            {{ $r->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 " style="margin-top: -0.5% !important;">
                                                    <div class="form-group row">
                                                        <label for="branch_id_fk" class="col-md-3 col-form-label">
                                                        </label>
                                                        <div class="col-md-9">
                                                            <a class="btn btn-info btn-sm btnSearch01 " href="#"
                                                                style="font-size: 14px !important;margin-left: 0.8%;">
                                                                <i class="bx bx-search align-middle "></i> SEARCH
                                                            </a>


                                                            <!--     <a class="btn btn-info btn-sm float-right " style="{{ @$sC }}" href="{{ route('backend.transfer_branch_get.create') }}">
                          <i class="bx bx-plus font-size-20 align-middle"></i>ADD สร้างใบรับสินค้าจาก PO
                        </a> -->


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- display: none; -->
                                        <?php if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)==0){ ?>
                                        <div class="myBorder divAddTransferItem " style="display: none;">
                                            <?php }else{ ?>
                                            <div class="myBorder divAddTransferItem ">
                                                <?php } ?>

                                                <form id="frm_save_to_transfer_list"
                                                    action="{{ route('backend.transfer_branch_code.store') }}"
                                                    method="POST" enctype="multipart/form-data" autocomplete="off">
                                                    {{ csrf_field() }}

                                                    <div class="row">
                                                        <div class="col-8">

                                                            <div class="form-group row">
                                                                <label for="note" class="col-md-3 col-form-label"><i
                                                                        class="bx bx-play"></i>สาขาต้นทาง :</label>
                                                                <div class="col-md-9">

                                                                    <input type="hidden" name="business_location_id_fk"
                                                                        value="{{ @$business_location_id_fk }}">

                                                                    @if ($User_branch_id == 0)
                                                                        <select id="branch_id_fk" name="branch_id_fk"
                                                                            class="form-control select2-templating "
                                                                            required="">
                                                                            <option value="">Select</option>
                                                                            option
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


                                                                    @if ($User_branch_id != 0)
                                                                        <select id="branch_id_fk" name="branch_id_fk"
                                                                            class="form-control select2-templating "
                                                                            required="">
                                                                            @if (@$sBranchs)
                                                                                @foreach (@$sBranchs as $r)
                                                                                    @if (@$r->id == $User_branch_id)
                                                                                        <option
                                                                                            value="{{ $r->id }}"
                                                                                            {{ @$r->id == @$sRow->branch_id_fk ? 'selected' : '' }}>
                                                                                            {{ $r->b_name }}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    @endif

                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-md-3 col-form-label"><i
                                                                        class="bx bx-play"></i>สาขาปลายทาง :</label>
                                                                <div class="col-md-9">
                                                                    <select id="branch_id_fk_to" name="branch_id_fk_to"
                                                                        class="form-control select2-templating ">
                                                                        <option value="">-Select-</option>
                                                                        @if (@$toBranchs)
                                                                            @foreach (@$toBranchs as $r)
                                                                                <option value="{{ $r->id }}">
                                                                                    {{ $r->b_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <div class="form-group row"
                                                                style="margin-bottom: 1% !important;">
                                                                <label for="note" class="col-md-3 col-form-label"><i
                                                                        class="bx bx-play"></i>หมายเหตุ (ถ้ามี) :</label>
                                                                <div class="col-md-9">
                                                                    <textarea class="form-control" rows="3" id="note" name="note"></textarea>
                                                                </div>
                                                            </div>


                                                            <div class="form-group row">
                                                                <label for="example-text-input"
                                                                    class="col-md-3 col-form-label"><i
                                                                        class="bx bx-play"></i>รหัสสินค้า : ชื่อสินค้า
                                                                </label>
                                                                <div class="col-md-7">
                                                                    <select name="product" id="product"
                                                                        class="form-control select2-templating ">
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

                                                                <div class="col-2">
                                                                    <a class="btn btn-info btn-sm btnSearch "
                                                                        href="{{ route('backend.transfer_branch.index') }}"
                                                                        style="font-size: 14px !important;">
                                                                        <i class="bx bx-search align-middle "></i> SEARCH
                                                                    </a>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>


                                                    <table id="data-table-to-transfer" class="table table-bordered "
                                                        style="width: 100%;">
                                                    </table>

                                                    <?php //echo count($sTransfer_choose)."xxxxxxxxxxxxxxxxxxxxxxxxxxx"
                                                    ?>
                                                    <?php //if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)!=0 && count($sTransfer_choose)==0){
                                                    ?>
                                                    <?php if(count($sTransfer_choose)!=0){ ?>
                                                    <div class="row" style="">
                                                        <div class="col-md-12 text-center divBtnSave ">
                                                            <button type="submit"
                                                                class="btn btn-primary btn-sm waves-effect btnSave "
                                                                style="font-size: 14px !important;">
                                                                <i class="bx bx-save font-size-16 align-middle mr-1"></i>
                                                                บันทึก สร้างใบโอน
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                    <input type="hidden" name="save_set_to_warehouse_c_e"
                                                        value="1">

                                                </form>

                                            </div>


                                            <div class="myBorder div_TransferList ">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div
                                                            class="page-title-box d-flex align-items-center justify-content-between">
                                                            <h4 class="mb-0 font-size-18"><i class="bx bx-play"></i>
                                                                รายการใบโอน </h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <table id="data-table-transfer-list" class="table table-bordered "
                                                    style="width: 100%;">
                                                </table>
                                            </div>




                                        </div>
                                    </div>



                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->


                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle"><b><i
                                                    class="bx bx-play"></i>รายการสินค้า</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form action="{{ route('backend.transfer_branch.store') }}" method="POST"
                                        enctype="multipart/form-data" autocomplete="off">
                                        <input type="hidden" name="save_select_to_transfer" value="1">
                                        <input type="hidden" id="branch_id_select_to_transfer"
                                            name="branch_id_select_to_transfer">
                                        <input type="hidden" id="branch_id_select_to_transfer_to"
                                            name="branch_id_select_to_transfer_to">
                                        {{ csrf_field() }}

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table id="data-table-choose" class="table table-bordered "
                                                                style="width: 100%;">
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-center  ">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width: 10%;">Save</button>
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


                        <div class="modal fade" id="setToWarehouseModal" tabindex="-1" role="dialog"
                            aria-labelledby="setToWarehouseModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg " role="document"
                                style="max-width: 1000px !important;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="setToWarehouseModalTitle"><b><i
                                                    class="bx bx-play"></i>เลือกสาขาปลายทาง</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form action="{{ route('backend.transfer_branch.store') }}" method="POST"
                                        enctype="multipart/form-data" autocomplete="off">
                                        <input type="hidden" name="save_set_to_warehouse" value="1">
                                        {{ csrf_field() }}

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-body">

                                                            <div class="row">
                                                                <div class="col-md-6 ">
                                                                    <div class="form-group row">
                                                                        <label for="branch_id_fk_c"
                                                                            class="col-md-3 col-form-label"> สาขาต้นทาง :
                                                                        </label>
                                                                        <div class="col-md-9">
                                                                            <select id="branch_id_fk_c"
                                                                                name="branch_id_fk_c"
                                                                                class="form-control select2-templating ">
                                                                                <option value="">Select</option>
                                                                                @if (@$sBranchs)
                                                                                    @foreach (@$sBranchs as $r)
                                                                                        @if ($r->id == $User_branch_id)
                                                                                            <option
                                                                                                value="{{ $r->id }}"
                                                                                                {{ @$r->id == @$sRow->branch_id_fk ? 'selected' : '' }}>
                                                                                                {{ $r->b_name }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 ">
                                                                    <div class="form-group row">
                                                                        <label for="branch_id_fk_c_to"
                                                                            class="col-md-3 col-form-label"> สาขาปลายทาง :
                                                                        </label>
                                                                        <div class="col-md-9">
                                                                            <select id="branch_id_fk_c_to"
                                                                                name="branch_id_fk_c_to"
                                                                                class="form-control select2-templating "
                                                                                required="">
                                                                                <option value="">Select</option>
                                                                                @if (@$sBranchs)
                                                                                    @foreach (@$sBranchs as $r)
                                                                                        @if ($r->id != $User_branch_id)
                                                                                            <option
                                                                                                value="{{ $r->id }}">
                                                                                                {{ $r->b_name }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
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
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width: 10%;">Save</button>
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



                        <div class="modal fade" id="setToWarehouseModalEdit" tabindex="-1" role="dialog"
                            aria-labelledby="setToWarehouseModalEditTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg " role="document"
                                style="max-width: 1000px !important;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="setToWarehouseModalEditTitle"><b><i
                                                    class="bx bx-play"></i>เลือกสาขาปลายทาง</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form action="{{ route('backend.transfer_branch.store') }}" method="POST"
                                        enctype="multipart/form-data" autocomplete="off">
                                        <input type="hidden" name="save_set_to_warehouse_e" value="1">
                                        {{ csrf_field() }}

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-body">

                                                            <div class="row">
                                                                <div class="col-md-6 ">
                                                                    <div class="form-group row">
                                                                        <label for="branch_id_fk_c_e"
                                                                            class="col-md-3 col-form-label"> สาขาต้นทาง :
                                                                        </label>
                                                                        <div class="col-md-9">
                                                                            <select id="branch_id_fk_c_e"
                                                                                name="branch_id_fk_c_e"
                                                                                class="form-control select2-templating ">
                                                                                <option value="">Select</option>
                                                                                @if (@$sBranchs)
                                                                                    @foreach (@$sBranchs as $r)
                                                                                        @if ($r->id == $User_branch_id)
                                                                                            <option
                                                                                                value="{{ $r->id }}"
                                                                                                {{ @$r->id == @$sRow->branch_id_fk ? 'selected' : '' }}>
                                                                                                {{ $r->b_name }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 ">
                                                                    <div class="form-group row">
                                                                        <label for="branch_id_fk_c_e_to"
                                                                            class="col-md-3 col-form-label"> สาขาปลายทาง :
                                                                        </label>
                                                                        <div class="col-md-9">
                                                                            <select id="branch_id_fk_c_e_to"
                                                                                name="branch_id_fk_c_e_to"
                                                                                class="form-control select2-templating "
                                                                                required="">
                                                                                <option value="">Select</option>
                                                                                @if (@$sBranchs)
                                                                                    @foreach (@$sBranchs as $r)
                                                                                        @if ($r->id != $User_branch_id)
                                                                                            <option
                                                                                                value="{{ $r->id }}">
                                                                                                {{ $r->b_name }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
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
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width: 10%;">Save</button>
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


                        <div class="modal fade" id="modalNote" tabindex="-1" role="dialog"
                            aria-labelledby="modalNoteTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered  " role="document"
                                style="max-width: 650px !important;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalNoteTitle"><b><i class="bx bx-play"></i>หมายเหตุ
                                                (สาเหตุที่ยกเลิก/หรืออื่นๆ)</b></h5>
                                    </div>

                                    <form action="{{ route('backend.transfer_branch.store') }}" method="POST"
                                        enctype="multipart/form-data" autocomplete="off">
                                        <input type="hidden" name="save_select_to_cancel" value="1">
                                        <input type="hidden" id="id_to_cancel" name="id_to_cancel">
                                        {{ csrf_field() }}

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <textarea class="form-control" rows="5" id="note_to_cancel" name="note_to_cancel" required=""></textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-center  ">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="width: 10%;margin-top: 5%;">Save</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                        style="margin-left: 1%;margin-top: 5%;">Close</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>


                    @endsection

                    @section('script')
                        <script type="text/javascript">
                            $('#branch_id_fk_c').change(function() {

                                var branch_id_fk = this.value;
                                // alert(warehouse_id_fk);

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
                                                $('#warehouse_id_fk_c').html(layout);
                                                $('#zone_id_fk_c').html(
                                                    '<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                                                $('#shelf_id_fk_c').html(
                                                    '<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                                            }
                                        }
                                    })
                                }

                            });


                            $('#warehouse_id_fk_c').change(function() {

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
                                            }
                                        }
                                    })
                                }

                            });


                            $('#zone_id_fk_c').change(function() {

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
                                            }
                                        }
                                    })
                                }

                            });



                            $('#branch_id_fk_c_e').change(function() {

                                var branch_id_fk = this.value;
                                // alert(warehouse_id_fk);

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
                                                $('#warehouse_id_fk_c_e').html(layout);
                                                $('#zone_id_fk_c_e').html(
                                                    '<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                                                $('#shelf_id_fk_c_e').html(
                                                    '<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                                            }
                                        }
                                    })
                                }

                            });


                            $('#warehouse_id_fk_c_e').change(function() {

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
                                                $('#zone_id_fk_c_e').html(layout);
                                                $('#shelf_id_fk_c_e').html('กรุณาเลือกโซนก่อน');
                                            }
                                        }
                                    })
                                }

                            });


                            $('#zone_id_fk_c_e').change(function() {

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
                                                $('#shelf_id_fk_c_e').html(layout);
                                            }
                                        }
                                    })
                                }

                            });


                            var oTable;
                            $(function() {
                                oTable = $('#data-table-transfer-list').DataTable({
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
                                        url: '{{ route('backend.transfer_branch_code.datatable') }}',
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
                                            data: 'tr_number',
                                            title: 'รหัสใบโอน',
                                            className: 'text-center w100'
                                        },
                                        {
                                            data: 'action_date',
                                            title: '<center>วันที่สร้างใบโอน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'to_branch_id',
                                            title: '<center>สาขาปลายทาง </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'action_user',
                                            title: '<center>พนักงาน<br>ที่ทำการโอน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approve_status',
                                            title: '<center>สถานะ<br>การอนุมัติ</center>',
                                            className: 'text-center w100 ',
                                            render: function(d) {
                                                if (d == 1) {
                                                    return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
                                                } else if (d == 2) {
                                                    return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:grey">ยกเลิก</span>';
                                                } else if (d == 3) {
                                                    return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:black">ไม่อนุมัติ</span>';
                                                } else {
                                                    return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">รออนุมัติ</span>';
                                                }
                                            }
                                        },
                                        {
                                            data: 'approver',
                                            title: '<center>ผู้อนุมัติ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'approve_date',
                                            title: '<center>วันอนุมัติ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'id',
                                            title: 'พิมพ์<br>ใบโอน',
                                            className: 'text-center ',
                                            render: function(d) {
                                                return '<center><a href="{{ URL::to('backend/transfer_branch/print_transfer') }}/' +
                                                    d +
                                                    '" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                                            }
                                        },
                                        {
                                            data: 'tr_status_from',
                                            title: '<center> ฝั่งส่ง </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'tr_status_to',
                                            title: '<center> ฝั่งรับ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'id',
                                            title: 'Tools',
                                            className: 'text-center w80'
                                        },
                                    ],
                                    rowCallback: function(nRow, aData, dataIndex) {

                                        if (aData['approve_status'] != 0) {

                                            $('td:last-child', nRow).html('' +
                                                '<a href="{{ route('backend.transfer_branch.index') }}/' + aData[
                                                'id'] +
                                                '/edit" class="btn btn-sm btn-primary" style="font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> ' +
                                                '<a href="javascript: void(0);"  class="btn btn-sm btn-secondary " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle" style="color:#bfbfbf;"></i></a>'
                                            ).addClass('input');

                                        } else {

                                            $('td:last-child', nRow).html('' +
                                                '<a href="{{ route('backend.transfer_branch.index') }}/' + aData[
                                                'id'] +
                                                '/edit" class="btn btn-sm btn-primary" style="font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> ' +
                                                '<a href="javascript: void(0);" data-id="' + aData['id'] +
                                                '" class="btn btn-sm btn-danger cCancel " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle"></i></a>'
                                            ).addClass('input');

                                        }

                                    }
                                });

                            });



                            $(document).ready(function() {


                                $(document).on('click', '.btnSearch', function(event) {
                                    event.preventDefault();

                                    var branch_id_fk = $("#branch_id_fk").val();
                                    var branch_id_fk_to = $("#branch_id_fk_to").val();
                                    var product_id = $("#product").val();
                                    var product_id = product_id ? product_id : 0;

                                    $("#branch_id_select_to_transfer").val(branch_id_fk);
                                    $("#branch_id_select_to_transfer_to").val(branch_id_fk_to);

                                    if (branch_id_fk == '') {
                                        $("#branch_id_fk").select2('open');
                                        return false;
                                    } else if (branch_id_fk_to == '') {
                                        $("#branch_id_fk_to").select2('open');
                                        return false;
                                    } else if (product_id == '') {
                                        $("#product").select2('open');
                                        return false;
                                    } else {

                                        $("#spinner_frame").show();

                                        var oTable;
                                        $(function() {

                                            oTable = $('#data-table-choose').DataTable({
                                                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                                processing: true,
                                                serverSide: true,
                                                ordering: false,
                                                "info": false,
                                                destroy: true,
                                                searching: false,
                                                // paging: false,
                                                ajax: {
                                                    url: '{{ route('backend.check_stock_transfer_branch.datatable') }}',
                                                    data: {
                                                        _token: '{{ csrf_token() }}',
                                                        branch_id_fk: branch_id_fk,
                                                        product_id_fk: product_id,
                                                    },
                                                    method: 'POST',
                                                },
                                                columns: [{
                                                        data: 'id',
                                                        title: 'No.',
                                                        className: 'text-center w50'
                                                    },
                                                    {
                                                        data: 'product_name',
                                                        title: '<center>รหัสสินค้า : ชื่อสินค้า </center>',
                                                        className: 'text-left'
                                                    },
                                                    {
                                                        data: 'lot_number',
                                                        title: '<center>ล็อตนัมเบอร์ </center>',
                                                        className: 'text-left'
                                                    },
                                                    {
                                                        data: 'lot_expired_date',
                                                        title: '<center>วันหมดอายุ </center>',
                                                        className: 'text-center'
                                                    },
                                                    {
                                                        data: 'warehouses',
                                                        title: '<center>คลังสินค้า </center>',
                                                        className: 'text-left'
                                                    },
                                                    {
                                                        data: 'amt',
                                                        title: '<center>จำนวนที่มีในคลัง</center>',
                                                        className: 'text-center',
                                                        render: function(d) {
                                                            return '<center>' + (d) +
                                                                '<input type="hidden" class="amt_in_warehouse" value="' +
                                                                (d) + '" > ';
                                                        }
                                                    },
                                                    {
                                                        data: 'id',
                                                        title: '<center>จำนวนโอน</center>',
                                                        className: 'text-center',
                                                        render: function(d) {
                                                            return '<center><input class="form-control amt_to_transfer in-tx  " type="number"  name="amt_transfer[]" style="background-color:#e6ffff;border: 2px inset #EBE9ED;width:60%;text-align:center;" ><input type="hidden" name="id[]" value="' +
                                                                (d) + '" >';
                                                        }
                                                    },

                                                ],
                                                rowCallback: function(nRow, aData, dataIndex) {

                                                    var info = $(this).DataTable().page.info();
                                                    $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                                }

                                            });

                                        });

                                        $('#exampleModalCenter').modal('show');
                                    }

                                });
                            });



                            $('#exampleModalCenter').on('focus', function() {
                                $("#spinner_frame").hide();
                            });


                            var oTable;
                            $(function() {
                                oTable = $('#data-table-to-transfer').DataTable({
                                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    ordering: false,
                                    "info": false,
                                    destroy: true,
                                    searching: false,
                                    iDisplayLength: 500,
                                    // paging: false,
                                    ajax: {
                                        url: '{{ route('backend.transfer_choose_branch.datatable') }}',
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
                                            title: 'No.',
                                            className: 'text-center w50'
                                        },
                                        {
                                            data: 'product_name',
                                            title: '<center>รหัสสินค้า : ชื่อสินค้า </center>',
                                            className: 'text-left'
                                        },
                                        {
                                            data: 'lot_number',
                                            title: '<center>ล็อตนัมเบอร์ </center>',
                                            className: 'text-left'
                                        },
                                        {
                                            data: 'lot_expired_date',
                                            title: '<center>วันหมดอายุ </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'amt_in_warehouse',
                                            title: '<center>จำนวนที่มีในคลัง </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'amt',
                                            title: '<center>จำนวนที่ต้องการโอน </center>',
                                            className: 'text-center'
                                        },
                                        {
                                            data: 'branch_to',
                                            title: '<center>โอนย้ายไปที่สาขา</center>',
                                            className: 'text-center',
                                            render: function(d) {
                                                if (d != '0') {
                                                    return d;
                                                } else {
                                                    return "<span style='color:red;'>* รอเลือกสาขาปลายทาง </span>";
                                                }
                                            }
                                        },
                                        {
                                            data: 'id',
                                            title: 'Tools',
                                            className: 'text-center w150'
                                        },
                                    ],
                                    rowCallback: function(nRow, aData, dataIndex) {

                                        var info = $(this).DataTable().page.info();
                                        $("td:eq(0)", nRow).html(info.start + dataIndex + 1);


                                        $('td:last-child', nRow).html('' +
                                            '<input type="hidden" name="transfer_choose_id[]" value="' + aData['id'] +
                                            '"> '
                                            // + '<a href="javascript: void(0);" data-url="{{ route('backend.transfer_choose_branch.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                            +
                                            '<a href="{{ url('backend/transfer_choose_branch_delete/') }}/' + aData[
                                                'id'] +
                                            '" class="btn btn-sm btn-danger " onclick="return confirm(\'ยืนยันการทำรายการ\');"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                        ).addClass('input');
                                    }


                                });

                            });


                            $(document).ready(function() {
                                $(document).on('change', '.amt_to_transfer', function(event) {
                                    event.preventDefault();
                                    var amt_to_transfer = $(this).val();
                                    var amt_in_warehouse = $(this).closest("tr").find(".amt_in_warehouse").val();
                                    // alert(amt_to_transfer+":"+amt_in_warehouse);
                                    var a = parseInt(amt_to_transfer);
                                    var b = parseInt(amt_in_warehouse);
                                    if (a > b) {
                                        alert("!!! จำนวนไม่ถูกต้อง จำนวนที่โอนควรมีค่าน้อยกว่าหรือเท่ากับจำนวนที่มีในคลัง");
                                        $(this).val("");
                                        return false;
                                    }
                                });
                            });

                            $(document).ready(function() {
                                $(document).on('change', '#branch_id_fk_to', function(event) {
                                    event.preventDefault();
                                    var v = $(this).val();
                                    $("#branch_id_select_to_transfer_to").val(v);

                                });
                            });
                        </script>



                        <script>
                            $(document).ready(function() {

                                $(document).on('click', '.btnSearch01', function(event) {

                                    event.preventDefault();
                                    $(".myloading").show();

                                    var business_location_id_fk = $('#business_location_id_fk').val();
                                    var branch_id_fk = $('#branch_id_fk').val();
                                    var to_branch = $('#to_branch').val();
                                    var tr_status_from = $('#tr_status_from').val();

                                    var tr_number = $('#tr_number').val();
                                    var startDate = $('#startDate').val();
                                    var endDate = $('#endDate').val();
                                    var action_user = $('#action_user').val();
                                    var tr_status_from = $('#tr_status_from').val();
                                    // console.log(tr_status);
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
                                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                                    var oTable;
                                    $(function() {
                                        oTable = $('#data-table-transfer-list').DataTable({
                                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                            processing: true,
                                            serverSide: true,
                                            scroller: true,
                                            scrollCollapse: true,
                                            scrollX: true,
                                            ordering: false,
                                            destroy: true,
                                            // scrollY: ''+($(window).height()-370)+'px',
                                            iDisplayLength: 25,

                                            ajax: {
                                                url: '{{ route('backend.transfer_branch_code.datatable') }}',
                                                data: {
                                                    _token: '{{ csrf_token() }}',
                                                    business_location_id_fk: business_location_id_fk,
                                                    branch_id_fk: branch_id_fk,
                                                    to_branch: to_branch,
                                                    tr_status_from: tr_status_from,
                                                    startDate: startDate,
                                                    endDate: endDate,
                                                    tr_number: tr_number,
                                                    action_user: action_user,
                                                },
                                                method: 'POST',
                                            },

                                            columns: [{
                                                    data: 'tr_number',
                                                    title: 'รหัสใบโอน',
                                                    className: 'text-center w80'
                                                },
                                                {
                                                    data: 'action_date',
                                                    title: '<center>วันที่สร้างใบโอน </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'to_branch_id',
                                                    title: '<center>สาขาปลายทาง </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'action_user',
                                                    title: '<center>พนักงาน<br>ที่ทำการโอน </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'approve_status',
                                                    title: '<center>สถานะ<br>การอนุมัติ</center>',
                                                    className: 'text-center w100 ',
                                                    render: function(d) {
                                                        if (d == 1) {
                                                            return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
                                                        } else if (d == 2) {
                                                            return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:grey">ยกเลิก</span>';
                                                        } else if (d == 3) {
                                                            return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:black">ไม่อนุมัติ</span>';
                                                        } else {
                                                            return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">รออนุมัติ</span>';
                                                        }
                                                    }
                                                },
                                                {
                                                    data: 'approver',
                                                    title: '<center>ผู้อนุมัติ </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'approve_date',
                                                    title: '<center>วันอนุมัติ </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'id',
                                                    title: 'พิมพ์<br>ใบโอน',
                                                    className: 'text-center ',
                                                    render: function(d) {
                                                        return '<center><a href="{{ URL::to('backend/transfer_branch/print_transfer') }}/' +
                                                            d +
                                                            '" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                                                    }
                                                },
                                                {
                                                    data: 'tr_status_from',
                                                    title: '<center> ฝั่งส่ง </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'tr_status_to',
                                                    title: '<center> ฝั่งรับ </center>',
                                                    className: 'text-center'
                                                },
                                                {
                                                    data: 'id',
                                                    title: 'Tools',
                                                    className: 'text-center w80'
                                                },
                                            ],
                                            rowCallback: function(nRow, aData, dataIndex) {

                                                if (aData['approve_status'] != 0) {

                                                    $('td:last-child', nRow).html('' +
                                                        '<a href="{{ route('backend.transfer_branch.index') }}/' +
                                                        aData['id'] +
                                                        '/edit" class="btn btn-sm btn-primary" style="font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> ' +
                                                        '<a href="javascript: void(0);"  class="btn btn-sm btn-secondary " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle" style="color:#bfbfbf;"></i></a>'
                                                    ).addClass('input');

                                                } else {

                                                    $('td:last-child', nRow).html('' +
                                                        '<a href="{{ route('backend.transfer_branch.index') }}/' +
                                                        aData['id'] +
                                                        '/edit" class="btn btn-sm btn-primary" style="font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> ' +
                                                        '<a href="javascript: void(0);" data-id="' +
                                                        aData['id'] +
                                                        '" class="btn btn-sm btn-danger cCancel " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle"></i></a>'
                                                    ).addClass('input');

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


                        <script type="text/javascript">
                            $(document).ready(function() {

                                // $("#exampleModalCenter").modal({ show : true });


                                $(window).keydown(function(event) {
                                    if (event.keyCode == 13) {
                                        event.preventDefault();
                                        return false;
                                    }
                                });


                                $('#note').change(function() {
                                    localStorage.setItem('note', this.value);
                                });
                                if (localStorage.getItem('note')) {
                                    $('#note').val(localStorage.getItem('note'));
                                }

                                $(document).on('click', '.btnSave', function(event) {
                                    event.preventDefault();
                                    var branch_id_fk = $("#branch_id_fk").val();
                                    var branch_id_fk_to = $("#branch_id_fk_to").val();
                                    if (branch_id_fk == "") {
                                        $("#branch_id_fk").select2('open');
                                        return false;
                                    }
                                    if (branch_id_fk_to == "") {
                                        $("#branch_id_fk_to").select2('open');
                                        return false;
                                    }
                                    $("#spinner_frame").show();

                                    setTimeout(function() {
                                        // localStorage.clear();
                                        // $("#spinner_frame").hide();
                                        // location.reload();
                                        $("#frm_save_to_transfer_list").submit();
                                    }, 2500);


                                });

                                $('#branch_id_fk_to').change(function(event) {
                                    var branch_id_fk_to = $(this).val();
                                    var branch_id_fk = $("#branch_id_fk").val();
                                    if (branch_id_fk_to == branch_id_fk) {
                                        alert(
                                            "!!! กรุณาตรวจสอบข้อมูลอีกครั้ง สาขาปลายทาง ไม่ควร เป็นสาขาเดียวกันกับ สาขาต้นทาง ");
                                        $("#branch_id_fk_to").val('').trigger('change');
                                        return false;
                                    }
                                });

                                $(document).on('change', '#branch_id_fk_to', function(event) {
                                    event.preventDefault();
                                    var id = $(this).val();
                                    localStorage.setItem('branch_id_fk_to', id);
                                });

                                if (localStorage.getItem('branch_id_fk_to')) {
                                    $('#branch_id_fk_to').val(localStorage.getItem('branch_id_fk_to')).select2();
                                }

                                $(document).on('click', '.btnAddTransferItem', function(event) {
                                    // event.preventDefault();
                                    $("#spinner_frame").show();
                                    setTimeout(function() {
                                        // $(".divAddTransferItem").show();
                                        $(".divAddTransferItem").toggle();
                                        $(".div_TransferList").toggle();
                                        $("#spinner_frame").hide();
                                    }, 500);
                                    // $(".divAddTransferItem").toggle();
                                });


                                $(document).on('click', '.btnSetToWarehouse', function(event) {
                                    event.preventDefault();
                                    var id = $(this).data('id');
                                    var branch_id_fk = $("#branch_id_fk").val();
                                    $('#branch_id_set_to_warehouse').val(branch_id_fk);
                                    $('#branch_id_fk_c').val(branch_id_fk).select2();

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
                                                        layout += '<option value=' + value.id + '>' + value
                                                            .w_name + '</option>';
                                                    });
                                                    $('#warehouse_id_fk_c').html(layout);
                                                    $('#zone_id_fk_c').html(
                                                        '<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                                                    $('#shelf_id_fk_c').html(
                                                        '<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                                                }
                                            }
                                        })
                                    } else {
                                        $('#warehouse_id_fk_c').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                                        $('#zone_id_fk_c').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                                        $('#shelf_id_fk_c').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                                    }

                                    $("#id_set_to_warehouse").val(id);
                                    $('#branch_id_fk_c').attr("disabled", true);
                                    $('#setToWarehouseModal').modal('show');

                                });


                                $(document).on('click', '.btnEditToWarehouse', function(event) {
                                    event.preventDefault();
                                    var id = $(this).data('id');
                                    // alert(id);

                                    var branch_id_fk = $("#branch_id_fk").val();

                                    $("#id_set_to_warehouse_e").val(id);
                                    // $('#setToWarehouseModal').modal('show');

                                    if (id != '') {

                                        $.ajax({

                                            type: 'POST',
                                            url: " {{ url('backend/ajaxGetSetToWarehouseBranch') }} ",
                                            data: {
                                                _token: '{{ csrf_token() }}',
                                                id: id
                                            },
                                            success: function(data) {

                                                console.log(data);

                                                var obj = JSON.parse(data);
                                                $.each(obj, function(index, value) {

                                                    var branch_id_fk = value.branch_id_fk;
                                                    var branch_id_fk_to = value.branch_id_fk_to;

                                                    $('#branch_id_fk_c_e').val(branch_id_fk).select2();
                                                    $('#branch_id_fk_c_e_to').val(branch_id_fk_to)
                                                .select2();

                                                });

                                                $('#branch_id_fk_c_e').attr("disabled", true);

                                                $('#setToWarehouseModalEdit').modal('show');

                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                console.log(JSON.stringify(jqXHR));
                                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                            }
                                        });


                                    }


                                });

                                $(document).on('change', '#branch_id_fk', function(event) {
                                    event.preventDefault();
                                    var id = $(this).val();
                                    localStorage.setItem('branch_id_fk', id);
                                });

                                if (localStorage.getItem('branch_id_fk')) {
                                    $('#branch_id_fk').val(localStorage.getItem('branch_id_fk')).select2();
                                }


                                $(document).on('change', '#product', function(event) {
                                    event.preventDefault();
                                    var id = $(this).val();
                                    localStorage.setItem('product', id);
                                });

                                if (localStorage.getItem('product')) {
                                    $('#product').val(localStorage.getItem('product')).select2();
                                }


                                $(document).on('click', '.cDelete', function(event) {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);
                                });

                                $(document).on('click', '.cCancel', function(event) {
                                    event.preventDefault();
                                    var id = $(this).data('id');
                                    $('#id_to_cancel').val(id);
                                    $('#modalNote').modal('show');

                                });


                                $('#branch_id_search').change(function() {

                                    var branch_id_fk = this.value;
                                    // alert(warehouse_id_fk);

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
                                                    $("#warehouse_id_search").val('').trigger('change');
                                                    $('#warehouse_id_search').html(
                                                        '<option disabled selected >(คลัง) กรุณาเลือกสาขาก่อน</option>'
                                                        );
                                                } else {
                                                    var layout = '<option value="" selected>- เลือกคลัง -</option>';
                                                    $.each(data, function(key, value) {
                                                        layout += '<option value=' + value.id + '>' + value
                                                            .w_name + '</option>';
                                                    });
                                                    $('#warehouse_id_search').html(layout);
                                                }
                                            }
                                        })
                                    }

                                });



                            });
                        </script>

                        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
                            rel="stylesheet"
                            integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
                            crossorigin="anonymous">
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

                            });


                            $(document).on('click', '.test_clear_data_transfer_branch', function(event) {


                                if (!confirm("โปรดระวัง ยืนยัน ! เพื่อล้างข้อมูลรายการสั่งซื้อทั้งหมดเพื่อเริ่มต้นคีย์ใหม่ ? ")) {
                                    return false;
                                } else {

                                    location.replace(window.location.href +
                                        "?test_clear_data_transfer_branch=test_clear_data_transfer_branch ");
                                }


                            });
                        </script>

                        <?php
    if(isset($_REQUEST['test_clear_data_transfer_branch'])){

          DB::select("TRUNCATE `db_transfer_choose_branch`;");
          DB::select("TRUNCATE `db_transfer_branch_details`;");
          DB::select("TRUNCATE `db_transfer_branch_details_log`;");
          DB::select("TRUNCATE `db_transfer_branch_code`;");
          DB::select("TRUNCATE `db_transfer_branch_get`;");
          DB::select("TRUNCATE `db_transfer_branch_get_products`;");
          DB::select("TRUNCATE `db_transfer_branch_get_products_receive`;");

      ?>
                        <script>
                            location.replace("{{ url('backend/transfer_branch') }}");
                        </script>
                        <?php
      }
    ?>


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
                                                $('#from_branch_id_fk').html(layout);
                                            }
                                            $('.myloading').hide();
                                        }
                                    })
                                }

                            });
                        </script>

                        <!-- Scripts For Requisition -->
                        <script>
                            const dtRequisition = $('#dt-requisition').DataTable({
                                sDom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('backend.transfer_branch.requisition-datatable') }}",
                                    method: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    }
                                },
                                columns: [{
                                        data: 'id',
                                        title: 'No.',
                                        className: 'text-center w80'
                                    },
                                    {
                                        data: 'from_branch_id',
                                        title: 'จากสาขา',
                                        className: 'text-center w80'
                                    },
                                    {
                                        data: 'to_branch_id',
                                        title: 'ถึงสาขา',
                                        className: 'text-center w80'
                                    },
                                    {
                                        data: 'requisition_by',
                                        name: 'requisitioned_by',
                                        title: 'ผู้ยื่นใบเบิก',
                                        className: 'text-center w80'
                                    },
                                    {
                                        data: 'approve_by',
                                        name: 'approved_by',
                                        title: 'ผู้อนุมัติใบเบิก',
                                        className: 'text-center w80'
                                    },
                                    {
                                        data: 'approved_at',
                                        name: 'approved_at',
                                        title: 'วันที่อนุมัติใบเบิก',
                                        className: 'text-center w80'
                                    },
                                    {
                                        data: 'actions',
                                        title: 'Tools',
                                        className: 'text-center w80',
                                        orderable: false,
                                        searchable: false
                                    },
                                ]
                            })

                            $('.modal-requisition-details').on('show.bs.modal', function(event) {
                                var button = $(event.relatedTarget)
                                var details = button.data('details')
                                var fromBranchId = button.data('from_branch_id')
                                var toBranchId = button.data('to_branch_id')
                                var modal = $(this)

                                let output = ''

                                details.forEach(detail => {
                                    output += `
          <input type='hidden' name='branch_id_fk' value='${toBranchId}' />
          <input type='hidden' name='to_branch_id_fk' value='${fromBranchId}' />
          <tr>
            <td>${detail.product_name}</td>
            <td>
              <input type='hidden' name='lists[${detail.id}][product_id]' class=h_lists value='${detail.product_id}' />
              <input type='number' name='lists[${detail.id}][amount]' class='s_lists form-control product-amount-${detail.product_id}' value='${detail.amount}'>
            </td>
            <td>
              <select class='form-control product-stock-${detail.product_id} select-stock stock_lists' name='lists[${detail.id}][stock]' data-product_id='${detail.product_id}'>
              </select>
            </td>
            <td>
              <button type="button" class="btn btn-sm btn-primary add_list" detail_id="${detail.id}">+</button>
              <button type="button" class="btn btn-sm btn-danger remove_list">-</button>
              </td>
          </tr>
          `
                                    generateStocks(toBranchId, detail.product_id)
                                })

                                modal.find('.modal-body #tbodyDetails').html(output)
                                checkStockSelected()
                            })

                            var xNum = 1;
                            $(document).on('click', '.add_list', function() {
                                var tr = $(this).closest('tr');
                                var detail_id = $(this).attr('detail_id');
                                var c = tr.clone();
                                c.find('.h_lists').each(function() {
                                    this.name = this.name.replace('lists['+detail_id+']', 'lists['+(detail_id+xNum+1000)+']');
                                });
                                c.find('.s_lists').each(function() {
                                    this.name = this.name.replace('lists['+detail_id+']', 'lists['+(detail_id+xNum+1000)+']');
                                });
                                c.find('.stock_lists').each(function() {
                                    this.name = this.name.replace('lists['+detail_id+']', 'lists['+(detail_id+xNum+1000)+']');
                                });
                                c.find('.add_list').attr('detail_id', (detail_id+xNum+1000));
                                xNum++;
                                $(tr).after(c);
                            });

                            $(document).on('click', '.remove_list', function() {
                                var tr = $(this).closest('tr');
                                var l = $('.add_list').length;
                                if (l > 1) {
                                    tr.remove();
                                } else {
                                    alert('ไม่สามารถลบรายการสุดท้ายได้');
                                    return false;
                                }

                            });

                            function generateStocks(to_branch_id, product_id) {
                                $.ajax({
                                    url: "{{ route('backend.transfer_branch.check-stocks') }}",
                                    method: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        to_branch_id,
                                        product_id
                                    },
                                    success: function(response) {
                                        $(`.product-stock-${product_id}`).html(response)
                                    }
                                })
                            }

                            $(document).on('change', '.select-stock', function() {
                                const productId = $(this).data('product_id')
                                if ($(`.product-amount-${productId}`).val() > $(this).find(':selected').data('amt')) {
                                    alert('จำนวนที่โอนควรมีค่าน้อยกว่าหรือเท่ากับจำนวนที่มีในคลัง กรุณาเปลี่ยนจำนวนหรือเปลี่ยนคลัง!')
                                    $(this).val('')
                                }
                                checkStockSelected()
                            })

                            function checkStockSelected() {
                                let countChecked = 0;
                                $('.select-stock').each(function(index, el) {
                                    if ($(el).val()) {
                                        countChecked++
                                    }
                                    // วุฒิเพิ่มให้โอนอย่างเดียวได้
                                    // if (countChecked == $('.select-stock').length) {
                                    //   $('#requisitionSubmitBtn').attr('disabled', false)
                                    // } else {
                                    //   $('#requisitionSubmitBtn').attr('disabled', true)
                                    // }
                                    if (countChecked > 0) {
                                        $('#requisitionSubmitBtn').attr('disabled', false)
                                    } else {
                                        $('#requisitionSubmitBtn').attr('disabled', true)
                                    }
                                })
                            }
                        </script>
                    @endsection
