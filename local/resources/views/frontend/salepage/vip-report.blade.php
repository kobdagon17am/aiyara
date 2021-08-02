@extends('frontend.layouts.customer.customer_app')

@section('conten')
<div class="row">
    <div class="col-md-12">
        <div class="card card-body">
            <h4 class="mb-4">VIP Report</h4>

            <div class="dt-responsive table-responsive">
                <table id="vip-datatable" class="table table-striped table-bordered  w-100">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Name</td>
                            <td>Mobile</td>
                            <td>Email</td>
                            <td>Member Since</td>
                        </tr>
                    </thead>
                </table>
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
    <!-- Custom js -->
    <script src="{{ asset('frontend/assets/pages/data-table/js/data-table-custom.js') }}"></script>

    <script>
        let vipDatatable = $('#vip-datatable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('salepage.vip-report-datatable') }}",
                type: 'GET'
            },
            columns: [
                {
                    data: 'id',
                },
                {
                    data: 'name'
                },
                {
                    data: 'tel_mobile'
                },
                {
                    data: 'email'
                },
                {
                    data: 'created_at'
                }
            ],
            order: [[ 4, "desc" ]]
        })
    </script>
@endsection
