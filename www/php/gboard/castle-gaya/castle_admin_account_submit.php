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

/* 관리자 계정 재설정 */
if ($page['mode'] == "CONFIG_ACCOUNT") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['admin_id']))
		$submit['admin_id'] = $_POST['admin_id'];

	if (isset($_POST['admin_password']))
		$submit['admin_password'] = $_POST['admin_password'];

	if (isset($_POST['admin_repassword']))
		$submit['admin_repassword'] = $_POST['admin_repassword'];

	if (isset($_POST['admin_old_password']))
		$submit['admin_old_password'] = $_POST['admin_old_password'];

		// 예외 사항 체크
	$check['submit'] = array("admin_id", "admin_password", "admin_repassword", "admin_old_password", );
	castle_check_submit($check['submit']);

	if ($submit['admin_password'] != $submit['admin_repassword']) 
		html_msgback("암호와 확인 암호가 같지 않습니다.");

	$check['admin_id_length'] = strlen($submit['admin_id']);
	castle_check_length("관리자 아이디", $check['admin_id_length'], 4, 16);

	$check['admin_password_length'] = strlen($submit['admin_password']);
	castle_check_length("관리자 암호", $check['admin_password_length'], 8, 32);

	$check['admin_old_password_length'] = strlen($submit['admin_old_password']);
	castle_check_length("관리자 이전 암호", $check['admin_old_password_length'], 8, 32);

		// 이전 암호 확인
	$policy['admin_old_password'] =& $_CASTLE_POLICY['CONFIG']['ADMIN']['PASSWORD'];
	$policy['admin_old_password'] = base64_decode($policy['admin_old_password']);

	$submit['admin_old_password'] = md5(md5($submit['admin_old_password']));

	if ($submit['admin_old_password'] != $policy['admin_old_password'])
		html_msgback("이전 암호가 정확하지 않습니다.");

	/* CASTLE 정책 정보 수정: 관리자 계정 아이디 및 암호 설정 */
		// 관리자 아이디 설정
	$_CASTLE_POLICY['CONFIG']['ADMIN']['ID'] = base64_encode($submit['admin_id']);
		// 관리자 암호 설정
	$_CASTLE_POLICY['CONFIG']['ADMIN']['PASSWORD'] = base64_encode(md5(md5($submit['admin_password'])));

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("관리자 계정 정보가 수정되었습니다.", "castle_admin_account.php");

	exit;
}
/* 관리자 계정 재설정 끝 */

?>
