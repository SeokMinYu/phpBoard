<meta charset="UTF-8">
<?
	$apiKey = "5d549edc67471548927872ed4eab17ee";	//키값
	$lat = "35.6528";								//위도
	$lon = "128.396";							//경도
	$googleApiUrl = "http://api.openweathermap.org/data/2.5/forecast?lat=" . $lat . "&lon=" .$lon. "&lang=kr&units=metric&APPID=" . $apiKey;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	echo $response;

	curl_close($ch);
	$data = json_decode($response);
	$currentTime = date();

	$startDate = date("Y-m-d", strtotime("Now"));
	$lastDate = date("Y-m-d", strtotime("+5 days"));
	
	$period = new DatePeriod( new DateTime($startDate), new DateInterval('P1D'), new DateTime($lastDate));
	foreach ($period as $date) $dateArray[] = $date->format("Y-m-d") . " 09:00:00" ;
	$K__I = 0;
?>
<!doctype html>
<html>
<head>
<title>OpenWeatherMap with PHP</title>
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
</head>
<body>
    <div class="report-container">
        <h2><?=$data->name?> 날씨 정보</h2>
	<?
		for ($C__I = 0; $C__I < count($data->list); $C__I++) 
		{ 
			if ( in_array($data->list[$C__I]->dt_txt, $dateArray) )
			{	
				$str_date = strtotime($currentTime.'+'.$K__I++.' days');
			?><br>
				<div class="time">
					<div><?=date("Y년 n월 j일",$str_date)?></div>
					<div><?=ucwords($data->list[$C__I]->weather[0]->description)?></div>
				</div>
				<div class="weather-forecast">
					<img src="http://openweathermap.org/img/wn/<?=$data->list[$C__I]->weather[0]->icon?>.png" class="weather-icon" />
					<p>현재 온도: <?=$data->list[$C__I]->main->temp?>°C</p>
					<p>최고 온도: <?=$data->list[$C__I]->main->temp_max?>°C</p>
					<p>최저 온도 : <?=$data->list[$C__I]->main->temp_min?>°C</p>
					<p>습도 : <?=$data->list[$C__I]->main->humidity?> %</p>
					<p>풍속 : <?=$data->list[$C__I]->wind->speed?> km/h</p>
				</div>
	<?		}
		}
	?>
    </div>
</body>
</html>