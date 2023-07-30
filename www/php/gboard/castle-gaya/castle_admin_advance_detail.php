<?php 

//@UTF-8 castle_admin_advance_detail.php
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

// 페이지 이름 체크
if (!isset($_GET['page_name'])) 
	html_msgback("페이지 이름이 입력되지 않았습니다.");

$submit['page_name'] = $_GET['page_name'];
$submit['return_pos'] = "";

if (isset($_GET['pos'])) 
	$submit['return_pos'] = $_GET['pos'];

if (!isset($_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']]))
	html_msgback("잘못된 페이지 이름입니다.");

$policy['page'] =& $_CASTLE_POLICY['ADVANCE']['PAGE'][$submit['page_name']];

$print['return_pos'] = $submit['return_pos'];
$print['page_name'] = $submit['page_name'];

$print['page_bool_true_check'] = "checked";
$print['page_bool_false_check'] = "";
$print['page_base_allow_check'] = "checked";
$print['page_base_deny_check'] = "";

if (base64_decode($policy['page']['BOOL']) == "TRUE") 
	$print['page_bool_true_check'] = "checked";
else
	$print['page_bool_false_check'] = "checked";

if (base64_decode($policy['page']['ALLOW'])  == "TRUE")
	$print['page_base_allow_check'] = "checked";
else
if (base64_decode($policy['page']['DENY'])  == "TRUE")
	$print['page_base_deny_check'] = "checked";

$error_msg['modify_confirm'] = "수정하시겠습니까?";
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
      function page_move()
      {
        location.href = 'castle_admin_advance.php#<?php echo $print['return_pos']?>';
      }

      function page_view2(url) 
      {
        window.open(url, "page_view");
      }

      function var_modify_confirm()
      {
        ret = confirm("<?php echo $error_msg['modify_confirm']?>");
        return ret;
      }

      function var_delete_submit(var_name)
      {
        ret = confirm("<?php echo $error_msg['delete_confirm']?>");
        if (!ret)
          return;
        location.href = 'castle_admin_advance_submit.php?mode=VAR_DELETE&page_name=<?php echo $print['page_name']?>&var_name='+var_name;  
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
                                  <font color="#C0C0C0"><li><b>페이지 고급설정</b> - 페이지별 세부 설정을 합니다.</font>
                                </td>
                                <td width="8"><img src="img/menu_top_rt.gif"></td>
                              </tr>
                            </table>
                            <table width="100%" cellspacing="10" cellpadding="0" border="0">
                              <tr>
                                <td width="100%" style="line-height:160%" nowrap>
                                  <li><b>알림: 페이지별 세부 설정을 합니다.</b><br>
                                </td>
                              </tr>
                            </table>
                            <table width="800" height="25" cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td width="2"></td>
                                <td width=""><input type="button" value="목록으로 돌아가기" onclick="page_move()" class="submit"></td>
                                </td>
                                <td width="2"></td>
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
                                <td height="100%" bgcolor="#B0B0B0" align="center">
                                  <b><font color="#FFFFFF">페이지 관리 설정</font></b>
                                </td>
                                <td width="2"></td>
                              </tr>
                            </table>
                            <table width="800" height="1">
                              <tr>
                                <td width="100%" height="100%" background="img/line_bg.gif"></td>
                              </tr>
                            </table>
                            <table width="800" cellspacing="2" cellpadding="5" border="0">
                              <form name="page_modify_form" action="castle_admin_advance_submit.php?mode=PAGE_MODIFY_DETAIL" method="post">
                              <tr>
                                <th height="30" rowspan="4" bgcolor="#D8D8D8" align="right">페이지 관리</th>
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
                                  <a href="" onclick="history.back(-1); return false;"><img src="img/button_cancel.gif" border="0"></a>
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
                                <td width="80" bgcolor="#B0B0B0">&nbsp;<a href="castle_admin_advance_detail.php?page_name=<?php echo $print['page_name']?>&mode=INSERT#insert_form"><img src="img/button_plus.gif" border="0"></a></td>
                                <td height="100%" bgcolor="#B0B0B0" align="center">
                                  <b><font color="#FFFFFF">변수 관리 설정</font></b>
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
                            <table width="800" cellspacing="2" cellpadding="0" border="0">
                              <tr height="25">
                                <th width="30" rowspan="2" bgcolor="#D8D8D8"><font color="#606060">번호</font></th>
                                <th width="320" colspan="2" bgcolor="#D8D8D8"><font color="#606060">변수이름</font></th>
                                <th width="100" bgcolor="#D8D8D8"><font color="#606060">메소드</font></th>
                                <th width="250" bgcolor="#D8D8D8"><font color="#606060">검사대상</font></th>
                                <th width="50" rowspan="2" bgcolor="#D8D8D8"><font color="#606060">수정</font></th>
                                <th width="50" rowspan="2" bgcolor="#D8D8D8"><font color="#606060">삭제</font></th>
                              </tr>
                              <tr height="25">
                                <th colspan="2" bgcolor="#D8D8D8"><font color="#606060">변수형태(정규표현식)</font></th>
                                <th colspan="2" bgcolor="#D8D8D8"><font color="#606060">최소/최대 길이</font></th>
                              </tr>
                              <tr>
                                <td width="100%" height="1" colspan="6" background="img/line_bg.gif"></td>
                              </tr>
<?php
/* 변수 목록 출력 시작 */

	// 변수 목록 가져오기
	$print['var_count'] = 0;

	if (isset($policy['page']['LIST'])) {
		$policy['var_list'] = $policy['page']['LIST'];

		$print['var_count'] = count($policy['var_list']);
		$print['var_num'] = 1;

		foreach ($policy['var_list'] as $key => $value)
		{
			if ($value == "")
				continue;

			$policy['var'] = $policy['var_list'][$key];

			$print['var_name'] = $key;
			$print['var_format'] = "";
			$print['var_min_length'] = "";
			$print['var_max_length'] = "";
			$print['var_method_get_check'] = "";
			$print['var_method_post_check'] = "";
			$print['var_target_sql_injection_check'] = "";
			$print['var_target_xss_check'] = "";
			$print['var_target_word_check'] = "";
			$print['var_target_tag_check'] = "";

			if (isset($policy['var']['FORMAT']))
				$print['var_format'] = base64_decode($policy['var']['FORMAT']);

			if (isset($policy['var']['MAX_LENGTH']))
				$print['var_min_length'] = base64_decode($policy['var']['MIN_LENGTH']);

			if (isset($policy['var']['MIN_LENGTH']))
				$print['var_max_length'] = base64_decode($policy['var']['MAX_LENGTH']);

			$print['var_name'] = htmlentities($print['var_name'], ENT_QUOTES, "UTF-8");
			$print['var_format'] = htmlentities($print['var_format'], ENT_QUOTES, "UTF-8");
			$print['var_max_length'] = htmlentities($print['var_max_length'], ENT_QUOTES, "UTF-8");
			$print['var_min_length'] = htmlentities($print['var_min_length'], ENT_QUOTES, "UTF-8");
				
			if (isset($policy['var']['METHOD']['GET']) && base64_decode($policy['var']['METHOD']['GET']) == "TRUE")
				$print['var_method_get_check'] = "checked"; 

			if (isset($policy['var']['METHOD']['POST']) && base64_decode($policy['var']['METHOD']['POST']) == "TRUE")
				$print['var_method_post_check'] = "checked"; 

			if (isset($policy['var']['TARGET']['SQL_INJECTION']) && base64_decode($policy['var']['TARGET']['SQL_INJECTION']) == "TRUE")
				$print['var_target_sql_injection_check'] = "checked";  

			if (isset($policy['var']['TARGET']['XSS']) && base64_decode($policy['var']['TARGET']['XSS']) == "TRUE")
				$print['var_target_xss_check'] = "checked"; 

			if (isset($policy['var']['TARGET']['WORD']) && base64_decode($policy['var']['TARGET']['WORD']) == "TRUE")
				$print['var_target_word_check'] = "checked"; 

			if (isset($policy['var']['TARGET']['TAG']) && base64_decode($policy['var']['TARGET']['TAG']) == "TRUE")
				$print['var_target_tag_check'] = "checked"; 

?>
                            <form name="var_modify_form" action="castle_admin_advance_submit.php?mode=VAR_MODIFY" method="post">
                              <input type="hidden" name="page_name" value="<?php echo $print['page_name']?>">
                              <input type="hidden" name="var_name" value="<?php echo $print['var_name']?>">
                              <tr height="25" bgcolor="#FFFFFF">
                                <td rowspan="2" align="center">
                                  <a name="<?php echo $print['var_num']?>"></a><?php echo $print['var_num']?>
                                </td>
                                <td width="70" align="right"><img src="img/name.gif"></td>
                                <td>&nbsp;<font color="#606060"><?php echo $print['var_name']?></font>
                                </td>
                                <td align="center">
                                  <input type="checkbox" name="var_method_get" <?php echo $print['var_method_get_check']?>><img src="img/get.gif">
                                  <input type="checkbox" name="var_method_post" <?php echo $print['var_method_post_check']?>><img src="img/post.gif">
                                </td>
                                <td align="center">
                                  <input type="checkbox" name="var_target_sql_injection" <?php echo $print['var_target_sql_injection_check']?>><img src="img/sql_injection.gif">
                                  <input type="checkbox" name="var_target_xss" <?php echo $print['var_target_xss_check']?>><img src="img/xss.gif">
                                  <input type="checkbox" name="var_target_word" <?php echo $print['var_target_word_check']?>><img src="img/word.gif">
                                  <input type="checkbox" name="var_target_tag" <?php echo $print['var_target_tag_check']?>><img src="img/tag.gif">
                                </td>
                                <td rowspan="2" align="center"><input type="image" src="img/button_modify.jpg" onclick="ret = var_modify_confirm(); if (!ret) return false; else submit();"></td>
                                <td rowspan="2" align="center"><input type="image" src="img/button_delete.jpg" onclick="var_delete_submit('<?php echo $print['var_name']?>'); return false;"></td>
                              </tr>
                              <tr height="30" bgcolor="#FFFFFF">
                                <td width="70" align="right"><img src="img/format.gif"></td>
                                <td>&nbsp;<input type="text" name="var_format" size="36" value="<?php echo $print['var_format']?>">
                                </td>
                                <td colspan="2" bgcolor="#FFFFFF" align="center">
                                  <img src="img/minlength.gif"> <input type="text" name="var_min_length" size="15" value="<?php echo $print['var_min_length']?>"> &nbsp;
                                  <img src="img/maxlength.gif"> <input type="text" name="var_max_length" size="15" value="<?php echo $print['var_max_length']?>">
                                </td>
                              </tr>
                            </form>
<?php
			$print['var_num']++;
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
                                <td width="100%" height="100%" align="center">총 <b><?php echo $print['var_count']?></b> 개가 설정되었습니다.</td>
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
                            <table width="800" cellspacing="2" cellpadding="0" border="0">
                            <form name="var_modify_form" action="castle_admin_advance_submit.php?mode=VAR_INSERT" method="post">
                              <input type="hidden" name="page_name" value="<?php echo $print['page_name']?>">
                              <tr height="25" bgcolor="#FFFFFF">
                                <td width="105" align="right"><img src="img/name.gif">&nbsp;</td>
                                <td>&nbsp;<input type="text" name="var_name" size="40"></td>
                                <td align="center">
                                  <input type="checkbox" name="var_method_get" checked><img src="img/get.gif">
                                  <input type="checkbox" name="var_method_post" checked><img src="img/post.gif">
                                </td>
                                <td align="center">
                                  <input type="checkbox" name="var_target_sql_injection"><img src="img/sql_injection.gif">
                                  <input type="checkbox" name="var_target_xss"><img src="img/xss.gif">
                                  <input type="checkbox" name="var_target_word"><img src="img/word.gif">
                                  <input type="checkbox" name="var_target_tag"><img src="img/tag.gif">
                                </td>
                              </tr>
                              <tr height="30" bgcolor="#FFFFFF">
                                <td align="right"><img src="img/format.gif">&nbsp;</td>
                                <td>&nbsp;<input type="text" name="var_format" size="40">
                                </td>
                                <td colspan="2" bgcolor="#FFFFFF" align="center">
                                  <img src="img/minlength.gif"> <input type="text" name="var_min_length" size="17" value="0"> &nbsp;
                                  <img src="img/maxlength.gif"> <input type="text" name="var_max_length" size="17" value="<?php echo MAX_VAR_LENGTH?>">
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
