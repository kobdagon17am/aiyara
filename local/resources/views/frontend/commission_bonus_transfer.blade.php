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
                    <h4 class="m-b-10">{{trans('message.p_comission report')}}  [{{ Auth::guard('c_user')->user()->user_name }}]</h4>


                    <div class="col-md-12">
                      <div class="row">
                          <div class="col-lg-3 col-md-3 p-1">
                              <input class="form-control" type="date" id="s_date" value="{{ date('Y-m-01') }}">
                          </div>
                          <div class="col-lg-3 col-md-3  p-1">
                              <input class="form-control" type="date" id="e_date" value="{{ date('Y-m-t') }}">
                          </div>
                          <div class="col-lg-1 col-md-1 p-1">
                              <button id="search-form" class="btn btn-primary btn-block"> Start </button>
                          </div>
                      </div>
                  </div>
                </div>

                <div class="card-block">
                    <div class=" table-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">


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
                data: function(d) {
                    d.s_date = $('#s_date').val();
                    d.e_date = $('#e_date').val();
                }
                },
            // type: "POST",

            columns: [{
                        data: 'bonus_transfer_date',
                        title: '<center>{{trans('message.p_comission date')}}</center>',
                        className: 'text-center'
                    },

                    {
                        data: 'umber_round',
                        title: '{{trans('message.p_comission round')}}',
                        className: 'text-center'
                    },

                    {
                        data: 'bonus_total',
                        title: '<center>Commission</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'tax_percent',
                        title: '<center>% {{trans('message.p_comission percent')}}</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'tax',
                        title: '<center>{{trans('message.p_comission tax')}}</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'fee',
                        title: '<center>{{trans('message.p_comission fee')}}</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'price_transfer_total',
                        title: '<center>{{trans('message.p_comission net')}}</center>',
                        className: 'text-right'
                    },
                    {
                        data: 'bank_name',
                        title: '<center>{{trans('message.p_comission bank')}} </center>',
                        className: 'text-left'
                    },
                    {
                        data: 'bank_account',
                        title: '<center>{{trans('message.p_comission account')}}</center>',
                        className: 'text-left'
                    },

                    {
                        data: 'status_transfer',
                        title: '<center>{{trans('message.p_comission result')}}</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'remark_transfer',
                        title: '<center>{{trans('message.p_comission remark')}}</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'action',
                        title: '<center>#</center>',
                        className: 'text-left'
                    },
                ],order:[[0,'DESC']],


        });

        $('#search-form').on('click', function(e) {
            oTable.draw();
            e.preventDefault();
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
