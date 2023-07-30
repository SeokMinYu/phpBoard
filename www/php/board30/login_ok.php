<?php
	//ini_set("session.cookie_lifetime", "86400"); 
	//ini_set("session.cache_expire", "86400"); 
	//ini_set("session.gc_maxlifetime", "86400"); 

	//ini_set("session.save_path", "/session"); 
	ini_set('session.cookie_secure', On);

	if (!session_id()) {
		session_set_cookie_params(0, '/',"tjrals627.cafe24.com",true,true);
		@ini_set("session.cookie_domain", "tjrals627.cafe24.com");
		session_start();
	}

	//session_set_cookie_params(0,"/","tjrals627.cafe24.com",true,true);
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";
	
	$user_id = $_POST['user_id'];
	$user_pw  = $_POST['user_pw'];
	$passhash = hash("sha256",$user_pw);

	// 디비에서 아이디 비밀번호 조회
	$usersql = mysqli_query($connect,"select * from member30 where memberId='".$user_id."' and memberPw='".$passhash."'");
	$loginValue = mysqli_fetch_array($usersql);
	

	if($loginValue['memberdelete'] != "")
	{
		echo '<script>alert("탈퇴한 회원입니다.");location.href="login.php";</script>';
	}

	if($loginValue != "")
	{
		$_SESSION['user_id']=$user_id;
		$_SESSION['seq']=$loginValue['seqno'];

		$cnt = $loginValue['logincnt'] + 1;
		$sql = mysqli_query($connect,"update member30 set logincnt='".$cnt."' where memberId='".$user_id."' and memberPw='".$passhash."'");
		echo '<script>location.href="list.php";</script>';
		
	}
	else
	{
		echo '<script>alert("아이디 또는 패스워드가 잘못되었습니다.");history.back();</script>';
	}
?>