@extends('backend.layouts.master')

@section('title')
    Aiyara Planet
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/magnific-popup/magnific-popup.min.css') }}">
    <link href="{{ asset('asset/lity/lity.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> ใบสั่งซื้อรออนุมัติ </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->




    <div class="row">
        <div class="col-11">
            <div class="card">
                <div class="card-body">

                    <div class="myBorder">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                            </div>
                                        </div>

                                        <table id="data-table" class="table table-bordered " style="width: 100%;">
                                        </table>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>

                    <div class="myBorder">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                            </div>
                                        </div>
                                        <label>บิลที่ชำระพร้อมบิลนี้</label>
                                        <table id="data-table_other" class="table table-bordered " style="width: 100%;">
                                        </table>

                                        <br>

                                        <label>Ai-cash ที่ชำระพร้อมบิลนี้</label>
                                        <table id="data-table_other_ai" class="table table-bordered " style="width: 100%;">
                                        </table>

                                        <hr>

                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <br>
                                                <h4><u>ยอดรวมทั้งสิ้น</u></h4>
                                                <h4 style="color:red;"><b id="data-table_sum"> </b></h4>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>

                    <div class="myBorder">

                        <h5 class=" col-5 mb-0 "><i class="bx bx-play"></i> Slip File : </h5><br>

                        <div class="form-group row">


                            @if (@$slip)
                                @php
                                    @$i = 1;
                                @endphp
                                @foreach (@$slip as $r)
                                    <?php
                                    $status_str = '';
                                    if (@$r->status == 1) {
                                        $status_str = '<span style="color:;"><b>รออนุมัติ</b></span>';
                                    } elseif (@$r->status == 2) {
                                        $status_str = '<span style="color:green;"><b>อนุมัติแล้ว</b></span>';
                                    } elseif (@$r->status == 3) {
                                        $status_str = '<span style="color:red;"><b>ไม่อนุมัติ</b></span>';
                                    }
                                    ?>

                                    <label for="example-text-input" class="col-md-1 col-form-label">Slip {{ @$i }}
                                    </label>
                                    <div class="col-md-3">
                                        <h5 class="font-size-14  ">วันที่เวลาที่โอนในสลิป {!! $status_str !!} </h5>
                                        <input class="form-control  " type="text"
                                            value="{{ @$r->transfer_bill_date ? @$r->transfer_bill_date : null }}"
                                            readonly="">
                                        <br>

                                        @if (!empty(@$r->file))
                                            @if ($r->order_channel == 'MEMBER')
                                                <img src="{{ $r->url }}/{{ @$r->file }}" data-lity width="200px"
                                                    height="200px" class="grow">
                                            @else
                                                <img src="https://v3.aiyara.co.th/aiyarashop/{{ $r->url }}/{{ @$r->file }}"
                                                    data-lity width="200px" height="200px" class="grow">
                                            @endif
                                            <br> <br>
                                            <input type="text" class="form-control" name="note" placeholder=""
                                                readonly value="หมายเหตุ : {{ @$r->note }}">
                                            <br>
                                            <input type="text" class="form-control note2" data-id="{{ @$r->id }}"
                                                name="note2" placeholder="หมายเหตุ 2 สำหรับพนักงาน"
                                                value="{{ @$r->note2 }}">
                                        @ELSE
                                            <img data-lity src="{{ asset('local/public/images/example_img.png') }}"
                                                class="grow" width="200px">
                                        @ENDIF
                                        <br>
                                        @if (@$r->status == 1)
                                            <button type="button" data-id="{{ @$r->id }}"
                                                class="btn btn-success btn-sm font-size-10 btn_approve"
                                                style="vertical-align: bottom;margin-bottom: 5px;">อนุมัติ</button>
                                            <button type="button" data-id="{{ @$r->id }}"
                                                class="btn btn-warning btn-sm font-size-10 btn_not_approve"
                                                style="vertical-align: bottom;margin-bottom: 5px;">ไม่อนุมัติ</button>
                                            <button type="button" data-id="{{ @$r->id }}"
                                                class="btn btn-danger btn-sm font-size-10 btnDelSlip "
                                                style="vertical-align: bottom;margin-bottom: 5px;">ลบไฟล์</button>
                                        @endif
                                    </div>

                                    @php
                                        @$i++;
                                    @endphp
                                @endforeach
                            @endif




                        </div>

                        @if (count($slip) == 0)
                            <center>
                                <h5 class="" style="color:red;"> * ไม่พบไฟล์สลิป </h5>
                            </center>
                        @ENDIF

                        <br>
                        <br>
                        <br>

                        <div class="form-group mb-0 row">
                            <div class="col-md-6">
                                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url('backend/po_approve') }}">
                                    <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                </a>
                            </div>
                            <div class="col-md-6 text-right">

                                {{-- <button type="submit" name="approved" class="btn btn-primary btn-sm waves-effect font-size-16 " value='approved'>
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก
                            </button>
                            &nbsp;
                            &nbsp;
                            &nbsp; --}}

                                {{-- @if (@$sRow->transfer_bill_status == 2) --}}
                                <?php
                                //  dd($sRow->approve_status);
                                ?>
                                @if (@$sRow->approve_status == 1 ||
                                    @$sRow->approve_status == 2 ||
                                    @$sRow->approve_status == 0 ||
                                    @$sRow->approve_status == 6)
                                    <div class="div_confirm_transfer_slip">
                                        <button type="button" class="btn btn-primary waves-effect waves-light"
                                            data-toggle="modal" data-target="#confirm">อนุมัติ</button>

                                        <button type="button"
                                            class="btn btn-danger waves-effect waves-light btnNotAprrove"
                                            data-toggle="modal" data-target="#cancel"
                                            order_id="{{ @$sRow->id }}">ไม่อนุมัติ</button>
                                        {{-- <button type="button" class="btn btn-success btn-sm waves-effect font-size-16"
                            data-toggle="modal" data-target="#cancel">
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> อัพโหลดสลิปใหม่
                        </button> --}}

                                    </div>
                                @ELSE
                                    {{-- <div class="div_confirm_transfer_slip">
                            <button type="button" class="btn btn-primary waves-effect waves-light"
                            data-toggle="modal" data-target="#confirm">อนุมัติ</button>

                        <button type="button" class="btn btn-success btn-sm waves-effect font-size-16"
                            data-toggle="modal" data-target="#cancel">
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> อัพโหลดสลิปใหม่
                        </button>

                    </div> --}}
                                @endif

                                <form action="{{ route('backend.po_approve.update', @$sRow->id) }}" method="POST"
                                    enctype="multipart/form-data" autocomplete="off" id="form_approve_con">
                                    <input name="_method" type="hidden" value="PUT">
                                    <input name="id" value="{{ $sRow->id }}" type="hidden">

                                    {{ csrf_field() }}

                                    <div class="modal fade" id="confirm" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="m  odal-title mt-0" id="confirm">
                                                        ยืนยันการอนุมัติใบสั่งซื้อ
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="col-md-12 mt-2 mb-2 text-left">
                                                        <div class="row form-group ">
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14">เลขที่ใบสั่งซื้อ </h5>
                                                                <input class="form-control" type="text"
                                                                    value="{{ @$sRow->code_order }}" readonly="">
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14">วันที่สั่งซื้อในสลิป</h5>
                                                                <input class="form-control" type="text"
                                                                    value="{{ @$sRow->action_date }}" readonly="">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 mt-2 mb-2 text-left">
                                                        <div class="row form-group ">
                                                            <div class="col-md-12 text-left">
                                                                <h5 class="font-size-14">เลขที่ใบสั่งซื้อทั้งหมด</h5>
                                                                {!! $p_bill !!}
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-md-12 mt-2 mb-2 text-left">
                                                        <div class="row form-group ">
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14 ">ยอดชำระรวม </h5>

                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                @if (@$sRow->purchase_type_id_fk == 5)
                                                                    <?php $transfer_price = $sRow->transfer_price;
                                                                    $transfer_price = number_format($transfer_price, 2);
                                                                    ?>
                                                                    <input class="form-control" type="text"
                                                                        id="total_price_sum"
                                                                        value="{{ $transfer_price }}" readonly>
                                                                @else
                                                                    <input class="form-control" type="text"
                                                                        id="total_price_sum" value="{{ @$price }}"
                                                                        readonly>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 mt-2 mb-2 text-left">
                                                        <div class="row form-group ">
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14 required_star_red ">
                                                                    ธนาคารที่โอนชำระ
                                                                </h5>
                                                                <select id="account_bank" name="account_bank"
                                                                    class="form-control select2-templating " required>
                                                                    <option value="">Select</option>
                                                                    @if (@$TransferBank)
                                                                        @foreach (@$TransferBank as $r)
                                                                            <option value="{{ $r->id }}"
                                                                                {{ @$r->id == @$sRow->account_bank_name_customer ? 'selected' : '' }}>
                                                                                {{ $r->txt_bank_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14 required_star_red">ยอดอนุมัติ
                                                                    (กรอกยืนยันอีกครั้ง)</h5>
                                                                <input class="form-control NumberOnly " type="text"
                                                                    name="approval_amount_transfer"
                                                                    id="approval_amount_transfer"
                                                                    value="{{ @$approval_amount_transfer }}"
                                                                    required="">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mt-2 mb-2 text-left">
                                                        <div class="row form-group ">
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14 ">
                                                                    จำนวนยอดที่เกิน<br>(หากในสลิปยอดเงินเกินมา)</h5>
                                                                <input class="form-control NumberOnly " type="text"
                                                                    name="approval_amount_transfer_over"
                                                                    id="approval_amount_transfer_over"
                                                                    value="{{ @$sRow->approval_amount_transfer_over }}">
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <h5 class="font-size-14 ">
                                                                    สถานะโอนคืน<br>(หากในสลิปยอดเงินเกินมา)</h5>
                                                               <select class="form-control"  name="approval_amount_transfer_over_status">
                                                                <option value="0">ยังไม่โอนยอดคืน</option>
                                                                <option value="1">โอนยอดคืนแล้ว</option>
                                                               </select>
                                                            </div>
                                                        </div>
                                                    </div>





                                                    @if (!empty(@$sRow->pay_with_other_bill))
                                                        <div class="col-md-12 mt-2 mb-2 text-left">
                                                            <div class="row form-group ">
                                                                <div class="col-md-6 text-left">
                                                                    <h5 class="font-size-14 ">ชำระพร้อมบิลอื่น </h5>
                                                                    <input class="form-control" type="text"
                                                                        name="" id=""
                                                                        value="{{ @$sRow->pay_with_other_bill_note }}"
                                                                        readonly="">
                                                                </div>
                                                                <div class="col-md-6 text-left">
                                                                    <h5 class="font-size-14  ">ชำระร่วม </h5>
                                                                    <input class="form-control" type="text"
                                                                        name="" id=""
                                                                        value="{{ @$sRow->pay_with_other_bill_note }}"
                                                                        readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @ENDIF

                                                    <div class="col-md-12 mt-2 text-left">

                                                        @if (@$slip)
                                                            @php
                                                                @$i = 1;
                                                            @endphp
                                                            @foreach (@$slip_approve as $r)
                                                                <hr>
                                                                <div class="col-md-12 mt-2 mb-2 text-left ">
                                                                    <div class="row form-group ">
                                                                        <div class="col-md-6 text-left">
                                                                            <h5 class="font-size-14 required_star_red ">
                                                                                Slip
                                                                                {{ @$i }} :
                                                                                วันที่เวลาที่โอนในสลิป </h5>
                                                                        </div>
                                                                        <div class="col-md-12 text-left">
                                                                            <input type="hidden" name="slip_ids[]"
                                                                                value="{{ @$r->id }}">
                                                                            <input class="form-control transfer_bill_date "
                                                                                name="transfer_bill_date[]" type="text"
                                                                                value="{{ @$r->transfer_bill_date ? @$r->transfer_bill_date : null }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 mt-2 mb-2 text-left">
                                                                    <div class="row form-group ">
                                                                        <div class="col-md-12 text-left">
                                                                            <h5 class="font-size-14">หมายเหตุ : </h5>
                                                                            <input class="form-control slip_note "
                                                                                type="text" name="slip_note[]"
                                                                                value="{{ @$r->note }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if (!empty(@$r->file))
                                                                    <img src="{{ $r->url }}/{{ @$r->file }}"
                                                                        class="img-fluid" alt="Responsive image">
                                                                @ELSE
                                                                    <img src="{{ asset('local/public/images/example_img.png') }}"
                                                                        class="img-fluid" alt="Responsive image">
                                                                @ENDIF

                                                                @if ($i == 2)
                                                                    @if (!empty(@$sRow->note_fullpayonetime_02))
                                                                        <input class="form-control" type="text"
                                                                            name="" id=""
                                                                            value="{{ @$sRow->note_fullpayonetime_02 }}">
                                                                    @endif
                                                                @endif

                                                                @if ($i == 3)
                                                                    @if (!empty(@$sRow->note_fullpayonetime_03))
                                                                        <input class="form-control" type="text"
                                                                            name="" id=""
                                                                            value="{{ @$sRow->note_fullpayonetime_03 }}">
                                                                    @endif
                                                                @endif

                                                                @php
                                                                    @$i++;
                                                                @endphp
                                                            @endforeach
                                                        @endif

                                                    </div>

                                                </div>


                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" name="approved" style="display: none;"
                                                        value='approved'
                                                        class="btn btn-primary btn_approve_con_real">อนุมัติ</button>
                                                    <button type="button" name="approved" value='approved'
                                                        class="btn btn-primary btn_approve_con">อนุมัติ</button>
                                                </div>


                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                </form>

                                <form action="{{ route('backend.po_approve.update', @$sRow->id) }}" method="POST"
                                    enctype="multipart/form-data" autocomplete="off">
                                    <input name="_method" type="hidden" value="PUT">
                                    <input name="id" value="{{ $sRow->id }}" type="hidden">

                                    {{ csrf_field() }}


                                    <div class="modal fade" id="cancel" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    {{-- <h5 class="modal-title mt-0" id="cancel">ยกเลิกบิลชำระ</h5> --}}
                                                    <h5 class="modal-title mt-0" id="cancel">ไม่อนุมัติ</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label>Detail <span class="text-danger">*</span></label>
                                                        <div>
                                                            <textarea required="" name="detail" class="form-control" rows="3">{{ @$sRow->transfer_bill_note }}</textarea>
                                                        </div>
                                                    </div>

                                                    <center>


                                                        @if (@$slip_not_approve)
                                                            <?php $i = 1; ?>
                                                            @foreach (@$slip_not_approve as $r)
                                                                {{-- <input type="file" accept="image/*" id="image0{{$i}}" name="image0{{$i}}" class="form-control" OnChange="showPreview_0{{$i}}(this)" required="" > --}}

                                                                @if (!empty(@$r->file))
                                                                    <img id="imgAvatar_0{{ $i }}"
                                                                        src="{{ @$r->url }}/{{ @$r->file }}"
                                                                        class="img-fluid" alt="Responsive image">
                                                                @ELSE
                                                                    <center><img id="imgAvatar_0{{ $i }}"
                                                                            src="{{ asset('local/public/images/file-slip.png') }}"
                                                                            style="margin-top: 5px;height: 180px;">
                                                                @ENDIF

                                                                <?php $i++; ?>
                                                            @endforeach
                                                        @endif


                                                        @if (count($slip) == 0)
                                                            {{-- <input type="file" accept="image/*" id="image01" name="image01" class="form-control" OnChange="showPreview_01(this)" required="" >
                                                       <center><img id="imgAvatar_01" src="{{ asset('local/public/images/file-slip.png') }}" style="margin-top: 5px;height: 180px;" > --}}
                                                        @endif


                                                </div>
                                                <div class="modal-footer">

                                                    <button type="submit" type="submit" name="no_approved"
                                                        value='no_approved' class="btn btn-primary">ไม่อนุมัติ</button>

                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->




                                </form>

                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->


@endsection

@section('script')
    <script src="{{ URL::asset('backend/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <!-- Lightbox init js -->
    <script src="{{ URL::asset('backend/js/pages/lightbox.init.js') }}"></script>
    <script src="{{ asset('asset/lity/lity.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var id = "{{ @$sRow->id }}"; // alert(id);
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
                    // scrollY: ''+($(window).height()-370)+'px',
                    // iDisplayLength: 25,
                    ajax: {
                        url: '{{ route('backend.po_approve_edit.datatable') }}',
                        data: {
                            id: id,
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
                            className: 'text-center',
                            render: function(d) {
                                if (d) {
                                    return d;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        // {data: 'updated_at', title :'<center>วันเวลาที่โอน </center>', className: 'text-center'},
                        // {data: 'pay_with_other_bill_note', title :'<center>ชำระร่วม </center>', className: 'text-center'},
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

                    ],
                    rowCallback: function(nRow, aData, dataIndex) {

                    }

                });

            });

            var oTable;
            $(function() {
                oTable = $('#data-table_other').DataTable({
                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: false,
                    serverSide: false,
                    scroller: false,
                    scrollCollapse: false,
                    scrollX: false,
                    ordering: false,
                    // scrollY: ''+($(window).height()-370)+'px',
                    // iDisplayLength: 25,
                    ajax: {
                        url: '{{ route('backend.po_approve_edit_other.datatable') }}',
                        data: {
                            id: id,
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
                            className: 'text-center',
                            render: function(d) {
                                if (d) {
                                    return d;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        // {data: 'updated_at', title :'<center>วันเวลาที่โอน </center>', className: 'text-center'},
                        // {data: 'pay_with_other_bill_note', title :'<center>ชำระร่วม </center>', className: 'text-center'},
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

                    ],
                    rowCallback: function(nRow, aData, dataIndex) {

                    }

                });

            });

            var oTable_ai;
            $(function() {
                oTable_ai = $('#data-table_other_ai').DataTable({
                    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: false,
                    serverSide: false,
                    scroller: false,
                    scrollCollapse: false,
                    scrollX: false,
                    ordering: false,
                    // scrollY: ''+($(window).height()-370)+'px',
                    // iDisplayLength: 25,
                    ajax: {
                        url: '{{ route('backend.po_approve_edit_other_ai.datatable') }}',
                        data: {
                            id: id,
                        },
                        method: 'POST',
                    },

                    columns: [{
                            data: 'id',
                            title: 'ID',
                            className: 'text-center w50'
                        },
                        {
                            data: 'customer_name',
                            title: '<center>ลูกค้า </center>',
                            className: 'text-left w150 '
                        },
                        {
                            data: 'code_order',
                            title: '<center> Order </center>',
                            className: 'text-left w100 '
                        },
                        {
                            data: 'aicash_remain',
                            title: '<center>ยอด Ai-Cash <br> ก่อนชำระ</center>',
                            className: 'text-center'
                        },
                        {
                            data: 'aicash_amt',
                            title: '<center>ยอด Ai-Cash <br>ที่ชำระ</center>',
                            className: 'text-center'
                        },
                        {
                            data: 'aicash_banlance',
                            title: '<center>ยอด Ai-Cash <br>หลังชำระ</center>',
                            className: 'text-center'
                        },
                        {
                            data: 'action_user',
                            title: '<center>พนักงาน <br> ที่ดำเนินการ </center>',
                            className: 'text-center'
                        },
                        // {
                        //     data: 'pay_type_id_fk',
                        //     title: '<center>รูปแบบการชำระเงิน </center>',
                        //     className: 'text-center'
                        // },
                        // {
                        //     data: 'total_amt',
                        //     title: '<center>ยอดชำระเงิน </center>',
                        //     className: 'text-center'
                        // },
                        // {
                        //     data: 'status',
                        //     title: '<center>สถานะ </center>',
                        //     className: 'text-center'
                        // },
                        // {
                        //     data: 'approver',
                        //     title: '<center>ผู้อนุมัติ</center>',
                        //     className: 'text-center'
                        // },
                        {
                            data: 'created_at',
                            title: '<center>วันที่ชำระ</center>',
                            className: 'text-center'
                        },
                    ],
                    rowCallback: function(nRow, aData, dataIndex) {

                    }

                });

            });

            $(function() {
                $.ajax({
                    type: "POST",
                    url: " {{ url('backend/po_approve_edit_other/datatable_sum') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(data) {
                        $('#data-table_sum').html(data);
                    }
                });
            });



        });
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
        integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
        crossorigin="anonymous"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
        crossorigin="anonymous" />

    <script>
        $('.transfer_bill_date').datetimepicker({
            value: '',
            rtl: false,
            format: 'Y-m-d H:i',
            formatTime: 'H:i',
            formatDate: 'Y-m-d H:i',
            monthChangeSpinner: true,
            closeOnTimeSelect: true,
            closeOnWithoutClick: true,
            closeOnInputClick: true,
            openOnFocus: true,
            timepicker: true,
            datepicker: true,
            weeks: false,
            // minDate: 0,
        });




        $(document).on('click', '.btn_approve_con', function() {
            var approval_amount_transfer = $('#approval_amount_transfer').val();
            var total_price_sum = $('#total_price_sum').val();
            total_price_sum = total_price_sum.replace(",", "");
            approval_amount_transfer = parseFloat(approval_amount_transfer);
            total_price_sum = parseFloat(total_price_sum);
            if (approval_amount_transfer == total_price_sum) {
                $('.btn_approve_con_real').trigger('click');
            } else {
                alert('กรุณาระบุยอดเงินให้ตรงกัน');
            }

        });

        function showPreview_01(ele) {
            $('#image01').attr('src', ele.value); // for IE
            if (ele.files && ele.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.span_file_slip').show();
                    $('#imgAvatar_01').show();
                    $('#imgAvatar_01').attr('src', e.target.result);
                }
                reader.readAsDataURL(ele.files[0]);
            }
        }

        function showPreview_02(ele) {
            $('#image02').attr('src', ele.value); // for IE
            if (ele.files && ele.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.span_file_slip_02').show();
                    $('#imgAvatar_02').show();
                    $('#imgAvatar_02').attr('src', e.target.result);
                }
                reader.readAsDataURL(ele.files[0]);
            }
        }

        function showPreview_03(ele) {
            $('#image03').attr('src', ele.value); // for IE
            if (ele.files && ele.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.span_file_slip_03').show();
                    $('#imgAvatar_03').show();
                    $('#imgAvatar_03').attr('src', e.target.result);
                }
                reader.readAsDataURL(ele.files[0]);
            }
        }



        $(document).on('click', '.btnDelSlip', function(event) {
            var id = $(this).data('id');
            if (!confirm("ยืนยันการลบ ! / Confirm to delete ? ")) {
                return false;
            } else {

                $.ajax({
                    type: "POST",
                    url: " {{ url('backend/ajaxDelFileSlip_04') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });

            }

        });

        $(document).on('click', '.btn_approve', function(event) {
            var id = $(this).data('id');
            var status = 2;
            $.ajax({
                type: "POST",
                url: " {{ url('backend/ajaxApproveFileSlip_04') }} ",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },
                success: function(data) {
                    // console.log(data);
                    location.reload();
                }
            });
        });

        $(document).on('click', '.btn_not_approve', function(event) {
            var id = $(this).data('id');
            var status = 3;
            $.ajax({
                type: "POST",
                url: " {{ url('backend/ajaxApproveFileSlip_04') }} ",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },
                success: function(data) {
                    // console.log(data);
                    location.reload();
                }
            });
        });

        // $(document).on('click', '.btnNotAprrove', function(event) {
        //           var id = $(this).attr('order_id');
        //               $.ajax({
        //                   type: "POST",
        //                   url: " {{ url('backend/ajaxApproveFileSlip_04') }} ",
        //                    data:{ _token: '{{ csrf_token() }}',id:id},
        //                   success: function(data){
        //                     // console.log(data);
        //                     location.reload();
        //                   }
        //               });
        // });

        $(document).on('change', '.note2', function() {
            var id = $(this).data('id');
            var note2 = $(this).val();
            console.log(note2);
            $.ajax({
                type: "POST",
                url: " {{ url('backend/ajaxChangeFileSlip_04') }} ",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    note2: note2
                },
                success: function(data) {

                }
            });
        });
    </script>
@endsection
