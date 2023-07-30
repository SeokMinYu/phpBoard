<?php 
$exception['check_authorized'] = FALSE;
require_once "castle_admin_lib.php";
require_once "castle_admin_util.php";

/*
* File : test.php
*   castle changed test
 */
$success_cnt = 0;
$fail_cnt = 0;
$fail_list = array();
$test_cnt = 0;
function test_assert($functionName, $expected, $real) {
	global $success_cnt, $fail_cnt, $fail_list, $test_cnt;
	$test_cnt++;
	if ($expected == $real) {
		echo "<font color='blue'>$test_cnt : Success - $functionName </font><br>\n";
		$success_cnt++;
	} else {
		echo "<font color='red'>$test_cnt Fail $functionName :  [expected:".htmlspecialchars($expected).", real:".htmlspecialchars($real)."]</font><br>\n";
		$fail_cnt++;
		array_push($fail_list, $functionName);
	}
}
// ########## castle_admin_lib::castle_file_download ##########
$user_agent = "Internet Explorer MSIE 5.0 other data";
test_assert("castle_ie50_51_60_check", TRUE, castle_ie50_51_60_check($user_agent));
test_assert("castle_ie50_51_60_check", FALSE, castle_ie55_check($user_agent));

$user_agent = "Internet Explorer MSIE 5.1 other data";
test_assert("castle_ie50_51_60_check", TRUE, castle_ie50_51_60_check($user_agent));
test_assert("castle_ie50_51_60_check", FALSE, castle_ie55_check($user_agent));

$user_agent = "Internet Explorer msIe 6.0 other data";
test_assert("castle_ie50_51_60_check", TRUE, castle_ie50_51_60_check($user_agent));
test_assert("castle_ie50_51_60_check", FALSE, castle_ie55_check($user_agent));

$user_agent = "Internet Explorer msIe 5.5 other data";
test_assert("castle_ie50_51_60_check", FALSE, castle_ie50_51_60_check($user_agent));
test_assert("castle_ie50_51_60_check", TRUE, castle_ie55_check($user_agent));

$user_agent = "Internet Explorer msieT5.5 other data";
test_assert("castle_ie50_51_60_check", FALSE, castle_ie50_51_60_check($user_agent));
test_assert("castle_ie50_51_60_check", FALSE, castle_ie55_check($user_agent));

// ########## castle_admin_util::castle_util_delete_directory_traverse ##########
$path = "/data/../../unknown/../hosts";
test_assert("castle_util_delete_directory_traverse", "/data/unknown/hosts", castle_util_delete_directory_traverse($path));

$path = "/data/./unknown/../hosts";
test_assert("castle_util_delete_directory_traverse", "/data/unknown/hosts", castle_util_delete_directory_traverse($path));

$path = "/data/./unknown/hosts\..\..\..\data";
test_assert("castle_util_delete_directory_traverse", "/data/unknown/hosts\data", castle_util_delete_directory_traverse($path));

$path = ".\data/./unknown/hosts/.\data";
test_assert("castle_util_delete_directory_traverse", "data/unknown/hosts/data", castle_util_delete_directory_traverse($path));


$path = ".\data/./unknown/hosts.\\\\data";
test_assert("castle_util_delete_directory_traverse", "data/unknown/hosts\data", castle_util_delete_directory_traverse($path));

$path = ".\data/./unknown/hosts\\data";
test_assert("castle_util_delete_directory_traverse", "data/unknown/hosts\data", castle_util_delete_directory_traverse($path));


// ########## castle_admin_util::castle_util_htmlencode_lt & rt ##########
$msg = "test<!-- castle_test --> <second>";
test_assert("castle_util_htmlencode_lt", "test&lt;!-- castle_test --> &lt;second>", $msg=castle_util_htmlencode_lt($msg));
test_assert("castle_util_htmlencode_gt", "test&lt;!-- castle_test --&gt; &lt;second&gt;", castle_util_htmlencode_gt($msg));

$value = "test\n\rnewline\r\nnextline";
test_assert("castle_util_remove_crlf", "test\\n\\rnewline\\r\\nnextline", castle_util_remove_crlf($value));



echo "End of test<br>\n";
echo "SUCCESS : $success_cnt, FAIL : $fail_cnt";


?>