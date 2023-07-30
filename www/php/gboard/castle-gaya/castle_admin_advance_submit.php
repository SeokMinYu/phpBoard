<?php 

//@UTF-8 castle_admin_advance_submit.php
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

/* PAGE_INSERT 재설정 */
if ($page['mode'] == "PAGE_INSERT") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['page_name']))
		$submit['page_name'] = $_POST['page_name'];
		
	if (isset($_POST['page_bool']))
		$submit['page_bool'] = $_POST['page_bool'];

	if (isset($_POST['page_base']))
		$submit['page_base'] = $_POST['page_base'];

		// 예외 사항 체크
	$check['submit'] = array("page_name", "page_bool", "page_base", );
	castle_check_submit($check['submit']);

		// 변수 트림
	$submit['page_name'] = trim($submit['page_name']);

		// 디렉터리 트레버스 패턴 제거
	$submit['page_name'] = castle_delete_directory_traverse($submit['page_name']);

	$check['page_name_length'] = strlen($submit['page_name']);
	castle_check_length("페이지 이름", $check['page_name_length'], 4, 64);

	if (isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]))
		html_msgback($submit['page_name']." 페이지는 이미 존재합니다.");

	/* CASTLE 정책 정보 수정: 페이지 추가 */
		// 적용 모드 설정
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['BOOL'] = base64_encode("FALSE");
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['ALLOW'] = base64_encode("FALSE");
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['DENY'] = base64_encode("FALSE");

	if ($submit['page_bool'] == "true")
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['BOOL'] = base64_encode("TRUE");

	if ($submit['page_base'] == "allow")
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['ALLOW'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['DENY'] = base64_encode("TRUE");

		// 페이지 목록 이름 기준으로 정렬
	ksort($_CASTLE_POLICY['ADVANCE']['PAGE']);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("페이지가 추가 되었습니다.", "castle_admin_advance.php");

	exit;
}
/* PAGE_INSERT 재설정 끝 */

/* PAGE_MODIFY 재설정 */
if ($page['mode'] == "PAGE_MODIFY") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['page_name']))
		$submit['page_name'] = $_POST['page_name'];
		
	if (isset($_POST['page_bool']))
		$submit['page_bool'] = $_POST['page_bool'];

	if (isset($_POST['page_base']))
		$submit['page_base'] = $_POST['page_base'];

		// 예외 사항 체크
	$check['submit'] = array("page_name", "page_bool", "page_base", );
	castle_check_submit($check['submit']);

		// 변수 트림
	$submit['page_name'] = trim($submit['page_name']);

		// 디렉터리 트레버스 패턴 제거
	$submit['page_name'] = castle_delete_directory_traverse($submit['page_name']);

	/* CASTLE 정책 정보 수정: 페이지 설정 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['BOOL'] = base64_encode("FALSE");
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['ALLOW'] = base64_encode("FALSE");
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['DENY'] = base64_encode("FALSE");

	if ($submit['page_bool'] == "true")
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['BOOL'] = base64_encode("TRUE");

	if ($submit['page_base'] == "allow")
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['ALLOW'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['DENY'] = base64_encode("TRUE");

		// 페이지 목록 이름 기준으로 정렬
	ksort($_CASTLE_POLICY['ADVANCE']['PAGE']);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("페이지가 수정 되었습니다.", "castle_admin_advance.php");

	exit;
}
/* PAGE_MODIFY 재설정 끝 */

/* PAGE_DELETE 재설정 */
if ($page['mode'] == "PAGE_DELETE") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_GET['page_name']))
		$submit['page_name'] = $_GET['page_name'];

		// 예외 사항 체크
	$check['submit'] = array("page_name", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: 페이지 설정 삭제 */
		// 메모리 해지
	if (isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]))
		unset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("페이지가 삭제 되었습니다.", "castle_admin_advance.php");

	exit;
}
/* PAGE_DELETE 재설정 끝 */

/* PAGE_MODIFY_DETAIL 재설정 */
if ($page['mode'] == "PAGE_MODIFY_DETAIL") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['page_name']))
		$submit['page_name'] = $_POST['page_name'];
		
	if (isset($_POST['page_bool']))
		$submit['page_bool'] = $_POST['page_bool'];

	if (isset($_POST['page_base']))
		$submit['page_base'] = $_POST['page_base'];

		// 예외 사항 체크
	$check['submit'] = array("page_name", "page_bool", "page_base", );
	castle_check_submit($check['submit']);

		// 변수 트림
	$submit['page_name'] = trim($submit['page_name']);

		// 디렉터리 트레버스 패턴 제거
	$submit['page_name'] = castle_delete_directory_traverse($submit['page_name']);

	/* CASTLE 정책 정보 수정: 페이지 설정 수정 */
		// 적용 모드 설정
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['BOOL'] = base64_encode("FALSE");
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['ALLOW'] = base64_encode("FALSE");
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['DENY'] = base64_encode("FALSE");

	if ($submit['page_bool'] == "true")
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['BOOL'] = base64_encode("TRUE");

	if ($submit['page_base'] == "allow")
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['ALLOW'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['DENY'] = base64_encode("TRUE");

		// 페이지 목록 이름 기준으로 정렬
	ksort($_CASTLE_POLICY['ADVANCE']['PAGE']);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("페이지가 수정 되었습니다.", "castle_admin_advance_detail.php?page_name=".$submit['page_name']);

	exit;
}
/* PAGE_MODIFY_DETAIL 재설정 끝 */

/* VAR_INSERT 재설정 */
if ($page['mode'] == "VAR_INSERT") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['page_name']))
		$submit['page_name'] = $_POST['page_name'];

	if (isset($_POST['var_name']))
		$submit['var_name'] = $_POST['var_name'];

	if (isset($_POST['var_format']))
		$submit['var_format'] = $_POST['var_format'];

	if (isset($_POST['var_min_length']))
		$submit['var_min_length'] = $_POST['var_min_length'];

	if (isset($_POST['var_max_length']))
		$submit['var_max_length'] = $_POST['var_max_length'];

	if (isset($_POST['var_method_get']))
		$submit['var_method_get'] = "TRUE";
	else
		$submit['var_method_get'] = "FALSE";

	if (isset($_POST['var_method_post']))
		$submit['var_method_post'] = "TRUE";
	else
		$submit['var_method_post'] = "FALSE";

	if (isset($_POST['var_target_sql_injection']))
		$submit['var_target_sql_injection'] = "TRUE";
	else
		$submit['var_target_sql_injection'] = "FALSE";

	if (isset($_POST['var_target_xss']))
		$submit['var_target_xss'] = "TRUE";
	else
		$submit['var_target_xss'] = "FALSE";

	if (isset($_POST['var_target_word']))
		$submit['var_target_word'] = "TRUE";
	else
		$submit['var_target_word'] = "FALSE";

	if (isset($_POST['var_target_tag']))
		$submit['var_target_tag'] = "TRUE";
	else
		$submit['var_target_tag'] = "FALSE";

		// 예외 사항 체크
	$check['submit'] = array("page_name", "var_name", "var_format", 
						  "var_min_length", "var_max_length", );
	castle_check_submit($check['submit']);

		// 변수 트림
	$submit['var_name'] = trim($submit['var_name']);
	$submit['var_format'] = trim($submit['var_format']);
	$submit['var_format'] = trim($submit['var_format']);
	$submit['var_min_length'] = trim($submit['var_min_length']);
	$submit['var_max_length'] = trim($submit['var_max_length']);
	$submit['var_method_get'] = trim($submit['var_method_get']);
	$submit['var_method_post'] = trim($submit['var_method_post']);
	$submit['var_target_sql_injection'] = trim($submit['var_target_sql_injection']);
	$submit['var_target_xss'] = trim($submit['var_target_xss']);
	$submit['var_target_word'] = trim($submit['var_target_word']);
	$submit['var_target_tag'] = trim($submit['var_target_tag']);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
	if (get_magic_quotes_gpc()) {
		$submit['var_name'] = stripslashes($submit['var_name']);
		$submit['var_format'] = stripslashes($submit['var_format']);
		$submit['var_min_length'] = stripslashes($submit['var_min_length']);
		$submit['var_max_length'] = stripslashes($submit['var_max_length']);
		$submit['var_method_get'] = stripslashes($submit['var_method_get']);
		$submit['var_method_post'] = stripslashes($submit['var_method_post']);
		$submit['var_target_sql_injection'] = stripslashes($submit['var_target_sql_injection']);
		$submit['var_target_xss'] = stripslashes($submit['var_target_xss']);
		$submit['var_target_word'] = stripslashes($submit['var_target_word']);
		$submit['var_target_tag'] = stripslashes($submit['var_target_tag']);
	}

	if (!isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]))
		html_msgback($submit['page_name']." 페이지는 없는 페이지입니다.");

	$check['var_name_length'] = strlen($submit['var_name']);
	castle_check_length("변수 이름", $check['var_name_length'], 1, MAX_VAR_LENGTH);

	$policy['page'] =& $_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']];

	if (isset($policy['page']['LIST'][$submit['var_name']]))
		html_msgback($submit['var_name']." 변수는 이미 존재합니다.");

	/* CASTLE 정책 정보 수정: 변수 설정 추가 */

		// 형태 설정
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['FORMAT'] = base64_encode($submit['var_format']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['MIN_LENGTH'] = base64_encode($submit['var_min_length']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['MAX_LENGTH'] = base64_encode($submit['var_max_length']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['METHOD']['GET'] = base64_encode($submit['var_method_get']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['METHOD']['POST'] = base64_encode($submit['var_method_post']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['SQL_INJECTION'] = base64_encode($submit['var_target_sql_injection']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['XSS'] = base64_encode($submit['var_target_xss']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['WORD'] = base64_encode($submit['var_target_word']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['TAG'] = base64_encode($submit['var_target_tag']);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("변수가 추가 되었습니다.", "castle_admin_advance_detail.php?page_name=".$submit['page_name']);

	exit;
}
/* VAR_INSERT 재설정 끝 */

/* VAR_MODIFY 재설정 */
if ($page['mode'] == "VAR_MODIFY") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_POST['page_name']))
		$submit['page_name'] = $_POST['page_name'];

	if (isset($_POST['var_name']))
		$submit['var_name'] = $_POST['var_name'];

	if (isset($_POST['var_format']))
		$submit['var_format'] = $_POST['var_format'];

	if (isset($_POST['var_min_length']))
		$submit['var_min_length'] = $_POST['var_min_length'];

	if (isset($_POST['var_max_length']))
		$submit['var_max_length'] = $_POST['var_max_length'];

	if (isset($_POST['var_method_get']))
		$submit['var_method_get'] = "TRUE";
	else
		$submit['var_method_get'] = "FALSE";

	if (isset($_POST['var_method_post']))
		$submit['var_method_post'] = "TRUE";
	else
		$submit['var_method_post'] = "FALSE";

	if (isset($_POST['var_target_sql_injection']))
		$submit['var_target_sql_injection'] = "TRUE";
	else
		$submit['var_target_sql_injection'] = "FALSE";

	if (isset($_POST['var_target_xss']))
		$submit['var_target_xss'] = "TRUE";
	else
		$submit['var_target_xss'] = "FALSE";

	if (isset($_POST['var_target_word']))
		$submit['var_target_word'] = "TRUE";
	else
		$submit['var_target_word'] = "FALSE";

	if (isset($_POST['var_target_tag']))
		$submit['var_target_tag'] = "TRUE";
	else
		$submit['var_target_tag'] = "FALSE";

		// 예외 사항 체크 
	$check['submit'] = array("page_name", "var_name", "var_format", 
						  "var_min_length", "var_max_length", );
	castle_check_submit($check['submit']);

		// 변수 트림
	$submit['var_format'] = trim($submit['var_format']);
	$submit['var_min_length'] = trim($submit['var_min_length']);
	$submit['var_max_length'] = trim($submit['var_max_length']);
	$submit['var_method_get'] = trim($submit['var_method_get']);
	$submit['var_method_post'] = trim($submit['var_method_post']);
	$submit['var_target_sql_injection'] = trim($submit['var_target_sql_injection']);
	$submit['var_target_xss'] = trim($submit['var_target_xss']);
	$submit['var_target_word'] = trim($submit['var_target_word']);
	$submit['var_target_tag'] = trim($submit['var_target_tag']);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
	if (get_magic_quotes_gpc()) {
		$submit['var_format'] = stripslashes($submit['var_format']);
		$submit['var_min_length'] = stripslashes($submit['var_min_length']);
		$submit['var_max_length'] = stripslashes($submit['var_max_length']);
		$submit['var_method_get'] = stripslashes($submit['var_method_get']);
		$submit['var_method_post'] = stripslashes($submit['var_method_post']);
		$submit['var_target_sql_injection'] = stripslashes($submit['var_target_sql_injection']);
		$submit['var_target_xss'] = stripslashes($submit['var_target_xss']);
		$submit['var_target_word'] = stripslashes($submit['var_target_word']);
		$submit['var_target_tag'] = stripslashes($submit['var_target_tag']);
	}

	if (!isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]))
		html_msgback($submit['page_name']." 페이지는 없는 페이지입니다.");

	$check['var_name_length'] = strlen($submit['var_name']);
	castle_check_length("변수 이름", $check['var_name_length'], 1, MAX_VAR_LENGTH);

	$policy['page'] =& $_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']];

	if (!isset($policy['page']['LIST']))
		html_msgback($submit['var_name']." 변수는 없는 변수입니다.");

	if (!isset($policy['page']['LIST'][$submit['var_name']]))
		html_msgback($submit['var_name']." 변수는 없는 변수입니다.");

	/* CASTLE 정책 정보 수정: 변수 설정 수정 */

		// 형태 설정
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['FORMAT'] = base64_encode($submit['var_format']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['MIN_LENGTH'] = base64_encode($submit['var_min_length']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['MAX_LENGTH'] = base64_encode($submit['var_max_length']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['METHOD']['GET'] = base64_encode($submit['var_method_get']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['METHOD']['POST'] = base64_encode($submit['var_method_post']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['SQL_INJECTION'] = base64_encode($submit['var_target_sql_injection']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['XSS'] = base64_encode($submit['var_target_xss']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['WORD'] = base64_encode($submit['var_target_word']);
	$_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]['TARGET']['TAG'] = base64_encode($submit['var_target_tag']);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("변수가 수정 되었습니다.", "castle_admin_advance_detail.php?page_name=".$submit['page_name']);

	exit;
}
/* VAR_MODIFY 재설정 끝 */

/* VAR_DELETE 재설정 */
if ($page['mode'] == "VAR_DELETE") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_GET['page_name']))
		$submit['page_name'] = $_GET['page_name'];

	if (isset($_GET['var_name']))
		$submit['var_name'] = $_GET['var_name'];

		// 예외 사항 체크
	$check['submit'] = array("page_name", "var_name", );
	castle_check_submit($check['submit']);

	/* CASTLE 정책 정보 수정: 변수 설정 삭제 */
		// 메모리 해지
	if (isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]))
		unset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]['LIST'][$submit['var_name']]);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("변수가 삭제 되었습니다.", "castle_admin_advance_detail.php?page_name=".$submit['page_name']);

	exit;
}
/* VAR_DELETE 재설정 끝 */

?>
