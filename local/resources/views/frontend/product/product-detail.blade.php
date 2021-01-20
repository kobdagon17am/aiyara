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
                  @foreach($img as $value_img)
                  <div class="port_big_img">
                    @if($type == '6')
                    <img class="img img-fluid" src="{{asset($value_img->img_url.'/'.$value_img->img_name)}}" alt="">
                    @else
                    <img class="img img-fluid" src="{{asset($value_img->img_url.'/'.$value_img->product_img)}}" alt="">
                    @endif
                  </div>
                  @endforeach
                </div>
              </div>
              <div class="col-lg-12 product-right">
                <div id="small_banner">
                 @foreach($img as $value_img_small)
                 <div>
                   @if($type == '6')
                   <img class="img img-fluid" width="120" src="{{asset($value_img->img_url.'/'.$value_img->img_name)}}" alt="">
                   @else
                   <img class="img img-fluid" width="120" src="{{asset($value_img->img_url.'/'.$value_img->product_img)}}" alt="">
                   @endif
                 </div>
                 @endforeach
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
                  @if($type=='6')
                  <?php 
                  if($couse_event->ce_type == 1){
                    $type_name = 'COURSE';  
                  }elseif($couse_event->ce_type == 2){
                    $type_name = 'EVENT';
                  }else{
                    $type_name = '';
                  }

                  $count_ce = \App\Helpers\Frontend::get_ce_register($couse_event->id);
                  ?>
                  <h4 class="pro-desc">{{$couse_event->ce_name}} <span class="label label-success" style="font-size: 15px"><i class="fa fa-users"></i> {{ $count_ce }}/{{$couse_event->ce_max_ticket}} </span></h4>
                  @else
                  

                  <h4 class="pro-desc">{{$product->product_name}} </h4>
                  @endif
                  
                  
                </div>
           {{--      <div class="col-lg-12">
                   {{$product->descriptions}}
                 </div> --}}
            <!--     <div class="stars stars-example-css m-t-15 detail-stars col-lg-12">
                  <select id="product-view" class="rating-star" name="rating" autocomplete="off">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div> -->

                
                @if($type == 6)
                <div class="col-lg-12">

                  <h4> {{number_format($couse_event->ce_ticket_price,2)}} <b style="color:#00c454"> [{{$couse_event->pv}} PV] </b></h4>
                  <hr>
                  {!! $couse_event->ce_place !!} 
                  <hr>
                </div>
                
                @else
                <div class="col-lg-12">
                  <h4>{!! $product->icon !!} {{number_format($product->member_price,2)}} <b style="color:#00c454">@if($type == 5)[0 PV]@else[{{$product->pv}} PV]@endif</b></h4>
                  <hr>
                  {!! $product->descriptions !!}
                  <hr>
                </div>
                @endif


                <div class="row col-lg-12">
                  <div class="col-sm-12"><h6 class="f-16 f-w-600 m-t-10 m-b-10">Quantity</h6></div>
                  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-6">
                   <div class="p-l-0 m-b-25">
                     <div class="input-group">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number shadow-none btn-sm btn-block" disabled="disabled" data-type="minus" data-field="quant[1]">
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
                @if($type == 6)
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-6">
                  <?php 
                  $ce_check = \App\Models\Frontend\CourseCheckRegis::check_register($couse_event->id); 
                  ?>
                  @if($ce_check['status'] == 'success')
                    <button type="button" onclick="addcart({{$couse_event->id}})" class="btn btn-primary waves-effect waves-light btn-block" data-type="success" data-from="top" data-align="right" style="margin-top: -2px">
                    <i class="icofont icofont-cart-alt f-16"></i><span class="m-l-10">ADD TO CART</span>
                  </button>
                  @else
                  <button type="button" class="btn btn-inverse waves-effect waves-light btn-block" data-type="success" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="{{ $ce_check['message'] }}" data-from="top" data-align="right" style="margin-top: -2px">
                    <i class="icofont icofont-cart-alt f-16"></i><span class="m-l-10">ADD TO CART</span>
                  </button>
                  @endif
                

                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12 text-center">
                 <a href="{{ route('product-list',['type'=>$type]) }}" class="btn btn-warning btn-block" style="margin-top: -2px" type=""><i class="fa fa-cart-plus"></i> <span class="m-l-10">เลือกสินค้าเพิ่ม </span></a>
               </div>
               @else
               <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 col-6">
                 <button type="button" onclick="addcart({{$product->products_id}})" class="btn btn-primary waves-effect waves-light btn-block" data-type="success" data-from="top" data-align="right" style="margin-top: -2px">
                  <i class="icofont icofont-cart-alt f-16"></i><span class="m-l-10">ADD TO CART</span>
                </button>

              </div>
              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col-12 text-center">
               <a href="{{ route('product-list',['type'=>$type]) }}" class="btn btn-warning btn-block" style="margin-top: -2px" type=""><i class="fa fa-cart-plus"></i> <span class="m-l-10">เลือกสินค้าเพิ่ม </span></a>
             </div>
             @endif



           </div>

         </div>
       </div>
     </div>
   </div>

   <div class="row">
    <div class="col-lg-12">
      <hr>

      {{-- <h2>{{$product->product_name}}</h2> --}}
      @if($type == 6)
      <p>{!! $couse_event->detail !!}</p>
      @else
      <p>{!! $product->products_details !!}</p>
      @endif
    </div>
  </div>

</div>
</div>
<!-- Product detail page end -->
</div>
</div>

<?php 

if($type == 6 ){
  $img = $img[0]->img_url.''.$img[0]->img_name; 

}else{
  $img = $img[0]->img_url.''.$img[0]->product_img;

}

?>

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
    var type = {{ $type }};
    if(type == 6 ){
     var type = {{ $type }};
     var quantity = $('#quant').val();
     var name = '{{@$couse_event->ce_name}}';
     var title = '{{@$couse_event->ce_place}}';
     var price = '{{@$couse_event->ce_ticket_price}}';

   }else{
    var quantity = $('#quant').val();
    var name = '{{@$product->product_name}}';
    var price = '{{@$product->member_price}}';
    var title = '{{@$product->title}}';
  }


  if(type == 5 ){
    var pv = 0;
  }else if(type == 6){
    var pv = '{{@$couse_event->pv}}';
  }else {
    var pv = '{{@$product->pv}}';
  }

  var img = '{{$img}}';
  var promotion = 'code promotion';

  $.ajax({
    url: '{{ route('add_cart') }}',
    type: 'get',
            // dataType: 'json',
            data: {id:id,quantity:quantity,name:name,price:price,pv:pv,img:img,title:title,promotion:promotion,type:'{{ $type }}'},
          })
  .done(function(data) {
   $('#count_cart').html(data);
   var count_cart = '<i class="fa fa-shopping-cart"></i> '+data;
   $('#count_cart_1').html(count_cart);
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

