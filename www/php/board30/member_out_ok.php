<?php
	
	include "DBconnect.php";
	include "login_check.php";

	$pw = hash("sha256",$_POST['pw']);
	$seqno = $_POST['del'];

	if($pw == $userPw) 
	{
		$delok = "del";
		$timesql = mysqli_query($connect,"update member30 set deletetime=now(),memberdelete='".$delok."' where seqno='".$seqno."'");

		echo '<script>alert("탈퇴하였습니다."); location.href="logout.php"; </script>';
	}
	else
	{
		echo '<script>alert("비밀번호가 다릅니다."); history.back(-1); </script>';
	}

?>