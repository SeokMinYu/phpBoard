<?php

	include "boardDB.php";
	include "logindex.php";

	$idx = $_REQUEST['replyidx'];


	$delok = "del";
	$delsql = mysqli_query($db,"update reply2 set deletetime=now() ,replydelete='{$delok}' where replyidx='".$idx."'");

	echo "<script>alert('댓글이 삭제되었습니다.'); history.back();</script>";
?>