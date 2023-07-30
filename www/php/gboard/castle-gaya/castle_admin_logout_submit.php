<?php 

//@UTF-8 castle_admin_logout_submit.php
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

	// 인증 세션 해지
session_unset();

html_msgmove("관리자 로그아웃 되었습니다.", "castle_admin_login.php");

exit;
/* 관리자 로그아웃 끝 */

?>
