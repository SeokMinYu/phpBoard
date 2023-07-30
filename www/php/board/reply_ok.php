<?php
	include "boardDB.php";

    $bno = $_GET['idx'];
    $userpw = $_POST['dat_pw'];
	$time = date('Y-m-d H:i:s');
    
    $sql = mq("insert into reply(list_num,name,pw,content,reply_time) values('".$bno."','".$_POST['dat_user']."','".$userpw."','".$_POST['content']."','".$time."')");
	echo "<script>location.href='read.php?idx=$bno';</script>";
?>