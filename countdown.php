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
    <script>
	var IEbrowser = <?=$isIE ?> ;
	function checkIE(){
		if(IEbrowser){
			handler = document.getElementById('container');
			handler.parentNode.removeChild(handler);
			handler = document.getElementById('preloader');
			handler.parentNode.removeChild(handler);
		}else{
			handler = document.getElementById('IEdetector');
			handler.parentNode.removeChild(handler);
		}
	}
	//=============================
	var stopCount = false;
	var countdown =<?php
		function date_diff_in_days($from,$to)
		{
			$date1 = strtotime($from);
			$date2 = strtotime($to);
			$dateDiff = $date1 - $date2;
			return $dateDiff;
		}
		$info = getdate();
		$date = $info['mday'];
		$month = $info['mon'];
		$year = $info['year'];
		$hour = $info['hours'];
		$min = $info['minutes'];
		$sec = $info['seconds'];
		$now=$year.'-'.$month.'-'.$date.' '.$hour.':'.$min.':'.$sec;
		$count='2013-07-22 00:00:00';
		
		$time=date_diff_in_days($count,$now);
		print $time;
    ?>;
	var TotalSeconds;
	function CreateTimer (Time) {
		TotalSeconds = Time;
		UpdateTimer()
		window.setTimeout("Tick()", 1000);
	}
	function Tick () {
		if (TotalSeconds <= 0) {
			stopCount = true;
			return;
		}
		TotalSeconds -= 1;
		UpdateTimer()
		window.setTimeout("Tick()", 1000);
	}
	function LeadingZero (Time) {
		return (Time < 10) ? "0" + Time : + Time;
	}
	function dayZero(day){
		if(day>0 && day<366){
			if(day>9 && day<99){ret = '0'+day ;}else if(day>99 && day<366){ret = day;}else{ret = '00'+day;}
		}else{ ret = '000';}
		return ret;
	}
	function UpdateTimer () {
		var Seconds = TotalSeconds;
		var Days = Math.floor(Seconds / 86400);
		Seconds -= Days * 86400;
		var Hours = Math.floor(Seconds / 3600);
		Seconds -= Hours * (3600);
		var Minutes = Math.floor(Seconds / 60);
		Seconds -= Minutes * (60);
		// write into document
		
		document.getElementById('seconds').innerHTML = LeadingZero(Seconds);
		document.getElementById('minutes').innerHTML = LeadingZero(Minutes);
		document.getElementById('hours').innerHTML = LeadingZero(Hours);
		document.getElementById('days').innerHTML = dayZero(Days);
	}
	// =====================
	function preloader(){
		document.getElementById("preloader").className = "fade_out";
		setTimeout(function(){
			$('.fade_out').remove();
		}, 1000);
	}
	var winW = window.innerWidth;
	var winH = window.innerHeight;
	//======================document ready
	$().ready(function() {
		checkIE();
        $('.centerScreen').fadeIn(400);
		loadingTop = (winH/2)-40;
		$('.centerScreen').animate({marginTop:loadingTop},1000);
		$('.container').css('margin-top',(winH/2)-100);
		if(!stopCount) CreateTimer(countdown);
    });
	</script>
</head>
<body onload="preloader();">
    <div id="preloader">
        <div class="centerScreen">
            <div id="loading"></div>
            <div id="pleasWait"></div>
        </div>
    </div>
    <div class="container" id="container">
       	<div class="numTimerCh" id="years1">0</div>
        <div class="numTimerCh" id="years2">0</div>
        <div class="numTimerCh" id="years3">0</div>
        <div class="numTimerCh" id="years4">0</div>
        <span class="numTimerCh">.</span>
    	<div class="numTimerCh" id="days">007</div>
        <span class="numTimerCh">.</span>
        <div class="numTimerCh" id="hours">03</div>
        <span class="numTimerCh">.</span>
        <div class="numTimerCh" id="minutes">53</div>
        <span class="numTimerCh">.</span>
        <div class="numTimerCh" id="seconds">12</div>
    </div>
    <div id="IEdetector">
        <div class="naSezaTit">دوست عزیز مرورگر شما غیر استاندارد میباشد لطفا مرورگر خود را تغییر دهید</div>
        <div id="naSeza"></div>
    </div>
    <script>
	$(".numTimerCh").approach({
		"fontSize": "60px"
		, "color": "#F66300"
	}, 200);
	</script>
</body>
</html>