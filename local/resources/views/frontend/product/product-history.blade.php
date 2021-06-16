@extends('frontend.layouts.customer.customer_app')
@section('css')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

@endsection
@section('conten')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-4">


                    <div class="col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 p-1">
                                <div class="form-group">
                                    <select class="form-control" id="dt_order_type">
                                        <option value="">จุดประสงค์การสั่งซื้อ(Type)</option>
                                        @foreach ($data['orders_type'] as $value)
                                            <option value="{{ $value->group_id }}">{{ $value->orders_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2 p-1">
                                <div class="form-group">
                                    <select class="form-control" id="dt_pay_type">
                                        <option value="">ชำระโดย(All)</option>
                                        @foreach ($data['pay_type'] as $value)
                                            <option value="{{ $value->id }}">{{ $value->detail }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 p-1">
                                <input class="form-control" type="date" id="s_date">
                            </div>
                            <div class="col-lg-3 col-md-3  p-1">
                                <input class="form-control" type="date" id="e_date">
                            </div>
                            <div class="col-lg-1 col-md-1 p-1">
                                <button id="search-form" class="btn btn-primary btn-block"> Seart </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-block">
                    <div class="table-responsive dt-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>เลขใบสั่งซื้อ</th>
                                    <th>TRACKING</th>
                                    <th>ยอดชำระ</th>
                                    <th>PV</th>
                                    {{-- <th>คงเหลือ</th> --}}
                                    <th>Active</th>
                                    <th>Type</th>
                                    <th>ชำระโดย</th>
                                    <th>สถานะ</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="row">
                        @foreach ($data['orders_type'] as $value)
                            <code style="color: #000">{!! $value->icon !!} {{ $value->orders_type }} </code>
                        @endforeach
                    </div>
                    <div class="row">
                        <code>กรณียกเลิกบิลสามารถทำได้ถายใน 30 นาที หลังจากบิลถูกอนุมัติและสามารถยกเลิกบิลได้ภายใน 23.00 น.
                            ของวันที่ทำรายการเท่านั้น</code>
                    </div>
                </div>

                <div id="modal_qr_recive"></div>

                <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                        <form action="{{ route('upload_slip') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Upload File Slip</h4>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">( JPG,PNG )</b>
                                                    </label>
                                                    <input type="file" name="file_slip" id="file_slip" class="form-control"
                                                        required="">
                                                    <input type="hidden" name="order_id" id="order_id" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                          <h5>Note</h5>
                                          <p>
                                            <code id="not"> </code></p>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect "
                                        data-dismiss="modal">Close</button>
                                    <button class="btn btn-success" type="submit" name="submit" id="submit_upload"
                                        value="upload">อัพโหลดหลักฐานการชำระเงิน</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="delete" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                        <form action="{{ route('delete_order') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="delete_title">ยืนยันการลบรายการ </h4>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <button type="button" class="btn btn-default waves-effect "
                                                data-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit" name="submit">Confirm</button>

                                            <input type="hidden" name="delete_order_id" id="delete_order_id" value="">
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

                <div class="modal fade" id="cancel" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                        <form action="{{ route('cancel_order') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="cancel_title">ยืนยันการยกเลิกรายการ </h4>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <button type="button" class="btn btn-default waves-effect "
                                                data-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit" name="submit">Confirm</button>
                                            <input type="hidden" name="cancel_order_id" id="cancel_order_id" value="">
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


                <div class="modal fade" id="promptpay" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-md" role="document">
                      <form action="{{ route('re_new_payment') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title" id="promptpay_title">ยืนยันการชำระเงินด้วย PromptPay </h4>
                              </div>

                              <div class="modal-body">
                                <img src="{{ asset('frontend/assets/images/thai_qr_payment.png') }}" class="img-fluid" alt="ชำระด้วย PromptPay">
                                          <input type="hidden" name="order_id" id="promptpay_order_id" value="">
                                          <input type="hidden" name="channel_list" id="promptpay_channel_list" value="promptpay">

                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                                <button class="btn btn-success md-auto" name="submit" value="PromptPay" type="submit">Confirm</button>
                            </div>
                          </div>
                      </form>
                  </div>
              </div>



                <div class="modal fade" id="truemoney" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-md" role="document">
                      <form action="{{ route('re_new_payment') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title" id="truemoney_title">ยืนยันการชำระเงินด้วย TrueMoney </h4>
                              </div>

                              <div class="modal-body">

                                <img src="{{ asset('frontend/assets/images/truemoneywallet-logo.png') }}" class="img-fluid" alt="ชำระด้วย TrueMoney">


                                          <input type="hidden" name="order_id" id="truemoney_order_id" value="">
                                          <input type="hidden" name="channel_list" id="truemoney_channel_list" value="truemoney">

                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                                <button class="btn btn-success md-auto" name="submit" value="TrueMoney" type="submit">Confirm</button>
                            </div>

                          </div>
                      </form>
                  </div>
              </div>



            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- data-table js -->

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

function qrcode(id,type='') {

            $.ajax({
                    url: '{{ route('modal_qr_recive_product') }}',
                    type: 'GET',
                    data: {
                        id: id,'type':type
                    },
                })
                .done(function(data) {
                    $('#modal_qr_recive').html(data);
                    var countdown = document.getElementById("time");
                    var close_modal = document.getElementById("close_modal");
                    $('#show_qr').modal('show');
                      //var i = $('#i').val();
                      var s = $('#s').val();
                      var id = $('#id').val();
                      var type_qr_modal = $('#type_qr_modal').val();
                      var timerId = '';
                      if(type_qr_modal == 'non'){
                        countdown.innerHTML = '00:00 <button class="btn btn-sm btn btn-success btn-outline-success btn-icon" onclick="refresh_time('+id+',\'refresh_time\')"> <i class="icofont icofont-refresh"></i> </button>';
                      }else{
                        var time = s; // 30 minutes converted to 1800 seconds
                        timerId = setInterval(function() {
                          //var countdown = i *  s * 1000;
                          time = time - 1;
                          var minute = Math.floor(parseInt(time / 60));
                          //console.log(minute);
                          var second = parseInt(time % 60);
                            minutes = minute < 10 ? "0" + minute : minute;
                            seconds = second < 10 ? "0" + second : second;
                            countdown.innerHTML = minutes + ' : ' + seconds +' <button class="btn btn-sm btn btn-success btn-outline-success btn-icon" onclick="refresh_time('+id+',\'refresh_time\','+timerId+')"> <i class="icofont icofont-refresh"></i> </button>';

                            if (second < 0) {
                              countdown.innerHTML = '00:00 <button class="btn btn-sm btn btn-success btn-outline-success btn-icon" onclick="refresh_time('+id+',\'refresh_time\','+timerId+')"> <i class="icofont icofont-refresh"></i> </button>';
                            }

                        }, 1000);

                      }

                      var close_modal_html = '<button type="button" class="btn btn-default waves-effect" onclick="time_stop('+timerId+')" >Close</button>';
                      close_modal.innerHTML = close_modal_html;

                })

                .fail(function() {
                    console.log("error");
                })
        }

        function refresh_time(id,type,timerId){
          clearInterval(timerId);
          $('#show_qr').modal('hide');
            qrcode(id,type);
        }


        function time_stop(timerId) {
          if(timerId){
            clearInterval(timerId);
          }
          $('#show_qr').modal('hide');
        }



        function upload_slip(order_id,not) {
            $('#order_id').val(order_id);
            $('#not').html(not);
        }

        function delete_order(order_id, code) {
            $('#delete_order_id').val(order_id);
            $('#delete_title').html('ยืนยันการลบรายการ (' + code + ')');

        }

        function cancel_order(order_id, code) {
            $('#cancel_order_id').val(order_id);
            $('#cancel_title').html('ยืนยันการยกเลิกรายการ (' + code + ')');
        }

        function re_new_payment_truemoney(order_id,code) {
            $('#truemoney_order_id').val(order_id);
            $('#truemoney_title').html('Repeat TrueMouney (' + code + ')');

        }

        function re_new_payment_promptpay(order_id,code) {
            $('#promptpay_order_id').val(order_id);
            $('#promptpay_title').html('Repeat PromtPay (' + code + ')');
        }

        $('#file_slip').change(function() {
            var fileExtension = ['jpg', 'png'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
                this.value = '';
                return false;
            }
        });

    </script>
    <script type="text/javascript">
        $(function() {
            var oTable = $('#multi-colum-dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{!! route('datable/history') !!}",
                    data: function(d) {
                        d.dt_order_type = $('#dt_order_type').val();
                        d.dt_pay_type = $('#dt_pay_type').val();
                        d.s_date = $('#s_date').val();
                        d.e_date = $('#e_date').val();
                    }
                },
                // type: "POST",
                columns: [{
                        data: 'date'
                    },
                    {
                        data: 'code_order'
                    },
                    {
                        data: 'tracking'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'pv_total'
                    },
                    // {
                    //     data: 'banlance'
                    // },
                    {
                        data: 'date_active'
                    },
                    {
                        data: 'type',
                        className: 'text-center'
                    },
                    {
                        data: 'pay_type_name'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action'
                    },
                ],
                order: [
                    [0, 'DESC']
                ]
            });

            $('#search-form').on('click', function(e) {
                oTable.draw();
                e.preventDefault();
            });

        });

    </script>

@endsection
