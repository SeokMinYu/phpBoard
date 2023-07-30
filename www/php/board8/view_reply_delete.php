<?php

	include "DBconnect.php";
	include "login_check.php";
	include "levelCheak.php";

	$idx = $_REQUEST['replyidx'];

	$delok = "del";
	$delsql = mysqli_query($connect,"update reply2 set deletetime=now() ,replydelete='".$delok."' where replyidx='".$idx."'");

	echo "<script>alert('댓글이 삭제되었습니다.'); history.back();</script>";
?>