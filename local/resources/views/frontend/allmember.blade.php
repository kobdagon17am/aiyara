@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
    <style>
        .font-red{
            color: red;
        }
        .user-img{
            width: 50px; height: auto;
        }
         @media screen and (max-width: 1440px) {
            .user-pc{
                    margin-left: 22%;
                }
        }
        @media screen and (max-width: 1900px) {
            .user-pc{
                    margin-left: 27%;
                }
        }
        @media screen and (max-width: 992px) {
            .user-pc{
                    margin-left: 12%;
                }
        }
        @media screen and (max-width: 600px) {
          .user-pc{
                    margin-left: 0%;
                }
        }
    </style>
@endsection

    <div class="contact-info-area mg-t-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    
                    
                       <h4>ข้อมูลแนะนำตรง 50 ราย</h4>
                       <div class="table-responsive">
                        <table class="table table-striped table-responsive table-bordered" >
                            <tbody>
                                    <tr class="info" style='text-align:center;'>
                                        <th  rowspan="2" style="font-size: 12px;">#</th>
                                        <th rowspan="2" style="font-size: 12px;">Line</th>
                                        <th rowspan="2" style="font-size: 12px;">ID</th>
                                        <th rowspan="2" style="font-size: 12px;">Name/Buniness Name</th>
                                        <th rowspan="2" style="font-size: 12px;">Package</th>
                                        <th rowspan="2" style="font-size: 12px;">Upline</th>
                                        <th rowspan="2" style="font-size: 12px;">Personal Active</th>
                                        <th colspan="3" style="font-size: 12px;">Team Active</th>
                                        <th colspan="2" style="font-size: 12px;">Reward Bonus</th>
                                        <th colspan="2" style="font-size: 12px;">คุณวุฒิ</th>
                                        
                                    </tr>
                                    <tr class=" warning" style='text-align:center;'>
                                        <th  style="font-size: 12px;">A</th>
                                        <th  style="font-size: 12px;">B</th>
                                        <th  style="font-size: 12px;">C</th>
                                        <th  style="font-size: 12px;">ปัจจุบัน</th>
                                        <th  style="font-size: 12px;">สูงสุด</th>
                                        <th  style="font-size: 12px;">ปัจจุบัน</th>
                                        <th  style="font-size: 12px;">สูงสุด</th>
                                        
                                    </tr>
                                    @for ($i=1;$i<=50;$i++)
                                    <tr>
                                        <th style="font-size: 12px;">{{ $i }}</th>
                                        <th style="font-size: 12px;">A</th>
                                        <th style="font-size: 12px;">A10263</th>
                                        <th style="font-size: 12px;">24Extra</th>
                                        <th style="font-size: 12px;">Exclusive</th>
                                        <th style="font-size: 12px;">A10264/A</th>
                                        <th style="font-size: 12px;">2020-04-221</th>
                                         <th style="font-size: 12px;">10</th>
                                        <th style="font-size: 12px;">3</th>
                                        <th style="font-size: 12px;">5</th>
                                        <th style="font-size: 12px;">Star</th>
                                        <th style="font-size: 12px;">Five Star</th>
                                        <th style="font-size: 12px;"></th>
                                        <th style="font-size: 12px;">SAS</th>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        &nbsp;
                        </div> 
                    </div>
                </div>
        </div>
    </div>
    <!-- End Contact Info area-->
 @endsection
@section('js')

@endsection

 
