<?php	

	$CLIENT_ID     = "42189112197-7s4a36o9naqce0vin23ahf09tae6dkag.apps.googleusercontent.com";
	$CLIENT_SECRET_ID     = "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";
    $REDIRECT_URI  = "https://tjrals627.cafe24.com/php/board30/google_Callback.php";
    $TOKEN_API_URL = "https://www.googleapis.com/oauth2/v4/token";

	$code = $_REQUEST["code"];

	$postdata =sprintf("grant_type=authorization_code&access_type=offline&client_id=%s&client_secret=%s&redirect_uri=%s&code=%s",$CLIENT_ID,$CLIENT_SECRET_ID,$REDIRECT_URI,$code);
	
	//사용자 토큰 받기
	$opts = array( 
					CURLOPT_URL => $TOKEN_API_URL, 
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSLVERSION => 1, // TLS 
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $postdata, 
					CURLOPT_RETURNTRANSFER => true, 
					CURLOPT_HEADER => false 
				); 
	$curlSession = curl_init(); 
	curl_setopt_array($curlSession, $opts); 
	$accessTokenJson = curl_exec($curlSession);
	curl_close($curlSession); 
	$responseArr = json_decode($accessTokenJson, true); 
	$access_token = $responseArr['access_token'];

	//사용자 정보 가저오기 
	$USER_API_URL= "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=" . $access_token; 
	$opts = array( 
					CURLOPT_URL => $USER_API_URL, 
					CURLOPT_SSL_VERIFYPEER => false, 
					CURLOPT_SSLVERSION => 1, 
					CURLOPT_POST => true, 
					CURLOPT_POSTFIELDS => false, 
					CURLOPT_RETURNTRANSFER => true, 
					CURLOPT_HTTPHEADER => array( "Authorization: Bearer " . $responseArr['access_token'] ) 
				); 
	$curlSession = curl_init(); 
	curl_setopt_array($curlSession, $opts); 
	$accessUserJson = curl_exec($curlSession); 
	curl_close($curlSession);
	$me_responseArr = json_decode($accessUserJson, true); 

	$USERID = $me_responseArr['user_id'];
?>
<!-- <script type="text/javascript">
	window.close();
	opener.parent.location='https://tjrals627.cafe24.com/php/board30/logintest.php?id=<?=$USERID?>&CLIENT_ID=<?=$CLIENT_ID?>';
</script> -->