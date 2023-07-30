<?php 

//@UTF-8 install_step1.php
/*
 * Castle: KISA Web Attack Defender - PHP Version
 * 
 * Author : 이재서 <mirr1004@gmail.com>
 *          주필환 <juluxer@gmail.com>
 *
 * Last modified Jun 22 2009
 *
 */

/* 인증 및 설치 검사 예외 페이지 */
$exception['check_installed'] = TRUE;
$exception['check_authorized'] = TRUE;

include_once("castle_admin_lib.php");

/* 운영체제별 판단 */
$check['os'] = getenv("OS");
if (strstr($check['os'], "Windows")) {
	// 윈도우인 경우 바로 스텝2로 이동
	html_move("install_step2.php");
}

/* 설치 여부 판단 */
if (file_exists("castle_policy.php"))
	html_msgmove("CASTLE - PHP 버전이 이미 설치되어 있습니다.", "castle_admin.php");

if (!isset($_SESSION['castle_install']))
	html_msgmove("install.php 설치 초기 페이지로 접근하십시오.", "install.php");

/* 설치 디렉터리 상태 가져오기 */
$fstat['castle_dir'] = stat("./");
$fstat['castle_log'] = stat("./log");

	// 10진수를 8진수로 수정
$fstat['castle_dir'] = decoct($fstat['castle_dir']['mode'] & 0000777);
$fstat['castle_log'] = decoct($fstat['castle_log']['mode'] & 0000777);

	// 출력 형태 작성 
$print['castle_dir_perm'] = "(현재상태: <b><font color=\"blue\">".$fstat['castle_dir']."</font></b>)";
$print['castle_log_perm'] = "(현재상태: <b><font color=\"blue\">".$fstat['castle_log']."</font></b>)";

if ($fstat['castle_dir'] == "707") 
	$print['castle_dir_perm'] .= " - <font color=\"green\">설정이 완료되었습니다.</font>";
else
	$print['castle_dir_perm'] .= " - <font color=\"red\">설정이 완료되지 않았습니다.</font>";

if ($fstat['castle_log'] == "707") 
	$print['castle_log_perm'] .= " - <font color=\"green\">설정이 완료되었습니다.</font>";
else
	$print['castle_log_perm'] .= " - <font color=\"red\">설정이 완료되지 않았습니다.</font>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="StyleSheet" HREF="style.css" type="text/css" title="style">
    <title>CASTLE - 설치 1단계</title>
  </head>
  <body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" bgcolor="#D0D0D0">
    <script language="javascript">
      function nextstep() {
<?php
/* 다음 단계 버튼 활성화 */
	if ($fstat['castle_dir'] == "707" && $fstat['castle_log'] == "707") {
?>
        location.href = "install_step2.php";
<?php
	}
	else {
?>
        alert("권한이 설정되지 않았습니다.");
<?php
	}
?>
      }
    </script>
    <br><br><br>
    <center>
      <table width="600" height="80" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td width="100%" height="80">
            <img src="img/logo.png" border="0" alt="LOGO">
          </td>
        </tr>
        <tr>
          <td>
            <form name="step1">
            <table width="600" cellspacing="1" cellpadding="20" border="0" bgcolor="#000000">
              <tr>
                <td width="100%" bgcolor="#FFFFFF" style="line-height:160%" nowrap>
                  <li><b>설치를 위하여 디렉터리 권한을 체크합니다.</b><br>
                  <br>
                  CASTLE 설치를 위하여 다음의 디렉터리를 권한 <font color="red"><b>707</b></font>로 설정하십시오.
                  <br>
                  <br>
                  <ul>
                    - CASTLE을 설치할 디렉터리 <?php echo $print['castle_dir_perm']?><br>
                   - ./log 디렉터리 <?php echo $print['castle_log_perm']?><br>
                  </ul>
                  ※ FTP나 텔넷, SSH를 이용하여 권한을 설정하십시오.
                </td>
              </tr>
            </table>
            <br><br>
            <table width="100%" height="1">
              <tr>
                <td width="100%" height="100%" background="img/line_bg.gif"></td>
              </tr>
            </table>
            <input type="button" value="다음 단계로(Next).." class="submit" style="height:20px;" onclick="return nextstep();">
            </form>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
