<?php
// Systemet kappelplatsen http://api.met.no/weatherapi/locationforecast/1.8/?lat=57.69;lon=11.97;msl=10
$url = 'http://api.met.no/weatherapi/locationforecast/1.8/?lat=57.42;lon=11.55;msl=10';
$xml = file_get_contents($url);

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$xml = curl_exec($ch);
//curl_close($ch);

// The weather types.
//SUN
//LIGHTCLOUD
//PARTLYCLOUD
//CLOUD
//LIGHTRAINSUN
//LIGHTRAINTHUNDERSUN
//SLEETSUN
//SNOWSUN
//LIGHTRAIN
//RAIN
//RAINTHUNDER
//SLEET
//SNOW
//SNOWTHUNDER
//FOG

$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->loadXML($xml);
$times = $dom->getElementsByTagName('time');

date_default_timezone_set('Europe/Berlin');
//$t = '2011-04-04T21:00:00Z';
//echo date('Y-m-n H:i', strtotime($t));

$now = time();
foreach ($times as $time) {
	$start = $time->getAttribute('from');
	$end = $time->getAttribute('to');

	if ($now > strtotime($start) && $now <= strtotime($end)) {
		$now_node = $time;
		break;
	}
}

$symbols = $now_node->getElementsByTagName('symbol');
foreach($symbols as $s) {
	$weather = $s->getAttribute('id');
}

$is_raining = in_array($weather, array(
		'LIGHTRAINSUN',
		'LIGHTRAINTHUNDERSUN',
		'SLEETSUN',
		'SNOWSUN',
		'LIGHTRAIN',
		'RAIN',
		'RAINTHUNDER',
		'SNOW',
		'SLEET',
		'SNOWTHUNDER')) ? true : false;
?>
<!doctype html>
<html>

<head>
	<meta charset="UTF-8" /> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js" type="text/javascript" charset="utf-8"></script> 
	<title><?php echo $is_raining ? 'Ja, det regnar i Göteborg.' : 'Nej, det regnar inte i Göteborg.'; ?></title>

<style>
html{height: 100%;}
body {
	margin: 0;
	font-family: 'CabinBold', 'Helvetica', 'Lucida Grande', sans-serif;
	background: #ebf1ef;
	height: 100%;
	text-align: center;
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, rgb(206,221,216)),
		color-stop(1, rgb(235,241,239))
	);
	background-image: -moz-linear-gradient(
		center bottom,
		rgb(206,221,216) 0%,
		rgb(235,241,239) 100%
	);
}
strong {
	line-height: 0px;
	opacity: 0;
	vertical-align: text-top;
	font-size: 20em;
	text-shadow: 0 -2px 0px #cad9e9;
	color: #8fb3a9;
}

@font-face {
    font-family: 'CabinBold';
    src: url('fonts/Cabin-Bold-webfont.eot');
    src: url('fonts/Cabin-Bold-webfont.eot?#iefix') format('eot'),
         url('fonts/Cabin-Bold-webfont.woff') format('woff'),
         url('fonts/Cabin-Bold-webfont.ttf') format('truetype'),
         url('fonts/Cabin-Bold-webfont.svg#webfontEiid5d2G') format('svg');
    font-weight: normal;
    font-style: normal;
}
</style>

	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-22536382-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

</head>

<script>
jQuery(document).ready(function($) {
	$('strong').css('height', $(window).height());
	$('strong').delay(100).animate({'line-height': $(window).height() + 'px', opacity: 1}, 1000, function(){});
});
</script>

<body>
	<strong><?php echo $is_raining ? 'Ja' : 'Nej'; ?></strong>
</body>

</html>
