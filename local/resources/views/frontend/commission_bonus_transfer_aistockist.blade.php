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
                    <h4 class="m-b-10">รายงานการโอนค่าคอมมิชชั่น Ai-Stockist [{{ Auth::guard('c_user')->user()->user_name }}]</h4>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 p-1">
                                <input class="form-control" type="date" id="startDate">
                            </div>
                            <div class="col-lg-3 col-md-3  p-1">
                                <input class="form-control" type="date" id="endDate">
                            </div>
                            <div class="col-lg-1 col-md-1 p-1">
                                <button id="search-form" class="btn btn-primary btn-block"> Start </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                            {{-- <thead>
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
                            </thead> --}}
                        </table>
                    </div>

                </div>

                <div id="view_transfer_aistockist"></div>
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

        var oTable;
        $(function() {
            oTable = $('#multi-colum-dt').DataTable({
              processing: true,
              serverSide: true,
              searching: true,
                ajax: {
                    url: '{{ route("dt_commission_bonus_transfer_aistockist") }}',
                    type: 'get',
                    data: function(d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                },


                columns: [{
                        data: 'action_date',
                        title: '<center>วันที่ทำรายการ</center>',
                        className: 'text-center'
                    },

                    // {
                    //     data: 'id',
                    //     title: 'UserName',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'cus_name',
                        title: '<center>ชื่อ-นามสกุล</center>',
                        className: 'text-left'
                    },
                    
                    {
                        data: 'commission',
                        title: '<center>Commission</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'tax_percent',
                        title: '<center>% หัก ณ ที่จ่าย</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'tax',
                        title: '<center>ภาษีหัก ณ ที่จ่าย</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'fee',
                        title: '<center>ค่าธรรมเนียม</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'destination_bank',
                        title: '<center>ธนาคารปลายทาง </center>',
                        className: 'text-left'
                    },
                    {
                        data: 'transferee_bank_no',
                        title: '<center>เลขที่บัญชี</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'amount',
                        title: '<center>จำนวนเงิน</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'transfer_result',
                        title: '<center>ผลการโอน</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'note',
                        title: '<center>หมายเหตุ</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'view',
                        title: '<center>view</center>',
                        className: 'text-left'
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


      function modal_commission_transfer_aistockist(date_transfer) {

        //alert(date_transfer);
            $.ajax({
                    url: '{{ route("modal_commission_bonus_transfer_aistockist") }}',
                    type: 'GET',
                    data: {
                        date: date_transfer
                    },
                })
                .done(function(data) {
                    console.log("success");
                    $('#view_transfer_aistockist').html(data);
                    $('#show_transfer_aistockist').modal('show');

                })
                .fail(function() {
                    console.log("error");
                })
        }



    </script>




@endsection
