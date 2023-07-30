<?php 

//@UTF-8 castle_admin_title.php
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

if (isset($_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME']))
	$policy['title'] = $_CASTLE_POLICY['CONFIG']['ADMIN']['MODULE_NAME'];
else
	$policy['title'] = base64_encode(CASTLE_BASE_MODULE_NAME);

$print['title'] = base64_decode($policy['title']);

?>
    <title><?php echo $print['title']?></title>
