 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 
@endsection
@section('conten')
     <!-- Start Contact Info area-->
     <!-- Start Contact Info area-->
    <div class="contact-info-area mg-t-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                        <h3>เลือกช่องทางการชำระเงิน</h3>
                        <select class="form-control form-control-user"   id="productcode" class="form-control form-control-user" style=" font-size: 18px; width: 40%;" >
                        <option value="0" selected="">เงินสด</option>
                        <option value="2">บัตรเครดิต</option>
                        <option value="2">Wallet</option>
                        <option value="2">Gift Voucher</option>
                        </select><br/>
                        <h3>ที่อยู่ในการจัดส่งสินค้า</h3>
                        <input onclick="setadd(1)" style=" width: 18px; height: 18px;" type="radio" value="option1" name="b" checked=""  class="i-checks"><font style=" font-size: 18px;"> ที่อยู่ที่ลงทะเบียนไว้ </font><br>
                        <div id="member_address"><strong>2102/1 อาคารไอยเรศวร ซ.ลาดพร้าว 84 ถ.ลาดพร้าว แขวงวังทองหลาง เขตวังทองหลาง กรุงเทพ 10310</strong></div><br/>
                        <input onclick="setadd(2)" style=" width: 18px; height: 18px;" type="radio" value="option1" name="b" class="i-checks"><font style=" font-size: 18px;"> ที่อยู่อื่นๆ </font><br>
                        <textarea onkeyup="setadd(2)" style="resize: none; display: none;" id="w3mission" rows="5" cols="30"></textarea><br>
                      <br/>
                    </div>
                    
                    
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                       <h3>สรุปคำสั่งซื้อ</h3>
                       <p id="pay">ช่องทางการชำระเงิน - <strong>บัตรเครดิต</strong></p>
                       <p id="add">ที่อยู่การจัดส่ง - <strong>ที่อยู่ที่ลงทะเบียนไว้</strong></p>
                       <button onclick="window.location.href ='cart-payment';" class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;;">ยืนยันคำสั่งซื้อ</button>
                    </div>
            </div>
        </div>
    </div>
    <!-- End Contact Info area-->
 

@endsection
@section('js')
    <script type="text/javascript">
        function setadd(x) {
            var tx = document.getElementById("w3mission").value;
            if(x==1) { 
                document.getElementById("w3mission").style.display = "none";
                document.getElementById("member_address").style.display = "block";
                document.getElementById("add").innerHTML = "ที่อยู่การจัดส่ง - <strong>ที่อยู่ที่ลงทะเบียนไว้</strong>"; 
            }
            if(x==2) { 
                document.getElementById("w3mission").style.display ="block";
                document.getElementById("member_address").style.display = "none";
                document.getElementById("add").innerHTML = "ที่อยู่การจัดส่ง - <br/><strong>" + tx + "</strong>"; 
            }
       
        }
        function setpay(x) {
            if(x==1) { document.getElementById("pay").innerHTML = "ช่องทางการชำระเงิน - <strong>บัตรเครดิต</strong>"; }
            if(x==2) { document.getElementById("pay").innerHTML = "ช่องทางการชำระเงิน - <strong>Pocket</strong>"; }
            if(x==3) { document.getElementById("pay").innerHTML = "ช่องทางการชำระเงิน - <strong>Wallet</strong>"; }
        }
        function setadd(x) {
            var tx = document.getElementById("w3mission").value;
            if(x==1) { 
                document.getElementById("w3mission").style.display = "none";
                document.getElementById("add").innerHTML = "ที่อยู่การจัดส่ง - <strong>ที่อยู่ที่ลงทะเบียนไว้</strong>"; 
            }
            if(x==2) { 
                document.getElementById("w3mission").style.display ="block";
                document.getElementById("add").innerHTML = "ที่อยู่การจัดส่ง - <br/><strong>" + tx + "</strong>"; 
            }
        }
        document.getElementById("w3mission").style.display = "none";
    </script>
@endsection

