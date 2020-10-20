<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
   @include('inc_header')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>

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
        font-size: 25px;
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


    .text-boxnew {
        margin-top: 1rem;
    }

    .box-homenew {
        background-color: #fff;
        border-radius: 4px;
        -webkit-box-shadow: 5px 5px 15px 5px rgba(50, 50, 50, .5);
        -moz-box-shadow: 5px 5px 15px 5px rgba(50, 50, 50, .5);
        box-shadow: 0px 0px 7px -2px rgba(48, 46, 50, 0.23);
        margin-bottom: 1.5rem;
    }

    .text-box-homenew a {
        font-size: 15px;
        color: black;
    }

    .boxnews {
        background: #012563;
        padding: 5px;
        width: 40%;
        text-align: center;
        color: white !important;
    }

    .boxnews:hover {
        background: #ffc107;
        color: white !important;
    }

    .cut-imgnews {
        overflow: hidden;
        position: relative;
        width: 100%;
        padding-bottom: 67%;
    }

    .cut-imgnews img {
        position: absolute;
        object-fit: cover;
        max-width: 100%;
    }

    .text-box-homenew h6 {
        font-weight: 600;
        letter-spacing: 0.3px;
        color: #000;
        font-size: 18px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .text-box-homenew p {
        line-height: 23px;
        font-size: 16px;
        color: #666;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .bg-newsgray {
        background-color: #e6e6e6 !important;
        height: auto;
        padding: 35px;
        margin-bottom: 2rem;
    }

    .text_topic a {
        font-size: 16px;
        font-weight: 100;
        color: #666;
    }

    .text_topic {
        padding: 5px;
        border-bottom: 1px solid #cccccc;
        margin-bottom: 1rem;
    }

    .textnewshade {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-align: left;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .text-time {
        font-size: 15px;
        text-align: left;
        color: black;
        font-weight: bold;
        margin-bottom: 7px;
    }

    .portfolio-menu {
        text-align: center;
    }

    .portfolio-menu ul li {
        display: inline-block;
        margin: 0;
        list-style: none;
        padding: 10px 15px;
        cursor: pointer;
        -webkit-transition: all 05s ease;
        -moz-transition: all 05s ease;
        -ms-transition: all 05s ease;
        -o-transition: all 05s ease;
        transition: all .5s ease;
    }

    .portfolio-item {
        /*width:100%;*/
    }

    .portfolio-item .item {
        /*width:303px;*/
        float: left;
        margin-bottom: 10px;
    }
    
    .textback {
    font-size: 20px;
    margin-bottom: 1rem;
    margin-top: 1rem;
    }
</style>

<body style="background-color: #f7f7f7;">
    @include('inc_topmenu')


    <div class="container-fluid  nopan wow fadeInDown">
       <img src="images/bg-product.jpg" class="img-fluid" style="width: 100%;" />
        <div class="container">
            <div class="row">
                <div class="col-12">
                        <a href="product.php" class="textback"><i class="fa fa-angle-left" aria-hidden="true"></i>&nbsp; <b>กลับ</b></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid nopan wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <img src="images/creem.jpg" alt="" class="img-fluid">
                </div>
                <div class="col-md-7">
                    <h4 class="textnewshade">Product 01</h4>
                    <p><b>รหัสสินค้า</b> : SKU-00106</p>
                    <p><b>ราคาปกติ</b> : 800.00 บาท</p>
                    <p><b>รหัสสินค้า </b> : SKU-00106</p>
                    <p><b>น้ำหนัก</b> : 50 กรัม</p>
                    <p><b>รหัสสินค้า</b> : SKU-00106</p>
                    <p><b>สถานะสินค้า</b> : พร้อมส่ง</p>
                    <p><b>รายละเอียด</b></p>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing</p>
                </div>
            </div>
            
            <br> <br>
            
            <div class="textupdat">Related products</div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <a href="detail-product.php">
                        <img src="images/creem3.jpg" alt="" class="img-fluid">
                        <div class="text-new">Text head </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="detail-product.php">
                        <img src="images/creem3.jpg" alt="" class="img-fluid">
                        <div class="text-new">Text head </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="detail-product.php">
                        <img src="images/creem3.jpg" alt="" class="img-fluid">
                        <div class="text-new">Text head </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="detail-product.php">
                        <img src="images/creem3.jpg" alt="" class="img-fluid">
                        <div class="text-new">Text head </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="margin-bottom"></div>
    </div>



    @include('inc_footer')


    <script>
        $('.mainmenu li:nth-child(1) a').addClass('active');
    </script>


</body>

</html>