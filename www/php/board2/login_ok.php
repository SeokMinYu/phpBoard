<?php
	header('Content-Type : text/html; charset=utf-8');

	session_start();
	include "boardDB.php";

	
	$user_id = $_POST['user_id'];
	$user_pw  = $_POST['user_pw'];

	// 디비에서 아이디 비밀번호 조회
	$usersql = mysqli_query($db,"select * from member2 where memberId='".$user_id."' and memberPw='".$user_pw."'");
	$loginValue = mysqli_fetch_array($usersql);
	

	if($loginValue['memberdelete'] != "")
	{
		echo '<script>alert("탈퇴한 회원입니다.");history.back();</script>';
	}
	if($loginValue != "")
	{
		$_SESSION['user_id']=$user_id;
		$_SESSION['seq']=$loginValue['seqno'];
		echo '<script>location.href="list.php";</script>';
	}
	else
	{
		echo '<script>alert("아이디 또는 패스워드가 잘못되었습니다.");history.back();</script>';
		}
?>



<!--
$loginCheck = false;
			
			if($user_id == "admin" && $user_pw == "1234") 
			{
				$user_name = "관리자";
				$level = 10;
				$loginCheck = true;
			}

			if($user_id == "gaya" && $user_pw == "1234") 
			{
				$user_name = "가야";
				$level = 5;
				$loginCheck = true;
			}

			if ($user_id == "user" && $user_pw == "1234") 
			{
				$user_name = "일반회원";
				$level = 1;
				$loginCheck = true;
			}

			if ( $loginCheck == true )
			{
				echo "로그인 성공";
				setcookie("user_id",$user_id,time()+1200,'/');
				setcookie("user_name",$user_name,time()+1200,'/');
				setcookie("level",$level,time()+1200,'/');
				echo "<script>location.href='list.php';</script>";
			}
			-->