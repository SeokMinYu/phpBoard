<?php 

//@UTF-8 castle_referee_raw.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Jun 22 2009
 *
 * Decription: castle_referee_raw.php는 제로보드 XE 버전에 최적화 됨.
 *             (more info - http://zzigregi.com/zbxe/castle/17312)
 * 
 */
if (!defined("__CASTLE_PHP_VERSION_BASE_DIR__"))
	return;

/* CASTLE 정책 파일 존재 검사 */
if (!file_exists(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_policy.php"))
	return;

/* CASTLE 정책 파일 참조 */
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_policy.php"); 

require_once 'castle_admin_util.php';

/* CASTLE POST 데이터 변수 : XML 등을 이용하는 경우를 대비함 */
$_CASTLE_POST_DATA = "";
$_CASTLE_POST_DATA_KEY = "";
$_CASTLE_POST_DATA_VALUE = "";

/* CASTLE 집행 프레임워크 함수들 */

/* CASTLE 리퍼리 경고 메시지 함수
 *
 * 함수명: castle_referee_alert
 *
 * 정책 설정에 따라 입력된 메시지를 브라우져 화면에 출력하거나
 * 경고창을 통해 출력함. 스텔스 모드인 경우에는 아무것도 출력하지 않음
 *
 * 파라미터: 
 *    $msg - 출력할 메시지
 *
 * 리턴: 없음
 */
function castle_referee_alert($msg)
{
	global $_CASTLE_POLICY, $_SERVER;

	/* 경고 부분 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['CONFIG']['ALERT']))
		return;

	/* 경고 정책 가져오기 */
	$policy['config'] =& $_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME'];
	$policy['config_title'] = base64_decode($policy['config']);

	$policy['alert'] =& $_CASTLE_POLICY['CONFIG']['ALERT'];
	$policy['alert_alert'] = base64_decode($policy['alert']['ALERT']);
	$policy['alert_message'] = base64_decode($policy['alert']['MESSAGE']);
	$policy['alert_stealth'] = base64_decode($policy['alert']['STEALTH']);

		// 스텔스 모드일 경우
	if ($policy['alert_stealth'] == "TRUE") {
		echo "";
		return;
	}

	$page = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];

	/*
	 * 2013/6/6 메시지 모드에서 이미지를 제거 하였기에 real_path 가 필요 없어 삭제 함
	 */
		// 메시지 모드일 경우
	//$real_path = realpath(__CASTLE_PHP_VERSION_BASE_DIR__."/img/sorry.gif");
	
                // Slashes를 Backslashes로 변환
	//if (preg_match("#\\\\#", $real_path))   // Windows 시스템 인경우
	//	$real_path = preg_replace("#\\\\#", "/", $real_path);

	//$error_img = preg_replace($_SERVER['DOCUMENT_ROOT'], "", $real_path);
	
	if ($policy['alert_message'] == "TRUE") {
		/*echo "
			<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
			<html>
			<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
			</head>
			<body bgcolor=\"#FFFFFF\">
				<center><br><br><br><img src=\"".$error_img."\"></center>
			</body>
			</html>";*/
		include "error.html";

		return;
	}

		// 경고 모드일 경우
	if ($policy['alert_alert'] == "TRUE") {
		$alert_msg_utf8  = "\\n※ CASTLE 알림 ※ \\n\\n";
		$alert_msg_utf8 .= "CASTLE에 의해 접근이 차단되었습니다.\\n\\n";
		$alert_msg_utf8 .= "--- 차단 페이지 ---\\n\\n".$page."\\n\\n";
		$alert_msg_utf8 .= "--- 차단 사유 ---\\n\\n".$msg."\\n\\n";
		$alert_msg_utf8 .= "특별한 사유 없이 위의 에러가 반복되면 관리자에게 문의하십시오.\\n";
		$alert_msg_utf8 .= "위의 결과는 모두 별도의 로그에 기록됩니다.\\n\\n";

		$alert_msg_euckr = iconv("UTF-8", "eucKR", $alert_msg_utf8);

		$alert_msg_js  = "<script language=\"javascript\">\n";
		$alert_msg_js .= "	var CastleBrowserDetect = {\n";
		$alert_msg_js .= "		init: function () {\n";
		$alert_msg_js .= "			this.browser = this.searchString(this.dataBrowser) || \"An unknown browser\";\n";
		$alert_msg_js .= "			this.version = this.searchVersion(navigator.userAgent)\n";
		$alert_msg_js .= "				|| this.searchVersion(navigator.appVersion)\n";
		$alert_msg_js .= "				|| \"an unknown version\";\n";
		$alert_msg_js .= "			this.OS = this.searchString(this.dataOS) || \"an unknown OS\";\n";
		$alert_msg_js .= "		},\n";
		$alert_msg_js .= "		searchString: function (data) {\n";
		$alert_msg_js .= "			for (var i=0;i<data.length;i++) {\n";
		$alert_msg_js .= "				var dataString = data[i].string;\n";
		$alert_msg_js .= "				var dataProp = data[i].prop;\n";
		$alert_msg_js .= "				this.versionSearchString = data[i].versionSearch || data[i].identity;\n";
		$alert_msg_js .= "				if (dataString) {\n";
		$alert_msg_js .= "					if (dataString.indexOf(data[i].subString) != -1)\n";
		$alert_msg_js .= "						return data[i].identity;\n";
		$alert_msg_js .= "				}\n";
		$alert_msg_js .= "				else if (dataProp)\n";
		$alert_msg_js .= "					return data[i].identity;\n";
		$alert_msg_js .= "			}\n";
		$alert_msg_js .= "		},\n";
		$alert_msg_js .= "		searchVersion: function (dataString) {\n";
		$alert_msg_js .= "			var index = dataString.indexOf(this.versionSearchString);\n";
		$alert_msg_js .= "			if (index == -1) return;\n";
		$alert_msg_js .= "			return parseFloat(dataString.substring(index+this.versionSearchString.length+1));\n";
		$alert_msg_js .= "		},\n";
		$alert_msg_js .= "		dataBrowser: [\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"Chrome\",\n";
		$alert_msg_js .= "				identity: \"Chrome\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{       string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"OmniWeb\",\n";
		$alert_msg_js .= "				versionSearch: \"OmniWeb/\",\n";
		$alert_msg_js .= "				identity: \"OmniWeb\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.vendor,\n";
		$alert_msg_js .= "				subString: \"Apple\",\n";
		$alert_msg_js .= "				identity: \"Safari\",\n";
		$alert_msg_js .= "				versionSearch: \"Version\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				prop: window.opera,\n";
		$alert_msg_js .= "				identity: \"Opera\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.vendor,\n";
		$alert_msg_js .= "				subString: \"iCab\",\n";
		$alert_msg_js .= "				identity: \"iCab\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.vendor,\n";
		$alert_msg_js .= "				subString: \"KDE\",\n";
		$alert_msg_js .= "				identity: \"Konqueror\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"Firefox\",\n";
		$alert_msg_js .= "				identity: \"Firefox\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.vendor,\n";
		$alert_msg_js .= "				subString: \"Camino\",\n";
		$alert_msg_js .= "				identity: \"Camino\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{	       // for newer Netscapes (6+)\n";
		$alert_msg_js .= "				string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"Netscape\",\n";
		$alert_msg_js .= "				identity: \"Netscape\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"MSIE\",\n";
		$alert_msg_js .= "				identity: \"Explorer\",\n";
		$alert_msg_js .= "				versionSearch: \"MSIE\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"Gecko\",\n";
		$alert_msg_js .= "				identity: \"Mozilla\",\n";
		$alert_msg_js .= "				versionSearch: \"rv\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{	       // for older Netscapes (4-)\n";
		$alert_msg_js .= "				string: navigator.userAgent,\n";
		$alert_msg_js .= "				subString: \"Mozilla\",\n";
		$alert_msg_js .= "				identity: \"Netscape\",\n";
		$alert_msg_js .= "				versionSearch: \"Mozilla\"\n";
		$alert_msg_js .= "			}\n";
		$alert_msg_js .= "		],\n";
		$alert_msg_js .= "		dataOS : [\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.platform,\n";
		$alert_msg_js .= "				subString: \"Win\",\n";
		$alert_msg_js .= "				identity: \"Windows\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.platform,\n";
		$alert_msg_js .= "				subString: \"Mac\",\n";
		$alert_msg_js .= "				identity: \"Mac\"\n";
		$alert_msg_js .= "			},\n";
		$alert_msg_js .= "			{\n";
		$alert_msg_js .= "				string: navigator.platform,\n";
		$alert_msg_js .= "				subString: \"Linux\",\n";
		$alert_msg_js .= "				identity: \"Linux\"\n";
		$alert_msg_js .= "			}\n";
		$alert_msg_js .= "		]\n";
		$alert_msg_js .= "\n";
		$alert_msg_js .= "	};\n";
		$alert_msg_js .= "\n";
		$alert_msg_js .= "	CastleBrowserDetect.init();\n";
		$alert_msg_js .= "\n";
		$alert_msg_js .= "	var CastleBrowser = '';\n";
		$alert_msg_js .= "	if (CastleBrowserDetect.browser == \"Explorer\")\n";
		$alert_msg_js .= "		CastleBrowser = document.charset;\n";
		$alert_msg_js .= "	else\n";
		$alert_msg_js .= "		CastleBrowser = document.characterSet;\n";
		$alert_msg_js .= "\n";
		$alert_msg_js .= "	if (CastleBrowser == \"UTF-8\" || CastleBrowser == \"utf-8\") \n";
		$alert_msg_js .= "		alert('" . $alert_msg_utf8 . "');\n";
		$alert_msg_js .= "	else \n";
		$alert_msg_js .= "		alert('" . $alert_msg_euckr . "');\n";
		$alert_msg_js .= "\n";
		$alert_msg_js .= "	history.back(-1);\n";
		$alert_msg_js .= "\n";
		$alert_msg_js .= "</script>\n";

		print($alert_msg_js);

		return;

	}

}


/* CASTLE 리퍼리 로깅 함수
 *
 * 함수명: castle_referee_logger
 *
 * 정책 설정에 따라 입력된 로그 메시지를 로그 파일에 추가함
 * 무기록 모드인 경우에는 로그를 기록하지 않음
 *
 * 파라미터: 
 *    $msg - 로그 메시지
 *
 * 리턴: 없음
 */
function castle_referee_logger($msg)
{
	global $_CASTLE_POLICY;

	/* 로그 부분 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['CONFIG']['LOG']))
		return;

	/* 로그 정책 가져오기 */
	$policy['log'] =& $_CASTLE_POLICY['CONFIG']['LOG'];
	$policy['log_bool'] = base64_decode($policy['log']['BOOL']);
	$policy['log_filename'] = base64_decode($policy['log']['FILENAME']);

	/* 로그 기록 여부 판단 */
	if ($policy['log_bool'] == "FALSE")
		return;

	/* 로그 메시지에서 '<', '>' 변환 - 추가 Jun 22 2009 */
		// "<" -> "&lt;"
	$msg = castle_util_htmlencode_lt($msg);
		// ">" -> "&gt;"
	$msg = castle_util_htmlencode_gt($msg);

	/* 로그 남김 */
	$log['filename']  = __CASTLE_PHP_VERSION_BASE_DIR__;
	$log['filename'] .= "/log/" . date("Ymd") . "-" . $policy['log_filename'];

	if (!file_exists($log['filename']))
		$log['openmode'] = "w";
	else
		$log['openmode'] = "a+";

	$fd = fopen($log['filename'], $log['openmode']);
	if ($fd) {
		fwrite($fd, $msg, strlen($msg));
		fclose($fd);
	}

	return;
}


/* CASTLE 리퍼리 확장 정규표현식 체크 함수
 *
 * 함수명: castle_referee_eregi
 *
 * 입력된 정규표현식 패턴과 입렶 문자열을 디코딩하고
 * UTF-8, CP949(eucKR)로 변환한 후 각각 테스트를 수행함
 *
 * 파라미터: 
 *    $regexp - 정규표현식 패턴
 *    $str - 입력된 문자열
 *
 * 리턴:
 *    ($utf8_regexp or $euckr_regexp) - 탐지된 정규표현식 패턴
 *    NULL - 탐지되지 않을 경우 널
 */
function castle_referee_eregi($regexp, $str)
{
	global $_CASTLE_POLICY;
	
	$regexp = trim($regexp);
	$str = trim($str);

		// 예외사항
	if (!strlen($regexp) || !strlen($str))
		return NULL;

		// 입력값 디코딩
	$str = castle_referee_urldecode($str);
	$str = castle_referee_unhtmlentities($str);
	$str = castle_referee_htmldecode($str);

		// slashes 덧붙임 제거
	if (get_magic_quotes_gpc())
		$str = stripslashes($str);

		// %00, %0a 문자 제거
	$str = castle_referee_delete_special_characters($str);

	// 정규표현식 체크
	
	$esc_regexp = str_replace("/", "\/", $regexp);
		/* 서버 설정이 eucKR인 경우 */
	if (preg_match("/$esc_regexp/", iconv("eucKR", "UTF-8", $str))) 
		return $regexp;

		/* 서버 설정이 UTF-8인 경우 */
	if (preg_match("/$esc_regexp/", $str)) 
		return $regexp;

	return NULL;
}

function castle_referee_urldecode($str)
{
	/* HTTP URL Decoding */

		// ULl Decoding  ex., '+' -> ' '
	$str = urldecode($str);	

		// RAW ULl Decoding  ex., "%20"' -> ' '
	$str = rawurldecode($str);	

	return $str;
}

// For users prior to PHP 4.3.0 you may do this:
function castle_referee_unhtmlentities($str)
{
	// replace numeric entities
	$str = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $str);
	$str = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $str);

	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);

	return strtr($str, $trans_tbl);
}

function castle_referee_htmldecode($str)
{
	/* ASCII Entities Decoding */

		// Hex encoding '&#x20;' -> ' '
	while (preg_match('~&#x([0-9a-f]+);~ei', $str))
		$str = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $str);

		// Hex encoding without semicolons '&#x20' -> ' '
	while (preg_match('~&#x([0-9a-f]+)~ei', $str))
		$str = preg_replace('~&#x([0-9a-f]+)~ei', 'chr(hexdec("\\1"))', $str);

		// UTF-8 Unicode encoding '&#32;' -> ' '
	while (preg_match('~&#([0-9]+);~e', $str))
		$str = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $str);

		// UTF-8 Unicode encoding without semicolons '&#32' -> ' '
	while (preg_match('~&#([0-9]+)~e', $str))
		$str = preg_replace('~&#([0-9]+)~e', 'chr("\\1")', $str);

	return $str;
}

function castle_referee_delete_special_characters($str)
{
	while (preg_match('/\x00/', $str))
		$str = preg_replace('/\x00/', '', $str);

	while (preg_match('/\x0a/', $str))
		$str = preg_replace('/\x0a/', '', $str);

	return $str;
}

function castle_referee_delete_directory_traverse($path)
{
	/* Delete directory traverse attack.. */

	$path = castle_referee_urldecode($path);
	
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
	while (preg_match("#\\\.\.\\#i", $path)) 
		$path = preg_replace("#\\\.\.\\#i", "\\", $path);

		// ".\" -> ""
	while (preg_match("#\.\\#i", $path))
		$path = preg_replace("#\.\\#i", "", $path);

		// "\\" -> "\"
	while (preg_match("#\\\\#i", $path)) 
		$path = preg_replace("#\\\\#i", "\\", $path);

	return $path;
	*/
}

function castle_referee_error_handler($policy_type, $rule, $method, $key, $value, $msg = "")
{
	global $_CASTLE_POLICY, $_SERVER;

	/* 경고 및 로그 부분 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['CONFIG']['ALERT']))
		return;

	if (!isset($_CASTLE_POLICY['CONFIG']['LOG']))
		return;

	/* 로그 정책 가져오기 */
	$policy['log'] =& $_CASTLE_POLICY['CONFIG']['LOG'];
	$policy['log_bool'] = base64_decode($policy['log']['BOOL']);
	$policy['log_simple'] = base64_decode($policy['log']['SIMPLE']);
	$policy['log_detail'] = base64_decode($policy['log']['DETAIL']);
	
	/* CASTLE 집행 모드 적용 */
	$policy['mode'] =& $_CASTLE_POLICY['CONFIG']['MODE'];
	$policy['mode_enforcing'] = base64_decode($policy['mode']['ENFORCING']);
	$policy['mode_permissive'] = base64_decode($policy['mode']['PERMISSIVE']);

	/* 로그 메시지 작성: REMOTE_ADDR - [DATE] REQUEST_URL : MESSAGE */

	/* LOG 문자셋 설정에 따라 변환 */
	$policy['log_charset']['eucKR'] = "";
	$policy['log_charset']['UTF-8'] = "";
	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['CHARSET'])) {
		$policy['log_charset'] = $_CASTLE_POLICY['CONFIG']['LOG']['CHARSET'];
		$policy['log_charset']['UTF-8'] = base64_decode($policy['log_charset']['UTF-8']);
		$policy['log_charset']['eucKR'] = base64_decode($policy['log_charset']['eucKR']);
	}

		// 개행제거
	$value = castle_util_remove_crlf($value);


		// 간략 메시지 작성
	if ($policy['log_simple'] == "TRUE") {
		$log['simple']  = $_SERVER['REMOTE_ADDR'] . " - [" . date("d/M/Y:H:i:s O") . "] ";

			/* 서버 설정이 eucKR인 경우 */
		if (!iconv("UTF-8", "eucKR", $value)) {
			if ($policy['log_charset']['UTF-8'] == "TRUE") {
				$log['simple'] .= iconv("eucKR", "UTF-8", $_SERVER['PHP_SELF'] . ": ");
				$log['simple'] .= iconv("eucKR", "UTF-8", $key . " = " . substr($value, 0, 64) . ": ");
				$log['simple'] .= $msg . "\n";
			}
			else {
				$log['simple'] .= $_SERVER['PHP_SELF'] . ": ";
				$log['simple'] .= $key . " = " . substr($value, 0, 64) . ": ";
				$log['simple'] .= iconv("UTF-8", "eucKR", $msg . "\n");
			}
		}
		else {
			/* 서버 설정이 UTF-8인 경우 */
			if ($policy['log_charset']['UTF-8'] == "TRUE") {
				$log['simple'] .= $_SERVER['PHP_SELF'] . ": ";
				$log['simple'] .= $key . " = " . substr($value, 0, 64) . ": ";
				$log['simple'] .= $msg . "\n";
			}
			else {
				$log['simple'] .= iconv("UTF-8", "eucKR", $_SERVER['PHP_SELF'] . ": ");
				$log['simple'] .= iconv("UTF-8", "eucKR", $key . " = " . substr($value, 0, 64) . ": ");
				$log['simple'] .= iconv("UTF-8", "eucKR", $msg . "\n");
			}
		}

		castle_referee_logger($log['simple']);
	}
	else
		// 상세 메시지 작성
	if ($policy['log_detail'] == "TRUE") {
		$log['detail']  = $_SERVER['REMOTE_ADDR'] . " - [" . date("d/M/Y:H:i:s O") . "] ";

			/* 서버 설정이 eucKR인 경우 */
		if (!iconv("UTF-8", "eucKR", $value)) {
			if ($policy['log_charset']['UTF-8'] == "TRUE") {
				$log['detail'] .= iconv("eucKR", "UTF-8", $_SERVER['PHP_SELF'] . ": ");
				$log['detail'] .= iconv("eucKR", "UTF-8", $key . " = " . substr($value, 0, 64) . ": ");
				$log['detail'] .= $msg . "\n";
			}
			else {
				$log['detail'] .= $_SERVER['PHP_SELF'] . ": ";
				$log['detail'] .= $key . " = " . substr($value, 0, 64) . ": ";
				$log['detail'] .= iconv("UTF-8", "eucKR", $msg . "\n");
			}
		}
		else {
			/* 서버 설정이 UTF-8인 경우 */
			if ($policy['log_charset']['UTF-8'] == "TRUE") {
				$log['detail'] .= $_SERVER['PHP_SELF'] . ": ";
				$log['detail'] .= $key . " = " . substr($value, 0, 64) . ": ";
				$log['detail'] .= $msg . "\n";
			}
			else {
				$log['detail'] .= iconv("UTF-8", "eucKR", $_SERVER['PHP_SELF'] . ": ");
				$log['detail'] .= iconv("UTF-8", "eucKR", $key . " = " . substr($value, 0, 64) . ": ");
				$log['detail'] .= iconv("UTF-8", "eucKR", $msg . "\n");
			}
		}

		if ($policy['log_charset']['UTF-8'] == "TRUE") {
			$log['detail'] .= " -> [Method: " . $method . "]\n";
			$log['detail'] .= " -> [Policy: " . $policy_type . "]\n";
			$log['detail'] .= " -> [Pattern: " . $rule . "]\n";
		}
		else {
			$log['detail'] .= iconv("UTF-8", "eucKR", " -> [Method: " . $method . "]\n");
			$log['detail'] .= iconv("UTF-8", "eucKR", " -> [Policy: " . $policy_type . "]\n"); 
			$log['detail'] .= iconv("UTF-8", "eucKR", " -> [Pattern: " . $rule . "]\n");
		}

		castle_referee_logger($log['detail']);
	}

		// 집행모드이면 강제 종료
	if ($policy['mode_enforcing'] == "TRUE") {
		castle_referee_alert($msg.": ".$key);
		exit;
	}

		// 감사모드이면 계속 실행
	if ($policy['mode_permissive'] == "TRUE")
		return;

	return;
}

function castle_referee_detect_sql_injection($string)
{
	global $_CASTLE_POLICY;

	/* SQL INJECTION 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['POLICY']['SQL_INJECTION']))
		return;

	$policy['sql_injection'] =& $_CASTLE_POLICY['POLICY']['SQL_INJECTION'];

	$policy['sql_injection_bool'] = $policy['sql_injection']['BOOL'];
	$policy['sql_injection_bool'] = base64_decode($policy['sql_injection_bool']);

		// SQL INJECTION 미적용 모드
	if ($policy['sql_injection_bool'] == "FALSE")
		return;

	$policy['sql_injection_list'] = $policy['sql_injection']['LIST'];

		// 정규표현식으로 탐지
	foreach ($policy['sql_injection_list'] as $regexp)
	{
		$regexp = base64_decode($regexp);
		$regexp = castle_referee_eregi($regexp, $string);
		if ($regexp)
			return $regexp;
	}

	return FALSE;
}

function castle_referee_detect_xss($string)
{
	global $_CASTLE_POLICY;

	/* XSS 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['POLICY']['XSS']))
		return;

	$policy['xss'] =& $_CASTLE_POLICY['POLICY']['XSS'];

	$policy['xss_bool'] = $policy['xss']['BOOL'];
	$policy['xss_bool'] = base64_decode($policy['xss_bool']);

		// XSS 미적용 모드
	if ($policy['xss_bool'] == "FALSE")
		return;

	$policy['xss_list'] = $policy['xss']['LIST'];

		// 정규표현식으로 탐지
	foreach ($policy['xss_list'] as $regexp)
	{
		$regexp = base64_decode($regexp);
		$regexp = castle_referee_eregi($regexp, $string);
		if ($regexp)
			return $regexp;
	}

	return FALSE;
}

function castle_referee_detect_word($string)
{
	global $_CASTLE_POLICY;

	/* WORD 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['POLICY']['WORD']))
		return;

	$policy['word'] =& $_CASTLE_POLICY['POLICY']['WORD'];

	$policy['word_bool'] = $policy['word']['BOOL'];
	$policy['word_bool'] = base64_decode($policy['word_bool']);

		// WORD 미적용 모드
	if ($policy['word_bool'] == "FALSE")
		return;

	$policy['word_list'] = $policy['word']['LIST'];

		// 정규표현식으로 탐지
	foreach ($policy['word_list'] as $regexp)
	{
		$regexp = base64_decode($regexp);
		$regexp = castle_referee_eregi($regexp, $string);
		if ($regexp)
			return $regexp;
	}

	return FALSE;
}

function castle_referee_detect_tag($string)
{
	global $_CASTLE_POLICY;

	/* TAG 정책 존재 여부 */
	if (!isset($_CASTLE_POLICY['POLICY']['TAG']))
		return;

	$policy['tag'] =& $_CASTLE_POLICY['POLICY']['TAG'];

	$policy['tag_bool'] = $policy['tag']['BOOL'];
	$policy['tag_bool'] = base64_decode($policy['tag_bool']);

		// TAG 미적용 모드
	if ($policy['tag_bool'] == "FALSE")
		return;

	$policy['tag_list'] = $policy['tag']['LIST'];

		// 정규표현식으로 탐지
	foreach ($policy['tag_list'] as $regexp)
	{
		$regexp = base64_decode($regexp);
		$regexp = castle_referee_eregi($regexp, $string);
		if ($regexp)
			return $regexp;
	}

	return FALSE;
}

function castle_referee_check_ip_policy()
{
	global $_CASTLE_POLICY;

		// 클라이언트 IP 가져옴
	$check['ip'] = getenv("REMOTE_ADDR");

	if (isset($_CASTLE_POLICY['POLICY']['IP']))
		$policy['ip'] =& $_CASTLE_POLICY['POLICY']['IP'];

	/* IP 정책 존배 판단 : IP 정책이 없으면 기본 허용 */
	if (!isset($policy['ip'])) 
		return;

	$policy['ip_bool'] = base64_decode($policy['ip']['BOOL']);
	$policy['ip_allow'] = base64_decode($policy['ip']['ALLOW']);
	$policy['ip_deny'] = base64_decode($policy['ip']['DENY']);
	
		// IP 검사 사용 안함 
	if ($policy['ip_bool'] == "FALSE") 
		return;

		// 목록 존재 판단
	if (!isset($policy['ip']['LIST']))
		return;

		// 목록 가져오기 
	$policy['ip_list'] = $policy['ip']['LIST'];

		// 목록에서 IP 존재 검사
	$ip_exists = FALSE;
	foreach ($policy['ip_list'] as $ip_regexp) 
	{
		$ip_regexp = base64_decode($ip_regexp);
			// IP의 경우 . -> \.로 변경
		if (castle_referee_eregi($ip_regexp, $check['ip'])) 
			$ip_exists = TRUE;
	}
	
	/* IP 허용기반 정책 적용 */
		// 화이트리스트
	if ($policy['ip_allow'] == "TRUE") {
		if (!$ip_exists)
			castle_referee_error_handler("IP정책", "check remote_ip", 
						   "IP", stripslashes($check['ip']), "",
						   "허용되지 않은 IP 대역에서 접근 시도");
	}
	else 
		// 블랙리스트
	if ($policy['ip_deny'] == "TRUE") {
		if ($ip_exists)
			castle_referee_error_handler("IP정책", "check remote_ip", 
						   "IP", stripslashes($check['ip']), "",
						   "차단된 IP 대역에서 접근 시도");
	}

	return;
}

function castle_referee_check_file_policy()
{
	global $_CASTLE_POLICY, $_FILE;

	if (!isset($_CASTLE_POLICY['CONFIG']['TARGET'])) 
		return;

	if (!isset($_CASTLE_POLICY['CONFIG']['TARGET']['FILE'])) 
		return;

	$policy['file_bool'] = base64_decode($_CASTLE_POLICY['CONFIG']['TARGET']['FILE']);

	if ($policy['file_bool'] == "FALSE") 
		return;

		// 파일 변수가 없음 
	if (!isset($_FILES))
		return;

		// 파일 관련 정책 가져옴
	$policy['filename'] = "";
	if (isset($_CASTLE_POLICY['POLICY']['FILENAME'])) 
		$policy['filename'] =& $_CASTLE_POLICY['POLICY']['FILENAME'];

	$policy['filetype'] = "";
	if (isset($_CASTLE_POLICY['POLICY']['FILETYPE'])) 
		$policy['filetype'] =& $_CASTLE_POLICY['POLICY']['FILETYPE'];

	$policy['filesize'] = "";
	if (isset($_CASTLE_POLICY['POLICY']['FILESIZE'])) 
		$policy['filesize'] =& $_CASTLE_POLICY['POLICY']['FILESIZE'];

	/* 파일 이름 정책 적용 */
	$policy['filename_bool'] = base64_decode($policy['filename']['BOOL']);
	$policy['filename_allow'] = base64_decode($policy['filename']['ALLOW']);
	$policy['filename_deny'] = base64_decode($policy['filename']['DENY']);

	/* 파일 타입 정책 적용 */
	$policy['filetype_bool'] = base64_decode($policy['filetype']['BOOL']);
	$policy['filetype_allow'] = base64_decode($policy['filetype']['ALLOW']);
	$policy['filetype_deny'] = base64_decode($policy['filetype']['DENY']);

	/* 파일 크기 정책 적용 */
	$policy['filesize_bool'] = base64_decode($policy['filesize']['BOOL']);
	$policy['filesize_min_size'] = base64_decode($policy['filesize']['MIN_SIZE']);
	$policy['filesize_max_size'] = base64_decode($policy['filesize']['MAX_SIZE']);

	foreach ($_FILES as $var => $fileinfo) 
	{
		$check['filename'] = $fileinfo['name'];
		$check['filevar'] = $var;
		$check['filetype'] = array_pop(explode('.', $fileinfo['name']));
		$check['filesize'] = $fileinfo['size'];

			// 파일 업로드 체크
		if ($fileinfo['error'])
			return;

			// 파일 이름 정책 적용 모드
		if ($policy['filename_bool'] == "TRUE")  {

			if (isset($policy['filename']['LIST'])) {
				$policy['filename_list'] = $policy['filename']['LIST'];

				$filename_exists = FALSE;
				foreach ($policy['filename_list'] as $filename) 
				{
					$filename_regexp = base64_decode($filename);
					if (castle_referee_eregi($filename_regexp, $check['filename']))
						$filename_exists = TRUE;
				}
			}

			/* 파일이름 허용기반 정책 적용 */
				// 화이트리스트
			if ($policy['filename_allow'] == "TRUE") {
				if (!$filename_exists)
					castle_referee_error_handler("파일정책", "check filename", 
								   "FILE", $check['filevar'], $check['filename'],
								   "허용되지 않은 이름 파일 업로드");
			}
			else 
				// 블랙리스트
			if ($policy['filename_deny'] == "TRUE") {
				if ($filename_exists)
					castle_referee_error_handler("파일정책", "check filename", 
								   "FILE", $check['filevar'], $check['filename'],
								   "차단된 이름 파일 업로드");
			}

		}

			// 파일 확장자 정책 적용 모드
		if ($policy['filetype_bool'] == "TRUE")  {

			if (isset($policy['filetype']['LIST'])) {
				$policy['filetype_list'] = $policy['filetype']['LIST'];

				$filetype_exists = FALSE;
				foreach ($policy['filetype_list'] as $filetype) 
				{
					$filetype_regexp = base64_decode($filetype);
					if (castle_referee_eregi($filetype_regexp, $check['filetype']))
						$filetype_exists = TRUE;
				}
			}

			/* 파일 확장자 허용기반 정책 적용 */
				// 화이트리스트
			if ($policy['filetype_allow'] == "TRUE") {
				if (!$filetype_exists)
					castle_referee_error_handler("파일정책", "check filetype", 
								   "FILE", $check['filevar'], $check['filename'],
								   "허용되지 않은 확장자 파일 업로드");
			}
			else 
				// 블랙리스트
			if ($policy['filetype_deny'] == "TRUE") {
				if ($filetype_exists)
					castle_referee_error_handler("파일정책", "check filetype", 
								   "FILE", $check['filevar'], $check['filename'],
								   "차단된 확장자 파일 업로드");
			}

		}

			// 파일 크기 정책 적용 모드
		if ($policy['filesize_bool'] == "TRUE")  {

				// 최소 크기 검사
			if ($check['filesize'] < $policy['filesize_min_size'])
				castle_referee_error_handler("파일정책", ">=".$policy['filesize_min_size'], 
							   "FILE", $check['filevar'], $check['filesize'], 
							   "최소 크기보다 작은 파일 업로드");

				// 최대 크기 검사
			if ($check['filesize'] > $policy['filesize_max_size'])
				castle_referee_error_handler("파일정책", "<=".$policy['filesize_max_size'],
							   "FILE", $check['filevar'], $check['filesize'], 
							   "최대 크기보다 큰 파일 업로드");

		}

	}

	return;
}

function castle_referee_check_site_policy()
{
	global $_CASTLE_POLICY, $_SERVER;

	/* 사이트 정책 적용 */
	if (!isset($_CASTLE_POLICY['CONFIG']['SITE']['BOOL'])) 
		return;

		// 사이트 정책 가져오기
	$policy['site'] =& $_CASTLE_POLICY['CONFIG']['SITE']['BOOL'];
	$policy['site'] = base64_decode($policy['site']);

		// 사이트 정책 적용
	if ($policy['site'] == "FALSE")
		castle_referee_error_handler("사이트정책", "check site-policy", "SITE", "차단됨", "", "사이트 잠금");

	return;
}

function castle_referee_check_basic_policy()
{
	global $_CASTLE_POLICY, $_CASTLE_POST_DATA, $_SERVER, $GLOBALS;

		// 페이지 이름을 가져옴
	$check['page_name'] = getenv("SCRIPT_NAME");
	$check['page_name'] = castle_referee_delete_directory_traverse($check['page_name']);

	if (isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$check['page_name']])) 
		$policy['page'] =& $_CASTLE_POLICY['ADVANCE']['PAGE'][$check['page_name']];

	/* 등록된 페이지 정책 적용 */
	if (isset($policy['page'])) 
		return;

	/* GET 메소드 처리 */
	if (isset($_CASTLE_POLICY['CONFIG']['TARGET'])) {

		if (isset($_CASTLE_POLICY['CONFIG']['TARGET']['GET'])) {

			$policy['get_bool'] = base64_decode($_CASTLE_POLICY['CONFIG']['TARGET']['GET']);

			if ($policy['get_bool'] == "TRUE") {

				foreach ($_GET as $key => $value) 
				{
					/* 탐지 대상별 탐지 */

						// SQL_INJECTION 탐지
					$pattern = castle_referee_detect_sql_injection($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "GET", $key, $value, 
									   "SQL_Injection 공격 패턴 탐지");

						// XSS 탐지
					$pattern = castle_referee_detect_xss($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "GET", $key, $value, 
									   "XSS 공격 패턴 탐지");
					
						// WORD 탐지

					$pattern = castle_referee_detect_word($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "GET", $key, $value, 
									   "금칙어 탐지");
					
						// TAG 탐지
					$pattern = castle_referee_detect_tag($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "GET", $key, $value, 
									   "TAG 공격 패턴 탐지");
				}

			}

		}

	}

	/* POST 메소드 처리 */
	if (isset($_CASTLE_POLICY['CONFIG']['TARGET'])) {

		if (isset($_CASTLE_POLICY['CONFIG']['TARGET']['POST'])) {

			$policy['post_bool'] = base64_decode($_CASTLE_POLICY['CONFIG']['TARGET']['POST']);

			if ($policy['post_bool'] == "TRUE") {

					// Raw POST Data 인 경우
				if ($GLOBALS['HTTP_RAW_POST_DATA']) {

					$xml_data = $GLOBALS['HTTP_RAW_POST_DATA'];

					$xml_parser = xml_parser_create();

					xml_set_element_handler($xml_parser, "castle_referee_xml_start_element_handler", "castle_referee_xml_end_element_handler");
					xml_set_character_data_handler($xml_parser, "castle_referee_xml_character_data_handler");
					
    					xml_parse($xml_parser, $xml_data);

				}
				else {
					// Typical POST인 경우
					$_CASTLE_POST_DATA = $_POST;
				}

				foreach ($_CASTLE_POST_DATA as $key => $value) 
				{
					/* 탐지 대상별 탐지 */
						// SQL_INJECTION 탐지
					$pattern = castle_referee_detect_sql_injection($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "POST", $key, $value, 
									     "SQL_Injection 공격 패턴 탐지");

						// XSS 탐지
					$pattern = castle_referee_detect_xss($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "POST", $key, $value, 
									     "XSS 공격 패턴 탐지");

						// WORD 탐지
					$pattern = castle_referee_detect_word($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "POST", $key, $value, 
									     "금칙어 탐지");

						// TAG 탐지
					$pattern = castle_referee_detect_tag($value);
					if ($pattern)
						castle_referee_error_handler("기본정책", $pattern, "POST", $key, $value, 
									     "TAG 공격 패턴 탐지");
				
				}

			}

		}

	}

	return;
}

function castle_referee_xml_start_element_handler($parser, $name, $attrs) 
{
	global $_CASTLE_POST_KEY;

	$name = trim($name);
	if (strlen($name))
		$_CASTLE_POST_KEY = $name;
}

function castle_referee_xml_end_element_handler($parser, $name) 
{
	global $_CASTLE_POST_DATA, $_CASTLE_POST_KEY, $_CASTLE_POST_VALUE;
	
	if ($_CASTLE_POST_VALUE)
		$_CASTLE_POST_DATA[$_CASTLE_POST_KEY] = $_CASTLE_POST_VALUE;
}

function castle_referee_xml_character_data_handler($parser, $data)
{
	global $_CASTLE_POST_VALUE;

	$data = trim($data);
	if (strlen($data))
		$_CASTLE_POST_VALUE = $data;
}

function castle_referee_check_cookie_policy()
{
	global $_CASTLE_POLICY, $_COOKIE;

	/* COOKIE 전역 변수 처리 */
	if (!isset($_CASTLE_POLICY['CONFIG']['TARGET']))
		return;

	if (!isset($_CASTLE_POLICY['CONFIG']['TARGET']['COOKIE'])) 
		return;

	$policy['cookie_bool'] = base64_decode($_CASTLE_POLICY['CONFIG']['TARGET']['COOKIE']);

	if ($policy['cookie_bool'] == "FALSE") 
		return;

	foreach ($_COOKIE as $key => $value) 
	{
		/* 탐지 대상별 탐지 */

			// SQL_INJECTION 탐지
		$pattern = castle_referee_detect_sql_injection($value);
		if ($pattern)
			castle_referee_error_handler("기본정책", $pattern, "COOKIE", $key, $value, 
						   "SQL_Injection 공격 패턴 탐지");

			// XSS 탐지
		$pattern = castle_referee_detect_xss($value);
		if ($pattern)
			castle_referee_error_handler("기본정책", $pattern, "COOKIE", $key, $value, "XSS 공격 패턴 탐지");
		
			// WORD 탐지
		$pattern = castle_referee_detect_word($value);
		if ($pattern)
			castle_referee_error_handler("기본정책", $pattern, "COOKIE", $key, $value, "금칙어 탐지");
		
			// TAG 탐지
		$pattern = castle_referee_detect_tag($value);
		if ($pattern)
			castle_referee_error_handler("기본정책", $pattern, "COOKIE", $key, $value, "TAG 공격 패턴 탐지");
	}

	return;
}

function castle_referee_check_advance_policy()
{
	global $_CASTLE_POLICY;

		// 페이지 이름을 가져옴
	$check['page_name'] = getenv("SCRIPT_NAME");
	$check['page_name'] = castle_referee_delete_directory_traverse($check['page_name']);

	if (isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$check['page_name']])) 
		$policy['page'] =& $_CASTLE_POLICY['ADVANCE']['PAGE'][$check['page_name']];

	/* 등록된 페이지 정책 적용 */
	if (!isset($policy['page'])) 
		return;

	/* 페이지 허용/차단 정책 적용 */
	$policy['page_bool'] = base64_decode($policy['page']['BOOL']);
	$policy['page_allow'] = base64_decode($policy['page']['ALLOW']);
	$policy['page_deny'] = base64_decode($policy['page']['DENY']);

		// FALSE인 경우 차단
	if ($policy['page_bool'] == "FALSE")
		castle_referee_error_handler("고급정책", "check page", "PAGE", $check['page_name'], "", "차단된 페이지 접근 시도");

	if (!isset($_CASTLE_POLICY['CONFIG']['TARGET'])) 
		return;

	/* GET 메소드 처리 */
	if (isset($_CASTLE_POLICY['CONFIG']['TARGET']['GET'])) {

		$policy['get_bool'] = base64_decode($_CASTLE_POLICY['CONFIG']['TARGET']['GET']);

		if ($policy['get_bool'] == "TRUE") {

			foreach ($_GET as $key => $value) 
			{
				/* 페이지 허용기반 정책 적용 */

					// 화이트리스트
				if ($policy['page_allow'] == "TRUE") {
					if (!isset($policy['page']['LIST'][$key])) 
						castle_referee_error_handler("고급정책", "check white-list", "GET", $key, "", "허용되지 않은 변수 접근 시도");
				}
				else 
					// 블랙리스트
				if ($policy['page_deny'] == "TRUE") {
					if (isset($policy['page']['LIST'][$key])) 
						castle_referee_error_handler("고급정책", "check black-list", "GET", $key, "", "차단된 변수 접근 시도");
				}

					// 변수 가져오기
				if (!isset($policy['page']['LIST'][$key])) 
					continue;

				$policy['var'] = $policy['page']['LIST'][$key];

					// GET 메소드 사용 유무 탐지
				$policy['get_bool'] = base64_decode($policy['var']['METHOD']['GET']);
				if ($policy['get_bool'] == "FALSE")
					castle_referee_error_handler("고급정책", "check method", "GET", $key, "", "차단된 메소드 접근 시도");

				/* 변수 데이터 형태별 정책 적용 */
				$policy['format'] = "";
				if (isset($policy['var']['FORMAT']))
					$policy['format'] = $policy['var']['FORMAT'];

				$policy['format'] = trim($policy['format']);
		 
				if (strlen($policy['format']) > 0) {
					$policy['format'] = base64_decode($policy['format']);	

					if (!castle_referee_eregi($policy['format'], $value))
						castle_referee_error_handler("고급정책", $policy['format'], "GET", $key, $value, "지정되지 않은 형태의 변수값 입력");
				}

				/* 변수 데이터 길이 정책 적용 */
				$submit[$key]['length'] = strlen($value);

					// 최소길이 검사
				if (isset($policy['var']['MIN_LENGTH'])) {
					$policy['min_length'] = base64_decode($policy['var']['MIN_LENGTH']);

					if ($submit[$key]['length'] < $policy['min_length'])
						castle_referee_error_handler("고급정책", ">=".$policy['min_length'], "GET", $key, $value, "최소 길이보다 작은 변수값 입력");
				}
					// 최대길이 검사
				if (isset($policy['var']['MAX_LENGTH'])) {
					$policy['max_length'] = base64_decode($policy['var']['MAX_LENGTH']);

					if ($submit[$key]['length'] > $policy['max_length'])
						castle_referee_error_handler("고급정책", "<=".$policy['max_length'], "GET", $key, $value, "최대 길이보다 큰 변수값 입력");
				}

				/* 탐지 대상별 탐지 */

					// SQL_INJECTION 탐지
				$policy['sql_injection_bool'] = "FALSE";
				if (isset($policy['var']['TARGET']['SQL_INJECTION']))
					$policy['sql_injection_bool'] = base64_decode($policy['var']['TARGET']['SQL_INJECTION']);

				if ($policy['sql_injection_bool'] == "TRUE") {
					$pattern = castle_referee_detect_sql_injection($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "GET", $key, $value, "SQL_Injection 공격 패턴 탐지");
				}

					// XSS 탐지
				$policy['xss'] = "FALSE";
				if (isset($policy['var']['TARGET']['XSS']))
					$policy['xss'] = base64_decode($policy['var']['TARGET']['XSS']);

				if ($policy['xss'] == "TRUE") {
					castle_referee_detect_xss($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "GET", $key, $value, "XSS 공격 패턴 탐지");
				}

					// WORD 탐지
				$policy['word'] = "FALSE";
				if (isset($policy['var']['TARGET']['WORD']))
					$policy['word'] = base64_decode($policy['var']['TARGET']['WORD']);

				if ($policy['word'] == "TRUE") {
					$pattern = castle_referee_detect_word($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "GET", $key, $value, "금칙어 탐지");
				}

					// TAG 탐지
				$policy['tag'] = "FALSE";
				if (isset($policy['var']['TARGET']['TAG']))
					$policy['tag'] = base64_decode($policy['var']['TARGET']['TAG']);

				if ($policy['tag'] == "TRUE") {
					$pattern = castle_referee_detect_tag($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "GET", $key, $value, "TAG 공격 패턴 탐지");
				}

			}

		}

	}

	/* POST 메소드 처리 */
	if (isset($_CASTLE_POLICY['CONFIG']['TARGET']['POST'])) {

		$policy['post_bool'] = base64_decode($_CASTLE_POLICY['CONFIG']['TARGET']['POST']);

		if ($policy['post_bool'] == "TRUE") {

			foreach ($_POST as $key => $value) 
			{

				/* 페이지 허용기반 정책 적용 */
					// 화이트리스트
				if ($policy['page_allow'] == "TRUE") {
					if (!isset($policy['page']['LIST'][$key])) 
						castle_referee_error_handler("고급정책", "check white-list", "POST", $key, "", "허용되지 않은 변수 접근 시도");
				}
				else 
					// 블랙리스트
				if ($policy['page_deny'] == "TRUE") {
					if (isset($policy['page']['LIST'][$key])) 
						castle_referee_error_handler("고급정책", "check black-list", "POST", $key, "", "차단된 변수 접근 시도");
				}

					// 변수 가져오기
				if (!isset($policy['page']['LIST'][$key]))
					continue;

				$policy['var'] = $policy['page']['LIST'][$key];

				/* 변수 데이터 길이 정책 적용 */

					// POST 메소드 사용 유무 탐지
				$policy['post_bool'] = base64_decode($policy['var']['METHOD']['POST']);
				if ($policy['post_bool'] == "FALSE")
					castle_referee_error_handler("고급정책", "check method", "POST", $key, "", "차단된 메소드 접근 시도");

				/* 변수 데이터 형태별 정책 적용 */
				$policy['format'] = "";
				if ($policy['var']['FORMAT'])
					$policy['format'] = $policy['var']['FORMAT'];

				$policy['format'] = trim($policy['format']);
		 
				if (strlen($policy['format']) > 0) {
					$policy['format'] = base64_decode($policy['format']);

					if (!castle_referee_eregi($policy['format'], $value))
						castle_referee_error_handler("고급정책", $policy['format'], "POST", $key, $value, "지정되지 않은 형태의 변수값 입력");
				}

				/* 변수 데이터 길이 정책 적용 */
				$submit[$key]['length'] = strlen($value);

					// 최소길이 검사
				if (isset($policy['var']['MIN_LENGTH'])) {
					$policy['min_length'] = base64_decode($policy['var']['MIN_LENGTH']);

					if ($submit[$key]['length'] < $policy['min_length'])
						castle_referee_error_handler("고급정책", ">=".$policy['min_length'], "POST", $key, $value, "최소 길이보다 작은 변수값 입력");
				}
					// 최대길이 검사
				if (isset($policy['var']['MAX_LENGTH'])) {
					$policy['max_length'] = base64_decode($policy['var']['MAX_LENGTH']);

					if ($submit[$key]['length'] > $policy['max_length'])
						castle_referee_error_handler("고급정책", "<=".$policy['max_length'], "POST", $key, $value, "최대 길이보다 큰 변수값 입력");
				}

				/* 탐지 대상별 탐지 */

					// SQL_INJECTION 탐지
				$policy['sql_injection_bool'] = "FALSE";
				if (isset($policy['var']['TARGET']['SQL_INJECTION']))
					$policy['sql_injection_bool'] = base64_decode($policy['var']['TARGET']['SQL_INJECTION']);

				if ($policy['sql_injection_bool'] == "TRUE") {
					$pattern = castle_referee_detect_sql_injection($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "POST", $key, $value, "SQL_Injection 공격 패턴 탐지");
				}

					// XSS 탐지
				$policy['xss'] = "FALSE";
				if (isset($policy['var']['TARGET']['XSS']))
					$policy['xss'] = base64_decode($policy['var']['TARGET']['XSS']);

				if ($policy['xss'] == "TRUE") {
					castle_referee_detect_xss($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "POST", $key, $value, "XSS 공격 패턴 탐지");
				}

					// WORD 탐지
				$policy['word'] = "FALSE";
				if (isset($policy['var']['TARGET']['WORD']))
					$policy['word'] = base64_decode($policy['var']['TARGET']['WORD']);

				if ($policy['word'] == "TRUE") {
					$pattern = castle_referee_detect_word($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "POST", $key, $value, "금칙어 탐지");
				}

					// TAG 탐지
				$policy['tag'] = "FALSE";
				if (isset($policy['var']['TARGET']['TAG']))
					$policy['tag'] = base64_decode($policy['var']['TARGET']['TAG']);

				if ($policy['tag'] == "TRUE") {
					$pattern = castle_referee_detect_tag($value);
					if ($pattern)
						castle_referee_error_handler("고급정책", $pattern, "POST", $key, $value, "TAG 공격 패턴 탐지");
				}

			}

		}
	
	}

	return;
}

/* CASTLE Referee 메인 함수 */
function castle_referee_main()
{
		// 사이트 접근 시도 중재
	castle_referee_check_site_policy();

		// IP 접근 시도 중재
	castle_referee_check_ip_policy();

		// 고급 정책 적용(페이지별 정책)
	castle_referee_check_advance_policy();

		// 기본 정책 적용
	castle_referee_check_basic_policy();

		// COOKIE 중재
	castle_referee_check_cookie_policy();

		// FILE 변수 중재
	castle_referee_check_file_policy();

	return;
}
/* CASTLE 집행 프레임워크 함수들 끝 */


if (!isset($_CASTLE_POLICY['CONFIG']['MODE']['ENFORCING']))
	return;

/* CASTLE DISABLED 상태이면 바로 종료 */
$policy['disabled'] =& $_CASTLE_POLICY['CONFIG']['MODE']['DISABLED'];
$policy['disabled'] = base64_decode($policy['disabled']);

if ($policy['disabled'] == "TRUE")
	return;

/* CASTLE Referee 메인 함수 호출 */
castle_referee_main();

/* End of castle_referee.php */
?>
