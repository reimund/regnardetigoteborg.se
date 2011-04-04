<?php
$xml = file_get_contents('http://api.met.no/weatherapi/locationforecast/1.8/?lat=57.42;lon=11.55;msl=70');

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, 'http://api.met.no/weatherapi/locationforecast/1.8/?lat=57.42;lon=11.55;msl=70');
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
	<title><?php echo $is_raining ? 'Ja, det regnar i Göteborg.' : 'Nej, det regnar inte i Göteborg.'; ?></title>

<style>
body {
	text-align: center;
}
strong {
	font-size: 30em;
}
</style>
</head>

<body>
	<strong><?php echo $is_raining ? 'Ja' : 'Nej'; ?></strong>
</body>

</html>
