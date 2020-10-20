  @extends('frontend.layouts.customer.customer_app')
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
@section('conten')
     <!-- Start Contact Info area-->
    <div class="contact-info-area mg-t-30">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                        <h3>คุณสมบัติท่องเที่ยวของ <strong>24extra</strong></h3>
                        <select style="width: 200px; font-size: 18px;">
                            <?php 
                            for($i=2563;$i>=2555;$i--) { ?>
                            <option><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                        <select style="width: 200px; font-size: 18px;">
                            <option>มกราคม</option>
                            <option>กุมภาพันธ์</option>
                            <option>มีนาคม</option>
                            <option>เมษายน</option>
                            <option>พฤษภาคม</option>
                            <option>มิถุนายน</option>
                            <option>กรกฏาคม</option>
                            <option>สิงหาคม</option>
                            <option>กันยายน</option>
                            <option>ตุลาคม</option>
                            <option>พฤษจิกายน</option>
                            <option>ธันวาคม</option>
                        </select>
                        
                        
                        <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">ตรวจสอบ</button>
                        <br/>
                        
                       <div class="table-responsive">
                    <table class="table table-striped table-responsive" >
                                <thead>
                                    <tr class="info" >
                                        <th style="font-size: 12px; text-align:center;">#</th>
                                        <th style="font-size: 12px; text-align:center;">รายการ</th>
                                        <th style="font-size: 12px; text-align:center;">จำนวนเงิน</th>
                                        <th style="font-size: 12px; text-align:center;">คะแนน</th>
                                        <th style="font-size: 12px; text-align:center;">สถานะรายการ</th>
                                        <th style="font-size: 12px; text-align:center;">วันที่</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($x=1;$x<=4;$x++) { ?>
                                    <tr style="text-align:center;">
                                        <td style="font-size: 12px; text-align:left;">{{ $x }}</td>
                                        <td style="font-size: 12px; text-align:left;">ซื้อสินค้า</td>
                                        <td style="font-size: 12px;  text-align:right;" >1,000.00</td>
                                        <td style="font-size: 12px; text-align:right;">100 PV</td>
                                        <td style="font-size: 12px; color:#00c454;  text-align:center;" >สำเร็จ</td>
                                        <td style="font-size: 12px;  text-align:center;" >01/08/2563</td>
                                    </tr>
                                    <?php }
                                    for($x=5;$x<=8;$x++) { ?>
                                    <tr style="text-align:center;">
                                        <td style="font-size: 12px; text-align:left;">{{ $x }}</td>
                                        <td style="font-size: 12px; text-align:left;">Pocket</td>
                                        <td style="font-size: 12px;  text-align:right;" >1,000.00</td>
                                        <td style="font-size: 12px; text-align:right;">100 PV</td>
                                        <td style="font-size: 12px; color:#00c454;  text-align:center;" >สำเร็จ</td>
                                        <td style="font-size: 12px;  text-align:center;" >01/08/2563</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                    </table>
                           <div class="modal fade" id="myModalone1" role="dialog">
                                    <div class="modal-dialog modals-sm">
                                        <div class="modal-content">
                                            <div class="modal-body" style=" text-align: left;">
                                                    <div class="table-responsive">
                                                    <table class="table table-striped table-responsive" >
                                                        <thead>
                                                            <tr class="info" >
                                                                <th style="font-size: 12px; text-align:center;">ออเดอร์ ID</th>
                                                                <th style="font-size: 12px; text-align:center;">วันที่</th>
                                                                <th style="font-size: 12px; text-align:center;">ราคาสินค้า</th>
                                                                <th style="font-size: 12px; text-align:center;">ค่าคอมมิชชั่น</th>
                                                                <th style="font-size: 12px; text-align:center;">ชำระค่าคอมฯ</th>
                                                                <th style="font-size: 12px; text-align:center;">รอการตรวจสอบ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr >
                                                                <td style="font-size: 12px; text-align:center;">OR0001</td>
                                                                <td style="font-size: 12px; text-align:center;">11/06/2563</td>
                                                                <td style="font-size: 12px; text-align:center;">1000.00</td>
                                                                <td style="font-size: 12px; text-align:center;">100.00</td>
                                                                <td style="font-size: 12px; text-align:center; color:#00c454; ">สำเร็จ</td>
                                                                <td style="font-size: 12px; text-align:center; color:#00c454; ">ผ่านการตรวจสอบ</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           <div class="modal fade" id="myModalone2" role="dialog">
                                    <div class="modal-dialog modals-sm">
                                        <div class="modal-content">
                                            <div class="modal-body" style=" text-align: left;">
                                                    <div class="table-responsive">
                                                    <table class="table table-striped table-responsive" >
                                                        <thead>
                                                            <tr class="info" >
                                                                <th style="font-size: 12px; text-align:center;">ออเดอร์ ID</th>
                                                                <th style="font-size: 12px; text-align:center;">วันที่</th>
                                                                <th style="font-size: 12px; text-align:center;">ราคาสินค้า</th>
                                                                <th style="font-size: 12px; text-align:center;">ค่าคอมมิชชั่น</th>
                                                                <th style="font-size: 12px; text-align:center;">ชำระค่าคอมฯ</th>
                                                                <th style="font-size: 12px; text-align:center;">รอการตรวจสอบ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr >
                                                                <td style="font-size: 12px; text-align:center;">OR0001</td>
                                                                <td style="font-size: 12px; text-align:center;">11/06/2563</td>
                                                                <td style="font-size: 12px; text-align:center;">1000.00</td>
                                                                <td style="font-size: 12px; text-align:center;">100.00</td>
                                                                <td style="font-size: 12px; text-align:center; color:#ffcc00; ">ยังไม่ชำระ</td>
                                                                <td style="font-size: 12px; text-align:center; color:#ffcc00; ">รอตรวจสอบ</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <br/>
                    </div>
                    </div>
                </div>
        </div>
    </div>
    @endsection
    @section('js') 
    @endsection