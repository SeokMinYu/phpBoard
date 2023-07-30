<?php

	ini_set("session.cookie_lifetime", "86400"); 
	ini_set("session.cache_expire", "86400"); 
	ini_set("session.gc_maxlifetime", "86400"); 

	//ini_set("session.save_path", "/session"); 
	ini_set('session.cookie_secure', On);

	session_set_cookie_params(0,"/","tjrals627.cafe24.com",true,true);
	session_start();

	
	$user_id = $_SESSION['user_id'];

	if($user_id == '') //로그인x , 로그인페이지 이동
	{
		echo '<script>location.href="https://tjrals627.cafe24.com/php/board30/login.php";</script>';
		exit;
	}
	else
	{
		$sql = mysqli_query($connect,"select * from member30 where memberId='".$_SESSION['user_id']."'");
		$row = mysqli_fetch_array($sql);

		$userId = $row['memberId'];
		$userName = $row['memberName'];
		$userPw = $row['memberPw'];
		$userLevel = $row['level'];
		$userSeq = $row['seqno'];
		$delOk = $row['memberdelete'];
	}
?>