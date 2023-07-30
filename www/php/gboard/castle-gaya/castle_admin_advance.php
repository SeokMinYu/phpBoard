<?php 

//@UTF-8 castle_admin_advance.php
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

/* 내부 변수 초기화 시작 */
castle_init_page();

//$page['mode']: 페이지별 실행 구분 변수
if (isset($_GET) && isset($_GET['mode']))
	$page['mode'] = $_GET['mode'];

/* 내부 변수 초기화 끝 */

$error_msg['page_name'] = "페이지 이름은 최소 4자 이상이여야 합니다.";
$error_msg['page_view'] = "페이이 보기를 먼저 실행하십시오.";
$error_msg['delete_confirm'] = "삭제하시겠습니까?";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="StyleSheet" HREF="style.css" type="text/css" title="style">
<?php include_once("castle_admin_title.php"); ?>
    <script language=javascript>
    <!--
      function page_view() 
      {
        var len = document.page_insert_form.page_name.value.length;
        if (len < 4) {
          alert("<?php echo $error_msg['page_name']?>");
          return;
        }
        window.open(document.page_insert_form.page_name.value, "page_view");
        document.page_insert_form.page_view_bool.value = 1;
      }

      function page_view2(url) 
      {
        window.open(url, "page_view");
      }
 
      function page_view_check_submit()
      {
        if (document.page_insert_form.page_view_bool.value == "0") {
          alert("<?php echo $error_msg['page_view']?>");
          return false;
        }
        document.page_insert_form.submit();
      }

      function page_delete_submit(page_name)
      {
        ret = confirm("<?php echo $error_msg['delete_confirm']?>");
        if (!ret)
          return;
        location.href = 'castle_admin_advance_submit.php?mode=PAGE_DELETE&page_name='+page_name;  
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
                                  <font color="#C0C0C0"><li><b>고급설정</b> - CASTLE 고급정책을 설정합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>주의: 이곳 페이지별 관리 목록에 등록되지 않은 페이지는 기본정책 설정에 따라 적용됩니다.</b><br>
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
                                <td width="80" bgcolor="#B0B0B0">&nbsp;<a href="castle_admin_advance.php?mode=INSERT#insert_form"><img src="img/button_plus.gif" border="0"></a></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center">
                                  <b><font color="#FFFFFF">관리 페이지 목록</font></b>
                                </td>
                                <td width="80" bgcolor="#B0B0B0"></td>
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
                                <th width="50" bgcolor="#D8D8D8">번호</th>
                                <th width="350" bgcolor="#D8D8D8">페이지 이름</th>
                                <th width="100" bgcolor="#D8D8D8">변수개수</th>
                                <th width="100" bgcolor="#D8D8D8">사용여부</th>
                                <th width="100" bgcolor="#D8D8D8">허용기반</th>
                                <th width="50" bgcolor="#D8D8D8">수정</th>
                                <th width="50" bgcolor="#D8D8D8">삭제</th>
                              </tr>
<?php
/* 페이지 목록 출력 시작 */

	// 페이지 목록 가져오기
	if (isset($_CASTLE_POLICY['ADVANCE']['PAGE']))
		$policy['page_list'] =& $_CASTLE_POLICY['ADVANCE']['PAGE'];
	else
		$policy['page_list'] = array();

	$print['page_count'] = count($policy['page_list']);
	$print['page_num'] = 1;

	foreach ($policy['page_list'] as $key => $value)
	{
		$policy['page'] = $policy['page_list'][$key];

		$print['page_name'] = $key;
		$print['page_bool'] = "<font color=\"red\"><b>차단</b></font>";
		$print['page_base'] = "<font color=\"red\"><b>블랙리스트</b></font>";

		if (isset($policy['page']['BOOL']) && base64_decode($policy['page']['BOOL']) == "TRUE")
			$print['page_bool'] = "<font color=\"blue\"><b>허용</b></font>";

		if (isset($policy['page']['ALLOW']) && base64_decode($policy['page']['ALLOW']) == "TRUE")
			$print['page_base'] = "<font color=\"blue\"><b>화이트리스트</b></font>";

		if (isset($policy['page']['LIST']))
			$print['page_var_num'] = count($policy['page']['LIST']);
		else
			$print['page_var_num'] = "--";
?>
                              <tr height="25" bgcolor="#FFFFFF">
                                <td align="center">
                                  <a name="<?php echo $print['page_num']?>"></a><?php echo $print['page_num']?>
                                </td>
                                <td>
                                  <table>
                                    <tr>
                                      <td><?php echo $print['page_name']?></td>
                                      <td><a href="castle_admin_advance_detail.php?page_name=<?php echo $print['page_name']?>&pos=<?php echo $print['page_num']-1?>"><img src="img/button_set.gif" border="0"></a></td>
                                    </tr>
                                  </table>
                                </td>
                                <td align="center"><?php echo $print['page_var_num']?></td>
                                <td align="center"><?php echo $print['page_bool']?></td>
                                <td align="center"><?php echo $print['page_base']?></td>
                                <td align="center"><a href="castle_admin_advance.php?page_name=<?php echo $print['page_name']?>&mode=MODIFY#<?php echo $print['page_num']-1?>"><img src="img/button_modify.jpg" border="0"></a></td>
                                <td align="center"><input type="image" src="img/button_delete.jpg" onclick="page_delete_submit('<?php echo $print['page_name']?>'); return false;"></td>
                              </tr>
<?php
		$print['page_num']++;

		// 페이지 수정 모드
		if (isset($page['mode']) && $page['mode'] == "MODIFY") 
		{
			$submit['page_name'] = $_GET['page_name'];

			if ($print['page_name'] != $submit['page_name']) 
				continue;

			$print['page_bool_true_check'] = "checked";
			$print['page_bool_false_check'] = "";
			$print['page_base_allow_check'] = "checked";
			$print['page_base_deny_check'] = "";

			if (isset($policy['page']['BOOL']) && base64_decode($policy['page']['BOOL']) == "TRUE") 
				$print['page_bool_true_check'] = "checked";
			else
				$print['page_bool_false_check'] = "checked";

			if (isset($policy['page']['ALLOW']) && base64_decode($policy['page']['ALLOW'])  == "TRUE")
				$print['page_base_allow_check'] = "checked";
			else
				$print['page_base_deny_check'] = "checked";
?>
                              <tr>
                                <td colspan="7" align="right">
                                  <table width="750" height="1">
                                    <tr>
                                      <td width="100%" height="100%" background="img/line_bg.gif"></td>
                                    </tr>
                                  </table>
                                  <table width="750" cellspacing="2" cellpadding="5" border="0">
                                  <form name="page_modify_form" action="castle_admin_advance_submit.php?mode=PAGE_MODIFY" method="post">
                                    <tr>
                                      <th width="150" height="30" rowspan="4" bgcolor="#D8D8D8" align="right">페이지 관리</th>
                                    </tr>
                                    <tr>
                                      <th width="100" height="30" bgcolor="#D8D8D8">페이지 이름</th>
                                      <td width="300">
                                        <input type="hidden" name="page_name" value="<?php echo $print['page_name']?>">
                                        <table width="300" height="25" cellspacing="1" cellpadding="2" border="0" bgcolor="#000000">
                                          <tr>
                                            <td bgcolor="#FFFFFF">
                                              <?php echo $print['page_name']?>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                      <td width="200">
                                        <input type="button" value="페이지보기" class="submit" onclick="page_view2('<?php echo $print['page_name']?>');";>
                                      </td>
                                    </tr>
                                    <tr>
                                      <th width="100" height="30" bgcolor="#D8D8D8">사용여부</th>
                                      <td width="480" colspan="2">
                                        <input type="radio" name="page_bool" value="true" <?php echo $print['page_bool_true_check']?>>허용함(기본)
                                        <input type="radio" name="page_bool" value="allow" <?php echo $print['page_bool_false_check']?>>차단함
                                      </td>
                                    </tr>
                                    <tr>
                                      <th width="100" height="30" bgcolor="#D8D8D8">허용기반</th>
                                      <td width="480" colspan="2">
                                        <input type="radio" name="page_base" value="allow" <?php echo $print['page_base_allow_check']?>>화이트리스트(기본)
                                        <input type="radio" name="page_base" value="deny" <?php echo $print['page_base_deny_check']?>>블랙리스트
                                      </td>
                                    </tr>
                                  </table>
                                  <table width="700" height="1">
                                    <tr>
                                      <td width="100%" height="100%" background="img/line_bg.gif"></td>
                                    </tr>
                                  </table>
                                  <table width="475" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                      <td width="175">&nbsp;</td>
                                      <td width="300">
                                        <input type="image" src="img/button_confirm.gif">
                                        <a href="" onclick="history.back(-1); return false;"><img src="img/button_cancel.gif" border="0"></a>
                                      </td>
                                    </tr>
                                  </table>
                                </form>
                              </td>
                            </tr>
<?php
		}
		/* 페이지 수정 모드 끝 */
	}
/* 페이지 목록 출력 끝 */
?>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" height="40">
                              <tr>
                                <td width="100%" height="100%" align="center">총 <b><?php echo $print['page_count']?></b> 개가 설정되었습니다.</td>
                              </tr>
                            </table>
<?php
	/* 페이별 추가 모드 */
	if (isset($page['mode']) && $page['mode'] == "INSERT") 
	{
?>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <a name="insert_form"></a>
                            <table width="800" height="25" cellspacing="0" cellpadding="0" border="0">
                            <form name="page_insert_form" action="castle_admin_advance_submit.php?mode=PAGE_INSERT" method="post" onsubmit="page_view_check_submit(); return false;">
                              <tr>
                                <td width="2"></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center"><b><font color="#FFFFFF">신규 관리 페이지 추가</font></b></td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                              <tr>
                                <th width="150" height="30" rowspan="6" bgcolor="#D8D8D8" align="right">페이지 관리</th>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">페이지 이름</th>
                                <td width="480">
                                  <input type="text" name="page_name" size="50">
                                  <input type="hidden" name="page_view_bool" value="0">
                                  <input type="button" value="페이지보기" class="submit" onclick="page_view();";>
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="30" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <b>http://host<font color="red">/path</font></b>에서 <font color="red"><b>/path</b></font>를 페이지 이름으로 적어 주십시오.
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">사용여부</th>
                                <td width="480">
                                  <input type="radio" name="page_bool" value="true" checked>허용함(기본)
                                  <input type="radio" name="page_bool" value="false">차단함
                                </td>
                              </tr>
                              <tr>
                                <th width="100"></th>
                                <td width="410" colspan="2">
                                  <table width="100%" height="30" cellspacing="1" cellpadding="10" border="0" bgcolor="#000000">
                                    <tr>
                                      <td bgcolor="#FFFFFF" style="line-height:120%" nowrap>
                                        <li>허용함 - 이 파일을 대한 접근을 허용합니다.<br>
                                        <li>차단함 - 이 파일에 대한 접근이 무조건 차단됩니다.<br>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <th width="100" height="30" bgcolor="#D8D8D8">허용기반</th>
                                <td width="480">
                                  <input type="radio" name="page_base" value="allow" checked>화이트리스트(기본)
                                  <input type="radio" name="page_base" value="deny">블랙리스트
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="475" cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td width="175">&nbsp;</td>
                                <td width="300">
                                  <input type="image" src="img/button_confirm.gif">
                                  <a href="" onclick="history.back(-1); return false;"><img src="img/button_cancel.gif" border="0"></a>
                                </td>
                              </tr>
                            </form>
                            </table>
<?php
	}
	/* 페이별 추가 모드 끝 */
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
