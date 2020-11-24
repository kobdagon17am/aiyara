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
						<a class="mobile-options">
							<i class="ti-more"></i>
						</a>
					</div>

					<div class="navbar-container container-fluid">
						<ul class="nav-left">
							<li>
								<div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
							</li>

							<li>
								<a href="#!" onclick="javascript:toggleFullScreen()">
									<i class="ti-fullscreen"></i>
								</a>
							</li>
						</ul>
						<ul class="nav-right">

							<li class="header">
								@if(request()->is('product-list/1') || request()->is('cart/1') || request()->is('product-detail/1/*') || request()->is('cart_payment/1'))
								<a href="{{route('cart',['type'=>1])}}">
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
								<a href="#!">
									<i class="fa fa-envelope-o"></i>
									<span class="badge bg-primary" style="font-size: 11px;border-radius: 100px;
									right: 11px;position: absolute;top: 10px;padding: 3px;">10</span>
								</a>
								<ul class="show-notification">
									<li>
										<h6>Notifications</h6>
										<label class="label label-danger">New</label>
									</li>

									<li>
										<div class="media">
											<img class="d-flex align-self-center img-radius" src="{{asset('frontend/assets/images/avatar-3.jpg')}}" alt="Generic placeholder image">
											<div class="media-body">
												<h5 class="notification-user">Sara Soudein</h5>
												<p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
												<span class="notification-time">30 minutes ago</span>
											</div>
										</div>
									</li>
								</ul>
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
											<i class="fa fa-image"></i> อัพโหลดรูปโปรไฟล์
										</a>
									</li>
									<li>
										<a href="{{route('profile_address')}}"><i class="fa fa-paper-plane-o"></i> แก้ไขข้อมูลส่วนตัว </a>

									</li>
									<li>
										<a href="{{route('docs')}}"><i class="fa fa-file-text"></i> สถานะเอกสารการสมัคร </a>

									</li>

                       {{--    <li>
                                        <a href="email-inbox.html">
                                            <i class="ti-email"></i> My Messages
                                        </a>
                                    </li> --}}

                                    <li>
                                    	<a href="{{route('logout')}}">
                                    		<i class="ti-layout-sidebar-left"></i> Logout
                                    	</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Sidebar inner chat start-->
            <div class="showChat_inner">
            	<div class="media chat-inner-header">
            		<a class="back_chatBox">
            			<i class="fa fa-chevron-left"></i> Josephin Doe
            		</a>
            	</div>
            	<div class="media chat-messages">
            		<a class="media-left photo-table" href="#!">
            			<img class="media-object img-radius img-radius m-t-5" src="{{asset('frontend/assets/images/avatar-3.jpg')}}" alt="Generic placeholder image">
            		</a>
            		<div class="media-body chat-menu-content">
            			<div class="">
            				<p class="chat-cont">I'm just looking around. Will you tell me something about yourself?</p>
            				<p class="chat-time">8:20 a.m.</p>
            			</div>
            		</div>
            	</div>
            	<div class="media chat-messages">
            		<div class="media-body chat-menu-reply">
            			<div class="">
            				<p class="chat-cont">I'm just looking around. Will you tell me something about yourself?</p>
            				<p class="chat-time">8:20 a.m.</p>
            			</div>
            		</div>
            		<div class="media-right photo-table">
            			<a href="#!">
            				<img class="media-object img-radius img-radius m-t-5" src="{{asset('frontend/assets/images/avatar-4.jpg')}}" alt="Generic placeholder image">
            			</a>
            		</div>
            	</div>
            	<div class="chat-reply-box p-b-20">
            		<div class="right-icon-control">
            			<input type="text" class="form-control search-text" placeholder="Share Your Thoughts">
            			<div class="form-icon">
            				<i class="fa fa-paper-plane"></i>
            			</div>
            		</div>
            	</div>
            </div>
            <!-- Sidebar inner chat end-->
            <div class="pcoded-main-container">
            	<div class="pcoded-wrapper">
            		<nav class="pcoded-navbar">
            			<div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
            			<div class="pcoded-inner-navbar main-menu">
<!-- 
	<div class="pcoded-navigation-label">สมาชิก</div> -->

	<ul class="pcoded-item pcoded-left-item" item-border="true" item-border-style="none" subitem-border="true">

		<li class="{{ (request()->is('home')) ? 'active' : '' }}">
			<a href="{{route('home')}}">
				<span class="pcoded-micon"><i class="fa fa-sitemap"></i><b>โครงสร้างสายงาน</b></span>
				<span class="pcoded-mtext">โครงสร้างสายงาน</span> 
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

{{-- 		<li class="{{ (request()->is('home_type_tree')) ? 'active' : '' }}">
			<a href="{{route('home_type_tree')}}">
				<span class="pcoded-micon"><i class="fa fa-sitemap"></i><b>โครงสร้างสายงาน</b></span>
				<span class="pcoded-mtext">โครงสร้างสายงาน</span> 
				<span class="pcoded-mcaret"></span>
				<span class="pcoded-badge label label-danger">NEW</span>
			</a>
		</li> --}}

		<li class="{{ (request()->is('allmember')) ? 'active' : '' }}">
			<a href="{{route('allmember')}}">
				<span class="pcoded-micon"><i class="fa fa-group"></i><b>Direct Sponsor</b></span>
				<span class="pcoded-mtext">Direct Sponsor</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="{{ (request()->is('profile')) ? 'active' : '' }}">
			<a href="{{route('profile')}}">
				<span class="pcoded-micon"><i class="fa fa-wpforms"></i><b>ข้อมูลส่วนตัว</b></span>
				<span class="pcoded-mtext">ข้อมูลส่วนตัว</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="pcoded-hasmenu {{ (request()->is('cart/*') || request()->is('product-detail/*') || request()->is('product-list/*')  || request()->is('product-status') || request()->is('product-history') || request()->is('cart_payment/*') || request()->is('cart-payment-history/*') ) ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
			<a href="javascript:void(0)">
				<span class="pcoded-micon"><i class="fa fa-shopping-cart"></i><b>สั่งซื้อสินค้า</b></span>
				<span class="pcoded-mtext">สั่งซื้อสินค้า</span>
				<span class="pcoded-mcaret"></span>
			</a>
			<ul class="pcoded-submenu">
			{{-- 	<li class="{{ (request()->is('product-list')) ? 'active' : '' }}">
					<a href="{{route('product-list')}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">สินค้าทั่วไป</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li> --}}


				<li class="{{ (request()->is('cart/1') || request()->is('product-list/1') || request()->is('product-detail/1/*') || request()->is('cart_payment/1') )  ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>1])}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">ทำคุณสมบัติ</span>
						@if(Cart::session(1)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_1" style="top:1px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(1)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/2') || request()->is('product-list/2') || request()->is('product-detail/2/*') || request()->is('cart_payment/2') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>2])}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">รักษาคุณสมบัติ</span>
						@if(Cart::session(2)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_2" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(2)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/3') || request()->is('product-list/3') || request()->is('product-detail/3/*') || request()->is('cart_payment/3') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>3])}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext" style="font-size: 13px">รักษาคุณสมบัติท่องเที่ยว</span>
						@if(Cart::session(3)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_3" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(3)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/4') || request()->is('product-list/4') || request()->is('product-detail/4/*') || request()->is('cart_payment/4') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>4])}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">เติม AiPocket</span>
						@if(Cart::session(4)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_4" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(4)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/5') || request()->is('product-list/5') || request()->is('product-detail/5/*') || request()->is('cart_payment/5') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>5])}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">Gift Voucher</span>
						@if(Cart::session(5)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_5" style="top:8px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(5)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

				<li class="{{ (request()->is('cart/6') || request()->is('product-list/6') || request()->is('product-detail/6/*') || request()->is('cart_payment/6') ) ? 'active' : '' }}">
					<a href="{{route('product-list',['type'=>6])}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">คอร์สอบรม</span>
						@if(Cart::session(6)->getTotalQuantity() != 0)
						<span class="pcoded-badge label label-info" id="count_cart_6" style="top:7px;font-size: 83%;"><i class="fa fa-shopping-cart"></i> {{ Cart::session(6)->getTotalQuantity() }}</span>
						@endif
						<span class="pcoded-mcaret"></span>
					</a>
				</li>
				
				{{-- <li class="{{ (request()->is('product-status')) ? 'active' : '' }}">
					<a href="{{route('product-status')}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">ติดตามสถานะ</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li> --}}
				<li class="{{ (request()->is('product-history') || request()->is('cart-payment-history/*')) ? 'active' : '' }}">
					<a href="{{route('product-history')}}">
						<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
						<span class="pcoded-mtext">ประวัติการสั่งซื้อ</span>
						<span class="pcoded-mcaret"></span>
					</a>
				</li>

			</ul>
		</li>

		<li class="{{ (request()->is('comission')) ? 'active' : '' }}">
			<a href="{{route('comission')}}">
				<span class="pcoded-micon"><i class="fa fa-line-chart"></i><b>รายงานคอมมิชชั่น</b></span>
				<span class="pcoded-mtext">รายงานคอมมิชชั่น</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="{{ (request()->is('walletindex')) ? 'active' : '' }}">
			<a href="{{route('walletindex')}}">
				<span class="pcoded-micon"><i class="fa fa-money"></i></i><b>Ai-Cash</b></span>
				<span class="pcoded-mtext">Ai-Cash</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>

		<li class="{{ (request()->is('ai-pocket')) ? 'active' : '' }}">
			<a href="{{route('ai-pocket')}}">
				<span class="pcoded-micon"><i class="ti-mobile"></i><b>Ai-Pocket</b></span>
				<span class="pcoded-mtext">Ai-Pocket</span>
				<span class="pcoded-mcaret"></span>
			</a>
		</li>



		<li class="pcoded-hasmenu {{ (request()->is('reward-history') || request()->is('benefits') 
		|| request()->is('course') || request()->is('travel') )  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
		<a href="javascript:void(0)">
			<span class="pcoded-micon"><i class="fa fa-gift"></i><b>สิทธิประโยชน์</b></span>
			<span class="pcoded-mtext">สิทธิประโยชน์</span>
			<span class="pcoded-mcaret"></span>
		</a>
		<ul class="pcoded-submenu">
			<li class="{{ (request()->is('reward-history')) ? 'active' : '' }}">
				<a href="{{route('reward-history')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">รางวัลเกียรติยศ</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

			<li class="{{ (request()->is('benefits')) ? 'active' : '' }}">
				<a href="{{route('benefits')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">ข้อมูลสิทธิประโยชน์</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

			<li class="{{ (request()->is('course')) ? 'active' : '' }}">
				<a href="{{route('course')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">คอร์สฝึกอบรม</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

			<li class="{{ (request()->is('travel')) ? 'active' : '' }}">
				<a href="{{route('travel')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">โปรโมชั่นท่องเที่ยวท่องเที่ยว</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

		</ul>
	</li>

	<li class="pcoded-hasmenu {{ (request()->is('24extra/promote') )  ? 'pcoded-trigger' : '' }}" dropdown-icon="style3" subitem-icon="style7">
		<a href="javascript:void(0)">
			<span class="pcoded-micon"><i class="fa fa-laptop"></i><b>Sale Page</b></span>
			<span class="pcoded-mtext">Sale Page</span>
			<span class="pcoded-mcaret"></span>
		</a>
		<ul class="pcoded-submenu">

			<li class="{{ (request()->is('24extra/promote')) ? 'active' : '' }}">
				<a href="{{url('24extra/promote')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">Sale Page 1</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

			<li class="{{ (request()->is('24extra/promote')) ? 'active' : '' }}">
				<a href="{{url('24extra/promote')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">Sale Page 2</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

			<li class="{{ (request()->is('24extra/promote')) ? 'active' : '' }}">
				<a href="{{route('course')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">Sale Page 3</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>

			<li class="{{ (request()->is('24extra/promote')) ? 'active' : '' }}">
				<a href="{{url('24extra/promote')}}">
					<span class="pcoded-micon"><i class="ti-angle-right"></i></span>
					<span class="pcoded-mtext">Sale Page 4</span>
					<span class="pcoded-mcaret"></span>
				</a>
			</li>


		</ul>
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

