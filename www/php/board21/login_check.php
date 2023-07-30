<?php
	session_start();
	header('Content-Type : text/html; charset=utf-8');

	
	$user_id = $_SESSION['user_id'];

	if($user_id == '') //로그인x , 로그인페이지 이동
	{
		echo '<script>location.href="login.php";</script>';
		exit;
	}
	else
	{
		$sql = mysqli_query($connect,"select * from member21 where memberId='".$_SESSION['user_id']."'");
		$row = mysqli_fetch_array($sql);

		$userId = $row['memberId'];
		$userName = $row['memberName'];
		$userPw = $row['memberPw'];
		$userLevel = $row['level'];
		$userSeq = $row['seqno'];
		$delOk = $row['memberdelete'];
	}
?>