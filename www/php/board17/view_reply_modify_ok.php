<?php
	include "DBconnect.php";
	include "login_check.php";

	$bno = $_POST['idx'];
	$content = $_POST['rpmodi'];

	$sql = mysqli_query($connect,"select * from reply17 where replyidx='".$bno."'");
	$result = mysqli_fetch_array($sql);
	
	$sql2 = mysqli_query($connect,"UPDATE reply17 SET content='" .$content. "',modifytime=now() WHERE replyidx = '" . $bno . "'");


	echo "<script>alert('수정되었습니다.'); location.href='list_view.php?idx=".$result['list_num']."'; </script>";

?>