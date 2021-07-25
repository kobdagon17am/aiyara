<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{  config('global.siteTitle') }}</title>

    
        
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
<!-- cropper CSS
		============================================ -->
    <link rel="stylesheet" href="css/cropper/cropper.min.css">
    <!-- meanmenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu/meanmenu.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- jvectormap CSS
		============================================ -->
    <link rel="stylesheet" href="css/jvectormap/jquery-jvectormap-2.0.3.css">
    <!-- notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/notika-custom-icon.css">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="css/wave/waves.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <style>
        .font-red{
            color: red;
        }
        .user-img{
            width: 50px; height: auto;
        }
         @media screen and (max-width: 1440px) {
            .user-pc{
                    margin-left: 22%;
                }
        }
        @media screen and (max-width: 1900px) {
            .user-pc{
                    margin-left: 27%;
                }
        }
        @media screen and (max-width: 992px) {
            .user-pc{
                    margin-left: 12%;
                }
        }
        @media screen and (max-width: 600px) {
          .user-pc{
                    margin-left: 0%;
                }
        }
    </style>
    </head>
    <body>

    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Start Header Top Area -->
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="logo-area">
                        <a href="#"><img  src="img/logo/logo.png" alt="" /></a>
                    </div>
                </div>
                @include('headertop')
            </div>
        </div>
    </div>
    <!-- End Header Top Area -->
    @include('mainmenu-1')
     <!-- Start Contact Info area-->
    <div class="contact-info-area mg-t-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                       <h2>ตั้งค่าข้อมูลส่วนตัว</h2>
                       <form action="upload.php" method="post" enctype="multipart/form-data">
                           <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="ชื่อ" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                           <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" >
                                </div>
                               แนบเอกสารการเปลี่ยนชื่อ
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">บันทึก</button>
                                </div>
                            </div>
                           </div>
                           <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="นามสกุล" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                           <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" >
                                </div>
                               แนบเอกสารการเปลี่ยนนามสกุล
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <div class="form-group">
                                    <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">บันทึก</button>
                                </div>
                            </div>
                           </div>
                           <hr/>
                           <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="หมายเลขโทรศัพท์" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="ระบุ หมายเลขโทรศัพท์ อีกครั้ง" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                               <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">บันทึก</button>
                           </div>
                           <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="Email" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" placeholder="ระบุ Email อีกครั้ง" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                               <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">บันทึก</button>
                           </div>
                           <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="password" class="form-control" placeholder="password" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st">
                                        <input type="password" class="form-control" placeholder="ระบุ password อีกครั้ง" name="fileToUpload" id="fileToUpload">
                                    </div>
                                </div>
                            </div>
                               <button class="btn btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: white;">บันทึก</button>
                           </div>
                           

                        <!--<input type="submit" value="Upload Image" name="submit">-->
                      </form>
                        <br/>
                    </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- End Contact Info area-->
    
    
    
    <!-- Start Footer area-->
    <div class="footer-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2018 
. All rights reserved. Template by Colorlib.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer area-->

     <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/meanmenu/jquery.meanmenu.js"></script>
    <!-- counterup JS
		============================================ -->
    <script src="js/counterup/jquery.counterup.min.js"></script>
    <script src="js/counterup/waypoints.min.js"></script>
    <script src="js/counterup/counterup-active.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- jvectormap JS
		============================================ -->
    <script src="js/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="js/jvectormap/jvectormap-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/sparkline-active.js"></script>
    <!-- flot JS
		============================================ -->
    <script src="js/flot/jquery.flot.js"></script>
    <script src="js/flot/jquery.flot.resize.js"></script>
    <script src="js/flot/jquery.flot.pie.js"></script>
    <script src="js/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/flot/jquery.flot.orderBars.js"></script>
    <script src="js/flot/curvedLines.js"></script>
    <script src="js/flot/flot-active.js"></script>
    <!-- knob JS
		============================================ -->
    <script src="js/knob/jquery.knob.js"></script>
    <script src="js/knob/jquery.appear.js"></script>
    <script src="js/knob/knob-active.js"></script>
    <!--  wave JS
		============================================ -->
    <script src="js/wave/waves.min.js"></script>
    <script src="js/wave/wave-active.js"></script>
    <!--  Chat JS
		============================================ -->
	<script src="js/chat/moment.min.js"></script>
    <script src="js/chat/jquery.chat.js"></script>
    <!--  todo JS
		============================================ -->
    <script src="js/todo/jquery.todo.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
	<!-- tawk chat JS
		============================================ -->
    <script src="js/tawk-chat.js"></script>
<!-- cropper JS
		============================================ -->
    <script src="js/cropper/cropper.min.js"></script>
    <script src="js/cropper/cropper-actice.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#img1').click(function(){
                    $('#myModalone').modal('show'); 
		});
          });
    </script>
    </body>
</html>
