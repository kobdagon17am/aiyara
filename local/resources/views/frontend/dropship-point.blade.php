<?php
use App\Helpers\Frontend;
$check_kyc = Frontend::check_kyc(Auth::guard('c_user')->user()->user_name);
?>
@extends('frontend.layouts.customer.customer_app')
@section('conten')
    <div class="row">
        <div class="col-md-3 col-xl-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h5 class="m-b-20">Dropship Point</h5>
                    <h3 class="text-right"><i
                            class="ti-wallet f-left"></i><span>{{ number_format(Auth::guard('c_user')->user()->drop_ship_bonus) }}
                            PV</span></h3>
                    <p class="m-b-0">{{trans('message.p_qualification Remaining Points')}}{{-- <span class="f-right">$542</span> --}}</p>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-xl-9">
            <div class="card">

                <div class="card-block">
                    <h6>ใช้ Dropship Point</h6>

                    <div class="form-group row">

                        <div class="col-md-6 m-t-5">

                          <div class="input-group input-group-button">
                              <input type="text" id="username" class="form-control" placeholder="รหัสสมาชิกที่ใช้">
                              <span class="input-group-addon btn btn-primary" onclick="check()">
                                  <span class="">{{trans('message.p_Ai-Cash active')}}</span>
                              </span>
                          </div>
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
                  <h5>Dropship Point</h5>
                  {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
              </div>
              <div class="card-block">
                <div class=" table-responsive">
                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                    </table>
                </div>
                <div class="row">
                  <code>{{trans('message.p_Ai-Stockist in case')}}</code>
              </div>

            </div>


          </div>
      </div>
  </div>

    <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <form action="{{ route('use_dropship') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{trans('message.p_Ai-Stockist use approve')}}</h4>
                    </div>

                    <div class="modal-body">
                        <div class="col-md-12 col-xl-12">
                            <div class="card bg-c-yellow order-card m-b-0">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 id="text_username" style="color: #000"></h5>
                                            <h6 class="m-b-0" id="name" style="color: #000"></h6>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="m-b-0" style="color: #000"><i class="fa fa-star p-2 m-b-0"></i>
                                                <b id="qualification_name"></b></h6>
                                        </div>
                                    </div>
                                    <hr class="m-b-5 m-t-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="m-b-0" style="color: #000">{{trans('message.p_qualification coll pont')}}</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="m-b-0 text-right" id="text_pv" style="color: #000"></h5>
                                        </div>
                                    </div>
                                    <hr class="m-b-5 m-t-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="m-b-0" style="color: #000"><b>{{trans('message.p_qualification Your monthly qualification status')}}</b></p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            {{-- <span class="label label-success" style="font-size: 12px">Active ถึง 14/09/2020
                                            </span> --}}
                                            <div id="pv_mt_active"></div>
                                        </div>
                                    </div>

                                    <hr class="m-b-5 m-t-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="m-b-0" style="color: #000"><b>{{trans('message.p_qualification travel status')}}</b></p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <div id="pv_tv_active"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <br>



                        <div class="col-md-12 col-xl-12">
                            <div class="card widget-statstic-card borderless-card">
                                <div class="card-header">
                                    <div class="card-header-left">
                                        {{-- <h4 id="text_username"></h4> --}}
                                        {{-- <p class="p-t-10 m-b-0 text-muted" id="name"> คุณ ชฎาพร1 พิกุล </p> --}}
                                    </div>
                                </div>
                                <div class="card-block">
                                    <i class="ti-wallet f-left st-icon bg-primary txt-lite-color"></i>
                                    <div class="text-left">
                                        {{-- <h3 class="d-inline-block text-success" id="text_pv"></h3> --}}
                                        {{-- <i class="fa fa-long-arrow-down text-danger f-24 m-l-15"></i>
                                    <span class="f-right bg-danger">-5%</span> --}}
                                        <div class="col-sm-12 m-t-5">
                                            <select class="form-control" name="type">
                                                @foreach ($type as $value)
                                                    <option value="{{ $value->group_id }}">{{ $value->orders_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 m-t-5">

                                            <input type="text" name="pv" class="form-control autonumber" data-v-max="99999"
                                                data-v-min="0" placeholder="จำนวน PV" required="">
                                        </div>
                                        <input type="hidden" name="username" id="input_username" value="">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>

                        <button class="btn btn-success" type="submit">Confirm</button>

                    </div>

                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="cancel_dropship" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-md" role="document">
          <form action="{{ route('cancel_dropship') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="cancel_title_aistockist">ยกเลิกรายการ Dropship Point</h5>
                  </div>

                  <div class="modal-body">
                      <div class="form-group row">
                          <div class="col-sm-12 text-center">
                              <button type="button" class="btn btn-default waves-effect "
                                  data-dismiss="modal">Close</button>
                              <button class="btn btn-primary" type="submit" name="submit">Confirm</button>
                              <input type="hidden" name="cancel_code" id="cancel_code" value="">
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
    var oTable;
    $(function() {
        oTable = $('#multi-colum-dt').DataTable({
          processing: true,
          serverSide: true,
          searching: true,
            ajax: {
              url: "{{ route('dt_dropship') }}",
                    dataType: "json",
                    type: "GET",
                    // data: function(d) {
                    //   // d.startDate = $('#startDate').val();
                    //   // d.endDate = $('#endDate').val();
                    // },
            },

            columns: [

                        {
                            "data": "created_at",
                            "name": "created_at",
                            "title": '<center>Date</center>',
                        },

                        {"data": "transection_code",
                        "name": "transection_code",
                        "title": '<center>Code</center>',
                        },

                        {"data": "code_order",
                        "name": "code_order",
                        "title": '<center>OrderCode</center>',
                        },
                        {
                            "data": "customer_id",
                            "name": "customer_id",
                            "title": '<center>Send</center>',
                        },
                        {
                            "data": "to_customer_id",
                            "name": "to_customer_id",
                            "title": '<center>Receive(UserName)</center>',
                        },
                        {
                            "data": "type",
                            "name": "type",
                            "title": '<center>Type</center>',
                        },
                        {
                            "data": "pv",
                            "name": "pv",
                            "title": '<center>Ai-Stockist</center>',
                        },
                        {
                            "data": "banlance",
                            "name": "banlance",
                            "title": '<center>Banlance</center>',
                        },

                        {
                            "data": "status",
                            "name": "status",
                            "title": '<center>Status</center>',
                        },

                        {
                            "data": "detail",
                            "name": "detail",
                            "title": '<center>Detail</center>',
                        },
                        {
                            "data": "action",
                            "name": "action",
                            "title": '<center>Action</center>',
                        },
                  ],order:[[0,'DESC']],
        });
        $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
            oTable.draw();
        });

        $('#search-form').on('click', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });
</script>

<script type="text/javascript">
  var oTable;
  $(function() {
      oTable = $('#simpletable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
          ajax: {
            url: "{{ route('dt_aistockist_panding') }}",
                  dataType: "json",
                  type: "GET",
                  // data: function(d) {
                  //   // d.startDate = $('#startDate').val();
                  //   // d.endDate = $('#endDate').val();
                  // },
          },

          columns: [

                      {
                          "data": "created_at",
                          "name": "created_at",
                          "title": '<center>Date</center>',
                      },
                      {"data": "order_code",
                      "name": "order_code",
                      "title": '<center>OrderCode</center>',
                      },
                      {
                          "data": "customer_id",
                          "name": "customer_id",
                          "title": '<center>Send</center>',
                      },
                      {
                          "data": "to_customer_id",
                          "name": "to_customer_id",
                          "title": '<center>Receive(UserName)</center>',
                      },
                      {
                          "data": "type",
                          "name": "type",
                          "title": '<center>Type</center>',
                      },
                      {
                          "data": "pv",
                          "name": "pv",
                          "title": '<center>Ai-Stockist</center>',
                      },
                      {
                          "data": "banlance",
                          "name": "banlance",
                          "title": '<center>Banlance</center>',
                      },
                      {
                          "data": "detail",
                          "name": "detail",
                          "title": '<center>Detail</center>',
                      },
                ],order:[[0,'DESC']],
      });
      $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
          oTable.draw();
      });

      $('#search-form').on('click', function(e) {
          oTable.draw();
          e.preventDefault();
      });
  });
</script>

<script type="text/javascript">
        function check() {
            //var url = '{{ route('cart_delete') }}';
            var username = $('#username').val();
            // alert(username);
            $.ajax({
                    url: '{{ route('check_customer_id') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        'user_name': username
                    }
                })
                .done(function(data) {


                    if (data['status'] == 'success') {
                        document.getElementById("text_username").innerHTML = data['data']['data']['business_name'] +
                            ' (' + data['user_name'] + ')';

                        document.getElementById("name").innerHTML = data[
                            'data']['data']['first_name'] + ' ' + data['data']['data']['last_name'];

                        document.getElementById("text_pv").innerHTML = data['data']['data']['pv'] + ' PV';
                        $("#input_username").val(data['data']['data']['user_name']);

                        document.getElementById("qualification_name").innerHTML = data['data']['data']['qualification_name'] ;

                        document.getElementById("pv_mt_active").innerHTML = data['data']['data']['pv_mt_active'];
                        document.getElementById("pv_tv_active").innerHTML = data['data']['data']['pv_tv_active'];



                        $("#large-Modal").modal();
                        //alert(data['status']);

                    } else {

                        Swal.fire({
                            title: data['message'],
                            // text: "You won't be able to revert this!",
                            icon: 'warning',
                            showConfirmButton: false,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //$( "#cart_delete" ).attr('action',url);
                                // $('#data_id').val(item_id);
                                //$( "#cart_delete" ).submit();
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )

                            }
                        })
                    }
                })
                .fail(function() {
                    console.log("error");
                })
        }


        function cancel_aistockist(id,code) {
          console.log(id,code);
            $('#cancel_code').val(code);
            $('#cancel_title_aistockist').html('ยืนยันการยกเลิกรายการ (' + code + ')');
        }



    </script>


@endsection
