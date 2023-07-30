<!doctype html>
<html lang="kr">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
  <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
  <?
	function papago($intext)
	{

		$client_id = "3H6Zs1os6XOlrcC1WDSw"; // 네이버 개발자센터에서 발급받은 CLIENT ID
		$client_secret = "6afO99VtSq";// 네이버 개발자센터에서 발급받은 CLIENT SECRET

		$encText = urlencode($intext);
		$postvars = "source=en&target=ko&text=".$encText;
		$url = "https://openapi.naver.com/v1/papago/n2mt";
		$is_post = true;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $is_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);

		$headers = array();
		$headers[] = "X-Naver-Client-Id: ".$client_id;
		$headers[] = "X-Naver-Client-Secret: ".$client_secret;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec ($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);

		return $response;
		
	}								

	// $lat = 위도, $lon = 경도
	function week_weather($lat,$lon)
	{
		$apiKey = "5d549edc67471548927872ed4eab17ee";
		$googleApiUrl = "http://api.openweathermap.org/data/2.5/onecall?lat=" . $lat . "&lon=" .$lon. "&exclude=hourly&lang=kr&appid=" . $apiKey. "&units=metric";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);

		//echo $response;

		curl_close($ch);
		$data = json_decode($response);

		return $data;
	}

	function current_weather($lat,$lon)
	{
		$apiKey = "5d549edc67471548927872ed4eab17ee";
		$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" .$lon. "&lang=kr&APPID=" . $apiKey. "&units=metric";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		//echo $response;

		curl_close($ch);
		$current_data = json_decode($response);
		return $current_data;
	}
	$data1 = week_weather("37.4759766","126.90681789999996"); //광명
	$data2 = week_weather("37.497928","127.027584"); //강남
	$data3 = week_weather("37.395218","127.111181"); //판교

	$current_data1 = current_weather("37.4759766","126.90681789999996"); //광명
	$current_data2 = current_weather("37.497928","127.027584"); //강남
	$current_data3 = current_weather("37.395218","127.111181"); //판교

	$papagoTest1 = json_decode(papago($current_data1->name)); //광명
	$papagoTest2 = json_decode(papago($current_data2->name)); //광명
	$papagoTest3 = json_decode(papago($current_data3->name)); //광명
		
  ?>
 </head>
 <body>
 <dl>
 <ol>
	<ul style="list-style-type : none;">
		<li><?=$papagoTest1->message->result->translatedText?>의 현재날씨온도</li>
		<li><img src="https://openweathermap.org/img/wn/<?=$current_data1->weather[0]->icon?>.png" /><?=$current_data1->weather[0]->description?></li>
		<li>온도 : <?=$current_data1->main->temp?>℃</li>
		<li>체감온도 : <?=$current_data1->main->feels_like?>℃</li>
		<li>최저온도 : <?=$current_data1->main->temp_min?>℃</li>
		<li>최고온도 : <?=$current_data1->main->temp_max?>℃</li>
		<li>습도 : <?=$current_data1->main->humidity?>%</li>
		<li>풍속 : <?=$current_data1->wind->speed?>m/s</li>

	</ul>
</ol>
<ol>
	<?
		for ( $C__I = 1 ; $C__I < count($data1->daily); $C__I++ )
		{
				?>
				
				<ul style="float:left; list-style-type : none;">
					<li>날짜 : <?=date("Y-m-d", $data1->daily[$C__I]->dt)?></li>
					<li><?=$data1->daily[$C__I]->weather[0]->description?></li>
					<li><img src="https://openweathermap.org/img/wn/<?=$data1->daily[$C__I]->weather[0]->icon?>.png" /></li>
					<li>온도 : <?=$data1->daily[$C__I]->temp->day?>℃</li>
					<li>체감온도 : <?=$data1->daily[$C__I]->feels_like->day?>℃</li>
					<li>최저온도 : <?=$data1->daily[$C__I]->temp->min?>℃</li>
					<li>최고온도 : <?=$data1->daily[$C__I]->temp->max?>℃</li>
					<li>습도 : <?=$data1->daily[$C__I]->humidity?>%</li>
					<li>풍속 : <?=$data1->daily[$C__I]->wind_speed?>m/s</li>
					<li>강수 확률 : <?=($data1->daily[$C__I]->pop * 100)?>%</li>

				</ul>
				<?
		}
	?>
</ol>
</dl>
<!-- <dl>
<ol>
	<ul style="list-style-type : none;">
		<li><?=$papagoTest2->message->result->translatedText?>의 현재날씨온도</li>
		<li><img src="https://openweathermap.org/img/wn/<?=$current_data2->weather[0]->icon?>.png" /><?=$current_data2->weather[0]->description?></li>
		<li>온도 : <?=$current_data2->main->temp?>℃</li>
		<li>체감온도 : <?=$current_data2->main->feels_like?>℃</li>
		<li>최저온도 : <?=$current_data2->main->temp_min?>℃</li>
		<li>최고온도 : <?=$current_data2->main->temp_max?>℃</li>
		<li>습도 : <?=$current_data2->main->humidity?>%</li>
		<li>풍속 : <?=$current_data2->wind->speed?>m/s</li>

	</ul>
</ol>
<ol>
	<?
		for ( $C__I = 1 ; $C__I < count($data2->daily); $C__I++ )
		{
				?>
				
				<ul style="float:left; list-style-type : none;">
					<li>날짜 : <?=date("Y-m-d", $data2->daily[$C__I]->dt)?></li>
					<li><?=$data2->daily[$C__I]->weather[0]->description?></li>
					<li><img src="https://openweathermap.org/img/wn/<?=$data2->daily[$C__I]->weather[0]->icon?>.png" /></li>
					<li>온도 : <?=$data2->daily[$C__I]->temp->day?>℃</li>
					<li>체감온도 : <?=$data2->daily[$C__I]->feels_like->day?>℃</li>
					<li>최저온도 : <?=$data2->daily[$C__I]->temp->min?>℃</li>
					<li>최고온도 : <?=$data2->daily[$C__I]->temp->max?>℃</li>
					<li>습도 : <?=$data2->daily[$C__I]->humidity?>%</li>
					<li>풍속 : <?=$data2->daily[$C__I]->wind_speed?>m/s</li>
					<li>강수 확률 : <?=($data2->daily[$C__I]->pop * 100)?>%</li>

				</ul>
				<?
		}
	?>
</ol>
</dl>
<dl>
<ol>
	<ul style="list-style-type : none;">
		<li><?=$papagoTest3->message->result->translatedText?>의 현재날씨온도</li>
		<li><img src="https://openweathermap.org/img/wn/<?=$current_data3->weather[0]->icon?>.png" /><?=$current_data3->weather[0]->description?></li>
		<li>온도 : <?=$current_data3->main->temp?>℃</li>
		<li>체감온도 : <?=$current_data3->main->feels_like?>℃</li>
		<li>최저온도 : <?=$current_data3->main->temp_min?>℃</li>
		<li>최고온도 : <?=$current_data3->main->temp_max?>℃</li>
		<li>습도 : <?=$current_data3->main->humidity?>%</li>
		<li>풍속 : <?=$current_data3->wind->speed?>m/s</li>

	</ul>
</ol>
<ol>
	<?
		for ( $C__I = 1 ; $C__I < count($data3->daily); $C__I++ )
		{
				?>
				
				<ul style="float:left; list-style-type : none;">
					<li>날짜 : <?=date("Y-m-d", $data3->daily[$C__I]->dt)?></li>
					<li><?=$data3->daily[$C__I]->weather[0]->description?></li>
					<li><img src="https://openweathermap.org/img/wn/<?=$data3->daily[$C__I]->weather[0]->icon?>.png" /></li>
					<li>온도 : <?=$data3->daily[$C__I]->temp->day?>℃</li>
					<li>체감온도 : <?=$data3->daily[$C__I]->feels_like->day?>℃</li>
					<li>최저온도 : <?=$data3->daily[$C__I]->temp->min?>℃</li>
					<li>최고온도 : <?=$data3->daily[$C__I]->temp->max?>℃</li>
					<li>습도 : <?=$data3->daily[$C__I]->humidity?>%</li>
					<li>풍속 : <?=$data3->daily[$C__I]->wind_speed?>m/s</li>
					<li>강수 확률 : <?=($data3->daily[$C__I]->pop * 100)?>%</li>

				</ul>
				<?
		}
	?>
</ol>
</dl> -->
 </body>
</html>