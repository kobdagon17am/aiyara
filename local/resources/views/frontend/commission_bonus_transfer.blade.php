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

                <div class="card-header">
                    <h4 class="m-b-10">รายงานการจ่ายโบนัส  [{{ Auth::guard('c_user')->user()->user_name }}]</h4>
                </div>

                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                            <thead>
                                <tr class="info" style='text-align:center;'>
                                    <th class="text-center">วันที่โอน</th>
                                    <th class="text-center">จำนวนรอบ</th>
                                    <th class="text-center">รวมโบนัส</th>
                                    <th class="text-center">ภาษี 5%</th>
                                    <th class="text-center">ค่าธรรมเนียมโอน</th>
                                    <th class="text-center">โอนสุทธิ</th>
                                    <th class="text-center">หมายเหตุ</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>

                <div id="view_transfer"></div>
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
    <!-- Custom js -->
    <script type="text/javascript">

          var oTable = $('#multi-colum-dt').DataTable({
              processing: true,
              serverSide: true,
              searching: true,
              ajax: {
                  url: "{!! route('dt_commission_bonus_transfer') !!}",
                  type:'GET',
                  },
              // type: "POST",
              columns: [{data: 'bonus_transfer_date'},
                  {data: 'umber_round'},
                  {data: 'bonus_total'},
                  {data: 'tax'},
                  {data: 'fee'},
                  {data: 'price_transfer_total'},
                  {data: 'remark_transfer'},
                  {data: 'action'},
              ],order:[[0,'DESC']]
          });

      function modal_commission_transfer(date_transfer) {

        //alert(date_transfer);
            $.ajax({
                    url: '{{ route('modal_commission_transfer') }}',
                    type: 'GET',
                    data: {
                        date:date_transfer
                    },
                })
                .done(function(data) {
                    console.log("success");
                    $('#view_transfer').html(data);
                    $('#show_transfer').modal('show');

                })
                .fail(function() {
                    console.log("error");
                })
        }

    </script>




@endsection
