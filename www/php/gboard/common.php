<?php

define("__CASTLE_PHP_VERSION_BASE_DIR__", $_SERVER['DOCUMENT_ROOT']."/php/gboard/castle-gaya");
include(__CASTLE_PHP_VERSION_BASE_DIR__ . "/castle_referee.php");

/*******************************************************************************
** ���� ����, ���, �ڵ�
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// ���ȼ����̳� �������� �޶� ��Ű�� ���ϵ��� ����
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!defined('G5_SET_TIME_LIMIT')) define('G5_SET_TIME_LIMIT', 0);
@set_time_limit(G5_SET_TIME_LIMIT);

if( version_compare( PHP_VERSION, '5.2.17' , '<' ) ){
    die(sprintf('PHP 5.2.17 or higher required. Your PHP version is %s', PHP_VERSION));
}

//==========================================================================================================================
// extract($_GET); ������� ���� page.php?_POST[var1]=data1&_POST[var2]=data2 �� ���� �ڵ尡 _POST ������ ���Ǵ� ���� ����
// 081029 : letsgolee �Բ��� ���� �ּ̽��ϴ�.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET ���� ����� ���������� �ִٸ� unset() ��Ŵ
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================


function g5_path()
{
    $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__))); 
    $result['path'] = str_replace('\\', '/', $chroot.dirname(__FILE__)); 
    $server_script_name = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_NAME'])); 
    $server_script_filename = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'])); 
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $server_script_name); 
    $document_root = str_replace($tilde_remove, '', $server_script_filename); 
    $pattern = '/.*?' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']); 
    $port = ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT']; 
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://'; 
    $user = str_replace(preg_replace($pattern, '', $server_script_filename), '', $server_script_name); 
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']; 
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host)) 
        $host = preg_replace('/:[0-9]+$/', '', $host); 
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host); 
    $result['url'] = $http.$host.$port.$user.$root; 
    return $result;
}

$g5_path = g5_path();

include_once($g5_path['path'].'/config.php');   // ���� ����

unset($g5_path);

// IIS ���� SERVER_ADDR ���������� ���ٸ�
if(! isset($_SERVER['SERVER_ADDR'])) {
    $_SERVER['SERVER_ADDR'] = isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : '';
}

// multi-dimensional array�� ��������� �Լ�����
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}


// SQL Injection ���� ���ڿ� ���͸�
function sql_escape_string($str)
{
    if(defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
        $pattern = G5_ESCAPE_PATTERN;
        $replace = G5_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}


//==============================================================================
// SQL Injection ������ ���� ��ȣ�� ���� sql_escape_string() ����
//------------------------------------------------------------------------------
// magic_quotes_gpc �� ���� backslashes ����
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string ����
$_POST    = array_map_deep(G5_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(G5_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(G5_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(G5_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================


// PHP 4.1.0 ���� ������
// php.ini �� register_globals=off �� ���
@extract($_GET);
@extract($_POST);
@extract($_SERVER);


// �ϵ������ �˷��ֽ� ���Ȱ��� ���� ����
// $member �� ���� ���� �ѱ� �� ����
$config = array();
$member = array('mb_id'=>'', 'mb_level'=> 1, 'mb_name'=> '', 'mb_point'=> 0, 'mb_certify'=>'', 'mb_email'=>'', 'mb_open'=>'', 'mb_homepage'=>'', 'mb_tel'=>'', 'mb_hp'=>'', 'mb_zip1'=>'', 'mb_zip2'=>'', 'mb_addr1'=>'', 'mb_addr2'=>'', 'mb_addr3'=>'', 'mb_addr_jibeon'=>'', 'mb_signature'=>'', 'mb_profile'=>'');
$board  = array('bo_table'=>'', 'bo_skin'=>'', 'bo_mobile_skin'=>'', 'bo_upload_count' => 0, 'bo_use_dhtml_editor'=>'', 'bo_subject'=>'', 'bo_image_width'=>0);
$group  = array('gr_device'=>'', 'gr_subject'=>'');
$g5     = array();
if( version_compare( phpversion(), '8.0.0', '>=' ) ) { $g5 = array('title'=>''); }
$qaconfig = array();
$g5_debug = array('php'=>array(),'sql'=>array());

include_once(G5_LIB_PATH.'/hook.lib.php');    // hook �Լ� ����
include_once(G5_LIB_PATH.'/get_data.lib.php');    // ����Ÿ �������� �Լ� ����
include_once(G5_LIB_PATH.'/cache.lib.php');     // cache �Լ� �� object cache class ����
include_once(G5_LIB_PATH.'/uri.lib.php');    // URL �Լ� ����

$g5_object = new G5_object_cache();

//==============================================================================
// ����
//------------------------------------------------------------------------------
$dbconfig_file = G5_DATA_PATH.'/'.G5_DBCONFIG_FILE;
if (file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
    include_once(G5_LIB_PATH.'/common.lib.php');    // ���� ���̺귯��

    $connect_db = sql_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(G5_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // mysql connect resource $g5 �迭�� ���� - ������δ� ����
    $g5['connect_db'] = $connect_db;

    sql_set_charset(G5_DB_CHARSET, $connect_db);
    if(defined('G5_MYSQL_SET_MODE') && G5_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if (defined('G5_TIMEZONE')) sql_query(" set time_zone = '".G5_TIMEZONE."'");
} else {
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>����! <?php echo G5_VERSION ?> ��ġ�ϱ�</title>
<link rel="stylesheet" href="install/install.css">
</head>
<body>

<div id="ins_bar">
    <span id="bar_img">GNUBOARD5</span>
    <span id="bar_txt">Message</span>
</div>
<h1>�״�����5�� ���� ��ġ���ֽʽÿ�.</h1>
<div class="ins_inner">
    <p>���� ������ ã�� �� �����ϴ�.</p>
    <ul>
        <li><strong><?php echo G5_DATA_DIR.'/'.G5_DBCONFIG_FILE ?></strong></li>
    </ul>
    <p>�״����� ��ġ �� �ٽ� �����Ͻñ� �ٶ��ϴ�.</p>
    <div class="inner_btn">
        <a href="<?php echo G5_URL; ?>/install/"><?php echo G5_VERSION ?> ��ġ�ϱ�</a>
    </div>
</div>
<div id="ins_ft">
    <strong>GNUBOARD5</strong>
    <p>GPL! OPEN SOURCE GNUBOARD</p>
</div>

</body>
</html>

<?php
    exit;
}
//==============================================================================


//==============================================================================
// SESSION ����
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0);    // PHPSESSID�� �ڵ����� �ѱ��� ����
@ini_set("url_rewriter.tags",""); // ��ũ�� PHPSESSID�� ����ٴϴ°��� ����ȭ�� (�ض��Բ��� �˷��ּ̽��ϴ�.)

session_save_path(G5_SESSION_PATH);

if (isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // ���� ĳ�� �����ð� (��)
ini_set("session.gc_maxlifetime", 10800); // session data�� garbage collection ���� �Ⱓ�� ���� (��)
ini_set("session.gc_probability", 1); // session.gc_probability�� session.gc_divisor�� �����Ͽ� gc(������ ����) ��ƾ�� ���� Ȯ���� �����մϴ�. �⺻���� 1�Դϴ�. �ڼ��� ������ session.gc_divisor�� �����Ͻʽÿ�.
ini_set("session.gc_divisor", 100); // session.gc_divisor�� session.gc_probability�� �����Ͽ� �� ���� �ʱ�ȭ �ÿ� gc(������ ����) ���μ����� ������ Ȯ���� �����մϴ�. Ȯ���� gc_probability/gc_divisor�� ����Ͽ� ����մϴ�. ��, 1/100�� �� ��û�ÿ� GC ���μ����� ������ Ȯ���� 1%�Դϴ�. session.gc_divisor�� �⺻���� 100�Դϴ�.

session_set_cookie_params(0, '/');
ini_set("session.cookie_domain", G5_COOKIE_DOMAIN);

//==============================================================================
// ���� ����
//------------------------------------------------------------------------------
// �⺻ȯ�漳��
// �⺻������ ����ϴ� �ʵ常 ���� �� ��Ȳ�� ���� �ʵ带 �߰��� ����
$config = get_config(true);

// �������� �Ǵ� ���θ� ���ÿ��� secure; SameSite=None �� �����մϴ�.
if( $config['cf_cert_use'] || (defined('G5_YOUNGCART_VER') && G5_YOUNGCART_VER) ) {
    // Chrome 80 �������� �Ʒ� �̽� ����
    // https://developers-kr.googleblog.com/2020/01/developers-get-ready-for-new.html?fbclid=IwAR0wnJFGd6Fg9_WIbQPK3_FxSSpFLqDCr9bjicXdzy--CCLJhJgC9pJe5ss
    if(!function_exists('session_start_samesite')) {
        function session_start_samesite($options = array())
        {
            global $g5;

            $res = @session_start($options);

            // IE ������ �Ǵ� ���������� �Ǵ� IOS ����ϰ� httpȯ�濡���� secure; SameSite=None�� �������� �ʽ��ϴ�.
            if( preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/(iPhone|iPod|iPad).*AppleWebKit.*Safari/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) || ! (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ){
                return $res;
            }

            $headers = headers_list();
            krsort($headers);
            foreach ($headers as $header) {
                if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
                $header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
                header($header, false);
                $g5['session_cookie_samesite'] = 'none';
                break;
            }
            return $res;
        }
    }

    session_start_samesite();
} else {
    @session_start();
}
//==============================================================================

define('G5_HTTP_BBS_URL',  https_url(G5_BBS_DIR, false));
define('G5_HTTPS_BBS_URL', https_url(G5_BBS_DIR, true));

define('G5_CAPTCHA_DIR',    !empty($config['cf_captcha']) ? $config['cf_captcha'] : 'kcaptcha');
define('G5_CAPTCHA_URL',    G5_PLUGIN_URL.'/'.G5_CAPTCHA_DIR);
define('G5_CAPTCHA_PATH',   G5_PLUGIN_PATH.'/'.G5_CAPTCHA_DIR);

// 4.00.03 : [���Ȱ���] PHPSESSID �� Ʋ���� �α׾ƿ��Ѵ�.
if (isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(G5_BBS_URL.'/logout.php');

// QUERY_STRING
$qstr = '';

if (isset($_REQUEST['sca']))  {
    $sca = clean_xss_tags(trim($_REQUEST['sca']));
    if ($sca) {
        $sca = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $sca);
        $qstr .= '&amp;sca=' . urlencode($sca);
    }
} else {
    $sca = '';
}

if (isset($_REQUEST['sfl']))  {
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl);
    if ($sfl)
        $qstr .= '&amp;sfl=' . urlencode($sfl); // search field (�˻� �ʵ�)
} else {
    $sfl = '';
}


if (isset($_REQUEST['stx']))  { // search text (�˻���)
    $stx = get_search_string(trim($_REQUEST['stx']));
    if ($stx || $stx === '0')
        $qstr .= '&amp;stx=' . urlencode(cut_str($stx, 20, ''));
} else {
    $stx = '';
}

if (isset($_REQUEST['sst']))  {
    $sst = trim($_REQUEST['sst']);
    $sst = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sst);
    if ($sst)
        $qstr .= '&amp;sst=' . urlencode($sst); // search sort (�˻� ���� �ʵ�)
} else {
    $sst = '';
}

if (isset($_REQUEST['sod']))  { // search order (�˻� ����, ��������)
    $sod = preg_match("/^(asc|desc)$/i", $sod) ? $sod : '';
    if ($sod)
        $qstr .= '&amp;sod=' . urlencode($sod);
} else {
    $sod = '';
}

if (isset($_REQUEST['sop']))  { // search operator (�˻� or, and ���۷�����)
    $sop = preg_match("/^(or|and)$/i", $sop) ? $sop : '';
    if ($sop)
        $qstr .= '&amp;sop=' . urlencode($sop);
} else {
    $sop = '';
}

if (isset($_REQUEST['spt']))  { // search part (�˻� ��Ʈ[����])
    $spt = (int)$spt;
    if ($spt)
        $qstr .= '&amp;spt=' . urlencode($spt);
} else {
    $spt = '';
}

if (isset($_REQUEST['page'])) { // ����Ʈ ������
    $page = (int)$_REQUEST['page'];
    if ($page)
        $qstr .= '&amp;page=' . urlencode($page);
} else {
    $page = '';
}

if (isset($_REQUEST['w'])) {
    $w = substr($w, 0, 2);
} else {
    $w = '';
}

if (isset($_REQUEST['wr_id'])) {
    $wr_id = (int)$_REQUEST['wr_id'];
} else {
    $wr_id = 0;
}

if (isset($_REQUEST['bo_table']) && ! is_array($_REQUEST['bo_table'])) {
    $bo_table = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['bo_table']));
    $bo_table = substr($bo_table, 0, 20);
} else {
    $bo_table = '';
}

// URL ENCODING
if (isset($_REQUEST['url'])) {
    $url = strip_tags(trim($_REQUEST['url']));
    $urlencode = urlencode($url);
} else {
    $url = '';
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
    if (G5_DOMAIN) {
        $p = @parse_url(G5_DOMAIN);
        $urlencode = G5_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path'])."/", "", $urlencode));
    }
}

if (isset($_REQUEST['gr_id'])) {
    if (!is_array($_REQUEST['gr_id'])) {
        $gr_id = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['gr_id']));
    }
} else {
    $gr_id = '';
}
//===================================


// �ڵ��α��� �κп��� ù�α��ο� ����Ʈ �ο��ϴ����� �α������϶��� �����ϸ鼭 �ڵ嵵 ���� �����Ͽ����ϴ�.
if (isset($_SESSION['ss_mb_id']) && $_SESSION['ss_mb_id']) { // �α������̶��
    $member = get_member($_SESSION['ss_mb_id']);

    // ���ܵ� ȸ���̸� ss_mb_id �ʱ�ȭ
    if($member['mb_intercept_date'] && $member['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
        set_session('ss_mb_id', '');
        $member = array();
    } else {
        // ���� ó�� �α��� �̶��
        if (substr($member['mb_today_login'], 0, 10) != G5_TIME_YMD) {
            // ù �α��� ����Ʈ ����
            insert_point($member['mb_id'], $config['cf_login_point'], G5_TIME_YMD.' ù�α���', '@login', $member['mb_id'], G5_TIME_YMD);

            // ������ �α����� �� ���� ������ ������ �α����� ���� ����
            // �ش� ȸ���� �����Ͻÿ� IP �� ����
            $sql = " update {$g5['member_table']} set mb_today_login = '".G5_TIME_YMDHIS."', mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' where mb_id = '{$member['mb_id']}' ";
            sql_query($sql);
        }
    }
} else {
    // �ڵ��α��� ---------------------------------------
    // ȸ�����̵� ��Ű�� ����Ǿ� �ִٸ� (3.27)
    if ($tmp_mb_id = get_cookie('ck_mb_id')) {

        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // �ְ�����ڴ� �ڵ��α��� ����
        if (strtolower($tmp_mb_id) !== strtolower($config['cf_admin'])) {
            $sql = " select mb_password, mb_intercept_date, mb_leave_date, mb_email_certify from {$g5['member_table']} where mb_id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            if($row['mb_password']){
                $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
                // ��Ű�� ����� Ű�� ���ٸ�
                $tmp_key = get_cookie('ck_auto');
                if ($tmp_key === $key && $tmp_key) {
                    // ����, Ż�� �ƴϰ� ���������� ����̸鼭 ������ �޾Ҵٸ�
                    if ($row['mb_intercept_date'] == '' &&
                        $row['mb_leave_date'] == '' &&
                        (!$config['cf_use_email_certify'] || preg_match('/[1-9]/', $row['mb_email_certify'])) ) {
                        // ���ǿ� ȸ�����̵� �����Ͽ� �α������� ����
                        set_session('ss_mb_id', $tmp_mb_id);

                        // �������� �����
                        echo "<script type='text/javascript'> window.location.reload(); </script>";
                        exit;
                    }
                }
            }
            // $row �迭���� ����
            unset($row);
        }
    }
    // �ڵ��α��� end ---------------------------------------
}


$write = array();
$write_table = '';
if ($bo_table) {
    $board = get_board_db($bo_table, true);
    if (isset($board['bo_table']) && $board['bo_table']) {
        set_cookie("ck_bo_table", $board['bo_table'], 86400 * 1);
        $gr_id = $board['gr_id'];
        $write_table = $g5['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�

        if (isset($wr_id) && $wr_id) {
            $write = get_write($write_table, $wr_id);
        } else if (isset($wr_seo_title) && $wr_seo_title) {
            $write = get_content_by_field($write_table, 'bbs', 'wr_seo_title', generate_seo_title($wr_seo_title));
            if( isset($write['wr_id']) ){
                $wr_id = $write['wr_id'];
            }
        }
    }
    
    // �Խ��ǿ��� 
    if (isset($board['bo_select_editor']) && $board['bo_select_editor']){
        $config['cf_editor'] = $board['bo_select_editor'];
    }
}

if ($gr_id && !is_array($gr_id)) {
    $group = get_group($gr_id, true);
}

if ($config['cf_editor']) {
    define('G5_EDITOR_LIB', G5_EDITOR_PATH."/{$config['cf_editor']}/editor.lib.php");
} else {
    define('G5_EDITOR_LIB', G5_LIB_PATH."/editor.lib.php");
}

// ȸ��, ��ȸ�� ����
$is_member = $is_guest = false;
$is_admin = '';
if (isset($member['mb_id']) && $member['mb_id']) {
    $is_member = true;
    $is_admin = is_admin($member['mb_id']);
    $member['mb_dir'] = substr($member['mb_id'],0,2);
} else {
    $is_guest = true;
    $member['mb_id'] = '';
    $member['mb_level'] = 1; // ��ȸ���� ��� ȸ�������� ���� ���� ����
}


if ($is_admin != 'super') {
    // ���ٰ��� IP
    $cf_possible_ip = trim($config['cf_possible_ip']);
    if ($cf_possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $cf_possible_ip);
        for ($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if (empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
            $pat = "/^{$pattern[$i]}$/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if ($is_possible_ip)
                break;
        }
        if (!$is_possible_ip)
            die ("<meta charset=utf-8>������ �������� �ʽ��ϴ�.");
    }

    // �������� IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['cf_intercept_ip']));
    for ($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if ($is_intercept_ip)
            die ("<meta charset=utf-8>���� �Ұ��մϴ�.");
    }
}


// �׸����
if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true)
    $config['cf_theme'] = isset($_GET['theme']) ? trim($_GET['theme']) : '';

if(isset($config['cf_theme']) && trim($config['cf_theme'])) {
    $theme_path = G5_PATH.'/'.G5_THEME_DIR.'/'.$config['cf_theme'];
    if(is_dir($theme_path)) {
        define('G5_THEME_PATH',        $theme_path);
        define('G5_THEME_URL',         G5_URL.'/'.G5_THEME_DIR.'/'.$config['cf_theme']);
        define('G5_THEME_MOBILE_PATH', $theme_path.'/'.G5_MOBILE_DIR);
        define('G5_THEME_LIB_PATH',    $theme_path.'/'.G5_LIB_DIR);
        define('G5_THEME_CSS_URL',     G5_THEME_URL.'/'.G5_CSS_DIR);
        define('G5_THEME_IMG_URL',     G5_THEME_URL.'/'.G5_IMG_DIR);
        define('G5_THEME_JS_URL',      G5_THEME_URL.'/'.G5_JS_DIR);
    }
    unset($theme_path);
}


// �׸� ���� �ε�
if(defined('G5_THEME_PATH') && is_file(G5_THEME_PATH.'/theme.config.php'))
    include_once(G5_THEME_PATH.'/theme.config.php');

//=====================================================================================
// ����� ����
// �׸��� G5_THEME_DEVICE ������ ���� ����� ȭ�� ���ѵ�
// �׸��� ���� ������ ���� ��� config.php G5_SET_DEVICE ������ ���� ����� ȭ�� ���ѵ�
// pc ���� �� ����� ��⿡���� PCȭ�� ������
// mobile ���� �� PC������ �����ȭ�� ������
// both ���� �� ���� ��⿡ ���� ȭ�� ������
//-------------------------------------------------------------------------------------
$is_mobile = false;
$set_device = true;

if(defined('G5_THEME_DEVICE') && G5_THEME_DEVICE != '') {
    switch(G5_THEME_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}

if(defined('G5_SET_DEVICE') && $set_device) {
    switch(G5_SET_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}
//==============================================================================

//==============================================================================
// Mobile ����� ����
// ��Ű�� ����� ���� ������̶�� ������ ������� ����Ϸ� ����
// �׷��� �ʴٸ� �������� HTTP_USER_AGENT �� ���� ����� ����
// G5_MOBILE_AGENT : config.php ���� ����
//------------------------------------------------------------------------------
if (G5_USE_MOBILE && $set_device) {
    if (isset($_REQUEST['device']) && $_REQUEST['device']=='pc')
        $is_mobile = false;
    else if (isset($_REQUEST['device']) && $_REQUEST['device']=='mobile')
        $is_mobile = true;
    else if (isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if (is_mobile())
        $is_mobile = true;
} else {
    $set_device = false;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('G5_IS_MOBILE', $is_mobile);
define('G5_DEVICE_BUTTON_DISPLAY', $set_device);
if (G5_IS_MOBILE) {
    $g5['mobile_path'] = G5_PATH.'/'.G5_MOBILE_DIR;
}
//==============================================================================


//==============================================================================
// ��Ų���
//------------------------------------------------------------------------------
if (G5_IS_MOBILE) {
    $board_skin_path    = get_skin_path('board', $board['bo_mobile_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_mobile_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_mobile_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_mobile_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_mobile_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_mobile_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_mobile_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_mobile_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_mobile_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_mobile_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_mobile_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_mobile_faq_skin']);
} else {
    $board_skin_path    = get_skin_path('board', $board['bo_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_faq_skin']);
}
//==============================================================================


// �湮�ڼ��� ������ ����
include_once(G5_BBS_PATH.'/visit_insert.inc.php');


// ���� �Ⱓ�� ���� DB ������ ���� �� ����ȭ
include_once(G5_BBS_PATH.'/db_table.optimize.php');

// common.php ������ ������ �ʿ䰡 ������ Ȯ���մϴ�.
$extend_file = array();
$tmp = dir(G5_EXTEND_PATH);
while ($entry = $tmp->read()) {
    // php ���ϸ� include ��
    if (preg_match("/(\.php)$/i", $entry))
        $extend_file[] = $entry;
}

if(!empty($extend_file) && is_array($extend_file)) {
    natsort($extend_file);

    foreach($extend_file as $file) {
        include_once(G5_EXTEND_PATH.'/'.$file);
    }
    unset($file);
}
unset($extend_file);

ob_start();

// �ڹٽ�ũ��Ʈ���� go(-1) �Լ��� ���� ������ ������� �ش� ���� ��ܿ� ����ϸ�
// ĳ���� ������ ������. ���������� �������� ����
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

run_event('common_header');

$html_process = new html_process();
?>