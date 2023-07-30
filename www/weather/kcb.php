<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
  <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
  <?
		
		$apiKey = "be859bdc86056776256f872e7c7a0a2d";	//키값
		$lat = "35.6528";								//위도
		$lon = "128.396";								//경도
		$googleApiUrl = "https://api.openweathermap.org/data/2.5/forecast?lat=" . $lat . "&lon=" .$lon. "&APPID=" . $apiKey. "&units=metric";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);

		curl_close($ch);
		$data = json_decode($response);

		print_r($data);

		$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" .$lon. "&APPID=" . $apiKey. "&units=metric";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);

		curl_close($ch);
		$current_data = json_decode($response);

		$statDate = date("Y-m-d", strtotime("Now"));
		$lastDate = date("Y-m-d", strtotime("+5 days"));
		
		$period = new DatePeriod( new DateTime($startDate), new DateInterval('P1D'), new DateTime($lastDate));
		foreach ($period as $date) $dateArray[] = $date->format("Y-m-d") . " 09:00:00" ;

  ?>
 </head>
 <body>
	
	<p>
		<p>현재날씨온도</p>
		<p><img src="https://openweathermap.org/img/wn/<?=$current_data->weather[0]->icon?>.png" /></p>
		<p>온도 : <?=$current_data->main->temp?>℃</p>
		<p>체감온도 : <?=$current_data->main->feels_like?>℃</p>
		<p>최저온도 : <?=$current_data->main->temp_min?>℃</p>
		<p>최고온도 : <?=$current_data->main->temp_max?>℃</p>
		<p>습도 : <?=$current_data->main->humidity?>%</p>
		<p>풍속 : <?=$current_data->wind->speed?>m/s</p>
		<p>강수 확률 : <?=($current_data->pop * 100)?>%</p>

	</p>

	<?
		for ( $C__I = 0 ; $C__I < count($data->list); $C__I++ )
		{
			if ( in_array($data->list[$C__I]->dt_txt, $dateArray) )
			{
				?>
				<p>
					<p>날짜 : <?=$data->list[$C__I]->dt_txt?></p>
					<p><img src="https://openweathermap.org/img/wn/<?=$data->list[$C__I]->weather[0]->icon?>.png" /></p>
					<p>온도 : <?=$data->list[$C__I]->main->temp?>℃</p>
					<p>체감온도 : <?=$data->list[$C__I]->main->feels_like?>℃</p>
					<p>최저온도 : <?=$data->list[$C__I]->main->temp_min?>℃</p>
					<p>최고온도 : <?=$data->list[$C__I]->main->temp_max?>℃</p>
					<p>습도 : <?=$data->list[$C__I]->main->humidity?>%</p>
					<p>풍속 : <?=$data->list[$C__I]->wind->speed?>m/s</p>
					<p>강수 확률 : <?=($data->list[$C__I]->pop * 100)?>%</p>

				</p>
				<?
			}
		}
	?>

	<input type="button" value="클릭" onclick="weather1();" />

	<script>
		function weather1()
		{
			lat = "35.6528";
			lon = "128.396";

			var apiURI = "https://api.openweathermap.org/data/2.5/forecast?lat="+lat+"&lon="+lon+"&appid="+"be859bdc86056776256f872e7c7a0a2d&units=metric";
			$.ajax({
				url: apiURI,
				dataType: "json",
				type: "GET",
				async: "false",
				success: function(resp) {
					console.log(resp);
					console.log("현재온도 : "+ resp.main.temp );
					console.log("현재습도 : "+ resp.main.humidity);
					console.log("날씨 : "+ resp.weather[0].main );
					console.log("상세날씨설명 : "+ resp.weather[0].description );
					console.log("날씨 이미지 : "+ resp.weather[0].icon );
					console.log("바람   : "+ resp.wind.speed );
					console.log("나라   : "+ resp.sys.country );
					console.log("도시이름  : "+ resp.name );
					console.log("구름  : "+ (resp.clouds.all) +"%" );                 
				}
			})
		}
		
	</script>
 </body>
</html>