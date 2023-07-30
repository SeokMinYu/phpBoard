<?php 

//@UTF-8 castle_admin_account_submit.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Sep 18 2008
 *
 */

include_once("castle_admin_lib.php");
castle_check_authorized();

/* 내부 변수 초기화 */
castle_init_page();

//$page['mode'] : 기본 설정 모드 변수
if (isset($_GET) && isset($_GET['mode']))
	$page['mode'] = $_GET['mode'];

/* 내부 변수 초기화 끝 */

/* 정책 기본 설정 재설정 */
if ($page['mode'] == "CONFIG_BASIC") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['admin_module_name']))
		$submit['admin_module_name'] = $_POST['admin_module_name'];

	if (isset($_POST['config_mode']))
		$submit['config_mode'] = $_POST['config_mode'];

	if (isset($_POST['config_alert']))
		$submit['config_alert'] = $_POST['config_alert'];

		// 예외 사항 체크
	$check['submit'] = array("admin_module_name", "config_mode", "config_alert", );
	castle_check_submit($check['submit']);

	$check['module_name_length'] = strlen($submit['admin_module_name']);
	castle_check_length("CASTLE 이름", $check['module_name_length'], 4, 64);

	/* CASTLE 정책 정보 수정: 기본 설정 정보 */
		// 변수 트림
	$submit['admin_module_name'] = trim($submit['admin_module_name']);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
	if (get_magic_quotes_gpc()) 
		$submit['admin_module_name'] = stripslashes($submit['admin_module_name']);

		// CASTLE 이름 설정
	$_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME'] = base64_encode($submit['admin_module_name']);

		// 적용 모드 설정
	$_CASTLE_POLICY['CONFIG']['MODE']['ENFORCING'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['MODE']['PERMISSIVE'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['MODE']['DISABLED'] = base64_encode("FALSE");

	if ($submit['config_mode'] == "enforcing")
		$_CASTLE_POLICY['CONFIG']['MODE']['ENFORCING'] = base64_encode("TRUE");

	if ($submit['config_mode'] == "permissive")
		$_CASTLE_POLICY['CONFIG']['MODE']['PERMISSIVE'] = base64_encode("TRUE");

	if ($submit['config_mode'] == "disabled")
		$_CASTLE_POLICY['CONFIG']['MODE']['DISABLED'] = base64_encode("TRUE");

		// 기록모드 설정
	$_CASTLE_POLICY['CONFIG']['ALERT']['ALERT'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['ALERT']['MESSAGE'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['ALERT']['STEALTH'] = base64_encode("FALSE");

	if ($submit['config_alert'] == "alert")
		$_CASTLE_POLICY['CONFIG']['ALERT']['ALERT'] = base64_encode("TRUE");
		
	if ($submit['config_alert'] == "message")
		$_CASTLE_POLICY['CONFIG']['ALERT']['MESSAGE'] = base64_encode("TRUE");

	if ($submit['config_alert'] == "stealth")
		$_CASTLE_POLICY['CONFIG']['ALERT']['STEALTH'] = base64_encode("TRUE");

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("CASTLE 정책 기본설정 정보가 수정되었습니다.", "castle_admin_config.php#basic");

	exit;
}
/* 정책 기본 설정 재설정 끝 */

/* SITE 정책 재설정 */
if ($page['mode'] == "CONFIG_SITE") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['config_site_bool']))
		$submit['config_site_bool'] = $_POST['config_site_bool'];

		// 예외 사항 체크
	$check['submit'] = array("config_site_bool", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: SITE 정책 수정 */
		// 적용 모드 설정
	if ($submit['config_site_bool'] == "true")
		$_CASTLE_POLICY['CONFIG']['SITE']['BOOL'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['CONFIG']['SITE']['BOOL'] = base64_encode("FALSE");

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("CASTLE 정책 SITE 정책이 수정되었습니다.", "castle_admin_config.php#site");

	exit;
}
/* SITE 정책 재설정 끝 */

/* 캐슬 적용대상 설정 재설정 */
if ($page['mode'] == "CONFIG_TARGET") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['target_get']))
		$submit['target_get'] = $_POST['target_get'];

	if (isset($_POST['target_post']))
		$submit['target_post'] = $_POST['target_post'];

	if (isset($_POST['target_cookie']))
		$submit['target_cookie'] = $_POST['target_cookie'];

	if (isset($_POST['target_file']))
		$submit['target_file'] = $_POST['target_file'];

		// 예외 사항 체크
	$check['submit'] = array("target_get", "target_post", 
							 "target_cookie", "target_file", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: 기본 설정 정보 */
		// 적용 대상 설정
	$_CASTLE_POLICY['CONFIG']['TARGET']['GET'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['TARGET']['POST'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['TARGET']['FILE'] = base64_encode("FALSE");
	$_CASTLE_POLICY['CONFIG']['TARGET']['COOKIE'] = base64_encode("FALSE");

	if ($submit['target_get'] == "true")
		$_CASTLE_POLICY['CONFIG']['TARGET']['GET'] = base64_encode("TRUE");

	if ($submit['target_post'] == "true")
		$_CASTLE_POLICY['CONFIG']['TARGET']['POST'] = base64_encode("TRUE");

	if ($submit['target_cookie'] == "true")
		$_CASTLE_POLICY['CONFIG']['TARGET']['COOKIE'] = base64_encode("TRUE");

	if ($submit['target_file'] == "true")
		$_CASTLE_POLICY['CONFIG']['TARGET']['FILE'] = base64_encode("TRUE");

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("CASTLE 정책 적용 대상 정보가 수정되었습니다.", "castle_admin_config.php#target");

	exit;
}
/* 캐슬 적용대상 설정 재설정 끝 */

?>
