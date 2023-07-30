<?php 

//@UTF-8 castle_admin_config.php
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
                          <td width="100%" style="line-height:120%" nowrap>
                            <table width="100%" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr height="100%">
                                <td width="9"><img src="img/menu_top_lt.gif"></td>
                                <td width="100%" background="img/menu_top_bg.gif">
                                  <font color="#C0C0C0"><li><b>기본설정</b> - CASTLE 기본 정책을 설정합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>알림: CASTLE 이름을 따로 설정하시길 바랍니다.</b><br>
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
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">CASTLE 기본설정</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* 정책 기본 설정 수정 폼 시작 */

	// 관리자 계정 정보 설정 변수 설정
	if (isset($_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME']))
		$policy['admin_module_name'] =& $_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME'];
	else
		$policy['admin_module_name'] = base64_encode(CASTLE_BASE_MODULE_NAME);

	$print['admin_module_name'] = base64_decode($policy['admin_module_name']);

	// CASTLE 적용 모드 설정 변수 설정
	$policy['mode_enforcing'] =& $_CASTLE_POLICY['CONFIG']['MODE']['ENFORCING'];
	$policy['mode_permissive'] =& $_CASTLE_POLICY['CONFIG']['MODE']['PERMISSIVE'];
	$policy['mode_disabled'] =& $_CASTLE_POLICY['CONFIG']['MODE']['DISABLED'];

	$policy['mode_enforcing'] = base64_decode($policy['mode_enforcing']);
	$policy['mode_permissive'] = base64_decode($policy['mode_permissive']);
	$policy['mode_disabled'] = base64_decode($policy['mode_disabled']);

	$print['mode_enforcing_select'] = "";
	$print['mode_permissive_select'] = "";
	$print['mode_disabled_select'] = "";

	if ($policy['mode_enforcing'] == "TRUE")
		$print['mode_enforcing_select'] = "selected";

	if ($policy['mode_permissive'] == "TRUE")
		$print['mode_permissive_select'] = "selected";

	if ($policy['mode_disabled'] == "TRUE")
		$print['mode_disabled_select'] = "selected";

	// CASTLE 알림 모드 설정 변수 설정
	$policy['alert_alert'] =& $_CASTLE_POLICY['CONFIG']['ALERT']['ALERT'];
	$policy['alert_message'] =& $_CASTLE_POLICY['CONFIG']['ALERT']['MESSAGE'];
	$policy['alert_stealth'] =& $_CASTLE_POLICY['CONFIG']['ALERT']['STEALTH'];

	$policy['alert_alert'] = base64_decode($policy['alert_alert']);
	$policy['alert_message'] = base64_decode($policy['alert_message']);
	$policy['alert_stealth'] = base64_decode($policy['alert_stealth']);

	$print['alert_alert_select'] = "";
	$print['alert_message_select'] = "";
	$print['alert_stealth_select'] = "";

	if ($policy['alert_alert'] == "TRUE")
		$print['alert_alert_select'] = "selected";

	if ($policy['alert_message'] == "TRUE")
		$print['alert_message_select'] = "selected";

	if ($policy['alert_stealth'] == "TRUE")
		$print['alert_stealth_select'] = "selected";

?>
                            <a name="basic"></a>
                            <table cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_config_submit.php?mode=CONFIG_BASIC" method="post">
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">CASTLE 이름</th>
                                <td><input type="text" name="admin_module_name" size="48" maxlength="64" value="<?php echo $print['admin_module_name']?>"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">집행모드</th>
                                <td>
                                  <select type="radio" name="config_mode">
                                    <option value="enforcing" <?php echo $print['mode_enforcing_select']?>>적용모드
                                    <option value="permissive" <?php echo $print['mode_permissive_select']?>>감사모드
                                    <option value="disabled" <?php echo $print['mode_disabled_select']?>>비적용모드
                                  </select>
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용모드(enforcing) - 실제로 CASTLE을 적용함.
                                        <li>감사모드(permissive) - CASTLE을 적용하지만 집행하지는 않음(기본).
                                        <li>비적용모드(disabled) - CASTLE을 적용하지 않음.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">알림방식</th>
                                <td>
                                  <select type="radio" name="config_alert">
                                    <option value="alert" <?php echo $print['alert_alert_select']?>>경고모드
                                    <option value="message"  <?php echo $print['alert_message_select']?>>메세지모드
                                    <option value="stealth" <?php echo $print['alert_stealth_select']?>>스텔스모드
                                  </select>
                                </td>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>경고모드(Alert) - 집행결과를 경고창으로 알림.
                                        <li>메세지모드(Message) - 집행결과를 메시지로 알림.
                                        <li>스텔스모드(Stealth) - 아무런 결과도 알리지 않음(기본).
                                      </td>
                                    </tr>
                                  </table>
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
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td width="2"></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">사이트 설정</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* SITE 잠금 정보 수정 폼 */

	// SITE 정책 정보 설정 변수 설정
	if (isset($_CASTLE_POLICY['CONFIG']['SITE']['BOOL']))
		$policy['site_bool'] =& $_CASTLE_POLICY['CONFIG']['SITE']['BOOL'];

	if (isset($policy['site_bool']))
		$policy['site_bool'] = base64_decode($policy['site_bool']);
	else
		$policy['site_bool'] = "TRUE";

	$print['site_bool_true_check'] = "";
	$print['site_bool_false_check'] = "";

	if ($policy['site_bool'] == "TRUE") 
		$print['site_bool_true_check'] = "checked";
	else
		$print['site_bool_false_check'] = "checked";
?>
                            <a name="site"></a>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_config_submit.php?mode=CONFIG_SITE" method="post">
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">사이트 잠금여부</th>
                                <td>
                                  <input type="radio" name="config_site_bool" value="true" <?php echo $print['site_bool_true_check']?>>열림(기본)
                                  <input type="radio" name="config_site_bool" value="false" <?php echo $print['site_bool_false_check']?>>잠금
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>열림(Open) - 사이트를 정상적으로 운영함.
                                        <li>잠금(Closed) - 사이트를 잠금하여 운영하지 않음.
                                      </td>
                                    </tr>
                                  </table>
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
/* 정책 기본 설정 수정 폼 끝 */
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td width="2"></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">CASTLE 적용대상</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* CASTLE 적용대상 수정 폼 시작 */

	// CASTLE 적용 대상 설정 변수 설정
	$policy['target_get'] =& $_CASTLE_POLICY['CONFIG']['TARGET']['GET'];
	$policy['target_post'] =& $_CASTLE_POLICY['CONFIG']['TARGET']['POST'];
	$policy['target_cookie'] =& $_CASTLE_POLICY['CONFIG']['TARGET']['COOKIE'];
	$policy['target_file'] =& $_CASTLE_POLICY['CONFIG']['TARGET']['FILE'];

	$policy['target_get'] = base64_decode($policy['target_get']);
	$policy['target_post'] = base64_decode($policy['target_post']);
	$policy['target_cookie'] = base64_decode($policy['target_cookie']);
	$policy['target_file'] = base64_decode($policy['target_file']);

	$print['target_get_true_check'] = "";
	$print['target_get_false_check'] = "";
	$print['target_post_true_check'] = "";
	$print['target_post_false_check'] = "";
	$print['target_cookie_true_check'] = "";
	$print['target_cookie_false_check'] = "";
	$print['target_file_true_check'] = "";
	$print['target_file_false_check'] = "";

	if ($policy['target_get'] == "TRUE")
		$print['target_get_true_check'] = "checked";
	else
		$print['target_get_false_check'] = "checked";

	if ($policy['target_post'] == "TRUE")
		$print['target_post_true_check'] = "checked";
	else
		$print['target_post_false_check'] = "checked";

	if ($policy['target_cookie'] == "TRUE")
		$print['target_cookie_true_check'] = "checked";
	else
		$print['target_cookie_false_check'] = "checked";

	if ($policy['target_file'] == "TRUE")
		$print['target_file_true_check'] = "checked";
	else
		$print['target_file_false_check'] = "checked";

?>
                            <a name="target"></a>
                            <table cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_config_submit.php?mode=CONFIG_TARGET" method="post">
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">GET 변수</th>
                                <td>
                                  <input type="radio" name="target_get" value="true" <?php echo $print['target_get_true_check']?>>적용
                                  <input type="radio" name="target_get" value="false" <?php echo $print['target_get_false_check']?>>비적용
                                </td>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">POST 변수</th>
                                <td width="150">
                                  <input type="radio" name="target_post" value="true" <?php echo $print['target_post_true_check']?>>적용
                                  <input type="radio" name="target_post" value="false" <?php echo $print['target_post_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">FILE 변수</th>
                                <td width="150">
                                  <input type="radio" name="target_file" value="true" <?php echo $print['target_file_true_check']?>>적용
                                  <input type="radio" name="target_file" value="false" <?php echo $print['target_file_false_check']?>>비적용
                                </td>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">COOKIE 변수</th>
                                <td>
                                  <input type="radio" name="target_cookie" value="true" <?php echo $print['target_cookie_true_check']?>>적용
                                  <input type="radio" name="target_cookie" value="false" <?php echo $print['target_cookie_false_check']?>>비적용
                                </td>
                              </tr>
                              <tr>
                                <th width="150" height="30"></th>
                                <td width="500" colspan="3">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>적용(True) - 각 변수에 대해서 정책을 적용함.</div>
                                        <li>비적용(False) - 각 변수에  대해서 정책을 적용하지 않음.</div>
                                      </td>
                                    </tr>
                                  </table>
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
/* CASTLE 적용대상 수정 폼 끝 */
?>
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
