 @extends('frontend.layouts.customer.customer_app')
 @section('css')

 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/bars-1to10.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/bars-horizontal.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/bars-movie.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/bars-pill.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/bars-reversed.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/bars-square.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery-bar-rating/css/css-stars.css')}}">
 <!-- slick css -->
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/slick-carousel/css/slick.css')}}">
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/slick-carousel/css/slick-theme.css')}}">
 <!-- themify-icons line icon -->
 <!-- ico font -->
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/icofont/css/icofont.css')}}">

 <link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/notification/notification.css')}}">
 <!-- Animate.css -->
 <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/animate.css/css/animate.css')}}">
 
 @endsection
 @section('conten')

 <div class="row">

  <div class="col-md-12">
    <!-- Product detail page start -->
    <div class="card product-detail-page">
      <div class="card-block">
        <div class="row">
          <div class="col-lg-5 col-xs-12">
            <div class="port_details_all_img row">
              <div class="col-lg-12 m-b-15">
                <div id="big_banner">

                  @if($product->image01)
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset($product->img_url.'/'.$product->image01)}}" alt="">
                  </div>
                  @endif
                  @if($product->image02)
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset($product->img_url.'/'.$product->image02)}}" alt="">
                  </div>
                  @endif
                  @if($product->image03)
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset($product->img_url.'/'.$product->image03)}}" alt="">
                  </div>
                  @endif


                </div>
              </div>
              <div class="col-lg-12 product-right">
                <div id="small_banner">

                 @if($product->image01)
                 <div>
                  <img class="img img-fluid" width="120" src="{{asset($product->img_url.'/'.$product->image01)}}" alt="">
                </div>
                @endif
                @if($product->image02)
                <div>
                  <img class="img img-fluid" width="120" src="{{asset($product->img_url.'/'.$product->image02)}}" alt="">
                </div>
                @endif
                @if($product->image03)
                <div>
                  <img class="img img-fluid" width="120" src="{{asset($product->img_url.'/'.$product->image03)}}" alt="">
                </div>
                @endif


              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-7 col-xs-12 product-detail" id="product-detail">
          <div class="row">
            <div>
               <!--  <div class="col-lg-12">
                  <span class="txt-muted d-inline-block">Product Code: <a href="#!"> PRDT1234 </a> </span>
                  <span class="f-right">Availability : <a href="#!"> In Stock </a> </span>
                </div> -->
                <div class="col-lg-12">


                  <h4 class="pro-desc">{{$product->product_name}}</h4>
                </div>
                <div class="col-lg-12">
                  <span class="txt-muted"> {{$product->title}} </span>    <p></p>

                </div>
            <!--     <div class="stars stars-example-css m-t-15 detail-stars col-lg-12">
                  <select id="product-view" class="rating-star" name="rating" autocomplete="off">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div> -->
                <div class="col-lg-12">
                  <h4>฿{{number_format($product->price,2)}} <b style="color:#00c454">[{{$product->pv}} PV]</b></h4>
                  <hr>
                  <p>{!! $product->product_detail !!}</p>
                  
                  <hr>

                </div>
                <div class="row col-lg-12">
                  <div class="col-sm-12"><h6 class="f-16 f-w-600 m-t-10 m-b-10">Quantity</h6></div>
                  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-6">
                   <div class="p-l-0 m-b-25">

                     <div class="input-group">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number shadow-none btn-sm" disabled="disabled" data-type="minus" data-field="quant[1]">
                          <span class="icofont icofont-minus m-0"></span>
                        </button>
                      </span>
                      <input type="text" id="quant" name="quant[1]" class="form-control input-number text-center" value="1">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number shadow-none btn-sm" data-type="plus" data-field="quant[1]">
                          <span class="icofont icofont-plus m-0"></span>
                        </button>
                      </span>
                    </div>
                  </div>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 notifications">
                 <button type="button" onclick="addcart({{$product->id}})" class="btn btn-primary waves-effect waves-light" data-type="success" data-from="top" data-align="right" style="margin-top: -2px">
                  <i class="icofont icofont-cart-alt f-16"></i><span class="m-l-10">ADD TO CART</span>
                </button>

              </div>
              <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12 text-center">
                 <a href="{{ route('product-list-2') }}" class="btn btn-warning" style="margin-top: -2px" type=""><i class="fa fa-cart-plus"></i> <span class="m-l-10">เลือกสินค้าเพิ่ม </span></a>
              </div>


            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <hr>
        <h2>{{$product->product_name}}</h2>
        <p>{{$product->title}}</p>
        <p>{!! $product->product_detail !!}</p>
        
      </div>
      

    </div>

  </div>
</div>
<!-- Product detail page end -->
</div>
</div>


@endsection
@section('js')
<script  src="{{asset('frontend/bower_components/jquery-bar-rating/js/jquery.barrating.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/rating/rating.js')}}"></script>
<!-- slick js -->
<script  src="{{asset('frontend/bower_components/slick-carousel/js/slick.min.js')}}"></script>
<!-- product detail js -->
<script  src="{{asset('frontend/assets/pages/product-detail/product-detail.js')}}"></script>
<script type="text/javascript">
  function addcart(id) { 
    <?php $img = $product->img_url.''.$product->image01; ?>
    var quantity = $('#quant').val();
    var name = '{{$product->product_name}}';
    var price = {{$product->price}};
    var pv = {{$product->pv}};
    var img = '{{$img}}';
    var title = '{{$product->title}}';
    var promotion = 'code promotion';
    $.ajax({
      url: '{{ route('add_cart') }}',
      type: 'get',
            // dataType: 'json',
            data: {id:id,quantity:quantity,name:name,price:price,pv:pv,img:img,title:title,promotion:promotion,type:'2'},
          })
    .done(function(data) {
     $('#count_cart').html(data);
     var count_cart = '<i class="fa fa-shopping-cart"></i> '+data;
     $('#count_cart_2').html(count_cart);
          //console.log(data);
        })
    .fail(function() {
      console.log("error");
    });
  }
</script>
<script  src="{{asset('frontend/assets/js/bootstrap-growl.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/notification/notification.js')}}"></script>
@endsection

