<link rel="stylesheet" href="../webpromote/secondary-expandable-navigation-master/css/style.css">
<!-- Resource style -->
<script src="../webpromote/secondary-expandable-navigation-master/js/modernizr.js"></script>
<!-- Modernizr -->
<style>
    .bg-dark {
        background-color: #fff !important;
    }

    .go-top {
        position: fixed;
        bottom: 6em;
        right: 2em;
        border: 2px solid #5e6069;
        font-size: 12px;
        text-align: center;
        z-index: 3;
        color: #5e6069;
        padding: 15px 20px 15px 20px;
    }

    .text-logo {
        display: inline-block;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .wrap_logo {
        text-align: center;
    }

    .wrap_mainmenu {
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
    }

    .wrap_mainmenu.sticky {
    position: fixed;
    top: 0;
    z-index: 9999;
    background-color: #FFF;
    left: 0;
    right: 0;
    box-shadow: 0px 1px 1px 1px rgba(50, 50, 50, 0.09);
}

    .mainmenu-align {
        text-align: center;
        margin: 0;
        border-bottom: 1px solid #efefef;
        padding: 0;
    }

    .mainmenu>li {
        display: inline-block;
    }

    .mainmenu>li>a.active {
        color: #b97c10;
        text-decoration: none;
        font-weight: 600;
    }

    .mainmenu li a {
        font-family: 'Kanit','Poppins',sans-serif;
        display: block;
        padding: 15px 30px 15px 30px;
        text-align: left;
        color: #000;
        text-decoration: none;
        font-weight: bold;
        font-size: 20px;
        border-bottom: 1px solid #efefef;
    }

    .navber_servicesbus {
        padding: 0;
    }

    .navber_servicesbus li {
        width: 100%;
        display: inline-block;
        vertical-align: top;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        padding: 0;
        z-index: 4;
        left: 0px;
        width: 280px;
        text-align: left;
    }

    .dropdown-content li a:hover {
        background-color: #e7c9ea;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .flexslider {
        margin: 0 0 0px;
        border: 0px solid #fff;
    }

    .flex-control-paging li a.flex-active {
        background: #fff;
    }

    .flex-control-paging li a:hover {
        background: #fff;
    }

    .flex-control-paging li a {
        background: #ffffff69;
        box-shadow: none;
    }

    .btn-secondary:not(:disabled):not(.disabled).active:focus,
    .btn-secondary:not(:disabled):not(.disabled):active:focus,
    .show>.btn-secondary.dropdown-toggle:focus {
        box-shadow: none;
    }

    .btn-secondary:not(:disabled):not(.disabled).active,
    .btn-secondary:not(:disabled):not(.disabled):active,
    .show>.btn-secondary.dropdown-toggle {
        color: #fff;
        background: none;
        border: none;
    }

    .btn-secondary:hover {
        color: #fff;
        background: none;
        border: none;
    }

    .btn-secondary {
        color: #fff;
        background: none;
        border: none;
        border-radius: 35px;
    }

    @media (max-width: 1199px) {
        .mainmenu li a {
            padding: 10px 20px 10px 20px;
            font-size: 16px;
        }
        .text-logo img {
            width: 90%;
        }
        .text-logo {
            margin: 20px 0 10px;
        }
    }

    @media (max-width: 992px) {
        .text-logo img {
            width: 70%;
        }
        .mainmenu img {
            width: 18px;
            margin-top: -5px;
        }
        .mainmenu li a {
            padding: 10px 10px 10px 10px;
            font-size: 15px;
        }
        .go-top {
            padding: 10px 15px 10px 15px;
        }
    }

    @media (max-width: 767px) {
        .text-logo {
            display: none;
        }
        .wrap_mainmenu {
            display: none;
        }
        .btn-secondary {
            color: #000;
            font-size: 14px;
        }
    }
</style>

<header>
    <a id="cd-logo" href="index.php"><img src="" alt="Homepage" class="logo"></a>
    <nav id="cd-top-nav">
        <ul>
            <li><a href="#0">Tour</a></li>
            <li><a href="#0">Login</a></li>
        </ul>
    </nav>
                
    <a id="cd-menu-trigger" href="#0">
    <span class="cd-menu-text">Menu</span>
    <span class="cd-menu-icon"></span>
    </a>
    
</header>
<nav id="cd-lateral-nav">
    <ul class="cd-navigation cd-single-item-wrapper ">
        <li><a href="index.php">หน้าหลัก</a></li>
        <li><a href="legal-counsel.php">ที่ปรึกษากฎหมาย</a></li>
        <li><a href="steps.php">ขั้นตอนการใช้บริการ</a></li>
        <li><a href="service-rate.php">อัตราค่าบริการ</a></li>
        <li><a href="Knowledge.php">เกร็ดความรู้</a></li>
        <li><a href="conditions.php">เงื่อนไขและข้อตกลง</a></li>
    </ul>

</nav>

<!--

<div class="container-fluid bg-navtop">
<nav class="wrap_mainmenu wow fadeInDown">
    <div class="row">
        <div class="col-md-6">
            <div class="text_top01"><i class="fa fa-envelope" aria-hidden="true"></i> E-mail : thailaw@gmail.com</div>
        </div>
        <div class="col-md-6 ">
            <div class="text_top">
                <img src="images/socia-01.png" alt="" class="img-fluid iconsocai">
                <img src="images/socia-02.png" alt="" class="img-fluid iconsocai">
                <img src="images/socia-03.png" alt="" class="img-fluid iconsocai">
            </div>
        </div>
    </div>
</nav>
</div>


<div class="row">
    <div class="col-12 wrap_logo">
        <div class="text-logo wow fadeInDown">
            <a href="index.php"><img src="" class="img-fluid logo"></a>
        </div>
    </div>
</div>
<div class="container-fluid">
    <nav class="wrap_mainmenu row wow fadeInDown">
        <ul class="mainmenu col-12 mainmenu-align mainmenu">
            <li><a href="index.php">หน้าหลัก</a></li>
            <li><a href="legal-counsel.php">ที่ปรึกษากฎหมาย</a></li>
            <li><a href="steps.php">ขั้นตอนการใช้บริการ</a></li>
            <li><a href="service-rate.php">อัตราค่าบริการ</a></li>
            <li><a href="Knowledge.php">เกร็ดความรู้</a></li>
            <li><a href="conditions.php">เงื่อนไขและข้อตกลง</a></li>
            
    
        </ul>
    </nav>
</div>
-->
<a href="#" class="go-top"> <i class="fa fa-chevron-up"></i></a>
<script src="../webpromote/secondary-expandable-navigation-master/js/main.js"></script>
<!-- Resource jQuery -->
<script>
    $(document).ready(function() {
        // Show or hide the sticky footer button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.go-top').fadeIn(300);
            } else {
                $('.go-top').fadeOut(300);
            }
        });

        // Animate the scroll to top
        $('.go-top').click(function(event) {
            event.preventDefault();

            $('html, body').animate({
                scrollTop: 0
            }, 800);
        })
    });
</script>
<script>
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.wrap_mainmenu').addClass("sticky");
        } else {
            $('.wrap_mainmenu').removeClass("sticky");
        }
    });
</script>