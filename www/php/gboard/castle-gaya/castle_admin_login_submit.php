<?php 

if ( !( in_array( $_SERVER["REMOTE_ADDR"], array("106.246.89.150", "999.999.999.999") ) ) ) exit();

//@UTF-8 castle_admin_login_submit.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Sep 18 2008
 *
 */

/* 인증 검사 예외 페이지 */
$exception['check_authorized'] = TRUE;

include_once("castle_admin_lib.php");

/* 내부 변수 초기화 */
castle_init_page();

/* 내부 변수 초기화 끝 */

/* 요청 변수 처리 */
castle_clear_submit();

if (isset($_POST['admin_id']))
	$submit['admin_id'] = $_POST['admin_id'];

if (isset($_POST['admin_password']))
	$submit['admin_password'] = $_POST['admin_password'];

	// 예외 사항 체크
$check['submit'] = array("admin_id", "admin_password", );
castle_check_submit($check['submit']);

$check['admin_id_length'] = strlen($submit['admin_id']);
castle_check_length("관리자 아이디", $check['admin_id_length'], 4, 16);

$check['admin_password_length'] = strlen($submit['admin_password']);
castle_check_length("관리자 암호", $check['admin_password_length'], 8, 32);

/* CASTLE 정책 정보: 관리자 계정 아이디 및 암호 가져오기 */
$policy['admin_id'] = "";
$policy['admin_password'] = "";

	// 관리자 아이디 
if (isset($_CASTLE_POLICY['CONFIG']['ADMIN']['ID']))
	$policy['admin_id'] = $_CASTLE_POLICY['CONFIG']['ADMIN']['ID'];

	// 관리자 암호
if (isset($_CASTLE_POLICY['CONFIG']['ADMIN']['PASSWORD']))
	$policy['admin_password'] = $_CASTLE_POLICY['CONFIG']['ADMIN']['PASSWORD'];

	// BASE64 디코딩
$policy['admin_id'] = base64_decode($policy['admin_id']);
$policy['admin_password'] = base64_decode($policy['admin_password']);

	// 아이디 & 암호 검사
$submit['admin_password'] = md5(md5($submit['admin_password']));

if ($submit['admin_id'] != $policy['admin_id'])
	html_msgback("잘못된 인증 정보입니다.");

if ($submit['admin_password'] != $policy['admin_password'])
	html_msgback("잘못된 인증 정보입니다.");

	// 인증 세션 생성
$auth['remote_addr'] = getenv("REMOTE_ADDR"); 
$auth['user_agent'] = getenv("HTTP_USER_AGENT"); 

$auth['key'] = "castle_auth_token_".$auth['remote_addr'];
$auth['value'] = md5($auth['user_agent']);

$_SESSION[$auth['key']] = $auth['value'];

html_msgmove("관리자 인증 되었습니다.", "castle_admin.php");

exit;
/* 관리자 인증 끝 */

?>
