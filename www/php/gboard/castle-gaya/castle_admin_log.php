<?php 

//@UTF-8 castle_admin_log.php
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
    <script language=javascript>
    <!--
      function log_delete_submit(log_filename)
      {
        ret = confirm("삭제하시겠습니까?");
        if (!ret)
          return;
        location.href = 'castle_admin_log_submit.php?mode=LOG_DELETE&log_filename='+log_filename;  
      }
    //-->
    </script>
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
                                  <font color="#C0C0C0"><li><b>로그관리</b> - CASTLE 로그 관리합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>알림: 로그는 수시로 파일 용량을 확인하시고 백업 받으시길 바랍니다.</b><br>
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
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">CASTLE 로그설정</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
<?php
/* 로그 정보 수정 폼 시작 */

	// 로그 정보 설정 변수 설정
	$policy['log_bool'] = "FALSE";
	$policy['log_filename'] = "";
	$policy['log_simple'] = "";
	$policy['log_detail'] = "";
	$policy['log_list_count'] = "";
	$policy['log_charset_utf8'] = "";
	$policy['log_charset_euckr'] = "";

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['BOOL']))
		$policy['log_bool'] =& $_CASTLE_POLICY['CONFIG']['LOG']['BOOL'];

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['FILENAME']))
		$policy['log_filename'] =& $_CASTLE_POLICY['CONFIG']['LOG']['FILENAME'];

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['SIMPLE']))
		$policy['log_simple'] =& $_CASTLE_POLICY['CONFIG']['LOG']['SIMPLE'];

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['DETAIL']))
		$policy['log_detail'] =& $_CASTLE_POLICY['CONFIG']['LOG']['DETAIL'];

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['LIST_COUNT']))
		$policy['log_list_count'] =& $_CASTLE_POLICY['CONFIG']['LOG']['LIST_COUNT'];

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['UTF-8']))
		$policy['log_charset_utf8'] =& $_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['UTF-8'];

	if (isset($_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['UTF-8']))
		$policy['log_charset_euckr'] =& $_CASTLE_POLICY['CONFIG']['LOG']['CHARSET']['eucKR'];

	$print['log_filename'] = base64_decode($policy['log_filename']);
	$print['log_bool_true_check'] = "";
	$print['log_bool_false_check'] = "";
	$print['log_simple_check'] = "";
	$print['log_detail_check'] = "";
	$print['log_charset_utf8_check'] = "";
	$print['log_charset_euckr_check'] = "";

	if (base64_decode($policy['log_bool']) == "TRUE")
		$print['log_bool_true_check'] = "checked";
	else
		$print['log_bool_false_check'] = "checked";

	if (base64_decode($policy['log_simple']) == "TRUE")
		$print['log_simple_check'] = "checked";
	else
		$print['log_detail_check'] = "checked";

	if (base64_decode($policy['log_charset_utf8']) == "TRUE") 
		$print['log_charset_utf8_check'] = "checked";
	else
	if (base64_decode($policy['log_charset_euckr']) == "TRUE") 
		$print['log_charset_euckr_check'] = "checked";

	$print['log_list_count'] = base64_decode($policy['log_list_count']);
?>
                            <table cellspacing="2" cellpadding="5" border="0">
                            <form action="castle_admin_log_submit.php?mode=LOG_MODIFY" method="post">
							<!--
							  <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">로그 파일이름</th>
                                <td width="475"><input type="text" name="log_filename" size="48" maxlength="64" value="<?php echo $print['log_filename']?>"></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        "log/Year.Month.Day-로그파일이름"에 기록됩니다.<br>
                                        (ex. log/20071016-castle_log.txt)
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
							  -->
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">로그 기록여부</th>
                                <td>
                                  <input type="radio" name="log_bool" value="true" <?php echo $print['log_bool_true_check']?>>기록
                                  <input type="radio" name="log_bool" value="false" <?php echo $print['log_bool_false_check']?>>무기록
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>기록(logging) - CASTLE 기록을 남김(기본).
                                        <li>무기록(none) - CASTLE 기록을 남기지 않음.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">로그 기록방식</th>
                                <td>
                                  <input type="radio" name="log_mode" value="simple" <?php echo $print['log_simple_check']?>>간략
                                  <input type="radio" name="log_mode" value="detail" <?php echo $print['log_detail_check']?>>상세
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>간략(simple) - CASTLE 기록을 간략히 남김(기본).<br>
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                           (REMOTE_ADDR - [Date] REQUEST_URL: Message)
                                        <li>상세(detail) - CASTLE 기록을 상세히 남김.<br>
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                           (REMOTE_ADDR - [Date] REQUEST_URL: Message: ...)
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">로그 문자셋</th>
                                <td>
                                  <input type="radio" name="log_charset" value="UTF-8" <?php echo $print['log_charset_utf8_check']?>>UTF-8(기본)
                                  <input type="radio" name="log_charset" value="eucKR" <?php echo $print['log_charset_euckr_check']?>>eucKR
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <table width="100%" height="50" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>UTF-8 - 로그 기록을 UTF-8로 하는 경우(기본).
                                        <li>eucKR - 로그 기록을 eucKR로 하는 경우.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="150" height="30" bgcolor="#D8D8D8" align="right">로그 목록개수</th>
                                <td>
                                  <input type="text" name="log_list_count" value="<?php echo $print['log_list_count']?>">
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
                                  <a href="#"><input type="image" src="img/button_confirm.gif" ></a>
                                  <input type="image" src="img/button_cancel.gif" onclick="reset(); return false;"></a>
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
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">CASTLE 로그목록</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                              <tr height="30">
                                <th width="80" bgcolor="#D8D8D8">번호</th>
                                <th width="370" bgcolor="#D8D8D8">로그파일</th>
                                <th width="100" bgcolor="#D8D8D8">파일크기</th>
                                <th width="200" bgcolor="#D8D8D8">최근시간</th>
                                <th width="50" bgcolor="#D8D8D8">삭제</th>
                              </tr>
<?php
/* 로그 파일 목록 출력 시작 */

	$log['dirname'] = "./log";

		// 로그 디렉터리 목록 가져오기
	if (is_dir($log['dirname'])) {

		if ($dh = opendir($log['dirname'])) {

			while (($file = readdir($dh)) !== FALSE) 
			{
					// 로그 관련 파일만 출력
				if (!strstr($file, $print['log_filename']))
					continue;

				$logs[$file]['filename'] = $file;
				$logs[$file]['filesize'] = number_format(filesize($log['dirname'] . "/" . $file));
				$logs[$file]['filemtime'] = date("F d Y H:i:s", (filemtime($log['dirname'] . "/" . $file)));
			}
			closedir($dh);
		}

	}

	$print['log_num'] = 0;
	$print['log_total_count'] = 0;

		// 로그 목록 정렬
	if (isset($logs)) {

		krsort($logs);

			// 로그 출력
		$print['log_total_count'] = count($logs);

		foreach ($logs as $log) 
		{
			if ($print['log_num'] >= $print['log_list_count'])
				break;

			$print['filename'] = htmlentities($log['filename'], ENT_QUOTES, "UTF-8");
			$print['filesize'] = htmlentities($log['filesize'], ENT_QUOTES, "UTF-8");
			$print['filemtime'] = htmlentities($log['filemtime'], ENT_QUOTES, "UTF-8");

?>
                              <tr height="25" bgcolor="#FFFFFF">
                                <td align="center">
                                  <?php echo ++$print['log_num']?>
                                </td>
                                <td align="center">
                                  <table>
                                    <tr>
                                      <td><a href="castle_admin_download.php?filename=<?php echo $print['filename']?>&filepath=./log/<?php echo $print['filename']?>"><?php echo $print['filename']?></a></td>
                                    <td><a href="castle_admin_download.php?filename=<?php echo $print['filename']?>&filepath=./log/<?php echo $print['filename']?>"><img src="img/button_download.gif" border="0"></a></td>
                                    </tr>
                                  </table>
                                <td align="center"><?php echo $print['filesize']?> Bytes</td>
                                <td align="center"><?php echo $print['filemtime']?></td>
                                <td align="center"><input type="image" src="img/button_delete.jpg" onclick="log_delete_submit('<?php echo $print['filename']?>'); return false;"></td>
                              </tr>
<?php
/* 로그 파일 목록 출력 시작 */

		}	

	}

?>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" height="40">
                              <tr>
                                <td width="100%" height="100%" align="center">총 <b><?php echo $print['log_total_count']?></b> 개가 기록되었습니다.</td>
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
