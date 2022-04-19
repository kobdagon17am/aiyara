@extends('frontend.layouts.customer.customer_app')
@section('css')
<?php
use App\Models\Frontend\CourseCheckRegis;
?>
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
                <input type="hidden" name="price" value="{{ $bill['price'] }}">
                <input type="hidden" name="vat" value="{{ $bill['vat'] }}">
                <input type="hidden" name="type" value="6">
                <input type="hidden" name="pv_total" value="{{$bill['pv_total']}}">

                <div class="card card-border-success">
                    <div class="card-header p-3">


                        @if ($bill['type'] == 6)
                            <h5>คอร์สอบรม</h5>
                        @else
                            <h5 class="text-danger">ไม่ทราบจุดประสงค์การสั่งซื้อ</h5>
                        @endif
                        {{-- <div class="card-header-right"></div> --}}
                    </div>

                    <div class="card-block payment-tabs">
                        <ul class="nav nav-tabs md-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" id="nav_card" href="#credit-card"
                                    role="tab">ชำระเงิน</a>
                                <div class="slide"></div>
                            </li>

                        </ul>
                        <div class="tab-content active m-t-15">

                            <div class="tab-pane active" id="credit-card" role="tabpanel">

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
                                                {{-- <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" onchange="open_input(2)" id="credit_cart"
                                                                name="pay_type" value="2">
                                                            <i class="helper"></i><b>บัตรเครดิต</b>
                                                        </label>
                                                    </div> --}}

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
                                                              class="text-c-blue">401-110-1843</span></h5>
                                                      <p class="m-b-2 m-t-5">ธนาคารไทยพาณิชย์ <br>บริษัท ไอยรา แพลนเน็ต จำกัด</p>
                                                        {{-- <button class="btn btn-primary btn-sm btn-round">Manage List</button> --}}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row col-md-12 col-lg-12">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group row">

                                                    <label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">(
                                                            JPG,PNG )</b>
                                                    </label>
                                                    <input type="file" id="upload" name="file_slip" class="form-control">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="col-xs-6 p-1">
                                              @if($canAccess)
                                                <button class="btn btn-success btn-block" type="submit" name="submit"
                                                    id="submit_upload" value="upload">อัพโหลดหลักฐานการชำระเงิน</button>
                                              @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" id="cart_payment_mobile_banking" style="display: none">
                                        <div class="row col-md-12 col-lg-12">
                                          @if(Auth::guard('c_user')->user()->business_location_id != 3)
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
                                                                        @if($canAccess)
                                                                        <button class="btn btn-success md-auto"
                                                                            name="submit" value="PromptPay"
                                                                            type="submit">Confirm</button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                            @endif
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
                                                                        @if($canAccess)
                                                                        <button class="btn btn-success md-auto"
                                                                            name="submit" value="TrueMoney"
                                                                            type="submit">Confirm</button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row" id="cart_payment_credit_card" style="display: none">

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
                                        </div> --}}

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
                                                                        {{ number_format(Auth::guard('c_user')->user()->ai_cash, 2) }}
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
                                                                    <u><span> {{ number_format($bill['price'], 2) }}
                                                                        </span></u>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div id="error_aicash" class="text-right"></div>
                                                    <div class="text-right">


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
                                                    <button class="btn btn-success" name="submit" id="ai_cash_submit"
                                                        style="display: none" value="ai_cash"
                                                        type="submit">ยืนยันการระเงินด้วย Ai-Cash</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </form>


            <div class="card">
                <div class="card card-border-warning">
                  <div class="card-header">
                    <h5>รายการสั่งซื้อคอร์สอบรม</h5>
                  </div>
                  <div class="card-block">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="dt-responsive table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th width="100">Quantity</th>
                                            <th width="100">Amount</th>
                                            <th>Pv</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill['data'] as $value)
                                        <?php
                                            $check = CourseCheckRegis::cart_check_register($value['id'],$value['quantity'],Auth::guard('c_user')->user()->user_name);
                                        ?>

                                        <tr id="items">
                                            <td class="text-center">
                                                <a href="{{ route('product-detail',['type'=>6,'id'=>$value['id']]) }}"><img src="{{asset($value['attributes']['img'])}}" class="img-fluid zoom img-70" alt="tbl"></a>
                                            </td>
                                            <td>
                                                <h6> <a href="{{ route('product-detail',['type'=>6,'id'=>$value['id']]) }}">{{ $value['name'] }}</a></h6>

                                                @if($value['attributes']['promotion_detail'])
                                                <ul>
                                                    @foreach($value['attributes']['promotion_detail'] as $promotion_product)
                                                    <li style="font-size: 12px">
                                                        <i class="icofont icofont-double-right text-success"></i> {{ $promotion_product->product_name }} {{ $promotion_product->product_amt }} {{ $promotion_product->unit_name }}
                                                    </li>

                                                    @endforeach
                                                </ul>

                                                @endif

                                                @if($check['status'] == 'fail')
                                                <span id="eror_{{ $value['id'] }}" class="text-danger">{{ $check['message'] }}</span>
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                <b> {{ $value['quantity']  }}</b>
                                            </td>
                                            <td><b>{{ number_format($value['price'],2) }}</b></td>

                                            <td><b>{{$value['attributes']['pv']}}</b></td>
                                            {{-- <td class="text-center">
                                                <button class="btn btn-warning btn-outline-warning btn-icon btn-sm"  onclick="cart_delete('{{ $value['id'] }}')"><i class="icofont icofont-delete-alt"></i></button>
                                            </td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
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
                                <td><strong id="quantity_bill">มูลค่าสินค้า ({{ $bill['quantity'] }}) ชิ้น</strong></td>
                                <td align="right"><strong id="price"> {{ number_format($bill['price_vat'], 2) }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Vat({{ $bill['vat'] }}%)</strong></td>
                                <td align="right"><strong> {{ number_format($bill['p_vat'], 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>มูลค่าสินค้า + Vat</strong></td>
                                <td align="right"><strong> {{ number_format($bill['price'], 2) }}</strong></td>
                            </tr>

                            <tr>
                                <td><strong>คะแนนที่ได้รับ</strong></td>
                                <td align="right"><strong class="text-success" id="pv">{{ $bill['pv_total'] }}
                                        PV</strong>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>ยอดที่ต้องชำระ</strong></td>
                                <td align="right"><strong> {{ $bill['price'] }} </strong>

                                </td>
                            </tr>




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

    <script type="text/javascript">
        document.getElementById("submit_upload").disabled = true;
        document.getElementById("submit_upload").className = "btn btn-inverse btn-block";
    </script>

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
                // document.getElementById("cart_payment_credit_card").style.display = "none";
                document.getElementById("cart_payment_aicash").style.display = "none";
                document.getElementById("cart_payment_mobile_banking").style.display = "none";


                $('#upload').change(function() {
                    var fileExtension = ['jpg', 'png'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
                        this.value = '';
                        return false;
                    } else {

                        document.getElementById("submit_upload").disabled = false;
                        document.getElementById("not_upload").disabled = true;
                    }
                });
            } else if (data == '2') {
                document.getElementById("cart_payment_tranfer").style.display = "none";
                // document.getElementById("cart_payment_credit_card").style.display = "block";
                document.getElementById("cart_payment_aicash").style.display = "none";
                document.getElementById("cart_payment_mobile_banking").style.display = "none";
            } else if (data == '3') {


                var ai_cash = {{ Auth::guard('c_user')->user()->ai_cash }};
                var price_total = "{{ $bill['price'] }}";

                if (ai_cash < price_total) {
                    document.getElementById("ai_cash_submit").style.display = "none";
                    $('#error_aicash').html(
                        '<label class="label label-inverse-danger text-right">Ai-Cash ไม่พอสำหรับการชำระเงิน</label>');
                }

                document.getElementById("cart_payment_tranfer").style.display = "none";
                // document.getElementById("cart_payment_credit_card").style.display = "none";
                document.getElementById("cart_payment_aicash").style.display = "block";
                document.getElementById("cart_payment_mobile_banking").style.display = "none";


            } else if (data == '4') {
                document.getElementById("cart_payment_tranfer").style.display = "none";
                // document.getElementById("cart_payment_credit_card").style.display = "none";
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

                document.getElementById("submit_upload").className = "btn btn-success";
                document.getElementById("submit_upload").disabled = false;

            }
        });
    </script>

    <script src="{{ asset('frontend/custom/cart_payment/other.js') }}"></script>{{-- js อื่นๆ --}}
@endsection
