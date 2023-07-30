<?php 

//@UTF-8 castle_admin_lib.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Sep 18 2008
 *
 */

require_once 'castle_admin_util.php';

if (defined("_CASTLE_ADMIN_LIB_PHP_")) 
	return;

else 
{

ini_set("display_errors", "0");

/* _CASTLE_ADMIN_LIB_PHP_ routine */

/* 초기값 선언 */
define("CASTLE_BASE_MODULE_NAME", "CASTLE - PHP 버전");
define("MAX_VAR_LENGTH", 65535);
define("MAX_FILESIZE", 1073741824); // 1GB

/* 전역변수 호환성 설정 */
	// $HTTP_POST_VARS : 4.1.0 이전 버전 
	// $_POST : 4.1.0 이후 버전
$php_ver = explode(".", phpversion()); 
if ($php_ver[0]<4 || $php_ver[0]==4 && $php_ver[1]<1) { 
	$_GET =& $HTTP_GET_VARS; 
	$_POST =& $HTTP_POST_VARS; 
	$_COOKIE =& $HTTP_COOKIE_VARS; 
	$_SESSION =& $HTTP_SESSION_VARS; 
	$_FILES =& $HTTP_POST_FILES; 
	$_ENV =& $HTTP_ENV_VARS; 
	$_SERVER =& $HTTP_SERVER_VARS; 
} 

/* 세션 시작 */
session_start();

/* CASTLE 정책 파일 가져옴 */
if (file_exists("castle_policy.php"))
	include_once("castle_policy.php");

function castle_write_policy($array = NULL, $vpath = "\$_CASTLE_POLICY", $step = 0, $fd = NULL)
{
	global $_CASTLE_POLICY;

		/* 정책 파일 최종 수정 날짜 변경 */
	$_CASTLE_POLICY['CONFIG']['ADMIN']['LASTMODIFIED'] = base64_encode(time());

		/* 윈도우인 경우 권한 체크 생략 */
	$check['os'] = getenv("OS");
	if (!strstr($check['os'], "Windows")) {
				
			// 설치 디렉터리 상태 가져오기
		$fstat['castle_dir'] = stat("./");
	
			// 10진수를 8진수로 수정
		$fstat['castle_dir'] = decoct($fstat['castle_dir']['mode'] & 0000777);

		if ($fstat['castle_dir'] != "707")
			html_msgback("CASTLE 관리자 페이지 디렉터리에 쓰기 권한이 없습니다.");
	}

	if (!$array && !$step)
		$array =& $_CASTLE_POLICY;

	if (!is_array($array))
		return;

	$count = count($array);
	$original = count($array);
	
	if ($step == 0) {
		$fd = fopen("castle_policy.tmp.php", "w");
		if (!$fd) 
			html_msgback("castle_policy.tmp.php: 임시 정책 파일을 만들수 없습니다.");

		fwrite($fd, "<?php\n\n");
		fwrite($fd, "//@UTF-8 castle_policy.php\n\n");
		fwrite($fd, "if (defined(\"_CASTLE_POLICY_PHP_\"))\n");
		fwrite($fd, "\treturn;\n\n");
		fwrite($fd, "else \n");
		fwrite($fd, "{\n");
		fwrite($fd, "/* _CASTLE_POLICY_PHP_ routine */\n");
	}

	foreach ($array as $key => $value)
	{
		if ($count == $original)
			$step ++;

		if (!is_array($array[$key])) 
			fwrite($fd, $vpath."']['".$key."'] = \"".$value."\";\n");

		if (is_array($array[$key])) {

			unset($imsi);
			if ($step == 1)
				$imsi = $vpath."['".$key;
			else
				$imsi = $vpath."']['".$key;

			castle_write_policy($array[$key], $imsi, $step, $fd);
		}

		$count --;
	}

	if ($step == 1) {
		fwrite($fd, "/* End of _CASTLE_POLICY_PHP */\n");
		fwrite($fd, "}\n\n");
		fwrite($fd, "/* End of castle_policy.php */\n");
		fwrite($fd, "?>");

		fclose($fd);

		if (file_exists("castle_policy.php")) 
			copy("castle_policy.php", "castle_policy.bak.php");

		copy("castle_policy.tmp.php", "castle_policy.php");
		unlink("castle_policy.tmp.php");

		if (file_exists("castle_policy.php")) 
			chmod("castle_policy.php", 0604);

		if (file_exists("castle_policy.bak.php")) 
			chmod("castle_policy.bak.php", 0604);

	}

	return;
}

function castle_print_policy($array = NULL, $vpath = "\$_CASTLE_POLICY", $step = 0)
{
	global $_CASTLE_POLICY;

	if (!$array && !$step)
		$array =& $_CASTLE_POLICY;

	if (!is_array($array))
		return;

	$count = count($array);
	$original = count($array);
	
	if (!$step)
		print("<b>CASTLE Policy Source View<br></b>\n");

	foreach ($array as $key => $value)
	{
		if ($count == $original)
			$step ++;

		if (!is_array($array[$key])) 
			print($vpath."']['".$key."'] = \"".htmlentities(base64_decode($value), ENT_QUOTES, "UTF-8")."\"<br>\n");

		if (is_array($array[$key])) {

			unset($imsi);
			if ($step == 1)
				$imsi = $vpath."['".$key;
			else
				$imsi = $vpath."']['".$key;

			castle_print_policy($array[$key], $imsi, $step);
		}

		$count --;
	}

	return;
}

function castle_print_policy_tree($array = NULL, $step = 0)
{
	global $_CASTLE_POLICY;

	if (!$array && !$step)
		$array =& $_CASTLE_POLICY;

	if (!is_array($array))
		return;

	$count = count($array);
	$original = count($array);

	if (!$step)
		print("<b>CASTLE Policy Tree View<br></b>\n");

	foreach ($array as $key => $value)
	{
		if ($count == $original)
			$step ++;

		if ($key != "PASSWORD") {
			for ($i = 0; $i < $step; $i++) 
				print("&nbsp; &nbsp; &nbsp; &nbsp; ");

			if (!is_array($array[$key])) 
				print("$key = ".htmlentities(base64_decode($value), ENT_QUOTES, "UTF-8")."<br>\n");
		}

		if (is_array($array[$key])) {
			print("<b>- $key</b><br>\n");
			castle_print_policy_tree($array[$key], $step);
		}

		$count --;
	}

	return;
}

function castle_check_length($check_name, $length, $min, $max)
{
	if ($length < $min)
		html_msgback($check_name.": 최소 ".$min."자 이상으로 설정하십시오.");

	if ($length > $max)
		html_msgback($check_name.": 최대 ".$min."자 이하로 설정하십시오.");

	return;
}

function castle_print_error($error)
{
	print($error);
}

function castle_init_page()
{
	if (isset($GLOBALS['page']))
		unset($page);

	if (isset($GLOBALS['print']))
		unset($print);

	if (isset($GLOBALS['submit']))
		unset($submit);

	if (isset($GLOBALS['policy']))
		unset($policy);

	return;
}

function castle_check_installed()
{
	if (!file_exists("castle_policy.php")) 
		html_msgmove("Castle이 설치되어 있지 않습니다.", "install.php");

	return;
}

function castle_check_authorized()
{
	global $_SESSION;

	$auth['remote_addr'] = getenv("REMOTE_ADDR"); 
	$auth['user_agent'] = getenv("HTTP_USER_AGENT"); 
	
	$auth['key'] = "castle_auth_token_".$auth['remote_addr'];
	$auth['value'] = md5($auth['user_agent']);

	if (!isset($_SESSION)) 
		html_msgmove("관리자 페이지 접근이 인증 되지 않습니다.", "castle_admin_login.php");

	if (!isset($_SESSION[$auth['key']])) 
		html_msgmove("관리자 페이지 접근이 인증 되지 않습니다..", "castle_admin_login.php");

	if ($_SESSION[$auth['key']] != $auth['value']) 
		html_msgmove("관리자 페이지 접근이 인증 되지 않습니다...", "castle_admin_login.php");

	return;
}

function castle_check_submit($key_array)
{
	if (!isset($GLOBALS['submit'])) {
		html_msgback("변수 입력에 문제가 발생하였습니다.");
		return;
	}

	if (!is_array($GLOBALS['submit']))
		return;

	if (!is_array($key_array)) {
		if (!isset($GLOBALS['submit'][$key_array]))
			html_msgback($key_array." 변수가 선언되지 않았습니다.");
		return;
	}

	for ($i = 0; isset($key_array[$i]); $i++) {
		$key = $key_array[$i];
		if (!isset($GLOBALS['submit'][$key]))
			html_msgback($key." 변수가 선언되지 않았습니다.");
	}

	return;
}

function castle_clear_submit()
{
	if (!isset($GLOBALS['submit']))
		return;	

	if (!is_array($GLOBALS['submit']))
		unset($GLOBALS['submit']);

	foreach ($GLOBALS['submit'] as $key => $value)
		unset($GLOBALS['submit'][$key]);	

	return;
}

function castle_ie50_51_60_check($user_agent)
{
	if (preg_match("(MSIE 5.0|MSIE 5.1|MSIE 6.0)i", $user_agent)) {
		return TRUE;
	}
	return FALSE;
}

function castle_ie55_check($user_agent)
{
	if (preg_match("/MSIE 5.5/i", $user_agent)) {
		return TRUE;
	}
	return FALSE;
}

function castle_file_download($filename, $filepath, $filesize)
{
	$user_agent = getenv("HTTP_USER_AGENT"); 

	if (!file_exists($filepath)) 
		html_msgback("파일이 존재하지 않습니다.");

	if ( castle_ie50_51_60_check($user_agent)) {
		header("Cache-Control: public");
		Header("Content-Disposition: attachment; filename=" . $filename . ";");
		Header("Content-type: application/x-force-download");
	}
	else
	if (castle_ie55_check($user_agent)) {
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		Header("Content-Disposition: inline; filename=" . $filename . ";");
	}
	else {
		header("Cache-Control: public");
		Header("Content-Disposition: attachment; filename=" . $filename . ";");
		Header("Content-type: application/octet-stream");
	}

	header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Content-Length: " . (string)$filesize);
	Header("Content-Transfer-Encoding: binary");

	readfile($filepath);

	return;
}

function castle_delete_directory_traverse($path)
{
	return castle_util_delete_directory_traverse($path);
	/*
		// "/../" -> "/"
	while (preg_match("#/\.\./#i", $path)) 
		$path = preg_replace("#/\.\./#i", "/", $path);

		// "./" -> ""
	while (preg_match("#\./#i", $path))
		$path = preg_replace("#\./#i", "", $path);

		// "//" -> "/"
	while (preg_match("#//#i", $path)) 
		$path = preg_replace("#//#i", "/", $path);

		// "\..\" -> "\"
	while (preg_match("/\\\\\.\.\\\\/i", $path)) 
		$path = preg_replace("/\\\\\.\.\\\\/i", "\\", $path);

		// ".\" -> ""
	while (preg_match("/\.\\\\/i", $path))
		$path = preg_replace("/\.\\\\/i", "", $path);

		// "\\" -> "\"
	while (preg_match("/\\\\\\\\/", $path)) 
		$path = preg_replace("/\\\\\\\\/i", "\\", $path);

	return $path;
	*/
}

/* CASTLE 관련 함수들 끝 */

/* HTML 관련 함수들 */
function html_msg($msg)
{
	global $_CASTLE_POLICY;

	print("<html>\n");
	print(" <head>");
	print("   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">");
	print(" </head>");
	print(" <script> alert(\"$msg\"); </script>");
	print("</html>\n");
}

function html_move($url, $target = "")
{
	if ($target)
		$target .= ".";

	print("<html>\n");
	print(" <head>");
	print("   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">");
	print(" </head>");
	print(" <script> " . $target . "location.href='$url'; </script>");
	print("</html>\n");

	exit;
}

function html_close()
{
	print("<html>\n");
	print(" <head>");
	print("   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">");
	print(" </head>");
	print("<script> window.close(); </script>");
	print("</html>\n");

	exit;
}

function html_back()
{
	print("<html>\n");
	print(" <head>");
	print("   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">");
	print(" </head>");
	print(" <script> history.back(-1); </script>");
	print("</html>\n");

	exit;
}

function html_msgmove($msg, $url, $target = "") 
{
	html_msg($msg);
	html_move($url, $target);
}

function html_msgback($msg) 
{
	html_msg($msg);
	html_back();
}

function html_msgclose($msg)
{
	html_msg($msg);
	html_close();
}
/* HTML 관련 함수들 끝 */

/* CASTLE 설치 여부 확인 */
if (!isset($exception['check_installed']))
	castle_check_installed();
else
if (!$exception['check_installed'])
	castle_check_installed();

/* CASTLE 인증 여부 확인 */
// 2013/6/6 인증을 각 페이지에서 직접 하도록 수정
/*
if (!isset($exception['check_authorized']))
{
	castle_check_authorized(1);
}
else
if (!$exception['check_authorized'])
{
	castle_check_authorized(2);
}
*/

/* End of _CASTLE_ADMIN_LIB_PHP_ */
}

/* End of castle_admin_lib.php */
?>
