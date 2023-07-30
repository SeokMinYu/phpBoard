<?php

	header('Content-Type : text/html; charset=utf-8');
	include "boardDB.php";

	$bno = $_GET['idx'];
	$pw = $_POST['pw'];

	$sql = mq("select pw from board where idx ='".$bno."'");
	$filesql = mq("select pw from upload where idx ='".$bno."'");
	$board = $sql->fetch_array();
	$file = $_GET['filename'];

	if($pw == $board['pw']){
		$sql = mq("delete from board where idx='$bno';");
		$filesql = mq("delete from upload where idx='$bno';");

		unlink($file);
	}
	else{
		echo "<script>
		alert('비밀번호가 틀립니다.');
		history.back();</script>";
    exit;
	}

?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=list.php" />