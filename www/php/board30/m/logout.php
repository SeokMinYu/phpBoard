<?php
	session_start();

	header('Content-Type : text/html; charset=utf-8');
	
	session_destroy();
	session_unset();

	// NAVER LOGIN 
	define('NAVER_CLIENT_ID', 'Wjga03g4pTVj7V7xhvcV'); 
	define('NAVER_CLIENT_SECRET', 'EFtJt4C8N2'); 
	// 네이버 접근 토큰 삭제 
	$naver_curl = "https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=".NAVER_CLIENT_ID."&client_secret=".NAVER_CLIENT_SECRET."&access_token=".$_SESSION['naver_access_token']."&service_provider=NAVER";
	$is_post = false; 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $naver_curl); 
	curl_setopt($ch, CURLOPT_POST, $is_post); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$response = curl_exec ($ch); 
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	curl_close ($ch);
	
	$access_token = $_SESSION['kakao_access_token']; 
	$UNLINK_API_URL = "https://kapi.kakao.com/v1/user/unlink"; 
	$opts = array( 
					CURLOPT_URL => $UNLINK_API_URL, 
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSLVERSION => 1, 
					CURLOPT_POST => true, 
					CURLOPT_POSTFIELDS => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HTTPHEADER => array( "Authorization: Bearer " . $access_token ) 
				);
	$curlSession = curl_init(); 
	curl_setopt_array($curlSession, $opts); 
	$accessUnlinkJson = curl_exec($curlSession);
	curl_close($curlSession); 
	$unlink_responseArr = json_decode($accessUnlinkJson, true);

	echo "<script>alert('로그아웃 되었습니다.'); location.href='login.php';</script>";
?>