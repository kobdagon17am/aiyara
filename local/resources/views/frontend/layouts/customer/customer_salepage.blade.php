<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>Sale Page 1</title>
 
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/carousel/">
    <link rel="icon" href="{{asset('frontend/assets/icon/logo_icon.png')}}" type="image/x-icon"> 
    
     <link href="{{asset('frontend/salepage/css/all.css')}}" rel="stylesheet">
    <!-- Bootstrap core CSS -->
<link href="{{asset('frontend/salepage/bootstrap.min.css')}}" rel="stylesheet"> 

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 2.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="{{asset('frontend/salepage/carousel.css')}}" rel="stylesheet">
  </head>
  <body>
    <header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-success ">
   <img class="img-fluid" src="{{asset('frontend/assets/images/logo.png')}}"  width="120" alt="Theme-Logo" />
 {{--    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button> --}}
    <button type="button" class="btn btn-outline-light d-md-none d-lg-none d-xl-none" style="margin-right: 16px;"><i class="fa fa-phone-alt"></i> <b> 087 433 6407</b></button>
    <div class="collapse navbar-collapse " id="navbarCollapse">
      <ul class="navbar-nav ml-auto">
     {{--    <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li> --}}

      </ul> 
    {{--    <form class="form-inline mt-2 mt-md-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>  --}}

      <button type="button" class="btn btn-outline-light"><i class="fa fa-phone-alt"></i> <b> 087 433 6407</b></button>
    </div>
  </nav> 
</header>
 @yield('content')

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"><\/script>')</script><script src="{{asset('frontend/salepage/bootstrap.bundle.min.js')}}"></script>
</html>

					
 