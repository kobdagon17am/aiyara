 <!DOCTYPE html>
<html lang="en-us" class="no-js">

<head>
    <meta charset="utf-8">
    <title>Aiyara 404</title>
    <meta name="description" content="Gradient able 404 Error page design" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Codedthemes">
    <!-- ================= Favicons ================== -->
    <link rel="shortcut icon" href="{{ asset('frontend/error/img/favicon.ico')}}">
    <!-- ============== Resources style ============== -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/error/css/style.css')}}" />
</head>

<body class="bubble">
    <canvas id="canvasbg"></canvas>
    <canvas id="canvas"></canvas>
    <!-- Your logo on the top left -->
    <a href="#" class="logo-link" title="back home">
        <img src="{{ asset('frontend/assets/images/logo.png')}}" class="logo" alt="logo" width="200" />
    </a>
    <div class="content">
        <div class="content-box">
            <div class="big-content">
                <!-- Main squares for the content logo in the background -->
                <div class="list-square">
                    <span class="square"></span>
                    <span class="square"></span>
                    <span class="square"></span>
                </div>
                <!-- Main lines for the content logo in the background -->
                <div class="list-line">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
                <!-- The animated searching tool -->
                <i class="fa fa-search color" aria-hidden="true"></i>
                <!-- div clearing the float -->
                <div class="clear"></div>
            </div>
            <!-- Your text -->
            <h1>Oops! Error 404 not found.</h1>
            <p>The page you were looking for doesn't exist.
                <br> We think the page may have moved.</p>
        </div>
    </div>
    <footer class="light">
        <ul>
            <li><a href="#">Support</a></li>
            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
        </ul>
    </footer>
    <script src="{{ asset('frontend/error/js/jquery.min.js')}}"></script>
    <script src="{{ asset('frontend/error/js/bootstrap.min.js')}}"></script>
    <!-- Bubble plugin -->
    <script src="{{ asset('frontend/error/js/bubble.js')}}"></script>
</body>

</html>
