@extends('frontend.layouts.customer.customer_salepage')
@section('content')

<main role="main">

{{-- <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" >
      <img src="http://website.aiyara.co.th/wp-content/uploads/2020/08/S_AIbody-New-2.jpg"  class="img-fluid" alt="Responsive image">
      
    </div>
    
    <div class="carousel-item">

      <img src="http://website.aiyara.co.th/wp-content/uploads/2020/04/S_AIFACAD.jpg" width="100%" class="img-fluid" alt="Responsive image">

   
    </div>

  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div> --}}
<div class="row justify-content-center">
  <img src="http://website.aiyara.co.th/wp-content/uploads/2020/08/S_AIbody-New-2.jpg"  class="img-fluid" alt="Responsive image">
</div>

<div class="container marketing" style="margin-top: 10px">
  {{-- <hr class="featurette-divider"> --}}
  <div class="row featurette">
    <div class="col-md-7">
      <h2 class="featurette-heading"> First featurette heading. <span class="text-muted">It’ll blow your mind.</span></h2>
      <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
    </div>
    <div class="col-md-5">
     <img src="http://website.aiyara.co.th/wp-content/uploads/2020/10/สื่อออนไลน์-โฟมล้างหน้าไอลดา-01-copy.jpg" alt="..." class="img-thumbnail">
   </div>
 </div>

 <hr class="featurette-divider">

 <div class="row featurette">
  <div class="col-md-7 order-md-2">
    <h2 class="featurette-heading">Oh yeah, it’s that good. <span class="text-muted">See for yourself.</span></h2>
    <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
  </div>
  <div class="col-md-5 order-md-1">

    <img src="http://website.aiyara.co.th/wp-content/uploads/2020/10/06-S__13918235-โชค.jpg" alt="..." class="img-thumbnail">
  </div>
</div>

<hr class="featurette-divider p-2" >

<div class="row featurette">
  <div class="col-md-7">
    <h2 class="featurette-heading">And lastly, this one. <span class="text-muted">Checkmate.</span></h2>
    <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
  </div>
  <div class="col-md-5">
    <img src="http://website.aiyara.co.th/wp-content/uploads/2020/10/05-S__57335986-ก้อย.jpg" alt="..." class="img-thumbnail">
  </div>
</div>

<hr class="featurette-divider">

<!-- /END THE FEATURETTES -->

</div><!-- /.container -->


<!-- FOOTER -->
<footer class="container">
    {{-- <p class="float-right"><a href="#">Back to top</a></p>
    <p>&copy; 2017-2020 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p> --}}
    <div class="row justify-content-center">
      <img src="{{asset('frontend/salepage/img/FB.png')}}" style="margin: 5px;" class="img-fluid p-10" width="50" alt="..."> 
      <img src="{{asset('frontend/salepage/img/IG.png')}}" style="margin: 5px;" class="img-fluid" width="50" alt="..."> 
      <img src="{{asset('frontend/salepage/img/line.png')}}" style="margin: 5px;" class="img-fluid" width="50" alt="...">
      
    </div>

    <div class="row justify-content-center" style="margin-top: 10px">
      <button type="button" class="btn btn-success"><i class="fa fa-phone-alt"></i> <b>086 785 333</b></button>
    
     
   </div>
   
 </footer>
</main>
@endsection
