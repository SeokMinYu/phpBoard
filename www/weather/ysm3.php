<meta charset="UTF-8">
<?
	$Url = "https://tjrals627.cafe24.com/weather/gaya.php";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $Url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);

	echo $response;
	
	curl_close($ch);

	$data = json_decode($response);

	echo "<br><br>";
	echo $data->{"list"}[0]->{"main"}->{"temp"};

	$jsonArr = Array(
						"Aa" => Array
						(
							"a" => "A",
							"a22" => Array
							(
								"a2" => "A2",
								"a3" => "A3"
							),
							"a1" => "A1",
							"a2" => "A2"
						),
						"Ba" => Array
						(
							"b22" => Array
							(
								"b2" => "B2",
								"b3" => "B3"
							),
							"b" => "B",
							"b1" => "B1"
						),
						"Cc" => Array
						(
							"c" => "C",
							"c1" => "C1",
							"c22" => Array
							(
								"c2" => "C2",
								"c3" => "C3"
							)
						)
					);
		$jsonTest = json_encode($jsonArr);
?>
	<p><?=$jsonTest?></p>