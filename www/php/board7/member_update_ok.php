<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";
	include "login_check.php";

	$oldpw = $_POST['oldpw'];
	$newpw = $_POST['newpw'];
	$name = $_POST['newname'];
	$seq = $_POST['seq'];

	$sql = mysqli_query($connect,"select * from member2 where sepno='".$seq."'");
	$row = mysqli_fetch_array($sql);


	if($oldpw != $userPw)
	{
			echo '<script>alert("비밀번호가 다릅니다."); history.back();</script>';
			exit;
	}

	if($newpw == "" && $name == $userName)
	{
			echo '<script>alert("변경된 정보가 없습니다.");</script>';
	}
	if($newpw == "")
	{
		$sql = mysqli_query($connect,"update member2 set memberPw='".$oldpw."',memberName='".$name."',modifytime=now() where seqno='".$seq."'");	
			
		echo '<script>alert("변경 완료되었습니다.");</script>';
	}
		else
		{	
			$sql = mysqli_query($connect,"update member2 set memberPw='".$newpw."',memberName='".$name."',modifytime=now() where seqno='".$seq."'");
		
			
			echo '<script>alert("변경 완료되었습니다.");</script>';
		}


?>
<meta http-equiv="refresh" content="0 url=list.php"/>