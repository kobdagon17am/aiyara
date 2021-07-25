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
                    <h4 class="m-b-10">หนังสือรับรองการหักภาษี ณ ที่จ่าย ตามมาตรา 50 ทวิ</h4>
                    {{-- <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 p-1">
                                <input class="form-control" type="date" id="startDate" value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-lg-3 col-md-3  p-1">
                                <input class="form-control" type="date" id="endDate" value="{{ date('Y-m-t') }}">
                            </div>
                            <div class="col-lg-1 col-md-1 p-1">
                                <button id="search-form" class="btn btn-primary btn-block"> Start </button>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="card-block">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>วันเริ่ม</th>
                                    <th>วันสิ้นสุด</th>
                                    <th>ค่า Commission</th>
                                    <th>ยอดภาษีหัก ณ ที่จ่าย</th>
                                    <th>งวด/ปีภาษี</th>
                                    <th>พิมพ์ 50 ทวิ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taxes as $tax)
                                    <tr>
                                        <td>{{ $tax->start_date }}</td>
                                        <td>{{ $tax->end_date }}</td>
                                        <td>{{ $tax->commission_cost }}</td>
                                        <td>{{ $tax->tax_amount }}</td>
                                        <td>{{ $tax->tax_year }}</td>
                                        <td>
                                            <a href="{{ URL::to('backend/taxdata/taxtvi/'.$tax->customer_id_fk) }}" target=_blank >
                                                View PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>                
            </div>
        </div>
    </div>
@endsection
