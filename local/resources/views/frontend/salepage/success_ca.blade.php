<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register AIYARA</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {{-- <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="codedthemes" /> --}}
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('frontend/assets/icon/logo_icon.png') }}" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/bootstrap/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/icon/themify-icons/themify-icons.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/icon/icofont/css/icofont.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/assets/icon/font-awesome/css/font-awesome.min.css') }}">
    <!-- Syntax highlighter Prism css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/pages/prism/prism.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets/css/pcoded-horizontal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/bower_components/select2/css/select2.min.css') }}" />
    <!-- Multi Select css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/multiselect/css/multi-select.css') }}">

    <style>
        .icons-alert:before {
            top: 11px;
        }

        .pcoded-main-container {
            margin-top: 0px !important;
        }

        .pcoded .pcoded-header[header-theme="theme5"] {
            background: linear-gradient(to right, #18c160, #18c160);
        }

    </style>

</head>
<!-- Menu horizontal icon fixed -->

<body class="horizontal-icon-fixed">

    <div id="pcoded" class="pcoded">

        <div class="pcoded-container">
            <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <img class="img-fluid" src="{{ asset('frontend/assets/images/logo.png') }}" width="140"
                            alt="Theme-Logo" />
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">

                <div class="pcoded-wrapper ">
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-4">

                                            <div class="card user-card">
                                                <div class="card-block">
                                                    <div class="usre-image">
                                                        <img src="{{ asset('local/public/images/ex.png') }}"
                                                            class="img-radius" width="100">
                                                    </div>
                                                    <h6 class="f-w-600 m-t-10 m-b-10">
                                                        {{ $data['data']['business_name'] }}</h6>
                                                    <h6 class="f-w-600 m-t-10 m-b-10">
                                                        {{ $data['data']['prefix_name'] }}
                                                        {{ $data['data']['first_name'] }}
                                                        {{ $data['data']['last_name'] }}</h6>
                                                    {{-- <p class="text-muted">Regiter Date | {{ data('Y-m-d',strtotime($data['data']['business_name'] )) }}</p> --}}

                                                    <ul class="list-unstyled activity-leval m-b-10">
                                                        <li class="active"></li>
                                                        <li class="active"></li>
                                                        <li class="active"></li>
                                                        <li class="active"></li>
                                                        <li class="active"></li>
                                                    </ul>

                                                    <div class="input-group input-group-primary">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>
                                                        <input type="text" class="form-control"
                                                            style="font-size: 18px;color: #000;font-weight: bold;"
                                                            value="{{ $data['data']['user_name'] }}" disabled="">
                                                    </div>

                                                    <div class="input-group input-group-primary">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-key"></i>
                                                        </span>
                                                        <input type="text" class="form-control"
                                                            style="font-size: 18px;color: #000;font-weight: bold;"
                                                            value="{{ $data['pass'] }}" disabled="">
                                                    </div>

                                                    <p class="m-t-15 text-muted"><b
                                                            class="text-success">ลงทะเบียนสำเร็จ</b>
                                                        <br>กรุณาบันทึกข้อมูลการลงทะเบียนเพื่อเพื่อใช้ในการเข้าสู่ระบบ
                                                    </p>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">

                                                                <a  href="{{ route('login') }}" class="btn btn-primary"><i
                                                                        class="fa fa-sitemap"></i> Login </a>


                                                        </div>
                                                    </div>
                                                    {{-- <div class="row justify-content-center user-social-link">
                                        <div class="col-auto"><a href="#!"><i class="fa fa-facebook text-facebook"></i></a></div>
                                        <div class="col-auto"><a href="#!"><i class="fa fa-twitter text-twitter"></i></a></div>
                                        <div class="col-auto"><a href="#!"><i class="fa fa-dribbble text-dribbble"></i></a></div>
                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="{{ asset('frontend/bower_components/jquery/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/jquery-ui/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/popper.js/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script src="{{ asset('frontend/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>
    <!-- modernizr js -->
    <script src="{{ asset('frontend/bower_components/modernizr/js/modernizr.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/modernizr/js/css-scrollbars.js') }}"></script>

    <!-- Syntax highlighter prism js -->
    <script src="{{ asset('frontend/assets/pages/prism/custom-prism.js') }}"></script>
    <!-- i18next.min.js -->
    <script src="{{ asset('frontend/bower_components/i18next/js/i18next.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js') }}"></script>
    <script
        src="{{ asset('frontend/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/jquery-i18next/js/jquery-i18next.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vertical/menu/menu-hori-fixed.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Custom js -->
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>

</body>

</html>
