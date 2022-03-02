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
            <form action="{{ route('cart_payment_transfer_aicash_submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="total_amt" value="{{ $ai_cash->total_amt }}">
                <input type="hidden" name="code_order" value="{{ $ai_cash->code_order }}">
                <input type="hidden" name="type" value="7">

                <div class="card card-border-success">
                    <div class="card-header p-3">

                            <h5>เติม Ai Cash</h5>

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

                                            <div class="col-md-6 col-lg-6">
                                              <div class="card">
                                                  <div class="card-block text-center">
                                                      <img src="{{ asset('frontend/assets/images/credit-logo.png') }}"
                                                          class="img-fluid" alt="credit">
                                                      <a class="btn btn-primary btn-md mt-2"
                                                          class="btn btn-primary btn-md mt-2" data-toggle="modal"
                                                          style="color: aliceblue" data-target="#confirm_credit">
                                                          ชำระด้วย Credit/Debit </a>

                                                      <div class="modal fade" id="confirm_credit" tabindex="-1"
                                                          role="dialog">
                                                          <div class="modal-dialog" role="document">
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                      <h4 class="modal-title">ยืนยันการชำระเงินด้วย
                                                                        Credit/Debit</h4>
                                                                      <button type="button" class="close"
                                                                          data-dismiss="modal" aria-label="Close">
                                                                          <span aria-hidden="true">&times;</span>
                                                                      </button>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                      {{-- <h5>Static Modal</h5> --}}
                                                                      <img src="{{ asset('frontend/assets/images/credit-logo.png') }}"
                                                                          class="img-fluid" alt="ชำระด้วย Credit/Debit">

                                                                  </div>
                                                                  <div class="modal-footer">
                                                                      <button type="button"
                                                                          class="btn btn-default waves-effect "
                                                                          data-dismiss="modal">Close</button>
                                                                      <button class="btn btn-success md-auto"
                                                                          name="submit" value="Credit"
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




                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-4">
            <div class="card card-border-success">
                <div class="card-header">
                    <h4>สรุปรายการเติม Ai Cash</h4>
                    {{-- <span class="label label-default f-right"> 28 January, 2015 </span> --}}
                </div>
                <div class="card-block" style="padding: 10px">
                    <div class="table-responsive p-3">
                        <table class="table">



                            <tr>
                                <td><strong>ยอดที่ต้องชำระ</strong></td>
                                <td align="right"><strong> {{ $ai_cash->total_amt }} </strong>

                                </td>
                            </tr>

                        </table>
                        <hr m-t-2>


                    </div>

                </div>
                <div class="card-footer">
                </div>

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


        function open_input(data) {
            var conten_4 = '<button class="btn btn-success btn-block" type="submit">ชำระเงิน</button>';

            if (data == '1') {

                document.getElementById("cart_payment_tranfer").style.display = "block";
                // document.getElementById("cart_payment_credit_card").style.display = "none";
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
                document.getElementById("cart_payment_mobile_banking").style.display = "none";
            }  else if (data == '4') {
                document.getElementById("cart_payment_tranfer").style.display = "none";
                // document.getElementById("cart_payment_credit_card").style.display = "none";
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
