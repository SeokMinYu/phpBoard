<?php
if (!defined('_GNUBOARD_')) exit;

include_once(dirname(__FILE__) .'/pbkdf2.compat.php');

/*************************************************************************
**
**  �Ϲ� �Լ� ����
**
*************************************************************************/

// ����ũ�� Ÿ���� ��� ��� �������� ����
function get_microtime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}


// ���������� ������ ��, ����������, ����������, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#(&amp;)?page=[0-9]*#', '', $url);
	$url .= substr($url, -1) === '?' ? 'page=' : '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">ó��</a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">����</a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only">������</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">����</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">������</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">����</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">�ǳ�</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

// ����¡ �ڵ��� <nav><span> �±� ������ �ڵ带 ����
function page_insertbefore($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    return preg_replace("/^(<nav[^>]+><span[^>]+>)/", '$1'.$insert_html.PHP_EOL, $paging_html);
}

// ����¡ �ڵ��� </span></nav> �±� ������ �ڵ带 ����
function page_insertafter($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    if(preg_match("#".PHP_EOL."</span></nav>#", $paging_html))
        $php_eol = '';
    else
        $php_eol = PHP_EOL;

    return preg_replace("#(</span></nav>)$#", $php_eol.$insert_html.'$1', $paging_html);
}

// ���� �Ǵ� �迭�� �̸��� ���� ��. print_r() �Լ��� ����
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, ����; font-size:9pt;'>$str</span>");
}


// ��Ÿ�±׸� �̿��� URL �̵�
// header("location:URL") �� ��ü
function goto_url($url)
{
    $url = str_replace("&amp;", "&", $url);
    //echo "<script> location.replace('$url'); </script>";

    if (!headers_sent())
        header('Location: '.$url);
    else {
        echo '<script>';
        echo 'location.replace("'.$url.'");';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
    }
    exit;
}


// ���Ǻ��� ����
function set_session($session_name, $value)
{
	global $g5;

	static $check_cookie = null;
	
	if( $check_cookie === null ){
		$cookie_session_name = session_name();
		if( ! isset($g5['session_cookie_samesite']) && ! ($cookie_session_name && isset($_COOKIE[$cookie_session_name]) && $_COOKIE[$cookie_session_name]) && ! headers_sent() ){
			@session_regenerate_id(false);
		}

		$check_cookie = 1;
	}

    if (PHP_VERSION < '5.3.0')
        session_register($session_name);
    // PHP ������ ���̸� ���ֱ� ���� ���
    $$session_name = $_SESSION[$session_name] = $value;
}


// ���Ǻ����� ����
function get_session($session_name)
{
    return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
}


// ��Ű���� ����
function set_cookie($cookie_name, $value, $expire)
{
    global $g5;

    setcookie(md5($cookie_name), base64_encode($value), G5_SERVER_TIME + $expire, '/', G5_COOKIE_DOMAIN);
}


// ��Ű������ ����
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return "";
}


// ���޼����� ���â����
function alert($msg='', $url='', $error=true, $post=false)
{
    global $g5, $config, $member, $is_member, $is_admin, $board;

    run_event('alert', $msg, $url, $error, $post);

    $msg = $msg ? strip_tags($msg, '<br>') : '�ùٸ� ������� �̿��� �ֽʽÿ�.';

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/alert.php');
    exit;
}


// ���޼��� ����� â�� ����
function alert_close($msg, $error=true)
{
    global $g5, $config, $member, $is_member, $is_admin, $board;
    
    run_event('alert_close', $msg, $error);

    $msg = strip_tags($msg, '<br>');

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/alert_close.php');
    exit;
}

// confirm â
function confirm($msg, $url1='', $url2='', $url3='')
{
    global $g5, $config, $member, $is_member, $is_admin, $board;

    if (!$msg) {
        $msg = '�ùٸ� ������� �̿��� �ֽʽÿ�.';
        alert($msg);
    }

    if(!trim($url1) || !trim($url2)) {
        $msg = '$url1 �� $url2 �� ������ �ּ���.';
        alert($msg);
    }

    if (!$url3) $url3 = clean_xss_tags($_SERVER['HTTP_REFERER']);

    $msg = str_replace("\\n", "<br>", $msg);

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/confirm.php');
    exit;
}


// way.co.kr �� wayboard ����
function url_auto_link($str)
{
    global $g5;
    global $config;

    // 140326 ��âȭ�� �����ڵ�� ����
    // http://sir.kr/pg_lecture/461
    // http://sir.kr/pg_lecture/463
    $attr_nofollow = (function_exists('check_html_link_nofollow') && check_html_link_nofollow('url_auto_link')) ? ' rel="nofollow"' : '';
    $str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"), $str);
    //$str = preg_replace("`(?:(?:(?:href|src)\s*=\s*(?:\"|'|)){0})((http|https|ftp|telnet|news|mms)://[^\"'\s()]+)`", "<A HREF=\"\\1\" TARGET='{$config['cf_link_target']}'>\\1</A>", $str);
    $str = preg_replace("/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[��-�R\xA1-\xFEa-zA-Z0-9\.:&#!=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET=\"{$config['cf_link_target']}\" $attr_nofollow>\\2</A>", $str);
    $str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET=\"{$config['cf_link_target']}\" $attr_nofollow>\\2</A>", $str);
    $str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href=\"mailto:\\0\" $attr_nofollow>\\0</a>", $str);
    $str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"), array("&nbsp;", "&lt;", "&gt;", "&#039;"), $str);

    /*
    // �ӵ� ��� 031011
    $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
    $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
    $str = preg_replace("/&amp;/", "&", $str);
    $str = preg_replace("/&quot;/", "\"", $str);
    $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
    $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='{$config['cf_link_target']}'>\\2</A>", $str);
    //$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config['cf_link_target']'>\\2</A>", $str);
    // 100825 : () �߰�
    // 120315 : CHARSET �� ���� ��ũ�� ���� �߸� ������ �־� ����
    $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[��-�R\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='{$config['cf_link_target']}'>\\2</A>", $str);

    // �̸��� ����ǥ���� ���� 061004
    //$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
    $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
    $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
    $str = preg_replace("/\t_gt_\t/", "&gt;", $str);
    */

    return run_replace('url_auto_link', $str);
}


// url�� http:// �� ���δ�
function set_http($url)
{
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
        $url = "http://" . $url;

    return $url;
}


// ������ �뷮�� ���Ѵ�.
//function get_filesize($file)
function get_filesize($size)
{
    //$size = @filesize(addslashes($file));
    if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}


// �Խñۿ� ÷�ε� ������ ��´�. (�迭�� ��ȯ)
function get_file($bo_table, $wr_id)
{
    global $g5, $qstr, $board;

    $file['count'] = 0;
    $sql = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' order by bf_no ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $no = (int) $row['bf_no'];
        $bf_content = $row['bf_content'] ? html_purifier($row['bf_content']) : '';
        $file[$no]['href'] = G5_BBS_URL."/download.php?bo_table=$bo_table&amp;wr_id=$wr_id&amp;no=$no" . $qstr;
        $file[$no]['download'] = $row['bf_download'];
        // 4.00.11 - ���� path �߰�
        $file[$no]['path'] = G5_DATA_URL.'/file/'.$bo_table;
        $file[$no]['size'] = get_filesize($row['bf_filesize']);
        $file[$no]['datetime'] = $row['bf_datetime'];
        $file[$no]['source'] = addslashes($row['bf_source']);
        $file[$no]['bf_content'] = $bf_content;
        $file[$no]['content'] = get_text($bf_content);
        //$file[$no]['view'] = view_file_link($row['bf_file'], $file[$no]['content']);
        $file[$no]['view'] = view_file_link($row['bf_file'], $row['bf_width'], $row['bf_height'], $file[$no]['content']);
        $file[$no]['file'] = $row['bf_file'];
        $file[$no]['image_width'] = $row['bf_width'] ? $row['bf_width'] : 640;
        $file[$no]['image_height'] = $row['bf_height'] ? $row['bf_height'] : 480;
        $file[$no]['image_type'] = $row['bf_type'];
        $file[$no]['bf_fileurl'] = $row['bf_fileurl'];
        $file[$no]['bf_thumburl'] = $row['bf_thumburl'];
        $file[$no]['bf_storage'] = $row['bf_storage'];
        $file['count']++;
    }

    return run_replace('get_files', $file, $bo_table, $wr_id);
}


// ������ �뷮 ($dir�� / ���� �ѱ⼼��)
function get_dirsize($dir)
{
    $size = 0;
    $d = dir($dir);
    while ($entry = $d->read()) {
        if ($entry != '.' && $entry != '..') {
            $size += filesize($dir.'/'.$entry);
        }
    }
    $d->close();
    return $size;
}


/*************************************************************************
**
**  �״����� ���� �Լ� ����
**
*************************************************************************/


// �Խù� ����($write_row)�� ����ϱ� ���Ͽ� $list�� ������ ������ ���� �� ����
function get_list($write_row, $board, $skin_url, $subject_len=40)
{
    global $g5, $config, $g5_object;
    global $qstr, $page;

    //$t = get_microtime();

    $g5_object->set('bbs', $write_row['wr_id'], $write_row, $board['bo_table']);

    // �迭��ü�� ����
    $list = $write_row;
    unset($write_row);

    $board_notice = array_map('trim', explode(',', $board['bo_notice']));
    $list['is_notice'] = in_array($list['wr_id'], $board_notice);

    if ($subject_len)
        $list['subject'] = conv_subject($list['wr_subject'], $subject_len, '��');
    else
        $list['subject'] = conv_subject($list['wr_subject'], $board['bo_subject_len'], '��');

    if( ! (isset($list['wr_seo_title']) && $list['wr_seo_title']) && $list['wr_id'] ){
        seo_title_update(get_write_table_name($board['bo_table']), $list['wr_id'], 'bbs');
    }

    // ��Ͽ��� ���� �̸����� ����� �Խ��Ǹ� ������ ��ȯ�� (�ӵ� ���) : kkal3(Ŀ��)�Բ��� �˷��ּ̽��ϴ�.
    if ($board['bo_use_list_content'])
	{
		$html = 0;
		if (strstr($list['wr_option'], 'html1'))
			$html = 1;
		else if (strstr($list['wr_option'], 'html2'))
			$html = 2;

        $list['content'] = conv_content($list['wr_content'], $html);
	}

    $list['comment_cnt'] = '';
    if ($list['wr_comment'])
        $list['comment_cnt'] = "<span class=\"cnt_cmt\">".$list['wr_comment']."</span>";

    // ������ ��� �ð����� ǥ����
    $list['datetime'] = substr($list['wr_datetime'],0,10);
    $list['datetime2'] = $list['wr_datetime'];
    if ($list['datetime'] == G5_TIME_YMD)
        $list['datetime2'] = substr($list['datetime2'],11,5);
    else
        $list['datetime2'] = substr($list['datetime2'],5,5);
    // 4.1
    $list['last'] = substr($list['wr_last'],0,10);
    $list['last2'] = $list['wr_last'];
    if ($list['last'] == G5_TIME_YMD)
        $list['last2'] = substr($list['last2'],11,5);
    else
        $list['last2'] = substr($list['last2'],5,5);

    $list['wr_homepage'] = get_text($list['wr_homepage']);

    $tmp_name = get_text(cut_str($list['wr_name'], $config['cf_cut_name'])); // ������ �ڸ��� ��ŭ�� �̸� ���
    $tmp_name2 = cut_str($list['wr_name'], $config['cf_cut_name']); // ������ �ڸ��� ��ŭ�� �̸� ���
    if ($board['bo_use_sideview'])
        $list['name'] = get_sideview($list['mb_id'], $tmp_name2, $list['wr_email'], $list['wr_homepage']);
    else
        $list['name'] = '<span class="'.($list['mb_id']?'sv_member':'sv_guest').'">'.$tmp_name.'</span>';

    $reply = $list['wr_reply'];

    $list['reply'] = strlen($reply)*20;

    $list['icon_reply'] = '';
    if ($list['reply'])
        $list['icon_reply'] = '<img src="'.$skin_url.'/img/icon_reply.gif" class="icon_reply" alt="�亯��">';

    $list['icon_link'] = '';
    if ($list['wr_link1'] || $list['wr_link2'])
        $list['icon_link'] = '<i class="fa fa-link" aria-hidden="true"></i> ';

    // �з��� ��ũ
    $list['ca_name_href'] = get_pretty_url($board['bo_table'], '', 'sca='.urlencode($list['ca_name']));

    $list['href'] = get_pretty_url($board['bo_table'], $list['wr_id'], $qstr);
    $list['comment_href'] = $list['href'];

    $list['icon_new'] = '';
    if ($board['bo_new'] && $list['wr_datetime'] >= date("Y-m-d H:i:s", G5_SERVER_TIME - ($board['bo_new'] * 3600)))
        $list['icon_new'] = '<img src="'.$skin_url.'/img/icon_new.gif" class="title_icon" alt="����"> ';

    $list['icon_hot'] = '';
    if ($board['bo_hot'] && $list['wr_hit'] >= $board['bo_hot'])
        $list['icon_hot'] = '<i class="fa fa-heart" aria-hidden="true"></i> ';

    $list['icon_secret'] = '';
    if (strstr($list['wr_option'], 'secret'))
        $list['icon_secret'] = '<i class="fa fa-lock" aria-hidden="true"></i> ';

    // ��ũ
    for ($i=1; $i<=G5_LINK_COUNT; $i++) {
        $list['link'][$i] = set_http(get_text($list["wr_link{$i}"]));
        $list['link_href'][$i] = G5_BBS_URL.'/link.php?bo_table='.$board['bo_table'].'&amp;wr_id='.$list['wr_id'].'&amp;no='.$i.$qstr;
        $list['link_hit'][$i] = (int)$list["wr_link{$i}_hit"];
    }

    // ���� ����
    if ($board['bo_use_list_file'] || ($list['wr_file'] && $subject_len == 255) /* view �� ��� */) {
        $list['file'] = get_file($board['bo_table'], $list['wr_id']);
    } else {
        $list['file']['count'] = $list['wr_file'];
    }

    if ($list['file']['count'])
        $list['icon_file'] = '<i class="fa fa-download" aria-hidden="true"></i> ';

    return $list;
}

// get_list �� alias
function get_view($write_row, $board, $skin_url)
{
    return get_list($write_row, $board, $skin_url, 255);
}


// set_search_font(), get_search_font() �Լ��� search_font() �Լ��� ��ü
function search_font($stx, $str)
{
    global $config;

    // ���ھտ� \ �� ���Դϴ�.
    $src = array('/', '|');
    $dst = array('\/', '\|');

    if (!trim($stx) && $stx !== '0') return $str;

    // �˻��� ��ü�� �������� ������
    $s = explode(' ', $stx);

    // "/(�˻�1|�˻�2)/i" �� ���� ������ ����
    $pattern = '';
    $bar = '';
    for ($m=0; $m<count($s); $m++) {
        if (trim($s[$m]) == '') continue;
        // �±״� �������� �ʾƾ� �ϴµ� �� �ȵǴ±�. �Ѥ�a
        //$pattern .= $bar . '([^<])(' . quotemeta($s[$m]) . ')';
        //$pattern .= $bar . quotemeta($s[$m]);
        //$pattern .= $bar . str_replace("/", "\/", quotemeta($s[$m]));
        $tmp_str = quotemeta($s[$m]);
        $tmp_str = str_replace($src, $dst, $tmp_str);
        $pattern .= $bar . $tmp_str . "(?![^<]*>)";
        $bar = "|";
    }

    // ������ �˻� ��Ʈ�� ����, ���������� ��ü
    $replace = "<b class=\"sch_word\">\\1</b>";

    return preg_replace("/($pattern)/i", $replace, $str);
}


// ������ ��ȯ
function conv_subject($subject, $len, $suffix='')
{
    return get_text(cut_str($subject, $len, $suffix));
}

// ������ ��ȯ
function conv_content($content, $html, $filter=true)
{
    global $config, $board;

    if ($html)
    {
        $source = array();
        $target = array();

        $source[] = "//";
        $target[] = "";

        if ($html == 2) { // �ڵ� �ٹٲ�
            $source[] = "/\n/";
            $target[] = "<br/>";
        }

        // ���̺� �±��� ������ ���� ���̺��� ������ �ʵ��� �Ѵ�.
        $table_begin_count = substr_count(strtolower($content), "<table");
        $table_end_count = substr_count(strtolower($content), "</table");
        for ($i=$table_end_count; $i<$table_begin_count; $i++)
        {
            $content .= "</table>";
        }

        $content = preg_replace($source, $target, $content);

        if($filter)
            $content = html_purifier($content);
    }
    else // text �̸�
    {
        // & ó�� : &amp; &nbsp; ���� �ڵ带 ���� �����
        $content = html_symbol($content);

        // ���� ó��
		//$content = preg_replace("/  /", "&nbsp; ", $content);
		$content = str_replace("  ", "&nbsp; ", $content);
		$content = str_replace("\n ", "\n&nbsp;", $content);

        $content = get_text($content, 1);
        $content = url_auto_link($content);
    }

    return $content;
}

function check_html_link_nofollow($type=''){
    return true;
}

// http://htmlpurifier.org/
// Standards-Compliant HTML Filtering
// Safe  : HTML Purifier defeats XSS with an audited whitelist
// Clean : HTML Purifier ensures standards-compliant output
// Open  : HTML Purifier is open-source and highly customizable
function html_purifier($html)
{
    $f = file(G5_PLUGIN_PATH.'/htmlpurifier/safeiframe.txt');
    $domains = array();
    foreach($f as $domain){
        // ù���� # �̸� �ּ� ó��
        if (!preg_match("/^#/", $domain)) {
            $domain = trim($domain);
            if ($domain)
                array_push($domains, $domain);
        }
    }
    // �� �����ε� �߰�
    array_push($domains, $_SERVER['HTTP_HOST'].'/');
    $safeiframe = implode('|', $domains);

    include_once(G5_PLUGIN_PATH.'/htmlpurifier/HTMLPurifier.standalone.php');
    include_once(G5_PLUGIN_PATH.'/htmlpurifier/extend.video.php');
    $config = HTMLPurifier_Config::createDefault();
    // data/cache ���丮�� CSS, HTML, URI ���丮 ���� �����.
    $config->set('Cache.SerializerPath', G5_DATA_PATH.'/cache');
    $config->set('HTML.SafeEmbed', false);
    $config->set('HTML.SafeObject', false);
    $config->set('Output.FlashCompat', false);
    $config->set('HTML.SafeIframe', true);
    if( (function_exists('check_html_link_nofollow') && check_html_link_nofollow('html_purifier')) ){
        $config->set('HTML.Nofollow', true);    // rel=nofollow ���� ���������� ����
    }
    $config->set('URI.SafeIframeRegexp','%^(https?:)?//('.$safeiframe.')%');
    $config->set('Attr.AllowedFrameTargets', array('_blank'));
    //��Ʃ��, ��޿� ��üȭ�� �����ϰ� �ϱ�
    $config->set('Filter.Custom', array(new HTMLPurifier_Filter_Iframevideo()));
    $purifier = new HTMLPurifier($config);
    return run_replace('html_purifier_result', $purifier->purify($html), $purifier, $html);
}


// �˻� ������ ��´�.
function get_sql_search($search_ca_name, $search_field, $search_text, $search_operator='and')
{
    global $g5;

    $str = "";
    if ($search_ca_name)
        $str = " ca_name = '$search_ca_name' ";

    $search_text = strip_tags(($search_text));
    $search_text = trim(stripslashes($search_text));

    if (!$search_text && $search_text !== '0') {
        if ($search_ca_name) {
            return $str;
        } else {
            return '0';
        }
    }

    if ($str)
        $str .= " and ";

    // ������ �ӵ��� ���̱� ���Ͽ� ( ) �� �ּ�ȭ �Ѵ�.
    $op1 = "";

    // �˻�� �����ڷ� ������. ���⼭�� ����
    $s = array();
    $s = explode(" ", $search_text);

    // �˻��ʵ带 �����ڷ� ������. ���⼭�� +
    $tmp = array();
    $tmp = explode(",", trim($search_field));
    $field = explode("||", $tmp[0]);
    $not_comment = "";
    if (!empty($tmp[1]))
        $not_comment = $tmp[1];

    $str .= "(";
    for ($i=0; $i<count($s); $i++) {
        // �˻���
        $search_str = trim($s[$i]);
        if ($search_str == "") continue;

        // �α�˻���
        insert_popular($field, $search_str);

        $str .= $op1;
        $str .= "(";

        $op2 = "";
        for ($k=0; $k<count($field); $k++) { // �ʵ��� ����ŭ ���� �ʵ� �˻� ���� (�ʵ�1+�ʵ�2...)

            // SQL Injection ����
            // �ʵ尪�� a-z A-Z 0-9 _ , | �̿��� ���� �ִٸ� �˻��ʵ带 wr_subject �� �����Ѵ�.
            $field[$k] = preg_match("/^[\w\,\|]+$/", $field[$k]) ? strtolower($field[$k]) : "wr_subject";

            $str .= $op2;
            switch ($field[$k]) {
                case "mb_id" :
                case "wr_name" :
                    $str .= " $field[$k] = '$s[$i]' ";
                    break;
                case "wr_hit" :
                case "wr_good" :
                case "wr_nogood" :
                    $str .= " $field[$k] >= '$s[$i]' ";
                    break;
                // ��ȣ�� �ش� �˻�� -1 �� ����
                case "wr_num" :
                    $str .= "$field[$k] = ".((-1)*$s[$i]);
                    break;
                case "wr_ip" :
                case "wr_password" :
                    $str .= "1=0"; // �׻� ����
                    break;
                // LIKE ���� INSTR �ӵ��� ����
                default :
                    if (preg_match("/[a-zA-Z]/", $search_str))
                        $str .= "INSTR(LOWER($field[$k]), LOWER('$search_str'))";
                    else
                        $str .= "INSTR($field[$k], '$search_str')";
                    break;
            }
            $op2 = " or ";
        }
        $str .= ")";

        $op1 = " $search_operator ";
    }
    $str .= " ) ";
    if ($not_comment)
        $str .= " and wr_is_comment = '0' ";

    return $str;
}

// �Խ��� ���̺��� �ϳ��� ���� ����
function get_write($write_table, $wr_id, $is_cache=false)
{
    global $g5, $g5_object;

    $wr_bo_table = preg_replace('/^'.preg_quote($g5['write_prefix']).'/i', '', $write_table);

    $write = $g5_object->get('bbs', $wr_id, $wr_bo_table);

    if( !$write || $is_cache == false ){
        $sql = " select * from {$write_table} where wr_id = '{$wr_id}' ";
        $write = sql_fetch($sql);

        $g5_object->set('bbs', $wr_id, $write, $wr_bo_table);
    }

    return $write;
}

// �Խ����� ������ ��ȣ�� ��´�.
function get_next_num($table)
{
    // ���� ���� ��ȣ�� ���
    $sql = " select min(wr_num) as min_wr_num from $table ";
    $row = sql_fetch($sql);
    // ���� ���� ��ȣ�� 1�� ���� �Ѱ���
    return (int)($row['min_wr_num'] - 1);
}


// �׷� ���� ���̺��� �ϳ��� ���� ����
function get_group($gr_id, $is_cache=false)
{
    global $g5;
    
    if( is_array($gr_id) ){
        return array();
    }

    static $cache = array();

    $gr_id = preg_replace('/[^a-z0-9_]/i', '', $gr_id);
    $cache = run_replace('get_group_db_cache', $cache, $gr_id, $is_cache);
    $key = md5($gr_id);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    $sql = " select * from {$g5['group_table']} where gr_id = '$gr_id' ";

    $group = run_replace('get_group', sql_fetch($sql), $gr_id, $is_cache);
    $cache[$key] = array_merge(array('gr_device'=>'', 'gr_subject'=>''), (array) $group);

    return $cache[$key];
}


// ȸ�� ������ ��´�.
function get_member($mb_id, $fields='*', $is_cache=false)
{
    global $g5;
    
    if (preg_match("/[^0-9a-z_]+/i", $mb_id))
        return array();

    static $cache = array();

    $key = md5($fields);

    if( $is_cache && isset($cache[$mb_id]) && isset($cache[$mb_id][$key]) ){
        return $cache[$mb_id][$key];
    }

    $sql = " select $fields from {$g5['member_table']} where mb_id = TRIM('$mb_id') ";

    $cache[$mb_id][$key] = run_replace('get_member', sql_fetch($sql), $mb_id, $fields, $is_cache);

    return $cache[$mb_id][$key];
}


// ��¥, ��ȸ���� ��� ���� ������� �������� �ϹǷ� $flag �� �߰�
// $flag : asc ���� ���� , desc ���� ����
// ���񺰷� �÷� �����ϴ� QUERY STRING
function subject_sort_link($col, $query_string='', $flag='asc')
{
    global $sst, $sod, $sfl, $stx, $page, $sca;

    $q1 = "sst=$col";
    if ($flag == 'asc')
    {
        $q2 = 'sod=asc';
        if ($sst == $col)
        {
            if ($sod == 'asc')
            {
                $q2 = 'sod=desc';
            }
        }
    }
    else
    {
        $q2 = 'sod=desc';
        if ($sst == $col)
        {
            if ($sod == 'desc')
            {
                $q2 = 'sod=asc';
            }
        }
    }

    $arr_query = array();
    $arr_query[] = $query_string;
    $arr_query[] = $q1;
    $arr_query[] = $q2;
    $arr_query[] = 'sfl='.$sfl;
    $arr_query[] = 'stx='.$stx;
    $arr_query[] = 'sca='.$sca;
    $arr_query[] = 'page='.$page;
    $qstr = implode("&amp;", $arr_query);

    return "<a href=\"{$_SERVER['SCRIPT_NAME']}?{$qstr}\">";
}


// ������ ������ ����
function get_admin($admin='super', $fields='*')
{
    global $config, $group, $board;
    global $g5;

    $is = false;
    if ($admin == 'board') {
        $mb = sql_fetch("select {$fields} from {$g5['member_table']} where mb_id in ('{$board['bo_admin']}') limit 1 ");
        $is = true;
    }

    if (($is && !$mb['mb_id']) || $admin == 'group') {
        $mb = sql_fetch("select {$fields} from {$g5['member_table']} where mb_id in ('{$group['gr_admin']}') limit 1 ");
        $is = true;
    }

    if (($is && !$mb['mb_id']) || $admin == 'super') {
        $mb = sql_fetch("select {$fields} from {$g5['member_table']} where mb_id in ('{$config['cf_admin']}') limit 1 ");
    }

    return $mb;
}


// �������ΰ�?
function is_admin($mb_id)
{
    global $config, $group, $board;

    if (!$mb_id) return '';

    $is_authority = '';

    if ($config['cf_admin'] == $mb_id){
        $is_authority = 'super';
    } else if (isset($group['gr_admin']) && ($group['gr_admin'] == $mb_id)){
        $is_authority = 'group';
    } else if (isset($board['bo_admin']) && ($board['bo_admin'] == $mb_id)){
        $is_authority = 'board';
    }

    return run_replace('is_admin', $is_authority, $mb_id);
}


// �з� �ɼ��� ����
// 4.00 ������ ī�װ� ���̺��� ���ְ� �������̺� �ִ� �������� ��ü
function get_category_option($bo_table='', $ca_name='')
{
    global $g5, $board, $is_admin;

    $categories = explode("|", $board['bo_category_list'].($is_admin?"|����":"")); // �����ڰ� | �� �Ǿ� ����
    $str = "";
    for ($i=0; $i<count($categories); $i++) {
        $category = trim($categories[$i]);
        if (!$category) continue;

        $str .= "<option value=\"$categories[$i]\"";
        if ($category == $ca_name) {
            $str .= ' selected="selected"';
        }
        $str .= ">$categories[$i]</option>\n";
    }

    return $str;
}


// �Խ��� �׷��� SELECT �������� ����
function get_group_select($name, $selected='', $event='')
{
    global $g5, $is_admin, $member;

    $sql = " select gr_id, gr_subject from {$g5['group_table']} a ";
    if ($is_admin == "group") {
        $sql .= " left join {$g5['member_table']} b on (b.mb_id = a.gr_admin)
                  where b.mb_id = '{$member['mb_id']}' ";
    }
    $sql .= " order by a.gr_id ";

    $result = sql_query($sql);
    $str = "<select id=\"$name\" name=\"$name\" $event>\n";
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i == 0) $str .= "<option value=\"\">����</option>";
        $str .= option_selected($row['gr_id'], $selected, $row['gr_subject']);
    }
    $str .= "</select>";
    return $str;
}


function option_selected($value, $selected, $text='')
{
    if (!$text) $text = $value;
    if ($value == $selected)
        return "<option value=\"$value\" selected=\"selected\">$text</option>\n";
    else
        return "<option value=\"$value\">$text</option>\n";
}


// '��', '�ƴϿ�'�� SELECT �������� ����
function get_yn_select($name, $selected='1', $event='')
{
    $str = "<select name=\"$name\" $event>\n";
    if ($selected) {
        $str .= "<option value=\"1\" selected>��</option>\n";
        $str .= "<option value=\"0\">�ƴϿ�</option>\n";
    } else {
        $str .= "<option value=\"1\">��</option>\n";
        $str .= "<option value=\"0\" selected>�ƴϿ�</option>\n";
    }
    $str .= "</select>";
    return $str;
}


// ����Ʈ �ο�
function insert_point($mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='', $expire=0)
{
    global $config;
    global $g5;
    global $is_admin;

    // ����Ʈ ����� ���� �ʴ´ٸ� return
    if (!$config['cf_use_point']) { return 0; }

    // ����Ʈ�� ���ٸ� ������Ʈ �� �ʿ� ����
    if ($point == 0) { return 0; }

    // ȸ�����̵� ���ٸ� ������Ʈ �� �ʿ� ����
    if ($mb_id == '') { return 0; }
    $mb = sql_fetch(" select mb_id from {$g5['member_table']} where mb_id = '$mb_id' ");
    if (!$mb['mb_id']) { return 0; }

    // ȸ������Ʈ
    $mb_point = get_point_sum($mb_id);

    // �̹� ��ϵ� �����̶�� �ǳʶ�
    if ($rel_table || $rel_id || $rel_action)
    {
        $sql = " select count(*) as cnt from {$g5['point_table']}
                  where mb_id = '$mb_id'
                    and po_rel_table = '$rel_table'
                    and po_rel_id = '$rel_id'
                    and po_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);
        if ($row['cnt'])
            return -1;
    }

    // ����Ʈ �Ǻ� ����
    $po_expire_date = '9999-12-31';
    if($config['cf_point_term'] > 0) {
        if($expire > 0)
            $po_expire_date = date('Y-m-d', strtotime('+'.($expire - 1).' days', G5_SERVER_TIME));
        else
            $po_expire_date = date('Y-m-d', strtotime('+'.($config['cf_point_term'] - 1).' days', G5_SERVER_TIME));
    }

    $po_expired = 0;
    if($point < 0) {
        $po_expired = 1;
        $po_expire_date = G5_TIME_YMD;
    }
    $po_mb_point = $mb_point + $point;

    $sql = " insert into {$g5['point_table']}
                set mb_id = '$mb_id',
                    po_datetime = '".G5_TIME_YMDHIS."',
                    po_content = '".addslashes($content)."',
                    po_point = '$point',
                    po_use_point = '0',
                    po_mb_point = '$po_mb_point',
                    po_expired = '$po_expired',
                    po_expire_date = '$po_expire_date',
                    po_rel_table = '$rel_table',
                    po_rel_id = '$rel_id',
                    po_rel_action = '$rel_action' ";
    sql_query($sql);

    // ����Ʈ�� ����� ��� ����Ʈ ������ ���ݾ� ���
    if($point < 0) {
        insert_use_point($mb_id, $point);
    }

    // ����Ʈ UPDATE
    $sql = " update {$g5['member_table']} set mb_point = '$po_mb_point' where mb_id = '$mb_id' ";
    sql_query($sql);

    return 1;
}

// �������Ʈ �Է�
function insert_use_point($mb_id, $point, $po_id='')
{
    global $g5, $config;

    if($config['cf_point_term'])
        $sql_order = " order by po_expire_date asc, po_id asc ";
    else
        $sql_order = " order by po_id asc ";

    $point1 = abs($point);
    $sql = " select po_id, po_point, po_use_point
                from {$g5['point_table']}
                where mb_id = '$mb_id'
                  and po_id <> '$po_id'
                  and po_expired = '0'
                  and po_point > po_use_point
                $sql_order ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $point2 = $row['po_point'];
        $point3 = $row['po_use_point'];

        if(($point2 - $point3) > $point1) {
            $sql = " update {$g5['point_table']}
                        set po_use_point = po_use_point + '$point1'
                        where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            break;
        } else {
            $point4 = $point2 - $point3;
            $sql = " update {$g5['point_table']}
                        set po_use_point = po_use_point + '$point4',
                            po_expired = '100'
                        where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            $point1 -= $point4;
        }
    }
}

// �������Ʈ ����
function delete_use_point($mb_id, $point)
{
    global $g5, $config;

    if($config['cf_point_term'])
        $sql_order = " order by po_expire_date desc, po_id desc ";
    else
        $sql_order = " order by po_id desc ";

    $point1 = abs($point);
    $sql = " select po_id, po_use_point, po_expired, po_expire_date
                from {$g5['point_table']}
                where mb_id = '$mb_id'
                  and po_expired <> '1'
                  and po_use_point > 0
                $sql_order ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $point2 = $row['po_use_point'];

        $po_expired = $row['po_expired'];
        if($row['po_expired'] == 100 && ($row['po_expire_date'] == '9999-12-31' || $row['po_expire_date'] >= G5_TIME_YMD))
            $po_expired = 0;

        if($point2 > $point1) {
            $sql = " update {$g5['point_table']}
                        set po_use_point = po_use_point - '$point1',
                            po_expired = '$po_expired'
                        where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            break;
        } else {
            $sql = " update {$g5['point_table']}
                        set po_use_point = '0',
                            po_expired = '$po_expired'
                        where po_id = '{$row['po_id']}' ";
            sql_query($sql);

            $point1 -= $point2;
        }
    }
}

// �Ҹ�����Ʈ ����
function delete_expire_point($mb_id, $point)
{
    global $g5, $config;

    $point1 = abs($point);
    $sql = " select po_id, po_use_point, po_expired, po_expire_date
                from {$g5['point_table']}
                where mb_id = '$mb_id'
                  and po_expired = '1'
                  and po_point >= 0
                  and po_use_point > 0
                order by po_expire_date desc, po_id desc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $point2 = $row['po_use_point'];
        $po_expired = '0';
        $po_expire_date = '9999-12-31';
        if($config['cf_point_term'] > 0)
            $po_expire_date = date('Y-m-d', strtotime('+'.($config['cf_point_term'] - 1).' days', G5_SERVER_TIME));

        if($point2 > $point1) {
            $sql = " update {$g5['point_table']}
                        set po_use_point = po_use_point - '$point1',
                            po_expired = '$po_expired',
                            po_expire_date = '$po_expire_date'
                        where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            break;
        } else {
            $sql = " update {$g5['point_table']}
                        set po_use_point = '0',
                            po_expired = '$po_expired',
                            po_expire_date = '$po_expire_date'
                        where po_id = '{$row['po_id']}' ";
            sql_query($sql);

            $point1 -= $point2;
        }
    }
}

// ����Ʈ ���� �հ�
function get_point_sum($mb_id)
{
    global $g5, $config;

    if($config['cf_point_term'] > 0) {
        // �Ҹ�����Ʈ�� ������ ���� �߰�
        $expire_point = get_expire_point($mb_id);
        if($expire_point > 0) {
            $mb = get_member($mb_id, 'mb_point');
            $content = '����Ʈ �Ҹ�';
            $rel_table = '@expire';
            $rel_id = $mb_id;
            $rel_action = 'expire'.'-'.uniqid('');
            $point = $expire_point * (-1);
            $po_mb_point = $mb['mb_point'] + $point;
            $po_expire_date = G5_TIME_YMD;
            $po_expired = 1;

            $sql = " insert into {$g5['point_table']}
                        set mb_id = '$mb_id',
                            po_datetime = '".G5_TIME_YMDHIS."',
                            po_content = '".addslashes($content)."',
                            po_point = '$point',
                            po_use_point = '0',
                            po_mb_point = '$po_mb_point',
                            po_expired = '$po_expired',
                            po_expire_date = '$po_expire_date',
                            po_rel_table = '$rel_table',
                            po_rel_id = '$rel_id',
                            po_rel_action = '$rel_action' ";
            sql_query($sql);

            // ����Ʈ�� ����� ��� ����Ʈ ������ ���ݾ� ���
            if($point < 0) {
                insert_use_point($mb_id, $point);
            }
        }

        // ��ȿ�Ⱓ�� ���� �� �Ⱓ�� ���� ����Ʈ expired üũ
        $sql = " update {$g5['point_table']}
                    set po_expired = '1'
                    where mb_id = '$mb_id'
                      and po_expired <> '1'
                      and po_expire_date <> '9999-12-31'
                      and po_expire_date < '".G5_TIME_YMD."' ";
        sql_query($sql);
    }

    // ����Ʈ��
    $sql = " select sum(po_point) as sum_po_point
                from {$g5['point_table']}
                where mb_id = '$mb_id' ";
    $row = sql_fetch($sql);

    return $row['sum_po_point'];
}

// �Ҹ� ����Ʈ
function get_expire_point($mb_id)
{
    global $g5, $config;

    if($config['cf_point_term'] == 0)
        return 0;

    $sql = " select sum(po_point - po_use_point) as sum_point
                from {$g5['point_table']}
                where mb_id = '$mb_id'
                  and po_expired = '0'
                  and po_expire_date <> '9999-12-31'
                  and po_expire_date < '".G5_TIME_YMD."' ";
    $row = sql_fetch($sql);

    return $row['sum_point'];
}

// ����Ʈ ����
function delete_point($mb_id, $rel_table, $rel_id, $rel_action)
{
    global $g5;

    $result = false;
    if ($rel_table || $rel_id || $rel_action)
    {
        // ����Ʈ ��������
        $sql = " select * from {$g5['point_table']}
                    where mb_id = '$mb_id'
                      and po_rel_table = '$rel_table'
                      and po_rel_id = '$rel_id'
                      and po_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);

        if(isset($row['po_point']) && $row['po_point'] < 0) {
            $mb_id = $row['mb_id'];
            $po_point = abs($row['po_point']);

            delete_use_point($mb_id, $po_point);
        } else {
            if(isset($row['po_use_point']) && $row['po_use_point'] > 0) {
                insert_use_point($row['mb_id'], $row['po_use_point'], $row['po_id']);
            }
        }

        $result = sql_query(" delete from {$g5['point_table']}
                     where mb_id = '$mb_id'
                       and po_rel_table = '$rel_table'
                       and po_rel_id = '$rel_id'
                       and po_rel_action = '$rel_action' ", false);

        // po_mb_point�� �ݿ�
        if(isset($row['po_point'])) {
            $sql = " update {$g5['point_table']}
                        set po_mb_point = po_mb_point - '{$row['po_point']}'
                        where mb_id = '$mb_id'
                          and po_id > '{$row['po_id']}' ";
            sql_query($sql);
        }

        // ����Ʈ ������ ���� ���ϰ�
        $sum_point = get_point_sum($mb_id);

        // ����Ʈ UPDATE
        $sql = " update {$g5['member_table']} set mb_point = '$sum_point' where mb_id = '$mb_id' ";
        $result = sql_query($sql);
    }

    return $result;
}

// ȸ�� ���̾�
function get_sideview($mb_id, $name='', $email='', $homepage='')
{
    global $config;
    global $g5;
    global $bo_table, $sca, $is_admin, $member;

    $email = get_string_encrypt($email);
    $homepage = set_http(clean_xss_tags($homepage));

    $name     = get_text($name, 0, true);
    $email    = get_text($email);
    $homepage = get_text($homepage);

    $tmp_name = "";
    $en_mb_id = $mb_id;

    if ($mb_id) {
        //$tmp_name = "<a href=\"".G5_BBS_URL."/profile.php?mb_id=".$mb_id."\" class=\"sv_member\" title=\"$name �ڱ�Ұ�\" rel="nofollow" target=\"_blank\" onclick=\"return false;\">$name</a>";
        $tmp_name = '<a href="'.G5_BBS_URL.'/profile.php?mb_id='.$mb_id.'" class="sv_member" title="'.$name.' �ڱ�Ұ�" target="_blank" rel="nofollow" onclick="return false;">';

        if ($config['cf_use_member_icon']) {
            $mb_dir = substr($mb_id,0,2);
            $icon_file = G5_DATA_PATH.'/member/'.$mb_dir.'/'.get_mb_icon_name($mb_id).'.gif';

            if (file_exists($icon_file)) {
                $icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?'.filemtime($icon_file) : '';
                $width = $config['cf_member_icon_width'];
                $height = $config['cf_member_icon_height'];
                $icon_file_url = G5_DATA_URL.'/member/'.$mb_dir.'/'.get_mb_icon_name($mb_id).'.gif'.$icon_filemtile;
                $tmp_name .= '<span class="profile_img"><img src="'.$icon_file_url.'" width="'.$width.'" height="'.$height.'" alt=""></span>';

                if ($config['cf_use_member_icon'] == 2) // ȸ��������+�̸�
                    $tmp_name = $tmp_name.' '.$name;
            } else {
                if( defined('G5_THEME_NO_PROFILE_IMG') ){
                    $tmp_name .= G5_THEME_NO_PROFILE_IMG;
                } else if( defined('G5_NO_PROFILE_IMG') ){
                    $tmp_name .= G5_NO_PROFILE_IMG;
                }
                if ($config['cf_use_member_icon'] == 2) // ȸ��������+�̸�
                    $tmp_name = $tmp_name.' '.$name;
            }
        } else {
            $tmp_name = $tmp_name.' '.$name;
        }
        $tmp_name .= '</a>';

        $title_mb_id = '['.$mb_id.']';
    } else {
        if(!$bo_table)
            return $name;

        $tmp_name = '<a href="'.get_pretty_url($bo_table, '', 'sca='.$sca.'&amp;sfl=wr_name,1&amp;stx='.$name).'" title="'.$name.' �̸����� �˻�" class="sv_guest" rel="nofollow" onclick="return false;">'.$name.'</a>';
        $title_mb_id = '[��ȸ��]';
    }

    $str = "<span class=\"sv_wrap\">\n";
    $str .= $tmp_name."\n";

    $str2 = "<span class=\"sv\">\n";
    if($mb_id)
        $str2 .= "<a href=\"".G5_BBS_URL."/memo_form.php?me_recv_mb_id=".$mb_id."\" onclick=\"win_memo(this.href); return false;\">����������</a>\n";
    if($email)
        $str2 .= "<a href=\"".G5_BBS_URL."/formmail.php?mb_id=".$mb_id."&amp;name=".urlencode($name)."&amp;email=".$email."\" onclick=\"win_email(this.href); return false;\">���Ϻ�����</a>\n";
    if($homepage)
        $str2 .= "<a href=\"".$homepage."\" target=\"_blank\">Ȩ������</a>\n";
    if($mb_id)
        $str2 .= "<a href=\"".G5_BBS_URL."/profile.php?mb_id=".$mb_id."\" onclick=\"win_profile(this.href); return false;\">�ڱ�Ұ�</a>\n";
    if($bo_table) {
        if($mb_id) {
            $str2 .= "<a href=\"".get_pretty_url($bo_table, '', "sca=".$sca."&amp;sfl=mb_id,1&amp;stx=".$en_mb_id)."\">���̵�� �˻�</a>\n";
        } else {
            $str2 .= "<a href=\"".get_pretty_url($bo_table, '', "sca=".$sca."&amp;sfl=wr_name,1&amp;stx=".$name)."\">�̸����� �˻�</a>\n";
        }
    }
    if($mb_id)
        $str2 .= "<a href=\"".G5_BBS_URL."/new.php?mb_id=".$mb_id."\" class=\"link_new_page\" onclick=\"check_goto_new(this.href, event);\">��ü�Խù�</a>\n";
    if($is_admin == "super" && $mb_id) {
        $str2 .= "<a href=\"".G5_ADMIN_URL."/member_form.php?w=u&amp;mb_id=".$mb_id."\" target=\"_blank\">ȸ����������</a>\n";
        $str2 .= "<a href=\"".G5_ADMIN_URL."/point_list.php?sfl=mb_id&amp;stx=".$mb_id."\" target=\"_blank\">����Ʈ����</a>\n";
    }
    $str2 .= "</span>\n";
    $str .= $str2;
    $str .= "\n<noscript class=\"sv_nojs\">".$str2."</noscript>";

    $str .= "</span>";

    return $str;
}


// ������ ���̰� �ϴ� ��ũ (�̹���, �÷���, ������)
function view_file_link($file, $width, $height, $content='')
{
    global $config, $board;
    global $g5;
    static $ids;

    if (!$file) return;

    $ids++;

    // ������ ���� �Խ��Ǽ����� �̹����� ���� ũ�ٸ� �Խ��Ǽ��� ������ ���߰� ������ ���� ���̸� ���
    if ($board && $width > $board['bo_image_width'] && $board['bo_image_width'])
    {
        $rate = $board['bo_image_width'] / $width;
        $width = $board['bo_image_width'];
        $height = (int)($height * $rate);
    }

    // ���� �ִ� ��� ���� ������ �Ӽ��� �ְ�, ������ �ڵ� ���ǵ��� �ڵ带 ������ �ʴ´�.
    if ($width)
        $attr = ' width="'.$width.'" height="'.$height.'" ';
    else
        $attr = '';

    if (preg_match("/\.({$config['cf_image_extension']})$/i", $file) && isset($board['bo_table'])) {
        $attr_href = run_replace('thumb_view_image_href', G5_BBS_URL.'/view_image.php?bo_table='.$board['bo_table'].'&amp;fn='.urlencode($file), $file, $board['bo_table'], $width, $height, $content);
        $img = '<a href="'.$attr_href.'" target="_blank" class="view_image">';
        $img .= '<img src="'.G5_DATA_URL.'/file/'.$board['bo_table'].'/'.urlencode($file).'" alt="'.$content.'" '.$attr.'>';
        $img .= '</a>';

        return $img;
    }
}


// view_file_link() �Լ����� �Ѱ��� �̹����� ���̰� �մϴ�.
// {img:0} ... {img:n} �� ���� ����
function view_image($view, $number, $attribute)
{
    if ($view['file'][$number]['view'])
        return preg_replace("/>$/", " $attribute>", $view['file'][$number]['view']);
    else
        //return "{".$number."�� �̹��� ����}";
        return "";
}


/*
// {link:0} ... {link:n} �� ���� ����
function view_link($view, $number, $attribute)
{
    global $config;

    if ($view['link'][$number]['link'])
    {
        if (!preg_match("/target/i", $attribute))
            $attribute .= " target='$config['cf_link_target']'";
        return "<a href='{$view['link'][$number]['href']}' $attribute>{$view['link'][$number]['link']}</a>";
    }
    else
        return "{".$number."�� ��ũ ����}";
}
*/


function cut_str($str, $len, $suffix="��")
{
    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    $str_len = count($arr_str);

    if ($str_len >= $len) {
        $slice_str = array_slice($arr_str, 0, $len);
        $str = join("", $slice_str);

        return $str . ($str_len > $len ? $suffix : '');
    } else {
        $str = join("", $arr_str);
        return $str;
    }
}


// TEXT �������� ��ȯ
function get_text($str, $html=0, $restore=false)
{
    $source[] = "<";
    $target[] = "&lt;";
    $source[] = ">";
    $target[] = "&gt;";
    $source[] = "\"";
    $target[] = "&#034;";
    $source[] = "\'";
    $target[] = "&#039;";

    if($restore)
        $str = str_replace($target, $source, $str);

    // 3.31
    // TEXT ����� ��� &amp; &nbsp; ���� �ڵ带 �������� ����� �ֱ� ����
    if ($html == 0) {
        $str = html_symbol($str);
    }

    if ($html) {
        $source[] = "\n";
        $target[] = "<br/>";
    }

    return str_replace($source, $target, $str);
}


/*
// HTML Ư������ ��ȯ htmlspecialchars
function hsc($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}
*/

// 3.31
// HTML SYMBOL ��ȯ
// &nbsp; &amp; &middot; ���� �������� ���
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}


/*************************************************************************
**
**  SQL ���� �Լ� ����
**
*************************************************************************/

// DB ����
function sql_connect($host, $user, $pass, $db=G5_MYSQL_DB)
{
    global $g5;

    if(function_exists('mysqli_connect') && G5_MYSQLI_USE) {
        $link = mysqli_connect($host, $user, $pass, $db);

        // ���� ���� �߻� �� ��ũ��Ʈ ����
        if (mysqli_connect_errno()) {
            die('Connect Error: '.mysqli_connect_error());
        }
    } else {
        $link = mysql_connect($host, $user, $pass);
    }

    return $link;
}


// DB ����
function sql_select_db($db, $connect)
{
    global $g5;

    if(function_exists('mysqli_select_db') && G5_MYSQLI_USE)
        return @mysqli_select_db($connect, $db);
    else
        return @mysql_select_db($db, $connect);
}


function sql_set_charset($charset, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    if(function_exists('mysqli_set_charset') && G5_MYSQLI_USE)
        mysqli_set_charset($link, $charset);
    else
        mysql_query(" set names {$charset} ", $link);
}

function sql_data_seek($result, $offset=0)
{
    if ( ! $result ) return;

    if(function_exists('mysqli_set_charset') && G5_MYSQLI_USE)
        mysqli_data_seek($result, $offset);
    else
        mysql_data_seek($result, $offset);
}

// mysqli_query �� mysqli_error �� �Ѳ����� ó��
// mysql connect resource ���� - ������δ� ����
function sql_query($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    global $g5, $g5_debug;

    if(!$link)
        $link = $g5['connect_db'];

    // Blind SQL Injection ����� �ذ�
    $sql = trim($sql);
    // union�� ����� ������� �ʽ��ϴ�.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    // `information_schema` DB���� ������ ������� �ʽ��ϴ�.
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    $is_debug = get_permission_debug_show();
    
    $start_time = $is_debug ? get_microtime() : 0;

    if(function_exists('mysqli_query') && G5_MYSQLI_USE) {
        if ($error) {
            $result = @mysqli_query($link, $sql) or die("<p>$sql<p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysqli_query($link, $sql);
        }
    } else {
        if ($error) {
            $result = @mysql_query($sql, $link) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysql_query($sql, $link);
        }
    }

    $end_time = $is_debug ? get_microtime() : 0;

    if($result && $is_debug) {
        // ���⿡ ������ sql���� ȭ�鿡 ǥ���ϴ� ���� �ֱ�
        $g5_debug['sql'][] = array(
            'sql' => $sql,
            'start_time' => $start_time,
            'end_time' => $end_time,
            );
    }

    run_event('sql_query_after', $result, $sql, $start_time, $end_time);

    return $result;
}


// ������ ������ �� ��������� ������ ��´�.
function sql_fetch($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    $result = sql_query($sql, $error, $link);
    //$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysqli_errno() . " : " .  mysqli_error() . "<p>error file : $_SERVER['SCRIPT_NAME']");
    $row = sql_fetch_array($result);
    return $row;
}


// ��������� ���� �����迭(�̸�����)�� ��´�.
function sql_fetch_array($result)
{
    if( ! $result) return array();

    if(function_exists('mysqli_fetch_assoc') && G5_MYSQLI_USE)
        $row = @mysqli_fetch_assoc($result);
    else
        $row = @mysql_fetch_assoc($result);

    return $row;
}


// $result�� ���� �޸�(memory)�� �ִ� ������ ��� �����Ѵ�.
// sql_free_result()�� ����κ��� ���� ���� ���� Ŀ�� ���� �޸𸮸� ����� ������ ���� �� ���ȴ�.
// ��, ��� ���� ��ũ��Ʈ(script) ����ΰ� ����Ǹ鼭 �޸𸮿��� �ڵ������� ��������.
function sql_free_result($result)
{
    if(!is_resource($result)) return;

    if(function_exists('mysqli_free_result') && G5_MYSQLI_USE)
        return mysqli_free_result($result);
    else
        return mysql_free_result($result);
}


function sql_password($value)
{
    // mysql 4.0x ���� ���������� password() �Լ��� ����� 16bytes
    // mysql 4.1x �̻� ���������� password() �Լ��� ����� 41bytes
    $row = sql_fetch(" select password('$value') as pass ");

    return $row['pass'];
}


function sql_insert_id($link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    if(function_exists('mysqli_insert_id') && G5_MYSQLI_USE)
        return mysqli_insert_id($link);
    else
        return mysql_insert_id($link);
}


function sql_num_rows($result)
{
    if(function_exists('mysqli_num_rows') && G5_MYSQLI_USE)
        return mysqli_num_rows($result);
    else
        return mysql_num_rows($result);
}


function sql_field_names($table, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    $columns = array();

    $sql = " select * from `$table` limit 1 ";
    $result = sql_query($sql, $link);

    if(function_exists('mysqli_fetch_field') && G5_MYSQLI_USE) {
        while($field = mysqli_fetch_field($result)) {
            $columns[] = $field->name;
        }
    } else {
        $i = 0;
        $cnt = mysql_num_fields($result);
        while($i < $cnt) {
            $field = mysql_fetch_field($result, $i);
            $columns[] = $field->name;
            $i++;
        }
    }

    return $columns;
}


function sql_error_info($link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    if(function_exists('mysqli_error') && G5_MYSQLI_USE) {
        return mysqli_errno($link) . ' : ' . mysqli_error($link);
    } else {
        return mysql_errno($link) . ' : ' . mysql_error($link);
    }
}


// PHPMyAdmin ����
function get_table_define($table, $crlf="\n")
{
    global $g5;

    // For MySQL < 3.23.20
    $schema_create = 'CREATE TABLE ' . $table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $schema_create .= '    ' . $row['Field'] . ' ' . $row['Type'];
        if (isset($row['Default']) && $row['Default'] != '')
        {
            $schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
        }
        if ($row['Null'] != 'YES')
        {
            $schema_create .= ' NOT NULL';
        }
        if ($row['Extra'] != '')
        {
            $schema_create .= ' ' . $row['Extra'];
        }
        $schema_create     .= ',' . $crlf;
    } // end while
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $kname    = $row['Key_name'];
        $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if ($kname != 'PRIMARY' && $row['Non_unique'] == 0) {
            $kname = "UNIQUE|$kname";
        }
        if ($comment == 'FULLTEXT') {
            $kname = 'FULLTEXT|$kname';
        }
        if (!isset($index[$kname])) {
            $index[$kname] = array();
        }
        if ($sub_part > 1) {
            $index[$kname][] = $row['Column_name'] . '(' . $sub_part . ')';
        } else {
            $index[$kname][] = $row['Column_name'];
        }
    } // end while
    sql_free_result($result);

    foreach((array) $index as $x => $columns){
        $schema_create     .= ',' . $crlf;
        if ($x == 'PRIMARY') {
            $schema_create .= '    PRIMARY KEY (';
        } else if (substr($x, 0, 6) == 'UNIQUE') {
            $schema_create .= '    UNIQUE ' . substr($x, 7) . ' (';
        } else if (substr($x, 0, 8) == 'FULLTEXT') {
            $schema_create .= '    FULLTEXT ' . substr($x, 9) . ' (';
        } else {
            $schema_create .= '    KEY ' . $x . ' (';
        }
        $schema_create     .= implode(', ', $columns) . ')';
    } // end while

    $schema_create .= $crlf . ') ENGINE=MyISAM DEFAULT CHARSET=utf8';

    return get_db_create_replace($schema_create);
} // end of the 'PMA_getTableDef()' function


// ���۷� üũ
function referer_check($url='')
{
    /*
    // ����� üũ�� ���� ���Ͽ� �ּ� ó����
    global $g5;

    if (!$url)
        $url = G5_URL;

    if (!preg_match("/^http['s']?:\/\/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER']))
        alert("����� �� ������ �ƴѰ� �����ϴ�.", $url);
    */
}


// �ѱ� ����
function get_yoil($date, $full=0)
{
    $arr_yoil = array ('��', '��', 'ȭ', '��', '��', '��', '��');

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= '����';
    }
    return $str;
}


// ��¥�� select �ڽ� �������� ��´�
function date_select($date, $name='')
{
    global $g5;

    $s = '';
    if (substr($date, 0, 4) == "0000") {
        $date = G5_TIME_YMDHIS;
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    // ��
    $s .= "<select name='{$name}_y'>";
    for ($i=$m['0']-3; $i<=$m['0']+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_d'>";
    for ($i=1; $i<=31; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    return $s;
}


// �ð��� select �ڽ� �������� ��´�
// 1.04.00
// ��ſ� �ð� ������ �����ϰ� �Ǹ鼭 �߰���
function time_select($time, $name="")
{
    preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $m);

    // ��
    $s .= "<select name='{$name}_h'>";
    for ($i=0; $i<=23; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_i'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    // ��
    $s .= "<select name='{$name}_s'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>�� \n";

    return $s;
}


// DEMO ��� ������ ������ ���� ȭ������ �ν���
function check_demo()
{
    global $is_admin;
    if ($is_admin != 'super' && file_exists(G5_PATH.'/DEMO'))
        alert('���� ȭ�鿡���� �Ͻ�(����) �� ���� �۾��Դϴ�.');
}


// ���ڿ��� �ѱ�, ����, ����, Ư�����ڷ� �����Ǿ� �ִ��� �˻�
function check_string($str, $options)
{
    global $g5;

    $s = '';
    for($i=0;$i<strlen($str);$i++) {
        $c = $str[$i];
        $oc = ord($c);

        // �ѱ�
        if ($oc >= 0xA0 && $oc <= 0xFF) {
            if ($options & G5_HANGUL) {
                $s .= $c . $str[$i+1] . $str[$i+2];
            }
            $i+=2;
        }
        // ����
        else if ($oc >= 0x30 && $oc <= 0x39) {
            if ($options & G5_NUMERIC) {
                $s .= $c;
            }
        }
        // ���빮��
        else if ($oc >= 0x41 && $oc <= 0x5A) {
            if (($options & G5_ALPHABETIC) || ($options & G5_ALPHAUPPER)) {
                $s .= $c;
            }
        }
        // ���ҹ���
        else if ($oc >= 0x61 && $oc <= 0x7A) {
            if (($options & G5_ALPHABETIC) || ($options & G5_ALPHALOWER)) {
                $s .= $c;
            }
        }
        // ����
        else if ($oc == 0x20) {
            if ($options & G5_SPACE) {
                $s .= $c;
            }
        }
        else {
            if ($options & G5_SPECIAL) {
                $s .= $c;
            }
        }
    }

    // �Ѿ�� ���� ���Ͽ� ������ ��, Ʋ���� ����
    return ($str == $s);
}


// �ѱ�(2bytes)���� ������ ���ڰ� 1byte�� ������ ���
// ��½� ������ ������ �߻��ϹǷ� ������ �������� ���� ����(1byte)�� �ϳ� ����
function cut_hangul_last($hangul)
{
    global $g5;

    // �ѱ��� ���ʳ��� ?�� ǥ�õǴ� ������ ����
    $cnt = 0;
    for($i=0;$i<strlen($hangul);$i++) {
        // �ѱ۸� ����
        if (ord($hangul[$i]) >= 0xA0) {
            $cnt++;
        }
    }

    return $hangul;
}


// ���̺��� INDEX(Ű) ��뿩�� �˻�
function explain($sql)
{
    if (preg_match("/^(select)/i", trim($sql))) {
        $q = "explain $sql";
        echo $q;
        $row = sql_fetch($q);
        if (!$row['key']) $row['key'] = "NULL";
        echo " <font color=blue>(type={$row['type']} , key={$row['key']})</font>";
    }
}

// �Ǽ��±� ��ȯ
function bad_tag_convert($code)
{
    global $view;
    global $member, $is_admin;

    if ($is_admin && $member['mb_id'] !== $view['mb_id']) {
        //$code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>(\<\/(embed|object)\>)?#i",
        // embed �Ǵ� object �±׸� ���� �ʴ� ��� ���͸��� �ǵ��� ����
        $code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>?(\<\/(embed|object)\>)?#i", '_callback_bad_tag_convert', $code);
    }

    return preg_replace("/\<([\/]?)(script|iframe|form)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
}

function _callback_bad_tag_convert($matches){
    return "<div class=\"embedx\">���ȹ����� ���Ͽ� ������ ���̵�δ� embed �Ǵ� object �±׸� �� �� �����ϴ�. Ȯ���Ͻ÷��� ���������� ���� �ٸ� ���̵�� �����ϼ���.</div>";
}

// ��ū ����
function _token()
{
    return md5(uniqid(rand(), true));
}


// �ҹ������� ������ ��ū�� �����ϸ鼭 ��ū���� ����
function get_token()
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_token', $token);

    return $token;
}


// POST�� �Ѿ�� ��ū�� ���ǿ� ����� ��ū ��
function check_token()
{
    set_session('ss_token', '');
    return true;
}


// ���ڿ��� utf8 ���ڰ� ��� �ִ��� �˻��ϴ� �Լ�
// �ڵ� : http://in2.php.net/manual/en/function.mb-check-encoding.php#95289
function is_utf8($str)
{
    $len = strlen($str);
    for($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
}


// UTF-8 ���ڿ� �ڸ���
// ��ó : https://www.google.co.kr/search?q=utf8_strcut&aq=f&oq=utf8_strcut&aqs=chrome.0.57j0l3.826j0&sourceid=chrome&ie=UTF-8
function utf8_strcut( $str, $size, $suffix='...' )
{
    if( function_exists('mb_strlen') && function_exists('mb_substr') ){
        
        if(mb_strlen($str)<=$size) {
            return $str;
        } else {
            $str = mb_substr($str, 0, $size, 'utf-8');
            $str .= $suffix;
        }

    } else {
        $substr = substr( $str, 0, $size * 2 );
        $multi_size = preg_match_all( '/[\x80-\xff]/', $substr, $multi_chars );

        if ( $multi_size > 0 )
            $size = $size + intval( $multi_size / 3 ) - 1;

        if ( strlen( $str ) > $size ) {
            $str = substr( $str, 0, $size );
            $str = preg_replace( '/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str );
            $str .= $suffix;
        }
    }

    return $str;
}


/*
-----------------------------------------------------------
    Charset �� ��ȯ�ϴ� �Լ�
-----------------------------------------------------------
iconv �Լ��� ������ iconv �� ��ȯ�ϰ�
������ mb_convert_encoding �Լ��� ����Ѵ�.
�Ѵ� ������ ����� �� ����.
*/
function convert_charset($from_charset, $to_charset, $str)
{

    if( function_exists('iconv') )
        return iconv($from_charset, $to_charset, $str);
    elseif( function_exists('mb_convert_encoding') )
        return mb_convert_encoding($str, $to_charset, $from_charset);
    else
        die("Not found 'iconv' or 'mbstring' library in server.");
}


// mysqli_real_escape_string �� alias ����� �Ѵ�.
function sql_real_escape_string($str, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];
    
    if(function_exists('mysqli_connect') && G5_MYSQLI_USE) {
        return mysqli_real_escape_string($link, $str);
    }

    return mysql_real_escape_string($str, $link);
}

function escape_trim($field)
{
    $str = call_user_func(G5_ESCAPE_FUNCTION, $field);
    return $str;
}


// $_POST ���Ŀ��� checkbox ������Ʈ�� checked �Ӽ����� checked �� �Ǿ� �Ѿ� �Դ����� �˻�
function is_checked($field)
{
    return !empty($_POST[$field]);
}


function abs_ip2long($ip='')
{
    $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
    return abs(ip2long($ip));
}


function get_selected($field, $value)
{
    if( is_int($value) ){
        return ((int) $field===$value) ? ' selected="selected"' : '';
    }

    return ($field===$value) ? ' selected="selected"' : '';
}


function get_checked($field, $value)
{
    if( is_int($value) ){
        return ((int) $field===$value) ? ' checked="checked"' : '';
    }

    return ($field===$value) ? ' checked="checked"' : '';
}


function is_mobile()
{
    return preg_match('/'.G5_MOBILE_AGENT.'/i', $_SERVER['HTTP_USER_AGENT']);
}


/*******************************************************************************
    ������ Ű�� ��´�.

    ��� :

        ����Ͻú���00 ~ ����Ͻú���99
        ��(4) ��(2) ��(2) ��(2) ��(2) ��(2) 100����1��(2)
        �� 16�ڸ��̸� �⵵�� 2�ڸ��� ��� ����ص� �˴ϴ�.
        ��) 2008062611570199 �Ǵ� 08062611570199 (2100������� ����Ű)

    ����ϴ� �� :
    1. �Խ��� �۾���� �̸� ����Ű�� ��� ���� ���ε� �ʵ忡 �ִ´�.
    2. �ֹ���ȣ �����ÿ� ����Ѵ�.
    3. ��Ÿ ����Ű�� �ʿ��� ������ ����Ѵ�.
*******************************************************************************/
// ������ get_unique_id() �Լ��� ������� �ʰ� get_uniqid() �� ����Ѵ�.
function get_uniqid()
{
    global $g5;

    sql_query(" LOCK TABLE {$g5['uniqid_table']} WRITE ");
    while (1) {
        // ����Ͻú��ʿ� 100���� 1�� ���ڸ��� �߰��� (1/100 �� �տ� �ڸ��� ���ڸ��� 0���� ä��)
        $key = date('YmdHis', time()) . str_pad((int)((float)microtime()*100), 2, "0", STR_PAD_LEFT);

        $result = sql_query(" insert into {$g5['uniqid_table']} set uq_id = '$key', uq_ip = '{$_SERVER['REMOTE_ADDR']}' ", false);
        if ($result) break; // ������ �����̸� ������.

        // insert ���� �������� �����ð� ������ �ٽ� ����Ű�� �����.
        usleep(10000); // 100���� 1�ʸ� ����
    }
    sql_query(" UNLOCK TABLES ");

    return $key;
}


// CHARSET ���� : euc-kr -> utf-8
function iconv_utf8($str)
{
    return iconv('euc-kr', 'utf-8', $str);
}


// CHARSET ���� : utf-8 -> euc-kr
function iconv_euckr($str)
{
    return iconv('utf-8', 'euc-kr', $str);
}


// PC �Ǵ� ����� ��������� �˻�
function check_device($device)
{
    global $is_admin;

    if ($is_admin) return;

    if ($device=='pc' && G5_IS_MOBILE) {
        alert('PC ���� �Խ����Դϴ�.', G5_URL);
    } else if ($device=='mobile' && !G5_IS_MOBILE) {
        alert('����� ���� �Խ����Դϴ�.', G5_URL);
    }
}


// �Խ��� �ֽű� ĳ�� ���� ����
function delete_cache_latest($bo_table)
{
    if (!preg_match("/^([A-Za-z0-9_]{1,20})$/", $bo_table)) {
        return;
    }

    g5_delete_cache_by_prefix('latest-'.$bo_table.'-');
}

// �Խ��� ÷������ ����� ����
function delete_board_thumbnail($bo_table, $file)
{
    if(!$bo_table || !$file)
        return;

    $fn = preg_replace("/\.[^\.]+$/i", "", basename($file));
    $files = glob(G5_DATA_PATH.'/file/'.$bo_table.'/thumb-'.$fn.'*');
    if (is_array($files)) {
        foreach ($files as $filename)
            unlink($filename);
    }
}

// ������ �̹��� ���
function get_editor_image($contents, $view=true)
{
    if(!$contents)
        return false;

    // $contents �� img �±� ����
    if ($view)
        $pattern = "/<img([^>]*)>/iS";
    else
        $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
    preg_match_all($pattern, $contents, $matchs);

    return $matchs;
}

// ������ ����� ����
function delete_editor_thumbnail($contents)
{
    if(!$contents)
        return;
    
    run_event('delete_editor_thumbnail_before', $contents);

    // $contents �� img �±� ����
    $matchs = get_editor_image($contents, false);

    if(!$matchs)
        return;

    for($i=0; $i<count($matchs[1]); $i++) {
        // �̹��� path ����
        $imgurl = @parse_url($matchs[1][$i]);
        $srcfile = dirname(G5_PATH).$imgurl['path'];
        if(! preg_match('/(\.jpe?g|\.gif|\.png)$/i', $srcfile)) continue;
        $filename = preg_replace("/\.[^\.]+$/i", "", basename($srcfile));
        $filepath = dirname($srcfile);
        $files = glob($filepath.'/thumb-'.$filename.'*');
        if (is_array($files)) {
            foreach($files as $filename)
                unlink($filename);
        }
    }

    run_event('delete_editor_thumbnail_after', $contents, $matchs);
}

// 1:1���� ÷������ ����� ����
function delete_qa_thumbnail($file)
{
    if(!$file)
        return;

    $fn = preg_replace("/\.[^\.]+$/i", "", basename($file));
    $files = glob(G5_DATA_PATH.'/qa/thumb-'.$fn.'*');
    if (is_array($files)) {
        foreach ($files as $filename)
            unlink($filename);
    }
}

// ��Ų style sheet ���� ���
function get_skin_stylesheet($skin_path, $dir='')
{
    if(!$skin_path)
        return "";

    $str = "";
    $files = array();

    if($dir)
        $skin_path .= '/'.$dir;

    $skin_url = G5_URL.str_replace("\\", "/", str_replace(G5_PATH, "", $skin_path));

    if(is_dir($skin_path)) {
        if($dh = opendir($skin_path)) {
            while(($file = readdir($dh)) !== false) {
                if($file == "." || $file == "..")
                    continue;

                if(is_dir($skin_path.'/'.$file))
                    continue;

                if(preg_match("/\.(css)$/i", $file))
                    $files[] = $file;
            }
            closedir($dh);
        }
    }

    if(!empty($files)) {
        sort($files);

        foreach($files as $file) {
            $str .= '<link rel="stylesheet" href="'.$skin_url.'/'.$file.'?='.date("md").'">'."\n";
        }
    }

    return $str;

    /*
    // glob �� �̿��� �ڵ�
    if (!$skin_path) return '';
    $skin_path .= $dir ? '/'.$dir : '';

    $str = '';
    $skin_url = G5_URL.str_replace('\\', '/', str_replace(G5_PATH, '', $skin_path));

    foreach (glob($skin_path.'/*.css') as $filepath) {
        $file = str_replace($skin_path, '', $filepath);
        $str .= '<link rel="stylesheet" href="'.$skin_url.'/'.$file.'?='.date('md').'">'."\n";
    }
    return $str;
    */
}

// ��Ų javascript ���� ���
function get_skin_javascript($skin_path, $dir='')
{
    if(!$skin_path)
        return "";

    $str = "";
    $files = array();

    if($dir)
        $skin_path .= '/'.$dir;

    $skin_url = G5_URL.str_replace("\\", "/", str_replace(G5_PATH, "", $skin_path));

    if(is_dir($skin_path)) {
        if($dh = opendir($skin_path)) {
            while(($file = readdir($dh)) !== false) {
                if($file == "." || $file == "..")
                    continue;

                if(is_dir($skin_path.'/'.$file))
                    continue;

                if(preg_match("/\.(js)$/i", $file))
                    $files[] = $file;
            }
            closedir($dh);
        }
    }

    if(!empty($files)) {
        sort($files);

        foreach($files as $file) {
            $str .= '<script src="'.$skin_url.'/'.$file.'"></script>'."\n";
        }
    }

    return $str;
}

// file_put_contents �� PHP5 ���� �Լ��̹Ƿ� PHP4 ������������ ����ϱ� ����
// http://www.phpied.com/file_get_contents-for-php4/
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}


// HTML ������ ó��
function html_end()
{
    global $html_process;

    return $html_process->run();
}

function add_stylesheet($stylesheet, $order=0)
{
    global $html_process;

    if(trim($stylesheet) && method_exists($html_process, 'merge_stylesheet') )
        $html_process->merge_stylesheet($stylesheet, $order);
}

function add_javascript($javascript, $order=0)
{
    global $html_process;

    if(trim($javascript) && method_exists($html_process, 'merge_javascript') )
        $html_process->merge_javascript($javascript, $order);
}

class html_process {
    protected $css = array();
    protected $js  = array();

    function merge_stylesheet($stylesheet, $order)
    {
        $links = $this->css;
        $is_merge = true;

        foreach($links as $link) {
            if($link[1] == $stylesheet) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            $this->css[] = array($order, $stylesheet);
    }

    function merge_javascript($javascript, $order)
    {
        $scripts = $this->js;
        $is_merge = true;

        foreach($scripts as $script) {
            if($script[1] == $javascript) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            $this->js[] = array($order, $javascript);
    }

    function run()
    {
        global $config, $g5, $member;

        // ���������� ó��
        $tmp_sql = " select count(*) as cnt from {$g5['login_table']} where lo_ip = '{$_SERVER['REMOTE_ADDR']}' ";
        $tmp_row = sql_fetch($tmp_sql);
        $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']; 

        if ($tmp_row['cnt']) {
            $tmp_sql = " update {$g5['login_table']} set mb_id = '{$member['mb_id']}', lo_datetime = '".G5_TIME_YMDHIS."', lo_location = '{$g5['lo_location']}', lo_url = '{$g5['lo_url']}' where lo_ip = '{$_SERVER['REMOTE_ADDR']}' ";
            sql_query($tmp_sql, FALSE);
        } else {
            $tmp_sql = " insert into {$g5['login_table']} ( lo_ip, mb_id, lo_datetime, lo_location, lo_url ) values ( '{$_SERVER['REMOTE_ADDR']}', '{$member['mb_id']}', '".G5_TIME_YMDHIS."', '{$g5['lo_location']}',  '{$g5['lo_url']}' ) ";
            sql_query($tmp_sql, FALSE);

            // �ð��� ���� ������ �����Ѵ�
            sql_query(" delete from {$g5['login_table']} where lo_datetime < '".date("Y-m-d H:i:s", G5_SERVER_TIME - (60 * $config['cf_login_minutes']))."' ");

            // �δ�(overhead)�� �ִٸ� ���̺� ����ȭ
            //$row = sql_fetch(" SHOW TABLE STATUS FROM `$mysql_db` LIKE '$g5['login_table']' ");
            //if ($row['Data_free'] > 0) sql_query(" OPTIMIZE TABLE $g5['login_table'] ");
        }

        $buffer = ob_get_contents();
        ob_end_clean();

        $stylesheet = '';
        $links = $this->css;

        if(!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);
            
            $links = run_replace('html_process_css_files', $links);

            foreach($links as $link) {
                if(!trim($link[1]))
                    continue;

                $link[1] = preg_replace('#\.css([\'\"]?>)$#i', '.css?ver='.G5_CSS_VER.'$1', $link[1]);

                $stylesheet .= PHP_EOL.$link[1];
            }
        }

        $javascript = '';
        $scripts = $this->js;
        $php_eol = '';

        unset($order);
        unset($index);

        if(!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);
            
            $scripts = run_replace('html_process_script_files', $scripts);

            foreach($scripts as $js) {
                if(!trim($js[1]))
                    continue;
                
                $add_version_str = (stripos($js[1], $http_host) !== false) ? '?ver='.G5_JS_VER : '';
                $js[1] = preg_replace('#\.js([\'\"]?>)<\/script>$#i', '.js'.$add_version_str.'$1</script>', $js[1]);

                $javascript .= $php_eol.$js[1];
                $php_eol = PHP_EOL;
            }
        }

        /*
        </title>
        <link rel="stylesheet" href="default.css">
        ������ ��Ų�� ��Ÿ�Ͻ�Ʈ�� ��ġ�ϵ��� �ϰ� �Ѵ�.
        */
        $buffer = preg_replace('#(</title>[^<]*<link[^>]+>)#', "$1$stylesheet", $buffer);

        /*
        </head>
        <body>
        ���� ��Ų�� �ڹٽ�ũ��Ʈ�� ��ġ�ϵ��� �ϰ� �Ѵ�.
        */
        $nl = '';
        if($javascript)
            $nl = "\n";
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);
        
        $meta_tag = run_replace('html_process_add_meta', '');
        
        if( $meta_tag ){
            /*
            </title>content<body>
            ���� ��Ÿ�±װ� ��ġ �ϵ��� �ϰ� �Ѵ�.
            */
            $nl = "\n";
            $buffer = preg_replace('#(<title[^>]*>.*?</title>)#', "$meta_tag{$nl}$1", $buffer);
        }

        return $buffer;
    }
}

// �޴�����ȣ�� ���ڸ� ���� �� �߰��� ������(-)�� �ִ´�.
function hyphen_hp_number($hp)
{
    $hp = preg_replace("/[^0-9]/", "", $hp);
    return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
}


// �α��� �� �̵��� URL
function login_url($url='')
{
    if (!$url) $url = G5_URL;

    return urlencode(clean_xss_tags(urldecode($url)));
}


// $dir �� �����Ͽ� https �Ǵ� http �ּҸ� ��ȯ�Ѵ�.
function https_url($dir, $https=true)
{
    if ($https) {
        if (G5_HTTPS_DOMAIN) {
            $url = G5_HTTPS_DOMAIN.'/'.$dir;
        } else {
            $url = G5_URL.'/'.$dir;
        }
    } else {
        if (G5_DOMAIN) {
            $url = G5_DOMAIN.'/'.$dir;
        } else {
            $url = G5_URL.'/'.$dir;
        }
    }

    return $url;
}


// �Խ����� ���������� , �� �����Ͽ� ������Ʈ �Ѵ�.
function board_notice($bo_notice, $wr_id, $insert=false)
{
    $notice_array = explode(",", trim($bo_notice));

    if($insert && in_array($wr_id, $notice_array))
        return $bo_notice;

    $notice_array = array_merge(array($wr_id), $notice_array);
    $notice_array = array_unique($notice_array);
    foreach ($notice_array as $key=>$value) {
        if (!trim($value))
            unset($notice_array[$key]);
    }
    if (!$insert) {
        foreach ($notice_array as $key=>$value) {
            if ((int)$value == (int)$wr_id)
                unset($notice_array[$key]);
        }
    }
    return implode(",", $notice_array);
}


// goo.gl ª���ּ� �����
function googl_short_url($longUrl)
{
    global $config;
    
    // ���� ª�� �ּҴ� ���񽺰� ���� �Ǿ����ϴ�.
    return function_exists('run_replace') ? run_replace('googl_short_url', $longUrl) : $longUrl;
}


// �ӽ� ����� �� ��
function autosave_count($mb_id)
{
    global $g5;

    if ($mb_id) {
        $row = sql_fetch(" select count(*) as cnt from {$g5['autosave_table']} where mb_id = '$mb_id' ");
        return (int)$row['cnt'];
    } else {
        return 0;
    }
}

// ����Ȯ�γ��� ���
function insert_cert_history($mb_id, $company, $method)
{
    global $g5;

    $sql = " insert into {$g5['cert_history_table']}
                set mb_id = '$mb_id',
                    cr_company = '$company',
                    cr_method = '$method',
                    cr_ip = '{$_SERVER['REMOTE_ADDR']}',
                    cr_date = '".G5_TIME_YMD."',
                    cr_time = '".G5_TIME_HIS."' ";
    sql_query($sql);
}

// �����õ�ȸ�� üũ
function certify_count_check($mb_id, $type)
{
    global $g5, $config;

    if($config['cf_cert_use'] != 2)
        return;

    if($config['cf_cert_limit'] == 0)
        return;

    $sql = " select count(*) as cnt from {$g5['cert_history_table']} ";

    if($mb_id) {
        $sql .= " where mb_id = '$mb_id' ";
    } else {
        $sql .= " where cr_ip = '{$_SERVER['REMOTE_ADDR']}' ";
    }

    $sql .= " and cr_method = '".$type."' and cr_date = '".G5_TIME_YMD."' ";

    $row = sql_fetch($sql);

    switch($type) {
        case 'hp':
            $cert = '�޴���';
            break;
        case 'ipin':
            $cert = '������';
            break;
        default:
            break;
    }

    if((int)$row['cnt'] >= (int)$config['cf_cert_limit'])
        alert_close('���� '.$cert.' ����Ȯ���� '.$row['cnt'].'ȸ �̿��ϼż� �� �̻� �̿��� �� �����ϴ�.');
}

// 1:1���� �����ε�
function get_qa_config($fld='*', $is_cache=false)
{
    global $g5;

    static $cache = array();

    if( $is_cache && !empty($cache) ){
        return $cache;
    }

    $sql = " select * from {$g5['qa_config_table']} ";
    $cache = run_replace('get_qa_config', sql_fetch($sql));

    return $cache;
}

// get_sock �Լ� ��ü
if (!function_exists("get_sock")) {
    function get_sock($url, $timeout=30)
    {
        // host �� uri �� �и�
        //if (ereg("http://([a-zA-Z0-9_\-\.]+)([^<]*)", $url, $res))
        if (preg_match("/http:\/\/([a-zA-Z0-9_\-\.]+)([^<]*)/", $url, $res))
        {
            $host = $res[1];
            $get  = $res[2];
        }
        
        $header = '';

        // 80�� ��Ʈ�� ��Ĺ���� �õ�
        $fp = fsockopen ($host, 80, $errno, $errstr, $timeout);
        if (!$fp)
        {
            //die("$errstr ($errno)\n");

            echo "$errstr ($errno)\n";
            return null;
        }
        else
        {
            fputs($fp, "GET $get HTTP/1.0\r\n");
            fputs($fp, "Host: $host\r\n");
            fputs($fp, "\r\n");

            // header �� content �� �и��Ѵ�.
            while (trim($buffer = fgets($fp,1024)) != "")
            {
                $header .= $buffer;
            }
            while (!feof($fp))
            {
                $buffer .= fgets($fp,1024);
            }
        }
        fclose($fp);

        // content �� return �Ѵ�.
        return $buffer;
    }
}

// ����, ���� ��� ���� üũ
function module_exec_check($exe, $type)
{
    $error = '';
    $is_linux = false;
    if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
        $is_linux = true;

    // ��� ���� �����ϴ��� üũ
    if(!is_file($exe)) {
        $error = $exe.' ������ �������� �ʽ��ϴ�.';
    } else {
        // ������� üũ
        if(!is_executable($exe)) {
            if($is_linux)
                $error = $exe.'\n������ ��������� �����ϴ�.\n\nchmod 755 '.basename($exe).' �� ���� ��������� �ο��� �ֽʽÿ�.';
            else
                $error = $exe.'\n������ ��������� �����ϴ�.\n\n'.basename($exe).' ���Ͽ� ��������� �ο��� �ֽʽÿ�.';
        } else {
            // ���̳ʸ� ��������
            if($is_linux) {

                if ( !function_exists('exec') ) {
                    alert('exec �Լ������� �Ұ����ϹǷ� ����Ҽ� �����ϴ�.');
                }

                $search = false;
                $isbinary = true;
                $executable = true;

                switch($type) {
                    case 'ct_cli':
                        exec($exe.' -h 2>&1', $out, $return_var);

                        if($return_var == 139) {
                            $isbinary = false;
                            break;
                        }

                        for($i=0; $i<count($out); $i++) {
                            if(strpos($out[$i], 'KCP ENC') !== false) {
                                $search = true;
                                break;
                            }
                        }
                        break;
                    case 'okname':
                        exec($exe.' D 2>&1', $out, $return_var);

                        if($return_var == 139) {
                            $isbinary = false;
                            break;
                        }

                        for($i=0; $i<count($out); $i++) {
                            if(strpos(strtolower($out[$i]), 'ret code') !== false) {
                                $search = true;
                                break;
                            }
                        }
                        break;
                }

                if(!$isbinary || !$search) {
                    $error = $exe.'\n������ ���̳ʸ� Ÿ������ �ٽ� ���ε��Ͽ� �ֽʽÿ�.';
                }
            }
        }
    }

    if($error) {
        $error = '<script>alert("'.$error.'");</script>';
    }

    return $error;
}

// �ּ����
function print_address($addr1, $addr2, $addr3, $addr4)
{
    $address = get_text(trim($addr1));
    $addr2   = get_text(trim($addr2));
    $addr3   = get_text(trim($addr3));

    if($addr4 == 'N') {
        if($addr2)
            $address .= ' '.$addr2;
    } else {
        if($addr2)
            $address .= ', '.$addr2;
    }

    if($addr3)
        $address .= ' '.$addr3;

    return $address;
}

// input vars üũ
function check_input_vars()
{
    $max_input_vars = ini_get('max_input_vars');

    if($max_input_vars) {
        $post_vars = count($_POST, COUNT_RECURSIVE);
        $get_vars = count($_GET, COUNT_RECURSIVE);
        $cookie_vars = count($_COOKIE, COUNT_RECURSIVE);

        $input_vars = $post_vars + $get_vars + $cookie_vars;

        if($input_vars > $max_input_vars) {
            alert('������ ���۵� ������ ������ max_input_vars ������ Ů�ϴ�.\\n���۵� ���� �Ϻδ� ���ǵǾ� DB�� ��ϵ� �� �ֽ��ϴ�.\\n\\n������ �ذ��ϱ� ���ؼ��� ���� php.ini�� max_input_vars ���� �����Ͻʽÿ�.');
        }
    }
}

// HTML Ư������ ��ȯ htmlspecialchars
function htmlspecialchars2($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}

// date ���� ��ȯ
function conv_date_format($format, $date, $add='')
{
    if($add)
        $timestamp = strtotime($add, strtotime($date));
    else
        $timestamp = strtotime($date);

    return date($format, $timestamp);
}

// �˻��� Ư������ ����
function get_search_string($stx)
{
    $stx_pattern = array();
    $stx_pattern[] = '#\.*/+#';
    $stx_pattern[] = '#\\\*#';
    $stx_pattern[] = '#\.{2,}#';
    $stx_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';

    $stx_replace = array();
    $stx_replace[] = '';
    $stx_replace[] = '';
    $stx_replace[] = '.';
    $stx_replace[] = '';

    $stx = preg_replace($stx_pattern, $stx_replace, $stx);

    return $stx;
}

// XSS ���� �±� ����
function clean_xss_tags($str, $check_entities=0, $is_remove_tags=0, $cur_str_len=0)
{
    if( $is_remove_tags ){
        $str = strip_tags($str);
    }

    if( $cur_str_len ){
        $str = utf8_strcut($str, $cur_str_len, '');
    }

    $str_len = strlen($str);
    
    $i = 0;
    while($i <= $str_len){
        $result = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
        
        if( $check_entities ){
            $result = str_replace(array('&colon;', '&lpar;', '&rpar;', '&NewLine;', '&Tab;'), '', $result);
        }
        
        $result = preg_replace('#([^\p{L}]|^)(?:javascript|jar|applescript|vbscript|vbs|wscript|jscript|behavior|mocha|livescript|view-source)\s*:(?:.*?([/\\\;()\'">]|$))#ius',
                '$1$2', $result);

        if((string)$result === (string)$str) break;

        $str = $result;
        $i++;
    }

    return $str;
}

// XSS ��Ʈ����Ʈ �±� ����
function clean_xss_attributes($str)
{
    $xss_attributes_string = 'onAbort|onActivate|onAttribute|onAfterPrint|onAfterScriptExecute|onAfterUpdate|onAnimationCancel|onAnimationEnd|onAnimationIteration|onAnimationStart|onAriaRequest|onAutoComplete|onAutoCompleteError|onAuxClick|onBeforeActivate|onBeforeCopy|onBeforeCut|onBeforeDeactivate|onBeforeEditFocus|onBeforePaste|onBeforePrint|onBeforeScriptExecute|onBeforeUnload|onBeforeUpdate|onBegin|onBlur|onBounce|onCancel|onCanPlay|onCanPlayThrough|onCellChange|onChange|onClick|onClose|onCommand|onCompassNeedsCalibration|onContextMenu|onControlSelect|onCopy|onCueChange|onCut|onDataAvailable|onDataSetChanged|onDataSetComplete|onDblClick|onDeactivate|onDeviceLight|onDeviceMotion|onDeviceOrientation|onDeviceProximity|onDrag|onDragDrop|onDragEnd|onDragEnter|onDragLeave|onDragOver|onDragStart|onDrop|onDurationChange|onEmptied|onEnd|onEnded|onError|onErrorUpdate|onExit|onFilterChange|onFinish|onFocus|onFocusIn|onFocusOut|onFormChange|onFormInput|onFullScreenChange|onFullScreenError|onGotPointerCapture|onHashChange|onHelp|onInput|onInvalid|onKeyDown|onKeyPress|onKeyUp|onLanguageChange|onLayoutComplete|onLoad|onLoadedData|onLoadedMetaData|onLoadStart|onLoseCapture|onLostPointerCapture|onMediaComplete|onMediaError|onMessage|onMouseDown|onMouseEnter|onMouseLeave|onMouseMove|onMouseOut|onMouseOver|onMouseUp|onMouseWheel|onMove|onMoveEnd|onMoveStart|onMozFullScreenChange|onMozFullScreenError|onMozPointerLockChange|onMozPointerLockError|onMsContentZoom|onMsFullScreenChange|onMsFullScreenError|onMsGestureChange|onMsGestureDoubleTap|onMsGestureEnd|onMsGestureHold|onMsGestureStart|onMsGestureTap|onMsGotPointerCapture|onMsInertiaStart|onMsLostPointerCapture|onMsManipulationStateChanged|onMsPointerCancel|onMsPointerDown|onMsPointerEnter|onMsPointerLeave|onMsPointerMove|onMsPointerOut|onMsPointerOver|onMsPointerUp|onMsSiteModeJumpListItemRemoved|onMsThumbnailClick|onOffline|onOnline|onOutOfSync|onPage|onPageHide|onPageShow|onPaste|onPause|onPlay|onPlaying|onPointerCancel|onPointerDown|onPointerEnter|onPointerLeave|onPointerLockChange|onPointerLockError|onPointerMove|onPointerOut|onPointerOver|onPointerUp|onPopState|onProgress|onPropertyChange|onqt_error|onRateChange|onReadyStateChange|onReceived|onRepeat|onReset|onResize|onResizeEnd|onResizeStart|onResume|onReverse|onRowDelete|onRowEnter|onRowExit|onRowInserted|onRowsDelete|onRowsEnter|onRowsExit|onRowsInserted|onScroll|onSearch|onSeek|onSeeked|onSeeking|onSelect|onSelectionChange|onSelectStart|onStalled|onStorage|onStorageCommit|onStart|onStop|onShow|onSyncRestored|onSubmit|onSuspend|onSynchRestored|onTimeError|onTimeUpdate|onTimer|onTrackChange|onTransitionEnd|onToggle|onTouchCancel|onTouchEnd|onTouchLeave|onTouchMove|onTouchStart|onTransitionCancel|onTransitionEnd|onUnload|onURLFlip|onUserProximity|onVolumeChange|onWaiting|onWebKitAnimationEnd|onWebKitAnimationIteration|onWebKitAnimationStart|onWebKitFullScreenChange|onWebKitFullScreenError|onWebKitTransitionEnd|onWheel';
    
    do {
        $count = $temp_count = 0;

        $str = preg_replace(
            '/(.*)(?:' . $xss_attributes_string . ')(?:\s*=\s*)(?:\'(?:.*?)\'|"(?:.*?)")(.*)/ius',
            '$1-$2-$3-$4',
            $str,
            -1,
            $temp_count
        );
        $count += $temp_count;

        $str = preg_replace(
            '/(.*)(?:' . $xss_attributes_string . ')\s*=\s*(?:[^\s>]*)(.*)/ius',
            '$1$2',
            $str,
            -1,
            $temp_count
        );
        $count += $temp_count;

    } while ($count);

    return $str;
}

function clean_relative_paths($path){
    $path_len = strlen($path);
    
    $i = 0;
    while($i <= $path_len){
        $result = str_replace('../', '', str_replace('\\', '/', $path));

        if((string)$result === (string)$path) break;

        $path = $result;
        $i++;
    }

    return $path;
}

// unescape nl ���
function conv_unescape_nl($str)
{
    $search = array('\\r', '\r', '\\n', '\n');
    $replace = array('', '', "\n", "\n");

    return str_replace($search, $replace, $str);
}

// ȸ�� ����
function member_delete($mb_id)
{
    global $config;
    global $g5;

    $sql = " select mb_name, mb_nick, mb_ip, mb_recommend, mb_memo, mb_level from {$g5['member_table']} where mb_id= '".$mb_id."' ";
    $mb = sql_fetch($sql);

    // �̹� ������ ȸ���� ����
    if(preg_match('#^[0-9]{8}.*������#', $mb['mb_memo']))
        return;

    if ($mb['mb_recommend']) {
        $row = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where mb_id = '".addslashes($mb['mb_recommend'])."' ");
        if ($row['cnt'])
            insert_point($mb['mb_recommend'], $config['cf_recommend_point'] * (-1), $mb_id.'���� ȸ���ڷ� ������ ���� ��õ�� ����Ʈ ��ȯ', "@member", $mb['mb_recommend'], $mb_id.' ��õ�� ����');
    }

    // ȸ���ڷ�� ������ ���� �� ���̵�� �����Ͽ� �ٸ� ����� ������� ���ϵ��� �� : 061025
    $sql = " update {$g5['member_table']} set mb_password = '', mb_level = 1, mb_email = '', mb_homepage = '', mb_tel = '', mb_hp = '', mb_zip1 = '', mb_zip2 = '', mb_addr1 = '', mb_addr2 = '', mb_birth = '', mb_sex = '', mb_signature = '', mb_memo = '".date('Ymd', G5_SERVER_TIME)." ������\n".sql_real_escape_string($mb['mb_memo'])."' where mb_id = '{$mb_id}' ";

    sql_query($sql);

    // ����Ʈ ���̺��� ����
    sql_query(" delete from {$g5['point_table']} where mb_id = '$mb_id' ");

    // �׷����ٰ��� ����
    sql_query(" delete from {$g5['group_member_table']} where mb_id = '$mb_id' ");

    // ���� ����
    sql_query(" delete from {$g5['memo_table']} where me_recv_mb_id = '$mb_id' or me_send_mb_id = '$mb_id' ");

    // ��ũ�� ����
    sql_query(" delete from {$g5['scrap_table']} where mb_id = '$mb_id' ");

    // �������� ����
    sql_query(" delete from {$g5['auth_table']} where mb_id = '$mb_id' ");

    // �׷�������� ��� �׷�����ڸ� ��������
    sql_query(" update {$g5['group_table']} set gr_admin = '' where gr_admin = '$mb_id' ");

    // �Խ��ǰ������� ��� �Խ��ǰ����ڸ� ��������
    sql_query(" update {$g5['board_table']} set bo_admin = '' where bo_admin = '$mb_id' ");

    //�Ҽȷα��ο��� ���� �Ǵ� ����
    if(function_exists('social_member_link_delete')){
        social_member_link_delete($mb_id);
    }

    // ������ ����
    @unlink(G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif');

    // ������ �̹��� ����
    @unlink(G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.$mb_id.'.gif');

    run_event('member_delete_after', $mb_id);
}

// �̸��� �ּ� ����
function get_email_address($email)
{
    preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", $email, $matches);

    return isset($matches[0]) ? $matches[0] : '';
}

// ���ϸ��� Ư������ ����
function get_safe_filename($name)
{
    $pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
    $name = preg_replace($pattern, '', $name);

    return $name;
}

// ���ϸ� ġȯ
function replace_filename($name)
{
    @session_start();
    $ss_id = session_id();
    $usec = get_microtime();
    $file_path = pathinfo($name);
    $ext = $file_path['extension'];
    $return_filename = sha1($ss_id.$_SERVER['REMOTE_ADDR'].$usec); 
    if( $ext )
        $return_filename .= '.'.$ext;

    return $return_filename;
}

// �����ڵ� ���������
function get_icode_userinfo($id, $pass)
{
    $res = get_sock('http://www.icodekorea.com/res/userinfo.php?userid='.$id.'&userpw='.$pass, 2);
    $res = explode(';', $res);
    $userinfo = array(
        'code'      => $res[0], // ����ڵ�
        'coin'      => $res[1], // �� �ܾ� (�������� �ش�)
        'gpay'      => $res[2], // ���� �Ǽ� �� ������ ǥ�� (�������� �ش�)
        'payment'   => $res[3]  // ����� ǥ��, A:������, C:������
    );

    return $userinfo;
}

// �α�˻��� �Է�
function insert_popular($field, $str)
{
    global $g5;

    if(!in_array('mb_id', $field)) {
        $sql = " insert into {$g5['popular_table']} set pp_word = '{$str}', pp_date = '".G5_TIME_YMD."', pp_ip = '{$_SERVER['REMOTE_ADDR']}' ";
        sql_query($sql, FALSE);
    }
}

// ���ڿ� ��ȣȭ
function get_encrypt_string($str)
{
    if(defined('G5_STRING_ENCRYPT_FUNCTION') && G5_STRING_ENCRYPT_FUNCTION) {
        $encrypt = call_user_func(G5_STRING_ENCRYPT_FUNCTION, $str);
    } else {
        $encrypt = sql_password($str);
    }

    return $encrypt;
}

// ��й�ȣ ��
function check_password($pass, $hash)
{
    if(defined('G5_STRING_ENCRYPT_FUNCTION') && G5_STRING_ENCRYPT_FUNCTION === 'create_hash') {
        return validate_password($pass, $hash);
    }

    $password = get_encrypt_string($pass);

    return ($password === $hash);
}

// �α��� �н����� üũ
function login_password_check($mb, $pass, $hash)
{
    global $g5;

    $mb_id = isset($mb['mb_id']) ? $mb['mb_id'] : '';

    if(!$mb_id)
        return false;

    if(G5_STRING_ENCRYPT_FUNCTION === 'create_hash' && (strlen($hash) === G5_MYSQL_PASSWORD_LENGTH || strlen($hash) === 16)) {
        if( sql_password($pass) === $hash ){

            if( ! isset($mb['mb_password2']) ){
                $sql = "ALTER TABLE `{$g5['member_table']}` ADD `mb_password2` varchar(255) NOT NULL default '' AFTER `mb_password`";
                sql_query($sql);
            }
            
            $new_password = create_hash($pass);
            $sql = " update {$g5['member_table']} set mb_password = '$new_password', mb_password2 = '$hash' where mb_id = '$mb_id' ";
            sql_query($sql);
            return true;
        }
    }

    return check_password($pass, $hash);
}

// ������ host url ����
function check_url_host($url, $msg='', $return_url=G5_URL, $is_redirect=false)
{
    if(!$msg)
        $msg = 'url�� Ÿ �������� ������ �� �����ϴ�.';

    $p = @parse_url($url);
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $is_host_check = false;
    
    // url�� urlencode �� 2���̻��ϸ� parse_url ���� scheme�� host ���� �����ü� ���� ������� ������
    if ( $is_redirect && !isset($p['host']) && urldecode($url) != $url ){
        $i = 0;
        while($i <= 3){
            $url = urldecode($url);
            if( urldecode($url) == $url ) break;
            $i++;
        }

        if( urldecode($url) == $url ){
            $p = @parse_url($url);
        } else {
            $is_host_check = true;
        }
    }

    if(stripos($url, 'http:') !== false) {
        if(!isset($p['scheme']) || !$p['scheme'] || !isset($p['host']) || !$p['host'])
            alert('url ������ �ùٸ��� �ʽ��ϴ�.', $return_url);
    }

    //php 5.6.29 ���� ���������� parse_url ���װ� ������
    //php 7.0.1 ~ 7.0.5 ���������� parse_url ���װ� ������
    if ( $is_redirect && (isset($p['host']) && $p['host']) ) {
        $bool_ch = false;
        foreach( array('user','host') as $key) {
            if ( isset( $p[ $key ] ) && strpbrk( $p[ $key ], ':/?#@' ) ) {
                $bool_ch = true;
            }
        }
        if( $bool_ch ){
            $regex = '/https?\:\/\/'.$host.'/i';
            if( ! preg_match($regex, $url) ){
                $is_host_check = true;
            }
        }
    }

    if ((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host']) || $is_host_check) {
        //if ($p['host'].(isset($p['port']) ? ':'.$p['port'] : '') != $_SERVER['HTTP_HOST']) {
        if ( ($p['host'] != $host) || $is_host_check ) {
            echo '<script>'.PHP_EOL;
            echo 'alert("url�� Ÿ �������� ������ �� �����ϴ�.");'.PHP_EOL;
            echo 'document.location.href = "'.$return_url.'";'.PHP_EOL;
            echo '</script>'.PHP_EOL;
            echo '<noscript>'.PHP_EOL;
            echo '<p>'.$msg.'</p>'.PHP_EOL;
            echo '<p><a href="'.$return_url.'">���ư���</a></p>'.PHP_EOL;
            echo '</noscript>'.PHP_EOL;
            exit;
        }
    }
}

// QUERY STRING �� ���Ե� XSS �±� ����
function clean_query_string($query, $amp=true)
{
    $qstr = trim($query);

    parse_str($qstr, $out);

    if(is_array($out)) {
        $q = array();

        foreach($out as $key=>$val) {
            if(($key && is_array($key)) || ($val && is_array($val))){
                $q[$key] = $val;
                continue;
            }

            $key = strip_tags(trim($key));
            $val = trim($val);

            switch($key) {
                case 'wr_id':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'sca':
                    $val = clean_xss_tags($val);
                    $q[$key] = $val;
                    break;
                case 'sfl':
                    $val = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $val);
                    $q[$key] = $val;
                    break;
                case 'stx':
                    $val = get_search_string($val);
                    $q[$key] = $val;
                    break;
                case 'sst':
                    $val = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $val);
                    $q[$key] = $val;
                    break;
                case 'sod':
                    $val = preg_match("/^(asc|desc)$/i", $val) ? $val : '';
                    $q[$key] = $val;
                    break;
                case 'sop':
                    $val = preg_match("/^(or|and)$/i", $val) ? $val : '';
                    $q[$key] = $val;
                    break;
                case 'spt':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'page':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'w':
                    $val = substr($val, 0, 2);
                    $q[$key] = $val;
                    break;
                case 'bo_table':
                    $val = preg_replace('/[^a-z0-9_]/i', '', $val);
                    $val = substr($val, 0, 20);
                    $q[$key] = $val;
                    break;
                case 'gr_id':
                    $val = preg_replace('/[^a-z0-9_]/i', '', $val);
                    $q[$key] = $val;
                    break;
                default:
                    $val = clean_xss_tags($val);
                    $q[$key] = $val;
                    break;
            }
        }

        if($amp)
            $sep = '&amp;';
        else
            $sep ='&';

        $str = http_build_query($q, '', $sep);
    } else {
        $str = clean_xss_tags($qstr);
    }

    return $str;
}

function get_params_merge_url($params, $url=''){
    $str_url = $url ? $url : G5_URL;
    $p = @parse_url($str_url);
    $href = (isset($p['scheme']) ? "{$p['scheme']}://" : '')
        . (isset($p['user']) ? $p['user']
        . (isset($p['pass']) ? ":{$p['pass']}" : '').'@' : '')
        . (isset($p['host']) ? $p['host'] : '')
        . ((isset($p['path']) && $url) ? $p['path'] : '')
        . ((isset($p['port']) && $p['port']) ? ":{$p['port']}" : '');
    
    $ori_params = '';
    if( $url ){
        $ori_params = !empty($p['query']) ? $p['query'] : '';
    } else if( $tmp = explode('?', $_SERVER['REQUEST_URI']) ){
        if( isset($tmp[0]) && $tmp[0] ) {
            $href .= $tmp[0];
            $ori_params = isset($tmp[1]) ? $tmp[1] : '';
        }
        if( $freg = strstr($ori_params, '#') ) {
            $p['fragment'] = preg_replace('/^#/', '', $freg);
        }
    }
    
    $q = array();
    if( $ori_params ){
        parse_str( $ori_params, $q );
    }
    
    if( is_array($params) && $params ){
        $q = array_merge($q, $params);
    }

    $query = http_build_query($q, '', '&amp;');
    $qc = (strpos( $href, '?' ) !== false) ? '&amp;' : '?';
    $href .= $qc.$query.(isset($p['fragment']) ? "#{$p['fragment']}" : '');

    return $href;
}

function get_device_change_url()
{
    $q = array();
    $device = (G5_IS_MOBILE ? 'pc' : 'mobile');
    $q['device'] = $device;

    return get_params_merge_url($q);
}

// ��Ų path
function get_skin_path($dir, $skin)
{
    global $config;

    if(preg_match('#^theme/(.+)$#', $skin, $match)) { // �׸��� ���Ե� ��Ų�̶��
        $theme_path = '';
        $cf_theme = trim($config['cf_theme']);

        $theme_path = G5_PATH.'/'.G5_THEME_DIR.'/'.$cf_theme;
        if(G5_IS_MOBILE) {
            $skin_path = $theme_path.'/'.G5_MOBILE_DIR.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$match[1];
            if(!is_dir($skin_path))
                $skin_path = $theme_path.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$match[1];
        } else {
            $skin_path = $theme_path.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$match[1];
        }
    } else {
        if(G5_IS_MOBILE)
            $skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$skin;
        else
            $skin_path = G5_SKIN_PATH.'/'.$dir.'/'.$skin;
    }

    return $skin_path;
}

// ��Ų url
function get_skin_url($dir, $skin)
{
    $skin_path = get_skin_path($dir, $skin);

    return str_replace(G5_PATH, G5_URL, $skin_path);
}

// �߽Ź�ȣ ��ȿ�� üũ
function check_vaild_callback($callback){
   $_callback = preg_replace('/[^0-9]/','', $callback);

   /**
   * 1588 �ν����ϸ� ��8�ڸ��ε� 7�ڸ��� ����
   * 02 �ν����ϸ� ��9�ڸ� �Ǵ� 10�ڸ��ε� 11�ڸ�������
   * 1366�� ����ü�� ����ȣ�̱⿡ �ٸ��� ������ ����
   * 030���� �����ϸ� ��10�ڸ� �Ǵ� 11�ڸ��ε� 9�ڸ�������
   */

   if( substr($_callback,0,4) == '1588') if( strlen($_callback) != 8) return false;
   if( substr($_callback,0,2) == '02')   if( strlen($_callback) != 9  && strlen($_callback) != 10 ) return false;
   if( substr($_callback,0,3) == '030')  if( strlen($_callback) != 10 && strlen($_callback) != 11 ) return false;

   if( !preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/",$_callback) &&
       !preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/",$_callback) ){
             return false;
   } else if( preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/",$_callback )) {
             return false;
   } else {
             return true;
   }
}

// ���ڿ� �Ϻ�ȣȭ
class str_encrypt
{
    var $salt;
    var $lenght;

    function __construct($salt='')
    {
        if(!$salt)
            $this->salt = md5(preg_replace('/[^0-9A-Za-z]/', substr(G5_MYSQL_USER, -1), $_SERVER['SERVER_SOFTWARE'].$_SERVER['DOCUMENT_ROOT']));
        else
            $this->salt = $salt;

        $this->length = strlen($this->salt);
    }

    function encrypt($str)
    {
        $length = strlen($str);
        $result = '';

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return strtr(base64_encode($result) , '+/=', '._-');
    }

    function decrypt($str) {
        $result = '';
        $str    = base64_decode(strtr($str, '._-', '+/='));
        $length = strlen($str);

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }
}

// �ҹ������� ������ ��ū�� �����ϸ鼭 ��ū���� ����
function get_write_token($bo_table)
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_write_'.$bo_table.'_token', $token);

    return $token;
}


// POST�� �Ѿ�� ��ū�� ���ǿ� ����� ��ū ��
function check_write_token($bo_table)
{
    if(!$bo_table)
        alert('�ùٸ� ������� �̿��� �ֽʽÿ�.', G5_URL);

    $token = get_session('ss_write_'.$bo_table.'_token');
    set_session('ss_write_'.$bo_table.'_token', '');

    if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
        alert('�ùٸ� ������� �̿��� �ֽʽÿ�.', G5_URL);

    return true;
}

function get_member_profile_img($mb_id='', $width='', $height='', $alt='profile_image', $title=''){
    global $member;

    static $no_profile_cache = '';
    static $member_cache = array();
    
    $src = '';

    if( $mb_id ){
        if( isset($member_cache[$mb_id]) ){
            $src = $member_cache[$mb_id];
        } else {
            $member_img = G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.get_mb_icon_name($mb_id).'.gif';
            if (is_file($member_img)) {
                if(defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) {
                    $member_img .= '?'.filemtime($member_img);
                }
                $member_cache[$mb_id] = $src = str_replace(G5_DATA_PATH, G5_DATA_URL, $member_img);
            }
        }
    }

    if( !$src ){
        if( !empty($no_profile_cache) ){
            $src = $no_profile_cache;
        } else {
            // ������ �̹����� ������ �⺻ �̹���
            $no_profile_img = (defined('G5_THEME_NO_PROFILE_IMG') && G5_THEME_NO_PROFILE_IMG) ? G5_THEME_NO_PROFILE_IMG : G5_NO_PROFILE_IMG;
            $tmp = array();
            preg_match( '/src="([^"]*)"/i', $no_profile_img, $tmp );
            $no_profile_cache = $src = isset($tmp[1]) ? $tmp[1] : G5_IMG_URL.'/no_profile.gif';
        }
    }

    if( $src ){
        $attributes = array('src'=>$src, 'width'=>$width, 'height'=>$height, 'alt'=>$alt, 'title'=>$title);

        $output = '<img';
        foreach ($attributes as $name => $value) {
            if (!empty($value)) {
                $output .= sprintf(' %s="%s"', $name, $value);
            }
        }
        $output .= '>';

        return $output;
    }

    return '';
}

function get_head_title($title){
    global $g5;

    if( isset($g5['board_title']) && $g5['board_title'] ){
        $title = strip_tags($g5['board_title']);
    }

    return $title;
}

function is_sms_send($is_type=''){
    global $config;
    
    $is_sms_send = false;
    
    // ��ūŰ�� ����Ѵٸ�
    if(isset($config['cf_icode_token_key']) && $config['cf_icode_token_key']){
        $is_sms_send = true;
    } else if($config['cf_icode_id'] && $config['cf_icode_pw']) {
        // �������� ��� �ܾ��� �ִ��� üũ

        $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);

        if($userinfo['code'] == 0) {
            if($userinfo['payment'] == 'C') { // ������
                $is_sms_send = true;
            } else {
                $minimum_coin = 100;
                if(defined('G5_ICODE_COIN'))
                    $minimum_coin = intval(G5_ICODE_COIN);

                if((int)$userinfo['coin'] >= $minimum_coin)
                    $is_sms_send = true;
            }
        }
    }

    return $is_sms_send;
}

function is_use_email_certify(){
    global $config;

    if( $config['cf_use_email_certify'] && function_exists('social_is_login_check') ){
        if( $config['cf_social_login_use'] && (get_session('ss_social_provider') || social_is_login_check()) ){      //�Ҽ� �α����� ����Ѵٸ�
            $tmp = (defined('G5_SOCIAL_CERTIFY_MAIL') && G5_SOCIAL_CERTIFY_MAIL) ? 1 : 0;
            return $tmp;
        }
    }

    return $config['cf_use_email_certify'];
}

function safe_replace_regex($str, $str_case=''){

    if($str_case === 'time'){
        return preg_replace('/[^0-9 _\-:]/i', '', $str);
    }

    return preg_replace('/[^0-9a-z_\-]/i', '', $str);
}

function get_real_client_ip(){

    $real_ip = $_SERVER['REMOTE_ADDR'];

    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $_SERVER['HTTP_X_FORWARDED_FOR']) ){
        $real_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return preg_replace('/[^0-9.]/', '', $real_ip);
}

function check_mail_bot($ip=''){

    //�����Ǹ� üũ�Ͽ� ���� ũ�Ѹ��� �����մϴ�.
    $check_ips = array('211.249.40.');
    $bot_message = 'bot ���� �ǴܵǾ� �����մϴ�.';
    
    if($ip){
        foreach( $check_ips as $c_ip ){
            if( preg_match('/^'.preg_quote($c_ip).'/', $ip) ) {
                die($bot_message);
            }
        }
    }

    // user agent�� üũ�Ͽ� ���� ũ�Ѹ��� �����մϴ�.
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    if ($user_agent === 'Carbon' || strpos($user_agent, 'BingPreview') !== false || strpos($user_agent, 'Slackbot') !== false) { 
        die($bot_message);
    } 
}

function get_call_func_cache($func, $args=array()){
    
    static $cache = array();

    $key = md5(serialize($args));

    if( isset($cache[$func]) && isset($cache[$func][$key]) ){
        return $cache[$func][$key];
    }

    $result = null;

    try{
        $cache[$func][$key] = $result = call_user_func_array($func, $args);
    } catch (Exception $e) {
        return null;
    }
    
    return $result;
}

// include �ϴ� ��ο� data file ��γ� �������� ���� ��ΰ� �ִ��� üũ�մϴ�.
function is_include_path_check($path='', $is_input='')
{
    if( $path ){

        if( strlen($path) > 255 ){
            return false;
        }

        if ($is_input){
            // ������ @jtjisgod <jtjisgod@gmail.com> �߰�
            // ���� ���� : rar wrapper ����

            if( stripos($path, 'rar:') !== false || stripos($path, 'php:') !== false || stripos($path, 'zlib:') !== false || stripos($path, 'bzip2:') !== false || stripos($path, 'zip:') !== false || stripos($path, 'data:') !== false || stripos($path, 'phar:') !== false || stripos($path, 'file:') !== false || stripos($path, '://') !== false ){
                return false;
            }

            $replace_path = str_replace('\\', '/', $path);
            $slash_count = substr_count(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/');
            $peer_count = substr_count($replace_path, '../');

            if ( $peer_count && $peer_count > $slash_count ){
                return false;
            }

            try {
                // whether $path is unix or not
                $unipath = strlen($path)==0 || substr($path, 0, 1) != '/';
                $unc = substr($path,0,2)=='\\\\'?true:false;
                // attempts to detect if path is relative in which case, add cwd
                if(strpos($path,':') === false && $unipath && !$unc){
                    $path=getcwd().DIRECTORY_SEPARATOR.$path;
                    if(substr($path, 0, 1) == '/'){
                        $unipath = false;
                    }
                }

                // resolve path parts (single dot, double dot and double delimiters)
                $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
                $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
                $absolutes = array();
                foreach ($parts as $part) {
                    if ('.'  == $part){
                        continue;
                    }
                    if ('..' == $part) {
                        array_pop($absolutes);
                    } else {
                        $absolutes[] = $part;
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, $absolutes);
                // resolve any symlinks
                // put initial separator that could have been lost
                $path = !$unipath ? '/'.$path : $path;
                $path = $unc ? '\\\\'.$path : $path;
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                return false;
            }

            if( preg_match('/\/data\/(file|editor|qa|cache|member|member_image|session|tmp)\/[A-Za-z0-9_]{1,20}\//i', $replace_path) ){
                return false;
            }
            if( preg_match('/'.G5_PLUGIN_DIR.'\//i', $replace_path) && (preg_match('/'.G5_OKNAME_DIR.'\//i', $replace_path) || preg_match('/'.G5_KCPCERT_DIR.'\//i', $replace_path) || preg_match('/'.G5_LGXPAY_DIR.'\//i', $replace_path)) || (preg_match('/search\.skin\.php/i', $replace_path) ) ){
                return false;
            }
            if( substr_count($replace_path, './') > 5 ){
                return false;
            }
            if( defined('G5_SHOP_DIR') && preg_match('/'.G5_SHOP_DIR.'\//i', $replace_path) && preg_match('/kcp\//i', $replace_path) ){
                return false;
            }
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if($extension && preg_match('/(jpg|jpeg|png|gif|bmp|conf|php\-x)$/i', $extension)) {
            return false;
        }
    }

    return true;
}

function filter_input_include_path($path){
    return str_replace('//', '/', $path);
}

function option_array_checked($option, $arr=array()){
    $checked = '';

    if( !is_array($arr) ){
        $arr = explode(',', $arr);
    }

    if ( !empty($arr) && in_array($option, (array) $arr) ){
        $checked = 'checked="checked"';
    }

    return $checked;
}

function goThere($msg='', $url='') {
		echo "<script>";
		if($msg) echo 'alert("'.$msg.'");';
		if($url) echo 'location.replace("'.$url.'");';
		else echo 'history.go(-1);';
		echo "</script>";
}
?>