<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title> @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('backend/images/favicon.ico') }}">
    <base href="{{ asset('') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session('locale') == 'en')
        <style>
            .metismenu li a {
                font-size: 15px !important;
            }
        </style>
    @endif
    @include('backend.layouts.head')

    <style>
        .modal.in {
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        /* .modal-body {
            position: relative;
            padding: 20px;
            height: 200px;
            overflow-y: scroll;
        } */
    </style>

</head>

@section('body')
@show

<body data-sidebar="dark">

    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('backend.layouts.topbar')
        @include('backend.layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">


                    @yield('content')



                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('backend.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('backend.layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    @include('backend.layouts.footer-script')
</body>

</html>
