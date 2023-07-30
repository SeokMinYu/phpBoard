<?php
	
	include "boardDB.php";
	include "logindex.php";

	$pw = $_POST['pw'];
	$seqno = $_POST['del'];

	if($pw == $userPw) 
		{
			$date = date('y-m-d H:i:s');
			$delok = "del";
			$timesql = mysqli_query($db,"update member2 set deletetime='".$date."',memberdelete='".$delok."' where seqno='".$seqno."'");

			echo '<script>alert("탈퇴하였습니다."); location.href="logout.php"; </script>';
		}
		else
		{
			echo '<script>alert("비밀번호가 다릅니다."); history.back(); </script>';
		}

?>