<?php

//@UTF-8 castle_policy_view.php
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
                                  <font color="#C0C0C0"><li><b>정책보기</b> - CASTLE 정책보기를 실행합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>정책 보기: CASTLE 정책을 트리 구조와 정책 구조로 출력합니다.</b><br>
                                </td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                              <tr>
                                <td>
                                  <table width="800" cellspacing="1" cellpadding="20" border="0" bgcolor="#000000">
                                    <tr>
                                      <td width="100%" height="100%" style="line-height:130%" bgcolor="#FFFFFF" nowrap>
<?php echo castle_print_policy_tree()?>
                                      </td>
                                    </tr>
                                  <table width="800" height="1">
                                    <tr>
                                      <td width="100" height="100%" background="img/line_bg.gif"></td>
                                    </tr>
                                  </table>
                                  <table width="800" cellspacing="1" cellpadding="20" border="0" bgcolor="#000000">
                                    <tr>
                                      <td width="100%" height="100%" style="line-height:130%" bgcolor="#FFFFFF" nowrap>
<?php echo castle_print_policy()?>
                                      </td>
                                    </tr>
                                  </table>
                                  <table width="800" height="1">
                                    <tr>
                                      <td width="100" height="100%" background="img/line_bg.gif"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
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
