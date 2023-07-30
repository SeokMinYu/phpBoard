<?php 

//@UTF-8 castle_admin_policy.php
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="StyleSheet" HREF="style.css" type="text/css" title="style">
<?php include_once("castle_admin_title.php"); ?>
  </head>
  <body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" bgcolor="#D0D0D0">
    <table width="100%" height="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
      <tr bgcolor="#FFFFFF"> 
        <td>
          <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
            <tr bgcolor="#606060">
              <td width="100%" height="80" colspan="2">
<?php include_once("castle_admin_top.php"); ?>
               </td>
            </tr>
            <tr>
              <td height="2" bgcolor="#000000" colspan="2"></td>
            </tr>
            <tr>
              <td width="160" bgcolor="#D0D0D0">
<?php include_once("castle_admin_menu.php"); ?>
              </td>
              <td width="100%" bgcolor="#E0E0E0">
                <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
                  <tr valign="top">
                    <td width="100%">
                      <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr valign="top">
                          <td width="100%">
                            <table width="100%" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr height="100%">
                                <td width="9"><img src="img/menu_top_lt.gif"></td>
                                <td width="100%" background="img/menu_top_bg.gif">
                                  <font color="#C0C0C0"><li><b>정책설정</b> - CASTLE 정책을 설정합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>주의: 적용 여부에 주의하시고 설정에 오류가 없도록 주의하십시오.</b><br>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td width="2"></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">정책 설정</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* SQL INJECTION 정책 정보 수정 폼 */

	// SQL INJECTION 정책 정보 설정 변수 설정
	$policy['sql_injection_bool'] =& $_CASTLE_POLICY['POLICY']['SQL_INJECTION']['BOOL'];

	if (isset($_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST']))
		$policy['sql_injection_list'] =& $_CASTLE_POLICY['POLICY']['SQL_INJECTION']['LIST'];
	else
		$policy['sql_injection_list'] = array();

	$policy['sql_injection_bool'] = base64_decode($policy['sql_injection_bool']);

	$print['sql_injection_bool_true_check'] = "";
	$print['sql_injection_bool_false_check'] = "";
	$print['sql_injection_list'] = "";

	if ($policy['sql_injection_bool'] == "TRUE") 
		$print['sql_injection_bool_true_check'] = "checked";
	else
		$print['sql_injection_bool_false_check'] = "checked";

	foreach ($policy['sql_injection_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['sql_injection_list'] .= base64_decode($value);
		$print['sql_injection_list'] .= "\n";
	}

?>
                            <a name="sql_injection"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_policy_submit.php?mode=POLICY_SQL_INJECTION" method="post">
                              <tr>
                                <th width="150" height="30" rowspan="5" bgcolor="#D8D8D8" align="right">SQL Injection</th>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td width="480">
                                  <input type="radio" name="policy_sql_injection" value="true" <?php echo $print['sql_injection_bool_true_check']?>>적용
                                  <input type="radio" name="policy_sql_injection" value="false" <?php echo $print['sql_injection_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용(True) - SQL Injection 취약점 탐지를 실행합니다.
                                        <li>비적용(False) - SQL Injection 취약점 탐지를 실행하지 않습니다.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">목록</th>
                                <td width="410" colspan="2">
                                  <textarea cols="65" rows="10" name="policy_sql_injection_list"><?php echo $print['sql_injection_list']?></textarea>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" height="35" cellspacing="0" cellpadding="0" border="0">
                              <tr valign="top">
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;">
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
/* SQL INJECTION 정책 정보 수정 폼 끝 */
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* XSS 정책 정보 수정 폼 */

	// XSS 정책 정보 설정 변수 설정
	$policy['xss_bool'] =& $_CASTLE_POLICY['POLICY']['XSS']['BOOL'];

	if (isset($_CASTLE_POLICY['POLICY']['XSS']['LIST']))
		$policy['xss_list'] =& $_CASTLE_POLICY['POLICY']['XSS']['LIST'];
	else
		$policy['xss_list'] = array();

	$policy['xss_bool'] = base64_decode($policy['xss_bool']);

	$print['xss_bool_true_check'] = "";
	$print['xss_bool_false_check'] = "";
	$print['xss_list'] = "";

	if ($policy['xss_bool'] == "TRUE") 
		$print['xss_bool_true_check'] = "checked";
	else
		$print['xss_bool_false_check'] = "checked";

	foreach ($policy['xss_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['xss_list'] .= base64_decode($value);
		$print['xss_list'] .= "\n";
	}

?>
                            <a name="xss"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_policy_submit.php?mode=POLICY_XSS" method="post">
                              <tr>
                                <th width="150" height="30" rowspan="5" bgcolor="#D8D8D8" align="right">
                                  XSS<br>(Cross-Site Script)
                                </th>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td width="480">
                                  <input type="radio" name="policy_xss" value="true" <?php echo $print['xss_bool_true_check']?>>적용
                                  <input type="radio" name="policy_xss" value="false" <?php echo $print['xss_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용(True) - XSS 취약점 탐지를 실행합니다.
                                        <li>비적용(False) - XSS 취약점 탐지를 실행하지 않습니다.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">목록</th>
                                <td width="410" colspan="2">
                                  <textarea cols="65" rows="10" name="policy_xss_list"><?php echo $print['xss_list']?></textarea>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" height="35" cellspacing="0" cellpadding="0" border="0">
                              <tr valign="top">
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;">
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
/* XSS 정책 정보 수정 폼 끝 */
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* WORD 정책 정보 수정 폼 */

	// WORD 정책 정보 설정 변수 설정
	$policy['word_bool'] =& $_CASTLE_POLICY['POLICY']['WORD']['BOOL'];

	if (isset($_CASTLE_POLICY['POLICY']['WORD']['LIST']))
		$policy['word_list'] =& $_CASTLE_POLICY['POLICY']['WORD']['LIST'];
	else
		$policy['word_list'] = array();

	$policy['word_bool'] = base64_decode($policy['word_bool']);

	$print['word_bool_true_check'] = "";
	$print['word_bool_false_check'] = "";
	$print['word_list'] = "";

	if ($policy['word_bool'] == "TRUE") 
		$print['word_bool_true_check'] = "checked";
	else
		$print['word_bool_false_check'] = "checked";

	foreach ($policy['word_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['word_list'] .= base64_decode($value);
		$print['word_list'] .= "\n";
	}

?>
                            <a name="word"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_policy_submit.php?mode=POLICY_WORD" method="post">
                              <tr>
                                <th width="150" height="30" rowspan="5" bgcolor="#D8D8D8" align="right">금칙어(WORD)&nbsp;</th>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td width="480">
                                  <input type="radio" name="policy_word" value="true" <?php echo $print['word_bool_true_check']?>>적용
                                  <input type="radio" name="policy_word" value="false" <?php echo $print['word_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용(True) - WORD 취약점 탐지를 실행합니다.
                                        <li>비적용(False) - WORD 취약점 탐지를 실행하지 않습니다.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">목록</th>
                                <td width="410" colspan="2">
                                  <textarea cols="65" rows="10" name="policy_word_list"><?php echo $print['word_list']?></textarea>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" height="35" cellspacing="0" cellpadding="0" border="0">
                              <tr valign="top">
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;">
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
/* WORD 정책 정보 수정 폼 끝 */
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* TAG 정책 정보 수정 폼 */

	// TAG 정책 정보 설정 변수 설정
	$policy['tag_bool'] =& $_CASTLE_POLICY['POLICY']['TAG']['BOOL'];
	
	if (isset($_CASTLE_POLICY['POLICY']['TAG']['LIST']))
		$policy['tag_list'] =& $_CASTLE_POLICY['POLICY']['TAG']['LIST'];
	else
		$policy['tag_list'] = array();

	$policy['tag_bool'] = base64_decode($policy['tag_bool']);

	$print['tag_bool_true_check'] = "";
	$print['tag_bool_false_check'] = "";
	$print['tag_list'] = "";

	if ($policy['tag_bool'] == "TRUE") 
		$print['tag_bool_true_check'] = "checked";
	else
		$print['tag_bool_false_check'] = "checked";

	foreach ($policy['tag_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['tag_list'] .= base64_decode($value);
		$print['tag_list'] .= "\n";
	}

?>
                            <a name="tag"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_policy_submit.php?mode=POLICY_TAG" method="post">
                              <tr>
                                <th width="150" height="30" rowspan="5" bgcolor="#D8D8D8" align="right">태그(TAG)</th>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td width="480">
                                  <input type="radio" name="policy_tag" value="true" <?php echo $print['tag_bool_true_check']?>>적용
                                  <input type="radio" name="policy_tag" value="false" <?php echo $print['tag_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용(True) - TAG 취약점 탐지를 실행합니다.
                                        <li>비적용(False) - TAG 취약점 탐지를 실행하지 않습니다.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">목록</th>
                                <td width="410" colspan="2">
                                  <textarea cols="65" rows="10" name="policy_tag_list"><?php echo $print['tag_list']?></textarea>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" height="35" cellspacing="0" cellpadding="0" border="0">
                              <tr valign="top">
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;">
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
/* TAG 정책 정보 수정 폼 끝 */
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* IP 정책 정보 수정 폼 */

	// IP 정책 정보 설정 변수 설정
	$policy['ip_bool'] =& $_CASTLE_POLICY['POLICY']['IP']['BOOL'];
	$policy['ip_allow'] =& $_CASTLE_POLICY['POLICY']['IP']['ALLOW'];
	$policy['ip_deny'] =& $_CASTLE_POLICY['POLICY']['IP']['DENY'];

	if (isset($_CASTLE_POLICY['POLICY']['IP']['LIST']))
		$policy['ip_list'] =& $_CASTLE_POLICY['POLICY']['IP']['LIST'];
	else
		$policy['ip_list'] = array();

	$policy['ip_bool'] = base64_decode($policy['ip_bool']);
	$policy['ip_allow'] = base64_decode($policy['ip_allow']);
	$policy['ip_deny'] = base64_decode($policy['ip_deny']);

	$print['ip_bool_true_check'] = "";
	$print['ip_bool_false_check'] = "";
	$print['ip_base_allow_check'] = "";
	$print['ip_base_deny_check'] = "";
	$print['ip_list'] = "";

	if ($policy['ip_bool'] == "TRUE") 
		$print['ip_bool_true_check'] = "checked";
	else
		$print['ip_bool_false_check'] = "checked";

	if ($policy['ip_allow'] == "TRUE") 
		$print['ip_base_allow_check'] = "checked";
	else
	if ($policy['ip_deny'] == "TRUE") 
		$print['ip_base_deny_check'] = "checked";

	foreach ($policy['ip_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['ip_list'] .= base64_decode($value);
		$print['ip_list'] .= "\n";
	}

?>
                            <a name="ip"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_policy_submit.php?mode=POLICY_IP" method="post">
                              <tr>
                                <th width="150" height="30" rowspan="7" bgcolor="#D8D8D8" align="right">아이피(IP)</th>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td width="480">
                                  <input type="radio" name="policy_ip" value="true" <?php echo $print['ip_bool_true_check']?>>적용
                                  <input type="radio" name="policy_ip" value="false" <?php echo $print['ip_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용(True) - IP 탐지를 실행합니다.
                                        <li>비적용(False) - IP 탐지를 실행하지 않습니다.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">적용기반</th>
                                <td width="480">
                                  <input type="radio" name="policy_ip_base" value="allow" <?php echo $print['ip_base_allow_check']?>>화이트리스트
                                  <input type="radio" name="policy_ip_base" value="deny" <?php echo $print['ip_base_deny_check']?>>블랙리스트(기본)
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>화이트리스트 - 다음 목록에서의 접근만을 허용합니다.
                                        <li>블랙리스트 - 다음 목록에서의 접근을 차단합니다.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">목록</th>
                                <td width="410" colspan="2">
                                  <textarea cols="65" rows="10" name="policy_ip_list"><?php echo $print['ip_list']?></textarea>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" height="35" cellspacing="0" cellpadding="0" border="0">
                              <tr valign="top">
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;">
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
/* IP 정책 정보 수정 폼 끝 */
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* FILE 정책 정보 수정 폼 */

	// FILENAME 정책 정보 설정 변수 설정
	$policy['filename_bool'] =& $_CASTLE_POLICY['POLICY']['FILENAME']['BOOL'];
	$policy['filename_allow'] =& $_CASTLE_POLICY['POLICY']['FILENAME']['ALLOW'];
	$policy['filename_deny'] =& $_CASTLE_POLICY['POLICY']['FILENAME']['DENY'];

	if (isset($_CASTLE_POLICY['POLICY']['FILENAME']['LIST']))
		$policy['filename_list'] =& $_CASTLE_POLICY['POLICY']['FILENAME']['LIST'];
	else
		$policy['filename_list'] = array();

	$policy['filename_bool'] = base64_decode($policy['filename_bool']);
	$policy['filename_allow'] = base64_decode($policy['filename_allow']);
	$policy['filename_deny'] = base64_decode($policy['filename_deny']);

	$print['filename_bool_true_check'] = "";
	$print['filename_bool_false_check'] = "";
	$print['filename_base_allow_check'] = "";
	$print['filename_base_deny_check'] = "";
	$print['filename_list'] = "";

	if ($policy['filename_bool'] == "TRUE") 
		$print['filename_bool_true_check'] = "checked";
	else
		$print['filename_bool_false_check'] = "checked";

	if ($policy['filename_allow'] == "TRUE") 
		$print['filename_base_allow_check'] = "checked";
	else
	if ($policy['filename_deny'] == "TRUE") 
		$print['filename_base_deny_check'] = "checked";

	foreach ($policy['filename_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['filename_list'] .= base64_decode($value);
		$print['filename_list'] .= "\n";
	}

	// FILETYPE 정책 정보 설정 변수 설정
	$policy['filetype_bool'] =& $_CASTLE_POLICY['POLICY']['FILETYPE']['BOOL'];
	$policy['filetype_allow'] =& $_CASTLE_POLICY['POLICY']['FILETYPE']['ALLOW'];
	$policy['filetype_deny'] =& $_CASTLE_POLICY['POLICY']['FILETYPE']['DENY'];

	if (isset($_CASTLE_POLICY['POLICY']['FILETYPE']['LIST']))
		$policy['filetype_list'] =& $_CASTLE_POLICY['POLICY']['FILETYPE']['LIST'];
	else
		$policy['filetype_list'] = array();

	$policy['filetype_bool'] = base64_decode($policy['filetype_bool']);
	$policy['filetype_allow'] = base64_decode($policy['filetype_allow']);
	$policy['filetype_deny'] = base64_decode($policy['filetype_deny']);

	$print['filetype_bool_true_check'] = "";
	$print['filetype_bool_false_check'] = "";
	$print['filetype_base_allow_check'] = "";
	$print['filetype_base_deny_check'] = "";
	$print['filetype_list'] = "";

	if ($policy['filetype_bool'] == "TRUE") 
		$print['filetype_bool_true_check'] = "checked";
	else
		$print['filetype_bool_false_check'] = "checked";

	if ($policy['filetype_allow'] == "TRUE") 
		$print['filetype_base_allow_check'] = "checked";
	else
	if ($policy['filetype_deny'] == "TRUE") 
		$print['filetype_base_deny_check'] = "checked";

	foreach ($policy['filetype_list'] as $key => $value) 
	{
		$value = trim($value);
			
		$print['filetype_list'] .= base64_decode($value);
		$print['filetype_list'] .= "\n";
	}

	// FILESIZE 정책 정보 설정 변수 설정
	$policy['filesize_bool'] =& $_CASTLE_POLICY['POLICY']['FILESIZE']['BOOL'];
	$policy['filesize_min_size'] =& $_CASTLE_POLICY['POLICY']['FILESIZE']['MIN_SIZE'];
	$policy['filesize_max_size'] =& $_CASTLE_POLICY['POLICY']['FILESIZE']['MAX_SIZE'];

	$policy['filesize_bool'] = base64_decode($policy['filesize_bool']);
	$print['filesize_min_size'] = base64_decode($policy['filesize_min_size']);
	$print['filesize_max_size'] = base64_decode($policy['filesize_max_size']);

	$print['filesize_bool_true_check'] = "";
	$print['filesize_bool_false_check'] = "";

	if ($policy['filesize_bool'] == "TRUE") 
		$print['filesize_bool_true_check'] = "checked";
	else
		$print['filesize_bool_false_check'] = "checked";

?>
                            <a name="file"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_policy_submit.php?mode=POLICY_FILE" method="post">
                              <tr>
                                <th width="150" rowspan="9" bgcolor="#D8D8D8" align="right">파일(File)</th>
                                <th width="100" height="30" rowspan="3" bgcolor="#D8D8D8">파일이름</th>
                                <th width="90" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td>
                                  <input type="radio" name="policy_filename" value="true" <?php echo $print['filename_bool_true_check']?>>적용
                                  <input type="radio" name="policy_filename" value="false" <?php echo $print['filename_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th height="30" bgcolor="#D8D8D8">적용기반</th>
                                <td>
                                  <input type="radio" name="policy_filename_base" value="allow" <?php echo $print['filename_base_allow_check']?>>화이트리스트
                                  <input type="radio" name="policy_filename_base" value="deny" <?php echo $print['filename_base_deny_check']?>>블랙리스트(기본)
                                </td>
                              </tr>
                              <tr>
                                <th height="30" bgcolor="#D8D8D8">목록</th>
                                <td>
                                  <textarea cols="50" rows="5" name="policy_filename_list"><?php echo $print['filename_list']?></textarea>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" rowspan="3" bgcolor="#D8D8D8">파일타입</th>
                                <th width="90" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td>
                                  <input type="radio" name="policy_filetype" value="true" <?php echo $print['filetype_bool_true_check']?>>적용
                                  <input type="radio" name="policy_filetype" value="false" <?php echo $print['filetype_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th height="30" bgcolor="#D8D8D8">적용기반</th>
                                <td>
                                  <input type="radio" name="policy_filetype_base" value="allow" <?php echo $print['filetype_base_allow_check']?>>화이트리스트(기본)
                                  <input type="radio" name="policy_filetype_base" value="deny" <?php echo $print['filetype_base_deny_check']?>>블랙리스트
                                </td>
                              </tr>
                              <tr>
                                <th height="30" bgcolor="#D8D8D8">목록</th>
                                <td>
                                  <textarea cols="50" rows="5" name="policy_filetype_list"><?php echo $print['filetype_list']?></textarea>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" rowspan="3" bgcolor="#D8D8D8">파일크기</th>
                                <th width="90" height="30" bgcolor="#D8D8D8">적용여부</th>
                                <td>
                                  <input type="radio" name="policy_filesize" value="true" <?php echo $print['filesize_bool_true_check']?>>적용
                                  <input type="radio" name="policy_filesize" value="false" <?php echo $print['filesize_bool_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th height="30" bgcolor="#D8D8D8">최소크기</th>
                                <td>
                                  <input type="text" name="policy_filesize_min_size" size="20" value="<?php echo $print['filesize_min_size']?>"> (크기 단위: Byte)
                                </td>
                              </tr>
                              <tr>
                                <th height="30" bgcolor="#D8D8D8">최대크기</th>
                                <td>
                                  <input type="text" name="policy_filesize_max_size" size="20" value="<?php echo $print['filesize_max_size']?>"> (크기 단위: Byte)
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" height="50" cellspacing="0" cellpadding="0" border="0">
                              <tr valign="top">
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;">
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
/* FILE 정책 정보 수정 폼 끝 */
?>
                            </form>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td height="2" bgcolor="#000000" colspan="2"></td>
            </tr>
            <tr bgcolor="#A0A0A0">
              <td width="100%" height="50" colspan="2" align="center">
<?php include_once("castle_admin_bottom.php"); ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
