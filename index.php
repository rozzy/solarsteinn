<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<?php
	require 'solarsteinn.class.php';

	$time = time();

	$zones = array(
		'@night' => 'ночью',
		'@sunrise' => 'на рассвете',
		'@morning' => 'утром',
		'@day' => 'днем',
		'@sunset' => 'на закате',

		'0:00..5:00' => '@night',
		'0:00..0:30' => 'полночь',
		'6:00...10:00' => '@morning',
		'10:00...12:00' => 'до полудня',
		'12:00...@sunset/18:00' => '@day',
		'@sunset/18:00..22:30' => 'вечером',
		'@sunset..@sunset+0:30' => 'на закате',
		'22:30...0:00' => 'до полуночи'
	);

	$solarsteinn = new Solarsteinn();

	echo $solarsteinn->compile(time(), $zones, '%e %B, *');
	// Выведет  6 января, до полуночи
	
	echo $solarsteinn->compile(date(time()), $zones, false);
	// Выведет 1 декабря 12 11:47:31
?>
</body>
</html>