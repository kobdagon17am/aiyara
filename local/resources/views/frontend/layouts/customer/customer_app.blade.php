<!DOCTYPE html>
<html lang="en">

<head>
	<title>@yield('title')</title>
	<!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- Meta -->
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
<meta name="keywords" content="flat ui, admin Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
<meta name="author" content="codedthemes" />
<!-- Favicon icon -->

<link rel="icon" href="{{asset('frontend/assets/icon/logo_icon.png')}}" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
<!-- Required Fremwork -->

@yield('css')
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/bootstrap/css/bootstrap.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/typicons-icons/css/typicons.min.css')}}">
<!-- themify-icons line icon -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/themify-icons/themify-icons.css')}}">
<!-- ico font -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/icofont/css/icofont.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/icon/font-awesome/css/font-awesome.min.css')}}">
<!-- jpro forms css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/j-pro/css/demo.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/j-pro/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/j-pro/css/j-forms.css')}}">
<!-- Style.css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/css/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/css/jquery.mCustomScrollbar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/sweetalert/css/sweetalert.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/css/component.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jquery.steps/css/jquery.steps.css')}}">

</head>
<body>
	<!-- Pre-loader start -->
	<div class="theme-loader">
		<div class="loader-track">
			<div class="loader-bar"></div>
		</div>
	</div>
	<!-- Pre-loader end -->
	<div id="pcoded" class="pcoded">
		<div class="pcoded-overlay-box"></div>
		<div class="pcoded-container navbar-wrapper">
			<nav class="navbar header-navbar pcoded-header p-b-10" >
				<div class="navbar-wrapper">
					<div class="navbar-logo">
						<a class="mobile-menu" id="mobile-collapse" href="#!">
							<i class="ti-menu"></i>
						</a>

						<a href="{{route('home')}}">
							<img class="img-fluid" src="{{asset('frontend/assets/images/logo.png')}}"  width="140" alt="Theme-Logo" />
						</a>

                @if(request()->is('product-list/1') || request()->is('cart/1') || request()->is('product-detail/1/*') || request()->is('cart_payment/1'))
								<a href="{{route('cart',['type'=>1])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{Cart::session(1)->getTotalQuantity() }}</span>
								</a>

								@elseif(request()->is('product-list/2') || request()->is('cart/2') || request()->is('product-detail/2/*') || request()->is('cart_payment/2'))
								<a href="{{route('cart',['type'=>2])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{Cart::session(2)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/3') || request()->is('cart/3') || request()->is('product-detail/3/*') || request()->is('cart_payment/3'))
								<a href="{{route('cart',['type'=>3])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{Cart::session(3)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/4') || request()->is('cart/4') || request()->is('product-detail/4/*') || request()->is('cart_payment/4') )
								<a href="{{route('cart',['type'=>4])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{Cart::session(4)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/5') || request()->is('cart/5') || request()->is('product-detail/5/*') || request()->is('cart_payment/5'))
								<a href="{{route('cart',['type'=>5])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{Cart::session(5)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/6') || request()->is('cart/6') || request()->is('product-detail/6/*') || request()->is('cart_payment/6'))
								<a href="{{route('cart',['type'=>6])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{Cart::session(6)->getTotalQuantity() }}</span>
								</a>

								@else

								<a href="{{route('cart',['type'=>1])}}" class="menu-shope d-lg-none">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="m_count_cart" style="font-size: 11px;border-radius: 100px;
									right: -9px;position: absolute;top:-6px;padding: 3px;">{{ Cart::session(1)->getTotalQuantity() }}</span>
								</a>
								@endif


						<a class="mobile-options">
							<i class="ti-more"></i>
						</a>

					</div>

					<div class="navbar-container container-fluid">
						<ul class="nav-left">
							<li>
								<div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu text-success"></i></a></div>
							</li>

							<li>
								<a href="#!" onclick="javascript:toggleFullScreen()">
									<i class="ti-fullscreen text-success"></i>
								</a>
							</li>
						</ul>
						<ul class="nav-right">

							<li class="header menu-shope-pc">
								@if(request()->is('product-list/1') || request()->is('cart/1') || request()->is('product-detail/1/*') || request()->is('cart_payment/1'))
								<a href="{{route('cart',['type'=>1])}}" class="d-sm-block d-xs-block">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{Cart::session(1)->getTotalQuantity() }}</span>
								</a>

								@elseif(request()->is('product-list/2') || request()->is('cart/2') || request()->is('product-detail/2/*') || request()->is('cart_payment/2'))
								<a href="{{route('cart',['type'=>2])}}">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{Cart::session(2)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/3') || request()->is('cart/3') || request()->is('product-detail/3/*') || request()->is('cart_payment/3'))
								<a href="{{route('cart',['type'=>3])}}">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{Cart::session(3)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/4') || request()->is('cart/4') || request()->is('product-detail/4/*') || request()->is('cart_payment/4') )
								<a href="{{route('cart',['type'=>4])}}">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{Cart::session(4)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/5') || request()->is('cart/5') || request()->is('product-detail/5/*') || request()->is('cart_payment/5'))
								<a href="{{route('cart',['type'=>5])}}">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{Cart::session(5)->getTotalQuantity() }}</span>
								</a>
								@elseif(request()->is('product-list/6') || request()->is('cart/6') || request()->is('product-detail/6/*') || request()->is('cart_payment/6'))
								<a href="{{route('cart',['type'=>6])}}">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{Cart::session(6)->getTotalQuantity() }}</span>
								</a>

								@else

								<a href="{{route('cart',['type'=>1])}}">
									<i class="fa fa-shopping-cart"></i>
									<span class="badge bg-c-pink" id="count_cart" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{ Cart::session(1)->getTotalQuantity() }}</span>
								</a>
								@endif

							</li>

							<li class="header-notification">
								<?php
								$noti = \App\Helpers\Frontend::notifications(Auth::guard('c_user')->user()->id);


								?>

								<a href="#!">
									<i class="fa fa-envelope-o"></i>
									@if($noti['count'] > 0)
									<span class="badge bg-primary" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">{{ $noti['count'] }}</span>
									@endif
								</a>

								@if($noti['count'] > 0)
								<ul class="show-notification">
									<li>
										<h6>Notifications</h6>
										<label class="label label-danger">New</label>
									</li>

									@foreach($noti['data'] as $noti_value)

									<li>
										<a href="{{ route('message',['active'=>'inbox']) }}">
										<div class="media">
											<img class="d-flex align-self-center img-radius" src="{{asset('local/public/images/admin_aiyara.png')}}" alt="Generic placeholder image">
											<div class="media-body">
												<h5 class="notification-user">มีข้อความตอบกลับจากใส่ใจคะ</h5>
												<p class="notification-msg">{{ $noti_value->topics_question }}</p>
												<span class="notification-time">30 minutes ago</span>
											</div>
										</div>
									</a>
									</li>
									@endforeach
								</ul>
								@endif
							</li>

							<li class="user-profile header-notification">
								<a href="#!">
									@if(Auth::guard('c_user')->user()->profile_img)

									<img class="img-radius" width="100" src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" alt="User-Profile-Image">
									@else

									<img class="img-radius" width="100" src="{{asset('local/public/images/ex.png')}}" alt="User-Profile-Image">
									@endif

									<span>{{ Auth::guard('c_user')->user()->first_name }}</span>
									<i class="ti-angle-down"></i>
								</a>
								<ul class="show-notification profile-notification">
									<li>
										<a href="{{route('profile_img')}}">
											<i class="fa fa-image text-success"></i> อัพโหลดรูปโปรไฟล์
										</a>
									</li>
									<li>
										<a href="{{route('profile_address')}}"><i class="fa fa-paper-plane-o text-success"></i> แก้ไขข้อมูลส่วนตัว </a>

									</li>

									<li>
										<a href="{{route('docs')}}"><i class="fa fa-file-text text-success"></i> สถานะเอกสารการสมัคร </a>

									</li>
									<li>
										<a href="{{route('chage_password')}}"><i class="fa fa-key text-success"></i> แก้ไขรหัสผ่าน </a>

									</li>

									<li>
										<a href="{{route('logout')}}">
											<i class="ti-layout-sidebar-left text-success"></i> Logout
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</nav>


			<div class="pcoded-main-container">
				<div class="pcoded-wrapper">
					<nav class="pcoded-navbar">
						<div class="sidebar_toggle"><a href="#"><i class="icon-close icons text-success"></i></a></div>
						<div class="pcoded-inner-navbar main-menu" style="padding-bottom: 50px">
<!--
	<div class="pcoded-navigation-label">สมาชิก</div> -->

	<ul class="pcoded-item pcoded-left-item" item-border="true" item-border-style="none" subitem-border="true">

		<li class="{{ (request()->is('home')) ? 'active' : '' }}">
			<a href="{{route('home')}}">
				<span class="pcoded-micon"><i class="fa fa-sitemap text-success"></i><b>โครงสร้างสายงาน</b></span>
				<span class="pcoded-mtext">โครงสร้างสายงาน</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

{{-- 		<li class="{{ (request()->is('home_type_tree')) ? 'active' : '' }}">
			<a href="{{route('home_type_tree')}}">
				<span class="pcoded-micon"><i class="fa fa-sitemap text-success"></i><b>โครงสร้างสายงาน</b></span>
				<span class="pcoded-mtext">โครงสร้างสายงาน</span>
				<span class="pcoded-mcaret"></span>
				<span class="pcoded-badge label label-danger">NEW</span>
			</a>
		</li> --}}

		<li class="{{ (request()->is('direct-sponsor')) ? 'active' : '' }}">
			<a href="{{route('direct-sponsor')}}">
				<span class="pcoded-micon"><i class="fa fa-group text-success"></i><b>Direct Sponsor</b></span>
				<span class="pcoded-mtext">Direct Sponsor</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="{{ (request()->is('profile')) ? 'active' : '' }}">
			<a href="{{route('profile')}}">
				<span class="pcoded-micon"><i class="fa fa-wpforms text-success"></i><b>ข้อมูลส่วนตัว</b></span>
				<span class="pcoded-mtext">ข้อมูลส่วนตัว</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="pcoded-hasmenu {{ (request()->is('cart/*') || request()->is('product-detail/*') || request()->is('product-list/*')  || request()->is('product-status') || request()->is('cart_payment/*') ) ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="fa fa-shopping-cart text-success"></i><b>สั่งซื้อสินค้า</b></span>
				<span class="pcoded-mtext">สั่งซื้อสินค้า</span>
				<span class="pcoded-mcaret"></span>
			</a>
			<ul class="pcoded-submenu">
			{{-- 	<li class="{{ (request()->is('product-list')) ? 'active' : '' }}">
					<a href="{{route('product-list')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">สินค้าทั่วไป</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li> --}}


				<li class="{{ (request()->is('cart/1') || request()->is('product-list/1') || request()->is('product-detail/1/*') || request()->is('cart_payment/1') )  ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>1])}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">ทำคุณสมบัติ</span>
						@if(Cart::session(1)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_1" style="top:1px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(1)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/2') || request()->is('product-list/2') || request()->is('product-detail/2/*') || request()->is('cart_payment/2') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>2])}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">รักษาคุณสมบัติรายเดือน</span>
						@if(Cart::session(2)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_2" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(2)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/3') || request()->is('product-list/3') || request()->is('product-detail/3/*') || request()->is('cart_payment/3') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>3])}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext" style="font-size: 13px">รักษาคุณสมบัติท่องเที่ยว</span>
						@if(Cart::session(3)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_3" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(3)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/4') || request()->is('product-list/4') || request()->is('product-detail/4/*') || request()->is('cart_payment/4') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>4])}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">เติม Ai-Stockist</span>
						@if(Cart::session(4)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_4" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(4)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/5') || request()->is('product-list/5') || request()->is('product-detail/5/*') || request()->is('cart_payment/5') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>5])}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Gift Voucher</span>
						@if(Cart::session(5)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_5" style="top:8px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(5)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/6') || request()->is('product-list/6') || request()->is('product-detail/6/*') || request()->is('cart_payment/6') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>6])}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">คอร์สอบรม</span>
						@if(Cart::session(6)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_6" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(6)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

			</ul>
		</li>
{{--
		<li class="pcoded-hasmenu {{ (request()->is('product-history') || request()->is('cart-payment-history/*') || request()->is('course') )  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="ti-receipt text-success"></i><b>ประวัติการสั่งซื้อ</b></span>
				<span class="pcoded-mtext">ประวัติการสั่งซื้อ</span>
				<span class="pcoded-mcaret"></span>
			</a>

			<ul class="pcoded-submenu">
				<li class="{{ (request()->is('product-history')) ? 'active' : '' }}">
					<a href="{{route('product-history')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">สินค้า</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('course')) ? 'active' : '' }}">
					<a href="{{route('course')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Course/Event</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

			</ul>
		</li> --}}


		<li class="{{ (request()->is('product-history') || request()->is('product-history/*') || request()->is('cart-payment-history/*'))  ? 'active' : '' }}">
			<a href="{{route('product-history')}}">
				<span class="pcoded-micon"><i class="ti-receipt text-success"></i><b>ประวัติการสั่งซื้อ</b></span>
				<span class="pcoded-mtext">ประวัติการสั่งซื้อ</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>
		<li class="{{ (request()->is('course'))  ? 'active' : '' }}">
			<a href="{{route('course')}}">
				<span class="pcoded-micon"><i class="fa fa-black-tie text-success"></i><b>Course/Event</b></span>
				<span class="pcoded-mtext">Course/Event</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="pcoded-hasmenu {{ (request()->is('giftvoucher_history') || request()->is('gift_order_history') || request()->is('gift-cart-payment-history/*')  )  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="fa fa-gift text-success"></i><b>Gift Voucher</b></span>
				<span class="pcoded-mtext">Gift Voucher</span>
				<span class="pcoded-mcaret"></span>
			</a>

			<ul class="pcoded-submenu">
				<li class="{{ (request()->is('giftvoucher_history')) ? 'active' : '' }}">
					<a href="{{route('giftvoucher_history')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">ความเคลื่อนไหว</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('gift_order_history') || request()->is('gift-cart-payment-history/*')  ) ? 'active' : '' }}">
					<a href="{{route('gift_order_history')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">แลกสินค้า</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>
			</ul>
		</li>

	{{-- 	<li class="{{ (request()->is('comission')) ? 'active' : '' }}">
			<a href="{{route('comission')}}">
				<span class="pcoded-micon"><i class="fa fa-line-chart text-success"></i><b>รายงานคอมมิชชั่น</b></span>
				<span class="pcoded-mtext">รายงานคอมมิชชั่น</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li> --}}

		<li class="pcoded-hasmenu {{ (request()->is('commission_bonus_transfer') || request()->is('commission-per-day')
    || request()->is('commission_faststart*') || request()->is('commission_matching*') || request()->is('taxdata') 
    ||  request()->is('commission_bonus_transfer_aistockist') ||  request()->is('commission_bonus_transfer_af') ||  request()->is('commission_bonus_transfer_member'))  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="fa fa-line-chart text-success"></i><b>Commission</b></span>
				<span class="pcoded-mtext">Commission</span>
				<span class="pcoded-mcaret"></span>
			</a>

			<ul class="pcoded-submenu">
				<li class="{{ (request()->is('commission_bonus_transfer')) ? 'active' : '' }}">
					<a href="{{route('commission_bonus_transfer')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">รายงานการจ่ายโบนัส</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('commission-per-day') || request()->is('commission_faststart*') || request()->is('commission_matching*') ) ? 'active' : '' }}">
					<a href="{{route('commission-per-day')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">ความเหลื่อนไหวคะแนน และโบนัดรายวัน</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

        <li class="{{ (request()->is('commission_bonus_transfer_aistockist')) ? 'active' : '' }}">
					<a href="{{route('commission_bonus_transfer_aistockist')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">รายงานการโอนค่าคอมมิชชั่น Ai-Stockist</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

        <li class="{{ (request()->is('commission_bonus_transfer_af')) ? 'active' : '' }}">
					<a href="{{route('commission_bonus_transfer_af')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">รายงานการโอนค่าคอมมิชชั่น AF</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

        <li class="{{ (request()->is('commission_bonus_transfer_member')) ? 'active' : '' }}">
					<a href="{{route('commission_bonus_transfer_member')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">รายการโอนค่าคอมมิชชั่นสมาชิกนิติบุคคล</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

		<li class="{{ (request()->is('taxdata')) ? 'active' : '' }}">
					<a href="{{route('taxdata')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">หนังสือรับรองการหักภาษี ณ ที่จ่าย</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

			</ul>
		</li>


		<li class="{{ (request()->is('ai-stockist')) ? 'active' : '' }}">
			<a href="{{route('ai-stockist')}}">
				<span class="pcoded-micon"><i class="ti-wallet text-success"></i><b>Ai-Stockist</b></span>
				<span class="pcoded-mtext">Ai-Stockist</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="{{ (request()->is('ai-cash')) ? 'active' : '' }}">
			<a href="{{route('ai-cash')}}">
				<span class="pcoded-micon"><i class="fa fa-money text-success"></i><b>Ai-Cash</b></span>
				<span class="pcoded-mtext">Ai-Cash</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="pcoded-hasmenu {{ (request()->is('reward-history') || request()->is('benefits') || request()->is('travel') )  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="fa fa-gift text-success"></i><b>สิทธิประโยชน์</b></span>
				<span class="pcoded-mtext">สิทธิประโยชน์</span>
				<span class="pcoded-mcaret"></span>
			</a>
			<ul class="pcoded-submenu">
				<li class="{{ (request()->is('reward-history')) ? 'active' : '' }}">
					<a href="{{route('reward-history')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">รางวัลเกียรติยศ</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('travel')) ? 'active' : '' }}">
					<a href="{{route('travel')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">โปรโมชั่นท่องเที่ยวท่องเที่ยว</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

			</ul>
		</li>



		<li class="{{ (request()->is('news') || request()->is('news_detail/*')) ? 'active' : '' }}">
			<a href="{{route('news')}}">
				<span class="pcoded-micon"><i class="fa fa-newspaper-o text-success"></i><b>รวมข่าวสาร/กิจกรรม</b></span>
				<span class="pcoded-mtext">รวมข่าวสาร/กิจกรรม</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>



		<li class="pcoded-hasmenu {{ (request()->is('salepage/*') || request()->is('salepage/setting') || request()->is('commission_faststart') || request()->is('commission_matching') )  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="fa fa-chrome text-success"></i><b>Sale page</b></span>
				<span class="pcoded-mtext">Sale page</span>
				<span class="pcoded-mcaret"></span>
			</a>

			<ul class="pcoded-submenu">
				<?php $user_name= Auth::guard('c_user')->user()->user_name; ?>

				<li class="{{ (request()->is('salepage/page-1*') ) ? 'active' : '' }}">
					<a href="{{route('salepage/page-1',['user_name'=>$user_name])}}" target="_blank">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Sale page 1</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('salepage/page-1*') ) ? 'active' : '' }}">
					<a href="{{route('salepage/page-1',['user_name'=>$user_name])}}" target="_blank">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Sale page 2</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('salepage/page-1*') ) ? 'active' : '' }}">
					<a href="{{route('salepage/page-1',['user_name'=>$user_name])}}" target="_blank">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Sale page 3</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('salepage/page-1*') ) ? 'active' : '' }}">
					<a href="{{route('salepage/page-1',['user_name'=>$user_name])}}" target="_blank">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Sale page 4</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('salepage/setting')) ? 'active' : '' }}">
					<a href="{{route('salepage/setting')}}">
						<span class="pcoded-micon"><i class="ti-angle-right text-success"></i></span>
						<span class="pcoded-mtext">Setting</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>


			</ul>
		</li>
			<li class="{{ (request()->is('message/*')) ? 'active' : '' }}">
			<a href="{{route('message')}}">
				<span class="pcoded-micon"><i class="fa fa-send text-success"></i><b>ติดต่อ/สอบถาม</b></span>
				<span class="pcoded-mtext">ติดต่อ/สอบถาม</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>



	</ul>

</div>
</nav>
<div class="pcoded-content">
	<div class="pcoded-inner-content">
		<!-- Main-body start -->
		<div class="main-body">
			<div class="page-wrapper">
				<!-- Page-header start -->
				<div class="page-body">
					@yield('conten')
				</div>
			</div>
		</div>
		<!-- <div id="styleSelector"> </div> -->
	</div>
</div>
</div>
</div>
</div>
</div>

<!-- modernizr js -->
<script  src="{{asset('frontend/bower_components/jquery/js/jquery.min.js')}}"></script>
<script  src="{{asset('frontend/bower_components/jquery-ui/js/jquery-ui.min.js')}}"></script>
<script  src="{{asset('frontend/bower_components/popper.js/js/popper.min.js')}}"></script>
<script  src="{{asset('frontend/bower_components/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- j-pro js -->
<script  src="{{asset('frontend/assets/pages/j-pro/js/jquery.ui.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/j-pro/js/jquery.maskedinput.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/j-pro/js/jquery-cloneya.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/j-pro/js/autoNumeric.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/j-pro/js/jquery.stepper.min.js')}}"></script>
<!-- jquery slimscroll js -->
<script  src="{{asset('frontend/bower_components/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
<!-- modernizr js -->
<script  src="{{asset('frontend/bower_components/modernizr/js/modernizr.js')}}"></script>
<script  src="{{asset('frontend/bower_components/modernizr/js/css-scrollbars.js')}}"></script>
<!-- Custom js -->
<script  src="{{asset('frontend/assets/pages/j-pro/js/custom/currency-form.js')}}"></script>
<script src="{{asset('frontend/assets/js/pcoded.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/vertical/vertical-layout.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script  src="{{asset('frontend/assets/js/script.js')}}"></script>
<script  src="{{asset('frontend/bower_components/sweetalert/js/sweetalert.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

@include('frontend.layouts.flash-message')
@yield('js')
</body>

</html>



