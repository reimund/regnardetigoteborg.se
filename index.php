<?php
// XXX: Add caching.
// XXX: Fade to color depending on weather.

date_default_timezone_set('Europe/Stockholm');

$url = 'http://api.met.no/weatherapi/locationforecast/1.8/?lat=57.42;lon=11.55;msl=10';
$xml = file_get_contents($url);

$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->loadXML($xml);

$time_node = find_forecast_node($dom->getElementsByTagName('time'), time());

/*
 * Gets the first forecast that covers the specified time.
 */
function find_forecast_node($node_list, $time)
{
	foreach ($node_list as $t) {
		if ($time > strtotime($t->getAttribute('from'))
				&& $time <= strtotime($t->getAttribute('to'))
				&& $t->getAttribute('from') != $t->getAttribute('to'))
			return $t;
	}

}

$symbols = $time_node->getElementsByTagName('symbol');

foreach($symbols as $s)
	$weather = $s->getAttribute('id');

/* The weather types.
	SUN
	LIGHTCLOUD
	PARTLYCLOUD
	CLOUD
	LIGHTRAINSUN
	LIGHTRAINTHUNDERSUN
	SLEETSUN
	SNOWSUN
	LIGHTRAIN
	RAIN
	RAINTHUNDER
	SLEET
	SNOW
	SNOWTHUNDER
	FOG
*/

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
