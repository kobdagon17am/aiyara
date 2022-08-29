<header id="page-topbar">
    <div class="navbar-header" style="background-color:#00cc66;" >
        <div class="d-flex" >
            <!-- LOGO -->
            <div class="navbar-brand-box"  style="background-color:#00cc66;">
          <!--       <a href="backend/nisit" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="backend/images/logo.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="backend/images/logo-dark.png" alt="" height="17">
                    </span>
                </a> -->

                <a href="backend/index" class="logo logo-light">
                <!--     <span class="logo-sm">
                        <img src="backend/images/logo-light.svg" alt="" height="22">
						<h3 style="margin-top:20px; color: #ffffff;">N</h3>
                    </span> -->
                    <span class="logo-lg">
                        <img src="backend/images/logo.png" alt="" width="80%" height="80%">
						<!-- <h3 style="margin-top:20px; color: #ffffff;">Nisit DB</h3> -->
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex" >
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect">
                   {{-- \Auth::user()->locale->name??'Super Admin' --}}
                </button>
            </div>

            <div class="dropdown d-none d-lg-inline-block ml-1">
				<!--
					<button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
						<i class="bx bx-fullscreen"></i>
					</button>
				-->
            </div>

            <div class="dropdown d-inline-block">
				<!--
					<button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="bx bx-bell bx-tada"></i>
						<span class="badge badge-danger badge-pill">3</span>
					</button>
				-->
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <!--<a href="#!" class="small"> View All</a>-->
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="bx bx-cart"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">Your order is placed</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <img src="backend/images/users/avatar-3.jpg"
                                    class="mr-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">James Lemire</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">It will seem like simplified English.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="bx bx-badge-check"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">Your item is shipped</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <img src="backend/images/users/avatar-4.jpg"
                                    class="mr-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">Salena Layfield</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top">
                        <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle mr-1"></i> View More..
                        </a>
                    </div>
                </div>
            </div>

           <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @IF(Session::get('locale')=='th')
                    <img class="" src="backend/images/flags/flag_thai.jpg" alt="Language" height="16">
                    @ELSEIF(Session::get('locale')=='en')
                    <img class="" src="backend/images/flags/us.jpg" alt="Language" height="16">
                    @ELSEIF(Session::get('locale')=='lo')
                    <img class="" src="backend/images/flags/flag_laos.jpg" alt="Language" height="16">
                    @ELSE
                    <img class="" src="backend/images/flags/flag_thai.jpg" alt="Language" height="16">
                    @ENDIF
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <a href="{{ URL('/lang/th') }}" class="dropdown-item notify-item">
                        <img src="backend/images/flags/flag_thai.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Thai</span>
                    </a>
                    <!-- item-->
                    <a href="{{ URL('/lang/en') }}" class="dropdown-item notify-item">
                        <img src="backend/images/flags/us.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">US</span>
                    </a>
                     <!-- item-->
                    <a href="{{ URL('/lang/lo') }}" class="dropdown-item notify-item">
                        <img src="backend/images/flags/flag_laos.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Laos</span>
                    </a>
                    <a href="{{ URL('/lang/ca') }}" class="dropdown-item notify-item">
                      <img src="backend/images/flags/flag_cambodia.jpg" alt="user-image" class="mr-1" height="12"> <span class="align-middle">Cambodia</span>
                  </a>

                </div>
            </div>


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="backend/images/users/avatar-1.jpg"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ml-1">{{ \Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ url('/backend/permission/'.\Auth::user()->id.'/edit') }}"><i class="fa fa-user font-size-16 align-middle mr-1"></i> PROFILE </a>
                    <a class="dropdown-item" href="{{ url('/config-cache') }}"><i class="fas fa-sync font-size-16 align-middle mr-1"></i> Clear Cache</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="backend/logout"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> {{ __('Logout') }} </a>
                </div>
            </div>



        </div>
    </div>
</header>
