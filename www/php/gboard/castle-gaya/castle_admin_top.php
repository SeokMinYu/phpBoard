<?php 

//@UTF-8 castle_admin_top.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Sep 18 2008
 *
 */

include_once("castle_version.php");
include_once("castle_admin_lib.php");
castle_check_authorized();

if (isset($_CASTLE_POLICY['CONFIG']['ADMIN']['LASTMODIFIED']))
	$policy['last_modified'] = $_CASTLE_POLICY['CONFIG']['ADMIN']['LASTMODIFIED'];
else
	$policy['last_modified'] = base64_encode("0");

$policy['last_modified'] = base64_decode($policy['last_modified']);

$print['last_modified'] = date("D M j G:i:s T Y", $policy['last_modified']);
$print['last_modified'] = htmlentities($print['last_modified'], ENT_QUOTES, "UTF-8");

?>
                <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr valign="middle">
                    <td width="50%">&nbsp;<a href="castle_admin.php"><img src="img/logo.png" alt="LOGO" border="0"></a></td>
                    <td width="50%" align="right">
                      <table width="100%" height="100%" cellspacing="0" cellpadding="10">
                        <tr align="right">
                          <td>
                            <font color="#FFFFFF"><b>CASTLE / PHP 버전(<?php echo CASTLE_VERSION?>)</b></font><br><br>
                            <b>
                            <a href="castle_admin_logout_submit.php"><font color="#FFFFFF">로그아웃</font></a> |
                            <a href="http://www.krcert.or.kr" target="castle"><font color="#FFFFFF">공식홈페이지</font></a> |
                            <a href="http://www.krcert.or.kr" target="castle"><font color="#FFFFFF">메뉴얼</font></a> | 
                            <a href="http://www.krcert.or.kr"><font color="#FFFFFF">만든이</font></a> 
                            </b><br><br>
                            <font color="#B0B0B0">최근 정책 수정일: <?php echo $print['last_modified']?></font><br>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
