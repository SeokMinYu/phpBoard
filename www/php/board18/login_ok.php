<?php
	session_start();
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";
	
	$user_id = $_POST['user_id'];
	$user_pw  = $_POST['user_pw'];
	$passhash = hash("sha256",$user_pw);

	// 디비에서 아이디 비밀번호 조회
	$usersql = mysqli_query($connect,"select * from member18 where memberId='".$user_id."' and memberPw='".$passhash."'");
	$loginValue = mysqli_fetch_array($usersql);
	

	if($loginValue['memberdelete'] != "")
	{
		echo '<script>alert("탈퇴한 회원입니다.");history.back();</script>';
	}

	if($loginValue != "")
	{
		$_SESSION['user_id']=$user_id;
		$_SESSION['seq']=$loginValue['seqno'];

		$cnt = $loginValue['logincnt'] + 1;
		$sql = mysqli_query($connect,"update member18 set logincnt='".$cnt."' where memberId='".$user_id."' and memberPw='".$passhash."'");
		echo '<script>location.href="list.php";</script>';
		
	}
	else
	{
		echo '<script>alert("아이디 또는 패스워드가 잘못되었습니다.");history.back();</script>';
	}
?>