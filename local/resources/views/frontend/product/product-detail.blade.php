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
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-1.jpg')}}" alt="Big_ Details">
                  </div>
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-2.jpg')}}" alt="Big_ Details">
                  </div>
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-3.jpg')}}" alt="Big_ Details">
                  </div>
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-4.jpg')}}" alt="Big_ Details">
                  </div>
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-5.jpg')}}" alt="Big_ Details">
                  </div>
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-6.jpg')}}" alt="Big_ Details">
                  </div>
                  <div class="port_big_img">
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-l-7.jpg')}}" alt="Big_ Details">
                  </div>
                </div>
              </div>
              <div class="col-lg-12 product-right">
                <div id="small_banner">
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-1.jpg')}}" alt="small-details">
                  </div>
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-2.jpg')}}" alt="small-details">
                  </div>
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-3.jpg')}}" alt="small-details">
                  </div>
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-4.jpg')}}" alt="small-details">
                  </div>
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-5.jpg')}}" alt="small-details">
                  </div>
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-6.jpg')}}" alt="small-details">
                  </div>
                  <div>
                    <img class="img img-fluid" src="{{asset('frontend/assets/images/product-detail/pro-d-s-7.jpg')}}" alt="small-details">
                  </div>
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


                  <h4 class="pro-desc">{{$data['product']->product_name}}</h4>
                </div>
                <div class="col-lg-12">
                  <span class="txt-muted"> {{$data['product']->title}} </span>    <p></p>

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
                  <h4>฿{{number_format($data['product']->price,2)}} <b style="color:#00c454">[{{$data['product']->pv}} PV]</b></h4>
                  <hr>
                  <p>{!! $data['product']->product_detail !!}</p>
                  
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
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6">
                 <button type="button" onclick="addcart({{$data['product']->id}})" class="btn btn-primary waves-effect waves-light" style="margin-top: -2px">
                  <i class="icofont icofont-cart-alt f-16"></i><span class="m-l-10">ADD TO CART</span>
                </button>
              </div>


            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <hr>
        <h2>{{$data['product']->product_name}}</h2>
        <p>{{$data['product']->title}}</p>
        <p>{!! $data['product']->product_detail !!}</p>
        
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
      <?php $img = $data['product']->img_url.''.$data['product']->image01; ?>
      var quantity = $('#quant').val();
      var name = '{{$data['product']->product_name}}';
      var price = {{$data['product']->price}};
      var pv = {{$data['product']->pv}};
      var img = '{{$img}}';
      var title = '{{$data['product']->title}}';
      var promotion = 'code promotion';
            $.ajax({
            url: '{{ route('add_cart') }}',
            type: 'get',
            // dataType: 'json',
            data: {id:id,quantity:quantity,name:name,price:price,pv:pv,img:img,title:title,promotion:promotion},
        })
        .done(function(data) {
           date = $('#count_cart').html(data);
          //console.log(data);
        })
        .fail(function() {
          console.log("error");
        });
    }
</script>
@endsection

