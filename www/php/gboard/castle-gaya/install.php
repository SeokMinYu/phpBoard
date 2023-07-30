<?php 

//@UTF-8 install.php
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

/* 설치 여부 판단 */
if (file_exists("castle_policy.php"))
	html_msgmove("CASTLE - PHP 버전이 이미 설치되어 있습니다.", "castle_admin.php");

$error_msg['license'] = "라이센스를 모두 읽고 이에 동의하십니까?";

	// 인증 다음 단계 인증을 위한 세션
$_SESSION['castle_install'] = "TRUE";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="StyleSheet" HREF="style.css" type="text/css" title="style">
    <title>CASTLE - PHP 버전 설치를 시작합니다.</title>
  </head>
  <body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" bgcolor="#D0D0D0">
    <script language="javascript">
      function agree_check() {
        if (document.license.agreement.checked) {
           if (confirm("라이센스를 모두 읽고 이에 동의하십니까?"))
              return true;
           else
              return false;
        }
      }
      function submit_check() {
        if (!document.license.agreement.checked) {
           alert("라이센스를 모두 읽고 이에 동의하십니까?");
           return false;
        }

        location.href = "install_step1.php";
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
            <form name="license">
            <textarea cols="90" rows="20" class="textarea">
 안내문&라이센스

 중요한 내용이므로 자세히 읽어 보시기 바랍니다.
  
 사용자는 비영리 목적으로 사용함에 한하여 본“소프트웨어”를 사용할 권리를
 허용합니다. 본 "소프트웨어"제품과 제품 내에 포함된 모든 부속물,부속 인쇄물
 및 소프트웨어의 복사본들에 대한 저작권과 지적 소유권은
 한국정보보호진흥원에게 있습니다. 

 한국정보보호진흥원은 본“소프트웨어”를 사용할 수 없거나 사용법을 제대로
 인식하지 못해서 발생한 이익 손실,업무 중단,사업 정보의 손실 또는 금전상의
 손실 등 사업상의 손해를 포함한 부수적이고 간접적인 손해에 대해서 비록 이와
 같은 손해 가능성에 대해 사전에 알고 있었다고 해도 한국정보보호진흥원에서는
 어떠한 책임도 지지 않습니다.
            </textarea><br>
            <input name="agreement" type="checkbox" value="1" onclick="return agree_check();"> 위의 라이센스를 모두 읽었으며 동의합니다.<br>
            <br>
			※ CASTLE과 관련된 문의 사항은 <font color="gray">castle@krcert.or.kr</font>로 하십시오.
            <br><br>
            <table width="100%" height="1">
              <tr>
                <td width="100%" height="100%" background="img/line_bg.gif"></td>
              </tr>
            </table>
            <input type="button" value="다음 단계로(Next).." class="submit" style="height:20px;" onclick="return submit_check();">
            </form>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
