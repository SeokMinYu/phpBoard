<?php 

//@UTF-8 castle_admin_download.php
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

/* 내부 변수 초기화 끝 */

/* 요청 변수 처리 */
castle_clear_submit();

if (isset($_GET['filename']))
	$submit['filename'] = $_GET['filename'];

if (isset($_GET['filepath']))
	$submit['filepath'] = $_GET['filepath'];

	// 예외 사항 체크
$check['submit'] = array("filename", "filepath", );
castle_check_submit($check['submit']);

$submit['filename'] = trim($submit['filename']);
$submit['filepath'] = trim($submit['filepath']);

	// 다운로드 경로 생성
$check['script_filename'] = getenv("SCRIPT_FILENAME");
$check['script_filename'] = castle_delete_directory_traverse($check['script_filename']);

$piecies = explode("/", $check['script_filename']);

$check['filepath'] = "";
$count = count($piecies) - 1;

for ($i = 0; $i < $count; $i++) 
	$check['filepath'] .= $piecies[$i] . "/";

	// 다온로드 경로 생성 끝 

	// 변수 트림
$submit['filepath'] = trim($submit['filepath']);

$check['filepath_length'] = strlen($submit['filepath']);
castle_check_length("파일경로", $check['filepath_length'], 1, 256);

	// 다운로드 전체 경로 작성
$submit['filepath'] = $check['filepath'] . $submit['filepath'];

	// 디렉터리 트레버스 제거
$submit['filepath'] = castle_delete_directory_traverse($submit['filepath']);

	// 파일 정보 가져옴 
if (!file_exists($submit['filepath']))
	exit;

$fileinfo = stat($submit['filepath']);
$filename = basename($submit['filepath']);

	// 파일 다운로드
castle_file_download($submit['filename'], $submit['filepath'], $fileinfo['size']);

exit;
/* 파일 다운로드 끝 */

?>
