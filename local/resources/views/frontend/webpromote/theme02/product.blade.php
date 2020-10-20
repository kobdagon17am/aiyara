<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    @include('inc_header')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">

</head>

<style>
    .flex-control-paging li a.flex-active {
        background: #fff;
        width: 12px;
        height: 12px;
        line-height: 28px;
    }

    .flex-direction-nav .flex-next {
        display: none;
    }

    .flex-direction-nav {
        display: none;
    }

    .flex-control-nav {
        bottom: 40px;
    }

    .wrap_slidecaption {
        position: absolute;
        top: 40%;
        width: 100%;
        text-align: center;
        -ms-transform: translate(0, -50%);
        -webkit-transform: translate(0, -50%);
        transform: translate(0, -50%);
    }

    .flexslider {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        border: 0px;
        overflow: hidden;
        border-radius: 0;
    }

    .flexslider-container,
    .flexslider .slides,
    .flex-viewport {
        height: 100%;
    }

    .flexslider .slides>li {
        background-position: center;
        height: 100%;
        width: 100%;
        display: none;
        -webkit-backface-visibility: hidden;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        position: relative;
    }

    .slidecaption {
        position: relative;
        opacity: 0;
    }

    .slideleft.actioncaption {
        -webkit-animation-name: slideInLeft;
        -moz-animation-name: slideInLeft;
        -o-animation-name: slideInLeft;
        animation-name: slideInLeft;
        -webkit-animation-duration: 1.5s;
        -moz-animation-duration: 1.5s;
        -o-animation-duration: 1.5s;
        animation-duration: 1.5s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: 1;
        animation-fill-mode: forwards;
    }

    .slideright.actioncaption {
        -webkit-animation-name: slideInRight;
        -moz-animation-name: slideInRight;
        -o-animation-name: slideInRight;
        animation-name: slideInRight;
        -webkit-animation-duration: 1.5s;
        -moz-animation-duration: 1.5s;
        -o-animation-duration: 1.5s;
        animation-duration: 1.5s;
        animation-timing-function: ease-in-out;
        animation-fill-mode: forwards;
        animation-iteration-count: 1;
        animation-delay: 0.5s;
    }

    @-webkit-keyframes slideInLeft {
        0% {
            left: -10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @-moz-keyframes slideInLeft {
        0% {
            left: -10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @-o-keyframes slideInLeft {
        0% {
            left: -10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @keyframes slideInLeft {
        0% {
            left: -10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @-webkit-keyframes slideInRight {
        0% {
            left: 10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @-moz-keyframes slideInRight {
        0% {
            left: 10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @-o-keyframes slideInRight {
        0% {
            left: 10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    @keyframes slideInRight {
        0% {
            left: 10%;
            opacity: 0;
        }

        100% {
            left: 0%;
            opacity: 1;
        }
    }

    .wrap_slidecaption h1 {
        color: black;
        font-weight: 600;
        font-size: 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .wrap_slidecaption h2 {
        color: black;
        font-size: 21px;
        line-height: 36px;
        text-transform: uppercase;
        padding-bottom: 20px;
    }

    .readmore-home a:hover {
        text-decoration: none;
        background-color: #721476;
    }

    .readmore-home a {
        background-color: #9e4ea5;
        text-align: center;
        font-size: 16px;
        padding: 8px 18px;
        border-radius: 35px;
        color: #fff;
        width: 130px;
        text-align: center;
        margin: 0 auto;
    }

    .flex-control-nav li {
        margin: 0 3px;
    }

    .flex-control-paging li a {
        width: 9px;
        height: 9px;
    }

    @media (max-width: 1199px) {
        .wrap_slidecaption h1 {
            font-size: 40px;
        }

        .readmore-home a {
            font-size: 14px;
        }

        .wrap_slidecaption h2 {
            font-size: 19px;
            line-height: 22px;
        }
    }

    @media (max-width: 992px) {
        .wrap_slidecaption h1 {
            font-size: 28px;
        }

        .flex-control-nav {
            bottom: 0px;
        }

        .flex-control-paging li a.flex-active {
            width: 8px;
            height: 8px;
            line-height: 27px;
        }

        .flex-control-paging li a {
            width: 7px;
            height: 7px;
        }

        .wrap_slidecaption h2 {
            font-size: 13px;
            line-height: 10px;
        }

        .readmore-home a {
            font-size: 12px;
            padding: 6px 14px;
        }
    }

    @media (max-width: 767px) {
        .flexslider {
            margin: 49px 0 0px !important;
        }

        .wrap_slidecaption {
            top: 39%;
        }

        .wrap_slidecaption h1 {
            font-size: 16px;
        }

        .wrap_slidecaption h2 {
            font-size: 11px;
            padding-bottom: 10px;
            line-height: 4px;
        }

        .flex-control-nav li {
            margin: 0px 2px;
        }

        .flex-control-nav {
            bottom: 50px;
        }

        .flex-control-paging li a {
            width: 5px;
            height: 5px;
        }

        .flex-control-paging li a.flex-active {
            width: 6px;
            height: 6px;
            line-height: 25px;
        }

        .readmore-home a {
            font-size: 10px;
            padding: 4px 9px;
        }
    }

    /*---------------------------------------------------*/


    .promotions {
        font-size: 18px;
        padding-bottom: 5px;
        font-weight: normal;
        background: url(../images/title-h-line.png) no-repeat left bottom;
    }

    .textporfile {
        font-size: 20px;
        margin-bottom: -10px;
        font-weight: bold;
        margin-top: 0rem;
    }

    .image-cropper {
        width: 150px;
        height: 150px;
        position: relative;
        overflow: hidden;
        border-radius: 50%;
        margin-bottom: 10px;
        margin: 0 auto;
    }

    .profile-pic {
        display: inline;
        margin: 0 auto;
        margin-left: 0;
        height: 151%;
        width: auto;
    }

    .box-profile {
        border-radius: 5px;
        padding: 15px;
        margin-top: 3rem;
        background-color: #ffffff;
        box-shadow: 0px 0px 7px -1px rgba(48, 46, 50, 0.23);
        margin-bottom: 1.5rem;
    }

    .box-2 {
        margin-top: 3rem;
    }

    .texttravel {
        font-size: 17px;
        font-weight: bold;
        text-align: center;
        margin-top: 5px;
        margin-bottom: 1rem;
    }

    .text-nameprofile {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
        text-align: center;
            margin-top: 5px;
    }

    .textprofile {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .text-new {
        text-align: center;
        letter-spacing: 0.3px;
        color: #000;
        margin-bottom: 1rem;
        font-size: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }


    .textupdat {
        font-size: 17px;
        margin-bottom: -10px;
        font-weight: bold;
    }

    .margin-bottom {
        margin-bottom: 10rem;
    }

</style>

<body style="background-color: #f7f7f7;">
    @include('inc_topmenu')


    <div class="container-fluid nopan wow fadeInDown">
        <div class="row ">
            <div class="flexslider">
                <ul class="slides">
                    <li>
                        <div class="wrap_slidecaption">
                            <div class="slidecaption slideleft">
                                <h1 class="text00"> Product </h1>
                            </div>
                        </div>
                        <img src="../webpromote/images/bg-product.jpg" class="img-fluid" style="width: 100%;" />
                    </li>
                    <li>
                        <div class="wrap_slidecaption">
                            <div class="slidecaption slideleft">
                                <h1 class="text00"> Product </h1>
                            </div>
                        </div>
                        <img src="../webpromote/images/bg-product.jpg" class="img-fluid" style="width: 100%;" />
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid nopan wow fadeInDown">
        <div class="container wow fadeInDown">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box-profile ">
                        <div class="promotions">
                            <h2 class="textporfile">Profile</h2>
                            <img src="../webpromote/images/title-h-line.png" alt="" class="img-fluid">
                        </div>

                        <div class="image-cropper">
                            <img src="../webpromote/images/young.jpg" alt="" class="img-fluid profile-pic">
                        </div>
                        <h2 class="text-nameprofile">กนกวรรณ เพรชทอง</h2>
                        <div class="textprofile">ทำงานที่ : บริษัททัวร์</div>
                        <div class="textprofile">อายุ : 26 ปี</div>
                        <div class="textprofile">เชื้อชาติ : ไทย</div>
                        <div class="textprofile">วันเกิด 9 ต.ค 1994</div>
                        <div class="textprofile">ที่อยู่ : กรุงเทพ</div>
                        <div class="textprofile">ภาษา : ไทย , อังกฤษ</div>
                        <a href="promote2">ข่าว</a>
                        <a href="promote">ท่องเที่ยว</a>
                        <a href="promote3">สินค้า</a>
                        <div class="center" style=" background-color: #00c454; color: white;">
                        ติดต่อซื้อสินค้า/ร่วมธุรกิจ<br/>
                        <select  class="center" style=" width: 90%; margin-left: 5%;">
                            <option>เลือกสินค้า</option>
                            <?php for($x=1;$x<=5;$x++) { ?>
                            <option>สินค้าโปรโมชั่นชุดที่ {{ $x }}</option>
                            <?php } ?>
                        </select>
                        <input class="center" style=" width: 90%; margin-left: 5%;" type="text" placeholder="ชื่อ-นามสกุล" />
                        <input class="center" style=" width: 90%; margin-left: 5%;" type="text" placeholder="เบอร์โทรศัพท์" />
                        <input class="center" style=" width: 90%; margin-left: 5%;" type="text" placeholder="อีเมล์" />
                        <textArea class="center" style=" width: 90%; height: 100px; margin-left: 5%;">รายละเอียดเพิ่มเติม</textArea>
                        <input class="center" style=" width: 90%; margin-left: 5%; background-color: #00c454; color: white;" type="button" value="ส่งข้อความ" />
                        <br/><br/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9 ">
                    <div class="box-2">
                        <div class="textupdat">News Product</div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem2.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem2.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>
                             
                             <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem2.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href="detail-product">
                                    <img src="../webpromote/images/creem2.jpg" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                             
                        </div>
                        <br>
                        <div class="textupdat">Featured product</div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                              <a href="detail-product">
                                <img src="../webpromote/images/creem3.jpg" alt="" class="img-fluid">
                                <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                              <a href="detail-product">
                                <img src="../webpromote/images/creem3.jpg" alt="" class="img-fluid">
                                <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-3">
                              <a href="detail-product">
                                <img src="../webpromote/images/creem3.jpg" alt="" class="img-fluid">
                                <div class="text-new">Text head </div>
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                              <a href="detail-product">
                                <img src="../webpromote/images/creem3.jpg" alt="" class="img-fluid">
                                <div class="text-new">Text head </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="margin-bottom"></div>
    </div>



    @include('inc_footer')


    <script>
        $('.mainmenu li:nth-child(1) a').addClass('active');

    </script>



    <script type="text/javascript" src="../webpromote/https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>

    <script type="text/javascript">
        $(window).on("load", function() {
            $('.flexslider').flexslider({
                animation: "slide",
                animationSpeed: 1500,
                slideshow: true,

                //animationLoop: false,
                start: function(slider) {
                    $('.slidecaption, .btn_slide ').removeClass('actioncaption');
                    $('.flex-active-slide').find('.slidecaption, .btn_slide').addClass('actioncaption');
                },
                after: function(slider) {
                    $('.slidecaption, .btn_slide ').removeClass('actioncaption');
                    $('.flex-active-slide').find('.slidecaption, .btn_slide').addClass('actioncaption');
                }
            });
        });

    </script>



    <script>
        $(document).ready(function() {
            $("#news-slider").owlCarousel({
                items: 3,
                itemsDesktop: [1199, 3],
                itemsMobile: [600, 1],
                itemsTablet: [768, 2],
                itemsTabletSmall: false,
                itemsMobile: [479, 1],
                singleItem: false,
                pagination: true,
                autoPlay: true

            });

        });

    </script>


</body>

</html>
