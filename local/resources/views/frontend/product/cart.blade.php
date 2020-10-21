 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 <style type="text/css" media="screen">
     table td[class*=col-], table th[class*=col-] {
        position: static !important;
        display: table-cell !important;
        float: none !important;
    }   
</style>
@endsection
@section('conten')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <!-- Shopping cart start -->
        <div class="card card-border-success">
            <div class="card-header">
                <div class="row">

                    <div class="col-md-8 col-sx-8">
                        <h4>Shopping Cart</h4>
                    </div>
                    <div class="col-md-4 col-sx-4 text-right">
                        <button class="btn btn-primary bt-sm" type="">เลือกสินค้า</button>
                    </div>
                </div>

            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dt-responsive table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th width="100">Quantity</th>
                                        <th width="100">Amount</th>
                                        <th>Pv</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $value)

                                    <tr id="items">
                                        <td class="text-center"><img src="{{asset($value['attributes']['img'])}}" class="img-fluid img-70" alt="tbl">
                                        </td>
                                        <td>
                                            <h6>{{ $value['name'] }}</h6>
                                            <span>{{ $value['attributes']['title'] }}</span>
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="" value="{{ $value['quantity'] }}">
                                        </td>
                                        <td><b>${{ $value['price'] }}</b></td>

                                        <td><b>{{$value['attributes']['pv']}}</b></td>
                                        <td class="text-center">
                                
                                            <button class="btn btn-warning btn-outline-warning btn-icon btn-sm"  onclick="delete_item({{ $value['id'] }})"><i class="icofont icofont-delete-alt"></i></button>
                                       
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

        </div>
        <div class="col-md-4">
            <div class="card card-border-success">
                <div class="card-header">
                    <h3>สรุปรายการสั่งซื้อ</h3>
                    {{--  <span class="label label-default f-right"> 28 January, 2015 </span> --}}
                </div>
                <div class="card-block">
                    <div class="col-md-12">
                        <table class="table table-responsive" >
                            <tr>
                                <td><strong>ยอดรวมจำนวน (3) ชิ้น</strong></td>
                                <td align="right"> ฿2,000.00</td>
                            </tr>
                            <tr>
                                <td><strong>ค่าจัดส่ง</strong></td>
                                <td align="right">฿60.00</td>
                            </tr>

                            <tr>
                                <td><strong>ยอดรวมทั้งสิ้น</strong></td>
                                <td align="right"><strong>฿2,060.00</strong>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>คะแนนที่ได้รับ</strong></td>
                                <td align="right"><strong>400 PV</strong></td>
                            </tr>


                        </table>
                        <div class="row" align="center">

                            <button class="btn btn-success btn-block" type="">ชำระเงิน</button>
                        </div>

                    </div>

                </div>
                <div class="card-footer">


                </div>
                <!-- end of card-footer -->
            </div>
            </div
        </div>
    </div>
</div>
<!-- Shopping cart start -->
</div>
</div>


<!-- End Contact Info area-->
@endsection
@section('js')

<script src="{{asset('frontend/assets/pages/forms-wizard-validation/form-wizard.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/inputmask.js')}}"></script>
<script src="{{asset('frontend/assets/pages/form-masking/jquery.inputmask.js')}}"></script>

<script src="{{asset('frontend/assets/pages/form-masking/form-mask.js')}}"></script>
<script src="{{asset('frontend/bower_components/jquery.cookie/js/jquery.cookie.js')}}"></script>

<script src="{{asset('frontend/bower_components/jquery.steps/js/jquery.steps.js')}}"></script>
<script src="{{asset('frontend/bower_components/jquery-validation/js/jquery.validate.js')}}"></script>
<!-- Validation js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script type="text/javascript">
    function delete_item(item_id){
        alert(item_id);
        $.ajax({
            url: '/path/to/file',
            type: 'GET',
            dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {item_id: 'item_id'},
        })
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    }
</script>

@endsection

