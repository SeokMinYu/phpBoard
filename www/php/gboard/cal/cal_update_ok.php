<?php
include_once("../common.php");

$caldate = $_POST['caldate'];
$content = $_POST['content'];

$sql = "insert into g5_cal_test (caldate,content) values ('".$caldate."','".$content."')";
sql_query($sql);

goThere("등록되었습니다.","calendar.php");
?>