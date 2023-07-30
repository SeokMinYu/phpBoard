<?php 

//@UTF-8 castle_admin_policy_submit.php
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

/* SQL_INJECTION 정책 재설정 */
if ($page['mode'] == "POLICY_SQL_INJECTION") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['policy_sql_injection']))
		$submit['policy_sql_injection'] = $_POST['policy_sql_injection'];

	if (isset($_POST['policy_sql_injection_list']))
		$submit['policy_sql_injection_list'] = $_POST['policy_sql_injection_list'];

		// 예외 사항 체크
	$check['submit'] = array("policy_sql_injection", "policy_sql_injection_list", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: SQL_INJECTION 정책 수정 */
		// 적용 모드 설정

	if ($submit['policy_sql_injection'] == "true")
		$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['BOOL'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['BOOL'] = base64_encode("FALSE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST']);

	$index = 0;

	$string = $submit['policy_sql_injection_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST'][$index++] = base64_encode($token);

		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST']['0'] = "";

		html_msg("SQL_INJECTION 정책 목록이 없어 비설정으로 설정합니다.");
	}

	// CASTLE 정책 쓰기
		castle_write_policy();

	html_msgmove("SQL_INJECTION 정책이 수정되었습니다.", "castle_admin_policy.php#sql_injection");

	exit;
}
/* SQL_INJECTION 정책 재설정 끝 */

/* XSS 정책 재설정 */
if ($page['mode'] == "POLICY_XSS") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['policy_xss']))
		$submit['policy_xss'] = $_POST['policy_xss'];

	if (isset($_POST['policy_xss_list']))
		$submit['policy_xss_list'] = $_POST['policy_xss_list'];

		// 예외 사항 체크
	$check['submit'] = array("policy_xss", "policy_xss_list", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: XSS 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['XSS']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_xss'] == "true")
		$_CASTLE_POLICY['POLICY']['XSS']['BOOL'] = base64_encode("TRUE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['XSS']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['XSS']['LIST']);

	$index = 0;

	$string = $submit['policy_xss_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['XSS']['LIST'][$index++] = base64_encode($token);
			
		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['XSS']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['XSS']['LIST']['0'] = "";

		html_msg("XSS 정책 목록이 없어 비설정으로 설정합니다.");
	}

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("XSS 정책이 수정되었습니다.", "castle_admin_policy.php#xss");

	exit;
}
/* XSS 정책 재설정 끝 */

/* WORD 정책 재설정 */
if ($page['mode'] == "POLICY_WORD") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['policy_word']))
		$submit['policy_word'] = $_POST['policy_word'];

	if (isset($_POST['policy_word_list']))
		$submit['policy_word_list'] = $_POST['policy_word_list'];

		// 예외 사항 체크
	$check['submit'] = array("policy_word", "policy_word_list", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: WORD 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['WORD']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_word'] == "true")
		$_CASTLE_POLICY['POLICY']['WORD']['BOOL'] = base64_encode("TRUE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['WORD']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['WORD']['LIST']);

	$index = 0;

	$string = $submit['policy_word_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['WORD']['LIST'][$index++] = base64_encode($token);

		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['WORD']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['WORD']['LIST']['0'] = "";

		html_msg("WORD 정책 목록이 없어 비설정으로 설정합니다.");
	}

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("WORD 정책이 수정되었습니다.", "castle_admin_policy.php#word");

	exit;
}
/* WORD 정책 재설정 끝 */

/* TAG 정책 재설정 */
if ($page['mode'] == "POLICY_TAG") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['policy_tag']))
		$submit['policy_tag'] = $_POST['policy_tag'];

	if (isset($_POST['policy_tag_list']))
		$submit['policy_tag_list'] = $_POST['policy_tag_list'];

		// 예외 사항 체크
	$check['submit'] = array("policy_tag", "policy_tag_list", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: TAG 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['TAG']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_tag'] == "true")
		$_CASTLE_POLICY['POLICY']['TAG']['BOOL'] = base64_encode("TRUE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['TAG']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['TAG']['LIST']);

	$index = 0;

	$string = $submit['policy_tag_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['TAG']['LIST'][$index++] = base64_encode($token);

		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['TAG']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['TAG']['LIST']['0'] = "";

		html_msg("TAG 정책 목록이 없어 비설정으로 설정합니다.");
	}

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("TAG 정책이 수정되었습니다.", "castle_admin_policy.php#tag");

	exit;
}
/* TAG 정책 재설정 끝 */

/* IP 정책 재설정 */
if ($page['mode'] == "POLICY_IP") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['policy_ip']))
		$submit['policy_ip'] = $_POST['policy_ip'];

	if (isset($_POST['policy_ip_base']))
		$submit['policy_ip_base'] = $_POST['policy_ip_base'];

	if (isset($_POST['policy_ip_list']))
		$submit['policy_ip_list'] = $_POST['policy_ip_list'];

		// 예외 사항 체크
	$check['submit'] = array("policy_ip", "policy_ip_base", "policy_ip_list", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: IP 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['IP']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_ip'] == "true")
		$_CASTLE_POLICY['POLICY']['IP']['BOOL'] = base64_encode("TRUE");

		// 적용기반 설정
	$_CASTLE_POLICY['POLICY']['IP']['ALLOW'] = base64_encode("FALSE");
	$_CASTLE_POLICY['POLICY']['IP']['DENY'] = base64_encode("FALSE");

	if ($submit['policy_ip_base'] == "allow")
		$_CASTLE_POLICY['POLICY']['IP']['ALLOW'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['POLICY']['IP']['DENY'] = base64_encode("TRUE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['IP']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['IP']['LIST']);

	$index = 0;

	$string = $submit['policy_ip_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['IP']['LIST'][$index++] = base64_encode($token);

		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['IP']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['IP']['LIST']['0'] = "";

		html_msg("IP 정책 목록이 없어 비설정으로 설정합니다.");
	}

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("IP 정책이 수정되었습니다.", "castle_admin_policy.php#ip");

	exit;
}
/* IP 정책 재설정 끝 */

/* FILE 정책 재설정 */
if ($page['mode'] == "POLICY_FILE") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['policy_filename']))
		$submit['policy_filename'] = $_POST['policy_filename'];

	if (isset($_POST['policy_filename_base']))
		$submit['policy_filename_base'] = $_POST['policy_filename_base'];

	if (isset($_POST['policy_filename_list']))
		$submit['policy_filename_list'] = $_POST['policy_filename_list'];

	if (isset($_POST['policy_filetype']))
		$submit['policy_filetype'] = $_POST['policy_filetype'];

	if (isset($_POST['policy_filetype_base']))
		$submit['policy_filetype_base'] = $_POST['policy_filetype_base'];

	if (isset($_POST['policy_filetype_list']))
		$submit['policy_filetype_list'] = $_POST['policy_filetype_list'];

	if (isset($_POST['policy_filesize']))
		$submit['policy_filesize'] = $_POST['policy_filesize'];

	if (isset($_POST['policy_filesize_min_size']))
		$submit['policy_filesize_min_size'] = $_POST['policy_filesize_min_size'];

	if (isset($_POST['policy_filesize_max_size']))
		$submit['policy_filesize_max_size'] = $_POST['policy_filesize_max_size'];

		// 예외 사항 체크
	$check['submit'] = array("policy_filename", "policy_filename_base", "policy_filename_list", 
							 "policy_filetype", "policy_filetype_base", "policy_filetype_list", 
							 "policy_filesize", "policy_filesize_min_size", "policy_filesize_max_size", );
	castle_check_submit($check['submit']);

	if (!strlen($submit['policy_filesize_min_size']))
		html_msgback("파일 최소 크기 값이 입력되지 않았습니다.");

	if (!strlen($submit['policy_filesize_max_size']))
		html_msgback("파일 최대 크기 값이 입력되지 않았습니다.");

	/* CASTLE 정책 정보 수정: FILENAME 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['FILENAME']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_filename'] == "true")
		$_CASTLE_POLICY['POLICY']['FILENAME']['BOOL'] = base64_encode("TRUE");

		// 적용기반 설정
	$_CASTLE_POLICY['POLICY']['FILENAME']['ALLOW'] = base64_encode("FALSE");
	$_CASTLE_POLICY['POLICY']['FILENAME']['DENY'] = base64_encode("FALSE");

	if ($submit['policy_filename_base'] == "allow")
		$_CASTLE_POLICY['POLICY']['FILENAME']['ALLOW'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['POLICY']['FILENAME']['DENY'] = base64_encode("TRUE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['FILENAME']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['FILENAME']['LIST']);

	$index = 0;

	$string = $submit['policy_filename_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['FILENAME']['LIST'][$index++] = base64_encode($token);

		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['FILENAME']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['FILENAME']['LIST']['0'] = "";

		html_msg("FILENAME 정책 목록이 없어 비설정으로 설정합니다.");
	}

	/* CASTLE 정책 정보 수정: FILETYPE 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['FILETYPE']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_filetype'] == "true")
		$_CASTLE_POLICY['POLICY']['FILETYPE']['BOOL'] = base64_encode("TRUE");

		// 적용기반 설정
	$_CASTLE_POLICY['POLICY']['FILETYPE']['ALLOW'] = base64_encode("FALSE");
	$_CASTLE_POLICY['POLICY']['FILETYPE']['DENY'] = base64_encode("FALSE");

	if ($submit['policy_filetype_base'] == "allow")
		$_CASTLE_POLICY['POLICY']['FILETYPE']['ALLOW'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['POLICY']['FILETYPE']['DENY'] = base64_encode("TRUE");

		// 목록 설정
	if (isset($_CASTLE_POLICY['POLICY']['FILETYPE']['LIST']))
		unset($_CASTLE_POLICY['POLICY']['FILETYPE']['LIST']);

	$index = 0;

	$string = $submit['policy_filetype_list'];
	$string = trim($string);

	$token = strtok($string, ", \n\t");
	while ($token) 
	{
			// 변수 트림
		$token = trim($token);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
		if (get_magic_quotes_gpc()) 
			$token = stripslashes($token);

		if (strlen($token) > 0)
			$_CASTLE_POLICY['POLICY']['FILETYPE']['LIST'][$index++] = base64_encode($token);

		$token = strtok(", \n\t");
	}

		// 정책이 하나도 없을 경우 비적용 상태로 바꿈
	if (!$index) 
	{
		$_CASTLE_POLICY['POLICY']['FILETYPE']['BOOL'] = base64_encode("FALSE");
		$_CASTLE_POLICY['POLICY']['FILETYPE']['LIST']['0'] = "";

		html_msg("FILETYPE 정책 목록이 없어 비설정으로 설정합니다.");
	}

	/* CASTLE 정책 정보 수정: FILESIZE 정책 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['POLICY']['FILESIZE']['BOOL'] = base64_encode("FALSE");

	if ($submit['policy_filesize'] == "true")
		$_CASTLE_POLICY['POLICY']['FILESIZE']['BOOL'] = base64_encode("TRUE");

		// 최소&최대값 설정
	$min_size = $submit['policy_filesize_min_size'];
	$max_size = $submit['policy_filesize_max_size'];

	if (isset($_CASTLE_POLICY['POLICY']['FILESIZE']['MIN_SIZE']))
		unset($_CASTLE_POLICY['POLICY']['FILESIZE']['MIN_SIZE']);

	if (isset($_CASTLE_POLICY['POLICY']['FILESIZE']['MAX_SIZE']))
		unset($_CASTLE_POLICY['POLICY']['FILESIZE']['MAX_SIZE']);

	$min_size = trim($min_size);
	$max_size = trim($max_size);

	$_CASTLE_POLICY['POLICY']['FILESIZE']['MIN_SIZE'] = base64_encode($min_size);
	$_CASTLE_POLICY['POLICY']['FILESIZE']['MAX_SIZE'] = base64_encode($max_size);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("FILENAME 정책이 수정되었습니다.", "castle_admin_policy.php#file");

	exit;
}
/* FILE 정책 재설정 끝 */

?>
