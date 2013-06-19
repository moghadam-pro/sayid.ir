<?php
$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
if ($msie){$isIE = "true";}else{ $isIE = "false";}
?>
<!doctype html>
<!--[if IE]> <![endif]-->
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sayid Moghadam Website</title>
	<!-- Your website's description for SEO -->
	<meta name="description" content="Welcome. This is Sayid Moghadam Personal Website .Hi ,I'm designer .">
    <meta name="Keywords" content="web development, web designer, sayid, i@sayid.ir, web studio, graphic, photoshop, designer, css, stylesheet, sayid moghadam, moghadam, css3, html5, open source design, sayid-ir, 1stui" />
	<!-- Makes your website stretch full width on a mobile device -->
	<meta name="viewport" content="width=device-width">
	<!-- CSS styles -->
	<link rel="stylesheet" href="./sitemedia/stylesheet/style.css">
   	<link rel="stylesheet" href="./sitemedia/stylesheet/reset.css">
    <!-- JS script -->
    <script type="text/javascript" src="./sitemedia/jscript/jquery.min.js"></script>
    <script type="text/javascript" src="./sitemedia/jscript/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="./sitemedia/jscript/jquery.approach.min.js"></script>
    <?php include('./lib/countdown.php');?>
    <style>
	.numTimerCh{
		display:inline-block;
		font-size:90px;
		color:#0E900B;
		font-family:"Lato";
		text-shadow:0 0 10px #999;
	}
	.dot{
		font-size:64px;
		font-family:"Lato";
		color:#0E900B;
	}
	.container{
		margin-top:200px;
		 text-align:center;
	}
	.IEdetector{}
	.isie{}
    </style>
</head>
<body >
    <div class="container timer">
       	<div class="numTimerCh" id="years">0000</div><span class="dot">.</span>
    	<div class="numTimerCh" id="days">007</div><span class="dot">.</span>
        <div class="numTimerCh" id="hours">03</div><span class="dot">.</span>
        <div class="numTimerCh" id="minutes">53</div><span class="dot">.</span>
        <div class="numTimerCh" id="seconds">12</div>
    </div>
    <script>
	$(".numTimerCh").approach({
		"fontSize": "60px",
		"color": "#F66300"
	}, 200);
	</script>
</body>
</html>