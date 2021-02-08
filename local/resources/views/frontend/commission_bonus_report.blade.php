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
            <h4 class="m-b-10">รายงานการจ่ายโบนัส</h4>
        </div> 

        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                    <thead>
                      <tr class="info" style='text-align:center;'>
                        <th class="text-center" >วันที่โอน</th>
                        <th class="text-center" >จำนวนรอบ</th>
                        <th class="text-center" >รวมโบนัส</th>
                        <th class="text-center" >ภาษี 5%</th>
                        <th class="text-center" >ค่าธรรมเนียมโอน</th>
                        <th class="text-center" >โอนสุทธิ</th>
                        <th class="text-center" >หมายเหตุ</th>
                        <th class="text-center" >รายละเอียด</th>
                    </tr>

                </thead>
                <tbody>
                    @for($i=0;$i<20;$i++)
                    <tr>
                        <td>2019/01/01</td>
                        <td>2</td>
                        <td> 0.00 </td>
                        <td> 0.00 </td>
                        <td> 30 </td>
                        <td> 0.00 </td> 
                        <td> โอนผ่านธนาคาร </td>
                        <td> <button class="btn btn-success btn-outline-success" data-toggle="modal" data-target="#large-Modal"><i class="fa fa-search"></i></button>

                         </td>
                    </tr>
                    @endfor

                </tbody>
            </table> 


            <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">รายงานการคำนวนโบนัสตามรอบของการโอน วันที่ 2021/01/01</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="card">
                    <div class="card-header">
                        {{-- <h5>รายงานการคำนวนโบนัสตามรอบของการโอน วันที่ 2021/01/01</h5> --}}
                     {{--   <span class="text-danger">*รหัสโปรโมชั่นละ 1 ชุด สามารถส่งต่อให้สมาชิกท่านอื่นๆได้ / ไม่สามารถใช้สิทธิ์กับรายการส่งเสริมการขายอื่นๆ รวมถึงการเติม AIPocket</span> --}}
                   </div>
                   <div class="card-block">
                    <div class="dt-responsive table-responsive">
                      <table id="simpletable" class="table table-striped table-bordered nowrap">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>FastStart</th>
                            <th>TMB</th>
                            <th>Booster</th>
                            <th>Reward</th>
                            <th>TeamMaker</th>
                            <th>Promotion</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    <tr>
                        <td>2010/04/23</td>
                        <td><b class="text-primary">550.00</b> </td>
                        <td> </td>
                       
                        <td>12/12/2020</td>
                        <td> </td>
                         <td> </td>
                          <td> </td>
                        <td>550.00</td>
                    </tr>
                      <tr>
                        <td>2010/04/23</td>
                        <td><b class="text-primary">550.00</b> </td>
                        <td> </td>
                       
                        <td>12/12/2020</td>
                        <td> </td>
                         <td> </td>
                          <td> </td>
                        <td>550.00</td>
                    </tr>
                      <tr>
                        <td>Total</td>
                        <td><b class="text-primary">550.00</b> </td>
                        <td> </td>
                       
                        <td>12/12/2020</td>
                        <td> </td>
                         <td> </td>
                          <td> </td>
                        <td>20,000.00 </td>
                    </tr>

                </tbody>
                                       {{--  <tfoot>
                                            <tr>
                                                <th>Code ticket</th>
                                                <th>Detail</th>
                                                <th>Start</th>
                                                <th>Expiry</th>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary waves-effect waves-light ">Save changes</button>
                  </div>
              </div>
          </div>
      </div>
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





