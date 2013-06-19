	<script>
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
		$count='2013-09-22 00:00:00';
		
		$time=date_diff_in_days($count,$now);
		print $time;
	?>;
	var TotalSeconds;
	function CreateTimer (TimerClass, Time) {
		TotalSeconds = Time;
		UpdateTimer()
		window.setTimeout("Tick()", 1000);
	}
	function Tick () {
		if (TotalSeconds <= 0) {
			$('.'+TimerClass).removeClass(TimerClass);
			return;
		}
		TotalSeconds -= 1;
		UpdateTimer()
		window.setTimeout("Tick()", 1000);
	}
	function LeadingZero (Time) {
		return (Time < 10) ? "0" + Time : + Time;
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
		document.getElementById('days').innerHTML = (Days > 0) ? Days : '000' ;
	}
	$(document).ready(function(){
		CreateTimer("timer", countdown);
	});
	</script>