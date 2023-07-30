<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";
	include "login_check.php";

	$oldpw = hash("sha256",$_POST['oldpw']);
	$newpw = hash("sha256",$_POST['newpw']);
	$name = $_POST['newname'];
	$seq = $_POST['seq'];
	$memberBirth = $_POST['birthNum'];
	$memberArea = $_POST['area'];
	$memberSex = $_POST['gender'];
	$Color = $_POST['color'];
	$memberColor = implode(",",$Color);
	$memberPhone = $_POST['Phone1'].'-'.$_POST['Phone2'].'-'.$_POST['Phone3'];
	$memberEmail = $_POST['Email'].'@'.$_POST['Email_sub'];

	$sql = mysqli_query($connect,"select memberPw from member29 where memberPw='".$oldpw."'");
	$board = mysqli_fetch_array($sql);

	if($oldpw != $board['memberPw'])
	{
			echo '<script>alert("비밀번호가 다릅니다."); history.back();</script>';
			exit;
	}

	if($_POST['newpw'] == "")
	{
		$sql = mysqli_query($connect,"update member29 set memberPw='".$oldpw."',memberName='".$name."',modifytime=now(),memberBirth='".$memberBirth."',memberArea='".$memberArea."',memberSex='".$memberSex."',memberColor='".$memberColor."',memberPhone='".$memberPhone."',memberEmail='".$memberEmail."' where seqno='".$seq."'");	
			
		echo '<script>alert("변경 완료되었습니다.");</script>';
	}
		else
		{	
			$sql = mysqli_query($connect,"update member29 set memberPw='".$newpw."',memberName='".$name."',modifytime=now(),memberBirth='".$memberBirth."',memberArea='".$memberArea."',memberSex='".$memberSex."',memberColor='".$memberColor."',memberPhone='".$memberPhone."',memberEmail='".$memberEmail." where seqno='".$seq."'");
		
			
			echo '<script>alert("변경 완료되었습니다.");</script>';
		}


?>
<meta http-equiv="refresh" content="0 url=list.php"/>