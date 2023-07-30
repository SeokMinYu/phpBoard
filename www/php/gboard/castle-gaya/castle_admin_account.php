<?php 

//@UTF-8 castle_admin_account.php
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
                                  <font color="#C0C0C0"><li><b>계정설정</b> - CASTLE 관리자 계정을 설정합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>주의: 관리자 계정 정보는 보안에 아주 중요하므로 관리에 주의하여 주십시오.</b><br>
                                  관리자 계정의 아이디 및 암호 모두 변경 가능하며 암호 찾기 기능은 지원하지 않습니다.<br>
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
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">관리자 계정 설정</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* 관리자 계정 정보 수정 폼 시작 */

	// 관리자 계정 정보 설정 변수 설정
	$policy['admin_id'] = "";
	$policy['admin_password'] = "";

	$policy['admin_id'] =& $_CASTLE_POLICY['CONFIG']['ADMIN']['ID'];
	$policy['admin_password'] =& $_CASTLE_POLICY['CONFIG']['ADMIN']['PASSWORD'];

	$print['admin_id'] = base64_decode($policy['admin_id']);
	$print['admin_password'] = base64_decode($policy['admin_password']);
?>
                            <a name="account"></a>
                            <table cellspacing="2" cellpadding="6" border="0">
                            <form action="castle_admin_account_submit.php?mode=CONFIG_ACCOUNT" method="post">
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">아이디</th>
                                <td colspan="3"><input type="text" name="admin_id" size="16" maxlength="32" value="<?php echo $print['admin_id']?>"></td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">신규암호</th>
                                <td><input type="password" name="admin_password" size="16" maxlength="32"></td>
                                <th width="100" height="30" bgcolor="#D8D8D8" align="right">암호 확인</th>
                                <td><input type="password" name="admin_repassword" size="16" maxlength="32"></td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">이전암호</th>
                                <td colspan="3"><input type="password" name="admin_old_password" size="16" maxlength="32"></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td colspan="3">
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>아이디(ID) - 최소 4자, 최대 16자로 설정하셔야 합니다.
                                        <li>암호(Password) - 최소 8자, 최대 32자로 설정하셔야 합니다.
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
/* 관리자 계정 정보 수정 폼 끝 */
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
