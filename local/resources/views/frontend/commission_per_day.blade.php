<?php
use App\Helpers\Frontend;
use App\Models\Frontend\DirectSponsor;

?>
@extends('frontend.layouts.customer.customer_app')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

@endsection
@section('conten')

<div class="row">
    <div class="col-md-12">
        <div class="card"> 

         <div class="card-header">
            <h4 class="m-b-10">ความเหลื่อนไหวคะแนน และโบนัสรายวัน รหัส xxxx</h4>

        </div> 
        <div class="card-block">

            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                    <thead>
                      <tr class="info" style='text-align:center;'>
                        <th class="text-center" rowspan="2" >วันที่</th>
                        <th class="text-center" rowspan="2" >ยอดสวนตัว</th>
                        <th class="text-center" rowspan="2" >ซื้อเพิ่ม</th>
                        <th class="text-center" rowspan="2" >ซื้อรักษา</th>
                        <th class="text-center" colspan="3" >คะแนนเข้าไหม่</th>
                        <th class="text-center" colspan="3" >คะแนนคงเหลือ</th>

                        <th class="text-center"  rowspan="2">Faststart</th>
                        <th class="text-center"  rowspan="2">Pro.</th>
                        <th class="text-center"  rowspan="2">TMB</th>
                        <th class="text-center"  rowspan="2">Booster</th>
                        <th class="text-center"  rowspan="2">Matching</th>
                        <th class="text-center"  rowspan="2">Pool</th>
                        <th class="text-center"  rowspan="2">Outime</th>
                        <th class="text-center"  rowspan="2">รวมโบนัส</th>


                    </tr>
                    <tr>
                        <th class="text-center"  style="font-size: 12px;">A</th>
                        <th class="text-center"  style="font-size: 12px;">B</th>
                        <th class="text-center"  style="font-size: 12px;">C</th>

                        <th class="text-center"  style="font-size: 12px;">A</th>
                        <th class="text-center"  style="font-size: 12px;">B</th>
                        <th class="text-center"  style="font-size: 12px;">C</th>

                        

                    </tr>
                </thead>
                <tbody>
                    @for($i=0;$i<20;$i++)
                    <tr>
                        <td>2019/01/20</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td> 
                        <td><a href="{{ route('commission_faststart') }}" class="text-primary">1000</a></td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        <td>1000</td>
                        
                    </tr>
                    @endfor

                </tbody>
            </table> 
        </div>

    </div>
</div> 
</div>
</div>

@endsection
@section('js')
<!-- data-table js -->


<script src="{{asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/jszip.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/pdfmake.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/vfs_fonts.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/data-table/js/data-table-custom.js')}}"></script>



@endsection





