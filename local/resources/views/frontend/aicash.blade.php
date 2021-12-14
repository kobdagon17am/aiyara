<?php
 use App\Helpers\Frontend;
 $check_kyc = Frontend::check_kyc(Auth::guard('c_user')->user()->user_name);
?>
@extends('frontend.layouts.customer.customer_app')
@section('conten')

    <div class="row">
        <div class="col-md-4 col-xl-4">
            <div class="card widget-statstic-card borderless-card">
                <div class="card-header">
                    <div class="card-header-left">
                        <h4>Ai-Cash</h4>
                        <p class="m-b-0">จำนวนเงินคงเหลือ{{-- <span class="f-right">$542</span> --}}</p>
                    </div>
                </div>
                <div class="card-block">
                    <i class="fa fa-money st-icon bg-success"></i>
                    <div class="text-left">
                        <h3 class="d-inline-block text-success">฿
                            {{ number_format(Auth::guard('c_user')->user()->ai_cash) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-8">
            <div class="card">
                <div class="card-block">
                    <h5 class="mb-2">เติม Ai-Cash</h5>

                    @if($check_kyc['status'] == 'fail')

                    {!! $check_kyc['html'] !!}
                    @else
                    <div class="form-group row">
                        <div class="col-md-6">
                            <form action="{{ route('cart_payment_aicash_submit') }}" id="cart_payment_aicash_submit" method="post">
                                @csrf
                                @if ($canAccess)
                                    <div class="input-group input-group-button">
                                        <input type="text" id="price" name="price" class="form-control autonumber"
                                            data-v-max="999999" placeholder="กรุณาใส่จำนวนเงิน">
                                        <span class="input-group-addon btn btn-primary" onclick="add_aicash()">
                                            <span class="">ทำรายการ</span>
                                        </span>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>ประวัติการเติม Ai-Cash</h5>
                    {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
                </div>

                <div class="card-block">
                    <div class="table-responsive dt-responsive">
                        <table id="dt-ajax-array" class="table table-striped table-bordered nowrap">


                        </table>
                    </div>

                    <div class="modal fade" id="delete_aicash" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-md" role="document">
                            <form action="{{ route('delete_aicash') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delete_title_aicash">ยืนยันการลบรายการ Ai-Cash</h5>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center">
                                                <button type="button" class="btn btn-default waves-effect "
                                                    data-dismiss="modal">Close</button>
                                                <button class="btn btn-primary" type="submit" name="submit">Confirm</button>

                                                <input type="hidden" name="delete_aicash_id" id="delete_aicash_id" value="">
                                            </div>

                                        </div>
                                    </div>

                                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect "
                            data-dismiss="modal">Close</button>

                    </div> --}}
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="modal fade" id="cancel_aicash" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-md" role="document">
                            <form action="{{ route('cancel_aicash') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancel_title_aicash">ยืนยันการยกเลิกรายการ Ai-Cash</h5>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center">
                                                <button type="button" class="btn btn-default waves-effect "
                                                    data-dismiss="modal">Close</button>
                                                <button class="btn btn-primary" type="submit" name="submit">Confirm</button>
                                                <input type="hidden" name="cancel_aicash_id" id="cancel_aicash_id" value="">
                                            </div>

                                        </div>
                                    </div>

                                    {{-- <div class="modal-footer">
                      <button type="button" class="btn btn-default waves-effect "
                          data-dismiss="modal">Close</button>

                  </div> --}}
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="modal fade" id="upload_slip_aicash" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-md" role="document">
                            <form action="{{ route('upload_slip_aicash') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="upload_title_aicash">Upload File Slip Ai-Cash</h5>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">( JPG,PNG
                                                                )</b>
                                                        </label>
                                                        <input type="file" name="file_slip_aicash" id="file_slip_aicash"
                                                            class="form-control" required="">
                                                        <input type="hidden" name="aicash_id" id="aicash_id" value="">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect "
                                            data-dismiss="modal">Close</button>
                                        <button class="btn btn-success" type="submit"
                                            name="submit">อัพโหลดหลักฐานการชำระเงิน</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>ประวัติการชำระด้วย Ai-Cash</h5>
                </div>
                <div class="card-block">
                    <div class="table-responsive dt-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                        </table>

                    </div>

                    <div class="row">
                        @foreach ($data['orders_type'] as $value)
                            <code style="color: #000">{!! $value->icon !!} {{ $value->orders_type }} </code>
                        @endforeach
                    </div>
                    <div class="row">
                        {{-- <code>กรณียกเลิกบิลสามารถทำได้ถายใน 30 นาที หลังจากบิลถูกอนุมัติและสามารถยกเลิกบิลได้ภายใน 23.00 น. ของวันที่ทำรายการเท่านั้น</code> --}}
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="modal fade" id="view_aicash" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Oder Code : <b id="code_order_aicash"></b></h4>
                </div>
                <div class="modal-body">
                    <div class="card bg-c-green order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">เติม Ai-Cash</h6>
                            <h2 class="text-right"><i class="ti-wallet f-left"></i><span id="price_add_aicash"></span></h2>


                            <p id="status"></p>
                            {{-- <p class="m-b-0">Stattus<span class="f-right" id="status">Success</span></p> --}}
                            <p class="m-b-0" style="margin-top: 31px;">Action Date <span class="f-right" id="date_aicash">
                                </span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('js')
    <!-- Masking js -->
    <script src="{{ asset('frontend/assets/pages/form-masking/inputmask.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/form-masking/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/form-masking/autoNumeric.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/form-masking/form-mask.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>
    <script type="text/javascript">
        function add_aicash() {
            var price = $('#price').val();
            if (price == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณาใส่จำนวนเงินที่ต้องการ',
                    // text: 'Something went wrong!',
                    // footer: '<a href>Why do I have this issue?</a>'
                })

            } else {
              var price = $('#price').val();
                Swal.fire({
                    title: 'ยืนยันการเติม Ai Cash',
                    text: 'ยอด '+price,
                    showCancelButton: true,
                    confirmButtonText: `Confirm`,
                    // denyButtonText: `Don't save`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      document.getElementById("cart_payment_aicash_submit").submit();
                    }
                })




            }
        }

        function delete_aicash(aicash_id, code) {

            $('#delete_aicash_id').val(aicash_id);
            $('#delete_title_aicash').html('ยืนยันการลบรายการ Ai-Cash (' + code + ')');
        }

        function cancel_aicash(aicash_id, code) {
            $('#cancel_aicash_id').val(aicash_id);
            $('#cancel_title_aicash').html('ยืนยันการยกเลิกรายการ Ai-Cash (' + code + ')');
        }

        function upload_slip_aicash(aicash_id, code) {
            $('#aicash_id').val(aicash_id);
            $('#upload_title_aicash').html('Upload File Slip Ai-Cash (' + code + ')');
        }
    </script>

    <script type="text/javascript">
        $(function() {
            var oTable = $('#dt-ajax-array').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{!! route('datatable_add_aicash') !!}",
                    //     data: function(d) {
                    //         d.dt_order_type = $('#dt_order_type').val();
                    //         d.dt_pay_type = $('#dt_pay_type').val();
                    //         d.s_date = $('#s_date').val();
                    //         d.e_date = $('#e_date').val();
                    //     }
                },
                columns: [{
                        data: 'created_at',
                        title: 'Date'
                    },
                    {
                        data: 'order_status',
                        title: 'Status'
                    },
                    {
                        data: 'code_order',
                        title: 'OrderCode'
                    },
                    {
                        data: 'pay_type',
                        title: 'Type',
                        className: 'text-center'
                    },
                    {
                        data: 'total_amt',
                        title: 'AiCash',
                        className: 'text-right'
                    },
                    {
                        data: 'aicash_banlance',
                        title: 'Banlance',
                        className: 'text-right'
                    },
                    {
                        data: 'note',
                        title: 'Detail'
                    },
                    {
                        data: 'action',
                        title: 'Action'

                    }

                ],
                order: [
                    [0, 'DESC']
                ]
            });

            // $('#search-form').on('click', function(e) {
            //     oTable.draw();
            //     e.preventDefault();
            // });

        });
    </script>

    <script type="text/javascript">
        $(function() {
            var oTable = $('#multi-colum-dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{!! route('datatable_order_aicash') !!}",
                    // data: function(d) {
                    //     d.dt_order_type = $('#dt_order_type').val();
                    //     d.dt_pay_type = $('#dt_pay_type').val();
                    //     d.s_date = $('#s_date').val();
                    //     d.e_date = $('#e_date').val();
                    // }
                },


                columns: [{
                        data: 'created_at',
                        title: 'Date'
                    },
                    {
                        data: 'code_order',
                        title: 'OrderCode',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'price_total',
                    //     title: 'ยอดรวม',
                    //     className: 'text-right'
                    // },

                    {
                        data: 'aicash_price',
                        title: 'Movement',
                        className: 'text-right'
                    },
                    // {
                    //     data: 'aicash_old',
                    //     title: 'Ai-Cash เดิม',
                    //     className: 'text-right'
                    // },
                    {
                        data: 'aicash_banlance',
                        title: 'Banlance',
                        className: 'text-right'
                    },
                    {
                        data: 'pay_type',
                        title: 'ชำระโดย',
                        className: 'text-center'
                    },

                    {
                        data: 'detail',
                        title: 'Detail'
                    },

                ],
                order: [
                    [0, 'DESC']
                ]
            });

            // $('#search-form').on('click', function(e) {
            //     oTable.draw();
            //     e.preventDefault();
            // });

        });

        function view_aicash(id) {
            $.ajax({
                    url: '{{ route('view_aicash') }}',
                    type: 'GET',
                    data: {id}
                })
                .done(function(data) {
                    //console.log(data['data']['css_class']);
                    $("#view_aicash").modal();
                    $('#code_order_aicash').html(data['data']['code_order']);
                    $('#price_add_aicash').html(data['price']);

                    if (data['data']['order_status_id_fk'] == '7') {
                        var status = 'Success';
                    } else {
                        var status = data['data']['order_status'];
                    }

                    $("#status").html('<span class="label f-right m-b-0 label-' + data['data']['css_class'] +
                        '" id="status" style="font-size: 14px">' + status + '</span>');
                    $('#date_aicash').html(data['date_aicash']);
                })
                .fail(function() {
                    console.log("error");
                })
        }
    </script>

@endsection
