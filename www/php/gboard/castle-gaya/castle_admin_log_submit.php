<?php 

//@UTF-8 castle_admin_log_submit.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Jun 22 2009
 *
 * History:
 *     Jun 22 2009 - 로그 파일이름 검사 기능 추가
 *                   초기 로그파일 이름 사용 불능화
 */

include_once("castle_admin_lib.php");
castle_check_authorized();

/* 내부 변수 초기화 */
castle_init_page();

//$page['mode'] : 모드 변수
if (isset($_GET) && isset($_GET['mode']))
	$page['mode'] = $_GET['mode'];

/* 내부 변수 초기화 끝 */

/* 로그 재설정 */
if ($page['mode'] == "LOG_MODIFY") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();
	/*
	if (isset($_POST['log_filename']))
		$submit['log_filename'] = $_POST['log_filename'];
	*/
	if (isset($_POST['log_bool']))
		$submit['log_bool'] = $_POST['log_bool'];

	if (isset($_POST['log_mode']))
		$submit['log_mode'] = $_POST['log_mode'];

	if (isset($_POST['log_list_count']))
		$submit['log_list_count'] = $_POST['log_list_count'];

	if (isset($_POST['log_charset']))
		$submit['log_charset'] = $_POST['log_charset'];

		// 예외 사항 체크
	$check['submit'] = array("log_bool", "log_mode", "log_list_count", "log_charset", );
	castle_check_submit($check['submit']);
	/*
	$check['submit'] = array("log_filename", "log_bool", "log_mode", "log_list_count", "log_charset", );
	castle_check_submit($check['submit']);

	$check['log_filename_length'] = strlen($submit['log_filename']);
	castle_check_length("로그 파일이름", $check['log_filename_length'], 4, 48);

	if ($submit['log_filename'] == "castle_log.txt") 
		html_msgback("castle_log.txt는 로그 파일이름으로 사용할 수 없습니다.");
	*/
	$check['log_list_count_length'] = strlen($submit['log_list_count']);
	castle_check_length("로그 목록개수", $check['log_list_count_length'], 1, 3);

	/* CASTLE 정책 정보 수정: 로그 정보 설정 */

		// 변수 트림
	/*
	$submit['log_filename'] = trim($submit['log_filename']);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
	if (get_magic_quotes_gpc()) 
		$submit['log_filename'] = stripslashes($submit['log_filename']);

		// 로그 파일이름 설정
	$_CASTLE_POLICY['CONFIG']['LOG']['FILENAME'] = base64_encode($submit['log_filename']);
	*/
		// 로그 기록 여부 설정
	if ($submit['log_bool'] == "true")
		$_CASTLE_POLICY['CONFIG']['LOG']['BOOL'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['CONFIG']['LOG']['BOOL'] = base64_encode("FALSE");

		// 로그 모드 설정
	if ($submit['log_mode'] == "simple") {
		$_CASTLE_POLICY['CONFIG']['LOG']['SIMPLE'] = base64_encode("TRUE");
		$_CASTLE_POLICY['CONFIG']['LOG']['DETAIL'] = base64_encode("FALSE");
	}
	else
	if ($submit['log_mode'] == "detail") {
		$_CASTLE_POLICY['CONFIG']['LOG']['SIMPLE'] = base64_encode("FALSE");
		$_CASTLE_POLICY['CONFIG']['LOG']['DETAIL'] = base64_encode("TRUE");
	}

		// 로그 문자셋 설정
	if ($submit['log_charset'] == "UTF-8")
		$_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['UTF-8'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['UTF-8'] = base64_encode("FALSE");

	if ($submit['log_charset'] == "eucKR")
		$_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['eucKR'] = base64_encode("TRUE");
	else
		$_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['eucKR'] = base64_encode("FALSE");

		// 로그 기록 개수
			// 변수 트림
	$submit['log_list_count'] = trim($submit['log_list_count']);

		// magic_quotes_gpc() 상태에 따른 stripslashes 실행
	/*
	if (get_magic_quotes_gpc()) 
		$submit['log_list_count'] = stripslashes($submit['log_list_count']);
	*/
	$_CASTLE_POLICY['CONFIG']['LOG']['LIST_COUNT'] = base64_encode($submit['log_list_count']);

		// CASTLE 정책 쓰기
	castle_write_policy();

	html_msgmove("로그 설정 정보가 수정되었습니다.", "castle_admin_log.php");

	exit;
}
/* 로그 재설정 끝 */

/* 로그 삭제 */
if ($page['mode'] == "LOG_DELETE") 
{
	/* 요청 변수 처리 */
	castle_clear_submit();

	if (isset($_GET['log_filename']))
		$submit['log_filename'] = $_GET['log_filename'];

		// 예외 사항 체크
	$check['submit'] = array("log_filename", );
	castle_check_submit($check['submit']);

		// 파일 삭제
	$log['filename'] = "./log/" . $submit['log_filename'];

	if (file_exists($log['filename']))
		unlink($log['filename']);

	html_msgmove("로그 파일이 삭제되었습니다.", "castle_admin_log.php");

	exit;

}
/* 로그 삭제 끝 */

?>
