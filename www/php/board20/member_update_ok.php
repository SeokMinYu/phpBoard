<?php
	header('Content-Type : text/html; charset=utf-8');

	include "DBconnect.php";
	include "login_check.php";

	$oldpw = $_POST['oldpw'];
	$oldpass = hash("sha256",$oldpw);

	$newpw = $_POST['newpw'];
	$newpass = hash("sha256",$newpw);

	$name = $_POST['newname'];
	$seq = $_POST['seq'];
	$userBirth = $_POST['birthNum'];
	$userArea = $_POST['area'];
	$userSex = $_POST['gender'];
	$Color = $_POST['color'];
	$userColor = implode(",",$Color);
	$userPhone = $_POST['Phone1'].'-'.$_POST['Phone2'].'-'.$_POST['Phone3'];
	$userEmail = $_POST['Email'].'@'.$_POST['Email_sub'];


	if($oldpass != $userPw)
	{
			echo '<script>alert("비밀번호가 다릅니다."); history.back();</script>';
			exit;
	}

	if($newpw == "")
	{
		$sql = mysqli_query($connect,"update member20 set memberPw='".$oldpass."',memberName='".$name."',modifytime=now(),userBrith='".$userBirth."',userArea='".$userArea."',userSex='".$userSex."',userColor='".$userColor."',userPhone='".$userPhone."',userEmail='".$userEmail."' where seqno='".$seq."'");	
			
		echo '<script>alert("변경 완료되었습니다.");</script>';
	}
		else
		{	
			$sql = mysqli_query($connect,"update member20 set memberPw='".$newpass."',memberName='".$name."',modifytime=now(),userBrith='".$userBirth."',userArea='".$userArea."',userSex='".$userSex."',userColor='".$userColor."',userPhone='".$userPhone."',userEmail='".$userEmail." where seqno='".$seq."'");
		
			
			echo '<script>alert("변경 완료되었습니다.");</script>';
		}


?>
<meta http-equiv="refresh" content="0 url=list.php"/>