<?php
	if(empty($_title)){
      $_title ='';  
    }
	if(empty($_keywords)){
      $_keywords ='';
    }		
	if(empty($_description)){
        $_description ='';

    } 	
?>
<title></title>
<meta name="keywords" content="<?php echo $_keywords;?>" />
<meta name="description" content="<?php echo $_description;?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robot" content="index, follow" />
<meta name="generator" content="Brackets">
<meta name='copyright' content='Orange Technology Solution co.,ltd.'>
<meta name='designer' content='Atthacha S.'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link rel="stylesheet" type="text/css" href="{{asset('public/webpromote/css/bootstrap.css')}}">
<link type="text/css" rel="stylesheet" href="{{asset('public/webpromote/css/layout.css')}}" media="screen,projection" />
<link type="image/ico" rel="shortcut icon" href="{{asset('public/webpromote/images/favicon.ico')}}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/v4-shims.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&family=Poppins:wght@300;400&display=swap" rel="stylesheet">

<script src="{{asset('public/webpromote/js/jquery-3.3.1.slim.min.js')}}"></script>
<script src="{{asset('public/webpromote/js/jquery.min.js')}}"></script>
<script src="{{asset('public/webpromote/js/jquery-ui.js')}}"></script>
<script src="{{asset('public/webpromote/js/popper.min.js')}}"></script>
<script src="{{asset('public/webpromote/js/bootstrap.min.js')}}"></script>

<!-- FLEXSLIDER -->
<link rel="stylesheet" type="text/css" href="{{asset('public/webpromote/flexslider/flexslider.css')}}">
<script src="{{asset('public/webpromote/flexslider/jquery.flexslider.js')}}"></script>

<!-- WOW -->
<link rel="stylesheet" type="text/css" href="{{asset('public/webpromote/wow-master/css/animate.css')}}">
<script src="{{asset('public/webpromote/wow-master/dist/wow.min.js')}}"></script>


<link rel="stylesheet" href="{{asset('public/webpromote/fancybox-master/dist/jquery.fancybox.css')}}" />
<script src="{{asset('public/webpromote/fancybox-master/dist/jquery.fancybox.min.js')}}"></script>


<script>
    wow = new WOW({
        animateClass: 'animated',
        offset: 100,
        callback: function(box) {
            console.log("WOW:animating <"+box.tagName.toLowerCase()+">")
        }
    });
    wow.init();

</script>
