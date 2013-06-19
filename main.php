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
    <script>
	var IEbrowser = <?php echo strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false; ?>
	function preloader(){
		if(!IEbrowser){
		document.getElementById("preloader").className = "fade_out";
		setTimeout(function(){
			$('.fade_out').remove();
		}, 1000);
		}else{
			document.getElementsByTagName('body').parentNode.removeChild();
			document.getElementsByTagName('body').innerHTML = "your browser is IE";
		}
	}
	</script>
</head>
<body onload="preloader();">
<div class="" id="preloader"></div>
<div class="leftmenu"></div>
<div class="content"></div>
<div class="footer"></div>
</body>
</html>