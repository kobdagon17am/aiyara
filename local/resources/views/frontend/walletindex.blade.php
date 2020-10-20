  
@extends('frontend.layouts.customer.customer_app')
@section('conten')

<!-- Start Contact Info area-->
<div class="contact-info-area mg-t-30" style="background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">
                 <h3>Wallet</h3>
             </div>
         </div>
     </div>
     <hr/>
     
     <div class="row">
        
        <div class="form-group row">
            <div class="col-lg-12 col-sm-12">
                <table class="table table-striped table-responsive " id="table1" name="table1">
                    <tr>
                        <td style=" width: 250px; text-align: center;"><h5 style="color: red;">วอลเล็ต(THB)</h5></td>
                        <td style=" width: 250px; text-align: center;"><h5>คอมมิชชั่น(THB)</h5></td>
                        <td style=" width: 250px; text-align: center;"><h5 style="color: #0080FF;">AiStockist(THB)</h5></td>
                    </tr>
                    <tr>
                        <td style=" width: 250px; text-align: center;"><h3>10,000.00</h3></td>
                        <td style=" width: 250px; text-align: center;"><h3>5,000.00</h3></td>
                        <td style=" width: 250px; text-align: center;"><h3>4,000.00</h3></td>
                    </tr>
                </table>
                
                <div id="topup_btn">
                    <button onclick="topupmoney()" class="btn btn-lg btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">เติมเงิน/Top Up</button>
                </div>
                <div id="topup_txt">
                    <input type="number" value="100" id="money" style="width: 75%; font-size: 18px;"/>
                    <button onclick="location.reload()" class="btn btn-lg btn-gray gray-icon-notika waves-effect" style=" background-color: #ffcc00; color: black">ดำเนินการต่อ</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
            <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                <select style=" font-size: 20px; width: 100%;" >
                    <option>รายการบันทึกทั้งหมด</option>
                    <option>Wallet</option>
                    <option>Commission</option>
                    <option>AiStockist</option>
                </select>
            </div>
        </div>




        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                <div class="table-responsive">
                    <table class="table table-striped table-responsive" >
                        <thead>
                            <tr class="info">
                                <th style="font-size: 12px;">วันที่</th>
                                <th style="font-size: 12px;">Type</th>
                                <th style="font-size: 12px;">รายการ</th>
                                <th style="font-size: 12px;">จำนวน</th>
                                <th style="font-size: 12px;">สถานะ</th>
                                <th style="font-size: 12px;">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10/16/2563</td>
                                <td>Wallet</td>
                                <td>เติมเงิน</td>
                                <td>8,000.00(THB)</td>
                                <td style="color:#00c454;">สำเร็จ</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>10/16/2563</td>
                                <td>Wallet</td>
                                <td>รายการซื้อ</td>
                                <td>3,000.00(THB)</td>
                                <td style="color: #ffcc00">รอดำเนินการ</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>10/16/2563</td>
                                <td>Wallet</td>
                                <td>รายการซื้อ</td>
                                <td>8,000.00(THB)</td>
                                <td style="color: black;">ทำรายการไม่สำเร็จ</td>
                                <td>-</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Contact Info area-->
@endsection
@section('js')
<script type="text/javascript">
    function topupmoney(){
        document.getElementById("topup_btn").style.display = 'none';
        document.getElementById("topup_txt").style.display = 'block';
    }
    $(document).ready(function () {
        
        document.getElementById("topup_btn").style.display = 'block';
        document.getElementById("topup_txt").style.display = 'none';

        

    });
    
    
    
</script>


@endsection
