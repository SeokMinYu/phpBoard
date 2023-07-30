<?php

	include "DBconnect.php";
	include "login_check.php";

	$oldpw = $_POST['oldpw'];
	$oldpass = hash("sha256",$oldpw);

	$newpw = $_POST['newpw'];
	$newpass = hash("sha256",$newpw);

	$name = $_POST['newname'];
	$seq = $_POST['seq'];
	$memberBirth = $_POST['birthNum'];
	$memberArea = $_POST['area'];
	$memberSex = $_POST['gender'];
	$Color = $_POST['color'];
	$memberColor = implode(",",$Color);
	$memberPhone = $_POST['Phone1'].'-'.$_POST['Phone2'].'-'.$_POST['Phone3'];
	$memberEmail = $_POST['Email'].'@'.$_POST['Email_sub'];


	if($oldpass != $userPw)
	{
			echo '<script>alert("비밀번호가 다릅니다."); history.back();</script>';
			exit;
	}

	if($newpw != "")
	{
		$sql = mysqli_query($connect,"update member30 set 
		memberPw='".$newpass."',memberName='".$name."',modifytime=now(),memberBirth='".$memberBirth."',memberArea='".$memberArea."',memberSex='".$memberSex."',memberColor='".$memberColor."',memberPhone='".$memberPhone."',memberEmail='".$memberEmail."' where seqno='".$seq."'");
	
		echo '<script>alert("변경 완료되었습니다.");</script>';
	}else
	{	
		$sql2 = mysqli_query($connect,"update member30 set memberPw='".$oldpass."',memberName='".$name."',modifytime=now(),memberBirth='".$memberBirth."',memberArea='".$memberArea."',memberSex='".$memberSex."',memberColor='".$memberColor."',memberPhone='".$memberPhone."',memberEmail='".$memberEmail."' where seqno='".$seq."'");	
			
		echo '<script>alert("변경 완료되었습니다.");</script>';
	}


?>
<meta http-equiv="refresh" content="0 url=list.php"/>