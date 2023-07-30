<?php 

if ( !( in_array( $_SERVER["REMOTE_ADDR"], array("106.246.89.150", "999.999.999.999") ) ) ) exit();

//@UTF-8 castle_admin_login.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Sep 18 2008
 *
 */

/* 인증 검사 예외 페이지 */
$exception['check_authorized'] = TRUE;

include_once("castle_admin_lib.php");

$error_msg['admin_id'] = "관리자 아이디가 입력되지 않았습니다.";
$error_msg['admin_password'] = "관리자 암호가 입력되지 않았습니다.";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="StyleSheet" HREF="style.css" type="text/css" title="style">
<?php include_once("castle_admin_title.php"); ?>
  </head>
  <body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" bgcolor="#D0D0D0">
    <script language="javascript">
      function nextstep() {
        var len = document.login.admin_id.value.length;
        if (len == 0) {
          alert("<?php echo $error_msg['admin_id']?>");
          document.login.admin_id.focus();
          return false;
        }
        len = document.login.admin_password.value.length;
        if (len == 0) {
          alert("<?php echo $error_msg['admin_password']?>");
          document.login.admin_password.focus();
          return false;
        }
        document.login.submit();
      }
    </script>
    <br><br><br>
    <center>
      <table width="600" height="80" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td width="100%" height="80" align="center">
            <img src="img/logo.png" border="0" alt="LOGO">
          </td>
        </tr>
        <tr>
          <td>
            <form name="login" action="castle_admin_login_submit.php" method="post" onsubmit="return nextstep();">
            <table width="100%" height="1">
              <tr>
                <td width="100%" height="100%" background="img/line_bg.gif"></td>
              </tr>
            </table>
            <br><br>
            <center>
            <b>관리자 인증화면</b>
            <br><br><br>
            <table width="300" cellspacing="1" cellpadding="5" border="0" bgcolor="#808080">
              <tr height="30">
                <td width="100" bgcolor="#C0C0C0" align="right"><b><font color="#404040">관리자 아이디</font></b></td>
                <td width="200" colspan="3" bgcolor="#FFFFFF"><input type="text" name="admin_id" size="24"></td>
              </tr>
              <tr height="30">
                <td width="100" bgcolor="#C0C0C0" align="right"><b><font color="#404040">암호</font></b></td>
                <td width="200" bgcolor="#FFFFFF"><input type="password" name="admin_password" size="24"></td>
              </tr>
              </tr>
            </table>
            <br>
            <input type="submit" value="로그인(Login)" class="submit" style="height:20px;">
            <input type="button" value="취소(Cancel)" class="submit" style="height:20px;" onclick="reset(); return false">
            <br><br><br>
            <table width="100%" height="1">
              <tr>
                <td width="100%" height="100%" background="img/line_bg.gif"></td>
              </tr>
            </table>
            <br>
<?php include_once("castle_admin_bottom.php"); ?>
            </form>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
