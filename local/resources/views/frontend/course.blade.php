 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 
@endsection
@section('conten')


<!-- Start Contact Info area-->
<div class="contact-info-area mg-t-30">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                    <h3>การฝึกอบรม ของ <strong>24extra</strong></h3>
                    <select style="width: 200px; font-size: 18px;">
                        <?php 
                        for($i=2563;$i>=2555;$i--) { ?>
                            <option><?php echo $i; ?></option>
                        <?php } ?>
                    </select>



                    <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">ตรวจสอบ</button>
                    <br/>

                    <div class="table-responsive">
                        <table class="table table-striped table-responsive" >
                            <thead>
                                <tr class="info" >
                                    <th style="font-size: 12px; text-align:center;">#</th>
                                    <th style="font-size: 12px; text-align:center;">วันที่อบรม</th>
                                    <th style="font-size: 12px; text-align:left;">หัวข้อ</th>
                                    <th style="font-size: 12px; text-align:left;">สถานที่</th>
                                    <th style="font-size: 12px; text-align:center;">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($x=1;$x<=8;$x++) { ?>
                                    <tr style="text-align:center;">
                                        <td style="font-size: 12px; text-align:center;">{{ $x }}</td>
                                        <td style="font-size: 12px; text-align:center;">01/08/2563</td>
                                        <td style="font-size: 12px; text-align:left;">FIT ME season</td>
                                        <td style="font-size: 12px;  text-align:left;" >อาคารไอยเรศวร</td>
                                        <td style="font-size: 12px; text-align:center;">ผ่านการอบรม</td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>


                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Info area-->
    @endsection
    @section('js') 
    @endsection
