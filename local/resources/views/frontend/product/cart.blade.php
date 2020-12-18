 @extends('frontend.layouts.customer.customer_app')
 @section('css')
{{--  <style type="text/css" media="screen">
   table td[class*=col-], table th[class*=col-] {
    position: static !important;
    display: table-cell !important;
    float: none !important;
}   
</style> --}}
@endsection
@section('conten')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <!-- Shopping cart start -->
        <div class="card card-border-success">
            <div class="card-header">
                <div class="row">

                    <div class="col-md-8 col-sx-8">
                        @if($type == 1)
                        <h4>รายการสั่งซื้อเพื่อทำคุณสมบัตร</h4>
                        @elseif($type == 2)
                        <h4>รายการสั่งซื้อเพื่อรักษาคุณสมบัติ</h4>
                        @elseif($type == 3)
                        <h4>รายการสั่งซื้อเพื่อรักษาคุณสมบัติท่องเที่ยว</h4>
                        @elseif($type == 4)
                        <h4>รายการสั่งซื้อเพื่อเติม AiPocket</h4>
                        @elseif($type == 5)
                        <h4>รายการสั่งซื้อเพื่อทำคุณสมบัตร</h4>
                        @elseif($type == 6)
                        @else
                        <h4 class="text-danger">ไม่ทราบจุดประสงค์การสั่งซื้อ</h4>
                        @endif

                        
                    </div>
                    <div class="col-md-4 col-sx-4 text-right">
                        <a href="{{route('product-list',['type'=>$type])}}" class="btn btn-primary bt-sm" type="">เลือกสินค้า</a>
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
                                    @foreach($bill['data'] as $value)

                                    <tr id="items">
                                        <td class="text-center"><a href="{{ route('product-detail',['type'=>$type,'id'=>$value['id']]) }}"><img src="{{asset($value['attributes']['img'])}}" class="img-fluid zoom img-70" alt="tbl"></a>
                                        </td>
                                        <td>
                                            <h6>{{ $value['name'] }}</h6>
                                            {{--  <span>{{ $value['attributes']['title'] }}</span> --}}
                                        </td>
                                        <td>
                                            <input type="number" min="1" onchange="quantity_change({{ $value['id'] }})" class="form-control" id="quantity_{{$value['id']}}" name="quantity" value="{{ $value['quantity'] }}">
                                        </td>
                                        <td><b>{{ $value['price'] }}</b></td>

                                        <td><b>{{$value['attributes']['pv']}}</b></td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-outline-warning btn-icon btn-sm"  onclick="cart_delete({{ $value['id'] }})"><i class="icofont icofont-delete-alt"></i></button>
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
                            <td><strong id="quantity_bill">ยอดรวมจำนวน ({{ $bill['quantity'] }}) ชิ้น</strong></td>
                            <td align="right"><strong id="price"> {{ $bill['price_total'] }} </strong></td>
                        </tr>


                        <tr>
                            <td><strong>คะแนนที่ได้รับ</strong></td>
                            <td align="right"><strong class="text-success" id="pv">{{ $bill['pv_total'] }} PV</strong></td>
                        </tr>


                    </table>
                    <div class="row" align="center">
                        @if($bill['quantity'] > 0)
                        <a href="{{ route('cart_payment',['type'=>$type]) }}" class="btn btn-success btn-block" type="">ชำระเงิน</a>
                        @endif
                        
                    </div>

                </div>

            </div>
            <div class="card-footer">


            </div>
            <!-- end of card-footer -->
        </div>
    </div>
</div>
 
<form action="" method="POST" id="cart_delete">
    @csrf
    <input type="hidden" id="data_id" name="data_id">
    <input type="hidden" id="type" name="type" value="{{ $type }}">
</form>

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

    function cart_delete(item_id){
        var url = '{{ route('cart_delete') }}';
        Swal.fire({ 
          title: 'Are you sure?',
          // text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed){
            $("#cart_delete" ).attr('action',url);
            $('#data_id').val(item_id);
            $("#cart_delete" ).submit();
            // Swal.fire(
            //   'Deleted!',
            //   'Your file has been deleted.',
            //   'success'
            //   )

        }
    })
  }

  function quantity_change(item_id){ 
    var qty = $('#quantity_'+item_id).val();
    var type = {{ $type }}; 

    $.ajax({
        url: '{{ route('edit_item') }}',
        type: 'POST',
        data: {'_token':'{{ csrf_token() }}',item_id:item_id,'qty':qty,'type':type},
    })
    .done(function(data) {
        $('#price').html(data['price_total']);
        $('#quantity_bill').html('ยอดรวมจำนวน ('+data['quantity']+') ชิ้น');
        $('#price_total').html(data['price_total']);
        $('#pv').html(data['pv_total']+' PV');
        console.log(data); 
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

