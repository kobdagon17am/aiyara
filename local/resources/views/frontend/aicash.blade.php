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
                    <h5>เติม Ai-Cash</h5>

                    <div class="form-group row">
                        <div class="col-md-6 m-t-5">
                            <form action="{{ route('cart_payment_aicash') }}" id="cart_payment_aicash" method="post">
                                @csrf
                                <div class="input-group input-group-button">
                                    <input type="text" id="price" name="price" class="form-control autonumber"
                                        data-v-max="999999" placeholder="กรุณาใส่จำนวนเงิน">
                                    <span class="input-group-addon btn btn-primary" onclick="add_aicash()">
                                        <span class="">ทำรายการ</span>
                                    </span>
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
                    <h5>ประวัติการเติม Ai-Cash</h5>
                    {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
                </div>

                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="simpletable" class="table table-striped table-bordered nowrap dataTable">
                            <thead>
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>OrderCode</th>
                                    <th>Type</th>
                                    <th>Cash</th>
                                    <th>Detail</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($ai_cash as $value)

                                    <?php $i++; ?>
                                    <tr>
                                        {{-- <td>{{ $i }}</td> --}}
                                        <td><span
                                                style="font-size: 13px">{{ date('Y/m/d H:i:s', strtotime($value->created_at)) }}</span>
                                        </td>


                                        <td><span class="label label-inverse-{{ $value->css_class }}"><b
                                                    style="color: #000"> {{ $value->order_status }} </b></span></td>
                                        <td>{{ $value->code_order }}</td>
                                        <td>{{ $value->pay_type }} </td>
                                        <td><b class="text-success">{{ number_format($value->total_amt, 2) }}</b></td>
                                        <td>{{ $value->note }}</td>

                                        <td>

                                            @if ($value->order_status_id_fk == 1 || $value->order_status_id_fk == 3)
                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#upload_slip_aicash" onclick="upload_slip_aicash({{ $value->id }},'{{ $value->code_order }}')"><i class="fa fa-upload"></i> Upload </button>

                                                <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#delete"
                                                    onclick="delete_aicash({{ $value->id }},'{{ $value->code_order }}')"><i
                                                        class="fa fa-trash"></i></button>
                                            @elseif($value->order_status_id_fk == 7 )

                                              @if( strtotime('now') < strtotime($value->cancel_expiry_date) )
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#cancel_aicash" onclick="cancel_aicash({{ $value->id }},'{{ $value->code_order }}')">
                                                  <i class="fa fa-reply-all"></i> Cancel</button>
                                              @endif

                                            @else

                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>OrderCode</th>
                                    <th>Type</th>
                                    <th>Cash</th>
                                    <th>Detail</th>
                                    <th>#</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="modal fade" id="delete_aicash" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-md" role="document">
                            <form action="{{ route('delete_aicash') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delete_title_aicash" st>ยืนยันการลบรายการ  Ai-Cash</h5>
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
                                        <h5 class="modal-title" id="upload_title_aicash">Upload File Slip  Ai-Cash</h5>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">( JPG,PNG )</b>
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
                                        <button class="btn btn-success" type="submit" name="submit">อัพโหลดหลักฐานการชำระเงิน</button>
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
                          <thead>
                              <tr>
                                  <th>วันที่สั่งซื้อ</th>
                                  <th>เลขใบสั่งซื้อ</th>
                                  <th>TRACKING</th>
                                  <th>ยอดชำระ</th>
                                  <th>PV</th>
                                  <th>Ai-Cash เดิม</th>
                                  <th>ถูกใช้ไป</th>
                                  <th>Banlance</th>
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
                    <code>กรณียกเลิกบิลสามารถทำได้ถายใน 30 นาที หลังจากบิลถูกอนุมัติและสามารถยกเลิกบิลได้ภายใน 23.00 น. ของวันที่ทำรายการเท่านั้น</code>
                </div>
              </div>

              <div id="modal_qr_recive"></div>

              <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-md" role="document">
                      <form action="{{ route('upload_slip') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title">Upload File Slip Order</h5>
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
                                    <button class="btn btn-primary" type="submit" name="submit" >Confirm</button>

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
                              <h5 class="modal-title" id="cancel_title">ยืนยันการยกเลิกรายการ </h5>
                          </div>

                          <div class="modal-body">
                              <div class="form-group row">
                                  <div class="col-sm-12 text-center">
                                    <button type="button" class="btn btn-default waves-effect "
                                  data-dismiss="modal">Close</button>
                                  <button class="btn btn-primary" type="submit" name="submit" >Confirm</button>
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
                document.getElementById("cart_payment_aicash").submit();

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

        function upload_slip_aicash(aicash_id,code) {
            $('#aicash_id').val(aicash_id);
            $('#upload_title_aicash').html('Upload File Slip Ai-Cash (' + code + ')');
        }

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


  function upload_slip(order_id) {
      $('#order_id').val(order_id);
  }

  function delete_order(order_id,code){
      $('#delete_order_id').val(order_id);
      $('#delete_title').html('ยืนยันการลบรายการ Order ('+code+')');

  }

  function cancel_order(order_id,code){

      $('#cancel_order_id').val(order_id);
      $('#cancel_title').html('ยืนยันการยกเลิกรายการ Order ('+code+')');
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
              url: "{!! route('datatable_order_aicash') !!}",
              data: function(d) {
                  d.dt_order_type = $('#dt_order_type').val();
                  d.dt_pay_type = $('#dt_pay_type').val();
                  d.s_date = $('#s_date').val();
                  d.e_date = $('#e_date').val();
              }
          },

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
                  data: 'price',
                  className: 'text-right'
              },
              {
                  data: 'pv_total',
                  className: 'text-right'
              },
              {
                  data: 'aicash_old',
                  className: 'text-right'
              },
              {
                  data: 'aicash_price',
                  className: 'text-right'
              },
              {
                  data: 'aicash_banlance',
                  className: 'text-right'
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
