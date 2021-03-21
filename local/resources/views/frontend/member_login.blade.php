<!DOCTYPE html>
<html lang="en">

<head>
	<title>Aiyara Login</title>
	<!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
  <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
  <meta name="author" content="codedthemes" />
  <!-- Favicon icon -->

  <link rel="icon" href="{{asset('frontend/assets/icon/logo_icon.png')}}" type="image/x-icon">
  <!-- Google font--><link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
  <!-- Required Fremwork -->
  <link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/bootstrap/css/bootstrap.min.css')}}">
  <!-- themify-icons line icon -->
  <link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/themify-icons/themify-icons.css')}}">
  <!-- ico font -->
  <link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/icofont/css/icofont.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/font-awesome/css/font-awesome.min.css')}}">
  <!-- Style.css -->
  <link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/css/style.css')}}">
  <style type="text/css">
  	.common-img-bg {
  		background: linear-gradient(45deg, #1dc561b3, #199e4f);
  	}
  </style>
</head>

<body class="fix-menu">
	<!-- Pre-loader start -->
	<div class="theme-loader">
		<div class="loader-track">
			<div class="loader-bar"></div>
		</div>
	</div>
	<!-- Pre-loader end  style="background-color: #1dc561" -->
	<section class="login p-fixed d-flex text-center bg-success common-img-bg">
		<!-- Container-fluid starts -->
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<!-- Authentication card start -->
					<div class="login-card card-block auth-body mr-auto ml-auto">
						<form class="md-float-material" action="login" method="POST">
							@csrf
							<div class="auth-box">
								<div class="row m-b-20">
									<div class="col-md-12">

										<img src="http://website.aiyara.co.th/wp-content/themes/aiplanetfresh/images/logo.png" alt="logo.png">
									</div>
								</div>


								<hr/>
								<div class="input-group">
									<input type="text" class="form-control" placeholder="User Name" name="username" value="A0000008" required="">
									<span class="md-line"></span>
								</div>
								<div class="input-group">
									<input type="password" class="form-control" placeholder="Password" name="password" value="123456" required="">
									<span class="md-line"></span>
								</div>
								<div class="row m-t-25 text-left">
									<div class="col-12">
										<div class="checkbox-fade fade-in-primary d-">
											<label>
												<input type="checkbox" value="">
												<span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
												<span class="text-inverse">Remember me</span>
											</label>
										</div>
										<div class="forgot-phone text-right f-right">
											<a href="" class="text-right f-w-600 text-inverse"> Forgot Password?</a>
										</div>
									</div>
								</div>
								<div class="row m-t-30">
									<div class="col-md-12">
										<button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Login</button>
									</div>
								</div>
								<hr/>

							</div>
						</form>
						<!-- end of form -->
					</div>
					<!-- Authentication card end -->
				</div>
				<!-- end of col-sm-12 -->
			</div>
			<!-- end of row -->
		</div>
		<!-- end of container-fluid -->
	</section>
	<!-- Warning Section Starts -->
	<!-- Older IE warning message -->
    <!--[if lt IE 10]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="../frontend/assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="../frontend/assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="../frontend/assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="../frontend/assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="../frontend/assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jquery -->
<script  src="{{asset('frontend/bower_components/jquery/js/jquery.min.js')}}"></script>
<script  src="{{asset('frontend/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
<script  src="{{asset('frontend/bower_components/popper.js/js/popper.min.js')}}"></script>
<script  src="{{asset('frontend/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- jquery slimscroll js -->
<script  src="{{asset('frontend/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
<!-- modernizr js -->
<script  src="{{asset('frontend/bower_components/modernizr/js/modernizr.js')}}"></script>
<script  src="{{asset('frontend/bower_components/modernizr/js/css-scrollbars.js')}}"></script>
<script  src="{{asset('frontend/assets/js/common-pages.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@include('frontend.layouts.flash-message')
</body>

</html>


