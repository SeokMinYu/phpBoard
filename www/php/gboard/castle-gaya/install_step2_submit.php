<?php 

//@UTF-8 install_step2_submit.php
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
 *                   src[[:space:]]*= 정책 제거
 */

/* 인증 및 설치 검사 예외 페이지 */
$exception['check_installed'] = TRUE;
$exception['check_authorized'] = TRUE;

include_once("castle_admin_lib.php");

/* 설치 여부 판단 - 설치된 경우 종료 : 추가 Jun 22 2009 */
if (file_exists("castle_policy.php")) 
	html_msgmove("CASTLE - PHP 버전이 이미 설치되어 있습니다.", "castle_admin.php");

	// 정책 리스트 목록
$LIST['sql_injection'] = array(
				"delete[[:space:]]+from", 
				"drop[[:space:]]+database", 
				"drop[[:space:]]+table", 
				"drop[[:space:]]+column", 
				"drop[[:space:]]+procedure", 
				"create[[:space:]]+table", 
				"update[[:space:]]+.*set",
				"insert[[:space:]]+into.*values",
				"select[[:space:]]+.*from",
				"bulk[[:space:]]+insert", 
				"union[[:space:]]+select", 
				"and[[:space:]]+['\"[[:space:]]]*[[:alnum:]]+['\"[[:space:]]]*[[:space:]]*=[[:space:]]*['\"[[:space:]]]*[[:alnum:]]+",
				"and[[:space:]]+[[:alnum:]]+[[:space:]]*=[[:space:]]*[[:alnum:]]+",
				"or[[:space:]]+['\"[[:space:]]]*[[:alnum:]]+['\"[[:space:]]]*[[:space:]]*=[[:space:]]*['\"[[:space:]]]*[[:alnum:]]+",
				"or[[:space:]]+[[:alnum:]]+[[:space:]]*=[[:space:]]*[[:alnum:]]+",
				"not[[:space:]]+['\"[[:space:]]]*[[:alnum:]]+['\"[[:space:]]]*[[:space:]]*=[[:space:]]*['\"[[:space:]]]*[[:alnum:]]+",
				"not[[:space:]]+[[:alnum:]]+[[:space:]]*=[[:space:]]*[[:alnum:]]+",
				"alter[[:space:]]+table", 
				"into[[:space:]]+outfile", 
				"load[[:space:]]+data",
				"declare.+varchar.+set"
			);

$LIST['xss'] = array(
				"<script", 
				"script[[:space:]]+.?src[[:space:]]*=", 
				"%3cscript", 
				"&#x3c;script", 
				"javascript:", 
				"expression[[:space:]]*\(", 
				"xss:[[:space:]].*\(", 
				"document\.cookie", 
				"document\.location", 
				"document\.write", 
				"onAbort[[:space:]]*=", 
				"onBlur[[:space:]]*=", 
				"onChange[[:space:]]*=", 
				"onClick[[:space:]]*=", 
				"onDblClick[[:space:]]*=", 
				"onDragDrop[[:space:]]*=", 
				"onError[[:space:]]*=", 
				"onFocus[[:space:]]*=", 
				"onKeyDown[[:space:]]*=", 
				"onKeyPress[[:space:]]*=", 
				"onKeyUp[[:space:]]*=", 
				"onLoad[[:space:]]*=", 
				"onMouseDown[[:space:]]*=", 
				"onMouseMove[[:space:]]*=", 
				"onMouseOut[[:space:]]*=", 
				"onMouseOver[[:space:]]*=", 
				"onMouseUp[[:space:]]*=", 
				"onMove[[:space:]]*=", 
				"onReset[[:space:]]*=", 
				"onResize[[:space:]]*=", 
				"onSelect[[:space:]]*=", 
				"onSubmit[[:space:]]*=", 
				"onUnload[[:space:]]*=", 
				"location.href[[:space:]]*="
			);

$LIST['word'] = array(
				"새끼",
				"개새끼",
				"소새끼",
				"병신",
				"지랄",
				"씨팔",
				"십팔",
				"니기미",
				"찌랄",
				"쌍년",
				"쌍놈",
				"빙신",
				"좆까",
				"니기미",
				"좆같은게",
				"잡놈",
				"벼엉신",
				"바보새끼",
				"씹새끼",
				"씨발",
				"씨팔",
				"시벌",
				"씨벌",
				"떠그랄",
				"좆밥",
				"추천인",
				"추천id",
				"추천아이디",
				"추/천/인",
				"쉐이",
				"등신",
				"싸가지",
				"미친놈",
				"미친넘",
				"찌랄",
				"죽습니다",
				"아님들아",
				"씨밸넘",
				"sex",
				"섹스",
				"바카라"
			);

$LIST['tag'] = array(
				"<iframe[[:space:]]*",
				"data[[:space:]]*:*.+base64",
				"\.\./",
				"\.\.\\\\"
			);

$LIST['filename'] = array("php", "html", "phtml", "inc", "jsp", "asp", "do", "htm", "exe", "sh", "bat");

$LIST['filetype'] = array("txt", "jpg", "gif", "mp3", "hwp", "doc", "docx", "pdf", "zip", "pptx", "ppt", "xls", "xlsx", "bmp", "png", "hwps", "jpeg", "eml");

/* 내부 변수 초기화 */
castle_init_page();

/* 내부 변수 초기화 끝 */

/* 요청 변수 처리 */
castle_clear_submit();

if (isset($_POST['admin_id']))
	$submit['admin_id'] = $_POST['admin_id'];

if (isset($_POST['admin_password']))
	$submit['admin_password'] = $_POST['admin_password'];

if (isset($_POST['admin_repassword']))
	$submit['admin_repassword'] = $_POST['admin_repassword'];

if (isset($_POST['log_filename']))
	$submit['log_filename'] = $_POST['log_filename'];

	// 예외 사항 체크
$check['submit'] = array("admin_id", "admin_password", "admin_repassword", "log_filename", );
castle_check_submit($check['submit']);

if ($submit['admin_password'] != $submit['admin_repassword']) 
	html_msgback("암호와 확인 암호가 같지 않습니다.");

$check['admin_id_length'] = strlen($submit['admin_id']);
castle_check_length("관리자 아이디", $check['admin_id_length'], 4, 16);

$check['admin_password_length'] = strlen($submit['admin_password']);
castle_check_length("관리자 암호", $check['admin_password_length'], 8, 32);

$check['log_filename_length'] = strlen($submit['log_filename']);
castle_check_length("로그 파일이름", $check['log_filename_length'], 4, 48);

if ($submit['log_filename'] == "castle_log.txt") 
	html_msgback("castle_log.txt는 로그 파일이름으로 사용할 수 없습니다.");

/* CASTLE 기본 정책 생성 */
$_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME'] = base64_encode(CASTLE_BASE_MODULE_NAME);
$_CASTLE_POLICY['CONFIG']['ADMIN']['ID'] = base64_encode($submit['admin_id']);
$_CASTLE_POLICY['CONFIG']['ADMIN']['PASSWORD'] = base64_encode(md5(md5($submit['admin_password'])));
$_CASTLE_POLICY['CONFIG']['ADMIN']['LASTMODIFIED'] = base64_encode(time());
$_CASTLE_POLICY['CONFIG']['SITE']['BOOL'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['CONFIG']['MODE']['ENFORCING'] = base64_encode("FALSE");
$_CASTLE_POLICY['CONFIG']['MODE']['PERMISSIVE'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['CONFIG']['MODE']['DISABLED'] = base64_encode("FALSE"); 
$_CASTLE_POLICY['CONFIG']['ALERT']['ALERT'] = base64_encode("FALSE"); 
$_CASTLE_POLICY['CONFIG']['ALERT']['MESSAGE'] = base64_encode("FALSE"); 
$_CASTLE_POLICY['CONFIG']['ALERT']['STEALTH'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['CONFIG']['LOG']['BOOL'] = base64_encode("TRUE");
$_CASTLE_POLICY['CONFIG']['LOG']['FILENAME'] = base64_encode($submit['log_filename']);
$_CASTLE_POLICY['CONFIG']['LOG']['DETAIL'] = base64_encode("FALSE");
$_CASTLE_POLICY['CONFIG']['LOG']['SIMPLE'] = base64_encode("TRUE");
$_CASTLE_POLICY['CONFIG']['LOG']['LIST_COUNT'] = base64_encode("10");
$_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['UTF-8'] = base64_encode("TRUE");
$_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['eucKR'] = base64_encode("FALSE");
$_CASTLE_POLICY['CONFIG']['TARGET']['GET'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['CONFIG']['TARGET']['POST'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['CONFIG']['TARGET']['FILE'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['CONFIG']['TARGET']['COOKIE'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['BOOL'] = base64_encode("TRUE"); 
$_CASTLE_POLICY['POLICY']['XSS']['BOOL'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['WORD']['BOOL'] = base64_encode("FALSE");
$_CASTLE_POLICY['POLICY']['TAG']['BOOL'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['IP']['BOOL'] = base64_encode("FALSE");
$_CASTLE_POLICY['POLICY']['IP']['ALLOW'] = base64_encode("FALSE");
$_CASTLE_POLICY['POLICY']['IP']['DENY'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['FILENAME']['BOOL'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['FILENAME']['ALLOW'] = base64_encode("FALSE");
$_CASTLE_POLICY['POLICY']['FILENAME']['DENY'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['FILETYPE']['BOOL'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['FILETYPE']['ALLOW'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['FILETYPE']['DENY'] = base64_encode("FALSE");
$_CASTLE_POLICY['POLICY']['FILESIZE']['BOOL'] = base64_encode("TRUE");
$_CASTLE_POLICY['POLICY']['FILESIZE']['MIN_SIZE'] = base64_encode("0");
$_CASTLE_POLICY['POLICY']['FILESIZE']['MAX_SIZE'] = base64_encode(MAX_FILESIZE);

foreach ($LIST['sql_injection'] as $key => $value)
{
	$value = base64_encode($value);
	$_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST'][$key] = $value;
}

foreach ($LIST['word'] as $key => $value)
{
	$value = base64_encode($value);
	$_CASTLE_POLICY['POLICY']['WORD']['LIST'][$key] = $value;
}

foreach ($LIST['xss'] as $key => $value)
{
	$value = base64_encode($value);
	$_CASTLE_POLICY['POLICY']['XSS']['LIST'][$key] = $value;
}

foreach ($LIST['tag'] as $key => $value)
{
	$value = base64_encode($value);
	$_CASTLE_POLICY['POLICY']['TAG']['LIST'][$key] = $value;
}

foreach ($LIST['filename'] as $key => $value)
{
	$value = base64_encode($value);
	$_CASTLE_POLICY['POLICY']['FILENAME']['LIST'][$key] = $value;
}

foreach ($LIST['filetype'] as $key => $value)
{
	$value = base64_encode($value);
	$_CASTLE_POLICY['POLICY']['FILETYPE']['LIST'][$key] = $value;
}

	// CASTLE 정책 쓰기
castle_write_policy();

html_msgmove("설치가 끝났습니다.", "castle_admin.php");

exit;

/* 설치 끝 */

?>
