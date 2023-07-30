<?php
include "DBconnect.php";
session_start(); 

$code = $_GET["code"]; // 서버로 부터 토큰을 발급받을 수 있는 코드를 받아옵니다.
define('KAKAO_CLIENT_ID', '5878a9c120240be2d4dfef9970105d9c');
define('KAKAO_CALLBACK_URL', 'https://tjrals627.cafe24.com/php/board30/kakao_Callback.php');

// 카카오 로그인 접근토큰 요청 예제
$kakao_state = md5(microtime() . mt_rand());
$_SESSION['kakao_state'] = $kakao_state;
$kakao_apiURL ="https://kauth.kakao.com/oauth/authorize?client_id=".KAKAO_CLIENT_ID."&redirect_uri=".urlencode(KAKAO_CALLBACK_URL)."&code=".$code."&state=".$kakao_state;

$isPost = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, KAKAO_CALLBACK_URL);
curl_setopt($ch, CURLOPT_POST, $isPost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array();
$loginResponse = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);  

$accessToken= json_decode($loginResponse)->access_token;
					
?>
