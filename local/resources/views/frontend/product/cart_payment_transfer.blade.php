@extends('frontend.layouts.customer.customer_app')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/bower_components/select2/css/select2.min.css') }}" />
    <!-- Multi Select css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/multiselect/css/multi-select.css') }}">
@endsection
@section('conten')

    <div class="row">
        <div class="col-md-8 col-sm-12">
            <form action="{{ route('payment_submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="code_order" value="{{ $data->code_order }}">
                <input type="hidden" name="id" value="{{ $data->id }}">
                <!-- Choose Your Payment Method start -->
                <div class="card card-border-success">
                    <div class="card-header p-3">

                        @if ($data->purchase_type_id_fk == 1)
                            <h5>รายการสั่งซื้อเพื่อทำคุณสมบัติ</h5>
                        @elseif($data->purchase_type_id_fk == 2)
                            <h5>รายการสั่งซื้อเพื่อรักษาคุณสมบัติรายเดือน</h5>
                        @elseif($data->purchase_type_id_fk == 3)
                            <h5>รายการสั่งซื้อเพื่อรักษาคุณสมบัติท่องเที่ยว</h5>
                        @elseif($data->purchase_type_id_fk == 4)
                            <h5>รายการสั่งซื้อเพื่อเติม Ai-Stockist</h5>
                        @elseif($data->purchase_type_id_fk == 5)
                            <h5> Ai Voucher</h5>
                        @elseif($data->purchase_type_id_fk == 6)
                            <h5>คอร์สอบรม</h5>
                        @else
                            <h5 class="text-danger">ไม่ทราบจุดประสงค์การสั่งซื้อ</h5>
                        @endif
                        {{-- <div class="card-header-right"></div> --}}
                    </div>
                    <div class="card-block payment-tabs">
                        <ul class="nav nav-tabs md-tabs" role="tablist">
                            @if ($data->purchase_type_id_fk == 6)
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" id="nav_card" href="#credit-card"
                                        role="tab">ชำระเงิน</a>
                                    <div class="slide"></div>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link active" id="nav_card">ชำระเงิน</a>
                                    <div class="slide"></div>
                                </li>
                            @endif

                            {{-- <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#debit-card" role="tab">Debit Card</a>
            <div class="slide"></div>
          </li> --}}
                        </ul>
                        <div class="tab-content active m-t-15">

                            <div class="tab-pane active" id="credit-card" role="tabpanel">
                                @if ($data->total_price == 0 and $data->purchase_type_id_fk == 5)
                                    <div class="row">
                                        <div class="col-md-12 col-xl-12">
                                            <div class="card bg-c-green order-card m-b-0">
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-md-8 col-sx-8 col-8">
                                                            <h6 class="m-b-10" style="font-size: 16px">Ai Voucher </h6>
                                                        </div>
                                                        <div class="col-md-4 col-sx-4 col-4">
                                                            <?php $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
                                                            ?>
                                                            <h3 class="text-right">
                                                                <span>{{ number_format($gv->sum_gv) }}
                                                                </span>
                                                            </h3>
                                                        </div>
                                                    </div>


                                                    <hr>

                                                    <div class="row">
                                                        <div class="col-md-8 col-sx-8 col-8">
                                                            <h6 class="m-b-10" style="font-size: 16px">ยอดรวมที่ใช้ </h6>

                                                        </div>
                                                        <div class="col-md-4 col-sx-4 col-4">

                                                            <h3 class="text-right"> <span class="price_total">
                                                                    {{ number_format($data->price_total) }} </span></h3>
                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-8 col-sx-8 col-8">
                                                            <h6 style="font-size: 16px"> Ai Voucher คงเหลือ </h6>

                                                        </div>
                                                        <div class="col-md-4 col-sx-4 col-4">

                                                            <h3 class="text-right"> <span class="gv_remove_price">
                                                                    {{ number_format($data->gv_total, 2) }} </span></h3>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row m-t-5">
                                                <div class="col-sm-6">
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <button class="btn btn-success btn-block" type="submit" name="submit"
                                                        value="gift_voucher">ชำระเงิน</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="demo-container card-block">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-xl-12 m-b-30">

                                                <div class="form-radio">
                                                    <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" id="bank" onchange="open_input(1)"
                                                                name="pay_type" value="1" checked="checked">
                                                            <i class="helper"></i><b>โอนชำระ</b>
                                                        </label>
                                                    </div>

                                                    <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" id="mobile_banking" onchange="open_input(4)"
                                                                name="pay_type" value="">
                                                            <i class="helper"></i><b>Mobile Banking</b>
                                                        </label>
                                                    </div>

                                                    <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" onchange="open_input(2)" id="credit_cart"
                                                                name="pay_type" value="2">
                                                            <i class="helper"></i><b>บัตรเครดิต</b>
                                                        </label>
                                                    </div>

                                                    <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" onchange="open_input(3)" id="ai_cash"
                                                                name="pay_type" value="3">
                                                            <i class="helper"></i><b>Ai-Cash</b>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="cart_payment_tranfer">

                                            <div class="row col-md-12 col-lg-12">
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="card">
                                                        <div class="card-block text-center">
                                                            {{-- <i class="fa fa-envelope-open text-c-blue d-block f-40"></i> --}}
                                                            <img src="{{ asset('frontend/assets/images/scb.png') }}"
                                                                class="img-fluid" alt="Responsive image" width="80">
                                                            <h5 class="m-t-20"><span
                                                                    class="text-c-blue">019-7-03027-3</span></h5>
                                                            <p class="m-b-2 m-t-5">ธนาคารไทยพาณิชย์ <br>Aiyara Planet </p>
                                                            {{-- <button class="btn btn-primary btn-sm btn-round">Manage List</button> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group row">

                                                <div class="col-sm-12">

                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">(
                                                                    JPG,PNG )</b>
                                                            </label>
                                                            <input type="file" id="upload" name="file_slip"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-6 p-1">
                                                        <button class="btn btn-success btn-block" type="submit"
                                                            name="submit" id="submit_upload"
                                                            value="upload">อัพโหลดหลักฐานการชำระเงิน</button>
                                                    </div>

                                                    <div class="col-xs-6 p-1">
                                                        <button class="btn btn-primary btn-block" type="submit"
                                                            name="submit"
                                                            value="not_upload">อัพโหลดหลักฐานการชำระเงินภายหลัง</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row" id="cart_payment_mobile_banking" style="display: none">
                                            <div class="row col-md-12 col-lg-12">
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="card">
                                                        <div class="card-block text-center">
                                                            {{-- <i class="fa fa-envelope-open text-c-blue d-block f-40"></i> --}}
                                                            <img src="{{ asset('frontend/assets/images/thai_qr_payment.png') }}"
                                                                class="img-fluid" alt="ชำระด้วย PromptPay">
                                                            <a class="btn btn-primary btn-md mt-2" data-toggle="modal"
                                                                style="color: aliceblue" data-target="#confirm_promptPay">
                                                                ชำระด้วย PromptPay </a>

                                                            <div class="modal fade" id="confirm_promptPay" tabindex="-1"
                                                                role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">ยืนยันการชำระเงินด้วย
                                                                                PromptPay</h4>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            {{-- <h5>Static Modal</h5> --}}
                                                                            <img src="{{ asset('frontend/assets/images/thai_qr_payment.png') }}"
                                                                                class="img-fluid" alt="ชำระด้วย PromptPay">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-default waves-effect "
                                                                                data-dismiss="modal">Close</button>
                                                                            <button class="btn btn-success md-auto"
                                                                                name="submit" value="PromptPay"
                                                                                type="submit">Confirm</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="card">
                                                        <div class="card-block text-center">
                                                            <img src="{{ asset('frontend/assets/images/truemoneywallet-logo.png') }}"
                                                                class="img-fluid" alt="TrueMoney">
                                                            <a class="btn btn-primary btn-md mt-2"
                                                                class="btn btn-primary btn-md mt-2" data-toggle="modal"
                                                                style="color: aliceblue" data-target="#confirm_truemoney">
                                                                ชำระด้วย TrueMoney </a>

                                                            <div class="modal fade" id="confirm_truemoney" tabindex="-1"
                                                                role="dialog">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">ยืนยันการชำระเงินด้วย
                                                                                TrueMoney</h4>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            {{-- <h5>Static Modal</h5> --}}
                                                                            <img src="{{ asset('frontend/assets/images/truemoneywallet-logo.png') }}"
                                                                                class="img-fluid" alt="ชำระด้วย TrueMoney">

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-default waves-effect "
                                                                                data-dismiss="modal">Close</button>
                                                                            <button class="btn btn-success md-auto"
                                                                                name="submit" value="TrueMoney"
                                                                                type="submit">Confirm</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row" id="cart_payment_credit_card" style="display: none">

                                            <div class="col-sm-12 col-md-12">
                                                <div class="card card-border-success">

                                                    <div class="card-block" style="padding: 10px">
                                                        <div class="col-md-12">

                                                            <div class="row mt-2">
                                                                <div class="col-md-8 col-sx-8 col-8">
                                                                    <h4 class="m-b-10"> ยอดที่ต้องชำระ </h4>
                                                                </div>

                                                                <div class="col-md-4 col-sx-4 col-4">
                                                                    <h3 class="text-right">
                                                                        <u><span> {{ $data->total_price }} </span></u>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="text-right">
                                                            <button class="btn btn-success" name="submit"
                                                                value="credit_card"
                                                                type="submit">ชำระเงินด้วยบัตรเครดิต</button>
                                                        </div>

                                                        <!-- end of card-footer -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="cart_payment_aicash" style="display: none;">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="card card-border-success">

                                                    <div class="card-block" style="padding: 10px">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-8 col-sx-8 col-8">
                                                                    <h4 class="m-b-10">Ai-Cash</h4>
                                                                </div>
                                                                <div class="col-md-4 col-sx-4 col-4">
                                                                    <h3 class="text-right">
                                                                        <span id="ai_cash_p" class="text-success">
                                                                            {{ number_format(Auth::guard('c_user')->user()->ai_cash) }}
                                                                        </span>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-8 col-sx-8 col-8">
                                                                    <h4 class="m-b-10"> ยอดรวมที่ใช้ </h4>
                                                                </div>
                                                                <div class="col-md-4 col-sx-4 col-4">
                                                                    <h3 class="text-right">
                                                                        <u><span class="price_total"> </span></u>
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div id="error_aicash" class="text-right"></div>
                                                        <div class="text-right">
                                                            {{-- <button class="btn btn-success" name="submit" id="ai_cash_submit" value="ai_cash"
                                                        type="submit">ชำระเงินด้วย Ai-Cash</button> --}}

                                                            <a class="btn btn-success" data-toggle="modal"
                                                                data-target="#default-Modal">ชำระเงินด้วย Ai-Cash</a>
                                                        </div>
                                                    </div>
                                                    <!-- end of card-footer -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">กรุณายืนยันรหัสผ่าน Ai-Cash
                                                            ก่อนทำการชำระเงิน</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row text-center">
                                                            <div class="col-md-1">
                                                            </div>
                                                            <div class="col-md-10">
                                                                <div id="status_check_pass_aicash"></div>

                                                                <div class="input-group input-group-primary">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-lock"></i>
                                                                    </span>
                                                                    <input type="password" name="password_aicash"
                                                                        id="password_aicash" class="form-control"
                                                                        placeholder="Password AiCash">
                                                                    <span class="input-group-addon btn btn-primary"
                                                                        id="basic-addon10">
                                                                        <span class=""
                                                                            onclick="check_pass_aicash()">Check</span>
                                                                    </span>
                                                                </div>


                                                            </div>
                                                            <div class="col-md-1">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">

                                                        <a href="{{ route('chage_password_aicash') }}"
                                                            id="chage_password_aicash"
                                                            class="btn btn-warning waves-effect waves-light">ตั้งค่า
                                                            PassWord Ai-cash</a>
                                                        <button type="button" class="btn btn-default waves-effect "
                                                            data-dismiss="modal">Close</button>
                                                        <button class="btn btn-success md-auto" name="submit"
                                                            id="ai_cash_submit" value="ai_cash" style="display: none"
                                                            type="submit">ชำระเงินด้วย Ai-Cash</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="card card-border-success">
                <div class="card-header">
                    <h4>สรุปรายการสั่งซื้อ</h4>
                    {{-- <span class="label label-default f-right"> 28 January, 2015 </span> --}}
                </div>
                <div class="card-block" style="padding: 10px">
                    <div class="table-responsive p-3">
                        <table class="table">
                            <tr>
                                <td><strong id="quantity_bill">มูลค่าสินค้า ({{ $data->quantity }}) ชิ้น</strong></td>
                                <td align="right"><strong id="price"> {{ number_format($data->product_value, 2) }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Vat({{ $data->tax }}%)</strong></td>
                                <td align="right"><strong> {{ $data->sum_price - $data->product_value }} </strong></td>
                            </tr>
                            <tr>
                                <td><strong>มูลค่าสินค้า + Vat</strong></td>
                                <td align="right"><strong> {{ $data->sum_price }} </strong></td>
                            </tr>

                            @if ($data->purchase_type_id_fk != 6)

                                <tr>
                                    <td><strong>ค่าจัดส่ง</strong></td>
                                    <td align="right"><strong id="shipping_cost"> {{ $data->shipping_price }} </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <label class="label label-inverse-warning">
                                            <font id="shipping_detail" style="color: #000">
                                                {{ $data->shipping_cost_detail }} </font>
                                        </label>

                                </tr>

                            @endif
                            <tr>
                                <td><strong>คะแนนที่ได้รับ</strong></td>
                                <td align="right"><strong class="text-success" id="pv">{{ $data->pv_total }}
                                        PV</strong>
                                </td>
                            </tr>
                            @if ($data->purchase_type_id_fk == 5)
                                <?php $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name); ?>
                                <tr>
                                    <td><strong>ยอดรวม</strong></td>
                                    <td align="right"><strong class="price_total">
                                            {{ number_format($data->price_total, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary"> Ai Voucher</strong></td>
                                    <td align="right"><strong class="text-primary"> {{ $gv->sum_gv }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary" style="font-size: 13px"> Ai Voucher คงเหลือ</strong>
                                    </td>
                                    <td align="right"><strong class="text-primary gv_remove_price">
                                            {{ $data->gv_total }}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>ยอดที่ต้องชำระเพิ่ม</strong></td>
                                    <td align="right"><strong class="total_price">
                                            {{ number_format($data->total_price, 2) }}</strong>

                                    </td>
                                </tr>
                            @elseif($data->purchase_type_id_fk == 6)
                                <tr>
                                    <td><strong>ยอดที่ต้องชำระ</strong></td>
                                    <td align="right"><strong> {{ number_format($data->price, 2) }} </strong>
                                    </td>
                                </tr>

                            @else
                                <tr>
                                    <td><strong>ยอดที่ต้องชำระ</strong></td>
                                    <td align="right"><u><strong> {{ number_format($data->total_price, 2) }} </strong></u>
                                    </td>
                                </tr>
                            @endif


                        </table>
                        <hr m-t-2>
                        {{-- @if ($check_giveaway['status'] == 'success' and $check_giveaway['s_data'])
                            <h4 class="text-danger">Promotion Free </h4>


                            <table class="table table-responsive m-b-0">
                                @foreach ($check_giveaway['s_data'] as $giveaway_value)
                                    <?php
                                    //dd($giveaway_value);
                                    ?>
                                    <tr class="table-danger">
                                        <td><strong>{{ $giveaway_value['name'] }} x
                                                [{{ $giveaway_value['count_free'] }}]</strong></td>
                                        <td>รวม</td>
                                    </tr>
                                    @if ($giveaway_value['type'] == 1)

                                        @foreach ($giveaway_value['product'] as $product_value)
                                            <tr>
                                                <td><strong style="font-size: 12px">{{ $product_value->product_name }}
                                                        [{{ $product_value->product_amt }}] x
                                                        [{{ $giveaway_value['count_free'] }}]</strong></td>
                                                <td align="right"><strong>
                                                        {{ $product_value->product_amt * $giveaway_value['count_free'] }}
                                                        {{ $product_value->product_unit }}</strong></td>
                                            </tr>
                                        @endforeach
                                    @else

                                        <tr>
                                            <td><strong style="font-size: 12px">GiftVoucher {{ $giveaway_value['gv'] }} x
                                                    [{{ $giveaway_value['count_free'] }}]</strong></td>
                                            <?php //$gv_total = $giveaway_value['gv'] * $giveaway_value['count_free'];
                                            ?>
                                            <td align="right"><strong> {{ number_format($gv_total) }} GV</strong></td>
                                        </tr>
                                    @endif
                                @endforeach

                            </table>
                        @endif --}}

                    </div>

                </div>
                <div class="card-footer">
                </div>
                <!-- end of card-footer -->
            </div>
        </div>
    </div>


@endsection
@section('js')

    @if ($data->total_price > 0 and $data->purchase_type_id_fk == 5)
        <script type="text/javascript">
            document.getElementById("submit_upload").disabled = true;
            document.getElementById("submit_upload").className = "btn btn-inverse btn-block";
        </script>
    @elseif($data->purchase_type_id_fk != 5)
        <script type="text/javascript">
            document.getElementById("submit_upload").disabled = true;
            document.getElementById("submit_upload").className = "btn btn-inverse btn-block";
        </script>
    @endif

    <script type="text/javascript">
        function check_pass_aicash() {
            var password_aicash = $('#password_aicash').val();

            $.ajax({
                type: "POST",
                url: "{{ route('check_pass_aicash') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    password_aicash: password_aicash,
                },
                success: function(data) {
                    if (data['status'] == 'fail') {
                        $('#status_check_pass_aicash').html('<b class="text-danger m-b-0"> ' + data['message'] +
                            ' </b>');
                    } else {
                        $('#status_check_pass_aicash').html('<b class="text-success m-b-0"> ' + data[
                            'message'] + ' </b>');
                        document.getElementById("ai_cash_submit").style.display = 'block';
                        document.getElementById("chage_password_aicash").style.display = 'none';
                    }

                }
            });
        }

        function open_input(data) {
            var conten_4 = '<button class="btn btn-success btn-block" type="submit">ชำระเงิน</button>';

            if (data == '1') {

                document.getElementById("cart_payment_tranfer").style.display = "block";
                document.getElementById("cart_payment_credit_card").style.display = "none";
                document.getElementById("cart_payment_aicash").style.display = "none";
                document.getElementById("cart_payment_mobile_banking").style.display = "none";

                document.getElementById("submit_upload").disabled = true;
                document.getElementById("submit_upload").className = "btn btn-success";
                $('#upload').change(function() {
                    var fileExtension = ['jpg', 'png'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
                        this.value = '';
                        return false;
                    } else {
                        document.getElementById("submit_upload").disabled = false;
                    }
                });
            } else if (data == '2') {
                document.getElementById("cart_payment_tranfer").style.display = "none";
                document.getElementById("cart_payment_credit_card").style.display = "block";
                document.getElementById("cart_payment_aicash").style.display = "none";
                document.getElementById("cart_payment_mobile_banking").style.display = "none";
            } else if (data == '3') {


                var ai_cash = {{ Auth::guard('c_user')->user()->ai_cash }};
                var price_total = $('#price_total').val();

                if (ai_cash < price_total) {
                    document.getElementById("ai_cash_submit").style.display = "none";
                    $('#error_aicash').html(
                        '<label class="label label-inverse-danger text-right">Ai-Cash ไม่พอสำหรับการชำระเงิน</label>');
                }

                document.getElementById("cart_payment_tranfer").style.display = "none";
                document.getElementById("cart_payment_credit_card").style.display = "none";
                document.getElementById("cart_payment_aicash").style.display = "block";
                document.getElementById("cart_payment_mobile_banking").style.display = "none";


            } else if (data == '4') {
                document.getElementById("cart_payment_tranfer").style.display = "none";
                document.getElementById("cart_payment_credit_card").style.display = "none";
                document.getElementById("cart_payment_aicash").style.display = "none";
                document.getElementById("cart_payment_mobile_banking").style.display = "block";
            } else {
                alert('ไม่มีช่องทางให้ชำระ (Data is Null)');
            }
        }




        $('#upload').change(function() {
            var fileExtension = ['jpg', 'png'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
                this.value = '';
                return false;
            } else {
                document.getElementById("submit_upload").disabled = false;
                document.getElementById("submit_upload").className = "btn btn-success";
            }
        });
    </script>

    <script src="{{ asset('frontend/custom/cart_payment/other.js') }}"></script>{{-- js อื่นๆ --}}
@endsection
