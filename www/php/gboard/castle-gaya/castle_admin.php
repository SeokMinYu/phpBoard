<?php 

//@UTF-8 castle_admin.php
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
                      <table width="100%" height="100%" cellspacing="20" cellpadding="0" border="0">
                        <tr valign="top">
                          <td width="100%" style="line-height:160%" nowrap>
                            <li><b>공식 영문명: CASTLE - KISA Web Attack Defender / 공식 국문명: 캐슬</b><br>
                            이하 영문의 경우 <b>CASTLE</b>로 약칭하며 국문인 경우에는 <b>캐슬</b>로 약칭합니다.<br>
                            <br>
                            <b>CASTLE - PHP 버전 관리자 페이지입니다.</b><br>
                            공개형 CASTLE의 전체적인 관리를 할 수 있는 관리자 페이지입니다.<br>
                            관리자 페이지는 기본, 정책 및 고급 설정등 CASTLE에 관련된 모든 설정을 할 수 있습니다.<br>
                            다음의 내용은 간단한 설명이며 보다 자세한 설명은 <a href="http://www.krcert.or.kr" target="castle"><b>공식홈페이지</b></a>를 통해서 확인할 수 있습니다.<br>
                            <br>
                            <table width="800" height="1">
                              <tr>
                                <td width="100" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td width="5"></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">메뉴 설명</font></b></td>
                                <td width="5"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                              <tr valign="top">
                                <td width="100" height="100%" bgcolor="#D8D8D8" style="line-height:160%" nowrap>
                                  1. <a href="castle_admin.php#home">HOME</a><br>
                                  2. <a href="castle_admin.php#account">계정설정</a><br>
                                  3. <a href="castle_admin.php#config">기본설정</a><br>
                                  4. <a href="castle_admin.php#policy">정책설정</a><br>
                                  5. <a href="castle_admin.php#advance">고급설정</a><br>
                                  6. <a href="castle_admin.php#log">로그관리</a><br>
                                  7. <a href="castle_admin.php#policyview">정책보기</a><br>
                                  8. <a href="castle_admin.php#backup">백업관리</a><br>
                                </td>
                                <td>
                                  <table width="100%" height="100%" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF">
                                        <pre style="line-height:160%">
<a name="home"><b>1. HOME</b></a>
    HOME 메뉴는 현재 페이지인 관리자 처음화면으로 이동하는 것을 의미합니다.


<a name="account"><b>2. 계정설정</b></a>
    관리자 계정 정보는 아주 중요한 정보입니다. 따라서 설정상 주의하여야 합니다.
    암호는 이중 MD5로 저장되어 안전합니다.
    <font color="red">* 주의: 아이디와 암호는 반드시 8자 이상으로 설정하십시오.</font>


<a name="config"><b>3. 기본설정</b></a>
    기본 설정은 CASTLE의 기본적이면서 가장 중요한 기능을 설정합니다.

    가. CASTLE 기본설정
    나. 사이트 설정
    다. CASTLE 적용대상
    
    
<a name="policy"><b>4. 정책설정</b></a>
    정책 설정은 실제 적용할  CASTLE 정책을 설정합니다. 
    이렇게 설정된  정책은 고급설정 부분에서 설정되지 않은 모든 페이지에 적용됩니다.

    가. SQL Injection 정책 설정
    나. XSS 정책 설정
    다. 금칙어(WORD) 정책 설정
    라. 태그(TAG) 정책 설정
    마. 파일(FILE) 정책 설정


<a name="advance"><b>5. 고급설정</b></a>
    고급 설정은 각 페이지별로 정책을 설정합니다.
    이 정책은 정책 설정의 정책보다 우선 적용됩니다.

    가. 페이지 추가/삭제
    나. 페이지별 변수 추가/삭제


<a name="log"><b>6. 로그관리</b></a>
    로그 관리는 CASTLE에 의해 탐지되어 기록되는 로그들을 관리합니다.
    
    가. 로그 기록여부 설정
    나. 로그 기록방식 설정
    다. 로그 작성 문자셋 설정
    라. 로그 목록개수 설정


<a name="policyview"><b>7. 정책보기</b></a>
    정책 보기는 현재 설정되어 있는 정책을 보여줍니다.

    가. 트리 형태의 정책보기
    나. 저장 형태의 정책보기


<a name="backup"><b>8. 백업관리</b></a>
    백업 관리는 현재 설정되어 있는 정책을 백업하여 관리자의 개인 컴퓨터에 저장할 수 있습니다.

    가. 정책파일 정보보기
    나. 정책파일 다운로드


                                        </pre>
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
